<?php

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Called by the shortcode or template function.
 * Reading mode of the guestbook frontend.
 *
 * @param array $shortcode_atts shortcode attributes
 * @param string $shortcode the shortcode that was used
 * @return string html with the list of entries
 */
function gwolle_gb_frontend_read( $shortcode_atts, $shortcode ) {

	$output = '';

	$is_search = gwolle_gb_is_search();
	$entries_list_class = 'gwolle-gb-read';
	if ( get_option( 'gwolle_gb-form_ajax', 'true' ) === 'true' ) {
		$entries_list_class .= ' gwolle-gb-ajax';
	}

	/* Show single entry if requested and is not search. */
	if ( ( (int) $shortcode_atts['entry_id'] > 0 && ! $is_search ) || ( isset($_GET['entry_id']) && (int) $_GET['entry_id'] > 0 && ! $is_search ) ) {

		$entries_list_class .= ' gwolle-gb-single-entry';

		if ( (int) $shortcode_atts['entry_id'] > 0 ) {
			$entry_id = (int) $shortcode_atts['entry_id'];
		} else {
			$entry_id = (int) $_GET['entry_id'];
		}

		$entry = new gwolle_gb_entry();
		$result = $entry->load( $entry_id );
		$entry_book_id = $entry->get_book_id();
		if ( ! $result ) {
			// No entry loaded.
			$output .= esc_html__( 'Sorry, but this entry does not seem to exist.', 'gwolle-gb' );
		} else if ( $entry->get_isspam() === 1 || $entry->get_istrash() === 1 || $entry->get_ischecked() === 0 ) {
			// Not visible.
			$output .= esc_html__( 'Sorry, but this entry does not seem to exist.', 'gwolle-gb' );
		} else if ( $entry_book_id !== (int) $shortcode_atts['book_id'] ) {
			// Not the right book.
			$output .= esc_html__( 'Sorry, but this entry does not seem to exist.', 'gwolle-gb' );
		} else {

			$entries_list_class = apply_filters( 'gwolle_gb_entries_list_class', $entries_list_class );
			$output .= '<div class="' . esc_attr( $entries_list_class ) . '" data-book_id="' . (int) $shortcode_atts['book_id'] . '">';

			$first = true;
			$counter = 0;
			$output .= gwolle_gb_single_view( $entry, $first, $counter );

			$output .= '</div>';

			// Add filter for the complete output.
			$output = apply_filters( 'gwolle_gb_entries_read', $output);

		}

		return $output;

	}

	/* List view. */
	$num_entries = (int) get_option('gwolle_gb-entriesPerPage', 20);
	$num_entries = (int) apply_filters( 'gwolle_gb_read_num_entries', $num_entries, $shortcode_atts );

	/* Get the total count for the frontend in case of search. */
	if ( $is_search ) {
		$entries_total = gwolle_gb_get_entry_count_from_search(
			array(
				'checked' => 'checked',
				'trash'   => 'notrash',
				'spam'    => 'nospam',
				'book_id' => $shortcode_atts['book_id'],
			)
		);
	} else {
		/* Get the total count from cache/transient or from database. */
		$key = 'gwolle_gb_frontend_pagination_book_' . $shortcode_atts['book_id'];
		$entries_total = get_transient( $key );
		if ( false === $entries_total ) {
			$entries_total = gwolle_gb_get_entry_count(
				array(
					'checked' => 'checked',
					'trash'   => 'notrash',
					'spam'    => 'nospam',
					'book_id' => $shortcode_atts['book_id'],
				)
			);
			set_transient( $key, $entries_total, DAY_IN_SECONDS );
		}
	}
	$pages_total = (int) ceil( $entries_total / $num_entries );

	$page_num = 1;
	if ( isset($_GET['pageNum']) && is_numeric($_GET['pageNum']) ) {
		$page_num = (int) $_GET['pageNum'];
	}

	if ( $page_num > $pages_total ) {
		// Page doesnot exist
		$page_num = 1;
	}

	if ( $page_num === 1 && $entries_total > 0 ) {
		$offset = 0;
	} else if ( $entries_total === 0 ) {
		$offset = 0;
	} else {
		$offset = ( $page_num - 1 ) * $num_entries;
	}

	if ( $is_search ) {
		$entries_list_class .= ' gwolle-gb-entries-list-search';
	} else {
		$entries_list_class .= ' gwolle-gb-entries-list';
	}

	/* Get the entries for the frontend */
	if ( $is_search ) {
		$entries = gwolle_gb_get_entries_from_search(
			array(
				'offset'      => $offset,
				'num_entries' => $num_entries,
				'checked'     => 'checked',
				'trash'       => 'notrash',
				'spam'        => 'nospam',
				'book_id'     => $shortcode_atts['book_id'],
			)
		);
	} else if ( $is_search && isset($_GET['show_all']) && $_GET['show_all'] === 'true' ) {
		$entries = gwolle_gb_get_entries_from_search(
			array(
				'offset'      => 0,
				'num_entries' => -1,
				'checked'     => 'checked',
				'trash'       => 'notrash',
				'spam'        => 'nospam',
				'book_id'     => $shortcode_atts['book_id'],
			)
		);
		$page_num = 0; // do not have it set to 1, this way the '1' will be clickable too.
	} else if ( isset($_GET['show_all']) && $_GET['show_all'] === 'true' ) {
		$entries = gwolle_gb_get_entries(
			array(
				'offset'      => 0,
				'num_entries' => -1,
				'checked'     => 'checked',
				'trash'       => 'notrash',
				'spam'        => 'nospam',
				'book_id'     => $shortcode_atts['book_id'],
			)
		);
		$page_num = 0; // do not have it set to 1, this way the '1' will be clickable too.
	} else {
		$entries = gwolle_gb_get_entries(
			array(
				'offset'      => $offset,
				'num_entries' => $num_entries,
				'checked'     => 'checked',
				'trash'       => 'notrash',
				'spam'        => 'nospam',
				'book_id'     => $shortcode_atts['book_id'],
			)
		);
	}


	/* Page navigation on top */
	$navigation = (int) get_option( 'gwolle_gb-navigation', 0 );
	if ( $navigation === 0 ) {
		$pagination = gwolle_gb_pagination_frontend( $page_num, $pages_total );
		$output .= $pagination;
	} else if ( $navigation === 1 ) {
		$entries_list_class .= ' gwolle_gb_infinite gwolle-gb-infinite';
	}
	$entries_list_class = apply_filters( 'gwolle_gb_entries_list_class', $entries_list_class );

	/* Entries from the template */
	if ( ! is_array( $entries ) || empty( $entries ) ) {
		$no_entries = apply_filters( 'gwolle_gb_read_no_entries', esc_html__('(no entries yet)', 'gwolle-gb') );
		$output .= '<div class="' . esc_attr( $entries_list_class ) . '" data-book_id="' . (int) $shortcode_atts['book_id'] . '">';
		$output .= $no_entries;
		$output .= '</div>';
	} else {
		$first = true;

		$output .= '<div class="' . esc_attr( $entries_list_class ) . '" data-book_id="' . (int) $shortcode_atts['book_id'] . '" data-page_id="' . (int) $page_num . '">
		';

		$args = array(
				'checked' => 'checked',
				'trash'   => 'notrash',
				'spam'    => 'nospam',
				'book_id' => (int) $shortcode_atts['book_id'],
			);
		$output .= apply_filters( 'gwolle_gb_entries_list_before', '', $args );


		// Try to load and require_once the template from the themes folders.
		if ( locate_template( array( 'gwolle_gb-entry.php' ), true, true ) === '') {

			$output .= '<!-- Gwolle-GB Entry: Default Template Loaded -->
				';

			// No template found and loaded in the theme folders.
			// Load the template from the plugin folder.
			require_once  GWOLLE_GB_DIR . '/frontend/gwolle_gb-entry.php';

		} else {

			$output .= '<!-- Gwolle-GB Entry: Custom Template Loaded -->
				';

		}

		$counter = 0;
		foreach ($entries as $entry) {
			$counter++;

			// Run the function from the template to get the entry.
			$entry_output = gwolle_gb_entry_template( $entry, $first, $counter );

			$first = false;

			// Add a filter for each entry, so devs can add or remove parts.
			$output .= apply_filters( 'gwolle_gb_entry_read', $entry_output, $entry );

		}

		$output .= '</div>';

	}


	/* Page navigation on bottom */
	if ( $navigation === 0 ) {
		$output .= $pagination;
	}


	// Add filter for the complete output.
	$output = apply_filters( 'gwolle_gb_entries_read', $output);

	return $output;

}
