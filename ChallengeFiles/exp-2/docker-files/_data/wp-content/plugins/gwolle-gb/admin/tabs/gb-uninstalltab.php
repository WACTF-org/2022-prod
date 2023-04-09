<?php
/*
 * Settings tab for the guestbook.
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Uninstall tab of the Settings page.
 */
function gwolle_gb_page_settingstab_uninstall() {

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	}
	if ( is_multisite() ) {
		esc_html_e('You are on a multisite install of WordPress. Please take a look at the documentation how to remove all the data of this plugin on multisite.', 'gwolle-gb');
		return;
	} ?>

	<input type="hidden" id="gwolle_gb_tab" name="gwolle_gb_tab" value="gwolle_gb_uninstall" />
	<?php
	settings_fields( 'gwolle_gb_options' );
	do_settings_sections( 'gwolle_gb_options' );

	/* Nonce */
	$nonce = wp_create_nonce( 'gwolle_gb_page_settings_uninstalltab' );
	echo '<input type="hidden" id="gwolle_gb_page_settings_uninstalltab" name="gwolle_gb_page_settings_uninstalltab" value="' . esc_attr( $nonce ) . '" />';
	?>
	<table class="form-table">
		<tbody>

		<tr>
			<th scope="row" style="color:#FF0000;"><label><?php esc_html_e('Uninstall', 'gwolle-gb'); ?></label></th>
			<td>
				<?php esc_html_e('Uninstalling means that all database entries are removed (settings and entries).', 'gwolle-gb'); echo '<br />';
				_e('This can <strong>not</strong> be undone.', 'gwolle-gb'); echo '<br />';
				esc_html_e('It is a good idea to make a backup of your website before you touch this button.', 'gwolle-gb');
				?>
			</td>
		</tr>

		<tr>
			<th scope="row" style="color:#FF0000;"><label for="gwolle_gb_uninstall_confirmed"><?php esc_html_e('Confirm', 'gwolle-gb'); ?></label></th>
			<td>
				<input type="checkbox" name="gwolle_gb_uninstall_confirmed" id="gwolle_gb_uninstall_confirmed">
				<label for="gwolle_gb_uninstall_confirmed"><?php esc_html_e("Yes, I'm absolutely sure of this. Proceed!", 'gwolle-gb'); ?></label>
			</td>
		</tr>

		<tr>
			<th colspan="2">
				<p class="submit">
					<input type="submit" name="gwolle_gb_uninstall" id="gwolle_gb_uninstall" class="button" disabled value="<?php esc_attr_e('Uninstall &raquo;', 'gwolle-gb'); ?>" />
				</p>
			</th>
		</tr>

		</tbody>
	</table>

	<?php
}
