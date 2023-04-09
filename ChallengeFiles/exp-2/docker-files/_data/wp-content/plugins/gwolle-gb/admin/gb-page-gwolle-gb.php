<?php
/*
 * Shows the overview screen with the widget-like windows.
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Main Admin page.
 */
function gwolle_gb_welcome() {

	if ( ! current_user_can('moderate_comments') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	}

	/* Post Handling: Save notification setting */
	if ( isset( $_POST['option_page']) && $_POST['option_page'] === 'gwolle_gb_options' ) {
		gwolle_gb_welcome_post();
	}

	gwolle_gb_admin_enqueue();

	add_meta_box('gwolle_gb_right_now', esc_html__('Welcome to the Guestbook!', 'gwolle-gb'), 'gwolle_gb_overview', 'gwolle_gb_welcome', 'normal');
	add_meta_box('gwolle_gb_visibility', esc_html__('Visibility', 'gwolle-gb'), 'gwolle_gb_overview_visibility', 'gwolle_gb_welcome', 'normal');
	add_meta_box('gwolle_gb_notification', esc_html__('E-mail Notifications', 'gwolle-gb'), 'gwolle_gb_overview_notification', 'gwolle_gb_welcome', 'normal');
	add_meta_box('gwolle_gb_thanks', esc_html__('Third Party', 'gwolle-gb'), 'gwolle_gb_overview_thanks', 'gwolle_gb_welcome', 'normal');

	add_meta_box('gwolle_gb_help', esc_html__('Help', 'gwolle-gb'), 'gwolle_gb_overview_help', 'gwolle_gb_welcome', 'right');
	add_meta_box('gwolle_gb_support', esc_html__('Support and Translations', 'gwolle-gb'), 'gwolle_gb_overview_support', 'gwolle_gb_welcome', 'right');
	add_meta_box('gwolle_gb_review', /* translators: Reviews on the plugin page at WordPress.org */ esc_html__('Review', 'gwolle-gb'), 'gwolle_gb_overview_review', 'gwolle_gb_welcome', 'right');
	$active = is_plugin_active( 'gwolle-gb-addon/gwolle-gb-addon.php' ); // true or false
	if ( $active ) {
		add_meta_box('gwolle_gb_addon', esc_html__('The Add-On', 'gwolle-gb'), 'gwolle_gb_overview_addon', 'gwolle_gb_welcome', 'right');
	} ?>

	<div class="wrap gwolle_gb">
		<div id="icon-gwolle-gb"><br /></div>
		<?php
		$heading = esc_html__('Gwolle Guestbook', 'gwolle-gb');
		if ( $heading !== 'Gwolle Guestbook' ) { // translated, so we add the real name.
			$heading .= ' (Gwolle Guestbook)';
		} ?>
		<h1><?php echo $heading . ' - v' . GWOLLE_GB_VER; ?></h1>

		<?php
		$gwolle_gb_messages = gwolle_gb_get_messages();
		$gwolle_gb_errors   = gwolle_gb_get_errors();
		$messageclass = '';
		if ( $gwolle_gb_errors ) {
			$messageclass = 'error';
		}

		if ( $gwolle_gb_messages ) {
			echo '
				<div id="message" class="updated fade notice is-dismissible ' . esc_attr( $messageclass ) . ' ">' .
					$gwolle_gb_messages .
				'</div>';
		} ?>

		<div id="dashboard-widgets-wrap" class="gwolle_gb_welcome">
			<div id="dashboard-widgets" class="metabox-holder">
				<div class="postbox-container">
					<?php do_meta_boxes( 'gwolle_gb_welcome', 'normal', '' ); ?>
				</div>
				<div class="postbox-container">
					<?php do_meta_boxes( 'gwolle_gb_welcome', 'right', '' ); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}


/*
 * Metabox with overview.
 */
function gwolle_gb_overview() {

	// Calculate the number of entries
	$count = array();
	$count['checked'] = gwolle_gb_get_entry_count( array(
			'checked' => 'checked',
			'trash'   => 'notrash',
			'spam'    => 'nospam',
		));
	$count['unchecked'] = gwolle_gb_get_entry_count( array(
			'checked' => 'unchecked',
			'trash'   => 'notrash',
			'spam'    => 'nospam',
		));
	$count['spam']    = gwolle_gb_get_entry_count( array( 'spam'  => 'spam'  ) );
	$count['trash']   = gwolle_gb_get_entry_count( array( 'trash' => 'trash' ) );
	$count['all']     = gwolle_gb_get_entry_count( array( 'all'   => 'all'   ) );
	?>

	<div class="table table_content gwolle_gb gwolle-gb-overview">
		<h3><?php esc_html_e('Overview', 'gwolle-gb'); ?></h3>

		<table>
			<tbody>
				<tr class="gwolle-gb-overview-all">
					<td>
						<a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&amp;show=all' ); ?>">
							<?php echo $count['all']; ?>
						</a>
					</td>
					<td class="colored">
						<?php echo _n( 'Entry total', 'Entries total', $count['all'], 'gwolle-gb' ); ?>
					</td>
				</tr>

				<tr class="gwolle-gb-overview-checked">
					<td>
						<a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&amp;show=checked' ); ?>">
						<?php echo $count['checked']; ?>
					</a></td>
					<td class="colored">
						<?php echo _n( 'Unlocked entry', 'Unlocked entries', $count['checked'], 'gwolle-gb' ); ?>
					</td>
				</tr>

				<tr class="gwolle-gb-overview-unchecked">
					<td>
						<a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&amp;show=unchecked' ); ?>">
						<?php echo $count['unchecked']; ?>
					</a></td>
					<td class="colored">
						<?php echo _n( 'New entry', 'New entries', $count['unchecked'], 'gwolle-gb' ); ?>
					</td>
				</tr>

				<tr class="gwolle-gb-overview-spam">
					<td>
						<a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&amp;show=spam' ); ?>">
						<?php echo $count['spam']; ?>
					</a></td>
					<td class="colored">
						<?php echo _n( 'Spam entry', 'Spam entries', $count['spam'], 'gwolle-gb' ); ?>
					</td>
				</tr>

				<tr class="gwolle-gb-overview-trash">
					<td>
						<a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&amp;show=trash' ); ?>">
						<?php echo $count['trash']; ?>
					</a></td>
					<td class="colored">
						<?php echo _n( 'Trashed entry', 'Trashed entries', $count['trash'], 'gwolle-gb' ); ?>
					</td>
				</tr>

			</tbody>
		</table>
	</div><!-- .table -->
	<div class="versions">
		<p>
			<?php
			$postid = gwolle_gb_get_postid_biggest_book();
			if ( $postid ) {
				$permalink = gwolle_gb_get_permalink( $postid );
				?>
				<a class="button rbutton button button-primary" href="<?php echo esc_attr( $permalink ); ?>"><?php esc_html_e('View Guestbook', 'gwolle-gb'); ?></a>
				<?php
			} ?>
			<a class="button rbutton button button-primary" href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/editor.php' ); ?>"><?php esc_html_e('Write admin entry', 'gwolle-gb'); ?></a>
		</p>
		<p>
			<?php
			global $wp_rewrite;
			$permalinks = $wp_rewrite->permalink_structure;
			if ( $permalinks ) {
				?>
				<a href="<?php bloginfo('url'); ?>/feed/gwolle_gb"><?php esc_html_e('Subscribe to RSS Feed', 'gwolle-gb'); ?></a>
				<?php
			} else {
				?>
				<a href="<?php bloginfo('url'); ?>/?feed=gwolle_gb"><?php esc_html_e('Subscribe to RSS Feed', 'gwolle-gb'); ?></a>
				<?php
			} ?>
		</p>
	</div>
<?php }


/*
 * Metabox with checbox for subscribing this user to email notifications.
 */
function gwolle_gb_overview_notification() {

	// Check if function mail() exists. If not, display a hint to the user.
	if ( ! function_exists('mail') ) {
		/* translators: %s is for the code element */
		echo '<p class="setting-description">' .
			sprintf( esc_html__('Sorry, but the function %smail()%s required to notify you by mail is not enabled in your PHP configuration. You might want to install a WordPress plugin that uses SMTP instead of %smail()%s. Or you can contact your hosting provider.', 'gwolle-gb'), '<code>', '</code>', '<code>', '</code>' )
			. '</p>';
	}
	$current_user_id = get_current_user_id();
	$current_user_notification = false;
	$user_ids = get_option('gwolle_gb-notifyByMail' );
	if ( strlen($user_ids) > 0 ) {
		$user_ids = explode( ',', $user_ids );
		if ( is_array($user_ids) && ! empty($user_ids) ) {
			if ( in_array( $current_user_id, $user_ids ) ) {
				$current_user_notification = true;
			}
		}
	} ?>
	<form name="gwolle_gb_welcome" method="post" action="#">
		<?php
		settings_fields( 'gwolle_gb_options' );
		do_settings_sections( 'gwolle_gb_options' );

		/* Nonce */
		$nonce = wp_create_nonce( 'gwolle_gb_page_gwolle' );
		echo '<input type="hidden" id="gwolle_gb_wpnonce" name="gwolle_gb_wpnonce" value="' . esc_attr( $nonce ) . '" />';
		?>
		<input name="notify_by_mail" type="checkbox" id="notify_by_mail" <?php
			if ( $current_user_notification ) {
				echo 'checked="checked"';
			} ?> >
		<label for="notify_by_mail" class="setting-description"><?php esc_html_e('Send me an e-mail when a new entry has been posted.', 'gwolle-gb'); ?></label>
		<p class="submit">
			<input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e('Save setting', 'gwolle-gb'); ?>" />
		</p>
	</form>
	<div>
		<?php esc_html_e('The following users have subscribed to this service:', 'gwolle-gb');

		if ( is_array($user_ids) && ! empty($user_ids) ) {
			echo '<ul>';
			foreach ( $user_ids as $user_id ) {
				$user_info = get_userdata( (int) $user_id );
				if ($user_info === false) {
					// Invalid $user_id
					continue;
				}
				echo '<li>';
				if ( $user_info->ID === get_current_user_id() ) {
					echo '<strong>' . esc_html__('You', 'gwolle-gb') . '</strong>';
				} else {
					echo esc_html( $user_info->first_name . ' ' . $user_info->last_name );
				}
				echo esc_html( ' (' . $user_info->user_email . ')' );
				echo '</li>';
			}
			echo '</ul>';
		} else {
			echo '<br /><i>(' . esc_html__('No subscriber yet', 'gwolle-gb') . ')</i>';
		}
		?>
	</div>
	<?php
}


/*
 * Metabox with overview of third parties.
 */
function gwolle_gb_overview_thanks() {
	echo '<h3>' . esc_html__('This plugin uses the following scripts and services:', 'gwolle-gb') . '</h3>
	<ul class="ul-disc">
		<li><a href="https://akismet.com/tos/" target="_blank">' . esc_html__( 'Akismet', 'gwolle-gb' ) . '</a></li>
		<li><a href="https://www.stopforumspam.com" target="_blank">' . esc_html__( 'Stop Forum Spam', 'gwolle-gb' ) . '</a></li>
		<li><a href="https://markitup.jaysalvat.com/" target="_blank">' . esc_html__( 'MarkItUp', 'gwolle-gb' ) . '</a></li>
		<li><a href="https://supersimpleslider.com/" target="_blank">' . esc_html__( 'Super Simple Slider', 'gwolle-gb' ) . '</a></li>
	</ul>';
}


/*
 * Metabox with quick help text.
 */
function gwolle_gb_overview_help() {
	echo '<h3>' . esc_html__('This is how you can get your guestbook displayed on your website:', 'gwolle-gb') . '</h3>
	<ul class="ul-disc">
		<li>' . esc_html__('Create a new page.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Choose a title and set &quot;[gwolle_gb]&quot; (without the quotes) as the content.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Shortcode:', 'gwolle-gb') . ' <input type="text" name="gwolle_gb_shortcode" size="10" value="[gwolle_gb]" id="gwolle_gb_shortcode" /></li>
	</ul>';
}


/*
 * Metabox with quick help text.
 */
function gwolle_gb_overview_visibility() {
	echo '<h3>' . esc_html__('These entries will be visible for your visitors:', 'gwolle-gb') . '</h3>
	<ul class="ul-disc">
		<li>' . esc_html__('Marked as Checked.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Not marked as Spam.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Not marked as Trash.', 'gwolle-gb') . '</li>
	</ul>';
}


/*
 * Metabox with text about support and translations.
 */
function gwolle_gb_overview_support() {
	?>
	<h3><?php esc_html_e('Support.', 'gwolle-gb'); ?></h3>
	<p><?php
		$support = '<a href="https://wordpress.org/support/plugin/gwolle-gb" target="_blank">';
		/* translators: %s is a link */
		echo sprintf( esc_html__( 'If you have a problem or a feature request, please post it on the %ssupport forum at wordpress.org%s.', 'gwolle-gb' ), $support, '</a>' ); ?>
		<?php esc_html_e('I will do my best to respond as soon as possible.', 'gwolle-gb'); ?><br />
		<?php esc_html_e('If you send me an email, I will not reply. Please use the support forum.', 'gwolle-gb'); ?>
	</p>

	<h3><?php esc_html_e('Translations.', 'gwolle-gb'); ?></h3>
	<p><?php
		$link = '<a href="https://translate.wordpress.org/projects/wp-plugins/gwolle-gb" target="_blank">';
		/* translators: %s is a link */
		echo sprintf( esc_html__( 'Translations can be added very easily through %sGlotPress%s.', 'gwolle-gb' ), $link, '</a>' ); echo '<br />';
		echo sprintf( esc_html__( "You can start translating strings there for your locale. They need to be validated though, so if there's no validator yet, and you want to apply for being validator (PTE), please post it on the %ssupport forum%s.", 'gwolle-gb' ), $support, '</a>' ); echo '<br />';
		$make = '<a href="https://make.wordpress.org/polyglots/" target="_blank">';
		/* translators: %s is a link */
		echo sprintf( esc_html__( 'I will make a request on %smake/polyglots%s to have you added as validator for this plugin/locale.', 'gwolle-gb' ), $make, '</a>' ); ?>
	</p>
	<?php
}


/*
 * Metabox with text about wp.org reviews.
 * Call for donations is gone.
 */
function gwolle_gb_overview_review() {
	?>
	<h3><?php esc_html_e('Review this plugin.', 'gwolle-gb'); ?></h3>
	<p><?php
		$review = '<a href="https://wordpress.org/support/view/plugin-reviews/gwolle-gb?rate=5#postform" target="_blank">';
		/* translators: %s is a link */
		echo sprintf( esc_html__( 'If this plugin has any value to you, then please leave a review at %sthe plugin page%s at wordpress.org.', 'gwolle-gb' ), $review, '</a>' ); ?>
	</p>
	<?php
}


/*
 * Metabox with overview of Add-On links.
 * Only shown when the Add-On is active.
 *
 * @since 4.0.2
 */
function gwolle_gb_overview_addon() {
	echo '<h3>' . esc_html__('Visit the ZenoWeb webshop for the Add-On.', 'gwolle-gb') . '</h3>
	<ul class="ul-disc">
		<li><a href="https://zenoweb.nl/changelog/" target="_blank">' . esc_html__( 'Changelog and Updates', 'gwolle-gb' ) . '</a></li>
		<li><a href="https://zenoweb.nl/faq/" target="_blank">' . esc_html__( 'FAQ', 'gwolle-gb' ) . '</a></li>
		<li><a href="https://zenoweb.nl/forums/forum/guestbook-add-on/" target="_blank">' . esc_html__( 'Support Forum', 'gwolle-gb' ) . '</a></li>
		<li><a href="https://zenoweb.nl/reviews/" target="_blank">' . esc_html__( 'Reviews', 'gwolle-gb' ) . '</a></li>
		<li><a href="https://zenoweb.nl/log-in/" target="_blank">' . esc_html__( 'Log in', 'gwolle-gb' ) . '</a></li>
	</ul>
	';
	if ( defined( 'GWOLLE_GB_ADDON_VER' ) ) {
		/* translators: %s is for the version of the add-on */
		echo sprintf( esc_html__( 'You are currently using v%s of The Add-On.', 'gwolle-gb' ), GWOLLE_GB_ADDON_VER );
	}
}


/*
 * Subscribe this user to email notifications.
 */
function gwolle_gb_welcome_post() {

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['gwolle_gb_wpnonce']) ) {
		$verified = wp_verify_nonce( $_POST['gwolle_gb_wpnonce'], 'gwolle_gb_page_gwolle' );
	}
	if ( $verified === false ) {
		gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
		return;
	}

	/* E-mail notification option. */
	if ( isset($_POST['notify_by_mail']) && $_POST['notify_by_mail'] === 'on' ) {
		// Turn the notification ON for the current user.
		$user_id = get_current_user_id();
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
		$user_ids[] = (int) $user_id; // Really add it.

		$user_ids = implode( ',', $user_ids );
		update_option('gwolle_gb-notifyByMail', $user_ids);

		gwolle_gb_add_message( '<p>' . esc_html__('Changes saved.', 'gwolle-gb') . '</p>', false, false);
	} else if ( ! isset($_POST['notify_by_mail'] ) ) {
		// Turn the notification OFF for the current user
		$user_id = get_current_user_id();
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
		gwolle_gb_add_message( '<p>' . esc_html__('Changes saved.', 'gwolle-gb') . '</p>', false, false);
	}
}
