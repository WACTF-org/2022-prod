<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * gwolle_gb_get_entries_from_search
 * Get guestbook entries from the database for a search query from the search widget.
 *
 * @param array $args
 * - num_entries   int: Number of requested entries. -1 will return all requested entries.
 * - offset        int: Start after this entry.
 * - book_id       int: Only entries from this book. Default in the shortcode is 1 (since 1.5.1).
 *
 * @return mixed array of objects of gwolle_gb_entry, false if no entries found.
 *
 * @since 3.0.0
 */
function gwolle_gb_get_entries_from_search( $args = array() ) {
	global $wpdb;

	$where = " 1 = %d";
	$values = array( 1 );

	if ( ! is_array( $args ) ) {
		return false;
	}

	$where .= "
		AND
		ischecked = %d";
	$values[] = 1;

	$where .= "
		AND
		isspam = %d";
		$values[] = 0;

	$where .= "
		AND
		istrash = %d";
		$values[] = 0;

	if ( isset( $args['book_id'] ) && ( (int) $args['book_id'] ) > 0 ) {
		$where .= "
			AND
			book_id = %d";
		$values[] = (int) $args['book_id'];
	}

	$search_query = gwolle_gb_is_search();
	$tablename = $wpdb->prefix . "gwolle_gb_entries";
	foreach ( $search_query as $term ) {
		$like = '%' . $wpdb->esc_like( $term ) . '%';
		$where .= $wpdb->prepare( "
			AND (
			($tablename . author_name LIKE %s)
			OR
			($tablename . content LIKE %s)
			OR
			($tablename . admin_reply LIKE %s))",
			$like, $like, $like
		);
	}

	// Offset
	$offset = " OFFSET 0 "; // default
	if ( isset($args['offset']) && (int) $args['offset'] > 0 ) {
		$offset = " OFFSET " . (int) $args['offset'];
	}

	// Limit
	if ( is_admin() ) {
		$perpage_option = (int) get_option('gwolle_gb-entries_per_page', 20);
	} else {
		$perpage_option = (int) get_option('gwolle_gb-entriesPerPage', 20);
	}

	$limit = " LIMIT " . $perpage_option; // default
	if ( isset($args['num_entries']) && (int) $args['num_entries'] > 0 ) {
		$limit = " LIMIT " . (int) $args['num_entries'];
	} else if ( isset($args['num_entries']) && (int) $args['num_entries'] === -1 ) {
		$limit = ' LIMIT 999999999999999 ';
		$offset = ' OFFSET 0 ';
	}

	$sql_nonprepared = "
			SELECT
				`id`,
				`author_name`,
				`author_id`,
				`author_email`,
				`author_origin`,
				`author_website`,
				`author_ip`,
				`author_host`,
				`content`,
				`datetime`,
				`ischecked`,
				`checkedby`,
				`istrash`,
				`isspam`,
				`admin_reply`,
				`admin_reply_uid`,
				`book_id`
			FROM
				" . $tablename . "
			WHERE
				" . $where . "
			ORDER BY
				datetime DESC
			" . $limit . " " . $offset . "
			;";

	$sql = $wpdb->prepare( $sql_nonprepared, $values );

	// Do a real query, we don't cache anything from searches.
	$datalist = $wpdb->get_results( $sql, ARRAY_A );

	// $wpdb->print_error();
	// echo "number of rows: " . $wpdb->num_rows;


	if ( is_array($datalist) && ! empty($datalist) ) {
		$entries = array();

		foreach ( $datalist as $data ) {

			// Use the fields that the setter method expects
			$item = array(
				'id'              => (int) $data['id'],
				'author_name'     => stripslashes($data['author_name']),
				'author_id'       => (int) $data['author_id'],
				'author_email'    => stripslashes($data['author_email']),
				'author_origin'   => stripslashes($data['author_origin']),
				'author_website'  => stripslashes($data['author_website']),
				'author_ip'       => $data['author_ip'],
				'author_host'     => $data['author_host'],
				'content'         => stripslashes($data['content']),
				'datetime'        => $data['datetime'],
				'ischecked'       => (int) $data['ischecked'],
				'checkedby'       => (int) $data['checkedby'],
				'istrash'         => (int) $data['istrash'],
				'isspam'          => (int) $data['isspam'],
				'admin_reply'     => stripslashes($data['admin_reply']),
				'admin_reply_uid' => (int) $data['admin_reply_uid'],
				'book_id'         => (int) $data['book_id'],
			);

			$entry = new gwolle_gb_entry();

			$entry->set_data( $item );

			// Add entry to the array of all entries
			$entries[] = $entry;
		}
		return $entries;
	}
	return false;

}


/*
 * Function to set/get if this is a search query for guestbook entries.
 *
 * @param  bool $is_search_input set the search to true.
 *
 * @return mixed
 *         array with strings with terms from the search query.
 *         bool false, this is not a search.
 *
 * @since 3.0.0
 */
function gwolle_gb_is_search() {

	static $search_query_static;

	if ( $search_query_static ) {
		return $search_query_static;
	}

	if (isset($_GET['gwolle-gb-search-input'])) {
		$search_query = trim($_GET['gwolle-gb-search-input']);

		if ( strlen( $search_query ) > 0 ) {

			// added slashes screw with quote grouping when done early, so done later
			$search_query = stripslashes( $search_query );
			$search_query = urldecode( $search_query );
			// there are no line breaks in <input /> fields
			$search_query = str_replace( array( "\r", "\n" ), '', $search_query );
			$search_query = sanitize_text_field( $search_query );

			$search_query = explode( ' ', $search_query );

			$search_query_static = $search_query; // use static var as cache.
			return $search_query_static;

		}
	}

	return false;

}


/*
 * gwolle_gb_get_entry_count_from_search
 * Get the number of entries from the database for a search query.
 *
 * @param array $args
 * - checked  string: 'checked' or 'unchecked', List the entries that are checked or not checked
 * - trash    string: 'trash' or 'notrash', List the entries that are deleted or not deleted
 * - spam     string: 'spam' or 'nospam', List the entries marked as spam or as no spam
 * - all      string: 'all', List all entries
 * - book_id  int: Only entries from this book. Default in the shortcode is 1.
 *
 * @return mixed int with the count of the entries, false if there's an error.
 *
 * @since 3.0.0
 */
function gwolle_gb_get_entry_count_from_search( $args ) {

	global $wpdb;

	static $count_static;
	if ( $count_static ) {
		return $count_static;
	}

	$where = " 1 = %d";
	$values = array( 1 );

	if ( ! is_array($args) ) {
		return false;
	}

	if ( isset($args['checked']) ) {
		if ( $args['checked'] === 'checked' || $args['checked'] === 'unchecked' ) {
			$where .= "
				AND
				ischecked = %d";
			if ( $args['checked'] === 'checked' ) {
				$values[] = 1;
			} else if ( $args['checked'] === 'unchecked' ) {
				$values[] = 0;
			}
		}
	}
	if ( isset($args['spam']) ) {
		if ( $args['spam'] === 'spam' || $args['spam'] === 'nospam' ) {
			$where .= "
				AND
				isspam = %d";
			if ( $args['spam'] === 'spam' ) {
				$values[] = 1;
			} else if ( $args['spam'] === 'nospam' ) {
				$values[] = 0;
			}
		}
	}
	if ( isset($args['trash']) ) {
		if ( $args['trash'] === 'trash' || $args['trash'] === 'notrash' ) {
			$where .= "
				AND
				istrash = %d";
			if ( $args['trash'] === 'trash' ) {
				$values[] = 1;
			} else if ( $args['trash'] === 'notrash' ) {
				$values[] = 0;
			}
		}
	}
	if ( isset( $args['book_id'] ) && ( (int) $args['book_id'] ) > 0 ) {
		$where .= "
			AND
			book_id = %d";
		$values[] = (int) $args['book_id'];
	}

	$search_query = gwolle_gb_is_search();
	$tablename = $wpdb->prefix . "gwolle_gb_entries";
	foreach ( $search_query as $search_term ) {
		$like = '%' . $wpdb->esc_like( $search_term ) . '%';
		$where .= $wpdb->prepare( "
			AND (
			($tablename . author_name LIKE %s)
			OR
			($tablename . content LIKE %s)
			OR
			($tablename . admin_reply LIKE %s))",
			$like, $like, $like
		);
	}

	$sql = "
			SELECT
				COUNT(id) AS count
			FROM
				" . $tablename . "
			WHERE
				" . $where . "
			;";

	$sql = $wpdb->prepare( $sql, $values );

	// Do a real query.
	$data = $wpdb->get_results( $sql, ARRAY_A );

	$count_static = (int) $data[0]['count']; // use static var as cache.
	return $count_static;

}


/*
 * Function to highlight the text in search results.
 *
 * @param string $text of whatever needs to be highlighted.
 *
 * @return string $text with highlighted search words.
 *
 * @since 3.1.8
 */
function gwolle_gb_highlight_searchresults( $text ) {

	$is_search = gwolle_gb_is_search();
	if ( ! $is_search ) {
		return $text;
	}

	$text = gwolle_gb_highlight( $text, $is_search );

	return $text;

}
add_filter( 'gwolle_gb_entry_the_author_name', 'gwolle_gb_highlight_searchresults', 10, 1 );
add_filter( 'gwolle_gb_entry_the_content', 'gwolle_gb_highlight_searchresults', 10, 1 );
add_filter( 'gwolle_gb_entry_the_admin_reply', 'gwolle_gb_highlight_searchresults', 10, 1 );


