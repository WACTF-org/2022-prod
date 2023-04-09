<?php
/**
 * Stop Forum Spam Function
 * https://www.stopforumspam.com
 * Copied and edited from seriously-simple-spam-blocker
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Check the $entry against Stop Forum Spam service
 *
 * @param object $entry instance of gb_entry class
 * @return bool true or false
 *
 * @since 2.3.0
 */
function gwolle_gb_stop_forum_spam( $entry ) {
	$args = array();
	$args['ip']         = gwolle_gb_get_user_ip();
	$args['email']      = rawurlencode(iconv( 'GBK', 'UTF-8', $entry->get_author_email() ));
	$args['username']   = rawurlencode(iconv( 'GBK', 'UTF-8', $entry->get_author_name() ));
	$args['f']          = 'json';
	$args['confidence'] = true;
	$args = array_filter( $args );

	$url   = 'https://api.stopforumspam.com/api?';
	$query = $url . http_build_query( $args );
	$key   = md5( $query );

	$transient = get_transient( 'gwolle_gb_sfs_' . $key );
	if ( false === $transient ) {
		$result = wp_remote_get( $query );
		if ( ! is_wp_error( $result ) ) {

			if ( strlen( $result['body'] ) < 10 || ! (int) $result['response']['code'] === 200 ) {
				return false;
			}

			if ( $data = json_decode( $result['body'] ) ) {
				// It is json. Continue.
				if ( (int) $data->success !== 1 ) {
					return false;
				}

				if ( isset( $data->ip ) || isset( $data->email ) || isset( $data->username ) ) {

					$blocked = false;

					if ( isset( $data->ip->confidence ) && $data->ip->confidence > 75 ) {
						$blocked = 'ip';
					}
					if ( isset( $data->username->confidence ) && $data->username->confidence > 80 ) {
						$blocked = 'username';
					}
					if ( isset( $data->email->confidence ) && $data->email->confidence > 75 ) {
						$blocked = 'email';
					}

					if ( $blocked ) {
						set_transient( 'gwolle_gb_sfs_' . $key, 'true', DAY_IN_SECONDS );
						return true;
					} else {
						set_transient( 'gwolle_gb_sfs_' . $key, 'false', DAY_IN_SECONDS );
						return false;
					}
				}
			}
		}
	} else {
		if ( 'true' === $transient ) {
			return true;
		} else {
			return false;
		}
	}
	return false;
}
