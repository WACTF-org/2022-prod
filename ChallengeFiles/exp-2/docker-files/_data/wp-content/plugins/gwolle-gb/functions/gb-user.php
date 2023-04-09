<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Is User also Author of the entry.
 *
 * @param object $entry instance of gwolle_gb_entry
 * @return bool true if author, false if not author
 *
 * @since 2.3.0
 */
function gwolle_gb_is_author( $entry ) {
	if ( is_object( $entry ) && is_a( $entry, 'gwolle_gb_entry' ) ) {
		$user_id = get_current_user_id(); // returns 0 if no current user
		if ( $user_id > 0 ) {
			$author_id = $entry->get_author_id();
			if ( $author_id === $user_id ) {
				return true;
			}
		}
	}
	return false;
}


/*
 * Is User allowed to manage comments.
 *
 * $param int $user_id ID of the user in question.
 * @return
 * - string user_nicename or user_login if allowed
 * - bool false if not allowed
 */
function gwolle_gb_is_moderator( $user_id ) {

	if ( $user_id > 0 ) {
		if ( user_can( $user_id, 'moderate_comments' ) ) {
			// Only moderators
			$userdata = get_userdata( $user_id );
			if ( is_object($userdata) ) {
				if ( isset( $userdata->display_name ) ) {
					return $userdata->display_name;
				} else {
					return $userdata->user_login;
				}
			}
		}
	}
	return false;
}


/*
 * Get all the users with capability 'moderate_comments'.
 *
 * @return array User objects.
 */
function gwolle_gb_get_moderators() {

	$role__in = array( 'Administrator', 'Editor', 'Author' );
	$role__in = apply_filters( 'gwolle_gb_get_moderators_role__in', $role__in );

	// role__in will only work since WP 4.4.
	$users_query = new WP_User_Query( array(
		'role__in' => $role__in,
		'fields'   => 'all',
		'orderby'  => 'display_name',
		) );
	$users = $users_query->get_results();

	$moderators = array();

	if ( is_array($users) && ! empty($users) ) {
		foreach ( $users as $user_info ) {

			if ($user_info === false) {
				// Invalid $user_id
				continue;
			}

			// No capability
			if ( ! user_can( $user_info, 'moderate_comments' ) ) {
				continue;
			}

			$moderators[] = $user_info;
		}
	}

	return $moderators;
}


/*
 * Delete author_id (and maybe checkedby) from stored entries after deletion of user.
 * Will trim down db requests, because non-existent users do not get cached.
 *
 * @param int $user_id ID of the deleted user.
 */
function gwolle_gb_deleted_user( $user_id ) {
	$entries = gwolle_gb_get_entries(array(
		'author_id'   => $user_id,
		'num_entries' => -1,
	));
	if ( is_array( $entries ) && ! empty( $entries ) ) {
		foreach ( $entries as $entry ) {
			// method will take care of it...
			$save = $entry->save();
		}
	}
}
add_action( 'deleted_user', 'gwolle_gb_deleted_user' );


/*
 * Get Author name in the right format as html
 *
 * @param object $entry instance of gb_entry class.
 * @return string $author_name_html html with formatted username
 */
function gwolle_gb_get_author_name_html( $entry ) {

	$author_name = gwolle_gb_sanitize_output( trim( $entry->get_author_name() ) );
	$author_name = apply_filters( 'gwolle_gb_entry_the_author_name', $author_name, $entry );

	// Registered user gets italic font-style.
	$author_id = $entry->get_author_id();
	$is_moderator = gwolle_gb_is_moderator( $author_id );
	if ( $is_moderator ) {
		$author_name_html = '<i class="gb-moderator">' . esc_attr( $author_name ) . '</i>';
	} else {
		$author_name_html = $author_name;
	}

	$author_link_to_buddypress = (bool) apply_filters( 'gwolle_gb_author_link_to_buddypress', true );
	if ( function_exists('bp_core_get_user_domain') && $author_link_to_buddypress ) {
		// Link to Buddypress profile.
		$author_website = trim( bp_core_get_user_domain( $author_id ) );
		if ($author_website) {
			$author_link_rel = apply_filters( 'gwolle_gb_author_link_rel', 'nofollow noopener noreferrer' );
			$author_name_html = '<a href="' . esc_attr( $author_website ) . '" target="_blank" rel="' . esc_attr( $author_link_rel ) . '"
							title="' . /* translators: BuddyPress profile */ esc_attr__( 'Visit the profile of', 'gwolle-gb' ) . ' ' . esc_attr( $author_name ) . ': ' . esc_attr( $author_website ) . '">' . $author_name_html . '</a>';
		}
	} else if ( get_option('gwolle_gb-linkAuthorWebsite', 'true') === 'true' ) {
		// Link to author website if set in options.
		$author_website = trim( $entry->get_author_website() );
		if ($author_website) {
			$pattern = '/^http/';
			if ( ! preg_match($pattern, $author_website, $matches) ) {
				$author_website = 'http://' . $author_website;
			}
			$author_link_rel = apply_filters( 'gwolle_gb_author_link_rel', 'nofollow noopener noreferrer' );
			$author_name_html = '<a href="' . esc_attr( $author_website ) . '" target="_blank" rel="' . esc_attr( $author_link_rel ) . '"
							title="' . esc_attr__( 'Visit the website of', 'gwolle-gb' ) . ' ' . esc_attr( $author_name ) . ': ' . esc_attr( $author_website ) . '">' . $author_name_html . '</a>';
		}
	}

	$author_name_html = apply_filters( 'gwolle_gb_author_name_html', $author_name_html, $entry );

	return $author_name_html;

}
