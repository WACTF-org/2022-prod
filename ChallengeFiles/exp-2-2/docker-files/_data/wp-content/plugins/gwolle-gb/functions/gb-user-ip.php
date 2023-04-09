<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Validate user IP, include known proxy headers if needed.
 *
 * @since 4.0.3
 */
function gwolle_gb_get_user_ip() {

	static $user_ip;

	if ( isset($user_ip) ) {
		return $user_ip;
	}

	$include_proxy = apply_filters( 'gwolle_gb_include_proxy_ips', false );
	if ( true === $include_proxy ) {
		$proxy_headers = array(
			'HTTP_VIA',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_FORWARDED',
			'HTTP_CLIENT_IP',
			'HTTP_FORWARDED_FOR_IP',
			'VIA',
			'X_FORWARDED_FOR',
			'FORWARDED_FOR',
			'X_FORWARDED',
			'FORWARDED',
			'CLIENT_IP',
			'FORWARDED_FOR_IP',
			'HTTP_PROXY_CONNECTION',
			'REMOTE_ADDR',
		);
		foreach ( $proxy_headers as $header ) {
			if ( isset( $_SERVER["$header"] ) ) {
				$user_ip = sanitize_text_field( $_SERVER["$header"] );
				break;
			}
		}
		return $user_ip;
	}

	$user_ip = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
	return $user_ip;

}
