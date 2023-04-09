<?php
/*
 * Lets the user import guestbook entries from other plugins.
 * Currently supported:
 * - DMSGuestbook (https://wordpress.org/plugins/dmsguestbook/).
 * - WordPress comments from a page, post or just all.
 * - Gwolle-GB through a CSV-file.
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Admin page for import.
 * Contains metaboxes with forms.
 */
function gwolle_gb_page_import() {

	gwolle_gb_admin_enqueue();

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	}

	if ( isset( $_POST['gwolle_gb_page']) && $_POST['gwolle_gb_page'] === 'gwolle_gb_import' ) {
		gwolle_gb_page_import_post();
	}

	?>
	<div class="wrap gwolle_gb">
		<div id="icon-gwolle-gb"><br /></div>
		<h1><?php esc_html_e('Import guestbook entries.', 'gwolle-gb'); ?> (Gwolle Guestbook) - v<?php echo GWOLLE_GB_VER; ?></h1>

		<?php
		$gwolle_gb_messages = gwolle_gb_get_messages();
		$gwolle_gb_errors   = gwolle_gb_get_errors();
		$messageclass = '';
		if ( $gwolle_gb_errors ) {
			$messageclass = 'error';
		}

		if ( $gwolle_gb_messages ) {
			echo '
				<div id="message" class="updated fade notice is-dismissible ' . $messageclass . ' ">' .
					$gwolle_gb_messages .
				'</div>';
		} ?>

		<div id="poststuff" class="gwolle_gb_import metabox-holder">
			<div class="postbox-container">
				<?php
				add_meta_box( 'gwolle_gb_import_postbox_dms', esc_html__('Import guestbook entries from DMSGuestbook', 'gwolle-gb'), 'gwolle_gb_import_postbox_dms', 'gwolle_gb_import', 'normal' );
				add_meta_box( 'gwolle_gb_import_postbox_wp', esc_html__('Import guestbook entries from WordPress comments', 'gwolle-gb'), 'gwolle_gb_import_postbox_wp', 'gwolle_gb_import', 'normal' );
				add_meta_box( 'gwolle_gb_import_postbox_gwolle', esc_html__('Import guestbook entries from Gwolle-GB', 'gwolle-gb'), 'gwolle_gb_import_postbox_gwolle', 'gwolle_gb_import', 'normal' );

				do_meta_boxes( 'gwolle_gb_import', 'normal', '' );
				?>
			</div>
		</div>

	</div><?php
}


/*
 * Metabox with form for import from DMS Guestbook.
 */
function gwolle_gb_import_postbox_dms() {
	global $wpdb;
	?>
	<form name="gwolle_gb_import_dms" id="gwolle_gb_import_dms" method="POST" action="#" accept-charset="UTF-8">
		<input type="hidden" name="gwolle_gb_page" value="gwolle_gb_import" />

		<?php
		/* Nonce */
		$nonce = wp_create_nonce( 'gwolle_gb_page_import_dms' );
		echo '<input type="hidden" id="gwolle_gb_wpnonce_dms" name="gwolle_gb_wpnonce_dms" value="' . esc_attr( $nonce ) . '" />';

		// Does the table of DMSGuestbook exist?
		$sql = "
			SHOW
			TABLES
			LIKE '" . $wpdb->prefix . "dmsguestbook'";
		$found_tables = $wpdb->get_results( $sql, ARRAY_A );

		$count = 0;
		if ( isset($found_tables[0]) && in_array( $wpdb->prefix . 'dmsguestbook', $found_tables[0] ) ) {
			// Get entry count
			$sql = "
				SELECT
					COUNT(id) AS count
				FROM
					" . $wpdb->prefix . "dmsguestbook";

			$data = $wpdb->get_results( $sql, ARRAY_A );

			$count = (int) $data[0]['count'];
		}

		if ( isset($found_tables[0]) && in_array( $wpdb->prefix . 'dmsguestbook', $found_tables[0] ) ) { ?>
			<div>
				<?php /* translators: %s is the number of entries */ echo sprintf( _n('%s entry was found and will be imported.', ' %s entries were found and will be imported.', $count, 'gwolle-gb'), $count ); ?>
			</div>
			<div>
				<?php esc_html_e('The importer will preserve the following data per entry:', 'gwolle-gb'); ?>
				<ul class="ul-disc">
					<li><?php esc_html_e('Name', 'gwolle-gb'); ?></li>
					<li><?php esc_html_e('E-Mail address', 'gwolle-gb'); ?></li>
					<li><?php esc_html_e('URL/Website', 'gwolle-gb'); ?></li>
					<li><?php esc_html_e('Date of the entry', 'gwolle-gb'); ?></li>
					<li><?php esc_html_e('IP address', 'gwolle-gb'); ?></li>
					<li><?php esc_html_e('Message', 'gwolle-gb'); ?></li>
					<li><?php esc_html_e('"is spam" flag', 'gwolle-gb'); ?></li>
					<li><?php esc_html_e('"is checked" flag', 'gwolle-gb'); ?></li>
				</ul>
				<?php /* translators: %s is for bold text */ echo sprintf( esc_html__('However, data such as HTML formatting is not supported by Gwolle-GB and %swill not%s be imported.', 'gwolle-gb'), '<strong>', '</strong>' ); ?>
				<br />
				<?php esc_html_e('The importer does not delete any data, so you can go back whenever you want.', 'gwolle-gb'); ?>
			</div>

			<p>
				<label for="gwolle_gb_dmsguestbook" class="selectit">
					<input id="gwolle_gb_dmsguestbook" name="gwolle_gb_dmsguestbook" type="checkbox" />
					<?php esc_html_e('Import all entries from DMSGuestbook.', 'gwolle-gb'); ?>
				</label>
			</p>
			<p>
				<input name="start_import_dms" id="start_import_dms" type="submit" class="button" disabled value="<?php esc_attr_e('Start import', 'gwolle-gb'); ?>">
			</p><?php
		} else {
			echo '<div>' . esc_html__('DMSGuestbook was not found.', 'gwolle-gb') . '</div>';
		} ?>
	</form>
	<?php
}


/*
 * Metabox with form for import from WordPress comments.
 */
function gwolle_gb_import_postbox_wp() {
	?>
	<form name="gwolle_gb_import_wp" id="gwolle_gb_import_wp" method="POST" action="#" accept-charset="UTF-8">
		<input type="hidden" name="gwolle_gb_page" value="gwolle_gb_import" />

		<?php
		/* Nonce */
		$nonce = wp_create_nonce( 'gwolle_gb_page_import_wp' );
		echo '<input type="hidden" id="gwolle_gb_wpnonce_wp" name="gwolle_gb_wpnonce_wp" value="' . esc_attr( $nonce ) . '" />';
		?>

		<div>
			<?php esc_html_e('The importer will preserve the following data per entry:', 'gwolle-gb'); ?>
			<ul class="ul-disc">
				<li><?php esc_html_e('Name', 'gwolle-gb'); ?></li>
				<li><?php esc_html_e('User ID', 'gwolle-gb'); ?></li>
				<li><?php esc_html_e('E-Mail address', 'gwolle-gb'); ?></li>
				<li><?php esc_html_e('URL/Website', 'gwolle-gb'); ?></li>
				<li><?php esc_html_e('Date of the entry', 'gwolle-gb'); ?></li>
				<li><?php esc_html_e('IP address', 'gwolle-gb'); ?></li>
				<li><?php esc_html_e('Message', 'gwolle-gb'); ?></li>
				<li><?php esc_html_e('"approved" status', 'gwolle-gb'); ?></li>
			</ul>
			<?php /* translators: %s is for bold text */ echo sprintf( esc_html__('However, data such as HTML formatting is not supported by Gwolle-GB and %swill not%s be imported.', 'gwolle-gb'), '<strong>', '</strong>' ); ?>
			<br />
			<?php esc_html_e('Spam comments will not be imported.', 'gwolle-gb'); ?>
			<br />
			<?php esc_html_e('The importer does not delete any data, so you can go back whenever you want.', 'gwolle-gb'); ?>
		</div>

		<p><label for="gwolle_gb_pageid"><?php esc_html_e('Select a page to import the comments from:', 'gwolle-gb'); ?></label><br />
			<select id="gwolle_gb_pageid" name="gwolle_gb_pageid">
			<option value="0"><?php esc_html_e('Select', 'gwolle-gb'); ?></option>
			<?php
			$args = array(
				'post_type'              => 'page',
				'posts_per_page'         => 500,
				'order'                  => 'ASC',
				'orderby'                => 'title',
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
			);

			$sel_query = new WP_Query( $args );
			if ( $sel_query->have_posts() ) {
				while ( $sel_query->have_posts() ) {
					$sel_query->the_post();
					$args = array(
						'status'  => 'all',
						'post_id' => get_the_ID(),
						'count'   => true,
					);
					$num_comments = (int) get_comments($args);
					// get_comments_number returns only approved comments, and wp_count_comments seems to list spam too?

					if ( $num_comments === 0 ) {
						continue;
					} else {
						/* translators: %s is the number of comments */
						$comments = sprintf( _n('%s Comment', '%s Comments', $num_comments, 'gwolle-gb'), $num_comments );
					}

					echo '<option value="' . get_the_ID() . '">' . get_the_title() . ' (' . $comments . ')</option>';
				}
			}
			wp_reset_postdata(); ?>
			</select>
		</p>

		<p><label for="gwolle_gb_postid"><?php esc_html_e('Select a post to import the comments from:', 'gwolle-gb'); ?></label><br />
			<select id="gwolle_gb_postid" name="gwolle_gb_postid">
			<option value="0"><?php esc_html_e('Select', 'gwolle-gb'); ?></option>
			<?php
			$args = array(
				'post_type'              => 'post',
				'posts_per_page'         => 500,
				'order'                  => 'ASC',
				'orderby'                => 'title',
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
			);

			$sel_query = new WP_Query( $args );
			if ( $sel_query->have_posts() ) {
				while ( $sel_query->have_posts() ) {
					$sel_query->the_post();
					$args = array(
						'status'  => 'all',
						'post_id' => get_the_ID(),
						'count'   => true,
					);
					$num_comments = (int) get_comments($args);

					if ( $num_comments === 0 ) {
						continue;
					} else {
						/* translators: %s is the number of comments */
						$comments = sprintf( _n('%s Comment', '%s Comments', $num_comments, 'gwolle-gb'), $num_comments );
					}

					echo '<option value="' . get_the_ID() . '">' . get_the_title() . ' (' . $comments . ')</option>';
				}
			}
			wp_reset_postdata(); ?>
			</select>
		</p>

		<?php
		$args = array(
			'status'  => 'all',
			'count'   => true,
		);
		$num_comments = (int) get_comments($args); ?>

		<p><label for="gwolle_gb_importfrom"><?php esc_html_e('Select where to import the comments from:', 'gwolle-gb'); ?></label><br />
			<label><input type="radio" name="gwolle_gb_importfrom" id="gwolle_gb_importfrom" value="page" /><?php esc_html_e('Comments from selected page.', 'gwolle-gb'); ?></label><br />
			<label><input type="radio" name="gwolle_gb_importfrom" id="gwolle_gb_importfrom" value="post" /><?php esc_html_e('Comments from selected post.', 'gwolle-gb'); ?></label><br />
			<label><input type="radio" name="gwolle_gb_importfrom" id="gwolle_gb_importfrom" value="all" /><?php esc_html_e('All Comments', 'gwolle-gb'); echo ' (' . $num_comments . ').'; ?></label><br />
		</p>

		<p>
			<input name="start_import_wp" id="start_import_wp" type="submit" class="button" disabled value="<?php esc_attr_e('Start import', 'gwolle-gb'); ?>">
		</p>
	</form>
	<?php
}


/*
 * Metabox with form for import from Gwolle through a CSV file.
 */
function gwolle_gb_import_postbox_gwolle() {
	?>
	<form name="gwolle_gb_import_gwolle" id="gwolle_gb_import_gwolle" method="POST" action="#" accept-charset="UTF-8" enctype="multipart/form-data">
		<input type="hidden" name="gwolle_gb_page" value="gwolle_gb_import" />

		<?php
		/* Nonce */
		$nonce = wp_create_nonce( 'gwolle_gb_page_import_gwolle' );
		echo '<input type="hidden" id="gwolle_gb_wpnonce_gwolle" name="gwolle_gb_wpnonce_gwolle" value="' . esc_attr( $nonce ) . '" />';
		?>

		<p>
			<label for="start_import_gwolle_file" class="selectit"><?php esc_html_e('Select a CSV file with exported entries to import again:', 'gwolle-gb'); ?><br />
				<input id="start_import_gwolle_file" name="start_import_gwolle_file" type="file" />
			</label>
		</p>
		<p>
			<input name="start_import_gwolle" id="start_import_gwolle" type="submit" class="button" disabled value="<?php esc_attr_e('Start import', 'gwolle-gb'); ?>">
		</p>
		<p>
			<?php esc_html_e('If you want to manually prepare a CSV file, please use decent software like LibreOffice Calc. Microsoft Excel will not be able to prepare a valid CSV file.', 'gwolle-gb'); ?><br />
		</p>

	</form>
	<?php
}


/*
 * Handle the submitted forms.
 */
function gwolle_gb_page_import_post() {
	global $wpdb;

	//if ( WP_DEBUG ) { echo "_POST: "; var_dump($_POST); }

	if ( isset( $_POST['gwolle_gb_page']) && $_POST['gwolle_gb_page'] === 'gwolle_gb_import' ) {

		if (isset($_POST['start_import_dms'])) {

			/* Check Nonce */
			$verified = false;
			if ( isset($_POST['gwolle_gb_wpnonce_dms']) ) {
				$verified = wp_verify_nonce( $_POST['gwolle_gb_wpnonce_dms'], 'gwolle_gb_page_import_dms' );
			}
			if ( $verified === false ) {
				// Nonce is invalid.
				gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
				return;
			}

			// Import all entries from DMSGuestbook
			// Does the table of DMSGuestbook exist?
			$sql = "
				SHOW
				TABLES
				LIKE '" . $wpdb->prefix . "dmsguestbook'";
			$found_tables = $wpdb->get_results( $sql, ARRAY_A );

			if ( isset($found_tables[0]) && in_array( $wpdb->prefix . 'dmsguestbook', $found_tables[0] ) ) {
				$result = $wpdb->get_results("
					SELECT
						`name`,
						`email`,
						`url`,
						`date`,
						`ip`,
						`message`,
						`spam`,
						`additional`,
						`flag`
					FROM
						" . $wpdb->prefix . "dmsguestbook
					ORDER BY
						date ASC
					", ARRAY_A);

				if ( is_array($result) && ! empty($result) ) {

					$saved = 0;
					foreach ($result as $entry_data) {

						/* New Instance of gwolle_gb_entry. */
						$entry = new gwolle_gb_entry();

						/* Set the data in the instance */
						$entry->set_isspam( $entry_data['spam'] );
						$entry->set_ischecked( true );
						$entry->set_istrash( $entry_data['flag'] );
						$entry->set_content( $entry_data['message'] );
						$entry->set_datetime( $entry_data['date'] );
						$entry->set_author_name( $entry_data['name'] );
						$entry->set_author_email( $entry_data['email'] );
						$entry->set_author_ip( $entry_data['ip'] );
						$entry->set_author_website( $entry_data['url'] );

						/* Save the instance */
						$save = $entry->save();
						if ( $save ) {
							// We have been saved to the Database
							gwolle_gb_add_log_entry( $entry->get_id(), 'imported-from-dmsguestbook' );
							$saved++;
						}
					}
					if ( $saved === 0 ) {
						gwolle_gb_add_message( '<p>' . esc_html__("I'm sorry, but I wasn't able to import entries from DMSGuestbook successfully.", 'gwolle-gb') . '</p>', true, false);
					} else if ( $saved === 1 || $saved > 1 ) {
						do_action( 'gwolle_gb_save_entry_admin', $entry );
						/* translators: %s is the number of entries */
						gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry imported successfully from DMSGuestbook.', '%s entries imported successfully from DMSGuestbook.', $saved, 'gwolle-gb'), $saved ) . '</p>', false, false);
					}
				} else {
					/* translators: %s is for bold text */
					gwolle_gb_add_message( '<p>' . sprintf( esc_html__("%sNothing to import.%s The guestbook you've chosen does not contain any entries.", 'gwolle-gb'), '<strong>', '</strong>' ) . '</p>', true, false);
				}
			} else {
				gwolle_gb_add_message( '<p>' . esc_html__("I'm sorry, but I wasn't able to find the MySQL table of DMSGuestbook.", 'gwolle-gb') . '</p>', true, false);
			}

		} else if (isset($_POST['start_import_wp'])) {

			/* Check Nonce */
			$verified = false;
			if ( isset($_POST['gwolle_gb_wpnonce_wp']) ) {
				$verified = wp_verify_nonce( $_POST['gwolle_gb_wpnonce_wp'], 'gwolle_gb_page_import_wp' );
			}
			if ( $verified === false ) {
				// Nonce is invalid.
				gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
				return;
			}

			$args = array();

			if ( isset($_POST['gwolle_gb_importfrom']) && $_POST['gwolle_gb_importfrom'] === 'page' && isset($_POST['gwolle_gb_pageid']) && (int) $_POST['gwolle_gb_pageid'] > 0 ) {
				$page_id = (int) $_POST['gwolle_gb_pageid'];
				$args = array(
					'status' => 'all',
					'post_id' => $page_id,
				);
			} else if ( isset($_POST['gwolle_gb_importfrom']) && $_POST['gwolle_gb_importfrom'] === 'post' && isset($_POST['gwolle_gb_postid']) && (int) $_POST['gwolle_gb_postid'] > 0 ) {
				$post_id = (int) $_POST['gwolle_gb_postid'];
				$args = array(
					'status' => 'all',
					'post_id' => $post_id,
				);
			} else if ( isset($_POST['gwolle_gb_importfrom']) && $_POST['gwolle_gb_importfrom'] === 'all' ) {
				$args = array(
					'status' => 'all',
				);
			} else {
				gwolle_gb_add_message( '<p>' . esc_html__("You haven't chosen how to import from WordPress comments. Please choose and try again.", 'gwolle-gb') . '</p>', true, false);
			}

			if ( is_array($args) && ! empty($args) ) {
				$comments = get_comments( $args );

				if ( is_array($comments) && ! empty($comments) ) {

					$saved = 0;
					foreach ( $comments as $comment ) {

						/* New Instance of gwolle_gb_entry. */
						$entry = new gwolle_gb_entry();

						/* Set the data in the instance */

						$entry->set_ischecked( $comment->comment_approved );
						$entry->set_content( $comment->comment_content );
						$entry->set_datetime( strtotime( $comment->comment_date ) );
						$entry->set_author_name( $comment->comment_author );
						$entry->set_author_email( $comment->comment_author_email );
						$entry->set_author_ip( $comment->comment_author_IP );
						$entry->set_author_website( $comment->comment_author_url );
						$entry->set_author_id( $comment->user_id );

						/* Save the instance */
						$save = $entry->save();
						if ( $save ) {
							// We have been saved to the Database
							gwolle_gb_add_log_entry( $entry->get_id(), 'imported-from-wp' );
							$saved++;
						}
					}
					if ( $saved === 0 ) {
						gwolle_gb_add_message( '<p>' . esc_html__("I'm sorry, but I wasn't able to import comments from that page successfully.", 'gwolle-gb') . '</p>', true, false);
					} else if ( $saved === 1 || $saved > 1 ) {
						do_action( 'gwolle_gb_save_entry_admin', $entry );
						/* translators: %s is the number of entries */
						gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry imported successfully from WordPress comments.', '%s entries imported successfully from WordPress comments.', $saved, 'gwolle-gb'), $saved ) . '</p>', false, false);
					}
				} else {
					/* translators: %s is for bold text */
					gwolle_gb_add_message( '<p>' . sprintf( esc_html__('%sNothing to import.%s There seem to be no comments on this page, post or at all.', 'gwolle-gb'), '<strong>', '</strong>' ) . '</p>', true, false);
				}
			} else {
				gwolle_gb_add_message( '<p>' . esc_html__('No comments found. Please try again.', 'gwolle-gb') . '</p>', true, false);
			}

		} else if (isset($_POST['start_import_gwolle'])) {

			/* Check Nonce */
			$verified = false;
			if ( isset($_POST['gwolle_gb_wpnonce_gwolle']) ) {
				$verified = wp_verify_nonce( $_POST['gwolle_gb_wpnonce_gwolle'], 'gwolle_gb_page_import_gwolle' );
			}
			if ( $verified === false ) {
				// Nonce is invalid.
				gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
				return;
			}

			// if they DID upload a file...
			if ($_FILES['start_import_gwolle_file']['name']) {
				if ( ! $_FILES['start_import_gwolle_file']['error'] ) { // if no errors...
					//now is the time to modify the future file name and validate the file
					// $new_file_name = strtolower( $_FILES['gwolle_gb_gwolle']['tmp_name'] ); //rename file
					if ( $_FILES['start_import_gwolle_file']['size'] > ( 4096000 ) ) { // Can't be larger than 4 MB
						$valid_file = false;
						gwolle_gb_add_message( '<p>' . esc_html__('Your file is too large.', 'gwolle-gb') . '</p>', true, false);
					} else {
						if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
							gwolle_gb_add_message( '<p>' . esc_html__('You have a very old version of PHP. Please contact your hosting provider and request an upgrade.', 'gwolle-gb') . '</p>', false, false);
						}

						ini_set('auto_detect_line_endings', true);
						$handle = fopen($_FILES['start_import_gwolle_file']['tmp_name'], 'r');
						$row = 0;

						while ( ( $data = fgetcsv( $handle, 2000, ',', '"' ) ) !== FALSE ) {
							$num = count($data);
							if ($row === 0) {
								// Check the headerrow. $testrow_old is version 1.4.1 and older.
								$testrow_1_0 = array(
									'id',
									'author_name',
									'author_email',
									'author_origin',
									'author_website',
									'author_ip',
									'author_host',
									'content',
									'date',
									'isspam',
									'ischecked',
									'istrash',
								);
								$testrow_1_4_1 = array(
									'id',
									'author_name',
									'author_email',
									'author_origin',
									'author_website',
									'author_ip',
									'author_host',
									'content',
									'datetime',
									'isspam',
									'ischecked',
									'istrash',
								);
								$testrow_1_4_8 = array(
									'id',
									'author_name',
									'author_email',
									'author_origin',
									'author_website',
									'author_ip',
									'author_host',
									'content',
									'datetime',
									'isspam',
									'ischecked',
									'istrash',
									'admin_reply',
								);
								$testrow_2_3_9 = array(
									'id',
									'author_name',
									'author_email',
									'author_origin',
									'author_website',
									'author_ip',
									'author_host',
									'content',
									'datetime',
									'isspam',
									'ischecked',
									'istrash',
									'admin_reply',
									'book_id',
								);
								$testrow_2_4_0 = array(
									'id',
									'author_name',
									'author_email',
									'author_origin',
									'author_website',
									'author_ip',
									'author_host',
									'content',
									'datetime',
									'isspam',
									'ischecked',
									'istrash',
									'admin_reply',
									'book_id',
									'meta_fields',
								);
								if ( $data !== $testrow_1_0 && $data !== $testrow_1_4_1 && $data !== $testrow_1_4_8 && $data !== $testrow_2_3_9 && $data !== $testrow_2_4_0 ) {
									gwolle_gb_add_message( '<p>' . esc_html__('It seems your CSV file is from an export that is not compatible with this version of Gwolle-GB.', 'gwolle-gb') . '</p>', true, false);
									break;
								}
								$row++;
								continue;
							}

							if ( $num !== 12 && $num !== 13 && $num !== 14 && $num !== 15 ) {
								gwolle_gb_add_message( '<p>' . esc_html__('Your data seems to be corrupt. Import failed.', 'gwolle-gb') . '</p>', true, false);
								break;
							}

							/* New Instance of gwolle_gb_entry. */
							$entry = new gwolle_gb_entry();

							/* Check if the date is a timestamp, else convert */
							if ( ! is_numeric($data[8]) ) {
								$data[8] = strtotime($data[8]);
							}

							/* Set the data in the instance */
							// $entry->set_id( $data[0] ); // id of entry
							$entry->set_author_name( $data[1] );
							$entry->set_author_email( $data[2] );
							$entry->set_author_origin( $data[3] );
							$entry->set_author_website( $data[4] );
							$entry->set_author_ip( $data[5] );
							$entry->set_author_host( $data[6] );
							$entry->set_content( $data[7] );
							$entry->set_datetime( $data[8] );
							$entry->set_isspam( $data[9] );
							$entry->set_ischecked( $data[10] );
							$entry->set_istrash( $data[11] );
							if ( isset( $data[12] ) ) {
								$entry->set_admin_reply( $data[12] ); // admin_reply is only since 1.4.8
							}
							if ( isset( $data[13] ) ) {
								$entry->set_book_id( $data[13] ); // book_id is only since 2.3.9
							}
							$metas = ''; // reset
							if ( isset( $data[14] ) ) {
								$metas = $data[14]; // meta fields is only since 2.4.0
							}

							// Support author ID, based on email address.
							$user = new WP_User();
							$user_object = $user->get_data_by( 'email', $data[2] );
							if ( is_object( $user_object ) && isset( $user_object->ID ) ) {
								$entry->set_author_id( $user_object->ID );
							}

							/* Save the instance */
							$save = $entry->save();
							if ( $save ) {
								// We have been saved to the Database
								if ( isset( $metas ) && function_exists( 'gwolle_gb_addon_save_meta' ) ) {
									$metas = maybe_unserialize( $metas );
									if ( ! empty( $metas ) && is_array( $metas ) ) {
										foreach ( $metas as $meta ) {
											gwolle_gb_addon_save_meta( $entry->get_id(), $meta['meta_key'], $meta['meta_value'] );
										}
									}
								}
								gwolle_gb_add_log_entry( $entry->get_id(), 'imported-from-gwolle' );
								$row++;
							} else {
								//$gwolle_gb_messages .= '<p>' . print_r( $entry, true ) . '</p>'; // Debug
								gwolle_gb_add_message( '<p>' . esc_html__('Your data seems to be corrupt. Saving failed and import failed.', 'gwolle-gb') . '</p>', true, false);
								break;
							}

						}
						$row--; // minus the header

						if ( $row === 0 ) {
							gwolle_gb_add_message( '<p>' . esc_html__("I'm sorry, but I wasn't able to import entries from the CSV file.", 'gwolle-gb') . '</p>', true, false);
						} else if ( $row === 1 || $row > 1 ) {
							do_action( 'gwolle_gb_save_entry_admin', $entry );
							/* translators: %s is the number of entries */
							gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry imported successfully from the CSV file.', '%s entries imported successfully from the CSV file.', $row, 'gwolle-gb'), $row ) . '</p>', false, false);
						}

						fclose($handle);

					}
				} else {
					// Set that to be the returned message.
					gwolle_gb_add_message( '<p>' . esc_html__('Your upload triggered the following error:', 'gwolle-gb') . ' ' . $_FILES['gwolle_gb_gwolle']['error'] . '</p>', true, false);
				}
			}
		}
	}
}
