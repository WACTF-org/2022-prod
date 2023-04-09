<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Handles AJAX request from Gwolle-GB AJAX Submit.
 *
 * @return string json encoded data, which is handled with by frontend/js/script.js.
 */
function gwolle_gb_form_ajax_callback() {

	$saved = gwolle_gb_frontend_posthandling();

	$data = array();
	$data['saved']                  = $saved;
	$data['gwolle_gb_messages']     = gwolle_gb_get_messages();
	$data['gwolle_gb_errors']       = gwolle_gb_get_errors();
	$data['gwolle_gb_error_fields'] = gwolle_gb_get_error_fields();

	if ( $saved ) {
		$entry = new gwolle_gb_entry();
		$result = $entry->load( $saved );
		if ( $result ) {
			if ( $entry->get_isspam() === 1 || $entry->get_istrash() === 1 || $entry->get_ischecked() === 0 ) {

				// Invisible.

			} else {

				$data['entry'] = gwolle_gb_single_view( $entry, true, 0 );

			}
		}
	}

	echo json_encode( $data );
	die(); // This is required to return a proper result.

}
add_action( 'wp_ajax_gwolle_gb_form_ajax', 'gwolle_gb_form_ajax_callback' );
add_action( 'wp_ajax_nopriv_gwolle_gb_form_ajax', 'gwolle_gb_form_ajax_callback' );
