<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Adds a dashboard widget to show the latest entries.
 */
function gwolle_gb_dashboard() {

	if ( ! current_user_can('moderate_comments') ) {
		return;
	}

	gwolle_gb_admin_enqueue();

	// Only get new and unchecked entries
	$entries = gwolle_gb_get_entries(array(
			'num_entries' => 5,
			'checked' => 'unchecked',
			'trash'   => 'notrash',
			'spam'    => 'nospam',
		));

	if ( is_array($entries) && ! empty($entries) ) {

		// List of guestbook entries
		echo '<div class="gwolle-gb-dashboard gwolle-gb">';
		$rowodd = false;
		foreach ( $entries as $entry ) {
			$class = '';
			// rows have a different color.
			if ($rowodd) {
				$rowodd = false;
				$class = ' alternate';
			} else {
				$rowodd = true;
				$class = '';
			}

			// Attach 'spam' to class if the entry is spam
			if ( $entry->get_isspam() === 1 ) {
				$class .= ' spam';
			} else {
				$class .= ' nospam';
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
			} ?>


			<div id="entry_<?php echo (int) $entry->get_id(); ?>" class="comment depth-1 comment-item <?php echo esc_attr( $class ); ?>">
				<div class="dashboard-comment-wrap">
					<h4 class="comment-meta">
						<?php // Author info ?>
						<cite class="comment-author"><?php echo gwolle_gb_get_author_name_html($entry); ?></cite>
					</h4>

					<?php
					// Date column
					echo '
						<div class="date">' . date_i18n( get_option('date_format'), $entry->get_datetime() ) . ', ' .
							date_i18n( get_option('time_format'), $entry->get_datetime() ) .
						'</div>'; ?>

					<blockquote class="excerpt">
						<p>
						<?php
						// Content / Excerpt
						$entry_content = gwolle_gb_get_excerpt( gwolle_gb_bbcode_strip($entry->get_content()), 16 );
						if ( get_option('gwolle_gb-showSmilies', 'true') === 'true' ) {
							$entry_content = convert_smilies($entry_content);
						}
						echo $entry_content; ?>
						</p>
					</blockquote><?php

					// Actions with AJAX
					?>
					<p class="row-actions" id="entry-actions-<?php echo $entry->get_id(); ?>">
						<span class="gwolle_gb_edit">
							<a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/editor.php&entry_id=' . $entry->get_id() ); ?>" title="<?php esc_attr_e('Edit entry', 'gwolle-gb'); ?>"><?php esc_html_e('Edit', 'gwolle-gb'); ?></a>
						</span>
						<span class="gwolle_gb_check">
							&nbsp;|&nbsp;
							<a id="check_<?php echo (int) $entry->get_id(); ?>" href="#" class="vim-a" title="<?php esc_attr_e('Check entry', 'gwolle-gb'); ?>"><?php esc_html_e('Check', 'gwolle-gb'); ?></a>
						</span>
						<span class="gwolle_gb_uncheck">
							&nbsp;|&nbsp;
							<a id="uncheck_<?php echo (int) $entry->get_id(); ?>" href="#" class="vim-u" title="<?php esc_attr_e('Uncheck entry', 'gwolle-gb'); ?>"><?php esc_html_e('Uncheck', 'gwolle-gb'); ?></a>
						</span>
						<span class="gwolle_gb_spam">
							&nbsp;|&nbsp;
							<a id="spam_<?php echo (int) $entry->get_id(); ?>" href="#" class="vim-s vim-destructive" title="<?php esc_attr_e('Mark entry as spam.', 'gwolle-gb'); ?>"><?php esc_html_e('Spam', 'gwolle-gb'); ?></a>
						</span>
						<span class="gwolle_gb_unspam">
							&nbsp;|&nbsp;
							<a id="unspam_<?php echo (int) $entry->get_id(); ?>" href="#" class="vim-a" title="<?php esc_attr_e('Mark entry as not-spam.', 'gwolle-gb'); ?>"><?php esc_html_e('Not spam', 'gwolle-gb'); ?></a>
						</span>
						<span class="gwolle_gb_trash">
							&nbsp;|&nbsp;
							<a id="trash_<?php echo (int) $entry->get_id(); ?>" href="#" class="vim-d vim-destructive" title="<?php esc_attr_e('Move entry to trash.', 'gwolle-gb'); ?>"><?php /* translators: Move to Trashcan */ esc_html_e('Trash', 'gwolle-gb'); ?></a>
						</span>
						<span class="gwolle_gb_untrash">
							&nbsp;|&nbsp;
							<a id="untrash_<?php echo (int) $entry->get_id(); ?>" href="#" class="vim-d" title="<?php esc_attr_e('Recover entry from trash.', 'gwolle-gb'); ?>"><?php esc_html_e('Untrash', 'gwolle-gb'); ?></a>
						</span>
						<span class="gwolle_gb_ajax">
							&nbsp;|&nbsp;
							<a id="ajax_<?php echo (int) $entry->get_id(); ?>" href="#" class="ajax vim-d vim-destructive" title="<?php esc_attr_e('Please wait...', 'gwolle-gb'); ?>"><?php esc_html_e('Wait...', 'gwolle-gb'); ?></a>
						</span>
					</p>
				</div>
			</div>
			<?php

		} ?>

		</div>
		<p class="textright">
			<a href="<?php echo admin_url( 'index.php' ); ?>" class="button"><?php esc_html_e('Refresh', 'gwolle-gb'); ?></a>
			<a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&amp;show=all' ); ?>" class="button button-primary"><?php esc_html_e('View all', 'gwolle-gb'); ?></a>
			<a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&amp;show=unchecked' ); ?>" class="button button-primary"><?php esc_html_e('View new', 'gwolle-gb'); ?></a>
		</p><?php
	} else {
		echo '<p>' . esc_html__('No new and unchecked guestbook entries.', 'gwolle-gb') . '</p>';
	}
}


/*
 * The hook to add a dashboard widget to show the latest entries.
 */
function gwolle_gb_dashboard_setup() {

	if ( ! current_user_can('moderate_comments') ) {
		return;
	}

	wp_add_dashboard_widget('gwolle_gb_dashboard', esc_html__('Guestbook (new entries)', 'gwolle-gb'), 'gwolle_gb_dashboard');
}
add_action('wp_dashboard_setup', 'gwolle_gb_dashboard_setup');
