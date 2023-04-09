<?php
/*
 * Settings page for the guestbook
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Debug tab of the Settings page.
 *
 */
function gwolle_gb_page_settingstab_debug() {

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	} ?>

	<input type="hidden" id="gwolle_gb_tab" name="gwolle_gb_tab" value="gwolle_gb_debug" />
	<?php
	settings_fields( 'gwolle_gb_options' );
	do_settings_sections( 'gwolle_gb_options' );

	/* Nonce */
	$nonce = wp_create_nonce( 'gwolle_gb_page_settings_debugtab' );
	echo '<input type="hidden" id="gwolle_gb_page_settings_debugtab" name="gwolle_gb_page_settings_debugtab" value="' . esc_attr( $nonce ) . '" />';
	?>
	<table class="form-table">
		<tbody>

		<tr>
			<td scope="row" colspan="2">
				<p>
					<?php esc_html_e('Please provide this information when posting a support message on the support forum.', 'gwolle-gb'); ?>
				</p>
			</td>
		</tr>

		<?php
		/* Check Nonce */
		$verified = false;
		if ( isset($_POST['gwolle_gb_page_settings_debugtab']) ) {
			$verified = wp_verify_nonce( $_POST['gwolle_gb_page_settings_debugtab'], 'gwolle_gb_page_settings_debugtab' );
		}
		if ( $verified && isset( $_POST['gwolle_gb_debug'] ) ) {
			// Save test entries
			$entry_id = gwolle_gb_test_add_entry( false );
			$entry_id_emoji = gwolle_gb_test_add_entry( true );
			?>

			<tr>
				<th><?php esc_html_e('Standard test:', 'gwolle-gb'); ?></th>
				<td><?php
					if ( $entry_id === 0 ) {
						echo '👎 ';
						esc_html_e('Failed.', 'gwolle-gb');
					} else {
						echo '👍 ';
						esc_html_e('Succeeded.', 'gwolle-gb');
					} ?>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e('Emoji test:', 'gwolle-gb'); ?></th>
				<td><?php
					if ( $entry_id_emoji === 0 ) {
						echo '👎 ';
						esc_html_e('Failed.', 'gwolle-gb');
					} else {
						echo '👍 ';
						esc_html_e('Succeeded.', 'gwolle-gb');
					} ?>
				</td>
			</tr>
			<?php
		}
		?>

		<tr>
			<th scope="row"><label for="blogdescription"><?php esc_html_e('Test', 'gwolle-gb'); ?></label></th>
			<td>
				<p>
				<?php esc_html_e('This test will attempt to save two test entries, one with standard text and one with Emoji.', 'gwolle-gb'); ?>
				</p>
				<p>
					<input type="submit" name="gwolle_gb_debug" id="gwolle_gb_debug" class="button button-primary" value="<?php esc_attr_e('Run test', 'gwolle-gb'); ?>" />
				</p>
			</td>
		</tr>

		<?php gwolle_gb_debug_info(); ?>

		</tbody>
	</table>

	<?php
}
