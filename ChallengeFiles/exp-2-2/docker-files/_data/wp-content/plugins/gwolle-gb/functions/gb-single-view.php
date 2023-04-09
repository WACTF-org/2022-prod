<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Give a single view of the entry.
 * Uses the template 'gwolle_gb-entry.php', either in the themedir or the plugin.
 *
 * @param object $entry instance of the class gb_entry.
 * @param bool $first if it is the first entry in the list
 * @param int $counter the number in the list.
 * @return string with html formatted entry.
 *
 * @since 2.3.0
 */
function gwolle_gb_single_view( $entry, $first = false, $counter = 0 ) {

	// Try to load and require_once the template from the themes folders.
	if ( locate_template( array( 'gwolle_gb-entry.php' ), true, true ) === '') {

		$output = '<!-- Gwolle-GB Entry: Default Template Loaded -->
			';

		// No template found and loaded in the theme folders.
		// Load the template from the plugin folder.
		require_once GWOLLE_GB_DIR . '/frontend/gwolle_gb-entry.php';

	} else {

		$output = '<!-- Gwolle-GB Entry: Custom Template Loaded -->
			';

	}

	// Run the function from the template to get the entry.
	$entry_output = gwolle_gb_entry_template( $entry, $first, $counter );

	// Add a filter for each entry, so devs can add or remove parts.
	$output .= apply_filters( 'gwolle_gb_entry_read', $entry_output, $entry );

	return $output;

}
