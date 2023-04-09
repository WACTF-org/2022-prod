<?php /*
 *
 *	export.php
 *	Lets the user export guestbook entries to a CSV file.
 *
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Admin page for export.
 */
function gwolle_gb_page_export() {

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	}

	gwolle_gb_admin_enqueue();

	/*
	 * Build the Page.
	 */
	?>
	<div class="wrap gwolle_gb">
		<div id="icon-gwolle-gb"><br /></div>
		<h1><?php esc_html_e('Export guestbook entries.', 'gwolle-gb'); ?> (Gwolle Guestbook) - v<?php echo GWOLLE_GB_VER; ?></h1>

		<div id="poststuff" class="gwolle_gb_export metabox-holder">
			<div class="postbox-container">
				<?php
				add_meta_box( 'gwolle_gb_export_postbox', esc_html__('Export guestbook entries from Gwolle-GB', 'gwolle-gb'), 'gwolle_gb_export_postbox', 'gwolle_gb_export', 'normal' );
				add_meta_box( 'gwolle_gb_export_postbox_user', esc_html__('Export guestbook entries for a user', 'gwolle-gb'), 'gwolle_gb_export_postbox_user', 'gwolle_gb_export', 'normal' );
				do_meta_boxes( 'gwolle_gb_export', 'normal', '' );
				?>
			</div>
		</div>

	</div><?php

}


function gwolle_gb_export_postbox() {

	$count = gwolle_gb_get_entry_count(array( 'all' => 'all' ));
	$num_entries = 2000;
	$parts = (int) ceil( $count / $num_entries );
	?>

	<form name="gwolle_gb_export" id="gwolle_gb_export" method="POST" action="#" accept-charset="UTF-8">
		<input type="hidden" name="gwolle_gb_page" value="gwolle_gb_export" />
		<input type="hidden" name="gwolle_gb_export_part" id="gwolle_gb_export_part" value="1" />
		<input type="hidden" name="gwolle_gb_export_parts" id="gwolle_gb_export_parts" value="<?php echo esc_attr( $parts ); ?>" />

	<?php
	/* Nonce */
	$nonce = wp_create_nonce( 'gwolle_gb_page_export' );
	echo '<input type="hidden" id="gwolle_gb_wpnonce" name="gwolle_gb_wpnonce" value="' . esc_attr( $nonce ) . '" />';

	if ( $count === 0 ) { ?>
		<p><?php esc_html_e('No entries were found.', 'gwolle-gb'); ?></p><?php
	} else {
		?>
		<p>
			<?php /* translators: %s is the number of entries */ echo sprintf( _n( '%s entry was found and will be exported.', '%s entries were found and will be exported.', $count, 'gwolle-gb' ), $count ); ?>
			<br />
			<?php /* translators: %s is the number of file parts */ echo sprintf( _n( 'The download will happen in a CSV file in %s part.', 'The download will happen in a CSV file in %s parts.', $parts, 'gwolle-gb' ), $parts ); ?>
		</p>
		<p>
			<?php esc_html_e('The exporter will preserve the following data per entry:', 'gwolle-gb'); ?>
		</p>
		<ul class="ul-disc">
			<li><?php esc_html_e('Name', 'gwolle-gb'); ?></li>
			<li><?php esc_html_e('E-Mail address', 'gwolle-gb'); ?></li>
			<li><?php esc_html_e('URL/Website', 'gwolle-gb'); ?></li>
			<li><?php esc_html_e('Origin', 'gwolle-gb'); ?></li>
			<li><?php esc_html_e('Date of the entry', 'gwolle-gb'); ?></li>
			<li><?php esc_html_e('IP address', 'gwolle-gb'); ?></li>
			<li><?php esc_html_e('Host address', 'gwolle-gb'); ?></li>
			<li><?php esc_html_e('Message', 'gwolle-gb'); ?></li>
			<li><?php esc_html_e('"is checked" flag', 'gwolle-gb'); ?></li>
			<li><?php esc_html_e('"is spam" flag', 'gwolle-gb'); ?></li>
			<li><?php esc_html_e('"is trash" flag', 'gwolle-gb'); ?></li>
			<li><?php esc_html_e('Admin Reply', 'gwolle-gb'); ?></li>
			<li><?php esc_html_e('Book ID', 'gwolle-gb'); ?></li>
			<li><?php esc_html_e('Meta Fields (if the add-on is active)', 'gwolle-gb'); ?></li>
		</ul>
		<?php esc_html_e('The exporter does not delete any data, so your data will still be here.', 'gwolle-gb'); ?>

		<p>
			<label for="start_export_enable" class="selectit">
				<input id="start_export_enable" name="start_export_enable" type="checkbox" />
				<?php esc_html_e('Export all entries from this website.', 'gwolle-gb'); ?>
			</label>
		</p>
		<p class="gwolle_gb_export_gif_container">
			<input name="gwolle_gb_start_export" id="gwolle_gb_start_export" type="submit" class="button" disabled value="<?php esc_attr_e('Start export', 'gwolle-gb'); ?>">
			<span class="gwolle_gb_export_gif"></span>
		</p>
		<?php
	}
	?></form><?php
}


function gwolle_gb_export_action() {
	if ( is_admin() ) {
		if ( isset( $_POST['gwolle_gb_page']) && $_POST['gwolle_gb_page'] === 'gwolle_gb_export' ) {
			gwolle_gb_export_callback();
		}
	}
}
add_action('admin_init', 'gwolle_gb_export_action');


/*
 * Callback function for request generated from the Export page.
 */
function gwolle_gb_export_callback() {

	if ( ! current_user_can('manage_options') ) {
		echo 'error, no permission.';
		die();
	}

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['gwolle_gb_wpnonce']) ) {
		$verified = wp_verify_nonce( $_POST['gwolle_gb_wpnonce'], 'gwolle_gb_page_export' );
	}
	if ( $verified === false ) {
		// Nonce is invalid.
		esc_html_e('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb');
		die();
	}

	$count = gwolle_gb_get_entry_count(array( 'all' => 'all' ));
	$num_entries = 2000;
	$parts = (int) ceil( $count / $num_entries );
	if ( isset( $_POST['gwolle_gb_export_part'] ) && ( (int) $_POST['gwolle_gb_export_part'] < ( $parts + 1 ) ) ) {
		$part = (int) $_POST['gwolle_gb_export_part'];
	} else {
		echo '(Gwolle-GB) Wrong part requested.';
		die();
	}
	$offset = ( $part * $num_entries ) - $num_entries;

	$entries = gwolle_gb_get_entries( array(
			'num_entries' => $num_entries,
			'offset'      => $offset,
			'all'         => 'all',
		));

	if ( is_array($entries) && ! empty($entries) ) {

		// Clean everything before here
		ob_end_clean();

		// Output headers so that the file is downloaded rather than displayed
		$filename = 'gwolle_gb_export_' . GWOLLE_GB_VER . '_' . date('Y-m-d_H-i') . '-part_' . $part . '_of_' . $parts . '.csv';
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . esc_attr( $filename ) );

		// Create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// Output the column headings
		fputcsv($output, array(
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
			));

		foreach ( $entries as $entry ) {

			$row = array();

			$row[] = $entry->get_id();
			$row[] = addslashes($entry->get_author_name());
			$row[] = addslashes($entry->get_author_email());
			$row[] = addslashes($entry->get_author_origin());
			$row[] = addslashes($entry->get_author_website());
			$row[] = $entry->get_author_ip();
			$row[] = $entry->get_author_host();
			$row[] = addslashes($entry->get_content());
			$row[] = $entry->get_datetime();
			$row[] = $entry->get_isspam();
			$row[] = $entry->get_ischecked();
			$row[] = $entry->get_istrash();
			$row[] = $entry->get_admin_reply();
			$row[] = $entry->get_book_id();

			$meta = '';
			if ( function_exists( 'gwolle_gb_addon_get_meta' ) ) {
				$meta = gwolle_gb_addon_get_meta( $entry->get_id(), '' );
				$meta = serialize( $meta );
			}
			$row[] = $meta;

			fputcsv($output, $row, ',', '"');

			gwolle_gb_add_log_entry( $entry->get_id(), 'exported-to-csv' );

		}

		fclose($output);
		die();
	}

	echo '(Gwolle-GB) Error, no entries.';
	die();
}


/*
 * Export entries for user.
 *
 * @since 2.3.11
 */
function gwolle_gb_export_postbox_user() {
	?>

	<form name="gwolle_gb_export_user" id="gwolle_gb_export_user" method="POST" action="#" accept-charset="UTF-8">
		<input type="hidden" name="gwolle_gb_page" value="gwolle_gb_export_user" />

	<?php
	/* Nonce */
	$nonce = wp_create_nonce( 'gwolle_gb_page_export_user' );
	echo '<input type="hidden" id="gwolle_gb_wpnonce" name="gwolle_gb_wpnonce" value="' . esc_attr( $nonce ) . '" />';

	$count = gwolle_gb_get_entry_count( array( 'all' => 'all' ) );
	if ( $count === 0 ) { ?>
		<p><?php esc_html_e('No entries were found.', 'gwolle-gb'); ?></p><?php
	} else {
		?>
		<p><?php esc_html_e('Select one option below, either User ID or Email address', 'gwolle-gb'); ?></p>
		<p>
			<label for="gwolle_gb_user_id" class="text-info"><?php esc_html_e('User ID', 'gwolle-gb'); ?>:<br />
				<input type="text" name="gwolle_gb_user_id" id="gwolle_gb_user_id" value="" placeholder="<?php esc_attr_e('User ID', 'gwolle-gb'); ?>" />
			</label><br />
			<label for="gwolle_gb_user_email" class="text-info"><?php esc_html_e('User Email', 'gwolle-gb'); ?>:<br />
				<input type="text" name="gwolle_gb_user_email" id="gwolle_gb_user_email" value="" placeholder="<?php esc_attr_e('User Email', 'gwolle-gb'); ?>" />
			</label>
		</p>

		<p>
			<label for="start_export_user_enable" class="selectit">
				<input id="start_export_user_enable" name="start_export_user_enable" type="checkbox" />
				<?php esc_html_e('Export user entries from this website.', 'gwolle-gb'); ?>
			</label>
		</p>
		<input name="gwolle_gb_start_export_user" id="gwolle_gb_start_export_user" type="submit" class="button" disabled value="<?php esc_attr_e('Start export', 'gwolle-gb'); ?>">
		<?php
	}
	?></form><?php
}


function gwolle_gb_export_user_action() {
	if ( is_admin() ) {
		if ( isset( $_POST['gwolle_gb_page']) && $_POST['gwolle_gb_page'] === 'gwolle_gb_export_user' ) {
			gwolle_gb_export_user_callback();
		}
	}
}
add_action('admin_init', 'gwolle_gb_export_user_action');


/*
 * Callback function for request generated from the Export page.
 */
function gwolle_gb_export_user_callback() {

	if ( ! current_user_can('manage_options') ) {
		echo 'error, no permission.';
		die();
	}

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['gwolle_gb_wpnonce']) ) {
		$verified = wp_verify_nonce( $_POST['gwolle_gb_wpnonce'], 'gwolle_gb_page_export_user' );
	}
	if ( $verified === false ) {
		// Nonce is invalid.
		esc_html_e('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb');
		die();
	}

	if ( isset( $_POST['gwolle_gb_user_id']) && ( (int) $_POST['gwolle_gb_user_id'] > 0 ) ) {
		$user_id = (int) $_POST['gwolle_gb_user_id'];
		$entries = gwolle_gb_get_entries(array(
				'author_id'   => $user_id,
				'num_entries' => -1,
				'all'         => 'all',
			));
	} else if ( isset( $_POST['gwolle_gb_user_email']) && strlen($_POST['gwolle_gb_user_email']) > 0 ) {
		$user_id = sanitize_text_field( $_POST['gwolle_gb_user_email'] );
		$entries = gwolle_gb_get_entries(array(
				'email'       => $user_id,
				'num_entries' => -1,
				'all'         => 'all',
			));
	}

	if ( is_array($entries) && ! empty($entries) ) {

		// Clean everything before here
		ob_end_clean();

		// Output headers so that the file is downloaded rather than displayed
		$filename = 'gwolle_gb_export_' . GWOLLE_GB_VER . '_' . date('Y-m-d_H-i') . '-user_' . $user_id . '.csv';
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );

		// Create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// Output the column headings
		fputcsv($output, array(
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
			));

		foreach ( $entries as $entry ) {

			$row = array();

			$row[] = $entry->get_id();
			$row[] = addslashes($entry->get_author_name());
			$row[] = addslashes($entry->get_author_email());
			$row[] = addslashes($entry->get_author_origin());
			$row[] = addslashes($entry->get_author_website());
			$row[] = $entry->get_author_ip();
			$row[] = $entry->get_author_host();
			$row[] = addslashes($entry->get_content());
			$row[] = $entry->get_datetime();
			$row[] = $entry->get_isspam();
			$row[] = $entry->get_ischecked();
			$row[] = $entry->get_istrash();
			$row[] = $entry->get_admin_reply();
			$row[] = $entry->get_book_id();

			$meta = '';
			if ( function_exists( 'gwolle_gb_addon_get_meta' ) ) {
				$meta = gwolle_gb_addon_get_meta( $entry->get_id(), '' );
				$meta = serialize( $meta );
			}
			$row[] = $meta;

			fputcsv($output, $row, ',', '"');

			gwolle_gb_add_log_entry( $entry->get_id(), 'exported-to-csv' );

		}

		fclose($output);
		die();
	}

	echo '(Gwolle-GB) Error, no entries.';
	die();
}
