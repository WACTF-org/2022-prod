<?php


/*
 * Check frontend form for blocklisted words.
 * Borrowed from wp-includes/comment.php check_comment().
 * Uses blocklisted words from WordPress Core and Guestbook settings.
 *
 * @since 4.0.5
 *
 * @param string $entry the guestbook entry the metadata belongs to.
 *
 * @return none.
 */
function gwolle_gb_blocklist( $entry ) {

	$wp_mod_keys = trim( get_option( 'moderation_keys' ) );
	$gb_mod_keys = trim( get_option( 'gwolle_gb_addon-moderation_keys' ) );
	$send_to_moderation = false;

	// If moderation 'keys' (keywords) are set, process them.
	$wp_words = array();
	$gb_words = array();
	if ( ! empty( $wp_mod_keys ) ) {
		$wp_words = explode( "\n", $wp_mod_keys );
	}
	if ( ! empty( $gb_mod_keys ) ) {
		$gb_words = explode( "\n", $gb_mod_keys );
	}
	$words = array_merge( $wp_words, $gb_words );

	if ( ! empty( $words ) ) {
		foreach ( (array) $words as $word ) {
			$word = trim( $word );

			// Skip empty lines.
			if ( empty( $word ) ) {
				continue;
			}

			/*
			 * Do some escaping magic so that '#' (number of) characters in the spam
			 * words don't break things:
			 */
			$word = preg_quote( $word, '#' );

			/*
			 * Check the comment fields for moderation keywords. If any are found,
			 * fail the check for the given field by returning false.
			 */
			$pattern = "#$word#i";
			$author = $entry->get_author_name();
			if ( preg_match( $pattern, $author ) ) {
				$send_to_moderation = true;
			}
			$email = $entry->get_author_email();
			if ( preg_match( $pattern, $email ) ) {
				$send_to_moderation = true;
			}
			$origin = $entry->get_author_origin();
			if ( preg_match( $pattern, $origin ) ) {
				$send_to_moderation = true;
			}
			$website = $entry->get_author_website();
			if ( preg_match( $pattern, $website ) ) {
				$send_to_moderation = true;
			}
			$content = $entry->get_content();
			if ( preg_match( $pattern, $content ) ) {
				$send_to_moderation = true;
			}
			$user_ip = gwolle_gb_get_user_ip();
			if ( preg_match( $pattern, $user_ip ) ) {
				$send_to_moderation = true;
			}
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if ( preg_match( $pattern, $user_agent ) ) {
				$send_to_moderation = true;
			}
		}
	}

	if ( $send_to_moderation === true ) {
		$entry->set_ischecked( false );
	}

	return $entry;

}
add_filter( 'gwolle_gb_new_entry_frontend', 'gwolle_gb_blocklist' );


/*
 * Disable frontend form for blocklisted words in Add-On.
 *
 * @since 4.0.5
 *
 * @return none.
 */
function gwolle_gb_disable_addon_blocklist() {

	remove_filter( 'gwolle_gb_new_entry_frontend', 'gwolle_gb_addon_blacklist' );

}
add_action('init', 'gwolle_gb_disable_addon_blocklist');
