<?php
/*
Plugin Name: Gwolle Guestbook
Plugin URI: https://wordpress.org/plugins/gwolle-gb/
Description: Gwolle Guestbook is not just another guestbook for WordPress. The goal is to provide an easy and slim way to integrate a guestbook into your WordPress powered site. Don't use your 'comment' section the wrong way - install Gwolle Guestbook and have a real guestbook.
Version: 4.3.0
Author: Marcel Pol
Author URI: https://zenoweb.nl
License: GPLv2 or later
Text Domain: gwolle-gb
Domain Path: /lang/


Copyright 2009 - 2010  Wolfgang Timme  (https://github.com/wtimme)
Copyright 2014 - 2022  Marcel Pol      (marcel@timelord.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Plugin Version
define('GWOLLE_GB_VER', '4.3.0');


/*
 * Todo List:
 *
 * - Entries Admin page, make columns sortable, add order parameters to get* functions.
 * - On Page editor, have a postbox with link to the guestbook admin entries.
 * - BBcode: add width and height to images.
 * - Add Filter for get_entry_count SQL, like get_entries.
 * - Add filters similar to pre_get_posts.
 * - Add proper docblocks to filters in the code.
 * - Consider a functions/list-view.php refactoring.
 * - Consider SQL IN when emptying spam/trash.
 * - Someday, do something with the REST API. Someday.
 * - Unify statuses in one status column like WP_Posts.
 *   Add status 'private', only visible for author and moderators.
 *   Add status 'revision' to support that too. Add metabox to editor to restore old revision.
 * - More smooth import from third parties.
 * - Test and possibly add support for Gutenberg editor (shortcode block and widgets).
 * - Support sticky entries.
 *   - https://wordpress.org/support/topic/pin-entries/
 * - Support mark-as-ham for Stop Forum Spam. Needs API key.
 * - Do something to have less database queries for meta fields in add-on, especially export:
 *   - Use foreign keys for add-on, set meta var (add function). Test with frontend and export and isam db-engine.
 *   - Use foreign keys through a hook with SQL, and add a setter for meta.
 *   - Or add function to prepopulate metas for export.
 * - Support rewrite API for single entry and for paging. Look at bbPress.
 *   - https://wordpress.org/support/topic/search-engine-friendly-url-2/
 * - Use select2 or similar for subscribe/unsubcribe dropdowns.
 * - On one-page-design, paging doesn't work correctly in Chrome:
 *   - https://wordpress.org/support/topic/problem-of-text-selection-in-gwolle-guestbook/
 *   - https://wordpress.org/support/topic/gwolle-and-onepager/
 *   - https://wordpress.org/support/topic/ajax-reload-on-entry-pagination/
 *   - Using AJAX for pagination would solve this.
 * - Use more CSS transitions and SVG icons.
 *   https://stackoverflow.com/questions/15979235/css-transition-show-div-smoothly
 * - Use CSS classes from contactform plugins, when they are active.
 * - Have custom capability through a filter instead of `moderate_comments`.
 *   - https://wordpress.org/support/topic/user-roles-changing/
 * - Use new capability options in WP_User_Query in WP 5.9.
 *   - https://make.wordpress.org/core/2022/01/05/new-capability-queries-in-wordpress-5-9/
 * - Automatically convert links and images to bbcode after cut-and-paste into the textarea.
 *   - https://zenoweb.nl/forums/topic/automatic-link-creation/
 * - Redo the "visitor from city wrote on datetime" into better translatable text with placeholders.
 * - Editor: really fix timezone issues, new entry should follow given options and data.
 *   https://wordpress.org/support/topic/problem-with-message-via-backend-timezone-and-editing-details/
 *
 */


/*
 * Definitions
 */
define('GWOLLE_GB_FOLDER', plugin_basename(dirname(__FILE__)));
define('GWOLLE_GB_DIR', WP_PLUGIN_DIR . '/' . GWOLLE_GB_FOLDER);
define('GWOLLE_GB_URL', plugins_url( '/', __FILE__ ));


global $wpdb;

// Declare database table names
$wpdb->gwolle_gb_entries = $wpdb->prefix . 'gwolle_gb_entries';
$wpdb->gwolle_gb_log = $wpdb->prefix . 'gwolle_gb_log';


// Classes
require_once GWOLLE_GB_DIR . '/functions/gb-class-entry.php';

// Functions for the frontend
require_once GWOLLE_GB_DIR . '/frontend/gb-ajax-infinite-scroll.php';
require_once GWOLLE_GB_DIR . '/frontend/gb-blocklist.php';
require_once GWOLLE_GB_DIR . '/frontend/gb-form.php';
require_once GWOLLE_GB_DIR . '/frontend/gb-form-ajax.php';
require_once GWOLLE_GB_DIR . '/frontend/gb-form-posthandling.php';
require_once GWOLLE_GB_DIR . '/frontend/gb-shortcode-widget.php';
require_once GWOLLE_GB_DIR . '/frontend/gb-shortcodes.php';
require_once GWOLLE_GB_DIR . '/frontend/gb-pagination.php';
require_once GWOLLE_GB_DIR . '/frontend/gb-read.php';
require_once GWOLLE_GB_DIR . '/frontend/gb-rss.php';
require_once GWOLLE_GB_DIR . '/frontend/gb-total.php';
require_once GWOLLE_GB_DIR . '/frontend/gb-widget.php';
require_once GWOLLE_GB_DIR . '/frontend/gb-widget-search.php';

// Functions and pages for the backend
if ( is_admin() ) {
	require_once GWOLLE_GB_DIR . '/admin/gb-ajax-management.php';
	require_once GWOLLE_GB_DIR . '/admin/gb-dashboard-widget.php';
	require_once GWOLLE_GB_DIR . '/admin/gb-page-add-on.php';
	require_once GWOLLE_GB_DIR . '/admin/gb-page-editor.php';
	require_once GWOLLE_GB_DIR . '/admin/gb-page-entries.php';
	require_once GWOLLE_GB_DIR . '/admin/gb-page-export.php';
	require_once GWOLLE_GB_DIR . '/admin/gb-page-gwolle-gb.php';
	require_once GWOLLE_GB_DIR . '/admin/gb-page-import.php';
	require_once GWOLLE_GB_DIR . '/admin/gb-page-settings.php';
	require_once GWOLLE_GB_DIR . '/admin/gb-pagination.php';
	require_once GWOLLE_GB_DIR . '/admin/gwolle-gb-hooks.php';
}
require_once GWOLLE_GB_DIR . '/admin/gb-upgrade.php';

// Tabs for gb-page-settings.php
if ( is_admin() ) {
	require_once GWOLLE_GB_DIR . '/admin/tabs/gb-formtab.php';
	require_once GWOLLE_GB_DIR . '/admin/tabs/gb-readingtab.php';
	require_once GWOLLE_GB_DIR . '/admin/tabs/gb-admintab.php';
	require_once GWOLLE_GB_DIR . '/admin/tabs/gb-antispamtab.php';
	require_once GWOLLE_GB_DIR . '/admin/tabs/gb-emailtab.php';
	require_once GWOLLE_GB_DIR . '/admin/tabs/gb-debugtab.php';
	require_once GWOLLE_GB_DIR . '/admin/tabs/gb-uninstalltab.php';
}

// General Functions
require_once GWOLLE_GB_DIR . '/functions/gb-akismet.php';
require_once GWOLLE_GB_DIR . '/functions/gb-bbcode_emoji.php';
require_once GWOLLE_GB_DIR . '/functions/gb-book_id.php';
require_once GWOLLE_GB_DIR . '/functions/gb-cache.php';
require_once GWOLLE_GB_DIR . '/functions/gb-debug.php';
require_once GWOLLE_GB_DIR . '/functions/gb-fields.php';
require_once GWOLLE_GB_DIR . '/functions/gb-formatting.php';
require_once GWOLLE_GB_DIR . '/functions/gb-get_entries.php';
require_once GWOLLE_GB_DIR . '/functions/gb-get_entries_from_search.php';
require_once GWOLLE_GB_DIR . '/functions/gb-log.php';
require_once GWOLLE_GB_DIR . '/functions/gb-mail.php';
require_once GWOLLE_GB_DIR . '/functions/gb-messages.php';
require_once GWOLLE_GB_DIR . '/functions/gb-metabox.php';
require_once GWOLLE_GB_DIR . '/functions/gb-post-meta.php';
require_once GWOLLE_GB_DIR . '/functions/gb-privacy.php';
require_once GWOLLE_GB_DIR . '/functions/gb-settings.php';
require_once GWOLLE_GB_DIR . '/functions/gb-single-view.php';
require_once GWOLLE_GB_DIR . '/functions/gb-stop-forum-spam.php';
require_once GWOLLE_GB_DIR . '/functions/gb-user.php';
require_once GWOLLE_GB_DIR . '/functions/gb-user-ip.php';

// General Hooks
require_once GWOLLE_GB_DIR . '/gwolle-gb-hooks.php';


/*
 * Trigger an install/upgrade function when the plugin is activated.
 */
function gwolle_gb_activation( $networkwide ) {
	global $wpdb;

	$current_version = get_option( 'gwolle_gb_version' );

	if ( is_multisite() ) {
		$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		foreach ($blogids as $blog_id) {
			switch_to_blog($blog_id);
			if ( $current_version === false ) {
				gwolle_gb_install();
			} else if ($current_version !== GWOLLE_GB_VER) {
				gwolle_gb_upgrade();
			}
			restore_current_blog();
		}
	} else {
		if ( $current_version === false ) {
			gwolle_gb_install();
		} else if ($current_version !== GWOLLE_GB_VER) {
			gwolle_gb_upgrade();
		}
	}
}
register_activation_hook(__FILE__, 'gwolle_gb_activation');
