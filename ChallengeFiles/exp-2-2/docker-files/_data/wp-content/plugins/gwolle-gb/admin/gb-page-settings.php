<?php
/*
 * Settings page for the guestbook.
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Admin Settings page. Uses the files under /tabs for filling the tabs. This file mostly does saving of the settings.
 */
function gwolle_gb_page_settings() {

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	}

	gwolle_gb_admin_enqueue();
	$saved = false;
	$active_tab = 'gwolle_gb_forms';

	if ( isset( $_POST['option_page']) && $_POST['option_page'] === 'gwolle_gb_options' ) {
		gwolle_gb_page_settings_update();
		$saved = true;
		$active_tab = gwolle_gb_settings_active_tab();
	}
	$gwolle_gb_messages = gwolle_gb_get_messages();
	$gwolle_gb_errors = gwolle_gb_get_errors();
	?>

	<div class="wrap gwolle_gb">

		<div id="icon-gwolle-gb"><br /></div>
		<h1><?php esc_html_e('Settings', 'gwolle-gb'); ?> (Gwolle Guestbook) - v<?php echo GWOLLE_GB_VER; ?></h1>

		<?php
		if ( $gwolle_gb_errors ) {
			echo '
				<div id="message" class="updated fade notice is-dismissible error">' .
					$gwolle_gb_messages .
				'</div>';
		} else if ( $saved ) {
			echo '
				<div id="message" class="updated fade notice is-dismissible">
					<p>' . esc_html__('Changes saved.', 'gwolle-gb') . '</p>' .
					$gwolle_gb_messages .
			'</div>';
		}

		/* The rel attribute will be the form that becomes active */
		/* Do not use nav but h2, since it is using (in)visible content, not real navigation. */
		?>
		<h2 class="nav-tab-wrapper gwolle-nav-tab-wrapper" role="tablist">
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'gwolle_gb_forms')     { echo "nav-tab-active";} ?>" rel="gwolle_gb_forms"><?php /* translators: Settings page tab */ esc_html_e('Form', 'gwolle-gb'); ?></a>
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'gwolle_gb_reading')   { echo "nav-tab-active";} ?>" rel="gwolle_gb_reading"><?php /* translators: Settings page tab */ esc_html_e('Reading', 'gwolle-gb'); ?></a>
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'gwolle_gb_admin')     { echo "nav-tab-active";} ?>" rel="gwolle_gb_admin"><?php /* translators: Settings page tab */ esc_html_e('Admin', 'gwolle-gb'); ?></a>
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'gwolle_gb_antispam')  { echo "nav-tab-active";} ?>" rel="gwolle_gb_antispam"><?php /* translators: Settings page tab */ esc_html_e('Anti-spam', 'gwolle-gb'); ?></a>
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'gwolle_gb_mail')      { echo "nav-tab-active";} ?>" rel="gwolle_gb_mail"><?php /* translators: Settings page tab */ esc_html_e('Notifications', 'gwolle-gb'); ?></a>
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'gwolle_gb_debug')     { echo "nav-tab-active";} ?>" rel="gwolle_gb_debug"><?php /* translators: Settings page tab */ esc_html_e('Debug', 'gwolle-gb'); ?></a>
			<?php if ( ! is_multisite() ) { ?>
				<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'gwolle_gb_uninstall') { echo "nav-tab-active";} ?>" rel="gwolle_gb_uninstall"><?php /* translators: Settings page tab */ esc_html_e('Uninstall', 'gwolle-gb'); ?></a>
			<?php } ?>
		</h2>

		<form name="gwolle_gb_options" role="tabpanel" class="gwolle_gb_options gwolle_gb_forms <?php if ($active_tab === 'gwolle_gb_forms') { echo "active";} ?>" method="post" action="#">
			<?php gwolle_gb_page_settingstab_form(); ?>
		</form>


		<form name="gwolle_gb_options" role="tabpanel" class="gwolle_gb_options gwolle_gb_reading <?php if ($active_tab === 'gwolle_gb_reading') { echo "active";} ?>" method="post" action="#">
			<?php gwolle_gb_page_settingstab_reading(); ?>
		</form>


		<form name="gwolle_gb_options" role="tabpanel" class="gwolle_gb_options gwolle_gb_admin <?php if ($active_tab === 'gwolle_gb_admin') { echo "active";} ?>" method="post" action="#">
			<?php gwolle_gb_page_settingstab_admin(); ?>
		</form>


		<form name="gwolle_gb_options" role="tabpanel" class="gwolle_gb_options gwolle_gb_antispam <?php if ($active_tab === 'gwolle_gb_antispam') { echo "active";} ?>" method="post" action="#">
			<?php gwolle_gb_page_settingstab_antispam(); ?>
		</form>


		<form name="gwolle_gb_options" role="tabpanel" class="gwolle_gb_options gwolle_gb_mail <?php if ($active_tab === 'gwolle_gb_mail') { echo "active";} ?>" method="post" action="#">
			<?php gwolle_gb_page_settingstab_email(); ?>
		</form>


		<form name="gwolle_gb_options" role="tabpanel" class="gwolle_gb_options gwolle_gb_debug <?php if ($active_tab === 'gwolle_gb_debug') { echo "active";} ?>" method="post" action="#">
			<?php gwolle_gb_page_settingstab_debug(); ?>
		</form>


		<?php if ( ! is_multisite() ) { ?>
			<form name="gwolle_gb_options" role="tabpanel" class="gwolle_gb_options gwolle_gb_uninstall <?php if ($active_tab === 'gwolle_gb_uninstall') { echo "active";} ?>" method="post" action="#">
				<?php gwolle_gb_page_settingstab_uninstall(); ?>
			</form>
		<?php } ?>

	</div> <!-- wrap -->
	<?php
}


/*
 * Update Settings.
 *
 * @since 3.0.0
 */
function gwolle_gb_page_settings_update() {

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	}

	if ( isset( $_POST['option_page']) && $_POST['option_page'] === 'gwolle_gb_options' ) {
		if ( isset( $_POST['gwolle_gb_tab'] ) ) {
			$active_tab = (string) $_POST['gwolle_gb_tab'];
			gwolle_gb_settings_active_tab( $active_tab );

			switch ( $active_tab ) {
				case 'gwolle_gb_forms':
					/* Check Nonce */
					$verified = false;
					if ( isset($_POST['gwolle_gb_page_settings_formtab']) ) {
						$verified = wp_verify_nonce( $_POST['gwolle_gb_page_settings_formtab'], 'gwolle_gb_page_settings_formtab' );
					}
					if ( $verified === false ) {
						// Nonce is invalid.
						gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
						break;
					}

					if (isset($_POST['require_login']) && $_POST['require_login'] === 'on') {
						update_option('gwolle_gb-require_login', 'true');
					} else {
						update_option('gwolle_gb-require_login', 'false');
					}

					if (isset($_POST['labels_float']) && $_POST['labels_float'] === 'on') {
						update_option('gwolle_gb-labels_float', 'true');
					} else {
						update_option('gwolle_gb-labels_float', 'false');
					}

					// Always save it, even when empty, for MultiLingual plugins.
					$header = gwolle_gb_sanitize_input( $_POST['gb_header'] );
					update_option('gwolle_gb-header', $header);

					$notice = gwolle_gb_sanitize_input( $_POST['notice'], 'setting_textarea' );
					update_option('gwolle_gb-notice', $notice);

					if (isset($_POST['form_ajax']) && $_POST['form_ajax'] === 'on') {
						update_option('gwolle_gb-form_ajax', 'true');
					} else {
						update_option('gwolle_gb-form_ajax', 'false');
					}

					if (isset($_POST['store_ip']) && $_POST['store_ip'] === 'on') {
						update_option('gwolle_gb-store_ip', 'true');
					} else {
						update_option('gwolle_gb-store_ip', 'false');
					}

					if (isset($_POST['gb_remove_ip']) && $_POST['gb_remove_ip'] === 'on') {
						gwolle_gb_remove_ip_host();
						gwolle_gb_add_message( '<p>' . esc_html__('IP address and hostname was removed from all the entries.', 'gwolle-gb') . '</p>', false, false);
					}

					$form_setting_from_db = gwolle_gb_get_setting( 'form' );
					$list = array(
						'form_name_enabled',
						'form_name_mandatory',
						'form_city_enabled',
						'form_city_mandatory',
						'form_email_enabled',
						'form_email_mandatory',
						'form_homepage_enabled',
						'form_homepage_mandatory',
						'form_message_enabled',
						'form_message_mandatory',
						'form_message_maxlength',
						'form_bbcode_enabled',
						'form_privacy_enabled',
						);
					$form_setting = array();
					// checkboxes
					foreach ( $list as $item ) {
						if ( isset($_POST["$item"]) && $_POST["$item"] === 'on' ) {
							$form_setting["$item"] = 'true';
						} else {
							$form_setting["$item"] = 'false';
						}
					}

					/* form-antispam-enabled moved to antispam tab, simply restore it here from old setting. */
					$form_setting['form_antispam_enabled'] = $form_setting_from_db['form_antispam_enabled'];

					// select with options, 0 is default.
					if ( isset($_POST['form_message_maxlength']) && is_numeric($_POST['form_message_maxlength']) && $_POST['form_message_maxlength'] > 0 ) {
						$form_setting['form_message_maxlength'] = (int) $_POST['form_message_maxlength'];
					} else {
						$form_setting['form_message_maxlength'] = 0;
					}
					update_option( 'gwolle_gb-form', $form_setting );
					break;

				case 'gwolle_gb_reading':
					/* Check Nonce */
					$verified = false;
					if ( isset($_POST['gwolle_gb_page_settings_readingtab']) ) {
						$verified = wp_verify_nonce( $_POST['gwolle_gb_page_settings_readingtab'], 'gwolle_gb_page_settings_readingtab' );
					}
					if ( $verified === false ) {
						// Nonce is invalid.
						gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
						break;
					}

					if ( isset($_POST['entriesPerPage']) && is_numeric($_POST['entriesPerPage']) && $_POST['entriesPerPage'] > 0 ) {
						update_option('gwolle_gb-entriesPerPage', (int) $_POST['entriesPerPage']);
					}

					if (isset($_POST['showLineBreaks']) && $_POST['showLineBreaks'] === 'on') {
						update_option('gwolle_gb-showLineBreaks', 'true');
					} else {
						update_option('gwolle_gb-showLineBreaks', 'false');
					}

					if ( isset($_POST['excerpt_length']) && is_numeric($_POST['excerpt_length']) ) {
						update_option('gwolle_gb-excerpt_length', (int) $_POST['excerpt_length']);
					}

					if (isset($_POST['showSmilies']) && $_POST['showSmilies'] === 'on') {
						update_option('gwolle_gb-showSmilies', 'true');
					} else {
						update_option('gwolle_gb-showSmilies', 'false');
					}

					if (isset($_POST['linkAuthorWebsite']) && $_POST['linkAuthorWebsite'] === 'on') {
						update_option('gwolle_gb-linkAuthorWebsite', 'true');
					} else {
						update_option('gwolle_gb-linkAuthorWebsite', 'false');
					}

					if (isset($_POST['admin_style']) && $_POST['admin_style'] === 'on') {
						update_option('gwolle_gb-admin_style', 'true');
					} else {
						update_option('gwolle_gb-admin_style', 'false');
					}

					if (isset($_POST['navigation']) && (int) $_POST['navigation'] === 0) {
						update_option('gwolle_gb-navigation', 0);
					} else if (isset($_POST['navigation']) && (int) $_POST['navigation'] === 1) {
						update_option('gwolle_gb-navigation', 1);
					}

					if (isset($_POST['paginate_all']) && $_POST['paginate_all'] === 'on') {
						update_option('gwolle_gb-paginate_all', 'true');
					} else {
						update_option('gwolle_gb-paginate_all', 'false');
					}

					$list = array(
						'read_avatar',
						'read_name',
						'read_city',
						'read_datetime',
						'read_date',
						'read_content',
						'read_aavatar',
						'read_editlink',
						);
					$read_setting = array();
					foreach ( $list as $item ) {
						if ( isset($_POST["$item"]) && $_POST["$item"] === 'on' ) {
							$read_setting["$item"] = 'true';
						} else {
							$read_setting["$item"] = 'false';
						}
					}
					update_option( 'gwolle_gb-read', $read_setting );
					break;

				case 'gwolle_gb_admin':
					/* Check Nonce */
					$verified = false;
					if ( isset($_POST['gwolle_gb_page_settings_admintab']) ) {
						$verified = wp_verify_nonce( $_POST['gwolle_gb_page_settings_admintab'], 'gwolle_gb_page_settings_admintab' );
					}
					if ( $verified === false ) {
						// Nonce is invalid.
						gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
						break;
					}

					if ( isset($_POST['entries_per_page']) && is_numeric($_POST['entries_per_page']) && $_POST['entries_per_page'] > 0 ) {
						update_option( 'gwolle_gb-entries_per_page', (int) $_POST['entries_per_page']);
					}

					if (isset($_POST['showEntryIcons']) && $_POST['showEntryIcons'] === 'on') {
						update_option('gwolle_gb-showEntryIcons', 'true');
					} else {
						update_option('gwolle_gb-showEntryIcons', 'false');
					}
					break;

				case 'gwolle_gb_antispam':
					/* Check Nonce */
					$verified = false;
					if ( isset($_POST['gwolle_gb_page_settings_antispamtab']) ) {
						$verified = wp_verify_nonce( $_POST['gwolle_gb_page_settings_antispamtab'], 'gwolle_gb_page_settings_antispamtab' );
					}
					if ( $verified === false ) {
						// Nonce is invalid.
						gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
						break;
					}

					if (isset($_POST['moderate-entries']) && $_POST['moderate-entries'] === 'on') {
						update_option('gwolle_gb-moderate-entries', 'true');
					} else {
						update_option('gwolle_gb-moderate-entries', 'false');
					}

					if (isset($_POST['refuse-spam']) && $_POST['refuse-spam'] === 'on') {
						update_option('gwolle_gb-refuse-spam', 'true');
					} else {
						update_option('gwolle_gb-refuse-spam', 'false');
					}

					if (isset($_POST['honeypot']) && $_POST['honeypot'] === 'on') {
						update_option('gwolle_gb-honeypot', 'true');
					} else {
						update_option('gwolle_gb-honeypot', 'false');
					}

					if (isset($_POST['gwolle_gb_nonce']) && $_POST['gwolle_gb_nonce'] === 'on') {
						update_option('gwolle_gb-nonce', 'true');
					} else {
						update_option('gwolle_gb-nonce', 'false');
					}

					if (isset($_POST['gwolle_gb_longtext']) && $_POST['gwolle_gb_longtext'] === 'on') {
						update_option('gwolle_gb-longtext', 'true');
					} else {
						update_option('gwolle_gb-longtext', 'false');
					}

					if (isset($_POST['gwolle_gb_linkchecker']) && $_POST['gwolle_gb_linkchecker'] === 'on') {
						update_option('gwolle_gb-linkchecker', 'true');
					} else {
						update_option('gwolle_gb-linkchecker', 'false');
					}

					if (isset($_POST['gwolle_gb_timeout']) && $_POST['gwolle_gb_timeout'] === 'on') {
						update_option('gwolle_gb-timeout', 'true');
					} else {
						update_option('gwolle_gb-timeout', 'false');
					}

					if (isset($_POST['akismet-active']) && $_POST['akismet-active'] === 'on') {
						update_option('gwolle_gb-akismet-active', 'true');
					} else {
						update_option('gwolle_gb-akismet-active', 'false');
					}

					if (isset($_POST['gwolle_gb_sfs']) && $_POST['gwolle_gb_sfs'] === 'on') {
						update_option('gwolle_gb-sfs', 'true');
					} else {
						update_option('gwolle_gb-sfs', 'false');
					}

					$form_setting = gwolle_gb_get_setting( 'form' );
					if ( isset($_POST['form-antispam-enabled']) && $_POST['form-antispam-enabled'] === 'on' ) {
						$form_setting['form_antispam_enabled'] = 'true';
					} else {
						$form_setting['form_antispam_enabled'] = 'false';
					}
					update_option( 'gwolle_gb-form', $form_setting );

					if ( isset($_POST['antispam-question']) ) {
						update_option('gwolle_gb-antispam-question', gwolle_gb_sanitize_input($_POST['antispam-question']));
					}
					if ( isset($_POST['antispam-answer']) ) {
						update_option('gwolle_gb-antispam-answer', gwolle_gb_sanitize_input($_POST['antispam-answer']));
					}

					if ( isset($_POST['gb_moderation_keys']) ) {
						$blocklist = gwolle_gb_sanitize_input( $_POST['gb_moderation_keys'], 'setting_textarea' );
						$blocklist = explode( "\n", $blocklist );
						$blocklist = array_filter( array_map( 'trim', $blocklist ) );
						$blocklist = array_unique( $blocklist );
						$blocklist = implode( "\n", $blocklist );
						update_option('gwolle_gb_addon-moderation_keys', $blocklist);
					}
					break;

				case 'gwolle_gb_mail':
					/* Check Nonce */
					$verified = false;
					if ( isset($_POST['gwolle_gb_page_settings_emailtab']) ) {
						$verified = wp_verify_nonce( $_POST['gwolle_gb_page_settings_emailtab'], 'gwolle_gb_page_settings_emailtab' );
					}
					if ( $verified === false ) {
						// Nonce is invalid.
						gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
						break;
					}

					if ( isset($_POST['admin_mail_from']) && $_POST['admin_mail_from'] !== gwolle_gb_sanitize_output( get_option('gwolle_gb-mail-from') ) ) {
						$admin_mail_from = gwolle_gb_sanitize_input( $_POST['admin_mail_from'] );
						if ( filter_var( $admin_mail_from, FILTER_VALIDATE_EMAIL ) ) {
							// Valid Email address.
							update_option('gwolle_gb-mail-from', $admin_mail_from);
						}
					}

					if ( isset($_POST['unsubscribe']) && $_POST['unsubscribe'] > 0 ) {
						$user_id = (int) $_POST['unsubscribe'];
						$user_ids = array();

						$user_ids_old = get_option('gwolle_gb-notifyByMail' );
						if ( strlen($user_ids_old) > 0 ) {
							$user_ids_old = explode( ',', $user_ids_old );
							foreach ( $user_ids_old as $user_id_old ) {
								if ( (int) $user_id_old === (int) $user_id ) {
									continue;
								}
								if ( is_numeric($user_id_old) ) {
									$user_ids[] = (int) $user_id_old;
								}
							}
						}

						$user_ids = implode( ',', $user_ids );
						update_option('gwolle_gb-notifyByMail', $user_ids);
					}

					if ( isset($_POST['subscribe']) && $_POST['subscribe'] > 0 ) {
						$user_id = (int) $_POST['subscribe'];
						$user_ids = array();

						$user_ids_old = get_option('gwolle_gb-notifyByMail' );
						if ( strlen($user_ids_old) > 0 ) {
							$user_ids_old = explode( ',', $user_ids_old );
							foreach ( $user_ids_old as $user_id_old ) {
								if ( (int) $user_id_old === (int) $user_id ) {
									continue; // will be added again below the loop
								}
								if ( is_numeric($user_id_old) ) {
									$user_ids[] = (int) $user_id_old;
								}
							}
						}
						$user_ids[] = $user_id; // Really add it.

						$user_ids = implode( ',', $user_ids );
						update_option('gwolle_gb-notifyByMail', $user_ids);
					}

					if ( isset($_POST['adminMailContent']) ) {
						$mail_content = gwolle_gb_sanitize_input( $_POST['adminMailContent'], 'setting_textarea' );
						update_option('gwolle_gb-adminMailContent', $mail_content);
					}

					if (isset($_POST['mail_author']) && $_POST['mail_author'] === 'on') {
						update_option('gwolle_gb-mail_author', 'true');
					} else {
						update_option('gwolle_gb-mail_author', 'false');
					}

					if ( isset($_POST['authorMailContent']) ) {
						$mail_content = gwolle_gb_sanitize_input( $_POST['authorMailContent'], 'setting_textarea' );
						update_option('gwolle_gb-authorMailContent', $mail_content);
					}

					if (isset($_POST['mail_author_moderation']) && $_POST['mail_author_moderation'] === 'on') {
						update_option('gwolle_gb-mail_author_moderation', 'true');
					} else {
						update_option('gwolle_gb-mail_author_moderation', 'false');
					}

					if ( isset($_POST['authormoderationcontent']) ) {
						$mail_content = gwolle_gb_sanitize_input( $_POST['authormoderationcontent'], 'setting_textarea' );
						update_option('gwolle_gb-authormoderationcontent', $mail_content);
					}

					if ( isset($_POST['gwolle_gb-mail_admin_replyContent']) ) {
						$mail_content = gwolle_gb_sanitize_input( $_POST['gwolle_gb-mail_admin_replyContent'], 'setting_textarea' );
						update_option('gwolle_gb-mail_admin_replyContent', $mail_content);
					}
					break;

				case 'gwolle_gb_debug':
					break;

				case 'gwolle_gb_uninstall':
					/* Check Nonce */
					$verified = false;
					if ( isset($_POST['gwolle_gb_page_settings_uninstalltab']) ) {
						$verified = wp_verify_nonce( $_POST['gwolle_gb_page_settings_uninstalltab'], 'gwolle_gb_page_settings_uninstalltab' );
					}
					if ( $verified === false ) {
						// Nonce is invalid.
						gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
						break;
					}

					if (isset($_POST['gwolle_gb_uninstall_confirmed']) && $_POST['gwolle_gb_uninstall_confirmed'] === 'on') {
						if ( ! is_multisite() ) {
							// uninstall the plugin -> delete all tables and preferences of the plugin
							gwolle_gb_uninstall();
							gwolle_gb_add_message( '<p>' . esc_html__('The entries and settings have been removed.', 'gwolle-gb') . '</p>', false, false);
							gwolle_gb_add_message( '<p>' . esc_html__('The plugin is deactivated.', 'gwolle-gb') . '</p>', false, false);
							$dashboard = '<a href="' . admin_url( '/index.php' ) . '">' . esc_html__('Dashboard', 'gwolle-gb') . '</a>';
							/* translators: %s is a link to the dashboard */
							gwolle_gb_add_message( '<p>' . sprintf( __('You can now go to your %s.', 'gwolle-gb'), $dashboard ) . '</p>', false, false);
						}
					} else {
						// Uninstallation not confirmed.
					}
					break;

				default:
					/* Just load the first tab */
					gwolle_gb_settings_active_tab( 'gwolle_gb_forms' );
					break;
			}
		}
	}
}


/*
 * Set and Get active tab for settings page.
 *
 * @param  string $active_tab text string with active tab (optional).
 * @return string text string with active tab.
 *
 * @since 3.0.0
 */
function gwolle_gb_settings_active_tab( $active_tab = false ) {

	static $active_tab_static;

	if ( $active_tab ) {
		$active_tab_static = $active_tab;
	}

	return $active_tab_static;

}
