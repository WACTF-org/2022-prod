<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Add a new log for an entry.
 *
 * @param  int    $entry_id ID of the entry
 * @param  string $subject one of the possible log_messages
 * @return bool   true or false, depending on succes
 */
function gwolle_gb_add_log_entry( $entry_id, $subject ) {
	global $wpdb;

	if ( ! isset($subject) || ! isset($entry_id) || (int) $entry_id === 0 ) {
		return false;
	}

	$log_messages = array(
		'entry-unchecked',
		'entry-checked',
		'marked-as-spam',
		'marked-as-not-spam',
		'marked-by-honeypot',
		'marked-by-nonce',
		'marked-by-akismet',
		'marked-by-sfs',
		'marked-by-longtext',
		'marked-by-linkchecker',
		'marked-by-timeout',
		'entry-edited',
		'imported-from-dmsguestbook',
		'imported-from-wp',
		'imported-from-gwolle',
		'exported-to-csv',
		'entry-trashed',
		'entry-untrashed',
		'admin-reply-added',
		'admin-reply-updated',
		'admin-reply-removed',
		'entry-anonymized',
		'privacy-policy-accepted',
	);
	if ( ! in_array( $subject, $log_messages ) ) {
		return false;
	}

	$result = $wpdb->query( $wpdb->prepare(
		"
		INSERT INTO $wpdb->gwolle_gb_log
		(
			subject,
			entry_id,
			author_id,
			datetime
		) VALUES (
			%s,
			%d,
			%d,
			%d
		)
		",
		array(
			addslashes( $subject ),
			(int) $entry_id,
			(int) get_current_user_id(),
			current_time( 'timestamp' ),
		)
	) );

	if ( $result === 1 ) {
		return true;
	}
	return false;

}


/*
 * Function to get log entries.
 *
 * @param int $entry_id ID of the guestbook entry where the log belongs to
 *
 * @return array with log_entries, each is an array:
 *   id           => (int) id
 *   subject      => (string) subject of the log, what happened
 *   author_id    => (int) author_id of the user responsible for this log entry
 *   datetime     => (int) log_date with timestamp
 *   msg          => (string) subject of the log, what happened. In Human Readable form, translated
 *   author_login => (string) display_name or login_name of the user as standard WP_User
 *   msg_html     => (string) string of html-text ready for display
 *   msg_txt      => (string) string of text ready for display
 *
 */
function gwolle_gb_get_log_entries( $entry_id ) {
	global $wpdb;

	if ( ! isset($entry_id) || (int) $entry_id === 0 ) {
		return false;
	}

	// Message to strings
	$log_messages = array(
		'entry-unchecked'             => /* translators: Log message */ esc_html__('Entry has been locked.', 'gwolle-gb'),
		'entry-checked'               => /* translators: Log message */ esc_html__('Entry has been checked.', 'gwolle-gb'),
		'marked-as-spam'              => /* translators: Log message */ esc_html__('Entry marked as spam.', 'gwolle-gb'),
		'marked-as-not-spam'          => /* translators: Log message */ esc_html__('Entry marked as not spam.', 'gwolle-gb'),
		'marked-by-honeypot'          => /* translators: Log message */ esc_html__('Entry marked by the Honeypot.', 'gwolle-gb'),
		'marked-by-nonce'             => /* translators: Log message */ esc_html__('Entry marked by invalid Nonce.', 'gwolle-gb'),
		'marked-by-akismet'           => /* translators: Log message */ esc_html__('Entry marked by Akismet.', 'gwolle-gb'),
		'marked-by-sfs'               => /* translators: Log message */ esc_html__('Entry marked by Stop Forum Spam.', 'gwolle-gb'),
		'marked-by-longtext'          => /* translators: Log message */ esc_html__('Entry marked for too long text.', 'gwolle-gb'),
		'marked-by-linkchecker'       => /* translators: Log message */ esc_html__('Entry marked for too many links.', 'gwolle-gb'),
		'marked-by-timeout'           => /* translators: Log message */ esc_html__('Entry marked for being submitted too fast.', 'gwolle-gb'),
		'entry-edited'                => /* translators: Log message */ esc_html__('Entry has been edited.', 'gwolle-gb'),
		'imported-from-dmsguestbook'  => /* translators: Log message */ esc_html__('Imported from DMSGuestbook', 'gwolle-gb'),
		'imported-from-wp'            => /* translators: Log message */ esc_html__('Imported from WordPress comments', 'gwolle-gb'),
		'imported-from-gwolle'        => /* translators: Log message */ esc_html__('Imported from Gwolle-GB', 'gwolle-gb'),
		'exported-to-csv'             => /* translators: Log message */ esc_html__('Exported to CSV file', 'gwolle-gb'),
		'entry-trashed'               => /* translators: Log message */ esc_html__('Entry has been trashed.', 'gwolle-gb'),
		'entry-untrashed'             => /* translators: Log message */ esc_html__('Entry has been untrashed.', 'gwolle-gb'),
		'admin-reply-added'           => /* translators: Log message */ esc_html__('Admin reply has been added.', 'gwolle-gb'),
		'admin-reply-updated'         => /* translators: Log message */ esc_html__('Admin reply has been updated.', 'gwolle-gb'),
		'admin-reply-removed'         => /* translators: Log message */ esc_html__('Admin reply has been removed.', 'gwolle-gb'),
		'entry-anonymized'            => /* translators: Log message */ esc_html__('Entry has been anonymized.', 'gwolle-gb'),
		'privacy-policy-accepted'     => /* translators: Log message */ esc_html__('Privacy Policy was accepted.', 'gwolle-gb'),
	);

	$where = " 1 = %d";
	$values = Array(1);
	$tablename = $wpdb->prefix . "gwolle_gb_log";

	$where .= "
		AND
			entry_id = %d";

	$values[] = $entry_id;

	$sql = "
			SELECT
				`id`,
				`subject`,
				`entry_id`,
				`author_id`,
				`datetime`
			FROM
				" . $tablename . "
			WHERE
				" . $where . "
			ORDER BY
				datetime ASC
			;";

	$sql = $wpdb->prepare( $sql, $values );

	$entries = $wpdb->get_results( $sql, ARRAY_A );

	//$wpdb->print_error();
	//echo "number of rows: " . $wpdb->num_rows;

	if ( is_array($entries) && ! empty($entries) ) {

		// Array to store the log entries
		$log_entries = array();

		foreach ( $entries as $entry ) {
			$log_entry = array(
				'id'        => (int) $entry['id'],
				'subject'   => stripslashes($entry['subject']),
				'entry_id'  => (int) $entry['entry_id'],
				'author_id' => (int) $entry['author_id'],
				'datetime'  => (int) $entry['datetime'],
			);

			$log_entry_subject = $log_entry['subject'];
			if ( isset($log_messages["$log_entry_subject"]) ) {
				// Use translation if it exists.
				$log_entry['msg'] = $log_messages["$log_entry_subject"];
			} else {
				$log_entry['msg'] = $log_entry['subject'];
			}

			// Get author's display name or login name if not already done.
			$userdata = get_userdata( $log_entry['author_id'] );
			if ( is_object($userdata) ) {
				if ( isset( $userdata->display_name ) ) {
					$log_entry['author_login'] = $userdata->display_name;
				} else {
					$log_entry['author_login'] = $userdata->user_login;
				}
			} else {
				$log_entry['author_login'] = esc_html__('Unknown', 'gwolle-gb');
			}

			// Construct the message in HTML
			$log_entry['msg_html']  = date_i18n( get_option('date_format'), $log_entry['datetime']) . ', ';
			$log_entry['msg_html'] .= date_i18n( get_option('time_format'), $log_entry['datetime']);
			$log_entry['msg_html'] .= ': ' . $log_entry['msg'];

			if ( $log_entry['author_id'] === get_current_user_id() && $log_entry['author_id'] !== 0 ) {
				$log_entry['msg_html'] .= ' <i>(<strong>' . esc_html__('You', 'gwolle-gb') . '</strong>)</i>';
			} else {
				$log_entry['msg_html'] .= ' <i>(' . $log_entry['author_login'] . ')</i>';
			}

			// Construct the message in plain text
			$log_entry['msg_txt']  = date_i18n( get_option('date_format'), $log_entry['datetime']) . ', ';
			$log_entry['msg_txt'] .= date_i18n( get_option('time_format'), $log_entry['datetime']);
			$log_entry['msg_txt'] .= ': ' . $log_entry['msg'];

			if ( $log_entry['author_id'] === get_current_user_id() && $log_entry['author_id'] !== 0 ) {
				$log_entry['msg_txt'] .= ' (' . esc_html__('You', 'gwolle-gb') . ')';
			} else {
				$log_entry['msg_txt'] .= ' (' . $log_entry['author_login'] . ')';
			}
			$log_entries[] = $log_entry;
		}

		return $log_entries;
	}
	return false;

}


/*
 * Delete the log entries for a guestbook entry after the entry was removed.
 *
 * @param  int  $entry_id ID of the entry
 * @return bool true or false, depending on succes
 */
function gwolle_gb_del_log_entries( $entry_id ) {
	global $wpdb;

	$entry_id = (int) $entry_id;

	if ( $entry_id === 0 || $entry_id < 0 ) {
		return false;
	}

	$sql = "
		DELETE
		FROM
			$wpdb->gwolle_gb_log
		WHERE
			entry_id = %d";

	$values = array(
			$entry_id,
		);

	$result = $wpdb->query(
			$wpdb->prepare( $sql, $values )
		);


	if ( $result > 0 ) {
		return true;
	}
	return false;

}
add_action( 'gwolle_gb_delete_entry', 'gwolle_gb_del_log_entries' );
