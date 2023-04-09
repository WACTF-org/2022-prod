<?php

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Admin page for advertising the Add-On.
 */
function gwolle_gb_addon_menu_advertisement() {

	$active = is_plugin_active( 'gwolle-gb-addon/gwolle-gb-addon.php' ); // true or false
	if ( $active && defined( 'GWOLLE_GB_ADDON_VER' ) ) {
		return;
	} else {
		add_submenu_page( GWOLLE_GB_FOLDER . '/gwolle-gb.php', esc_html__('The Add-On', 'gwolle-gb'), /* translators: Menu entry */ esc_html__('The Add-On', 'gwolle-gb'), 'moderate_comments', GWOLLE_GB_FOLDER . '/addon-settings.php', 'gwolle_gb_addon_page_advertisement' );
	}
}
add_action( 'admin_menu', 'gwolle_gb_addon_menu_advertisement', 11 );


/*
 * Admin page for advertising the Add-On. Contains metaboxes.
 */
function gwolle_gb_addon_page_advertisement() {

	if ( ! current_user_can('moderate_comments') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	}

	gwolle_gb_admin_enqueue();

	add_meta_box('gwolle_gb_addon_description', esc_html__('Gwolle Guestbook: The Add-On', 'gwolle-gb'), 'gwolle_gb_addon_description', 'gwolle_gb_addon', 'normal');
	add_meta_box('gwolle_gb_addon_features', esc_html__('Features', 'gwolle-gb'), 'gwolle_gb_addon_features', 'gwolle_gb_addon', 'normal');

	add_meta_box('gwolle_gb_addon_buy', esc_html__('Buy Now', 'gwolle-gb'), 'gwolle_gb_addon_buy', 'gwolle_gb_addon', 'right');
	add_meta_box('gwolle_gb_addon_demo', esc_html__('Demo', 'gwolle-gb'), 'gwolle_gb_addon_demo', 'gwolle_gb_addon', 'right');
	add_meta_box('gwolle_gb_addon_development', esc_html__('Development', 'gwolle-gb'), 'gwolle_gb_addon_development', 'gwolle_gb_addon', 'right');

	?>
	<div class="wrap gwolle_gb">
		<div id="icon-gwolle-gb"><br /></div>
		<h1><?php esc_html_e('Gwolle Guestbook: The Add-On', 'gwolle-gb'); ?></h1>

		<div id="dashboard-widgets-wrap" class="gwolle_gb_addon">
			<div id="dashboard-widgets" class="metabox-holder">
				<div class="postbox-container">
					<?php do_meta_boxes( 'gwolle_gb_addon', 'normal', '' ); ?>
				</div>
				<div class="postbox-container">
					<?php do_meta_boxes( 'gwolle_gb_addon', 'right', '' ); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}


/*
 * Metabox with the main description of the add-on.
 */
function gwolle_gb_addon_description() {
	?>
	<div class="table table_content gwolle_gb">
		<p><?php esc_html_e('Gwolle Guestbook: The Add-On is a commercial add-on for Gwolle Guestbook that gives extra functionality for your guestbook.', 'gwolle-gb'); ?></p>
	</div>
	<div id="gwolle-gb-addon-screenshot"><br /></div>
	<?php
}


/*
 * Metabox with the feature list of the add-on.
 */
function gwolle_gb_addon_features() {
	echo '<h3>
	' . esc_html__('Current features include:', 'gwolle-gb') . '</h3>
	<ul class="ul-disc">
		<li>' . esc_html__('Meta Fields. Add any field you want; company, phone number, you name it.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Social Media Sharing (optional).', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Star Ratings, with voting and display and Rich Snippets for SEO (optional).', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Average star rating per guestbook, including a widget.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Like an entry and view likes for each entry.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Preview for the frontend form.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Preview for the admin editor form.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Automatic Refresh of guestbook list with new entries.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Admin reply on the frontend with AJAX.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Edit content/author/origin of entry on the frontend with AJAX.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Report Abuse.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Easy String Replacement in the default text so you can make this guestbook into a review section or anything you want.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Delete button in each entry for the moderator and author (optional).', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Permalink button in each entry for easy access (optional).', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Email button to contact each author (optional).', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Upload Images through the form. (Only for Author, Editor and Administrator with capability "upload_files") (optional).', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Sitemap support for popular SEO/Sitemap plugins.', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Auto Anonymize timer (optional).', 'gwolle-gb') . '</li>
		<li>' . esc_html__('Auto Delete timer (optional).', 'gwolle-gb') . '</li>
	</ul>';
}


/*
 * Metabox with the link to the webshop for the add-on.
 */
function gwolle_gb_addon_buy() {
	?>
	<h3><?php esc_html_e('Buy the Add-On.', 'gwolle-gb'); ?></h3>
	<p><?php
		$link = '<a href="https://zenoweb.nl/downloads/gwolle-guestbook-add-on/" target="_blank">';
		/* translators: %s is a link */
		echo sprintf( esc_html__( 'You can buy this add-on at the %sZenoWeb Webshop%s for only %s Euro.', 'gwolle-gb' ), $link, '</a>', '15' ); ?><br />
		<?php
		$link = '<a href="https://zenoweb.nl/forums/forum/guestbook-add-on/" target="_blank">';
		/* translators: %s is a link */
		echo sprintf( esc_html__('Support for the add-on is also at the %sZenoWeb Support Forum%s.', 'gwolle-gb'), $link, '</a>' ); ?>
	</p>
	<?php
}


/*
 * Metabox with links to the demo site.
 */
function gwolle_gb_addon_demo() {
	?>
	<h3><?php esc_html_e('Demo with the Add-On.', 'gwolle-gb'); ?></h3>
	<p><?php
		$link = '<a href="https://demo.zenoweb.nl/wordpress-plugins/gwolle-guestbook-the-add-on/" target="_blank">';
		/* translators: %s is a link */
		echo sprintf( esc_html__( 'The demo site of this plugin is available with the add-on enabled at %sthe demo site%s.', 'gwolle-gb' ), $link, '</a>' ); ?>
	</p>

	<h3><?php esc_html_e('Demo without the Add-On.', 'gwolle-gb'); ?></h3>
	<p><?php
		$link = '<a href="https://demo.zenoweb.nl/wordpress-plugins/gwolle-gb/" target="_blank">';
		/* translators: %s is a link */
		echo sprintf( esc_html__( 'For comparison you can also see the %sdemo site without the add-on%s enabled.', 'gwolle-gb' ), $link, '</a>' ); ?>
	</p>
	<?php
}


/*
 * Metabox with the motivational text of the add-on.
 */
function gwolle_gb_addon_development() {
	?>
	<p><?php
		esc_html_e('Over the last few years I put about 1000+ hours into this Guestbook plugin in development and support.', 'gwolle-gb'); echo '<br />'; echo '<br />';
		esc_html_e('It is just a hobby for me and even if it is not about the money, I still enjoy seeing some money in return for my work.', 'gwolle-gb'); echo '<br />'; echo '<br />';
		esc_html_e('If you buy this Add-On, that will encourage me and does add motivation to keep maintaining and supporting this guestbook software. It is a good way to support me and my work.', 'gwolle-gb');
		?>
	</p>
	<?php
}
