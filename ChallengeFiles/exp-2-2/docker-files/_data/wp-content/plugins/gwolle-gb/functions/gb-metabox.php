<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Edit Link for Moderators for in the Metabox.
 *
 * @param string $gb_metabox html for the metabox of this entry
 * @param object $entry instance of class gb_entry
 * @return string $gb_metabox new html for the metabox of this entry
 *
 * @since 2.3.0
 */
function gwolle_gb_entry_metabox_lines_edit_link( $gb_metabox, $entry ) {

	if ( current_user_can('moderate_comments') ) {
		$gb_metabox .= '
					<div class="gb-metabox-line">
						<a class="gwolle_gb_edit_link gwolle-gb-edit-link" href="' . admin_url('admin.php?page=' . GWOLLE_GB_FOLDER . '/editor.php&amp;entry_id=' . $entry->get_id() ) . '" title="' . esc_attr__('Edit entry', 'gwolle-gb') . '">' . esc_html__('Edit in Editor', 'gwolle-gb') . '</a>
					</div>';
	}
	return $gb_metabox;

}
add_filter( 'gwolle_gb_entry_metabox_lines', 'gwolle_gb_entry_metabox_lines_edit_link', 90, 2 );


/*
 * Ajax Icon for in the Metabox.
 * Only shown when there is already content.
 *
 * @param string $gb_metabox html for the metabox of this entry
 * @param object $entry instance of class gb_entry
 * @return string $gb_metabox new html for the metabox of this entry
 *
 * @since 2.3.0
 */
function gwolle_gb_entry_metabox_lines_ajax_icon( $gb_metabox, $entry ) {

	if ( current_user_can('moderate_comments') ) {
		if ( $gb_metabox ) {

			$gb_metabox .= '
					<div class="gb-metabox-line gb-metabox-line-ajax">
						' . esc_html__('Please wait...', 'gwolle-gb') . '
					</div>';
		}
	}
	return $gb_metabox;

}
add_filter( 'gwolle_gb_entry_metabox_lines', 'gwolle_gb_entry_metabox_lines_ajax_icon', 99, 2 );
