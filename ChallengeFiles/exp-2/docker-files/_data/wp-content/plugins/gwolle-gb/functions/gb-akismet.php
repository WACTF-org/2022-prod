<?php
/**
 * Akismet Function
 * Akismet API: https://akismet.com/development/api/
 * Copied and edited from Contact Form 7
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * @param object $entry  instance of gwolle_gb_entry with a guestbook entry to be checked
 * @param string $action the requested action
 *          - comment-check: check with Akismet service if entry is considered spam or not
 *          - submit-ham: submit as ham to Akismet service
 *          - submit-spam: submit as spam to Akismet service
 * @return bool
 *          - true if the entry is considered spam by akismet
 *          - false if no spam, or no akismet functionality is found
 */
function gwolle_gb_akismet( $entry, $action ) {

	$actions = array(
		'comment-check',
		'submit-ham',
		'submit-spam',
	);

	if ( ! in_array( $action, $actions ) ) {
		return false;
	}

	$active_plugins = get_option('active_plugins');
	if ( ! in_array('akismet/akismet.php', $active_plugins)) {
		return false;
	}

	$akismet_active = get_option( 'gwolle_gb-akismet-active', 'false' );
	if ( $akismet_active !== 'true' ) {
		return false;
	}

	if ( is_callable( array( 'Akismet', 'get_api_key' ) ) ) { // Akismet v3.0+
		$api_key = (bool) Akismet::get_api_key();
	} else if ( function_exists( 'akismet_get_key' ) ) {
		$api_key = (bool) akismet_get_key();
	}

	if ( ! $api_key ) {
		// No api key, no glory
		return false;
	}

	if ( ! is_object( $entry ) ) {
		// No object, no fuss
		return false;
	}

	$comment = array();

	$comment['comment_author'] = $entry->get_author_name();

	$comment['comment_author_email'] = $entry->get_author_email();

	$comment['comment_author_origin'] = $entry->get_author_origin();

	$comment['comment_author_url'] = $entry->get_author_website();

	$comment['comment_content'] = gwolle_gb_bbcode_strip( $entry->get_content() );

	$comment['blog'] = get_option( 'home' );
	$comment['blog_lang'] = get_locale();
	$comment['blog_charset'] = get_option( 'blog_charset' );
	$store_author_ip = get_option('gwolle_gb-store_ip', 'true');
	if ( $store_author_ip === 'true' ) {
		$comment['user_ip'] = preg_replace( '/[^0-9., ]/', '', gwolle_gb_get_user_ip() );
		if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
			$comment['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		}
	}
	if ( isset($_SERVER['HTTP_REFERER']) ) {
		$comment['referrer'] = $_SERVER['HTTP_REFERER'];
	}

	// https://blog.akismet.com/2012/06/19/pro-tip-tell-us-your-comment_type/
	$comment['comment_type'] = 'comment';

	$permalink = gwolle_gb_get_permalink( get_the_ID() );
	if ( $permalink ) {
		$comment['permalink'] = $permalink;
	}

	$ignore = array( 'HTTP_COOKIE', 'HTTP_COOKIE2', 'PHP_AUTH_PW', 'REMOTE_ADDR', 'HTTP_USER_AGENT', 'PATH' );
	foreach ( $_SERVER as $key => $value ) {
		if ( ! in_array( $key, (array) $ignore ) )
			$comment["$key"] = $value;
	}

	// Send the thing to the Akismet service
	return gwolle_gb_akismet_entry_check( $comment, $action );

}


/*
 * Check the $comment against Akismet service
 *
 * @param array $comment array with data fields to be checked on
 * @param string $action the requested action
 *          - comment-check: check with Akismet service if entry is considered spam or not
 *          - submit-ham: submit as ham to Akismet service
 *          - submit-spam: submit as spam to Akismet service
 * @return bool
 *          - true if the entry is considered spam by akismet
 *          - false if no spam, or no akismet functionality is found
 */
function gwolle_gb_akismet_entry_check( $comment, $action ) {
	global $akismet_api_host, $akismet_api_port;

	$query_string = '';

	foreach ( $comment as $key => $data ) {
		if ( is_array($data) ) {
			continue;
		}
		$query_string .= $key . '=' . rawurlencode( wp_unslash( (string) $data ) ) . '&';
	}

	if ( is_callable( array( 'Akismet', 'http_post' ) ) ) {
		// Akismet v3.0+
		$response = Akismet::http_post( $query_string, $action );
	} else {
		$response = akismet_http_post( $query_string, $akismet_api_host, '/1.1/' . $action, $akismet_api_port );
	}

	//if ( WP_DEBUG ) { echo "Akismet response: "; var_dump($response); }

	if ( $action === 'comment-check' && isset( $response[1] ) && 'true' === $response[1] ) {
		return true;
	} else if ( $action === 'submit-ham' && isset( $response[1] ) ) {
		return true;
	} else if ( $action === 'submit-spam' && isset( $response[1] ) ) {
		return true;
	} else {
		return false;
	}

}
