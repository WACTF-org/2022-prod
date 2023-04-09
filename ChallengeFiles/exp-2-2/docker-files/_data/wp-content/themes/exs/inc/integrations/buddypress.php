<?php
/**
 * BuddyPress support
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


//add sidebar position option for product and shop
add_filter( 'exs_customizer_options', 'exs_filter_exs_customizer_options_buddypress' );
if ( ! function_exists( 'exs_filter_exs_customizer_options_buddypress' ) ) :
	function exs_filter_exs_customizer_options_buddypress( $options ) {
		//sections
		$options['section_exs_buddypress'] = array(
			'type'        => 'section',
			'panel'       => 'bp_nouveau_panel',
			'label'       => esc_html__( 'ExS BuddyPress Options', 'exs' ),
			'description' => esc_html__( 'These options let you manage your theme BuddyPress settings.', 'exs' ),
		);

		//options
		//sidebars
		$options['buddypress_sidebar_position'] = array(
			'type'        => 'radio',
			'section'     => 'section_exs_buddypress',
			'default'     => exs_option( 'buddypress_sidebar_position', 'right' ),
			'label'       => esc_html__( 'BuddyPress pages sidebar position', 'exs' ),
			'description' => esc_html__( 'This option let you manage sidebar position on the BuddyPress pages.', 'exs' ),
			'choices'     => exs_get_sidebar_position_options(),
		);
		//container width
		$options['buddypress_container_width'] = array(
			'type'    => 'radio',
			'section' => 'section_exs_buddypress',
			'label'   => esc_html__( 'BuddyPress pages container max width', 'exs' ),
			'default' => esc_html( exs_option( 'buddypress_container_width', '' ) ),
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