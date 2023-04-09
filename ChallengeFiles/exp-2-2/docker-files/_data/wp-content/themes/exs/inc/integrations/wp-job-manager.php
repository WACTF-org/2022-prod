<?php
/**
 * WP Job Manager support
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


//add sidebar position option for product and shop
add_filter( 'exs_customizer_options', 'exs_filter_exs_customizer_options_wpjm' );
if ( ! function_exists( 'exs_filter_exs_customizer_options_wpjm' ) ) :
	function exs_filter_exs_customizer_options_wpjm( $options ) {
		//sections
		$options['section_exs_wpjm'] = array(
			'type'        => 'section',
			'panel'       => 'panel_theme',
			'label'       => esc_html__( 'ExS WP Job Manager Options', 'exs' ),
			'description' => esc_html__( 'These options let you manage your theme WP Job Manager settings.', 'exs' ),
		);

		//options
		//sidebars
		$options['wpjm_sidebar_position'] = array(
			'type'        => 'radio',
			'section'     => 'section_exs_wpjm',
			'default'     => exs_option( 'wpjm_sidebar_position', 'right' ),
			'label'       => esc_html__( 'WP Job Manager pages sidebar position', 'exs' ),
			'description' => esc_html__( 'This option let you manage sidebar position on the WP Job Manager pages.', 'exs' ),
			'choices'     => exs_get_sidebar_position_options(),
		);
		//container width
		$options['wpjm_container_width'] = array(
			'type'    => 'radio',
			'section' => 'section_exs_wpjm',
			'label'   => esc_html__( 'WP Job Manager pages container max width', 'exs' ),
			'default' => esc_html( exs_option( 'wpjm_container_width', '' ) ),
			'choices' => array(
				''     => esc_html__( 'Inherit from Global', 'exs' ),
				'1400' => esc_html__( '1400px', 'exs' ),
				'1140' => esc_html__( '1140px', 'exs' ),
				'960'  => esc_html__( '960px', 'exs' ),
				'720'  => esc_html__( '720px', 'exs' ),
			),
		);

		return $options;
	}
endif;

/**
 * Enqueue scripts and styles for search page.
 */
if ( ! function_exists( 'exs_enqueue_static_wpjb' ) ) :
	function exs_enqueue_static_wpjb( $return ) {
		if( is_search() ) {
			$return = true;
		}
		return $return;
	}
endif;
add_filter( 'job_manager_enqueue_frontend_style', 'exs_enqueue_static_wpjb' );
