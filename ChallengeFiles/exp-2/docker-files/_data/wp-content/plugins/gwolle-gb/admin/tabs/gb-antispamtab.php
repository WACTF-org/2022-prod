<?php
/*
 * Settings page for the guestbook
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Anti-spam tab of the Settings page.
 */
function gwolle_gb_page_settingstab_antispam() {

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	} ?>

	<input type="hidden" id="gwolle_gb_tab" name="gwolle_gb_tab" value="gwolle_gb_antispam" />
	<?php
	settings_fields( 'gwolle_gb_options' );
	do_settings_sections( 'gwolle_gb_options' );

	/* Nonce */
	$nonce = wp_create_nonce( 'gwolle_gb_page_settings_antispamtab' );
	echo '<input type="hidden" id="gwolle_gb_page_settings_antispamtab" name="gwolle_gb_page_settings_antispamtab" value="' . esc_attr( $nonce ) . '" />';
	?>
	<table class="form-table">
		<tbody>

		<tr>
			<th scope="row"><label for="moderate-entries"><?php /* translators: Settings page, option for moderation */ esc_html_e('Moderate Guestbook', 'gwolle-gb'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'gwolle_gb-moderate-entries', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="moderate-entries" id="moderate-entries">
				<label for="moderate-entries">
					<?php esc_html_e('Moderate entries before publishing them.', 'gwolle-gb'); ?>
				</label><br />
				<span class="setting-description">
					<?php esc_html_e('New entries have to be unlocked by a moderator before they are visible to the public.', 'gwolle-gb'); ?>
					<br />
					<?php esc_html_e('It is recommended that you turn this on, because you are responsible for the content on your website.', 'gwolle-gb'); ?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="refuse-spam"><?php /* translators: Settings page, option for refusing spam */ esc_html_e('Refuse Spam', 'gwolle-gb'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'gwolle_gb-refuse-spam', 'false') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="refuse-spam" id="refuse-spam">
				<label for="refuse-spam">
					<?php esc_html_e('Refuse spam when recognized.', 'gwolle-gb'); ?>
				</label><br />
				<span class="setting-description">
					<?php esc_html_e('Entries that are marked as spam will be placed in your spam folder by default.', 'gwolle-gb'); ?>
					<br />
					<?php esc_html_e('This option will refuse to accept entries marked by Honeypot, Nonce, Link Checker, Form Timeout, Akismet and Stop Forum Spam. Users will see the form again after submit, with an error stating that it is recognized as spam.', 'gwolle-gb'); ?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="honeypot"><?php esc_html_e('Honeypot', 'gwolle-gb'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'gwolle_gb-honeypot', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="honeypot" id="honeypot">
				<label for="honeypot">
					<?php esc_html_e('Use Honeypot.', 'gwolle-gb'); ?>
				</label><br />
				<span class="setting-description">
					<?php esc_html_e('This will add a non-visible input field to the form. It should not get filled in, but when it is, the entry will be marked as spam.', 'gwolle-gb'); ?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="gwolle_gb_nonce"><?php esc_html_e('Nonce', 'gwolle-gb'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'gwolle_gb-nonce', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="gwolle_gb_nonce" id="gwolle_gb_nonce">
				<label for="gwolle_gb_nonce">
					<?php esc_html_e('Use Nonce.', 'gwolle-gb'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('This will add a Nonce to the form. It is a way to check for a human user. If it does not validate, the entry will be marked as spam.', 'gwolle-gb');
					echo '<br />';
					$link_wp = '<a href="https://codex.wordpress.org/Wordpress_Nonce_Implementation" target="_blank">';
					/* translators: %s is a link */
					echo sprintf( esc_html__( 'If you want to know more about what a Nonce is and how it works, please read about it on the %sWordPress Codex%s.', 'gwolle-gb' ), $link_wp, '</a>' );
					echo '<br />';
					esc_html_e('If your website uses caching, it is possible that you get false-positives in your spamfolder. If this is the case, you could either disable the Nonce, or disable caching for the guestbook page.', 'gwolle-gb');
					?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="gwolle_gb_longtext"><?php esc_html_e('Long Text', 'gwolle-gb'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'gwolle_gb-longtext', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="gwolle_gb_longtext" id="gwolle_gb_longtext">
				<label for="gwolle_gb_longtext">
					<?php esc_html_e('Scan for Long Text.', 'gwolle-gb'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('This will scan entries for long words, which can be considered abusive. If there is a word with long text found, it will be automatically set to unchecked and you will need to moderate it manually.', 'gwolle-gb');
					echo '<br />';
					/* translators: %s is the number of characters */
					echo sprintf( esc_html__( 'The content has a limit set to %s characters, the author name to %s characters.', 'gwolle-gb' ), '100', '60' );
					?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="gwolle_gb_linkchecker"><?php esc_html_e('Link Checker', 'gwolle-gb'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'gwolle_gb-linkchecker', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="gwolle_gb_linkchecker" id="gwolle_gb_linkchecker">
				<label for="gwolle_gb_linkchecker">
					<?php esc_html_e('Scan for multiple links.', 'gwolle-gb'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('This will scan entries for links, which are often part of spam. If there are 2 links found in the content, it will be automatically marked as spam.', 'gwolle-gb');
					?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="gwolle_gb_timeout"><?php esc_html_e('Form Timeout', 'gwolle-gb'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'gwolle_gb-timeout', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="gwolle_gb_timeout" id="gwolle_gb_timeout">
				<label for="gwolle_gb_timeout">
					<?php esc_html_e('Set timeout for form submit.', 'gwolle-gb'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('This will enable a timer function for the form. If the form is submitted faster than the timeout the entry will be marked as spam.', 'gwolle-gb');
					?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="akismet-active"><?php esc_html_e('Akismet', 'gwolle-gb'); ?></label>
			</th>
			<td>
				<span class="setting-description">
					<a href="https://akismet.com/" title="<?php esc_html_e('Learn more about Akismet...', 'gwolle-gb'); ?>" target="_blank">
						<?php esc_html_e("What's Akismet?", 'gwolle-gb'); ?>
					</a><br />
					<?php
					$current_plugins = get_option('active_plugins');
					$wordpress_api_key = get_option('wordpress_api_key');

					// Check wether Akismet is installed and activated or not.
					if ( ! in_array('akismet/akismet.php', $current_plugins)) {
						echo esc_html__('Akismet is an external service by Automattic that acts as a spamfilter for guestbook entries.', 'gwolle-gb') . '<br />';
						// Akismet is not installed and activated. Show notice with suggestion to install it.
						esc_html_e("Akismet helps you to fight spam. It's free and easy to install. Download and install it today to stop spam in your guestbook.", 'gwolle-gb');
					} else if ( ! $wordpress_api_key) {
						// No WordPress API key is defined and set in the database.
						/* translators: First 2 %s are a strong element. Second %s is for a link. */
						echo sprintf( esc_html__("Sorry, wasn't able to locate your %sWordPress API key%s. You can enter it at the %sAkismet configuration page%s.", 'gwolle-gb'), '<strong>', '</strong>', '<a href="options-general.php?page=akismet-key-config">', '</a>' );
					} else {
						// Akismet is installed and activated and a WordPress API key exists (we just assume it is valid).
						echo '<input ';
						if ( get_option( 'gwolle_gb-akismet-active', 'false' ) === 'true' ) {
							echo 'checked="checked" ';
						}
						echo 'name="akismet-active" id="akismet-active" type="checkbox" />
							<label for="akismet-active">
							' . esc_html__('Use Akismet', 'gwolle-gb') . '
							</label><br />';
						esc_html_e('Akismet is an external service by Automattic that acts as a spamfilter for guestbook entries.', 'gwolle-gb'); echo '<br />';
						esc_html_e('The WordPress API key has been found, so you can start using Akismet right now.', 'gwolle-gb');
					}
					?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="gwolle_gb_sfs"><?php esc_html_e('Stop Forum Spam', 'gwolle-gb'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'gwolle_gb-sfs', 'false') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="gwolle_gb_sfs" id="gwolle_gb_sfs">
				<label for="gwolle_gb_sfs">
					<?php esc_html_e('Use Stop Forum Spam.', 'gwolle-gb'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('Stop Forum Spam is an external service that acts as a spamfilter for guestbook entries.', 'gwolle-gb'); echo '<br />';
					$link_wp = '<a href="https://www.stopforumspam.com" target="_blank">';
					/* translators: %s is a link */
					echo sprintf( esc_html__( 'If you want to know more about Stop Forum Spam and how it works, please read about it on their %swebsite%s.', 'gwolle-gb' ), $link_wp, '</a>' );
					?>
				</span>
			</td>
		</tr>

		<?php
		$form_setting = gwolle_gb_get_setting( 'form' );
		$antispam_question = gwolle_gb_sanitize_output( get_option('gwolle_gb-antispam-question') );
		$antispam_answer   = gwolle_gb_sanitize_output( get_option('gwolle_gb-antispam-answer') );
		?>
		<tr>
			<th scope="row"><label for="form-antispam-enabled"><?php esc_html_e('Security Question', 'gwolle-gb'); ?></label></th>
			<td>
				<div>
					<input type="checkbox" id="form-antispam-enabled" name="form-antispam-enabled"<?php
					if ( isset($form_setting['form_antispam_enabled']) && $form_setting['form_antispam_enabled'] === 'true' ) {
						echo ' checked="checked"';
					}
					?> />
					<label for="form-antispam-enabled"><?php esc_html_e('Use Custom Anti-Spam Security Question.', 'gwolle-gb'); ?></label><br />

					<label for="antispam-question" class="setting-description"><?php esc_html_e('Custom security question to battle spam:', 'gwolle-gb'); ?></label><br />
					<input name="antispam-question" type="text" id="antispam-question" value="<?php echo esc_attr( $antispam_question ); ?>" class="regular-text" placeholder="<?php esc_attr_e('12 + six =', 'gwolle-gb'); ?>" /><br />
					<label for="antispam-answer" class="setting-description"><?php esc_html_e('The answer to your security question:', 'gwolle-gb'); ?></label><br />
					<input name="antispam-answer" type="text" id="antispam-answer" value="<?php echo esc_attr( $antispam_answer ); ?>" class="regular-text" placeholder="<?php esc_attr_e('18', 'gwolle-gb'); ?>" /><br />
					<span class="setting-description"><?php esc_html_e('You can ask your visitors to answer a custom security question, so only real people can post an entry.', 'gwolle-gb'); ?></span>
				</div>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="gb_moderation_keys"><?php esc_html_e( 'Blocklist Moderation', 'gwolle-gb' ); ?></label></th>
			<td>
				<label for="gb_moderation_keys">
					<?php esc_html_e('Enter words for the blocklist.', 'gwolle-gb'); ?>
				</label><br />
				<textarea name="gb_moderation_keys" rows="10" cols="50" id="gb_moderation_keys" class="large-text code"><?php echo esc_textarea( get_option( 'gwolle_gb_addon-moderation_keys' ) ); ?></textarea>
				<span class="setting-description">
					<?php esc_html_e( 'When an entry contains any of these words in its content, name, URL, email, or IP address, it will be held in the moderation queue. One word or IP address per line. It will match inside words, so &#8220;press&#8221; will match &#8220;WordPress&#8221;.', 'gwolle-gb' ); ?>
				</span>
			</td>
		</tr>

		<tr>
			<th colspan="2">
				<p class="submit">
					<input type="submit" name="gwolle_gb_settings_antispam" id="gwolle_gb_settings_antispam" class="button-primary" value="<?php esc_attr_e('Save settings', 'gwolle-gb'); ?>" />
				</p>
			</th>
		</tr>

		</tbody>
	</table>

	<?php
}
