<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Use a custom field name for the form fields that are different for each website.
 *
 * @param string field name of the requested field.
 * @return string hashed fieldname or fieldname, prepended with gwolle_gb.
 *
 * @since 2.4.1
 */
function gwolle_gb_get_field_name( $field ) {

	if ( ! in_array( $field, array( 'name', 'city', 'email', 'website', 'honeypot', 'honeypot2', 'nonce', 'custom', 'timeout', 'timeout2' ) ) ) {
		return 'gwolle_gb_' . $field;
	}

	$blog_url = get_option( 'siteurl' );
	// $blog_url = get_bloginfo('wpurl'); // Will be different depending on scheme (http/https).

	$key = 'gwolle_gb_' . $field . '_field_name_' . $blog_url;
	$field_name = wp_hash( $key, 'auth' );
	$field_name = 'gwolle_gb_' . $field_name;

	return $field_name;

}
