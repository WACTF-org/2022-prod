<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * gwolle_gb_get_entries
 * Get guestbook entries from the database.
 *
 * @param array $args
 * - num_entries   int: Number of requested entries. -1 will return all requested entries.
 * - offset        int: Start after this entry.
 * - checked       string: 'checked' or 'unchecked', List the entries that are checked or unchecked.
 * - trash         string: 'trash' or 'notrash', List the entries that are in trash or not in trash.
 * - spam          string: 'spam' or 'nospam', List the entries marked as spam or as no spam.
 * - author_id     string: Entries associated with this author_id (since 1.5.0).
 * - email         string: Entries associated with this emailaddress.
 * - no_email      string: Entries not associated with this emailaddress (since 2.6.1).
 * - no_moderators string: 'true', Only entries not written by a moderator (might be expensive with many users) (since 1.5.0).
 * - book_id       int: Only entries from this book. Default in the shortcode is 1 (since 1.5.1).
 * - date_query    array:
 *   - datetime    int: timestamp (non-inclusive)
 *   - before      bool: before this datetime
 *   - after       bool: after this datetime
 *
 * @return mixed array of objects of gwolle_gb_entry, false if no entries found.
 *
 * @since 1.0.0
 */
function gwolle_gb_get_entries( $args = array() ) {
	global $wpdb;

	$where = " 1 = %d";
	$values = array( 1 );

	if ( ! is_array( $args ) ) {
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
	if ( isset( $args['author_id']) ) {
		$where .= "
			AND
			author_id = %d";
		$values[] = (int) $args['author_id'];
	}
	if ( isset($args['email']) ) {
		$where .= "
			AND
			author_email = %s";
		$values[] = $args['email'];
	}
	if ( isset($args['no_email']) ) {
		$where .= "
			AND
			author_email != %s";
		$values[] = $args['no_email'];
	}
	if ( isset($args['no_moderators']) ) {
		$no_moderators = $args['no_moderators'];
		if ( $no_moderators === 'true' ) {
			$users = gwolle_gb_get_moderators();
			if ( is_array($users) && ! empty($users) ) {
				foreach ( $users as $user_info ) {
					$where .= "
						AND
						author_id != %d";
					$values[] = $user_info->ID;
				}
			}
		}
	}
	if ( isset( $args['book_id'] ) && ( (int) $args['book_id'] ) > 0 ) {
		$where .= "
			AND
			book_id = %d";
		$values[] = (int) $args['book_id'];
	}

	if ( isset( $args['date_query'] ) && is_array( $args['date_query'] ) && ! empty( $args['date_query'] ) ) {
		$date_query = $args['date_query'];
		if ( isset( $date_query['datetime'] ) && ( (int) $date_query['datetime'] > 0 ) ) {
			$datetime = $date_query['datetime'];
			if ( isset( $date_query['before'] ) && $date_query['before'] === true ) {
				$where .= "
				AND
				datetime < %d";
				$values[] = (int) $datetime;
			} else if ( isset( $date_query['after'] ) && $date_query['after'] === true ) {
				$where .= "
				AND
				datetime > %d";
				$values[] = (int) $datetime;
			}
		}
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


	$tablename = $wpdb->prefix . "gwolle_gb_entries";

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

	/*
	 * Make sure to use wpdb->prepare in your function, avoid SQL injection attacks.
	 * - $sql is the value with the prepared sql query.
	 * - $sql_nonprepared is the sql query with placeholders still.
	 * - $values is an array with values that will replace those placeholders
	 * - $args are the additional arguments that were passed to this function.
	 */
	$sql = apply_filters( 'gwolle_gb_get_entries_sql', $sql, $sql_nonprepared, $values, $args );


	/* Support caching of the list of entries. */
	$key         = md5( serialize( $sql ) );
	$cache_key   = "gwolle_gb_get_entries:$key";
	$cache_value = wp_cache_get( $cache_key );

	if ( false === $cache_value ) {

		// Do a real query.
		$datalist = $wpdb->get_results( $sql, ARRAY_A );

		wp_cache_add( $cache_key, $datalist );

		// $wpdb->print_error();
		// echo "number of rows: " . $wpdb->num_rows;

	} else {

		// This is data from cache.
		$datalist = $cache_value;

	}


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
 * gwolle_gb_get_entry_ids
 * Function to get guestbook entry IDs from the database.
 *
 * @param array $args
 * - checked       string: 'checked' or 'unchecked', List the entries that are checked or unchecked.
 * - trash         string: 'trash' or 'notrash', List the entries that are in trash or not in trash.
 * - spam          string: 'spam' or 'nospam', List the entries marked as spam or as no spam.
 * - author_id     string: All entries associated with this author_id (since 1.5.0).
 * - email         string: All entries associated with this emailaddress.
 * - no_moderators string: 'true', Only entries not written by a moderator (might be expensive with many users) (since 1.5.0).
 * - book_id       int: Only entries from this book. Default in the shortcode is 1 (since 1.5.1).
 *
 * @return mixed array of ids of gwolle_gb_entry, bool false if no entries found.
 *
 * @since 2.3.0
 */
function gwolle_gb_get_entry_ids( $args = array() ) {
	global $wpdb;

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
	if ( isset( $args['author_id']) ) {
		$where .= "
			AND
			author_id = %d";
		$values[] = (int) $args['author_id'];
	}
	if ( isset($args['email']) ) {
		$where .= "
			AND
			author_email = %s";
		$values[] = $args['email'];
	}
	if ( isset($args['no_moderators']) ) {
		$no_moderators = $args['no_moderators'];
		if ( $no_moderators === 'true' ) {
			$users = gwolle_gb_get_moderators();
			if ( is_array($users) && ! empty($users) ) {
				foreach ( $users as $user_info ) {
					$where .= "
						AND
						author_id != %d";
					$values[] = $user_info->ID;
				}
			}
		}
	}
	if ( isset( $args['book_id'] ) && ( (int) $args['book_id'] ) > 0 ) {
		$where .= "
			AND
			book_id = %d";
		$values[] = (int) $args['book_id'];
	}

	$tablename = $wpdb->prefix . "gwolle_gb_entries";

	$sql_nonprepared = "
			SELECT
				`id`
			FROM
				" . $tablename . "
			WHERE
				" . $where . "
			ORDER BY
				datetime DESC
			LIMIT 999999999999999
			OFFSET 0
			;";

	$sql = $wpdb->prepare( $sql_nonprepared, $values );

	/*
	 * Make sure to use wpdb->prepare in your function, avoid SQL injection attacks.
	 * - $sql is the value with the prepared sql query.
	 * - $sql_nonprepared is the sql query with placeholders still.
	 * - $values is an array with values that will replace those placeholders
	 * - $args are the additional arguments that were passed to this function.
	 */
	$sql = apply_filters( 'gwolle_gb_get_entries_sql', $sql, $sql_nonprepared, $values, $args );

	$entry_ids = $wpdb->get_results( $sql, ARRAY_N );

	// $wpdb->print_error();
	// echo "number of rows: " . $wpdb->num_rows;

	if ( is_array($entry_ids) && ! empty($entry_ids) ) {
		$_entry_ids = array();
		foreach ( $entry_ids as $entry_id ) {
			$_entry_ids[] = (int) $entry_id[0];
		}
		return $_entry_ids;
	}
	return false;

}


/*
 * Function to delete guestbook entries from the database.
 * Removes the log entries as well.
 *
 * @param string $status 'spam' or 'trash'
 * - spam         string: 'spam',  delete the entries marked as spam
 * - trash        string: 'trash', delete the entries that are in trash
 *
 * @return int Number of deleted entries, 0 if no entries found.
 *
 * @since 1.0.0
 */
function gwolle_gb_del_entries( $status ) {
	global $wpdb;
	$where = '';
	$values = array();

	// First get all the id's, so we can remove the logs later

	if ( $status === 'spam' ) {
		$where = "
			isspam = %d";
		$values[] = 1;
	} else if ( $status === 'trash' ) {
		$where = "
			istrash = %d";
		$values[] = 1;
	} else {
		return 0; // not the right $status
	}

	$sql = "
			SELECT
				`id`
			FROM
				$wpdb->gwolle_gb_entries
			WHERE
				" . $where . "
			LIMIT 999999999999999
			OFFSET 0
		;";

	$sql = $wpdb->prepare( $sql, $values );

	$datalist = $wpdb->get_results( $sql, ARRAY_A );

	if ( is_array($datalist) && ! empty($datalist) ) {

		$sql = "
			DELETE
			FROM
				$wpdb->gwolle_gb_entries
			WHERE
				" . $where . "
			;";

		$result = $wpdb->query(
			$wpdb->prepare( $sql, $values )
		);

		if ( $result > 0 ) {
			gwolle_gb_clear_cache();

			// Also remove the log entries
			foreach ( $datalist as $id ) {
				gwolle_gb_del_log_entries( $id['id'] );
			}

			return $result;
		}
	}
	return 0;

}


/*
 * gwolle_gb_get_entry_count
 * Get the number of entries from the database.
 *
 * @param array $args
 * - checked  string: 'checked' or 'unchecked', List the entries that are checked or not checked
 * - trash    string: 'trash' or 'notrash', List the entries that are deleted or not deleted
 * - spam     string: 'spam' or 'nospam', List the entries marked as spam or as no spam
 * - all      string: 'all', List all entries
 * - book_id  int: Only entries from this book. Default in the shortcode is 1 (since 1.5.1).
 *
 * @return mixed int with the count of the entries, false if there's an error.
 */
function gwolle_gb_get_entry_count( $args ) {

	global $wpdb;


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

	$tablename = $wpdb->prefix . "gwolle_gb_entries";

	$sql = "
			SELECT
				COUNT(id) AS count
			FROM
				" . $tablename . "
			WHERE
				" . $where . "
			;";

	$sql = $wpdb->prepare( $sql, $values );


	/* Support caching of the result. */
	$key         = md5( serialize( $sql ) );
	$cache_key   = "gwolle_gb_get_entry_count:$key";
	$cache_value = wp_cache_get( $cache_key );

	if ( false === $cache_value ) {

		// Do a real query.
		$data = $wpdb->get_results( $sql, ARRAY_A );

		wp_cache_add( $cache_key, $data );

		// $wpdb->print_error();
		// echo "number of rows: " . $wpdb->num_rows;

	} else {

		// This is data from cache.
		$data = $cache_value;

	}

	return (int) $data[0]['count'];

}
