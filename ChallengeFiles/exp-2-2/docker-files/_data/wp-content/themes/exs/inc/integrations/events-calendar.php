<?php
/**
 * Events Calendar support
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//add sidebar position option for product and shop
add_filter( 'exs_customizer_options', 'exs_filter_exs_customizer_events_calendar_options' );
if ( ! function_exists( 'exs_filter_exs_customizer_events_calendar_options' ) ) :
	function exs_filter_exs_customizer_events_calendar_options( $options ) {
		//sections
		$options['section_exs_events_layout']   = array(
			'type'        => 'section',
			'panel'       => 'tribe_customizer',
			'label'       => esc_html__( 'ExS Events Layout', 'exs' ),
			'description' => esc_html__( 'These options let you manage layouts for the Events and Single Event pages.', 'exs' ),
		);

		//options
		//Events Feed
		$options['events_feed_options_heading'] = array(
			'type'        => 'block-heading',
			'section'     => 'section_exs_events_layout',
			'label'       => esc_html__( 'Events Feed options', 'exs' ),
			'description' => esc_html__( 'You can customize your events feed by changing layout, container width and sidebar position.', 'exs' ),
		);
		$options['events_feed_layout']                               = array(
			'type'    => 'select',
			'section' => 'section_exs_events_layout',
			'label'   => esc_html__( 'Events Feed Layout', 'exs' ),
			'default' => esc_html( exs_option( 'section_exs_events_layout', '' ) ),
			'choices' => array(
				''  => esc_html__( 'Default', 'exs' ),
				'events-layout-top-image' => esc_html__( 'Top Image', 'exs' ),
			),
		);
		$options['events_sidebar_position'] = array(
			'type'        => 'radio',
			'section'     => 'section_exs_events_layout',
			'default'     => exs_option( 'events_sidebar_position', 'right' ),
			'label'       => esc_html__( 'Events feed sidebar position', 'exs' ),
			'description' => esc_html__( 'This option let you manage sidebar position on the events page.', 'exs' ),
			'choices'     => exs_get_sidebar_position_options(),
		);

		$options['events_container_width']   = array(
			'type'    => 'radio',
			'section' => 'section_exs_events_layout',
			'label'   => esc_html__( 'Events feed container max width', 'exs' ),
			'default' => esc_html( exs_option( 'events_container_width', '' ) ),
			'choices' => array(
				''     => esc_html__( 'Inherit from Global', 'exs' ),
				'1400' => esc_html__( '1400px', 'exs' ),
				'1140' => esc_html__( '1140px', 'exs' ),
				'960'  => esc_html__( '960px', 'exs' ),
				'720'  => esc_html__( '720px', 'exs' ),
			),
		);

		//Single Event
		$options['event_single_options_heading'] = array(
			'type'        => 'block-heading',
			'section'     => 'section_exs_events_layout',
			'label'       => esc_html__( 'Single Event options', 'exs' ),
			'description' => esc_html__( 'You can customize your single event by changing container width and sidebar position.', 'exs' ),
		);
		$options['event_sidebar_position'] = array(
			'type'        => 'radio',
			'section'     => 'section_exs_events_layout',
			'default'     => exs_option( 'event_sidebar_position', 'right' ),
			'label'       => esc_html__( 'Single Event sidebar position', 'exs' ),
			'description' => esc_html__( 'This option let you manage sidebar position on event pages.', 'exs' ),
			'choices'     => exs_get_sidebar_position_options(),
		);

		$options['event_container_width']   = array(
			'type'    => 'radio',
			'section' => 'section_exs_events_layout',
			'label'   => esc_html__( 'Single Event container max width', 'exs' ),
			'default' => esc_html( exs_option( 'event_container_width', '' ) ),
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

if ( ! function_exists( 'exs_events_body_classes' ) ) :
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $exs_classes Classes for the body element.
	 *
	 * @return array
	 */
	function exs_events_body_classes( $exs_classes ) {
		if ( exs_is_events() ) {

			if ( is_singular() ) {
				if ( exs_option( 'title_show_title' ) ) {
					$exs_classes[] = 'event-title-hidden';
				}
			}

			$exs_classes[] = esc_attr( exs_option( 'events_feed_layout' ) );
		}

		return $exs_classes;
	}
endif;
add_filter( 'body_class', 'exs_events_body_classes' );
