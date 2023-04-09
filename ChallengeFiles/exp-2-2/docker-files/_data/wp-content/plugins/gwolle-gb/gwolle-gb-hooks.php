<?php

/*
 * WordPress Actions and Filters.
 * See the Plugin API in the Codex:
 * https://codex.wordpress.org/Plugin_API
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Check if we need to install or upgrade.
 * Supports MultiSite since 1.5.2.
 */
function gwolle_gb_init() {

	global $wpdb;

	$current_version = get_option( 'gwolle_gb_version' );

	if ( $current_version && version_compare($current_version, GWOLLE_GB_VER, '<') ) {
		// Upgrade, if this version differs from what the database says.

		if ( is_multisite() ) {
			$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blog_id) {
				switch_to_blog( $blog_id );
				gwolle_gb_upgrade();
				restore_current_blog();
			}
		} else {
			gwolle_gb_upgrade();
		}
	}
}
add_action( 'init', 'gwolle_gb_init' );


/*
 * Install database tables for new blog on MultiSite.
 * Deprecated action since WP 5.1.0.
 *
 * @since 1.5.2
 */
function gwolle_gb_activate_new_site( $blog_id ) {
	switch_to_blog( $blog_id );
	gwolle_gb_install();
	restore_current_blog();
}
add_action( 'wpmu_new_blog', 'gwolle_gb_activate_new_site' );


/*
 * Install database tables for new blog on MultiSite.
 * Used since WP 5.1.0.
 * Do not use wp_insert_site, since the options table doesn't exist yet...
 *
 * @since 3.1.5
 */
function gwolle_gb_wp_initialize_site( $blog ) {
	switch_to_blog( $blog->id );
	gwolle_gb_install();
	restore_current_blog();
}
add_action( 'wp_initialize_site', 'gwolle_gb_wp_initialize_site' );


/*
 * Register styles and scripts.
 * Enqueue them in the frontend function only when we need them.
 */
function gwolle_gb_register() {

	// Always load jQuery, it's just easier this way.
	wp_enqueue_script('jquery');

	// Register script for frontend. Load it later.
	wp_register_script( 'gwolle_gb_frontend_js', GWOLLE_GB_URL . 'frontend/js/gwolle-gb-frontend.js', array('jquery'), GWOLLE_GB_VER, true );
	$data_to_be_passed = array(
		'ajax_url'     => admin_url('admin-ajax.php'),
		'load_message' => /* translators: Infinite Scroll */ esc_html__('Loading more...', 'gwolle-gb'),
		'end_message'  => /* translators: Infinite Scroll */ esc_html__('No more entries.', 'gwolle-gb'),
		'honeypot'     => gwolle_gb_get_field_name( 'honeypot' ),
		'honeypot2'    => gwolle_gb_get_field_name( 'honeypot2' ),
		'timeout'      => gwolle_gb_get_field_name( 'timeout' ),
		'timeout2'     => gwolle_gb_get_field_name( 'timeout2' ),
	);
	wp_localize_script( 'gwolle_gb_frontend_js', 'gwolle_gb_frontend_script', $data_to_be_passed );

	// Register style for frontend. Load it later.
	wp_register_style('gwolle_gb_frontend_css', GWOLLE_GB_URL . 'frontend/css/gwolle-gb-frontend.css', false, GWOLLE_GB_VER,  'screen');
}
add_action('wp_enqueue_scripts', 'gwolle_gb_register');


/*
 * Enqueue JS and CSS for marktitup editor functions.
 *
 * @since 3.0.0
 */
function gwolle_gb_enqueue_markitup() {
	wp_enqueue_script( 'markitup', GWOLLE_GB_URL . 'frontend/markitup/jquery.markitup.js', 'jquery', GWOLLE_GB_VER, true );
	wp_enqueue_style('gwolle_gb_markitup_css', GWOLLE_GB_URL . 'frontend/markitup/style.css', false, GWOLLE_GB_VER,  'screen');

	$data_to_be_passed = array(
		'bold'      => /* translators: MarkItUp menu item */ esc_html__('Bold', 'gwolle-gb' ),
		'italic'    => /* translators: MarkItUp menu item */ esc_html__('Italic', 'gwolle-gb' ),
		'bullet'    => /* translators: MarkItUp menu item */ esc_html__('Bulleted List', 'gwolle-gb' ),
		'numeric'   => /* translators: MarkItUp menu item */ esc_html__('Numeric List', 'gwolle-gb' ),
		'picture'   => /* translators: MarkItUp menu item */ esc_html__('Picture', 'gwolle-gb' ),
		'source'    => /* translators: MarkItUp menu item */ esc_html__('Source', 'gwolle-gb' ),
		'link'      => /* translators: MarkItUp menu item */ esc_html__('Link', 'gwolle-gb' ),
		'linktext'  => /* translators: MarkItUp menu item */ esc_html__('Your text to link...', 'gwolle-gb' ),
		'clean'     => /* translators: MarkItUp menu item */ esc_html__('Clean', 'gwolle-gb' ),
		'emoji'     => /* translators: MarkItUp menu item */ esc_html__('Emoji', 'gwolle-gb' ),
	);
	wp_localize_script( 'markitup', 'gwolle_gb_localize', $data_to_be_passed );
}


/*
 * Load Language files for frontend and backend.
 */
function gwolle_gb_load_lang() {
	load_plugin_textdomain( 'gwolle-gb', false, GWOLLE_GB_FOLDER . '/lang' );
}
add_action('plugins_loaded', 'gwolle_gb_load_lang');


/*
 * Add number of unchecked entries to admin bar, if > 0.
 */
function gwolle_gb_admin_bar_menu( $wp_admin_bar ) {
	if ( ! current_user_can('moderate_comments') )
		return;

	// Counter
	$count_unchecked = (int) gwolle_gb_get_entry_count(
		array(
			'checked' => 'unchecked',
			'trash'   => 'notrash',
			'spam'    => 'nospam',
		)
	);

	$count_unchecked_i18n = number_format_i18n( $count_unchecked );
	$awaiting_text = esc_attr( sprintf( /* translators: Toolbar */ _n(
			'%s guestbook entry awaiting moderation',
			'%s guestbook entries awaiting moderation',
			$count_unchecked,
			'gwolle-gb' ),
		$count_unchecked_i18n ) );

	if ( $count_unchecked > 0 ) {
		$icon  = '<span class="ab-icon"></span>';
		$title = '<span id="ab-unchecked-entries" class="ab-label awaiting-mod pending-count count-' . $count_unchecked . '" aria-hidden="true">' . $count_unchecked_i18n . '</span>';
		$title .= '<span class="screen-reader-text">' . $awaiting_text . '</span>';

		$wp_admin_bar->add_menu( array(
			'id'    => 'gwolle-gb',
			'title' => $icon . $title,
			'href'  => admin_url('admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&amp;show=unchecked'),
		) );
	}
}
add_action( 'admin_bar_menu', 'gwolle_gb_admin_bar_menu', 61 );
