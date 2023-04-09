<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme ExS for publication on ThemeForest
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Plugin:
 * require_once dirname( __FILE__ ) . '/path/to/class-tgm-plugin-activation.php';
 */

require_once EXS_THEME_PATH . '/inc/tgm-plugin-activation/class-tgm-plugin-activation.php';

//required plugins arrays - default and additional for different demos
if ( ! function_exists( 'exs_get_required_plugins_array' ) ) :
	function exs_get_required_plugins_array( $exs_index = 'default', $exs_all = false, $exs_all_flat = false ) {
		$exs_required_plugins_array = apply_filters(
			'exs_required_plugins_array',
			array(
				//Following plugins are required for all demo contents:
				'default' => array(
					array(
						'name'        => esc_html__( esc_html__( 'WordPress SEO by Yoast', 'exs' ), 'exs' ),
						'slug'        => 'wordpress-seo',
						'is_callable' => 'wpseo_init',
					),
					array(
						'name'     => esc_html__( 'ExS Widgets', 'exs' ),
						'slug'     => 'exs-widgets',
					),
					array(
						'name'     => esc_html__( 'Advanced Import: ExS Theme Demo Contents', 'exs' ),
						'slug'     => 'advanced-import',
					),
				),
			)
		);
		if ( ! empty( $exs_all_flat ) ) {
			$exs_required_plugins_array_all = array();
			foreach ( $exs_required_plugins_array as $key => $plugins ) {
				foreach ( $plugins as $plugin ) {
					$exs_required_plugins_array_all[ $plugin['slug'] ] = $plugin;
				}
			}

			return $exs_required_plugins_array_all;
		} elseif ( ! empty( $exs_all ) ) {
			return $exs_required_plugins_array;
		} else {
			return $exs_required_plugins_array[ $exs_index ];
		}
	}
endif; //exs_get_required_plugins_array

add_action( 'tgmpa_register', 'exs_register_required_plugins' );
if ( ! function_exists( 'exs_register_required_plugins' ) ) :
	/**
	 * Register the required plugins for this theme.
	 *
	 * The variables passed to the `tgmpa()` function should be:
	 * - an array of plugin arrays;
	 * - optionally a configuration array.
	 * If you are not changing anything in the configuration array, you can remove the array and remove the
	 * variable from the function call: `tgmpa( $exs_plugins );`.
	 * In that case, the TGMPA default settings will be used.
	 *
	 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
	 */
	function exs_register_required_plugins() {
		/*
		* Array of plugin arrays. Required keys are name and slug.
		* If the source is NOT from the .org repo, then source is also required.
		*/
		//we need this to install different plugins for different demos
		if ( ! empty( $_POST['exs_all_plugins'] ) ) {
			$exs_plugins = exs_get_required_plugins_array( '', false, true );
		} else {
			$exs_plugins = exs_get_required_plugins_array();
		}
		tgmpa(
			$exs_plugins,
			array(
				'domain'       => 'exs',
				'dismissable'  => true,
				'is_automatic' => false,
			)
		);
	}
endif;

//prevent redirects
remove_action( 'bp_admin_init', 'bp_do_activation_redirect', 1 );

///////////////////
//ADVANCED IMPORT//
///////////////////
if ( ! function_exists( 'exs_demo_import_filter_post_ids' ) ) :
	function exs_demo_import_filter_post_ids( $ids ){
		//reusable block
		$ids[] = 'block_id';
		return $ids;
	}
endif;
add_filter( 'advanced_import_replace_post_ids', 'exs_demo_import_filter_post_ids' );
if ( ! function_exists( 'exs_demo_import_filter_term_ids' ) ) :
	function exs_demo_import_filter_term_ids( $ids ){
		//exs widgets
		$ids[] = 'cat';
		$ids[] = 'category';
		return $ids;
	}
endif;
add_filter( 'advanced_import_replace_term_ids', 'exs_demo_import_filter_term_ids' );

if ( EXS_WP ) {
	require_once EXS_THEME_PATH . '/inc/wp/plugins.php';
}
if ( EXS_TM ) {
	require_once EXS_THEME_PATH . '/inc/tm/plugins.php';
}
