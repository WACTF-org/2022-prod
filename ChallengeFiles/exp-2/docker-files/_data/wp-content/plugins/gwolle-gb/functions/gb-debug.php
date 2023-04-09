<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Add debug info to debug tab on the settings page.
 *
 * @since 1.6.2
 */
function gwolle_gb_debug_info() {
	global $wp_version, $wp_db_version, $wpdb;

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	} ?>

	<tr>
		<th><?php esc_html_e('WordPress version:', 'gwolle-gb'); ?></th>
		<td><?php
			echo $wp_version . ' (db: ' . $wp_db_version . ')';
			if ( version_compare( $wp_version, '3.7', '<' ) ) {
				echo '<br />' . esc_html__( 'You have a very old version of WordPress that is not receiving security updates anymore. Please upgrade WordPress to a more recent version.', 'gwolle-gb' );
			} ?>
		</td>
	</tr>

	<tr>
		<th><?php esc_html_e('WordPress theme:', 'gwolle-gb'); ?></th>
		<td><?php echo wp_get_theme()->get('Name'); ?></td>
	</tr>

	<tr>
		<th><?php esc_html_e('Active plugins:', 'gwolle-gb'); ?></th>
		<td><?php
			$active_plugins = get_option('active_plugins');
			$active_plugins = gwolle_gb_array_flatten( $active_plugins );
			$active_plugins = implode( '<br />', $active_plugins );
			echo $active_plugins;
			?>
		</td>
	</tr>

	<tr>
		<th><?php esc_html_e('PHP Version:', 'gwolle-gb'); ?></th>
		<td><?php
			echo PHP_VERSION;
			if ( version_compare( PHP_VERSION, '5.6', '<' ) ) {
				echo '<br />' . esc_html__( 'You have a very old version of PHP. Please contact your hosting provider and request an upgrade.', 'gwolle-gb' );
			} ?>
		</td>
	</tr>

	<tr>
		<th><?php esc_html_e('MySQL Version:', 'gwolle-gb'); ?></th>
		<td><?php
			$mysql_version = $wpdb->get_var('SELECT VERSION()');
			echo $mysql_version; ?>
		</td>
	</tr>

	<tr>
		<th><?php esc_html_e('MySQL Charset:', 'gwolle-gb'); ?></th>
		<td><?php
			echo esc_html__('MySQL Charset:', 'gwolle-gb') . ' ' . $wpdb->charset;

			// Make sure we do not run this query after uninstall.
			$table_entries = $wpdb->query("SHOW TABLES LIKE '" . $wpdb->prefix . "gwolle_gb_entries'");

			if ( $table_entries != 0 && method_exists($wpdb, 'get_col_charset') ) {
				$charset = $wpdb->get_col_charset( $wpdb->gwolle_gb_entries, 'content' );
				echo '<br />' . esc_html__('MySQL Column Charset:', 'gwolle-gb') . ' ' . $charset;
			} ?>
		</td>
	</tr>

	<tr>
		<th><?php esc_html_e('MySQL Tables:', 'gwolle-gb'); ?></th>
		<td><?php
			$exist = '';
			$nonexist = '';

			$table = $wpdb->query("SHOW TABLES LIKE '" . $wpdb->prefix . "gwolle_gb_entries'");
			if ( $table != 0 ) {
				$exist .= '<li>gwolle_gb_entries</li>';
			} else {
				$nonexist .= '<li>gwolle_gb_entries</li>';
			}
			$table = $wpdb->query("SHOW TABLES LIKE '" . $wpdb->prefix . "gwolle_gb_log'");
			if ( $table != 0 ) {
				$exist .= '<li>gwolle_gb_log</li>';
			} else {
				$nonexist .= '<li>gwolle_gb_log</li>';
			}

			$active = is_plugin_active( 'gwolle-gb-addon/gwolle-gb-addon.php' ); // true or false
			if ( $active ) {
				$table = $wpdb->query("SHOW TABLES LIKE '" . $wpdb->prefix . "gwolle_gb_meta'");
				if ( $table != 0 ) {
					$exist .= '<li>gwolle_gb_meta</li>';
				} else {
					$nonexist .= '<li>gwolle_gb_meta</li>';
				}
			}
			echo esc_html__('MySQL Tables that exist:', 'gwolle-gb');
			echo '<ul class="ul-disc">';
			echo $exist;
			echo '</ul>';

			if ( strlen( $nonexist ) > 0 ) {
				echo esc_html__('MySQL Tables that do not exist:', 'gwolle-gb');
				echo '<ul class="ul-disc">';
				echo $nonexist;
				echo '</ul>';
			}

			?>
		</td>
	</tr>

	<tr>
		<th><?php esc_html_e('MySQL / MySQLi:', 'gwolle-gb'); ?></th>
		<td><?php
			if ( $wpdb->use_mysqli === true ) {
				echo 'mysqli';
			} else {
				echo 'mysql';
			} ?>
		</td>
	</tr>

	<tr>
		<th><?php esc_html_e('MySQL variables:', 'gwolle-gb'); ?></th>
		<td><?php
			$mysql_variables = (array) $wpdb->get_results('SHOW VARIABLES', ARRAY_N);
			$mysql_variables_char = array();
			foreach ( $mysql_variables as $variable ) {
				$pattern = '/^char/';
				if ( preg_match( $pattern, $variable[0], $matches ) ) {
					$mysql_variables_char[$variable[0]] = $variable[1];
				}
			}

			$mysql_variables_char = gwolle_gb_array_flatten( $mysql_variables_char );
			foreach ( $mysql_variables_char as $key => $value ) {
				echo $key . ': ' . $value . '<br />';
			}
			?>
		</td>
	</tr>

	<tr>
		<th><?php esc_html_e('MySQL engine:', 'gwolle-gb'); ?></th>
		<td><?php
			$sql = "
				SELECT TABLE_NAME, ENGINE
				FROM   information_schema.TABLES
				WHERE  TABLE_SCHEMA = '" . $wpdb->dbname . "'
				;";
			$engines = $wpdb->get_results( $sql, ARRAY_N );
			foreach ( $engines as $engine ) {
				if ( $engine[0] == $wpdb->prefix . 'gwolle_gb_entries' ) {
					echo $engine[1];
				}
			}

			?>
		</td>
	</tr>

	<?php
}


/*
 * Test adding an entry.
 *
 * @param bool $emoji save text with or without emoji characters
 * @return int ID of the saved entry, 0 if not saved
 *
 * @since 1.6.2
 */
function gwolle_gb_test_add_entry( $emoji = false ) {
	// Sample data
	$content = esc_html__('Test entry, delete if you wish.', 'gwolle-gb');
	$data = array(
		'author_name'     => 'You',
		'author_id'       => 0,
		'author_email'    => 'test@example.com',
		'author_origin'   => 'Home',
		'author_website'  => 'https://example.com',
		'author_ip'       => '127.0.0.1',
		'author_host'     => 'example.com',
		'content'         => $content,
		'datetime'        => time(),
		'ischecked'       => 0,
		'checkedby'       => 0,
		'istrash'         => 1,
		'isspam'          => 0,
		'admin_reply'     => esc_html__('Just a test', 'gwolle-gb'),
		'admin_reply_uid' => 0,
		'book_id'         => 1,
	);
	if ( $emoji ) {
		$data['content'] = gwolle_gb_maybe_encode_emoji( $content . ' 😄👍👌', 'content' );
	}

	$entry = new gwolle_gb_entry();

	$set_data = $entry->set_data( $data );
	$entry_id = 0;

	if ( $set_data ) {
		$entry_id = $entry->save();
	}

	return $entry_id;

}


/*
 * Flattens an array, or returns false on fail.
 * Taken from:
 * https://stackoverflow.com/questions/7179799/how-to-flatten-array-of-arrays-to-array
 *
 * @param array Array flat or multi-dimensional.
 * @return array Array flat or false on fail.
 *
 * @since 4.2.1
 */
function gwolle_gb_array_flatten( $array ) {

	if ( ! is_array( $array ) ) {
		return false;
	}

	$result = array();
	foreach ($array as $key => $value) {
		if ( is_array($value) ) {
			$result = array_merge( $result, array_flatten($value) );
		} else {
			$result[$key] = $value;
		}
	}

	return $result;

}
