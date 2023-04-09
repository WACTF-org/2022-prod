<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Remove IP address and hostname from existing database entries.
 *
 * @since 2.6.0
 */
function gwolle_gb_remove_ip_host() {
	global $wpdb;

	$wpdb->query( "
		UPDATE `$wpdb->gwolle_gb_entries` SET `author_ip` = '';
	");
	$wpdb->query( "
		UPDATE `$wpdb->gwolle_gb_entries` SET `author_host` = '';
	");

}


/*
 * Add example text to the privacy policy.
 *
 * @since 2.6.0
 */
function gwolle_gb_add_privacy_policy_content() {
	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}

	$content = sprintf(
		'<p>' . esc_html__( 'When visitors leave entries on the site we collect the data entered in the guestbook form and possibly the IP address and hostname of the visitor to help spam detection.', 'gwolle-gb' ) . '</p>' .
		'<p>' . esc_html__( 'An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your entry, your profile picture is visible to the public in the context of your guestbook entry.', 'gwolle-gb' ) . '</p>' .
		'<p>' . esc_html__( 'The entered entry and its metadata may be sent to third parties like Akismet and Stop Forum Spam to help spam detection. Their respective privacy policies are at https://automattic.com/privacy/ and https://www.stopforumspam.com/privacy.', 'gwolle-gb' ) . '</p>'
	);

	wp_add_privacy_policy_content(
		'Gwolle Guestbook',
		wp_kses_post( wpautop( $content, false ) )
	);
}
add_action( 'admin_init', 'gwolle_gb_add_privacy_policy_content' );


/*
 * Registers the personal data exporter for guestbook entries.
 *
 * @since 2.6.0
 *
 * @param  array $exporters An array of personal data exporters.
 * @return array $exporters An array of personal data exporters.
 */
function gwolle_gb_register_personal_data_exporter( $exporters ) {
	$exporters['gwolle-gb'] = array(
		'exporter_friendly_name' => esc_html__( 'Gwolle Guestbook', 'gwolle-gb' ),
		'callback'               => 'gwolle_gb_personal_data_exporter',
	);

	return $exporters;
}
add_filter( 'wp_privacy_personal_data_exporters', 'gwolle_gb_register_personal_data_exporter' );


/*
 * Finds and exports personal data associated with an email address from the entries table.
 *
 * @since 2.6.0
 *
 * @param  string $email_address The entry author email address.
 * @param  int    $page          Export page.
 * @return array  $return        An array of personal data.
 */
function gwolle_gb_personal_data_exporter( $email_address, $page = 1 ) {
	$number = 100;
	$page   = (int) $page;

	$data_to_export = array();

	$entry_prop_to_export = array(
		'author_name'    => esc_html__( 'Author', 'gwolle-gb' ),
		'author_email'   => esc_html__( 'Email', 'gwolle-gb' ),
		'author_origin'  => esc_html__( 'Origin', 'gwolle-gb' ),
		'author_website' => esc_html__( 'Website', 'gwolle-gb' ),
		'author_ip'      => esc_html__( 'IP address', 'gwolle-gb' ),
		'author_host'    => esc_html__( 'Host address', 'gwolle-gb' ),
		'datetime'       => esc_html__( 'Date of the entry', 'gwolle-gb' ),
		'content'        => esc_html__( 'Content', 'gwolle-gb' ),
		'entry_link'     => esc_html__( 'URL of entry', 'gwolle-gb' ),
	);

	/* Used for permalinks */
	$books = gwolle_gb_get_permalinks();

	$offset = $number * ( $page - 1 );
	$entries = gwolle_gb_get_entries(
		array(
			'all'         => 'all',
			'offset'      => $offset,
			'num_entries' => $number,
			'email'       => $email_address,
		)
	);

	if ( ! is_array($entries) || empty($entries) ) {
		return array(
			'data' => array(),
			'done' => true,
		);
	}

	foreach ( (array) $entries as $entry ) {
		$entry_data_to_export = array();
		$entry_id = $entry->get_id();

		foreach ( $entry_prop_to_export as $key => $name ) {
			$value = '';

			switch ( $key ) {
				case 'author_name':
					$value = gwolle_gb_sanitize_output( trim( $entry->get_author_name() ) );
					break;

				case 'author_email':
					$value = $entry->get_author_email();
					break;

				case 'author_origin':
					$value = gwolle_gb_sanitize_output( $entry->get_author_origin() );
					break;

				case 'author_website':
					$value = $entry->get_author_website();
					break;

				case 'author_ip':
					$value = $entry->get_author_ip();
					break;

				case 'author_host':
					$value = $entry->get_author_host();
					break;

				case 'datetime':
					$value = date_i18n( get_option('date_format'), $entry->get_datetime() ) . ' ' . esc_html__('at', 'gwolle-gb') . ' ' . trim(date_i18n( get_option('time_format'), $entry->get_datetime() ));
					break;

				case 'content':
					$entry_content = gwolle_gb_sanitize_output( $entry->get_content(), 'content' );
					if ( get_option( 'gwolle_gb-showLineBreaks', 'false' ) === 'true' ) {
						$entry_content = nl2br($entry_content);
					}
					if ( isset($form_setting['form_bbcode_enabled']) && $form_setting['form_bbcode_enabled'] === 'true' ) {
						$entry_content = gwolle_gb_bbcode_parse($entry_content);
					} else {
						$entry_content = gwolle_gb_bbcode_strip($entry_content);
					}
					$value = $entry_content;
					break;

				case 'entry_link':
					$book_id = $entry->get_book_id();
					$permalink = '';
					if ( isset( $books["$book_id"] ) && isset( $books["$book_id"]['permalink'] ) ) {
						$permalink = $books["$book_id"]['permalink'];
						$permalink = add_query_arg( 'entry_id', $entry_id, $permalink );
					}
					if ( $entry->get_ischecked() === 1 && $entry->get_isspam() === 0 && $entry->get_istrash() === 0 && strlen( $permalink ) > 0 ) {
						$value = sprintf(
							'<a href="%s" target="_blank" rel="noreferrer noopener">%s</a>',
							esc_url( $permalink ),
							esc_html( $permalink )
						);
					} else {
						$value = esc_html__('This entry is Not Visible.', 'gwolle-gb');
					}
					break;

				default:
					break;
			}

			if ( ! empty( $value ) ) {
				$entry_data_to_export[] = array(
					'name'  => $name,
					'value' => $value,
				);
			}
		} // end foreach props, entry is done.

		$data_to_export[] = array(
			'group_id'    => 'gwolle-gb',
			'group_label' => esc_html__( 'Guestbook entries', 'gwolle-gb' ),
			'item_id'     => "gb-entry-{$entry_id}",
			'data'        => $entry_data_to_export,
		);
	} // end foreach entries.

	$done = false;
	if ( count( $entries ) < $number ) {
		$done = true;
	}

	return array(
		'data' => $data_to_export,
		'done' => $done,
	);
}


/*
 * Registers the personal data eraser for guestbook entries.
 *
 * @since 2.6.0
 *
 * @param  array $erasers An array of personal data erasers.
 * @return array $erasers An array of personal data erasers.
 */
function gwolle_gb_register_personal_data_eraser( $erasers ) {
	$erasers['gwolle-gb'] = array(
		'eraser_friendly_name' => esc_html__( 'Gwolle Guestbook', 'gwolle-gb' ),
		'callback'             => 'gwolle_gb_personal_data_eraser',
	);

	return $erasers;
}
add_filter( 'wp_privacy_personal_data_erasers', 'gwolle_gb_register_personal_data_eraser' );


/*
 * Erases personal data associated with an email address from the entries table.
 *
 * @since 2.6.0
 *
 * @param  string $email_address The author email address.
 * @param  int    $page          Erase page.
 * @return array
 */
function gwolle_gb_personal_data_eraser( $email_address, $page = 1 ) {

	if ( empty( $email_address ) ) {
		return array(
			'items_removed'  => false,
			'items_retained' => false,
			'messages'       => array(),
			'done'           => true,
		);
	}

	// Limit us to 100 entries at a time to avoid timing out.
	$number         = 100;
	$page           = (int) $page;
	$items_removed  = false;
	$items_retained = false;
	$messages       = array();

	$entries = gwolle_gb_get_entries(
		array(
			'offset'      => 0,
			'num_entries' => $number,
			'email'       => $email_address,
		)
	);

	if ( ! is_array($entries) || empty($entries) ) {
		$messages[] = esc_html__( 'No guestbook entries have been found for this email address.', 'gwolle-gb' );
		return array(
			'items_removed'  => false,
			'items_retained' => false,
			'messages'       => $messages,
			'done'           => true,
		);
	}

	foreach ( $entries as $entry ) {
		$entry = gwolle_gb_privacy_anonymize_entry( $entry );
		$result = $entry->save();
		if ( $result ) {
			$items_removed = true;
			do_action( 'gwolle_gb_save_entry_admin', $entry );
			gwolle_gb_add_log_entry( $entry->get_id(), 'entry-anonymized' );
		} else {
			$items_retained = true;
			/* translators: %d: Entry ID */
			$messages[] = sprintf( esc_html__( 'Guestbook entry %d contains personal data but could not be anonymized.', 'gwolle-gb' ), $entry->get_id() );
		}
	}

	$done = count( $entries ) < $number;

	return array(
		'items_removed'  => $items_removed,
		'items_retained' => $items_retained,
		'messages'       => $messages,
		'done'           => $done,
	);
}


/*
 * Anonymize personal data associated with an entry.
 *
 * @since 2.6.0
 *
 * @param  object $entry instance of gwolle_gb_entry class.
 * @return object $entry anonymized instance of gwolle_gb_entry class.
 */
function gwolle_gb_privacy_anonymize_entry( $entry ) {

	$entry->set_author_name( /* translators: Username */ esc_html__( 'Anonymous', 'gwolle-gb' ) );
	$entry->set_author_id( 0 );
	$entry->set_author_email( '' );
	$entry->set_author_origin( '' );
	$entry->set_author_website( '' );
	$entry->set_author_ip( '' );
	$entry->set_author_host( '' );

	return $entry;

}
