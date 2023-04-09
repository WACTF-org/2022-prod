<?php
/**
 * Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

defined( 'EXS_THEME_VERSION' ) || define( 'EXS_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

//https://developer.wordpress.org/themes/basics/linking-theme-files-directories/#linking-to-theme-directories
defined( 'EXS_THEME_URI' ) || define( 'EXS_THEME_URI', get_parent_theme_file_uri() );
defined( 'EXS_THEME_PATH' ) || define( 'EXS_THEME_PATH', get_parent_theme_file_path() );

//THEME MAIN CLASS
require_once EXS_THEME_PATH . '/inc/exs.php';

//THEME SETUP
//theme support
//image sizes
//register menus
//register sidebars
require_once EXS_THEME_PATH . '/inc/setup.php';

//THEME OPTIONS helpers and default options
require_once EXS_THEME_PATH . '/inc/options.php';

//STATIC ASSETS
require_once EXS_THEME_PATH . '/inc/static.php';

//HTML OUTPUT FILTERS
require_once EXS_THEME_PATH . '/inc/output-filters.php';

//WooCommerce support
if ( class_exists( 'WooCommerce' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/woocommerce.php';
}
//EDD support
if ( class_exists( 'Easy_Digital_Downloads' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/edd.php';
}
//bbPress support
if ( class_exists( 'bbPress' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/bbpress.php';
}
//BuddyPress support
if ( class_exists( 'BuddyPress' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/buddypress.php';
}
//WP Job Manager support
if ( class_exists( 'WP_Job_Manager' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/wp-job-manager.php';
}
//Post Views Counter
if ( class_exists( 'Post_Views_Counter' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/post-views-counter.php';
}

//Posts Like Dislike
if ( class_exists( 'PLD_Comments_like_dislike' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/posts-like-dislike.php';
}

//Comments Like Dislike
if ( class_exists( 'CLD_Comments_like_dislike' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/comments-like-dislike.php';
}

//Events Calendar
if ( class_exists( 'Tribe__Events__Main' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/events-calendar.php';
}

//LearnPress
if ( class_exists( 'LearnPress' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/learnpress.php';
}

//ACF
if ( class_exists( 'ACF' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/acf.php';
}

//WP Optimize
if ( class_exists( 'WP_Optimize' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/wp-optimize.php';
}

//Classic Editor
if ( class_exists( 'Classic_Editor' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/classic-editor.php';
}

//AMP
if ( defined( 'AMP__VERSION' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/amp.php';
}

if ( EXS_EXTRA ) {
	if ( EXS_FR ) {
		if ( ! function_exists( 'exs_fs' ) ) {
			// Create a helper function for easy SDK access.
			function exs_fs() {

				global $exs_fs;

				if ( ! isset( $exs_fs ) ) {
					require_once EXS_THEME_PATH . '/freemius/start.php';

					$exs_fs = fs_dynamic_init( array(
						'id'                  => '6216',
						'slug'                => 'exs',
						'premium_slug'        => 'exs-pro',
						'type'                => 'theme',
						'public_key'          => 'pk_7520d603913d7eb6187fc471f4ac7',
						'is_premium'          => true,
						'premium_suffix'      => 'Pro',
						'has_premium_version' => true,
						'has_addons'          => false,
						'has_paid_plans'      => true,
						'menu'                => array(
							'slug'           => 'exs-theme',
							'parent'         => array(
								'slug' => 'themes.php',
							),
						),
					) );
				}

				return $exs_fs;
			}

			exs_fs();
			do_action( 'exs_fs_loaded' );
		}
	}
	require_once EXS_THEME_PATH . '/extra/functions.php';
}

//TEMPLATE HELPERS
require_once EXS_THEME_PATH . '/inc/template-helpers.php';

//only for admin
if ( is_admin() ) {
	//TGM plugin activation and demo-content
	require_once EXS_THEME_PATH . '/inc/tgm-plugin-activation/plugins.php';

	if ( function_exists( 'register_block_pattern_category' ) ) :
		require_once EXS_THEME_PATH . '/inc/block-patterns.php';
	endif;
//only for frontend
} else {
	//since 2.0.2 - post reading time
	require_once EXS_THEME_PATH . '/inc/integrations/reading-time.php';
}

//only for customizer
if ( is_customize_preview() || EXS_DEV_MODE ) {

	//CUSTOMIZER INIT
	require_once EXS_THEME_PATH . '/inc/customizer.php';

}

if ( EXS_DEV_MODE ) :
	require_once EXS_THEME_PATH . '/dev/extensions/functions.php';
endif;

//only if our fields plugin not activated and if is_admin
if ( is_admin() && ! class_exists( 'Exs_Fields_Taxonomy' ) && EXS_EXTRA ) {
	require_once EXS_THEME_PATH . '/extra/taxonomy-options/class-exs-fields.php';
	require_once EXS_THEME_PATH . '/extra/taxonomy-options/class-exs-fields-taxonomy.php';
}
