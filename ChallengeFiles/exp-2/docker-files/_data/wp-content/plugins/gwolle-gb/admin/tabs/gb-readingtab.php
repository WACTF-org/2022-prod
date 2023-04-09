<?php
/*
 * Settings page for the guestbook
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Reading tab of the Settings page.
 */
function gwolle_gb_page_settingstab_reading() {

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	} ?>

	<input type="hidden" id="gwolle_gb_tab" name="gwolle_gb_tab" value="gwolle_gb_reading" />
	<?php
	settings_fields( 'gwolle_gb_options' );
	do_settings_sections( 'gwolle_gb_options' );


	/* Nonce */
	$nonce = wp_create_nonce( 'gwolle_gb_page_settings_readingtab' );
	echo '<input type="hidden" id="gwolle_gb_page_settings_readingtab" name="gwolle_gb_page_settings_readingtab" value="' . esc_attr( $nonce ) . '" />';
	?>
	<table class="form-table">
		<tbody>

		<tr>
			<th scope="row"><label for="entriesPerPage"><?php esc_html_e('Entries per page on the frontend', 'gwolle-gb'); ?></label></th>
			<td>
				<select name="entriesPerPage" id="entriesPerPage">
					<?php $entries_per_page = (int) get_option( 'gwolle_gb-entriesPerPage', 20 );
					$presets = array( 3, 5, 10, 15, 20, 25, 30, 40, 50, 60, 70, 80, 90, 100, 120, 150, 200, 250 );
					$presets_count = count( $presets );
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
				<span class="setting-description"><?php esc_html_e('Number of entries shown on the frontend.', 'gwolle-gb'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="excerpt_length"><?php esc_html_e('Length of the entry content', 'gwolle-gb'); ?></label></th>
			<td>
				<select name="excerpt_length" id="excerpt_length">
					<?php
					$excerpt_length = (int) get_option( 'gwolle_gb-excerpt_length', 0 );
					$presets = array( 20, 40, 60, 80, 100, 120, 150, 200, 300 );
					echo '<option value="0"';
					if ( 0 === $excerpt_length ) {
						echo ' selected="selected"';
					}
					/* translators: Number of words to display */
					echo '>' . esc_html__('Unlimited Words', 'gwolle-gb') . '</option>
					';

					foreach ( $presets as $preset ) {
						echo '<option value="' . (int) $preset . '"';
						if ($preset === $excerpt_length) {
							echo ' selected="selected"';
						}
						/* translators: Number of words to display */
						echo '>' . $preset . ' ' . esc_html__('Words', 'gwolle-gb') . '</option>
						';
					} ?>
				</select>
				<br />
				<span class="setting-description">
					<?php esc_html_e('Maximum length of the entry content in words.', 'gwolle-gb'); ?><br />
					<?php esc_html_e('Please be aware that this will strip linebreaks as well.', 'gwolle-gb'); ?><br />
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="showLineBreaks"><?php esc_html_e('Line breaks', 'gwolle-gb'); ?></label></th>
			<td>
				<input type="checkbox" id="showLineBreaks" name="showLineBreaks"<?php
					if ( get_option( 'gwolle_gb-showLineBreaks', 'false' ) === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="showLineBreaks"><?php esc_html_e('Show line breaks.', 'gwolle-gb'); ?></label>
				<br />
				<span class="setting-description">
					<?php esc_html_e('Show line breaks as the entry authors entered them. (May result in very long entries. Is turned off by default.)', 'gwolle-gb'); ?><br />
					<?php esc_html_e('This can only be enabled if the Excerpt Length above is set to Unlimited Words.', 'gwolle-gb'); ?><br />
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="showSmilies"><?php esc_html_e('Smileys', 'gwolle-gb'); ?></label></th>
			<td>
				<input type="checkbox" id="showSmilies" name="showSmilies"<?php
					if ( get_option( 'gwolle_gb-showSmilies', 'true' ) === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="showSmilies"><?php esc_html_e('Display smileys as images.', 'gwolle-gb'); ?></label>
				<br />
				<?php /* translators: %s is a smiley image */ ?>
				<span class="setting-description"><?php echo sprintf( esc_html__("Replaces smileys in entries like :) with their image %s. Uses the WP smiley replacer, so check on that one if you'd like to add new/more smileys.", 'gwolle-gb'), convert_smilies(':)')); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="linkAuthorWebsite"><?php esc_html_e('Links', 'gwolle-gb'); ?></label></th>
			<td>
				<input type="checkbox" id="linkAuthorWebsite" name="linkAuthorWebsite"<?php
					if ( get_option( 'gwolle_gb-linkAuthorWebsite', 'true' ) === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="linkAuthorWebsite"><?php esc_html_e("Link authors' name to their website.", 'gwolle-gb'); ?></label>
				<br />
				<span class="setting-description"><?php esc_html_e('The author of an entry can set his/her website. If this setting is checked, his/her name will be a link to that website.', 'gwolle-gb'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="admin_style"><?php esc_html_e('Admin Entry Styling', 'gwolle-gb'); ?></label></th>
			<td>
				<input type="checkbox" id="admin_style" name="admin_style"<?php
					if ( get_option( 'gwolle_gb-admin_style', 'false' ) === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="admin_style"><?php esc_html_e('Admin entries get a special CSS styling.', 'gwolle-gb'); ?></label>
				<br />
				<span class="setting-description"><?php esc_html_e('Admin entries get a special CSS styling. It will get a lightgrey background.', 'gwolle-gb'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="navigation"><?php esc_html_e('Navigation', 'gwolle-gb'); ?></label></th>
			<td>
				<?php $navigation = (int) get_option( 'gwolle_gb-navigation', 0 ); ?>
				<label><input type="radio" name="navigation" value="0" <?php checked( 0, $navigation ); ?> />
					<?php esc_html_e('Pagination', 'gwolle-gb'); ?>
				</label><br />
				<label><input type="radio" name="navigation" value="1" <?php checked( 1, $navigation ); ?> />
					<?php esc_html_e('Infinite Scroll', 'gwolle-gb'); ?>
				</label><br />
				<span class="setting-description"><?php esc_html_e('Use standard navigation with links to all pages, or use infinite scroll where entries will be added to the bottom as you are reading.', 'gwolle-gb'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="paginate_all"><?php esc_html_e('Pagination', 'gwolle-gb'); ?></label></th>
			<td>
				<input type="checkbox" id="paginate_all" name="paginate_all"<?php
					if ( get_option( 'gwolle_gb-paginate_all', 'false' ) === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="paginate_all"><?php esc_html_e('Show a link in the pagination for All entries.', 'gwolle-gb'); ?></label>
				<br />
				<span class="setting-description"><?php esc_html_e('Clicking this link will list all the published entries in the guestbook.', 'gwolle-gb'); ?></span>
			</td>
		</tr>


		<?php $read_setting = gwolle_gb_get_setting( 'read' ); ?>

		<tr>
			<td colspan="2"><h3><?php esc_html_e('Configure the parts of the entries that are shown to visitors.', 'gwolle-gb'); ?></h3></td>
		</tr>

		<tr>
			<th scope="row"><label for="read_avatar"><?php esc_html_e('Avatar', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="read_avatar" name="read_avatar"<?php
					if ( isset($read_setting['read_avatar']) && $read_setting['read_avatar'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="read_avatar"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="read_name"><?php esc_html_e('Name', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="read_name" name="read_name"<?php
					if ( isset($read_setting['read_name']) && $read_setting['read_name'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="read_name"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="read_city"><?php esc_html_e('City', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="read_city" name="read_city"<?php
					if ( isset($read_setting['read_city']) && $read_setting['read_city'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="read_city"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="read_datetime"><?php esc_html_e('Date and Time', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="read_datetime" name="read_datetime"<?php
					if ( isset($read_setting['read_datetime']) && $read_setting['read_datetime'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="read_datetime"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label><br />
				<span class="setting-description"><?php esc_html_e('Setting this will show the date and the time of the entry.', 'gwolle-gb'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="read_date"><?php esc_html_e('Date', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="read_date" name="read_date"<?php
					if ( isset($read_setting['read_date']) && $read_setting['read_date'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="read_date"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label><br />
				<span class="setting-description"><?php esc_html_e('Setting this will show the date of the entry. If Date and Time above are enabled, that setting has preference.', 'gwolle-gb'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="read_content"><?php esc_html_e('Content', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="read_content" name="read_content"<?php
					if ( isset($read_setting['read_content']) && $read_setting['read_content'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="read_content"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="read_aavatar"><?php esc_html_e('Admin Avatar', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="read_aavatar" name="read_aavatar"<?php
					if ( isset($read_setting['read_aavatar']) && $read_setting['read_aavatar'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="read_aavatar"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="read_editlink"><?php esc_html_e('Edit link', 'gwolle-gb'); ?>:</label></th>
			<td>
				<input type="checkbox" id="read_editlink" name="read_editlink"<?php
					if ( isset($read_setting['read_editlink']) && $read_setting['read_editlink'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
				<label for="read_editlink"><?php esc_html_e('Enabled', 'gwolle-gb'); ?></label><br />
				<span class="setting-description"><?php esc_html_e('A link to the editor will be added to the metabox. Only visible for moderators.', 'gwolle-gb'); ?></span>
			</td>
		</tr>

		<tr>
			<th colspan="2">
				<p class="submit">
					<input type="submit" name="gwolle_gb_settings_reading" id="gwolle_gb_settings_reading" class="button-primary" value="<?php esc_attr_e('Save settings', 'gwolle-gb'); ?>" />
				</p>
			</th>
		</tr>

		</tbody>
	</table>

	<?php
}
