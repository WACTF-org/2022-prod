<?php
/*
 * Editor for editing entries and writing admin entries.
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Admin page with the entry editor. Used for new and existing entries.
 */
function gwolle_gb_page_editor() {

	if ( ! current_user_can('moderate_comments') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	}

	gwolle_gb_admin_enqueue();
	gwolle_gb_register();

	$gwolle_gb_errors = false;
	$section_heading = esc_html__('Edit guestbook entry', 'gwolle-gb');

	// Always fetch the requested entry, so we can compare the $entry and the $_POST.
	$entry = new gwolle_gb_entry();

	if ( isset($_POST['entry_id']) ) { // _POST has preference over _GET
		$entry_id = (int) $_POST['entry_id'];
	} else if ( isset($_GET['entry_id']) ) {
		$entry_id = (int) $_GET['entry_id'];
	}
	if ( isset($entry_id) && $entry_id > 0 ) {
		$result = $entry->load( $entry_id );
		if ( ! $result ) {
			gwolle_gb_add_message( '<p>' . esc_html__('Entry could not be found.', 'gwolle-gb') . '</p>', true, false);
			$gwolle_gb_errors = true;
			$section_heading = esc_html__('Guestbook entry (error)', 'gwolle-gb');
		}
	} else {
		$section_heading = esc_html__('New guestbook entry', 'gwolle-gb');
	}

	/*
	 * Handle the $_POST
	 */
	if ( isset($_POST['gwolle_gb_page']) && $_POST['gwolle_gb_page'] === 'editor' && $gwolle_gb_errors === false ) {
		$entry = gwolle_gb_page_editor_update( $entry );
	}
	$gwolle_gb_messages = gwolle_gb_get_messages();
	$gwolle_gb_errors = gwolle_gb_get_errors();
	$messageclass = '';
	if ( $gwolle_gb_errors ) {
		$messageclass = 'error';
	}

	/*
	 * Build the Page and the Form
	 */
	?>
	<div class="wrap gwolle_gb">
		<div id="icon-gwolle-gb"><br /></div>
		<h1><?php echo $section_heading; ?> (Gwolle Guestbook) - v<?php echo GWOLLE_GB_VER; ?></h1>

		<?php
		if ( $gwolle_gb_messages ) {
			echo '
				<div id="message" class="updated fade notice is-dismissible ' . $messageclass . ' ">' .
					$gwolle_gb_messages .
				'</div>';
		}
		?>

		<form name="gwolle_gb_editor" id="gwolle_gb_editor" method="POST" action="#" accept-charset="UTF-8">
			<input type="hidden" name="gwolle_gb_page" value="editor" />
			<input type="hidden" name="entry_id" value="<?php echo (int) $entry->get_id(); ?>" />

			<?php
			/* Nonce */
			$nonce = wp_create_nonce( 'gwolle_gb_page_editor' );
			echo '<input type="hidden" id="gwolle_gb_wpnonce" name="gwolle_gb_wpnonce" value="' . esc_attr( $nonce ) . '" />';
			?>

			<div id="poststuff" class="gwolle_gb_editor">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<?php
						add_meta_box('gwolle_gb_editor_postbox_content', esc_html__('Guestbook entry', 'gwolle-gb'), 'gwolle_gb_editor_postbox_content', 'gwolle_gb_editor', 'normal');
						add_meta_box('gwolle_gb_editor_postbox_website', esc_html__('Website', 'gwolle-gb'), 'gwolle_gb_editor_postbox_website', 'gwolle_gb_editor', 'normal');
						add_meta_box('gwolle_gb_editor_postbox_author', esc_html__('City', 'gwolle-gb'), 'gwolle_gb_editor_postbox_author_origin', 'gwolle_gb_editor', 'normal');
						add_meta_box('gwolle_gb_editor_postbox_admin_reply', esc_html__('Admin Reply', 'gwolle-gb'), 'gwolle_gb_editor_postbox_admin_reply', 'gwolle_gb_editor', 'normal');

						$active = is_plugin_active( 'gwolle-gb-addon/gwolle-gb-addon.php' ); // true or false
						$entry_id = $entry->get_id();
						if ( $active && function_exists( 'gwolle_gb_addon_editor_postbox_preview' ) && $entry_id > 0 ) {
							add_meta_box('gwolle_gb_editor_postbox_preview', esc_html__('Preview', 'gwolle-gb'), 'gwolle_gb_addon_editor_postbox_preview', 'gwolle_gb_editor', 'normal');
						}
						if ( $active && function_exists( 'gwolle_gb_addon_editor_metabox_meta' ) ) {
							add_meta_box('gwolle_gb_addon_editor_metabox_meta', esc_html__('The Add-On', 'gwolle-gb'), 'gwolle_gb_addon_editor_metabox_meta', 'gwolle_gb_editor', 'normal');
						}

						do_meta_boxes( 'gwolle_gb_editor', 'normal', $entry );
						?>
					</div>
					<div id="postbox-container-1" class="postbox-container">
						<?php
						add_meta_box('gwolle_gb_editor_postbox_icons', esc_html__('Visibility', 'gwolle-gb'), 'gwolle_gb_editor_postbox_icons', 'gwolle_gb_editor', 'side');
						add_meta_box('gwolle_gb_editor_postbox_actions', esc_html__('Actions', 'gwolle-gb'), 'gwolle_gb_editor_postbox_actions', 'gwolle_gb_editor', 'side');
						add_meta_box('gwolle_gb_editor_postbox_details', esc_html__('Details', 'gwolle-gb'), 'gwolle_gb_editor_postbox_details', 'gwolle_gb_editor', 'side');
						add_meta_box('gwolle_gb_editor_postbox_logs', esc_html__('Log', 'gwolle-gb'), 'gwolle_gb_editor_postbox_logs', 'gwolle_gb_editor', 'side');

						do_meta_boxes( 'gwolle_gb_editor', 'side', $entry );
						?>
					</div>
				</div>
			</div>
		</form>
	</div><!-- .wrap -->
	<?php
}


/*
 * Metabox with the content of the entry.
 */
function gwolle_gb_editor_postbox_content( $entry ) {
	?>
	<textarea rows="10" name="gwolle_gb_content" id="gwolle_gb_content" class="wp-exclude-emoji"><?php echo esc_textarea( gwolle_gb_sanitize_output( $entry->get_content(), 'content' ) ); ?></textarea>
	<?php
	if (get_option('gwolle_gb-showLineBreaks', 'false') === 'false') {
		$settingslink = '<a href="' . admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/settings.php' ) . '">';
		/* translators: %s is a link */
		echo '<p>' . sprintf( esc_html__('Line breaks will not be visible to the visitors due to your %ssettings%s.', 'gwolle-gb'), $settingslink, '</a>' ) . '</p>';
	}
	$form_setting = gwolle_gb_get_setting( 'form' );

	if ( isset($form_setting['form_bbcode_enabled']) && $form_setting['form_bbcode_enabled'] === 'true' ) {
		gwolle_gb_enqueue_markitup();

		// Emoji symbols
		echo '<div class="gwolle_gb_emoji gwolle-gb-hide">';
		$emoji = gwolle_gb_get_emoji();
		// make it into images for nice colors.
		if ( function_exists('wp_staticize_emoji') ) {
			$emoji = wp_staticize_emoji( $emoji );
		}
		echo $emoji;
		echo '</div>';
	}
}


/*
 * Metabox with the website of the entry.
 */
function gwolle_gb_editor_postbox_website( $entry ) {
	?>
	<input type="url" name="gwolle_gb_author_website" value="<?php echo esc_attr( gwolle_gb_sanitize_output( $entry->get_author_website() ) ); ?>" id="author_website" />
	<p><?php
		/* translators: %s is a code element */
		echo sprintf( esc_html__('Example: %shttps://www.example.com/%s', 'gwolle-gb'), '<code>', '</code>' ); ?>
	</p>
	<?php
}


/*
 * Metabox with the city/origin of the entry.
 */
function gwolle_gb_editor_postbox_author_origin( $entry ) {
	?>
	<input type="text" name="gwolle_gb_author_origin" class="wp-exclude-emoji" value="<?php echo esc_attr( gwolle_gb_sanitize_output( $entry->get_author_origin() ) ); ?>" id="author_origin" />
	<?php
}


/*
 * Metabox with the admin reply of the entry.
 */
function gwolle_gb_editor_postbox_admin_reply( $entry ) {
	$form_setting = gwolle_gb_get_setting( 'form' );
	?>

	<textarea rows="10" name="gwolle_gb_admin_reply" id="gwolle_gb_admin_reply" class="wp-exclude-emoji"><?php echo esc_textarea( gwolle_gb_sanitize_output( $entry->get_admin_reply(), 'admin_reply' ) ); ?></textarea>

	<?php
	if ( isset($form_setting['form_bbcode_enabled']) && $form_setting['form_bbcode_enabled'] === 'true' ) {
		echo '<div class="gwolle_gb_admin_reply_emoji gwolle-gb-hide">';
		// Emoji symbols
		$emoji = gwolle_gb_get_emoji();
		// make it into images for nice colors.
		if ( function_exists('wp_staticize_emoji') ) {
			$emoji = wp_staticize_emoji( $emoji );
		}
		echo $emoji;
		echo '</div>';
	}

	/* Admin Reply Author */
	$admin_reply_name = gwolle_gb_is_moderator( $entry->get_admin_reply_uid() );
	if ( $admin_reply_name ) { ?>
		<p class="gb-admin_reply_uid"><?php
			/* translators: %s is the name of the admin author */
			$admin_reply_header = '<em>' . sprintf( esc_html__('Admin Reply by: %s', 'gwolle-gb'), $admin_reply_name ) . '</em>';
			echo apply_filters( 'gwolle_gb_admin_reply_header', $admin_reply_header, $entry );
			?>
		</p><?php
	} ?>

	<p>
		<input type="checkbox" name="gwolle_gb_admin_reply_mail_author" id="gwolle_gb_admin_reply_mail_author">
		<label for="gwolle_gb_admin_reply_mail_author">
		<?php esc_html_e('Mail the author a notification about this reply.', 'gwolle-gb'); ?>
		</label>
	</p>

	<?php
	if (get_option('gwolle_gb-showLineBreaks', 'false') === 'false') {
		$settingslink = '<a href="' . admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/settings.php' ) . '">';
		/* translators: %s is a link */
		echo '<p>' . sprintf( esc_html__('Line breaks will not be visible to the visitors due to your %ssettings%s.', 'gwolle-gb'), $settingslink, '</a>' ) . '</p>';
	}
}


/*
 * Metabox with the icons and checkboxes for quick glancing at the visibility of the entry.
 */
function gwolle_gb_editor_postbox_icons( $entry ) {

	$class = gwolle_gb_editor_get_class( $entry );

	$postid = gwolle_gb_get_postid( (int) $entry->get_book_id() );
	if ( $postid ) {
		$permalink = gwolle_gb_get_permalink( $postid );
		?>
		<div id="gwolle_gb_frontend">
			<a class="button rbutton button" href="<?php echo $permalink; ?>"><?php esc_attr_e('View Guestbook', 'gwolle-gb'); ?></a>
		</div>
		<?php
	}

	// Optional Icon column where CSS is being used to show them or not
	if ( get_option('gwolle_gb-showEntryIcons', 'true') === 'true' ) { ?>
		<span class="entry-icons <?php echo $class; ?>">
			<span class="visible-icon" title="<?php esc_attr_e('Visible', 'gwolle-gb'); ?>"></span>
			<span class="invisible-icon" title="<?php esc_attr_e('Invisible', 'gwolle-gb'); ?>"></span>
			<span class="spam-icon" title="<?php esc_attr_e('Spam', 'gwolle-gb'); ?>"></span>
			<span class="trash-icon" title="<?php /* translators: Is in Trashcan */ esc_attr_e('In Trash', 'gwolle-gb'); ?>"></span>
			<?php
			$admin_reply = gwolle_gb_sanitize_output( $entry->get_admin_reply(), 'admin_reply' );
			if ( strlen( trim($admin_reply) ) > 0 ) { ?>
				<span class="admin_reply-icon" title="<?php esc_attr_e('Admin Replied', 'gwolle-gb'); ?>"></span><?php
			} ?>
			<span class="gwolle_gb_ajax" title="<?php esc_attr_e('Wait...', 'gwolle-gb'); ?>"></span>
		</span>
		<?php
	}

	if ( $entry->get_id() === 0 ) {
		echo '<h3 class="h3-invisible">' . esc_html__('This entry is not yet visible.', 'gwolle-gb') . '</h3>';
	} else {
		if ($entry->get_ischecked() === 1 && $entry->get_isspam() === 0 && $entry->get_istrash() === 0 ) {
			echo '
				<h3 class="h3-visible">' . esc_html__('This entry is Visible.', 'gwolle-gb') . '</h3>
				<h3 class="h3-invisible" style="display:none;">' . esc_html__('This entry is Not Visible.', 'gwolle-gb') . '</h3>
				';
		} else {
			echo '
				<h3 class="h3-visible" style="display:none;">' . esc_html__('This entry is Visible.', 'gwolle-gb') . '</h3>
				<h3 class="h3-invisible">' . esc_html__('This entry is Not Visible.', 'gwolle-gb') . '</h3>
				';
		} ?>

		<label for="ischecked" class="selectit">
			<input id="ischecked" name="ischecked" type="checkbox" <?php
				if ($entry->get_ischecked() === 1 || $entry->get_id() === 0) {
					echo 'checked="checked"';
				}
				?> />
			<?php esc_html_e('Checked', 'gwolle-gb'); ?>
		</label>

		<br />
		<label for="isspam" class="selectit">
			<input id="isspam" name="isspam" type="checkbox" <?php
				if ($entry->get_isspam() === 1) {
					echo 'checked="checked"';
				}
				?> />
			<?php esc_html_e('Spam', 'gwolle-gb'); ?>
		</label>

		<br />
		<label for="istrash" class="selectit">
			<input id="istrash" name="istrash" type="checkbox" <?php
				if ($entry->get_istrash() === 1) {
					echo 'checked="checked"';
				}
				?> />
			<?php /* translators: Is in Trashcan */ esc_html_e('In Trash', 'gwolle-gb'); ?>
		</label>

		<?php
		$trashclass = '';
		if ( $entry->get_istrash() === 0 ) {
			$trashclass = 'gwolle-gb-hide';
		} ?>
		<br />
		<label for="remove" class="selectit gwolle_gb_remove <?php echo $trashclass; ?>">
			<input id="remove" name="remove" type="checkbox" />
			<?php esc_html_e('Remove this entry Permanently.', 'gwolle-gb'); ?>
		</label>
		<?php
	} ?>

	<div id="publishing-action">
		<input name="save" type="submit" class="button-primary" id="publish" value="<?php esc_attr_e('Save', 'gwolle-gb'); ?>" />
	</div> <!-- .publishing-action -->
	<div class="clear"></div>
	<?php
}


/*
 * Metabox with quick actions for the entry (AJAX).
 */
function gwolle_gb_editor_postbox_actions( $entry ) {

	$class = gwolle_gb_editor_get_class( $entry );

	if ( $entry->get_id() > 0 ) {
		echo '
		<p class="gwolle_gb_actions ' . $class . '">
			<span class="gwolle_gb_check">
				<a id="check_' . $entry->get_id() . '" href="#" class="vim-a" title="' . esc_attr__('Check entry', 'gwolle-gb') . '">' . esc_html__('Check', 'gwolle-gb') . '</a>
			</span>
			<span class="gwolle_gb_uncheck">
				<a id="uncheck_' . $entry->get_id() . '" href="#" class="vim-u" title="' . esc_attr__('Uncheck entry', 'gwolle-gb') . '">' . esc_html__('Uncheck', 'gwolle-gb') . '</a>
			</span>
			<span class="gwolle_gb_spam">&nbsp;|&nbsp;
				<a id="spam_' . $entry->get_id() . '" href="#" class="vim-s vim-destructive" title="' . esc_attr__('Mark entry as spam.', 'gwolle-gb') . '">' . esc_html__('Spam', 'gwolle-gb') . '</a>
			</span>
			<span class="gwolle_gb_unspam">&nbsp;|&nbsp;
				<a id="unspam_' . $entry->get_id() . '" href="#" class="vim-a" title="' . esc_attr__('Mark entry as not-spam.', 'gwolle-gb') . '">' . esc_html__('Not spam', 'gwolle-gb') . '</a>
			</span>
			<span class="gwolle_gb_trash">&nbsp;|&nbsp;
				<a id="trash_' . $entry->get_id() . '" href="#" class="vim-d vim-destructive" title="' . esc_attr__('Move entry to trash.', 'gwolle-gb') . '">' . /* translators: Move to Trashcan */ esc_html__('Trash', 'gwolle-gb') . '</a>
			</span>
			<span class="gwolle_gb_untrash">&nbsp;|&nbsp;
				<a id="untrash_' . $entry->get_id() . '" href="#" class="vim-d" title="' . esc_attr__('Recover entry from trash.', 'gwolle-gb') . '">' . esc_html__('Untrash', 'gwolle-gb') . '</a>
			</span><br />
			<span class="gwolle_gb_ajax">
				<a id="ajax_' . $entry->get_id() . '" href="#" class="ajax vim-d vim-destructive" title="' . esc_attr__('Please wait...', 'gwolle-gb') . '">' . esc_html__('Wait...', 'gwolle-gb') . '</a>
			</span><br />
		</p>
		';
	}
}


/*
 * Metabox with the small details of the entry.
 */
function gwolle_gb_editor_postbox_details( $entry ) {
	?>
	<p>
		<?php esc_html_e('Author', 'gwolle-gb'); ?>: <span class="gb-editor-author-name"><?php
			if ( $entry->get_author_name() ) {
				echo gwolle_gb_sanitize_output( $entry->get_author_name() );
			} else {
				echo '<i>(' . esc_html__('Unknown', 'gwolle-gb') . ')</i>';
			} ?>
		</span><br />
		<?php esc_html_e('Email', 'gwolle-gb'); ?>: <span><?php
			if (strlen(str_replace( ' ', '', $entry->get_author_email() )) > 0) {
				echo gwolle_gb_sanitize_output( $entry->get_author_email() );
			} else {
				echo '<i>(' . esc_html__('Unknown', 'gwolle-gb') . ')</i>';
			} ?>
		</span><br />
		<?php esc_html_e('Date and time', 'gwolle-gb'); ?>: <span class="gb-editor-datetime"><?php
			if ( $entry->get_datetime() > 0 ) {
				echo date_i18n( get_option('date_format'), $entry->get_datetime() ) . ', ';
				echo date_i18n( get_option('time_format'), $entry->get_datetime() );
			} else {
				echo '(' . esc_html__('Not yet', 'gwolle-gb') . ')';
			} ?>
		</span><br />
		<?php esc_html_e('Logged in', 'gwolle-gb'); ?>: <span><?php
			if ( (int) $entry->get_author_id() > 0 ) {
				esc_html_e('Yes', 'gwolle-gb');
			} else {
				esc_html_e('No', 'gwolle-gb');
			} ?>
		</span><br />
		<?php esc_html_e("Author's IP-address", 'gwolle-gb'); ?>: <span><?php
			if (strlen( $entry->get_author_ip() ) > 0) {
				echo '<a href="https://www.db.ripe.net/whois?form_type=simple&searchtext=' . $entry->get_author_ip() . '"
						title="' . esc_attr__('Whois search for this IP', 'gwolle-gb') . '" target="_blank">
							' . $entry->get_author_ip() . '
						</a>';
			} else {
				echo '<i>(' . esc_html__('Unknown', 'gwolle-gb') . ')</i>';
			} ?>
		</span><br />
		<?php esc_html_e('Host', 'gwolle-gb'); ?>: <span><?php
			if (strlen( $entry->get_author_host() ) > 0) {
				echo $entry->get_author_host();
			} else {
				echo '<i>(' . esc_html__('Unknown', 'gwolle-gb') . ')</i>';
			} ?>
		</span><br />
		<?php esc_html_e('Book', 'gwolle-gb'); ?>: <span class="gb-editor-book-id"><?php echo $entry->get_book_id(); ?>
		</span><br />
		<span class="gwolle_gb_edit_meta">
			<a href="#" title="<?php esc_attr_e('Edit metadata', 'gwolle-gb'); ?>"><?php esc_html_e('Edit', 'gwolle-gb'); ?></a>
		</span>
	</p>

	<div class="gwolle_gb_editor_meta_inputs">
		<?php gwolle_gb_editor_meta_inputs( $entry ); ?>
	</div>

	<?php
}


/*
 * Taken from wp-admin/includes/template.php touch_time()
 * Adapted for simplicity.
 *
 * @param object $entry instance of the class gb_entry
 */
function gwolle_gb_editor_meta_inputs( $entry ) {
	global $wp_locale;
	?>
	<label for="gwolle_gb_author_name"><?php esc_html_e('Author', 'gwolle-gb'); ?></label><br />
	<input type="text" name="gwolle_gb_author_name" size="24" value="<?php echo esc_attr( gwolle_gb_sanitize_output( $entry->get_author_name() ) ); ?>" id="gwolle_gb_author_name" class="wp-exclude-emoji" /><br />

	<label for="gwolle_gb_author_id"><?php esc_html_e('Author ID', 'gwolle-gb');
	// Get user ID from email address.
	$user = new WP_User();
	$user_object = $user->get_data_by( 'email', gwolle_gb_sanitize_output( $entry->get_author_email() ) );
	if ( is_object( $user_object ) && isset( $user_object->ID ) ) {
		/* translators: %s is the user ID that is suggested. */
		echo ' ' . sprintf( esc_html__('(suggested %s)', 'gwolle-gb'), $user_object->ID );
	}
	?></label><br />
	<input type="text" name="gwolle_gb_author_id" size="6" value="<?php echo (int) ( $entry->get_author_id() ); ?>" id="gwolle_gb_author_id" class="wp-exclude-emoji" /><br />

	<span><?php esc_html_e('Date and time', 'gwolle-gb'); ?> </span><br />
	<div class="gwolle_gb_date"><?php

		$date = $entry->get_datetime();
		if ( ! $date ) {
			$date = current_time( 'timestamp' );
		}

		$dd = date_i18n( 'd', $date );
		$mm = date_i18n( 'm', $date );
		$yy = date_i18n( 'Y', $date );
		$hh = date_i18n( 'H', $date );
		$mn = date_i18n( 'i', $date );

		// Day
		echo '<label><span class="screen-reader-text">' . esc_html__( 'Day', 'gwolle-gb' ) . '</span><input type="text" id="dd" name="dd" value="' . esc_attr( $dd ) . '" size="2" maxlength="2" autocomplete="off" /></label>';

		// Month
		echo '<label for="mm"><span class="screen-reader-text">' . esc_html__( 'Month', 'gwolle-gb' ) . '</span>
				<select id="mm" name="mm">';
		for ( $i = 1; $i < 13; $i++ ) {
			$monthnum = zeroise($i, 2);
			echo '
					<option value="' . esc_attr( $monthnum ) . '" ' . selected( $monthnum, $mm, false ) . '>';
			/* translators: 1: month number (01, 02, etc.), 2: month abbreviation */
			echo sprintf( esc_html__( '%1$s-%2$s', 'gwolle-gb' ), $monthnum, $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) ) . '</option>';
		}
		echo '
				</select></label>';

		// Year
		echo '<label for="yy"><span class="screen-reader-text">' . esc_html__( 'Year', 'gwolle-gb' ) . '</span><input type="text" id="yy" name="yy" value="' . esc_attr( $yy ) . '" size="4" maxlength="4" autocomplete="off" /></label>';
		echo '<br />';
		// Hour
		echo '<label for="hh"><span class="screen-reader-text">' . esc_html__( 'Hour', 'gwolle-gb' ) . '</span><input type="text" id="hh" name="hh" value="' . esc_attr( $hh ) . '" size="2" maxlength="2" autocomplete="off" /></label>:';
		// Minute
		echo '<label for="mn"><span class="screen-reader-text">' . esc_html__( 'Minute', 'gwolle-gb' ) . '</span><input type="text" id="mn" name="mn" value="' . esc_attr( $mn ) . '" size="2" maxlength="2" autocomplete="off" /></label>';
		?>

		<div class="gwolle_gb_timestamp">
			<!-- Clicking OK will place a local timestamp here. -->
			<input type="hidden" id="gwolle_gb_timestamp" name="gwolle_gb_timestamp" value="" />
		</div>
	</div>

	<label for="gwolle_gb_book_id"><?php esc_html_e('Book ID', 'gwolle-gb'); ?></label><br />
	<input type="text" name="gwolle_gb_book_id" size="4" value="<?php echo (int) $entry->get_book_id(); ?>" id="gwolle_gb_book_id" />

	<p>
		<a href="#" class="gwolle_gb_save_timestamp hide-if-no-js button" title="<?php esc_attr_e('Save the date and time', 'gwolle-gb'); ?>">
			<?php esc_html_e('Save', 'gwolle-gb'); ?>
		</a>
		<a href="#" class="gwolle_gb_cancel_timestamp hide-if-no-js button-cancel" title="<?php esc_attr_e('Cancel saving date and time', 'gwolle-gb'); ?>">
			<?php esc_html_e('Cancel', 'gwolle-gb'); ?>
		</a>
	</p>

	<?php
}


/*
 * Metabox with the log of the entry.
 */
function gwolle_gb_editor_postbox_logs( $entry ) {
	?>
	<ul>
		<?php
		if ($entry->get_datetime() > 0) {
			echo '<li>';
			echo date_i18n( get_option('date_format'), $entry->get_datetime() ) . ', ';
			echo date_i18n( get_option('time_format'), $entry->get_datetime() );
			/* translators: In log on Editor page */
			echo ': ' . esc_html__('Written', 'gwolle-gb') . '</li>';

			$log_entries = gwolle_gb_get_log_entries( $entry->get_id() );
			if ( is_array($log_entries) && ! empty($log_entries) ) {
				foreach ($log_entries as $log_entry) {
					echo '<li class="log_id_' . $log_entry['id'] . '">' . $log_entry['msg_html'] . '</li>';
				}
			}
		} else {
			echo '<li>(' . esc_html__('No log yet.', 'gwolle-gb') . ')</li>';
		}
		?>
	</ul>
	<?php
}


/*
 * Update admin page with the entry editor. Used for new and existing entries.
 *
 * @since 3.0.0
 */
function gwolle_gb_page_editor_update( $entry ) {

	if ( ! current_user_can('moderate_comments') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	}

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['gwolle_gb_wpnonce']) ) {
		$verified = wp_verify_nonce( $_POST['gwolle_gb_wpnonce'], 'gwolle_gb_page_editor' );
		if ( $verified === false ) {
			// Nonce is invalid, so considered spam
			gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
			return $entry;
		}
	}
	if ( $verified === false ) {
		// Nonce is invalid.
		gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
		return $entry;
	}

	if ( ! isset($_POST['entry_id']) || (int) $_POST['entry_id'] !== $entry->get_id() ) {
		gwolle_gb_add_message( '<p>' . esc_html__('Something strange happened.', 'gwolle-gb') . '</p>', true, false);
		return $entry;
	} else if ( $_POST['entry_id'] > 0 && $entry->get_id() > 0 ) {

		/* Remove permanently and return early. */
		if ( isset($_POST['istrash']) && $_POST['istrash'] === 'on' && isset($_POST['remove']) && $_POST['remove'] === 'on' ) {
			if ( $entry->get_istrash() === 1 ) {
				$entry->delete();
				$entry->set_id(0);
				$changed = true;
				gwolle_gb_add_message( '<p>' . esc_html__('Entry removed.', 'gwolle-gb') . '</p>', false, false);
				$entry = new gwolle_gb_entry();
				return $entry;
			}
		}

		/* Check if it was visible or not. We need to check this, because multiple changes are possible and we don't want multiple emails. */
		$was_visible = false;
		if ( $entry->get_ischecked() === 1 && $entry->get_isspam() === 0 && $entry->get_istrash() === 0 ) {
			$was_visible = true;
		}

		/* Set as checked or unchecked, and by whom */
		if ( isset($_POST['ischecked']) && $_POST['ischecked'] === 'on' ) {
			if ( $_POST['ischecked'] === 'on' && $entry->get_ischecked() === 0 ) {
				$entry->set_ischecked( true );
				$user_id = get_current_user_id(); // returns 0 if no current user
				$entry->set_checkedby( $user_id );
				gwolle_gb_add_log_entry( $entry->get_id(), 'entry-checked' );
				gwolle_gb_clear_cache( $entry );
			}
		} else if ( $entry->get_ischecked() === 1 ) {
			$entry->set_ischecked( false );
			gwolle_gb_add_log_entry( $entry->get_id(), 'entry-unchecked' );
		}

		/* Set as spam or not, and submit as ham or spam to Akismet service */
		if ( isset($_POST['isspam']) && $_POST['isspam'] === 'on' ) {
			if ( $_POST['isspam'] === 'on' && $entry->get_isspam() === 0 ) {
				$entry->set_isspam( true );
				$result = gwolle_gb_akismet( $entry, 'submit-spam' );
				if ( $result ) {
					gwolle_gb_add_message( '<p>' . esc_html__('Submitted as Spam to the Akismet service.', 'gwolle-gb') . '</p>', false, false);
				}
				gwolle_gb_add_log_entry( $entry->get_id(), 'marked-as-spam' );
			}
		} else if ( $entry->get_isspam() === 1 ) {
			$entry->set_isspam( false );
			$result = gwolle_gb_akismet( $entry, 'submit-ham' );
			if ( $result ) {
				gwolle_gb_add_message( '<p>' . esc_html__('Submitted as Ham to the Akismet service.', 'gwolle-gb') . '</p>', false, false);
			}
			gwolle_gb_add_log_entry( $entry->get_id(), 'marked-as-not-spam' );
		}

		/* Set as trash or not */
		if ( isset($_POST['istrash']) && $_POST['istrash'] === 'on' ) {
			if ( $_POST['istrash'] === 'on' && $entry->get_istrash() === 0 ) {
				$entry->set_istrash( true );
				gwolle_gb_add_log_entry( $entry->get_id(), 'entry-trashed' );
			}
		} else if ( $entry->get_istrash() === 1 ) {
			$entry->set_istrash( false );
			gwolle_gb_add_log_entry( $entry->get_id(), 'entry-untrashed' );
		}

		/* Check if the content changed, and update accordingly */
		if ( isset($_POST['gwolle_gb_content']) && $_POST['gwolle_gb_content'] != '' ) {
			if ( trim($_POST['gwolle_gb_content']) != $entry->get_content() ) {
				$entry_content = gwolle_gb_maybe_encode_emoji( $_POST['gwolle_gb_content'], 'content' );
				$entry->set_content( $entry_content );
			}
		}

		/* Check if the website changed, and update accordingly */
		if ( isset( $_POST['gwolle_gb_author_website'] ) ) {
			$website = trim( $_POST['gwolle_gb_author_website'] );
		} else {
			$website = '';
		}
		if ( $website !== $entry->get_author_website() ) {
			$entry->set_author_website( $website );
		}

		/* Check if the author_origin changed, and update accordingly */
		if ( isset($_POST['gwolle_gb_author_origin']) ) {
			if ( $_POST['gwolle_gb_author_origin'] != $entry->get_author_origin() ) {
				$entry_origin = gwolle_gb_maybe_encode_emoji( $_POST['gwolle_gb_author_origin'], 'author_origin' );
				$entry->set_author_origin( $entry_origin );
			}
		}

		/* Check if the admin_reply changed, and update and log accordingly */
		if ( isset($_POST['gwolle_gb_admin_reply']) ) {
			if ( trim($_POST['gwolle_gb_admin_reply']) !== $entry->get_admin_reply() ) {
				$gwolle_gb_admin_reply = gwolle_gb_maybe_encode_emoji( $_POST['gwolle_gb_admin_reply'], 'admin_reply' );
				if ( $gwolle_gb_admin_reply != '' && $entry->get_admin_reply() == '' ) {
					$entry->set_admin_reply_uid( get_current_user_id() );
					gwolle_gb_add_log_entry( $entry->get_id(), 'admin-reply-added' );
				} else if ( $gwolle_gb_admin_reply == '' && $entry->get_admin_reply() != '' ) {
					$entry->set_admin_reply_uid( 0 );
					gwolle_gb_add_log_entry( $entry->get_id(), 'admin-reply-removed' );
				} else if ( $gwolle_gb_admin_reply != '' && $entry->get_admin_reply() != '' ) {
					gwolle_gb_add_log_entry( $entry->get_id(), 'admin-reply-updated' );
				}
				$entry->set_admin_reply( $gwolle_gb_admin_reply );
			}
		}

		/* Mail the author about the Admin Reply, if so requested */
		if ( isset($_POST['gwolle_gb_admin_reply_mail_author']) ) {
			if ( $_POST['gwolle_gb_admin_reply_mail_author'] === 'on' ) {
				gwolle_gb_mail_author_on_admin_reply( $entry );
			}
		}

		/* Check if the author_name changed, and update accordingly */
		if ( isset($_POST['gwolle_gb_author_name']) ) {
			if ( $_POST['gwolle_gb_author_name'] != $entry->get_author_name() ) {
				$entry_name = gwolle_gb_maybe_encode_emoji( $_POST['gwolle_gb_author_name'], 'author_name' );
				$entry->set_author_name( $entry_name );
			}
		}

		/* Check if the author_id changed, and update accordingly */
		if ( isset($_POST['gwolle_gb_author_id']) ) {
			if ( $_POST['gwolle_gb_author_id'] != $entry->get_author_id() ) {
				$entry_author_id = (int) $_POST['gwolle_gb_author_id'];
				$entry->set_author_id( $entry_author_id );
			}
		}

		/* Check if the datetime changed, and update from all input. */
		if ( isset($_POST['gwolle_gb_timestamp']) && is_numeric($_POST['gwolle_gb_timestamp']) ) {
			$timestamp = (int) $_POST['gwolle_gb_timestamp'];
			$entry->set_datetime( $timestamp );
		}

		/* Check if the book_id changed, and update accordingly */
		if ( isset($_POST['gwolle_gb_book_id']) && is_numeric($_POST['gwolle_gb_book_id']) ) {
			if ( $_POST['gwolle_gb_book_id'] != $entry->get_book_id() ) {
				$entry->set_book_id( (int) $_POST['gwolle_gb_book_id'] );
			}
		}

		/* Save the entry */
		$result = $entry->save();
		if ($result ) {
			gwolle_gb_add_log_entry( $entry->get_id(), 'entry-edited' );
			gwolle_gb_add_message( '<p>' . esc_html__('Changes saved.', 'gwolle-gb') . '</p>', false, false);
			if ( $was_visible === false && $entry->get_ischecked() === 1 && $entry->get_isspam() === 0 && $entry->get_istrash() === 0 ) {
				gwolle_gb_mail_author_on_moderation( $entry );
			}
			do_action( 'gwolle_gb_save_entry_admin', $entry );
		} else {
			gwolle_gb_add_message( '<p>' . esc_html__('Error happened during saving.', 'gwolle-gb') . '</p>', true, false);
		}

	} else if ( (int) $_POST['entry_id'] === 0 && $entry->get_id() === 0 ) {

		/*
		 * Check for input, and save accordingly. This is on a New Entry! (So no logging)
		 */

		$data = array();

		/* Set as checked anyway, new entry is always by an admin */
		$data['ischecked'] = true;
		$user_id           = get_current_user_id(); // returns 0 if no current user
		$data['checkedby'] = $user_id;
		$data['author_id'] = $user_id;

		/* Set metadata of the admin */
		$userdata = get_userdata( $user_id );

		if (is_object($userdata)) {
			if ( isset( $userdata->display_name ) ) {
				$author_name = $userdata->display_name;
			} else {
				$author_name = $userdata->user_login;
			}
			$author_email = $userdata->user_email;
		}
		$data['author_name'] = $author_name;
		$data['author_name'] = gwolle_gb_maybe_encode_emoji( $data['author_name'], 'author_name' );
		$data['author_email'] = $author_email;

		/* Set as Not Spam */
		$data['isspam'] = false;

		/* Do not set as trash */
		$data['istrash'] = false;

		/* Check if the content is filled in, and save accordingly */
		if ( isset($_POST['gwolle_gb_content']) && $_POST['gwolle_gb_content'] != '' ) {
			$data['content'] = $_POST['gwolle_gb_content'];
			$data['content'] = gwolle_gb_maybe_encode_emoji( $data['content'], 'content' );
		} else {
			$form_setting = gwolle_gb_get_setting( 'form' );
			if ( isset($form_setting['form_message_enabled']) && $form_setting['form_message_enabled'] === 'true' && isset($form_setting['form_message_mandatory']) && $form_setting['form_message_mandatory'] === 'true' ) {
				gwolle_gb_add_message( '<p>' . esc_html__('Entry has no content, even though that is mandatory.', 'gwolle-gb') . '</p>', true, false);
			} else {
				$data['content'] = '';
			}
		}

		/* Check if the website is set, and save accordingly */
		if ( isset($_POST['gwolle_gb_author_website']) ) {
			if ( $_POST['gwolle_gb_author_website'] != '' ) {
				$data['author_website'] = $_POST['gwolle_gb_author_website'];
			} else {
				$data['author_website'] = home_url();
			}
		}

		/* Check if the author_origin is set, and save accordingly */
		if ( isset($_POST['gwolle_gb_author_origin']) ) {
			if ( $_POST['gwolle_gb_author_origin'] != '' ) {
				$data['author_origin'] = $_POST['gwolle_gb_author_origin'];
				$data['author_origin'] = gwolle_gb_maybe_encode_emoji( $data['author_origin'], 'author_origin' );
			}
		}

		/* Check if the admin_reply is set, and save accordingly */
		if ( isset($_POST['gwolle_gb_admin_reply']) ) {
			if ( $_POST['gwolle_gb_admin_reply'] != '' ) {
				$data['admin_reply'] = gwolle_gb_maybe_encode_emoji( $_POST['gwolle_gb_admin_reply'], 'admin_reply' );
				$data['admin_reply_uid'] = get_current_user_id();
				gwolle_gb_add_log_entry( $entry->get_id(), 'admin-reply-added' );
			}
		}

		/* Check if the book_id is set, and save accordingly */
		if ( isset($_POST['gwolle_gb_book_id']) && is_numeric($_POST['gwolle_gb_book_id']) ) {
			$entry->set_book_id( (int) $_POST['gwolle_gb_book_id'] );
		}

		/* Network Information */
		$set_author_ip = apply_filters( 'gwolle_gb_set_author_ip', true );
		$set_author_ip2 = get_option('gwolle_gb-store_ip', 'true');
		if ( $set_author_ip && ( $set_author_ip2 === 'true' ) ) {
			$entry->set_author_ip( gwolle_gb_get_user_ip() );
			$entry->set_author_host( gethostbyaddr( gwolle_gb_get_user_ip() ) );
		}

		$result1 = $entry->set_data( $data );
		if ( $result1 ) {
			$result2 = $entry->save();
			if ( $result1 && $result2 ) {
				gwolle_gb_add_message( '<p>' . esc_html__('Entry saved.', 'gwolle-gb') . '</p>', false, false);
				gwolle_gb_clear_cache( $entry );
				do_action( 'gwolle_gb_save_entry_admin', $entry );
			} else {
				gwolle_gb_add_message( '<p>' . esc_html__('Error happened during saving.', 'gwolle-gb') . '</p>', true, false);
			}
		} else {
			gwolle_gb_add_message( '<p>' . esc_html__('Entry was not saved.', 'gwolle-gb') . '</p>', true, false);
		}
	}
	return $entry;
}


/*
 * Get editor class for this entry.
 *
 * @param  object $entry instance of gwolle_gb_entry class.
 * @return string text string with CSS classes.
 *
 * @since 3.0.0
 */
function gwolle_gb_editor_get_class( $entry ) {

	static $class_static;

	if ( $class_static ) {
		return $class_static;
	}

	$class = '';
	// Attach 'spam' to class if the entry is spam
	if ( $entry->get_isspam() === 1 ) {
		$class .= 'spam';
	} else {
		$class .= 'nospam';
	}

	// Attach 'trash' to class if the entry is in trash
	if ( $entry->get_istrash() === 1 ) {
		$class .= ' trash';
	} else {
		$class .= ' notrash';
	}

	// Attach 'checked/unchecked' to class
	if ( $entry->get_ischecked() === 1 ) {
		$class .= ' checked';
	} else {
		$class .= ' unchecked';
	}

	// Attach 'visible/invisible' to class
	if ( $entry->get_isspam() === 1 || $entry->get_istrash() === 1 || $entry->get_ischecked() === 0 ) {
		$class .= ' invisible';
	} else {
		$class .= ' visible';
	}

	// Add admin-entry class to an entry from an admin
	$author_id = $entry->get_author_id();
	$is_moderator = gwolle_gb_is_moderator( $author_id );
	if ( $is_moderator ) {
		$class .= ' admin-entry';
	}

	$class_static = $class;
	return $class_static;

}
