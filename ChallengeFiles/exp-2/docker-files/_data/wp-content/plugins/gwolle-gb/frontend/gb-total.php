<?php
/*
 *
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Display the total number of entries in a book.
 *
 * @param string $html html content of the filter.
 * @param array $args the parameters of the query for visible entries. Defaults to 'book_id' = 1.
 * @return string $html new html content of the filter.
 *
 * @since 2.3.2
 */
function gwolle_gb_addon_get_total_entries( $html, $args ) {
	_deprecated_function( __FUNCTION__, ' 2.6.0', 'gwolle_gb_get_total_entries()' );
	return gwolle_gb_get_total_entries( $html, $args );
}


/*
 * Display the total number of entries in a book.
 *
 * @param string $html html content of the filter.
 * @param array $args the parameters of the query for visible entries. Defaults to 'book_id' = 1.
 * @return string $html new html content of the filter.
 *
 * @since 2.6.0
 */
function gwolle_gb_get_total_entries( $html, $args ) {

	if ( ! isset($args['book_id']) ) {
		$args['book_id'] = 1; // default
	}

	$is_search = gwolle_gb_is_search();
	if ( $is_search ) {
		$entries_total = gwolle_gb_get_entry_count_from_search(
			array(
				'checked' => 'checked',
				'trash'   => 'notrash',
				'spam'    => 'nospam',
				'book_id' => $args['book_id'],
			)
		);
	} else {
		$key = 'gwolle_gb_frontend_pagination_book_' . $args['book_id'];
		$entries_total = get_transient( $key );
		if ( false === $entries_total ) {
			$entries_total = gwolle_gb_get_entry_count(
				array(
					'checked' => 'checked',
					'trash'   => 'notrash',
					'spam'    => 'nospam',
					'book_id' => $args['book_id'],
				)
			);
			set_transient( $key, $entries_total, DAY_IN_SECONDS );
		}
	}
	$html .= '<div id="gwolle-gb-total" class="gwolle-gb-total">' .
		sprintf( _n( '%d entry.', '%d entries.', $entries_total, 'gwolle-gb' ), $entries_total )
		. '</div>';

	return $html;

}
add_filter( 'gwolle_gb_entries_list_before', 'gwolle_gb_get_total_entries', 8, 2 );
