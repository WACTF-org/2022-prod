<?php
/*
 * Settings page for the guestbook
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Form tab of the Settings page.
 */
function gwolle_gb_page_settingstab_form() {

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	} ?>

	<input type="hidden" id="gwolle_gb_tab" name="gwolle_gb_tab" value="gwolle_gb_forms" />
	<?php
	settings_fields( 'gwolle_gb_options' );
	do_settings_sections( 'gwolle_gb_options' );

	/* Nonce */
	$nonce = wp_create_nonce( 'gwolle_gb_page_settings_formtab' );
	echo '<input type="hidden" id="gwolle_gb_page_settings_formtab" name="gwolle_gb_page_settings_formtab" value="' . esc_attr( $nonce ) . '" />';
	?>
	<table class="form-table">
		<tbody>

		<tr>
			<th scope="row"><label for="require_login"><?php esc_html_e('Require Login', 'gwolle-gb'); ?></label></th>
			<td>
				<input type="checkbox" id="require_login" name="require_login" <?php
					if ( get_option( 'gwolle_gb-require_login', 'false' ) === 'true' ) {
						echo 'checked="checked"';
					}
					?> />
				<label for="require_login"><?php esc_html_e('Require user to be logged in.', 'gwolle-gb'); ?></label>
				<br />
				<span class="setting-description"><?php esc_html_e('Only allow logged-in users to add a guestbook entry.', 'gwolle-gb'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="labels_float"><?php esc_html_e('Labels float', 'gwolle-gb'); ?></label></th>
			<td>
				<input type="checkbox" id="labels_float" name="labels_float" <?php
					if ( get_option( 'gwolle_gb-labels_float', 'true' ) === 'true' ) {
						echo 'checked="checked"';
					}
					?> />
				<label for="labels_float"><?php esc_html_e('Labels in the form float to the left.', 'gwolle-gb'); ?></label>
				<br />
				<span class="setting-description"><?php esc_html_e('Labels in the form float to the left. Otherwise the labels will be above the input-fields.', 'gwolle-gb'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="gb_header"><?php esc_html_e('Header Text', 'gwolle-gb'); ?></label></th>
			<td><?php
				$header = gwolle_gb_sanitize_output( get_option('gwolle_gb-header', false) );
				if ( ! $header ) {
					$header = esc_html__('Write a new entry for the Guestbook', 'gwolle-gb');
				} ?>
				<input name="gb_header" id="gb_header" class="regular-text" type="text" value="<?php echo esc_attr( $header ); ?>" />
				<br />
				<span class="setting-description">
					<?php esc_html_e('You can set the header that is shown on top of the form.', 'gwolle-gb'); ?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="notice"><?php esc_html_e('Notice Text', 'gwolle-gb'); ?></label></th>
			<td>
				<?php
				$notice = gwolle_gb_sanitize_output( get_option('gwolle_gb-notice', false), 'setting_textarea' );
				if ( ! $notice) { // No text set by the user. Use the default text.
					$notice = esc_html__("
Fields marked with * are required.
Your E-mail address won't be published.
It's possible that your entry will only be visible in the guestbook after we reviewed it.
We reserve the right to edit, delete, or not publish entries.
", 'gwolle-gb');
				} ?>
				<textarea name="notice" id="notice" style="width:400px;height:180px;" class="regular-text"><?php echo esc_textarea( $notice ); ?></textarea>
				<br />
				<span class="setting-description">
					<?php esc_html_e('You can set the content of the notice that gets shown below the form.', 'gwolle-gb');
					echo '<br />';
					esc_html_e('You can use the tag %ip% to show the ip address.', 'gwolle-gb');
					echo '<br /><br />';
					esc_html_e('If you use a Multi-Lingual plugin, keep the 2 fields for header and notice empty when saving. That way the default text will be shown from a translated PO file.', 'gwolle-gb'); ?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="form_ajax"><?php esc_html_e('Use AJAX', 'gwolle-gb'); ?></label></th>
			<td>
				<input type="checkbox" id="form_ajax" name="form_ajax" <?php
					if ( get_option( 'gwolle_gb-form_ajax', 'true' ) === 'true' ) {
						echo 'checked="checked"';
					}
					?> />
				<label for="form_ajax"><?php esc_html_e('Use AJAX to submit the form.', 'gwolle-gb'); ?></label>
				<br />
				<span class="setting-description"><?php esc_html_e('Submit the form while staying on the same page and place, without a new page load.', 'gwolle-gb'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="store_ip"><?php esc_html_e('Store IP Address', 'gwolle-gb'); ?></label></th>
			<td>
				<input type="checkbox" id="store_ip" name="store_ip" <?php
					if ( get_option( 'gwolle_gb-store_ip', 'true' ) === 'true' ) {
						echo 'checked="checked"';
					}
					?> />
				<label for="store_ip"><?php esc_html_e('Store IP Address and hostname for each entry.', 'gwolle-gb'); ?></label>
				<br />
				<span class="setting-description"><?php /* translators: The GDPR law often has a country specific name */
				esc_html_e('In the EU there is the GDPR law about privacy and storing personal information.', 'gwolle-gb'); echo '<br />';
				esc_html_e('Disabling this option will still have the IP Address used for spamfiltering in Stop Forum Spam.', 'gwolle-gb'); echo '<br />';
				esc_html_e('Disabling this option will probably make the Akismet spamfilter less effective.', 'gwolle-gb'); ?></span><br />
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="gb_remove_ip"><?php esc_html_e('Remove IP Address', 'gwolle-gb'); ?></label></th>
			<td>
				<input type="checkbox" id="gb_remove_ip" name="gb_remove_ip" />
				<label for="gb_remove_ip"><?php esc_html_e('Permanently remove IP Address and hostname for all existing entries.', 'gwolle-gb'); ?></label>
			</td>
		</tr>

		</tbody>
	</table>

	<table class="form-table">
		<tbody>

		<?php $form_setting = gwolle_gb_get_setting( 'form' ); ?>

		<tr>
			<td colspan="3"><h3><?php esc_html_e('Configure the form that is shown to visitors.', 'gwolle-gb'); ?></h3></td>
		</tr>

		<tr>
			<th scope="row"><label for="form_name_enabled"><?php esc_html_e('Name', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="form_name_enabled" name="form_name_enabled"<?php
					if ( isset($form_setting['form_name_enabled']) && $form_setting['form_name_enabled'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="form_name_enabled"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label>
			</td>
			<td>
				<input type="checkbox" id="form_name_mandatory" name="form_name_mandatory"<?php
					if ( isset($form_setting['form_name_mandatory']) && $form_setting['form_name_mandatory'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="form_name_mandatory"><?php esc_html_e('Mandatory', 'gwolle-gb'); ?></label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="form_city_enabled"><?php esc_html_e('City', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="form_city_enabled" name="form_city_enabled"<?php
					if ( isset($form_setting['form_city_enabled']) && $form_setting['form_city_enabled'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="form_city_enabled"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label>
			</td>
			<td>
				<input type="checkbox" id="form_city_mandatory" name="form_city_mandatory"<?php
					if ( isset($form_setting['form_city_mandatory']) && $form_setting['form_city_mandatory'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="form_city_mandatory"><?php esc_html_e('Mandatory', 'gwolle-gb'); ?></label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="form_email_enabled"><?php esc_html_e('Email', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="form_email_enabled" name="form_email_enabled"<?php
					if ( isset($form_setting['form_email_enabled']) && $form_setting['form_email_enabled'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="form_email_enabled"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label>
			</td>
			<td>
				<input type="checkbox" id="form_email_mandatory" name="form_email_mandatory"<?php
					if ( isset($form_setting['form_email_mandatory']) && $form_setting['form_email_mandatory'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="form_email_mandatory"><?php esc_html_e('Mandatory', 'gwolle-gb'); ?></label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="form_homepage_enabled"><?php esc_html_e('Website', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="form_homepage_enabled" name="form_homepage_enabled"<?php
					if ( isset($form_setting['form_homepage_enabled']) && $form_setting['form_homepage_enabled'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="form_homepage_enabled"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label>
			</td>
			<td>
				<input type="checkbox" id="form_homepage_mandatory" name="form_homepage_mandatory"<?php
					if ( isset($form_setting['form_homepage_mandatory']) && $form_setting['form_homepage_mandatory'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="form_homepage_mandatory"><?php esc_html_e('Mandatory', 'gwolle-gb'); ?></label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="form_message_enabled"><?php esc_html_e('Message', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="form_message_enabled" name="form_message_enabled"<?php
					if ( isset($form_setting['form_message_enabled']) && $form_setting['form_message_enabled'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="form_message_enabled"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label>
			</td>
			<td>
				<input type="checkbox" id="form_message_mandatory" name="form_message_mandatory"<?php
					if ( isset($form_setting['form_message_mandatory']) && $form_setting['form_message_mandatory'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="form_message_mandatory"><?php esc_html_e('Mandatory', 'gwolle-gb'); ?></label>

				<label for="form_message_maxlength">
				<select name="form_message_maxlength" id="form_message_maxlength">
					<?php
					$form_message_maxlength = 0;
					if ( isset($form_setting['form_message_maxlength']) ) {
						$form_message_maxlength = (int) $form_setting['form_message_maxlength'];
					}
					if ( $form_message_maxlength === 0 ) {
						echo '<option value="0" selected="selected">' . esc_html__('No Length Limit', 'gwolle-gb') . '</option>';
					} else {
						echo '<option value="0">' . esc_html__('No Limit', 'gwolle-gb') . '</option>';
					}
					$presets = array( 100, 150, 200, 250, 300, 400, 500 );
					$preset_count = count($presets);
					for ($i = 0; $i < $preset_count; $i++) {
						echo '<option value="' . (int) $presets[$i] . '"';
						if ( $presets[$i] === $form_message_maxlength ) {
							echo ' selected="selected"';
						}
						echo '>' . $presets[$i] . ' ' . esc_html__('Characters', 'gwolle-gb') . '</option>';
					}
					?>
				</select>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="form_bbcode_enabled"><?php esc_html_e('Visual formatting and Emoji', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="form_bbcode_enabled" name="form_bbcode_enabled"<?php
					if ( isset($form_setting['form_bbcode_enabled']) && $form_setting['form_bbcode_enabled'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="form_bbcode_enabled"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label>
			</td>
			<td>
				<?php esc_html_e('Will add a button row to the message field.', 'gwolle-gb'); echo '<br />';
				esc_html_e('Adds bold and italic style, images, links and emoji.', 'gwolle-gb'); ?>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="form_privacy_enabled"><?php esc_html_e('Privacy checkbox', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="form_privacy_enabled" name="form_privacy_enabled"<?php
					if ( isset($form_setting['form_privacy_enabled']) && $form_setting['form_privacy_enabled'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="form_privacy_enabled"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label>
			</td>
			<td>
				<?php esc_html_e('When enabled it is mandatory.', 'gwolle-gb'); ?>
			</td>
		</tr>

		<tr>
			<th colspan="3">
				<p class="submit">
					<input type="submit" name="gwolle_gb_settings_form" id="gwolle_gb_settings_form" class="button-primary" value="<?php esc_attr_e('Save settings', 'gwolle-gb'); ?>" />
				</p>
			</th>
		</tr>

		</tbody>
	</table>

	<?php
}
