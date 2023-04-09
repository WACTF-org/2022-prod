<?php
/*
 * Settings page for the guestbook
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Admin tab of the Settings page.
 */
function gwolle_gb_page_settingstab_admin() {

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	} ?>

	<input type="hidden" id="gwolle_gb_tab" name="gwolle_gb_tab" value="gwolle_gb_admin" />
	<?php
	settings_fields( 'gwolle_gb_options' );
	do_settings_sections( 'gwolle_gb_options' );

	/* Nonce */
	$nonce = wp_create_nonce( 'gwolle_gb_page_settings_admintab' );
	echo '<input type="hidden" id="gwolle_gb_page_settings_admintab" name="gwolle_gb_page_settings_admintab" value="' . esc_attr( $nonce ) . '" />';
	?>
	<table class="form-table">
		<tbody>

		<tr>
			<th scope="row"><label for="entries_per_page"><?php esc_html_e('Entries per page in the admin', 'gwolle-gb'); ?></label></th>
			<td>
				<select name="entries_per_page" id="entries_per_page">
					<?php
					$entries_per_page = (int) get_option( 'gwolle_gb-entries_per_page', 20 );
					$presets = array( 5, 10, 15, 20, 25, 30, 40, 50, 60, 70, 80, 90, 100, 120, 150, 200, 250 );
					$presets_count = count($presets);
					for ($i = 0; $i < $presets_count; $i++) {
						echo '<option value="' . (int) $presets[$i] . '"';
						if ($presets[$i] === $entries_per_page) {
							echo ' selected="selected"';
						}
						echo '>' . $presets[$i] . ' ' . esc_html__('Entries', 'gwolle-gb') . '</option>';
					}
					?>
				</select>
				<br />
				<span class="setting-description"><?php esc_html_e('Number of entries shown in the admin.', 'gwolle-gb'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="showEntryIcons"><?php esc_html_e('Entry icons', 'gwolle-gb'); ?></label></th>
			<td>
				<input type="checkbox" <?php
					if ( get_option( 'gwolle_gb-showEntryIcons', 'true' ) === 'true' ) {
						echo 'checked="checked"';
					}
					?> name="showEntryIcons" id="showEntryIcons" /><label for="showEntryIcons"><?php esc_html_e('Show entry icons', 'gwolle-gb'); ?></label>
				<br />
				<span class="setting-description"><?php esc_html_e('These icons are shown in every entry row of the admin list, so that you know its status (checked, spam and trash).', 'gwolle-gb'); ?></span>
			</td>
		</tr>

		<tr>
			<th colspan="2">
				<p class="submit">
					<input type="submit" name="gwolle_gb_settings_admin" id="gwolle_gb_settings_admin" class="button-primary" value="<?php esc_attr_e('Save settings', 'gwolle-gb'); ?>" />
				</p>
			</th>
		</tr>

		</tbody>
	</table>

	<?php
}
