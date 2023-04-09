<?php
/**
 * bbPress support
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//add inline CSS styles
add_filter( 'exs_root_colors_inline_styles_string', 'exs_filter_exs_root_colors_inline_styles_string' );
if ( ! function_exists( 'exs_filter_exs_root_colors_inline_styles_string' ) ) :
	function exs_filter_exs_root_colors_inline_styles_string( $string ) {
		$string .= '--bbpressColorNoticeBackground:' . sanitize_hex_color( exs_option( 'bbpressColorNoticeBackground', '#ffffe0' ) ) . ';';
		$string .= '--bbpressColorNoticeBorder:' . sanitize_hex_color( exs_option( 'bbpressColorNoticeBorder', '#e6db55' ) ) . ';';
		$string .= '--bbpressColorNoticeColor:' . sanitize_hex_color( exs_option( 'bbpressColorNoticeColor', '#000000' ) ) . ';';
		$string .= '--bbpressColorInfoBackground:' . sanitize_hex_color( exs_option( 'bbpressColorInfoBackground', '#f0f8ff' ) ) . ';';
		$string .= '--bbpressColorInfoBorder:' . sanitize_hex_color( exs_option( 'bbpressColorInfoBorder', '#cee1ef' ) ) . ';';
		$string .= '--bbpressFontNormal:' . sanitize_title( exs_option( 'bbpress_font_size_normal', '15px' ) ) . ';';
		$string .= '--bbpressFontBig:' . sanitize_title( exs_option( 'bbpress_font_size_big', '18px' ) ) . ';';

		return $string;
	}
endif;

//check for remove breadcrumbs
add_filter( 'bbp_no_breadcrumb', 'exs_filter_bbp_no_breadcrumb' );
if ( ! function_exists( 'exs_filter_bbp_no_breadcrumb' ) ) :
	function exs_filter_bbp_no_breadcrumb() {
		$hide_bbpress_breadcrumbs = exs_option( 'bbpress_hide_breadcrumbs', false );
		return ! empty( $hide_bbpress_breadcrumbs );
	}
endif;

//add sidebar position option for product and shop
add_filter( 'exs_customizer_options', 'exs_filter_exs_customizer_options_bbpress' );
if ( ! function_exists( 'exs_filter_exs_customizer_options_bbpress' ) ) :
	function exs_filter_exs_customizer_options_bbpress( $options ) {
		//sections
		$options['section_exs_bbpress'] = array(
			'type'        => 'section',
			'panel'       => 'panel_theme',
			'label'       => esc_html__( 'bbPress Options', 'exs' ),
			'description' => esc_html__( 'These options let you manage sidebar positions on the forum pages.', 'exs' ),
		);

		//options
		//sidebars
		$options['bbpress_sidebar_position'] = array(
			'type'        => 'radio',
			'section'     => 'section_exs_bbpress',
			'default'     => exs_option( 'bbpress_sidebar_position', 'right' ),
			'label'       => esc_html__( 'bbPress sidebar position', 'exs' ),
			'description' => esc_html__( 'This option let you manage sidebar position on the bbPress forum pages.', 'exs' ),
			'choices'     => exs_get_sidebar_position_options(),
		);
		//container width
		$options['bbpress_container_width'] = array(
			'type'    => 'radio',
			'section' => 'section_exs_bbpress',
			'label'   => esc_html__( 'Forums container max width', 'exs' ),
			'default' => esc_html( exs_option( 'bbpress_container_width', '' ) ),
			'choices' => array(
				''     => esc_html__( 'Inherit from Global', 'exs' ),
				'1400' => esc_html__( '1400px', 'exs' ),
				'1140' => esc_html__( '1140px', 'exs' ),
				'960'  => esc_html__( '960px', 'exs' ),
				'720'  => esc_html__( '720px', 'exs' ),
			),
		);
		$options['bbpress_hide_breadcrumbs'] = array(
			'type'        => 'checkbox',
			'section'     => 'section_exs_bbpress',
			'label'       => esc_html__( 'Hide bbPress breadcrumbs', 'exs' ),
			'default'     => esc_html( exs_option( 'bbpress_hide_breadcrumbs', false ) ),
			'description' => esc_html__( 'If you have Yoast SEO or Rank Math plugins installed they will print breadcrumbs so you can hide default bbPress breadcrumbs', 'exs' ),
		);
		$options['bbpress_colors_heading'] = array(
			'type'        => 'block-heading',
			'section'     => 'section_exs_bbpress',
			'label'       => esc_html__( 'bbPress additional colors', 'exs' ),
			'description' => esc_html__( 'Set your custom colors for notices and sticky forums and topics.', 'exs' ),
		);
		$options['bbpressColorNoticeBackground'] = array(
			'type'        => 'color',
			'section'     => 'section_exs_bbpress',
			'label'       => esc_html__( 'Notice background color', 'exs' ),
			'default'     => esc_html( exs_option( 'bbpressColorNoticeBackground', '#ffffe0' ) ),
			'description' => esc_html__( 'Using as a background for notices and sticky forums', 'exs' ),
		);
		$options['bbpressColorNoticeBorder'] = array(
			'type'        => 'color',
			'section'     => 'section_exs_bbpress',
			'label'       => esc_html__( 'Notice border color', 'exs' ),
			'default'     => esc_html( exs_option( 'bbpressColorNoticeBorder', '#e6db55' ) ),
			'description' => esc_html__( 'Using as a border color for notices', 'exs' ),
		);
		$options['bbpressColorNoticeColor'] = array(
			'type'        => 'color',
			'section'     => 'section_exs_bbpress',
			'label'       => esc_html__( 'Notice text color', 'exs' ),
			'default'     => esc_html( exs_option( 'bbpressColorNoticeColor', '#000000' ) ),
			'description' => esc_html__( 'Using as a text color for notices', 'exs' ),
		);
		$options['bbpressColorInfoBackground'] = array(
			'type'        => 'color',
			'section'     => 'section_exs_bbpress',
			'label'       => esc_html__( 'Info background color', 'exs' ),
			'default'     => esc_html( exs_option( 'bbpressColorInfoBackground', '#f0f8ff' ) ),
			'description' => esc_html__( 'Using as a background for notices', 'exs' ),
		);
		$options['bbpressColorInfoBorder'] = array(
			'type'        => 'color',
			'section'     => 'section_exs_bbpress',
			'label'       => esc_html__( 'Info border color', 'exs' ),
			'default'     => esc_html( exs_option( 'bbpressColorInfoBorder', '#cee1ef' ) ),
			'description' => esc_html__( 'Using as a border color for notices', 'exs' ),
		);
		$options['bbpress_fonts_heading'] = array(
			'type'        => 'block-heading',
			'section'     => 'section_exs_bbpress',
			'label'       => esc_html__( 'bbPress font sizes', 'exs' ),
			'description' => esc_html__( 'Set your custom font sizes for forums and headings.', 'exs' ),
		);
		$options['bbpress_font_size_normal'] = array(
			'type'    => 'select',
			'section' => 'section_exs_bbpress',
			'label'   => esc_html__( 'Normal font size', 'exs' ),
			'default' => esc_html( exs_option( 'bbpress_font_size_normal', '15px' ) ),
			'choices' => array(
				'10px' => esc_html__( '10px', 'exs' ),
				'11px' => esc_html__( '11px', 'exs' ),
				'12px' => esc_html__( '12px', 'exs' ),
				'13px' => esc_html__( '13px', 'exs' ),
				'14px' => esc_html__( '14px', 'exs' ),
				'15px' => esc_html__( '15px', 'exs' ),
				'16px' => esc_html__( '16px', 'exs' ),
				'17px' => esc_html__( '17px', 'exs' ),
				'18px' => esc_html__( '18px', 'exs' ),
				'19px' => esc_html__( '19px', 'exs' ),
				'20px' => esc_html__( '20px', 'exs' ),
				'21px' => esc_html__( '21px', 'exs' ),
				'22px' => esc_html__( '22px', 'exs' ),
			),
		);
		$options['bbpress_font_size_big'] = array(
			'type'    => 'select',
			'section' => 'section_exs_bbpress',
			'label'   => esc_html__( 'Big font size', 'exs' ),
			'default' => esc_html( exs_option( 'bbpress_font_size_big', '18px' ) ),
			'choices' => array(
				'10px' => esc_html__( '10px', 'exs' ),
				'11px' => esc_html__( '11px', 'exs' ),
				'12px' => esc_html__( '12px', 'exs' ),
				'13px' => esc_html__( '13px', 'exs' ),
				'14px' => esc_html__( '14px', 'exs' ),
				'15px' => esc_html__( '15px', 'exs' ),
				'16px' => esc_html__( '16px', 'exs' ),
				'17px' => esc_html__( '17px', 'exs' ),
				'18px' => esc_html__( '18px', 'exs' ),
				'19px' => esc_html__( '19px', 'exs' ),
				'20px' => esc_html__( '20px', 'exs' ),
				'21px' => esc_html__( '21px', 'exs' ),
				'22px' => esc_html__( '22px', 'exs' ),
			),
		);

		return $options;
	}
endif;

//remove deprecated in WordPress 5.5 filters to prevent PHP error notices
remove_filter( 'bbp_get_reply_content', 'wp_make_content_images_responsive', 60 );
remove_filter( 'bbp_get_topic_content', 'wp_make_content_images_responsive', 60 );
