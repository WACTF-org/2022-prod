<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once EXS_THEME_PATH . '/inc/customizer/class-exs-block-extra-button-control.php';
require_once EXS_THEME_PATH . '/inc/customizer/class-exs-block-heading-control.php';
require_once EXS_THEME_PATH . '/inc/customizer/class-exs-slider-control.php';
require_once EXS_THEME_PATH . '/inc/customizer/class-exs-dropdown-category-control.php';
require_once EXS_THEME_PATH . '/inc/customizer/class-exs-hidden-customize-control.php';
require_once EXS_THEME_PATH . '/inc/customizer/class-exs-color-radio-control.php';
require_once EXS_THEME_PATH . '/inc/customizer/class-exs-image-radio-control.php';
require_once EXS_THEME_PATH . '/inc/customizer/class-exs-customizer.php';
require_once EXS_THEME_PATH . '/inc/customizer/customizer-woocommerce.php';
require_once EXS_THEME_PATH . '/inc/customizer/customizer-helpers.php';

//customizer theme settings
if ( ! function_exists( 'exs_customizer_settings_array' ) ) :
	function exs_customizer_settings_array() {
		return apply_filters(
			'exs_customizer_options',
			//panels -> sections -> settings
			array(
				//////////////////////
				//registering panels//
				//////////////////////
				'panel_theme'                           => array(
					'type'            => 'panel',
					'label'           => esc_html__( 'ExS Theme options', 'exs' ),
					'description'     => esc_html__( 'Theme specific options', 'exs' ),
					'priority'        => 130,
					'active_callback' => '',
				),
				'panel_colors_inverse'                  => array(
					'type'            => 'section',
					'label'           => esc_html__( 'Colors Inverse', 'exs' ),
					'description'     => esc_html__( 'Inverse color scheme', 'exs' ),
					'priority'        => 55,
					'active_callback' => '',
				),
				'panel_bottom_image'                  => array(
					'type'            => 'section',
					'label'           => esc_html__( 'Bottom Image', 'exs' ),
					'description'     => esc_html__( 'Site bottom background image. This image will be displayed as a background for the Top Footer Section, Footer and Copyright sections', 'exs' ),
					'priority'        => 75,
					'active_callback' => '',
				),
				////////////////////////
				//registering sections//
				////////////////////////
				/*
				make sure that you have appropriate panel registered above
				otherwise do not pass 'panel' key
				*/
				//global settings preset. It will change multiple options over 'Theme options' panel
				'section_presets'                       => array(
					'type'            => 'section',
					'panel'           => 'panel_theme',
					'label'           => esc_html__( 'Theme Presets', 'exs' ),
					'description'     => esc_html__( 'Customizer options presets. Will change multiple options over \'Theme options\' Customizer panel', 'exs' ),
					'priority'        => 100,
					'active_callback' => '__return_false',
				),
				'section_skins'                         => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Theme Skins', 'exs' ),
					'description' => esc_html__( 'Change your theme skins CSS and manage your JS files', 'exs' ),
					'priority'    => 100,
				),
				'section_layout'                        => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Theme Layout', 'exs' ),
					'description' => esc_html__( 'Site layout options', 'exs' ),
					'priority'    => 100,
				),
				'section_buttons'                       => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Theme Buttons', 'exs' ),
					//'description' => esc_html__( 'Buttons options', 'exs' ),
					'priority'    => 100,
				),
				'section_meta'                          => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Theme Meta', 'exs' ),
					'description' => esc_html__( 'Email, phone, address etc. Appears in various template parts depending from choosen sections layout', 'exs' ),
					'priority'    => 100,
				),
				'section_side_nav'                      => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Side Menu', 'exs' ),
					'description' => esc_html__( 'Side menu options. Please add menu or widgets to \'Side Menu\' position to display side menu on your site', 'exs' ),
					'priority'    => 100,
				),
				'section_top_nav'                      => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Main Menu', 'exs' ),
					//'description' => esc_html__( 'Main menu display options', 'exs' ),
					'priority'    => 100,
				),
				'section_bottom_nav'                      => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Bottom Fixed Menu', 'exs' ),
					'description' => esc_html__( 'Will be shown only if menu is assigned to the "Bottom Fixed Menu" location', 'exs' ),
					'priority'    => 100,
				),
				'section_fixed_sidebar'                      => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Side Fixed Sidebar', 'exs' ),
					'description' => esc_html__( 'Will be shown only if widgets exists in the "Fixed Side Sidebar" widget area', 'exs' ),
					'priority'    => 100,
				),
				//template parts layout sections
				'section_header'                        => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Header Section', 'exs' ),
					'description' => esc_html__( 'Choose header options', 'exs' ),
					'priority'    => 100,
				),
				'section_header_bottom'                 => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Header Bottom Section', 'exs' ),
					'description' => esc_html__( 'Choose header bottom sectionƒh options', 'exs' ),
					'priority'    => 100,
				),
				'section_title'                         => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Title Section', 'exs' ),
					'description' => esc_html__( 'Choose title options. Yoast SEO plugin required for breadcrumbs', 'exs' ),
					'priority'    => 100,
				),
				'section_main'                          => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Main Section', 'exs' ),
					'description' => esc_html__( 'Choose main section options', 'exs' ),
					'priority'    => 100,
				),
				'section_footer_top'                    => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Top Footer Section', 'exs' ),
					'description' => esc_html__( 'Choose top footer section options', 'exs' ),
					'priority'    => 100,
				),
				'section_footer'                        => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Footer Section', 'exs' ),
					'description' => esc_html__( 'Choose footer options', 'exs' ),
					'priority'    => 100,
				),
				'section_copyright'                     => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Copyright Section', 'exs' ),
					'description' => esc_html__( 'Choose copyright options', 'exs' ),
					'priority'    => 100,
				),
				'section_infinite_loop'                 => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Infinite Loop', 'exs' ),
					'description' => esc_html__( 'Add Infinite Loop functionality to the Archive pages', 'exs' ),
					'priority'    => 100,
				),
				'section_icons'                         => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Theme Icons', 'exs' ),
					'description' => esc_html__( 'Select theme icons pack', 'exs' ),
					'priority'    => 100,
				),
				'section_share_buttons'                 => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Share Buttons', 'exs' ),
					'description' => esc_html__( 'Add share buttons to your pages, posts and archives', 'exs' ),
					'priority'    => 100,
				),
				'section_blog'                          => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Blog', 'exs' ),
					'description' => esc_html__( 'Blog display options', 'exs' ),
					'priority'    => 100,
				),
				'section_blog_post'                     => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Single Post', 'exs' ),
					'description' => esc_html__( 'Single post display options', 'exs' ),
					'priority'    => 100,
				),
				'section_readtime'                    => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Estimated Reading Time', 'exs' ),
					'description' => esc_html__( 'Posts estimated reading time options', 'exs' ),
					'priority'    => 100,
				),
				'section_search'                        => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Search Results', 'exs' ),
					'description' => esc_html__( 'Search results display options', 'exs' ),
					'priority'    => 100,
				),
				'section_404'                           => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( '404 page', 'exs' ),
					'description' => esc_html__( '404 page options', 'exs' ),
					'priority'    => 100,
				),
				'section_typography'                    => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Typography', 'exs' ),
					'description' => esc_html__( 'Set global typography settings', 'exs' ),
					'priority'    => 100,
				),
				'section_fonts'                         => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Google Fonts', 'exs' ),
					'description' => esc_html__( 'Choose Google fonts', 'exs' ),
					'priority'    => 100,
				),
				'section_performance'                         => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Performance and Privacy', 'exs' ),
					'description' => esc_html__( 'Managing 3rd party web services', 'exs' ),
					'priority'    => 100,
				),
				'section_special_categories'            => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Services, Portfolio, Team', 'exs' ),
					'description' => esc_html__( 'Choose separate categories for displaying Services, Portfolio, Team. They will be removed from regular blog displaying', 'exs' ),
					'priority'    => 100,
				),
				'section_animation'                     => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Animation', 'exs' ),
					'description' => esc_html__( 'You can select elements that you want to animate on your page', 'exs' ),
					'priority'    => 100,
				),
				'section_contact_messages'              => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Contact forms messages', 'exs' ),
					'description' => esc_html__( 'Set your messages for ExS ajax contact form patterns', 'exs' ),
					'priority'    => 100,
				),
				'section_mailchimp_messages'              => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'MailChimp forms settings', 'exs' ),
					'description' => esc_html__( 'Set your settings for ExS ajax MailChimp subscribe form block patterns', 'exs' ),
					'priority'    => 100,
				),
				'section_messages'                      => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Popup messages', 'exs' ),
					'description' => esc_html__( 'You can set popup messages that will appear at the top and at the bottom of your site', 'exs' ),
					'priority'    => 100,
				),
				'section_mouse_cursor'                      => array(
					'type'        => 'section',
					'panel'       => 'panel_theme',
					'label'       => esc_html__( 'Mouse cursor effect', 'exs' ),
					'description' => esc_html__( 'Set your mouse pointer effect options', 'exs' ),
					'priority'    => 100,
				),
				///////////////////////
				//registering options//
				///////////////////////
				/*
				make sure that you have registered appropriate section above
				or used default sections as 'section' key's value:
					'title_tagline' - Site Title & Tagline
					'colors' - Colors
					'header_image' - Header Image
					'background_image' - Background Image
					'nav' - Navigation
					'static_front_page' - Static Front Page
				*/
				/*
				available types:
					'checkbox'
					'color'
					'dropdown-pages'
					'file'
					'image'
					'radio'
					'select'
					'text'
					'textarea'
					'url'
					'dropdown-category' - our custom dropdown
					'block-heading' - our custom block heading
					'hidden-option' - our custom hidden option
				make sure that you have provide an array with 'choices' key for 'select' and 'radio':
					'choices' => array(
						'choice_1' => esc_html__( 'Choice 1', 'exs' ),
						...
					)
				*/
				//////////////////////
				//Hidden Demo Number//
				//////////////////////
				'demo_number'                           => array(
					'type'    => 'hidden-option',
					'section' => 'section_presets',
					'default' => esc_html( exs_option( 'demo_number', '' ) ),
				),
				//////////
				//colors//
				//////////
				//see _variables.scss
				//see options.php for defaults
				// colorLight
				// colorFont
				// colorFontMuted
				// colorBackground
				// colorBorder
				// colorDark
				// colorDarkMuted
				// colorMain
				// colorMain2
				'colors_palette_block_heading'                   => array(
					'type'        => 'block-heading',
					'section'     => 'colors',
					'label'       => esc_html__( 'Theme colors palette', 'exs' ),
					'description' => esc_html__( 'This color palette will be used all over the theme.', 'exs' ),
				),
				'colorLight'                            => array(
					'type'        => 'color',
					'section'     => 'colors',
					'label'       => esc_html__( 'Light color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorLight', '#ffffff' ) ),
					'description' => esc_html__( 'Using as a background for light sections and as a font color in inversed sections.', 'exs' ),
				),
				'colorFont'                             => array(
					'type'        => 'color',
					'section'     => 'colors',
					'label'       => esc_html__( 'Font color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorFont', '#555555' ) ),
					'description' => esc_html__( 'Using as a font color.', 'exs' ),
				),
				'colorFontMuted'                        => array(
					'type'        => 'color',
					'section'     => 'colors',
					'label'       => esc_html__( 'Font muted color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorFontMuted', '#666666' ) ),
					'description' => esc_html__( 'Using as a font muted color.', 'exs' ),
				),
				'colorBackground'                       => array(
					'type'        => 'color',
					'section'     => 'colors',
					'label'       => esc_html__( 'Light grey background color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorBackground', '#f7f7f7' ) ),
					'description' => esc_html__( 'Using as a light grey background.', 'exs' ),
				),
				'colorBorder'                           => array(
					'type'        => 'color',
					'section'     => 'colors',
					'label'       => esc_html__( 'Grey border color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorBorder', '#e1e1e1' ) ),
					'description' => esc_html__( 'Using as a border color.', 'exs' ),
				),
				'colorDark'                             => array(
					'type'        => 'color',
					'section'     => 'colors',
					'label'       => esc_html__( 'Dark color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorDark', '#444444' ) ),
					'description' => esc_html__( 'Using as a buttons color for light sections and as a background for inversed sections.', 'exs' ),
				),
				'colorDarkMuted'                        => array(
					'type'        => 'color',
					'section'     => 'colors',
					'label'       => esc_html__( 'Darker color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorDarkMuted', '#222222' ) ),
					'description' => esc_html__( 'Using as headings color and a background for darker inversed sections.', 'exs' ),
				),
				'colorMain'                             => array(
					'type'        => 'color',
					'section'     => 'colors',
					'label'       => esc_html__( 'Main accent color #1', 'exs' ),
					'default'     => esc_html( exs_option( 'colorMain', '#148BCC' ) ),
					'description' => esc_html__( 'Using as a main accent color.', 'exs' ),
				),
				'colorMain2'                            => array(
					'type'        => 'color',
					'section'     => 'colors',
					'label'       => esc_html__( 'Main accent color #2', 'exs' ),
					'default'     => esc_html( exs_option( 'colorMain2', '#428AB2' ) ),
					'description' => esc_html__( 'Using as a main accent second color.', 'exs' ),
				),
				'colorMain3'                            => array(
					'type'        => 'color',
					'section'     => 'colors',
					'label'       => esc_html__( 'Main accent color #3', 'exs' ),
					'default'     => esc_html( exs_option( 'colorMain3', '#428AB2' ) ),
					'description' => esc_html__( 'Using as a main accent third color.', 'exs' ),
				),
				'colorMain4'                            => array(
					'type'        => 'color',
					'section'     => 'colors',
					'label'       => esc_html__( 'Main accent color #4', 'exs' ),
					'default'     => esc_html( exs_option( 'colorMain4', '#428AB2' ) ),
					'description' => esc_html__( 'Using as a main accent fourth color.', 'exs' ),
				),
				'colors_theme_elements_block_heading'                   => array(
					'type'        => 'block-heading',
					'section'     => 'colors',
					'label'       => esc_html__( 'Theme elements colors', 'exs' ),
					'description' => esc_html__( 'Colors for theme elements can be chosen from the colors palette.', 'exs' ),
				),
				'color_meta_icons'                      => array(
					'type'    => 'select',
					'section' => 'colors',
					'label'   => esc_html__( 'Color for icons in the post meta', 'exs' ),
					'default' => esc_html( exs_option( 'color_meta_icons', '' ) ),
					'choices' => array(
						''                      => esc_html__( 'Default', 'exs' ),
						'meta-icons-main'       => esc_html__( 'Accent color', 'exs' ),
						'meta-icons-main2'      => esc_html__( 'Accent color 2', 'exs' ),
						'meta-icons-border'     => esc_html__( 'Borders color', 'exs' ),
						'meta-icons-dark'       => esc_html__( 'Dark color', 'exs' ),
						'meta-icons-dark-muted' => esc_html__( 'Darker color', 'exs' ),
					),
				),
				'color_meta_text'                      => array(
					'type'    => 'select',
					'section' => 'colors',
					'label'   => esc_html__( 'Color for text and links in the post meta', 'exs' ),
					'default' => esc_html( exs_option( 'color_meta_text', '' ) ),
					'choices' => array(
						''                     => esc_html__( 'Default', 'exs' ),
						'meta-text-main'       => esc_html__( 'Accent color', 'exs' ),
						'meta-text-main2'      => esc_html__( 'Accent color 2', 'exs' ),
						'meta-text-border'     => esc_html__( 'Borders color', 'exs' ),
						'meta-text-dark'       => esc_html__( 'Dark color', 'exs' ),
						'meta-text-dark-muted' => esc_html__( 'Darker color', 'exs' ),
					),
				),
				'color_links_menu'                      => array(
					'type'    => 'color-radio',
					'section' => 'colors',
					'label'   => esc_html__( 'Desktop main menu links color', 'exs' ),
					'default' => esc_html( exs_option( 'color_links_menu', '' ) ),
					'description' => esc_html__( 'Experimental. Using as a color of the main navigation links for desktop screen resolutions.', 'exs' ),
					'choices' => array(
						''                        => esc_html__( 'Inherit', 'exs' ),
						'l'                       => esc_html__( 'Light', 'exs' ),
						'l m'                     => esc_html__( 'Grey', 'exs' ),
						'i'                       => esc_html__( 'Dark', 'exs' ),
						'i m'                     => esc_html__( 'Darker', 'exs' ),
						'i c'                     => esc_html__( 'Accent color', 'exs' ),
						'i c c2'                  => esc_html__( 'Accent secondary color', 'exs' ),
						'i c c3'                  => esc_html__( 'Accent third color', 'exs' ),
						'i c c4'                  => esc_html__( 'Accent fourth color', 'exs' ),
					),
				),
				'color_links_menu_hover'                      => array(
					'type'    => 'color-radio',
					'section' => 'colors',
					'label'   => esc_html__( 'Desktop main menu links hover color', 'exs' ),
					'default' => esc_html( exs_option( 'color_links_menu_hover', '' ) ),
					'description' => esc_html__( 'Experimental. Using as a color of the main navigation links in the hover state for desktop screen resolutions.', 'exs' ),
					'choices' => array(
						''                        => esc_html__( 'Inherit', 'exs' ),
						'l'                       => esc_html__( 'Light', 'exs' ),
						'l m'                     => esc_html__( 'Grey', 'exs' ),
						'i'                       => esc_html__( 'Dark', 'exs' ),
						'i m'                     => esc_html__( 'Darker', 'exs' ),
						'i c'                     => esc_html__( 'Accent color', 'exs' ),
						'i c c2'                  => esc_html__( 'Accent secondary color', 'exs' ),
						'i c c3'                  => esc_html__( 'Accent third color', 'exs' ),
						'i c c4'                  => esc_html__( 'Accent fourth color', 'exs' ),
					),
				),
				'color_links_content'                      => array(
					'type'    => 'color-radio',
					'section' => 'colors',
					'label'   => esc_html__( 'Content area default links color', 'exs' ),
					'default' => esc_html( exs_option( 'color_links_content', '' ) ),
					'description' => esc_html__( 'Experimental. Using as a color of the simple links inside the main content area on singular pages.', 'exs' ),
					'choices' => array(
						''                        => esc_html__( 'Inherit', 'exs' ),
						'l'                       => esc_html__( 'Light', 'exs' ),
						'l m'                     => esc_html__( 'Grey', 'exs' ),
						'i'                       => esc_html__( 'Dark', 'exs' ),
						'i m'                     => esc_html__( 'Darker', 'exs' ),
						'i c'                     => esc_html__( 'Accent color', 'exs' ),
						'i c c2'                  => esc_html__( 'Accent secondary color', 'exs' ),
						'i c c3'                  => esc_html__( 'Accent third color', 'exs' ),
						'i c c4'                  => esc_html__( 'Accent fourth color', 'exs' ),
					),
				),
				'color_links_content_hover'                      => array(
					'type'    => 'color-radio',
					'section' => 'colors',
					'label'   => esc_html__( 'Content area default links hover color', 'exs' ),
					'default' => esc_html( exs_option( 'color_links_content_hover', '' ) ),
					'description' => esc_html__( 'Experimental. Using as a color of the simple links in the hover inside the main content area on singular pages.', 'exs' ),
					'choices' => array(
						''                        => esc_html__( 'Inherit', 'exs' ),
						'l'                       => esc_html__( 'Light', 'exs' ),
						'l m'                     => esc_html__( 'Grey', 'exs' ),
						'i'                       => esc_html__( 'Dark', 'exs' ),
						'i m'                     => esc_html__( 'Darker', 'exs' ),
						'i c'                     => esc_html__( 'Accent color', 'exs' ),
						'i c c2'                  => esc_html__( 'Accent secondary color', 'exs' ),
						'i c c3'                  => esc_html__( 'Accent third color', 'exs' ),
						'i c c4'                  => esc_html__( 'Accent fourth color', 'exs' ),
					),
				),
				//////////////////
				//colors inverse//
				//////////////////
				// 'colorLightInverse' => '#0a0a0a',
				// 'colorFontInverse' => '#d8d8d8',
				// 'colorFontMutedInverse' => '#aaaaaa',
				// 'colorBackgroundInverse' => '#161616',
				// 'colorBorderInverse' => '#3a3a3a',
				// 'colorDarkInverse' => '#dbdbdb',
				// 'colorDarkMutedInverse' => '#ffffff',
				'colors_inverse_palette_block_heading'                   => array(
					'type'        => 'block-heading',
					'section'     => 'panel_colors_inverse',
					'label'       => esc_html__( 'Theme inverse colors palette', 'exs' ),
					'description' => esc_html__( 'This color palette will be used all over the theme for inverse color scheme.', 'exs' ),
				),
				'colors_inverse_enabled'                      => array(
					'type'    => 'checkbox',
					'section' => 'panel_colors_inverse',
					'label'   => esc_html__( 'Enable inverse color scheme', 'exs' ),
					'default' => esc_html( exs_option( 'colors_inverse_enabled', false ) ),
					'description' => esc_html__( 'This feature uses Cookies so it is NOT COMPATIBLE with the AMP plugin', 'exs' ),
				),
				'colors_inverse_label_default'                        => array(
					'type'        => 'text',
					'section'     => 'panel_colors_inverse',
					'label'       => esc_html__( 'Default color scheme label', 'exs' ),
					'default'     => esc_html( exs_option( 'colors_inverse_label_default', esc_html__( 'Light', 'exs' ) ) ),
				),
				'colors_inverse_label_inverse'                        => array(
					'type'        => 'text',
					'section'     => 'panel_colors_inverse',
					'label'       => esc_html__( 'Inverse color scheme label', 'exs' ),
					'default'     => esc_html( exs_option( 'colors_inverse_label_inverse', esc_html__( 'Dark', 'exs' ) ) ),
				),
				'colorLightInverse'                            => array(
					'type'        => 'color',
					'section'     => 'panel_colors_inverse',
					'label'       => esc_html__( 'Light inverse color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorLightInverse', '#0a0a0a' ) ),
					'description' => esc_html__( 'Using as a background for light sections and as a font color in inversed sections.', 'exs' ),
				),
				'colorFontInverse'                             => array(
					'type'        => 'color',
					'section'     => 'panel_colors_inverse',
					'label'       => esc_html__( 'Font inverse color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorFontInverse', '#d8d8d8' ) ),
					'description' => esc_html__( 'Using as a font color.', 'exs' ),
				),
				'colorFontMutedInverse'                        => array(
					'type'        => 'color',
					'section'     => 'panel_colors_inverse',
					'label'       => esc_html__( 'Font muted inverse color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorFontMutedInverse', '#aaaaaa' ) ),
					'description' => esc_html__( 'Using as a font muted color.', 'exs' ),
				),
				'colorBackgroundInverse'                       => array(
					'type'        => 'color',
					'section'     => 'panel_colors_inverse',
					'label'       => esc_html__( 'Light grey background inverse color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorBackgroundInverse', '#161616' ) ),
					'description' => esc_html__( 'Using as a light grey background.', 'exs' ),
				),
				'colorBorderInverse'                           => array(
					'type'        => 'color',
					'section'     => 'panel_colors_inverse',
					'label'       => esc_html__( 'Grey border inverse color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorBorderInverse', '#3a3a3a' ) ),
					'description' => esc_html__( 'Using as a border color.', 'exs' ),
				),
				'colorDarkInverse'                             => array(
					'type'        => 'color',
					'section'     => 'panel_colors_inverse',
					'label'       => esc_html__( 'Dark inverse color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorDarkInverse', '#dbdbdb' ) ),
					'description' => esc_html__( 'Using as a buttons color for light sections and as a background for inversed sections.', 'exs' ),
				),
				'colorDarkMutedInverse'                        => array(
					'type'        => 'color',
					'section'     => 'panel_colors_inverse',
					'label'       => esc_html__( 'Darker inverse color', 'exs' ),
					'default'     => esc_html( exs_option( 'colorDarkMutedInverse', '#ffffff' ) ),
					'description' => esc_html__( 'Using as headings color and a background for darker inversed sections.', 'exs' ),
				),
				'colors_inverse_hide_label'                      => array(
					'type'    => 'checkbox',
					'section' => 'panel_colors_inverse',
					'label'   => esc_html__( 'Hide switcher text label', 'exs' ),
					'default' => esc_html( exs_option( 'colors_inverse_hide_label', false ) ),
				),
				'colors_inverse_hide_switcher'                      => array(
					'type'    => 'checkbox',
					'section' => 'panel_colors_inverse',
					'label'   => esc_html__( 'Hide switcher toggler', 'exs' ),
					'default' => esc_html( exs_option( 'colors_inverse_hide_switcher', false ) ),
				),
				'colors_inverse_hide_icon'                      => array(
					'type'    => 'checkbox',
					'section' => 'panel_colors_inverse',
					'label'   => esc_html__( 'Hide switcher icon', 'exs' ),
					'default' => esc_html( exs_option( 'colors_inverse_hide_icon', false ) ),
				),

				///////////////////////////
				//homepage slider section//
				///////////////////////////
				// static_front_page
				'intro_block_heading'                   => array(
					'type'        => 'block-heading',
					'section'     => 'static_front_page',
					'label'       => esc_html__( 'Intro Section', 'exs' ),
					'description' => esc_html__( 'Set your settings for homepage intro section. Leave blank if not needed.', 'exs' ),
				),
				'intro_position'                        => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Section Position', 'exs' ),
					'default' => esc_html( exs_option( 'intro_position', '' ) ),
					'choices' => array(
						''       => esc_html__( 'Hidden', 'exs' ),
						'before' => esc_html__( 'Before header', 'exs' ),
						'after'  => esc_html__( 'After header', 'exs' ),
					),
				),
				'intro_layout'                          => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Section Layout', 'exs' ),
					'default' => esc_html( exs_option( 'intro_layout', '' ) ),
					'choices' => array(
						''             => esc_html__( 'Background image', 'exs' ),
						'image-left'   => esc_html__( 'Left side image', 'exs' ),
						'image-right'  => esc_html__( 'Right side image', 'exs' ),
						'image-top'    => esc_html__( 'Top image', 'exs' ),
						'image-bottom' => esc_html__( 'Bottom image', 'exs' ),
					),
				),
				'intro_fullscreen'                      => array(
					'type'    => 'checkbox',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Full Screen Intro Height', 'exs' ),
					'default' => esc_html( exs_option( 'intro_fullscreen', false ) ),
				),
				'intro_background'                      => array(
					'type'    => 'color-radio',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Section Background', 'exs' ),
					'default' => esc_html( exs_option( 'intro_background', '' ) ),
					'choices' => exs_customizer_backgrounds_array(),
				),

				'intro_alignment'                       => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Section Text Alignment', 'exs' ),
					'default' => esc_html( exs_option( 'intro_alignment', 'text-left' ) ),
					'choices' => array(
						'text-left'   => esc_html__( 'Left', 'exs' ),
						'text-center' => esc_html__( 'Centered', 'exs' ),
						'text-right'  => esc_html__( 'Right', 'exs' ),
					),
				),
				'intro_extra_padding_top'               => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro section top padding', 'exs' ),
					'default' => esc_html( exs_option( 'intro_extra_padding_top', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pt-0'  => esc_html__( '0', 'exs' ),
						'pt-1'  => esc_html__( '1em', 'exs' ),
						'pt-2'  => esc_html__( '2em', 'exs' ),
						'pt-3'  => esc_html__( '3em', 'exs' ),
						'pt-4'  => esc_html__( '4em', 'exs' ),
						'pt-5'  => esc_html__( '5em', 'exs' ),
						'pt-6'  => esc_html__( '6em', 'exs' ),
						'pt-7'  => esc_html__( '7em', 'exs' ),
						'pt-8'  => esc_html__( '8em', 'exs' ),
						'pt-9'  => esc_html__( '9em', 'exs' ),
						'pt-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'intro_extra_padding_bottom'            => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro section bottom padding', 'exs' ),
					'default' => esc_html( exs_option( 'intro_extra_padding_bottom', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pb-0'  => esc_html__( '0', 'exs' ),
						'pb-1'  => esc_html__( '1em', 'exs' ),
						'pb-2'  => esc_html__( '2em', 'exs' ),
						'pb-3'  => esc_html__( '3em', 'exs' ),
						'pb-4'  => esc_html__( '4em', 'exs' ),
						'pb-5'  => esc_html__( '5em', 'exs' ),
						'pb-6'  => esc_html__( '6em', 'exs' ),
						'pb-7'  => esc_html__( '7em', 'exs' ),
						'pb-8'  => esc_html__( '8em', 'exs' ),
						'pb-9'  => esc_html__( '9em', 'exs' ),
						'pb-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'intro_show_search'                     => array(
					'type'    => 'checkbox',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Show search form', 'exs' ),
					'default' => esc_html( exs_option( 'intro_show_search', false ) ),
				),
				'intro_font_size'                       => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro section font size', 'exs' ),
					'default' => esc_html( exs_option( 'intro_font_size', '' ) ),
					'choices' => exs_customizer_font_size_array(),
				),

				'intro_background_image_heading'      => array(
					'type'        => 'block-heading',
					'section'     => 'static_front_page',
					'label'       => esc_html__( 'Intro image settings', 'exs' ),
				),
				'intro_background_image'                => array(
					'type'    => 'image',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Section Image', 'exs' ),
					'default' => esc_html( exs_option( 'intro_background_image', '' ) ),
				),
				'intro_image_animation'                 => array(
					'type'        => 'select',
					'section'     => 'static_front_page',
					'label'       => esc_html__( 'Animation for intro image', 'exs' ),
					'description' => esc_html__( 'Animation should be enabled', 'exs' ),
					'default'     => esc_html( exs_option( 'intro_image_animation', '' ) ),
					'choices'     => exs_get_animation_options(),
				),
				'intro_background_image_cover'          => array(
					'type'    => 'checkbox',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Cover background image', 'exs' ),
					'default' => esc_html( exs_option( 'intro_background_image_cover', false ) ),
				),
				'intro_background_image_fixed'          => array(
					'type'    => 'checkbox',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Fixed background image', 'exs' ),
					'default' => esc_html( exs_option( 'intro_background_image_fixed', false ) ),
				),
				'intro_background_image_overlay'        => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Overlay for background image', 'exs' ),
					'default' => esc_html( exs_option( 'intro_background_image_overlay', '' ) ),
					'choices' => exs_customizer_background_overlay_array(),
				),
				'intro_background_image_overlay_opacity' => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Background image overlay opacity', 'exs' ),
					'default'     => esc_html( exs_option( 'intro_background_image_overlay_opacity', '' ) ),
					'section'     => 'static_front_page',
					'atts'        => array(
						'min'         => '1',
						'max'         => '99',
						'step'        => '1',
					),
				),
				'intro_section_content_heading'      => array(
					'type'        => 'block-heading',
					'section'     => 'static_front_page',
					'label'       => esc_html__( 'Intro Section Content', 'exs' ),
				),
				'intro_heading'                         => array(
					'type'    => 'text',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Section Heading text', 'exs' ),
					'default' => esc_html( exs_option( 'intro_heading', '' ) ),
				),
				'intro_heading_mt'                      => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Heading top margin', 'exs' ),
					'default' => esc_html( exs_option( 'intro_heading_mt', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mt-0'  => '0',
						'mt-05' => '0.5em',
						'mt-1'  => '1em',
						'mt-15' => '1.5em',
						'mt-2'  => '2em',
						'mt-3'  => '3em',
						'mt-4'  => '4em',
						'mt-5'  => '5em',
					),
				),
				'intro_heading_mb'                      => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Heading bottom margin', 'exs' ),
					'default' => esc_html( exs_option( 'intro_heading_mb', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mb-0'  => '0',
						'mb-05' => '0.5em',
						'mb-1'  => '1em',
						'mb-15' => '1.5em',
						'mb-2'  => '2em',
						'mb-3'  => '3em',
						'mb-4'  => '4em',
						'mb-5'  => '5em',
					),
				),
				'intro_heading_animation'               => array(
					'type'        => 'select',
					'section'     => 'static_front_page',
					'label'       => esc_html__( 'Animation for intro heading', 'exs' ),
					'description' => esc_html__( 'Animation should be enabled', 'exs' ),
					'default'     => esc_html( exs_option( 'intro_heading_animation', '' ) ),
					'choices'     => exs_get_animation_options(),
				),
				'intro_description'                     => array(
					'type'    => 'textarea',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Section description text', 'exs' ),
					'default' => esc_html( exs_option( 'intro_description', '' ) ),
				),
				'intro_description_mt'                  => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Description top margin', 'exs' ),
					'default' => esc_html( exs_option( 'intro_description_mt', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mt-0'  => '0',
						'mt-05' => '0.5em',
						'mt-1'  => '1em',
						'mt-15' => '1.5em',
						'mt-2'  => '2em',
						'mt-3'  => '3em',
						'mt-4'  => '4em',
						'mt-5'  => '5em',
					),
				),
				'intro_description_mb'                  => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Description bottom margin', 'exs' ),
					'default' => esc_html( exs_option( 'intro_description_mb', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mb-0'  => '0',
						'mb-05' => '0.5em',
						'mb-1'  => '1em',
						'mb-15' => '1.5em',
						'mb-2'  => '2em',
						'mb-3'  => '3em',
						'mb-4'  => '4em',
						'mb-5'  => '5em',
					),
				),
				'intro_description_animation'           => array(
					'type'        => 'select',
					'section'     => 'static_front_page',
					'label'       => esc_html__( 'Animation for intro description text', 'exs' ),
					'description' => esc_html__( 'Animation should be enabled', 'exs' ),
					'default'     => esc_html( exs_option( 'intro_description_animation', '' ) ),
					'choices'     => exs_get_animation_options(),
				),
				'intro_button_text_first'               => array(
					'type'    => 'text',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Primary Action Button Text', 'exs' ),
					'default' => esc_html( exs_option( 'intro_button_text_first', '' ) ),
				),
				'intro_button_url_first'                => array(
					'type'    => 'url',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Primary Action Button URL', 'exs' ),
					'default' => esc_html( exs_option( 'intro_button_url_first', '' ) ),
				),
				'intro_button_first_animation'          => array(
					'type'        => 'select',
					'section'     => 'static_front_page',
					'label'       => esc_html__( 'Animation for first intro button', 'exs' ),
					'description' => esc_html__( 'Animation should be enabled', 'exs' ),
					'default'     => esc_html( exs_option( 'intro_button_first_animation', '' ) ),
					'choices'     => exs_get_animation_options(),
				),
				'intro_button_text_second'              => array(
					'type'    => 'text',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Secondary Action Button Text', 'exs' ),
					'default' => esc_html( exs_option( 'intro_button_text_second', '' ) ),
				),
				'intro_button_url_second'               => array(
					'type'    => 'url',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Secondary Action Button URL', 'exs' ),
					'default' => esc_html( exs_option( 'intro_button_url_second', '' ) ),
				),
				'intro_button_second_animation'         => array(
					'type'        => 'select',
					'section'     => 'static_front_page',
					'label'       => esc_html__( 'Animation for second intro button', 'exs' ),
					'description' => esc_html__( 'Animation should be enabled', 'exs' ),
					'default'     => esc_html( exs_option( 'intro_button_second_animation', '' ) ),
					'choices'     => exs_get_animation_options(),
				),
				'intro_buttons_mt'                      => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Buttons top margin', 'exs' ),
					'default' => esc_html( exs_option( 'intro_buttons_mt', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mt-0'  => '0',
						'mt-05' => '0.5em',
						'mt-1'  => '1em',
						'mt-15' => '1.5em',
						'mt-2'  => '2em',
						'mt-3'  => '3em',
						'mt-4'  => '4em',
						'mt-5'  => '5em',
					),
				),
				'intro_buttons_mb'                      => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Buttons bottom margin', 'exs' ),
					'default' => esc_html( exs_option( 'intro_buttons_mb', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mb-0'  => '0',
						'mb-05' => '0.5em',
						'mb-1'  => '1em',
						'mb-15' => '1.5em',
						'mb-2'  => '2em',
						'mb-3'  => '3em',
						'mb-4'  => '4em',
						'mb-5'  => '5em',
					),
				),
				'intro_shortcode'                       => array(
					'type'        => 'text',
					'section'     => 'static_front_page',
					'label'       => esc_html__( 'Intro Section Shortcode', 'exs' ),
					'description' => esc_html__( 'You can put shortcode here. It will appear below Intro description', 'exs' ),
					'default'     => esc_html( exs_option( 'intro_shortcode', '' ) ),
				),
				'intro_shortcode_mt'                    => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Shortcode top margin', 'exs' ),
					'default' => esc_html( exs_option( 'intro_shortcode_mt', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mt-0'  => '0',
						'mt-05' => '0.5em',
						'mt-1'  => '1em',
						'mt-15' => '1.5em',
						'mt-2'  => '2em',
						'mt-3'  => '3em',
						'mt-4'  => '4em',
						'mt-5'  => '5em',
					),
				),
				'intro_shortcode_mb'                    => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Shortcode bottom margin', 'exs' ),
					'default' => esc_html( exs_option( 'intro_shortcode_mb', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mb-0'  => '0',
						'mb-05' => '0.5em',
						'mb-1'  => '1em',
						'mb-15' => '1.5em',
						'mb-2'  => '2em',
						'mb-3'  => '3em',
						'mb-4'  => '4em',
						'mb-5'  => '5em',
					),
				),
				'intro_shortcode_animation'             => array(
					'type'        => 'select',
					'section'     => 'static_front_page',
					'label'       => esc_html__( 'Animation for intro shortcode', 'exs' ),
					'description' => esc_html__( 'Animation should be enabled', 'exs' ),
					'default'     => esc_html( exs_option( 'intro_shortcode_animation', '' ) ),
					'choices'     => exs_get_animation_options(),
				),
				/////////////////
				//intro teasers//
				/////////////////
				'intro_teasers_block_heading'           => array(
					'type'        => 'block-heading',
					'section'     => 'static_front_page',
					'label'       => esc_html__( 'Teasers settigns', 'exs' ),
					'description' => esc_html__( 'You can show teasers on your homepage at the top of your main section. Leave blank if not needed.', 'exs' ),
				),
				'intro_teaser_section_layout'           => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Layout for intro teasers section', 'exs' ),
					'default' => esc_html( exs_option( 'intro_teaser_section_layout', '' ) ),
					'choices' => array(
						''                            => esc_html__( 'Disabled', 'exs' ),
						'container'                   => esc_html__( 'Container width', 'exs' ),
						'container-fluid'             => esc_html__( 'Full width', 'exs' ),
						'container top-overlap'       => esc_html__( 'Container width top overlap', 'exs' ),
						'container-fluid top-overlap' => esc_html__( 'Full width top overlap', 'exs' ),
					),
				),
				'intro_teaser_section_background'       => array(
					'type'    => 'color-radio',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Teasers Section Background', 'exs' ),
					'default' => esc_html( exs_option( 'intro_teaser_section_background', '' ) ),
					'choices' => exs_customizer_backgrounds_array(),
				),
				'intro_teaser_section_padding_top'      => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Teasers Section top padding', 'exs' ),
					'default' => esc_html( exs_option( 'intro_teaser_section_padding_top', '' ) ),
					'choices' => array(
						''     => esc_html__( 'None', 'exs' ),
						'pt-1' => esc_html__( '1em', 'exs' ),
						'pt-2' => esc_html__( '2em', 'exs' ),
						'pt-3' => esc_html__( '3em', 'exs' ),
						'pt-4' => esc_html__( '4em', 'exs' ),
						'pt-5' => esc_html__( '5em', 'exs' ),
					),
				),
				'intro_teaser_section_padding_bottom'   => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Teasers Section bottom padding', 'exs' ),
					'default' => esc_html( exs_option( 'intro_teaser_section_padding_bottom', '' ) ),
					'choices' => array(
						''     => esc_html__( 'None', 'exs' ),
						'pb-1' => esc_html__( '1em', 'exs' ),
						'pb-2' => esc_html__( '2em', 'exs' ),
						'pb-3' => esc_html__( '3em', 'exs' ),
						'pb-4' => esc_html__( '4em', 'exs' ),
						'pb-5' => esc_html__( '5em', 'exs' ),
					),
				),
				'intro_teaser_font_size'                => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Teasers section font size', 'exs' ),
					'default' => esc_html( exs_option( 'intro_teaser_font_size', '' ) ),
					'choices' => exs_customizer_font_size_array(),
				),
				'intro_teaser_layout'                   => array(
					'type'    => 'select',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Layout for intro teasers', 'exs' ),
					'default' => esc_html( exs_option( 'intro_teaser_layout', '' ) ),
					'choices' => array(
						''            => esc_html__( 'Vertical', 'exs' ),
						'text-center' => esc_html__( 'Vertical Centered', 'exs' ),
						'horizontal'  => esc_html__( 'Horizontal', 'exs' ),
					),
				),
				'intro_teaser_heading'                  => array(
					'type'    => 'text',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Teasers Heading text', 'exs' ),
					'default' => esc_html( exs_option( 'intro_teaser_heading', '' ) ),
				),
				'intro_teaser_description'              => array(
					'type'    => 'textarea',
					'section' => 'static_front_page',
					'label'   => esc_html__( 'Intro Teasers description text', 'exs' ),
					'default' => esc_html( exs_option( 'intro_teaser_description', '' ) ),
				),
				////////
				//logo//
				////////
				//to existing 'title_tagline' section
				'logo'                                  => array(
					'type'    => 'select',
					'section' => 'title_tagline',
					'label'   => esc_html__( 'Logo Layout', 'exs' ),
					'default' => esc_html( exs_option( 'logo', '' ) ),
					'choices' => array(
						'1' => esc_html__( 'Left image and right text', 'exs' ),
						'2' => esc_html__( 'Top image and bottom text', 'exs' ),
						'3' => esc_html__( 'Image between text', 'exs' ),
					),
				),
				'logo_text_primary'                     => array(
					'type'    => 'text',
					'section' => 'title_tagline',
					'label'   => esc_html__( 'Logo Primary Text', 'exs' ),
					'default' => esc_html( exs_option( 'logo_text_primary', '' ) ),
				),
				'logo_text_primary_fs'                            => array(
					'type'    => 'slider',
					'section' => 'title_tagline',
					'label'   => esc_html__( 'Primary Text Font Size', 'exs' ),
					'default' => esc_html( exs_option( 'logo_text_primary_fs', '' ) ),
					'description' => esc_html__( 'It will be applied to logo primary text. Value in pixels', 'exs' ),
					'atts'        => array(
						'min'         => '9',
						'max'         => '26',
						'step'        => '1',
					),
				),
				'logo_text_primary_fs_xl'                            => array(
					'type'    => 'slider',
					'section' => 'title_tagline',
					'label'   => esc_html__( 'Primary Text Font Size for Large Screens', 'exs' ),
					'default' => esc_html( exs_option( 'logo_text_primary_fs_xl', '' ) ),
					'description' => esc_html__( 'It will be applied to logo primary text for screens larger then 1200px. Value in pixels', 'exs' ),
					'atts'        => array(
						'min'         => '9',
						'max'         => '26',
						'step'        => '1',
					),
				),
				'logo_text_primary_hidden'                    => array(
					'type'    => 'select',
					'section' => 'title_tagline',
					'label'   => esc_html__( 'Hide logo primary text on screens:', 'exs' ),
					'default' => esc_html( exs_option( 'logo_text_primary_hidden', '' ) ),
					'choices' => exs_customizer_responsive_display_array(),
				),
				'logo_text_secondary'                   => array(
					'type'    => 'text',
					'section' => 'title_tagline',
					'label'   => esc_html__( 'Logo Secondary Text', 'exs' ),
					'default' => esc_html( exs_option( 'logo_text_secondary', '' ) ),
				),
				'logo_text_secondary_fs'                            => array(
					'type'    => 'slider',
					'section' => 'title_tagline',
					'label'   => esc_html__( 'Secondary Text Font Size', 'exs' ),
					'default' => esc_html( exs_option( 'logo_text_secondary_fs', '' ) ),
					'description' => esc_html__( 'It will be applied to logo secondary text. Value in pixels', 'exs' ),
					'atts'        => array(
						'min'         => '9',
						'max'         => '26',
						'step'        => '1',
					),
				),
				'logo_text_secondary_fs_xl'                            => array(
					'type'    => 'slider',
					'section' => 'title_tagline',
					'label'   => esc_html__( 'Secondary Text Font Size for Large Screens', 'exs' ),
					'default' => esc_html( exs_option( 'logo_text_secondary_fs_xl', '' ) ),
					'description' => esc_html__( 'It will be applied to logo secondary text for screens larger then 1200px. Value in pixels', 'exs' ),
					'atts'        => array(
						'min'         => '9',
						'max'         => '26',
						'step'        => '1',
					),
				),
				'logo_text_secondary_hidden'            => array(
					'type'    => 'select',
					'section' => 'title_tagline',
					'label'   => esc_html__( 'Hide logo secondary text on screens:', 'exs' ),
					'default' => esc_html( exs_option( 'logo_text_secondary_hidden', '' ) ),
					'choices' => exs_customizer_responsive_display_array(),
				),
				'header_top_tall'                       => array(
					'type'        => 'checkbox',
					'section'     => 'title_tagline',
					'label'       => esc_html__( 'Logo additional vertical padding', 'exs' ),
					'description' => esc_html__( 'Will make header taller in top position', 'exs' ),
					'default'     => esc_html( exs_option( 'header_top_tall', false ) ),
				),
				'logo_background'                       => array(
					'type'    => 'color-radio',
					'section' => 'title_tagline',
					'label'   => esc_html__( 'Logo Background', 'exs' ),
					'default' => esc_html( exs_option( 'logo_background', '' ) ),
					'choices' => exs_customizer_backgrounds_array(),
				),
				'logo_padding_horizontal'               => array(
					'type'        => 'checkbox',
					'section'     => 'title_tagline',
					'label'       => esc_html__( 'Logo additional horizontal padding', 'exs' ),
					'description' => esc_html__( 'This will add an extra horizontal padding for logo', 'exs' ),
					'default'     => esc_html( exs_option( 'logo_padding_horizontal', false ) ),
				),
				'logo_width_zero'               => array(
					'type'        => 'checkbox',
					'section'     => 'title_tagline',
					'label'       => esc_html__( 'Logo zero width in the header section', 'exs' ),
					'description' => esc_html__( 'This will help other header elements to ignore logo width', 'exs' ),
					'default'     => esc_html( exs_option( 'logo_width_zero', false ) ),
				),
				////////////////////
				//skins and assets//
				////////////////////
				'skins_extra'                           => array(
					'type'        => 'extra-button',
					'label'       => esc_html__( 'Site skins', 'exs' ),
					'description' => esc_html__( 'Change your site look and feel without changing your theme with growing number of CSS skins in your Customizer', 'exs' ),
					'section'     => 'section_skins',
				),
				////////////////////
				//layout and skins//
				////////////////////
				'main_container_width'                  => array(
					'type'        => 'radio',
					'section'     => 'section_layout',
					'label'       => esc_html__( 'Global container max width', 'exs' ),
					'default'     => esc_html( exs_option( 'main_container_width', '1140' ) ),
					'description' => esc_html__( 'Can be overridden by different page template', 'exs' ),
					'choices' => array(
						'1400' => esc_html__( '1400px', 'exs' ),
						'1140' => esc_html__( '1140px', 'exs' ),
						'960'  => esc_html__( '960px', 'exs' ),
						'720'  => esc_html__( '720px', 'exs' ),
					),
				),
				'blog_container_width'                  => array(
					'type'    => 'radio',
					'section' => 'section_layout',
					'label'   => esc_html__( 'Archive container max width', 'exs' ),
					'default' => esc_html( exs_option( 'blog_container_width', '' ) ),
					'choices' => array(
						''     => esc_html__( 'Inherit from Global', 'exs' ),
						'1400' => esc_html__( '1400px', 'exs' ),
						'1140' => esc_html__( '1140px', 'exs' ),
						'960'  => esc_html__( '960px', 'exs' ),
						'720'  => esc_html__( '720px', 'exs' ),
					),
				),
				'blog_single_container_width'           => array(
					'type'    => 'radio',
					'section' => 'section_layout',
					'label'   => esc_html__( 'Single post container max width', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_container_width', '' ) ),
					'choices' => array(
						''     => esc_html__( 'Inherit from Global', 'exs' ),
						'1400' => esc_html__( '1400px', 'exs' ),
						'1140' => esc_html__( '1140px', 'exs' ),
						'960'  => esc_html__( '960px', 'exs' ),
						'720'  => esc_html__( '720px', 'exs' ),
					),
				),
				'search_container_width'               => array(
					'type'    => 'radio',
					'section' => 'section_layout',
					'label'   => esc_html__( 'Search results container max width', 'exs' ),
					'default' => esc_html( exs_option( 'search_container_width', '' ) ),
					'choices' => array(
						''     => esc_html__( 'Inherit from Global', 'exs' ),
						'1400' => esc_html__( '1400px', 'exs' ),
						'1140' => esc_html__( '1140px', 'exs' ),
						'960'  => esc_html__( '960px', 'exs' ),
						'720'  => esc_html__( '720px', 'exs' ),
					),
				),
				'preloader'                             => array(
					'type'     => 'select',
					'section'  => 'section_layout',
					'label'    => esc_html__( 'Page preloader', 'exs' ),
					'default'  => esc_html( exs_option( 'preloader', '' ) ),
					'priority' => 200,
					'choices'  => array(
						''       => esc_html__( 'No preloader', 'exs' ),
						'cover'  => esc_html__( 'Cover page preloader', 'exs' ),
						'corner' => esc_html__( 'Corner page preloader', 'exs' ),
					),
				),
				'box_fade_in'                           => array(
					'type'     => 'checkbox',
					'section'  => 'section_layout',
					'label'    => esc_html__( 'Fade in page on load', 'exs' ),
					'default'  => esc_html( exs_option( 'box_fade_in', '' ) ),
					'priority' => 200,
				),
				'widgets_ul_margin'                   => array(
					'type'        => 'slider',
					'section' => 'section_layout',
					'label'   => esc_html__( 'Vertical margin for widgets list items', 'exs' ),
					'default' => esc_html( exs_option( 'widgets_ul_margin', '.5' ) ),
					'atts'        => array(
						'min'         => '0',
						'max'         => '2.5',
						'step'        => '0.05',
					),
				),
				'search_modal'                             => array(
					'type'     => 'select',
					'section'  => 'section_layout',
					'label'    => esc_html__( 'Search Modal Type', 'exs' ),
					'default'  => esc_html( exs_option( 'search_modal', '' ) ),
					'priority' => 200,
					'choices'  => array(
						''  => esc_html__( 'Default', 'exs' ),
						'1' => esc_html__( 'Full Screen', 'exs' ),
						'2' => esc_html__( 'Full Screen Dark', 'exs' ),
						'3' => esc_html__( 'Half Screen Big', 'exs' ),
						'4' => esc_html__( 'Half Screen Big Dark', 'exs' ),
					),
				),
				'assets_lightbox'                            => array(
					'type'            => 'checkbox',
					'section'         => 'section_layout',
					'label'           => esc_html__( 'Load LightBox for galleries and YouTube video links', 'exs' ),
					'description'     => esc_html__( 'If checked will loads additional LightBox JS and CSS files to open images and YouTube video links in the LightBox PopUp', 'exs' ),
					'default'         => esc_html( exs_option( 'assets_lightbox', false ) ),
				),
				//always min assets for best performance
				'assets_min'                            => array(
					'type'            => 'checkbox',
					'section'         => 'section_layout',
					'label'           => esc_html__( 'Use minified version of CSS files', 'exs' ),
					'description'     => esc_html__( 'You can use compressed versions of your static files for best performance', 'exs' ),
					'default'         => esc_html( exs_option( 'assets_min', false ) ),
				),
				'post_thumbnails_fullwidth'             => array(
					'type'        => 'checkbox',
					'section'     => 'section_layout',
					'label'       => esc_html__( 'Make post thumbnails full width', 'exs' ),
					'description' => esc_html__( 'If your featured images are narrower than post width, they will be stretched', 'exs' ),
					'default'     => esc_html( exs_option( 'post_thumbnails_fullwidth', '' ) ),
					'priority'    => 200,
				),
				'post_thumbnails_centered'             => array(
					'type'        => 'checkbox',
					'section'     => 'section_layout',
					'label'       => esc_html__( 'Make post thumbnails center aligned', 'exs' ),
					'description' => esc_html__( 'If your featured images are narrower than post width, they will be center aligned', 'exs' ),
					'default'     => esc_html( exs_option( 'post_thumbnails_centered', '' ) ),
					'priority'    => 200,
				),
				'assets_main_nob'                      => array(
					'type'            => 'checkbox',
					'section'         => 'section_layout',
					'label'           => esc_html__( 'Not load Gutenberg Block Editor Theme CSS styles', 'exs' ),
					'description'     => esc_html__( 'If you are using the Classic Editor plugin then you may reduce your CSS file size by omit the Gutenberg styles', 'exs' ),
					'default'         => esc_html( exs_option( 'assets_main_nob', false ) ),
				),
				'assets_nob'                            => array(
					'type'            => 'checkbox',
					'section'         => 'section_layout',
					'label'           => esc_html__( 'Disable Gutenberg Block Editor default CSS styles', 'exs' ),
					'description'     => esc_html__( 'If you are using the Classic Editor plugin then you can disable the default WordPress Gutenberg CSS styles', 'exs' ),
					'default'         => esc_html( exs_option( 'assets_nob', false ) ),
				),
				'remove_widgets_block_editor'           => array(
					'type'            => 'checkbox',
					'section'         => 'section_layout',
					'label'           => esc_html__( 'Disable Block Editor for Widgets screen', 'exs' ),
					'description'     => esc_html__( 'If you want to stick with the classic widgets screen after WordPress 5.8 update check this checkbox', 'exs' ),
					'default'         => esc_html( exs_option( 'remove_widgets_block_editor', false ) ),
				),
				'remove_wp_default_duotone_svg'         => array(
					'type'            => 'checkbox',
					'section'         => 'section_layout',
					'label'           => esc_html__( 'Disable WP5.9+ SVG inline code for duotones', 'exs' ),
					'description'     => esc_html__( 'If you want to remove default SVG for duotones that WordPress 5.9+ prints then check this checkbox', 'exs' ),
					'default'         => esc_html( exs_option( 'remove_wp_default_duotone_svg', false ) ),
				),
				'enable_wp_default_footer_container_styles'         => array(
					'type'            => 'checkbox',
					'section'         => 'section_layout',
					'label'           => esc_html__( 'Enable WP5.9+ Footer container CSS styles', 'exs' ),
					'description'     => esc_html__( 'Starting from WP version 5.9 WordPress prints STYLE tag before closing BODY tag which is not a valid HTML markup. If you want to enable these STYLE tags check this checkbox', 'exs' ),
					'default'         => esc_html( exs_option( 'enable_wp_default_footer_container_styles', false ) ),
				),
				///////////
				//buttons//
				///////////
				'buttons_main_heading'        => array(
					'type'        => 'block-heading',
					'section'     => 'section_buttons',
					'label'       => esc_html__( 'Links and submit buttons', 'exs' ),
					'description' => esc_html__( 'Change your buttons block styles, submit buttons styles etc.', 'exs' ),
				),
				'buttons_uppercase'                     => array(
					'type'    => 'checkbox',
					'section' => 'section_buttons',
					'label'   => esc_html__( 'Uppercase buttons', 'exs' ),
					'default' => esc_html( exs_option( 'buttons_uppercase', '' ) ),
				),
				'buttons_bold'                          => array(
					'type'    => 'checkbox',
					'section' => 'section_buttons',
					'label'   => esc_html__( 'Bold text buttons', 'exs' ),
					'default' => esc_html( exs_option( 'buttons_bold', '' ) ),
				),
				'buttons_colormain'                     => array(
					'type'    => 'checkbox',
					'section' => 'section_buttons',
					'label'   => esc_html__( 'Accent color buttons', 'exs' ),
					'default' => esc_html( exs_option( 'buttons_colormain', '' ) ),
				),
				'buttons_outline'                       => array(
					'type'    => 'checkbox',
					'section' => 'section_buttons',
					'label'   => esc_html__( 'Outlined buttons', 'exs' ),
					'default' => esc_html( exs_option( 'buttons_outline', '' ) ),
				),
				'buttons_big'                           => array(
					'type'    => 'checkbox',
					'section' => 'section_buttons',
					'label'   => esc_html__( 'Bigger buttons', 'exs' ),
					'default' => esc_html( exs_option( 'buttons_big', '' ) ),
				),
				'buttons_radius'                        => array(
					'type'    => 'select',
					'section' => 'section_buttons',
					'label'   => esc_html__( 'Border radius', 'exs' ),
					'default' => esc_html( exs_option( 'buttons_radius', '' ) ),
					'choices' => array(
						''             => esc_html__( 'Default', 'exs' ),
						'btns-rounded' => esc_html__( 'Rounded corners', 'exs' ),
						'btns-round'   => esc_html__( 'Round corners', 'exs' ),
					),
				),
				'buttons_fs'                   => array(
					'type'        => 'slider',
					'section' => 'section_buttons',
					'label'   => esc_html__( 'Font Size (px)', 'exs' ),
					'default' => esc_html( exs_option( 'buttons_fs', '.92em' ) ),
					'atts'        => array(
						'min'         => '9',
						'max'         => '26',
						'step'        => '1',
					),
				),
				'buttons_burger_heading'        => array(
					'type'        => 'block-heading',
					'section'     => 'section_buttons',
					'label'       => esc_html__( 'Burger buttons', 'exs' ),
					'description' => esc_html__( 'Change your menu toggler buttons style.', 'exs' ),
				),
				'button_burger'                    => array(
					'type'    => 'image-radio',
					'section' => 'section_buttons',
					//'label'   => esc_html__( 'Burger menu button type', 'exs' ),
					'label'   => '',
					'default' => esc_html( exs_option( 'button_burger', '' ) ),
					'choices' => array(
						''    => array(
							'label' => esc_html__( 'Default', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/burger/burger-default.png',
						),
						'1'    => array(
							'label' => esc_html__( 'Thin', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/burger/burger-thin.png',
						),
						'2'    => array(
							'label' => esc_html__( 'Thin with 2 lines', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/burger/burger-thin-2-lines.png',
						),
						'3'    => array(
							'label' => esc_html__( 'Narrow middle line', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/burger/burger-narrow-middle.png',
						),
						'4'    => array(
							'label' => esc_html__( 'Thin with narrow middle line', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/burger/burger-thin-narrow-middle.png',
						),
						'5'    => array(
							'label' => esc_html__( 'Thin Tall', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/burger/burger-thin-tall.png',
						),
						'6'    => array(
							'label' => esc_html__( 'Tall', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/burger/burger-tall.png',
						),
						'7'    => array(
							'label' => esc_html__( 'Wide center line', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/burger/burger-wide-center-line.png',
						),
						'8'    => array(
							'label' => esc_html__( 'Narrow bottom line', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/burger/burger-narrow-bottom-line.png',
						),
						'9'    => array(
							'label' => esc_html__( 'Thin with narrow bottom line', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/burger/burger-thin-narrow-bottom-line.png',
						),
					),
				),
				'buttons_pagination_heading'        => array(
					'type'        => 'block-heading',
					'section'     => 'section_buttons',
					'label'       => esc_html__( 'Pagination buttons style', 'exs' ),
					'description' => esc_html__( 'Change your pagination buttons style.', 'exs' ),
				),
				'buttons_pagination'                    => array(
					'type'    => 'image-radio',
					'section' => 'section_buttons',
					//'label'   => esc_html__( 'Pagination buttons type', 'exs' ),
					'label'   => '',
					'default' => esc_html( exs_option( 'buttons_pagination', '' ) ),
					'choices' => array(
						''    => array(
							'label' => esc_html__( 'Default', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/pagination/pagination0.png',
						),
						'1'    => array(
							'label' => esc_html__( 'Grey Square', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/pagination/pagination1.png',
						),
						'2'    => array(
							'label' => esc_html__( 'Grey Rounded', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/pagination/pagination2.png',
						),
						'3'    => array(
							'label' => esc_html__( 'Grey Round', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/pagination/pagination3.png',
						),
						'4'    => array(
							'label' => esc_html__( 'Transparent', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/pagination/pagination4.png',
						),
						'5'    => array(
							'label' => esc_html__( 'Transparent Rounded', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/pagination/pagination5.png',
						),
						'6'    => array(
							'label' => esc_html__( 'Transparent Round', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/pagination/pagination6.png',
						),
						'7'    => array(
							'label' => esc_html__( 'Bordered', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/pagination/pagination7.png',
						),
						'8'    => array(
							'label' => esc_html__( 'Bordered Rounded', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/pagination/pagination8.png',
						),
						'9'    => array(
							'label' => esc_html__( 'Bordered Round', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/pagination/pagination9.png',
						),
					),
				),
				'buttons_social_heading'        => array(
					'type'        => 'block-heading',
					'section'     => 'section_buttons',
					'label'       => esc_html__( 'Social buttons style', 'exs' ),
					'description' => esc_html__( 'Change your social buttons style.', 'exs' ),
				),
				'buttons_social'                    => array(
					'type'    => 'image-radio',
					'section' => 'section_buttons',
					'label'   => esc_html__( 'Social buttons type', 'exs' ),
					'default' => esc_html( exs_option( 'buttons_social', '' ) ),
					'choices' => array(
						''    => array(
							'label' => esc_html__( 'Default', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/social/social-buttons-default.png',
						),
						'1'    => array(
							'label' => esc_html__( 'Fill Color', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/social/social-buttons-fill.png',
						),
						'2'    => array(
							'label' => esc_html__( 'Background Color', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/social/social-buttons-square.png',
						),
						'3'    => array(
							'label' => esc_html__( 'Background Color Rounded', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/social/social-buttons-rounded.png',
						),
						'4'    => array(
							'label' => esc_html__( 'Background Color Round', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/social/social-buttons-round.png',
						),
					),
				),
				'buttons_social_gap'                   => array(
					'type'        => 'slider',
					'section' => 'section_buttons',
					'label'   => esc_html__( 'Social Buttons Gap', 'exs' ),
					'default' => esc_html( exs_option( 'buttons_social_gap', '1em' ) ),
					'atts'        => array(
						'min'         => '0',
						'max'         => '1.5',
						'step'        => '0.05',
					),
				),
				'buttons_totop_heading'        => array(
					'type'        => 'block-heading',
					'section'     => 'section_buttons',
					'label'       => esc_html__( '"To Top" button style', 'exs' ),
					'description' => esc_html__( 'Change your "To Top" button style.', 'exs' ),
				),
				'totop'                                 => array(
					'type'     => 'image-radio',
					'section'  => 'section_buttons',
					//'label'    => esc_html__( 'Page \'to top\' button', 'exs' ),
					'label'    => '',
					'default'  => esc_html( exs_option( 'totop', '' ) ),
					'choices' => array(
						''    => array(
							'label' => esc_html__( 'Disabled', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/none.png',
						),
						'1'    => array(
							'label' => esc_html__( 'Default', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/totop/default.png',
						),
						'2'    => array(
							'label' => esc_html__( 'Rounded', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/totop/rounded.png',
						),
						'3'    => array(
							'label' => esc_html__( 'Round', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/totop/round.png',
						),
						'4'    => array(
							'label' => esc_html__( 'Accent Color', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/totop/accent.png',
						),
						'5'    => array(
							'label' => esc_html__( 'Accent Color Rounded', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/totop/accent-rounded.png',
						),
						'6'    => array(
							'label' => esc_html__( 'Accent Color Round', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/totop/accent-round.png',
						),
						'7'    => array(
							'label' => esc_html__( 'Accent Border Color', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/totop/border.png',
						),
						'8'    => array(
							'label' => esc_html__( 'Accent Border Color Rounded', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/totop/border-rounded.png',
						),
						'9'    => array(
							'label' => esc_html__( 'Accent Border Color Round', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/totop/border-round.png',
						),
					),
				),
				////////
				//meta//
				////////
				'meta_email'                            => array(
					'type'    => 'text',
					'section' => 'section_meta',
					'label'   => esc_html__( 'Email', 'exs' ),
					'default' => esc_html( exs_option( 'meta_email', '' ) ),
				),
				'meta_email_label'                      => array(
					'type'    => 'text',
					'section' => 'section_meta',
					'label'   => esc_html__( 'Email label', 'exs' ),
					'default' => esc_html( exs_option( 'meta_email_label', '' ) ),
				),
				'meta_phone'                            => array(
					'type'    => 'text',
					'section' => 'section_meta',
					'label'   => esc_html__( 'Phone', 'exs' ),
					'default' => esc_html( exs_option( 'meta_phone', '' ) ),
				),
				'meta_phone_label'                      => array(
					'type'    => 'text',
					'section' => 'section_meta',
					'label'   => esc_html__( 'Phone label', 'exs' ),
					'default' => esc_html( exs_option( 'meta_phone_label', '' ) ),
				),
				'meta_phone_link'                      => array(
					'type'        => 'checkbox',
					'section'     => 'section_meta',
					'label'       => esc_html__( 'Make phone number clickable', 'exs' ),
					'description' => esc_html__( 'Create a clickable link that will allow to make a call immediately', 'exs' ),
					'default'     => esc_html( exs_option( 'meta_phone_link', '' ) ),
				),
				'meta_address'                          => array(
					'type'    => 'text',
					'section' => 'section_meta',
					'label'   => esc_html__( 'Address', 'exs' ),
					'default' => esc_html( exs_option( 'meta_address', '' ) ),
				),
				'meta_address_label'                    => array(
					'type'    => 'text',
					'section' => 'section_meta',
					'label'   => esc_html__( 'Address label', 'exs' ),
					'default' => esc_html( exs_option( 'meta_address_label', '' ) ),
				),
				'meta_opening_hours'                    => array(
					'type'    => 'text',
					'section' => 'section_meta',
					'label'   => esc_html__( 'Opening hours', 'exs' ),
					'default' => esc_html( exs_option( 'meta_opening_hours', '' ) ),
				),
				'meta_opening_hours_label'              => array(
					'type'    => 'text',
					'section' => 'section_meta',
					'label'   => esc_html__( 'Opening hours label', 'exs' ),
					'default' => esc_html( exs_option( 'meta_opening_hours_label', '' ) ),
				),
				//social links
				'meta_facebook'                         => array(
					'type'    => 'url',
					'section' => 'section_meta',
					'label'   => esc_html__( 'Facebook URL', 'exs' ),
					'default' => esc_html( exs_option( 'meta_facebook', '' ) ),
				),
				'meta_twitter'                          => array(
					'type'    => 'url',
					'section' => 'section_meta',
					'label'   => esc_html__( 'Twitter URL', 'exs' ),
					'default' => esc_html( exs_option( 'meta_twitter', '' ) ),
				),
				'meta_youtube'                          => array(
					'type'    => 'url',
					'section' => 'section_meta',
					'label'   => esc_html__( 'YouTube URL', 'exs' ),
					'default' => esc_html( exs_option( 'meta_youtube', '' ) ),
				),
				'meta_instagram'                        => array(
					'type'    => 'url',
					'section' => 'section_meta',
					'label'   => esc_html__( 'Instagram URL', 'exs' ),
					'default' => esc_html( exs_option( 'meta_instagram', '' ) ),
				),
				'meta_pinterest'                        => array(
					'type'    => 'url',
					'section' => 'section_meta',
					'label'   => esc_html__( 'Pinterest URL', 'exs' ),
					'default' => esc_html( exs_option( 'meta_pinterest', '' ) ),
				),
				'meta_linkedin'                         => array(
					'type'    => 'url',
					'section' => 'section_meta',
					'label'   => esc_html__( 'LinkedIn URL', 'exs' ),
					'default' => esc_html( exs_option( 'meta_linkedin', '' ) ),
				),
				'meta_github'                           => array(
					'type'    => 'url',
					'section' => 'section_meta',
					'label'   => esc_html__( 'GitHub URL', 'exs' ),
					'default' => esc_html( exs_option( 'meta_github', '' ) ),
				),
				'meta_tiktok'                           => array(
					'type'    => 'url',
					'section' => 'section_meta',
					'label'   => esc_html__( 'TikTok URL', 'exs' ),
					'default' => esc_html( exs_option( 'meta_tiktok', '' ) ),
				),
				/////////////
				//side menu//
				/////////////
				'side_extra'                            => array(
					'type'        => 'extra-button',
					'label'       => esc_html__( 'Side panel (menu)', 'exs' ),
					'description' => esc_html__( 'Set your side menu, make it always visible for large screens and many more in your Customizer', 'exs' ),
					'section'     => 'section_side_nav',
				),
				//////////
				//header//
				//////////
				//header image options
				//section 'header_image'
				'header_image_background_image_inverse'   => array(
					'type'    => 'checkbox',
					'section' => 'header_image',
					'label'   => esc_html__( 'Inverse background', 'exs' ),
					'description' => esc_html__( 'Make text and links in the header image section light color if checked', 'exs' ),
					'default' => esc_html( exs_option( 'header_image_background_image_inverse', true ) ),
				),
				'header_image_background_image_cover'   => array(
					'type'    => 'checkbox',
					'section' => 'header_image',
					'label'   => esc_html__( 'Cover background image', 'exs' ),
					'default' => esc_html( exs_option( 'header_image_background_image_cover', false ) ),
				),
				'header_image_background_image_fixed'   => array(
					'type'    => 'checkbox',
					'section' => 'header_image',
					'label'   => esc_html__( 'Fixed background image', 'exs' ),
					'default' => esc_html( exs_option( 'header_image_background_image_fixed', false ) ),
				),
				'header_image_background_image_overlay' => array(
					'type'    => 'select',
					'section' => 'header_image',
					'label'   => esc_html__( 'Overlay for background image', 'exs' ),
					'default' => esc_html( exs_option( 'header_image_background_image_overlay', '' ) ),
					'choices' => exs_customizer_background_overlay_array(),
				),
				'header_image_background_image_overlay_opacity' => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Overlay Opacity', 'exs' ),
					'default'     => esc_html( exs_option( 'header_image_background_image_overlay_opacity', '' ) ),
					'section'     => 'header_image',
					'atts'        => array(
						'min'         => '1',
						'max'         => '99',
						'step'        => '1',
					),
				),
				////////////////
				//bottom image//
				////////////////
				//bottom image options
				//section 'bottom_image'
				'bottom_background_image'               => array(
					'type'    => 'image',
					'section' => 'panel_bottom_image',
					'label'   => esc_html__( 'Site Bottom Background Image', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_background_image', '' ) ),
				),
				'bottom_background_image_cover'   => array(
					'type'    => 'checkbox',
					'section' => 'panel_bottom_image',
					'label'   => esc_html__( 'Cover background image', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_background_image_cover', false ) ),
				),
				'bottom_background_image_fixed'   => array(
					'type'    => 'checkbox',
					'section' => 'panel_bottom_image',
					'label'   => esc_html__( 'Fixed background image', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_background_image_fixed', false ) ),
				),
				'bottom_background_image_overlay' => array(
					'type'    => 'select',
					'section' => 'panel_bottom_image',
					'label'   => esc_html__( 'Overlay for background image', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_background_image_overlay', '' ) ),
					'choices' => exs_customizer_background_overlay_array(),
				),
				'bottom_background_image_overlay_opacity' => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Overlay Opacity', 'exs' ),
					'default'     => esc_html( exs_option( 'bottom_background_image_overlay_opacity', '' ) ),
					'section'     => 'panel_bottom_image',
					'atts'        => array(
						'min'         => '1',
						'max'         => '99',
						'step'        => '1',
					),
				),
				//main menu
				'header_menu_options_heading_desktop'        => array(
					'type'        => 'block-heading',
					'section'     => 'section_top_nav',
					'label'       => esc_html__( 'Desktop Menu Options', 'exs' ),
					'description' => esc_html__( 'Options related to the main menu on big screens.', 'exs' ),
				),
				'header_align_main_menu'                => array(
					'type'    => 'select',
					'section' => 'section_top_nav',
					'label'   => esc_html__( 'Align main menu', 'exs' ),
					'default' => esc_html( exs_option( 'header_align_main_menu', true ) ),
					'choices' => array(
						''            => esc_html__( 'Default', 'exs' ),
						'menu-right'  => esc_html__( 'Right', 'exs' ),
						'menu-center' => esc_html__( 'Center', 'exs' ),
					),
				),

				'header_menu_uppercase'                 => array(
					'type'        => 'checkbox',
					'section'     => 'section_top_nav',
					'label'       => esc_html__( 'Uppercase menu items', 'exs' ),
					'default'     => esc_html( exs_option( 'header_menu_uppercase', false ) ),
					'description' => esc_html__( 'For desktop menu. Can be overridden by skin', 'exs' ),
				),
				'header_menu_bold'                      => array(
					'type'        => 'checkbox',
					'section'     => 'section_top_nav',
					'label'       => esc_html__( 'Bold menu items', 'exs' ),
					'default'     => esc_html( exs_option( 'header_menu_bold', false ) ),
					'description' => esc_html__( 'For desktop menu. Can be overridden by skin', 'exs' ),
				),
				'menu_desktop'                    => array(
					'type'    => 'select',
					'section' => 'section_top_nav',
					'label'   => esc_html__( 'Desktop menu type', 'exs' ),
					'default' => esc_html( exs_option( 'menu_desktop', '' ) ),
					'choices' => array(
						'' => esc_html__( 'Default', 'exs' ),
						'1' => esc_html__( 'Vertical flip', 'exs' ),
						'2' => esc_html__( 'Animated pills', 'exs' ),
						'3' => esc_html__( 'Half height underline', 'exs' ),
						'4' => esc_html__( 'Corners Decor', 'exs' ),
					),
				),

				'header_menu_options_heading_mobile'        => array(
					'type'        => 'block-heading',
					'section'     => 'section_top_nav',
					'label'       => esc_html__( 'Mobile Menu Options', 'exs' ),
					'description' => esc_html__( 'Options related to the main menu on small screens.', 'exs' ),
				),
				'header_toggler_menu_main'              => array(
					'type'    => 'checkbox',
					'section' => 'section_top_nav',
					'label'   => esc_html__( 'Put main menu mobile toggler in the header', 'exs' ),
					'default' => esc_html( exs_option( 'header_toggler_menu_main', true ) ),
					'description' => esc_html__( 'Show mobile menu toggler in the header or fix it in the top right corner of the screen', 'exs' ),
				),
				'header_toggler_menu_main_center'              => array(
					'type'    => 'checkbox',
					'section' => 'section_top_nav',
					'label'   => esc_html__( 'Centered toggler in the header', 'exs' ),
					'default' => esc_html( exs_option( 'header_toggler_menu_main_center', false ) ),
				),
				'menu_breakpoint'                   => array(
					'type'        => 'slider',
					'section' => 'section_top_nav',
					'label'   => esc_html__( 'Mobile Menu Breakpoint (px)', 'exs' ),
					'default' => esc_html( exs_option( 'menu_breakpoint', '1200' ) ),
					'atts'        => array(
						'min'         => '320',
						'max'         => '1600',
						'step'        => '1',
					),
				),
				'mobile_nav_width'                   => array(
					'type'        => 'slider',
					'section' => 'section_top_nav',
					'label'   => esc_html__( 'Mobile Menu Width (px)', 'exs' ),
					'default' => esc_html( exs_option( 'mobile_nav_width', '290' ) ),
					'atts'        => array(
						'min'         => '200',
						'max'         => '500',
						'step'        => '10',
					),
				),
				'mobile_nav_px'                   => array(
					'type'        => 'slider',
					'section' => 'section_top_nav',
					'label'   => esc_html__( 'Mobile Menu Horizontal Padding (px)', 'exs' ),
					'default' => esc_html( exs_option( 'mobile_nav_px', '20' ) ),
					'atts'        => array(
						'min'         => '0',
						'max'         => '60',
						'step'        => '5',
					),
				),
				'menu_mobile'                    => array(
					'type'    => 'select',
					'section' => 'section_top_nav',
					'label'   => esc_html__( 'Mobile menu type', 'exs' ),
					'default' => esc_html( exs_option( 'menu_mobile', '' ) ),
					'choices' => array(
						'' => esc_html__( 'Default', 'exs' ),
						'1' => esc_html__( 'Dark Default', 'exs' ),
						'2' => esc_html__( 'Full Width', 'exs' ),
						'3' => esc_html__( 'Full Width Centered', 'exs' ),
						'4' => esc_html__( 'Dark Full Width', 'exs' ),
						'5' => esc_html__( 'Dark Full Width Centered', 'exs' ),
						'6' => esc_html__( 'Blur background effect', 'exs' ),
						'7' => esc_html__( 'Bordered list', 'exs' ),
						'8' => esc_html__( 'Dark bordered list', 'exs' ),
					),
				),
				'menu_mobile_show_logo'              => array(
					'type'    => 'checkbox',
					'section' => 'section_top_nav',
					'label'   => esc_html__( 'Show logo in the mobile menu', 'exs' ),
					'default' => esc_html( exs_option( 'menu_mobile_show_logo', false ) ),
				),
				'menu_mobile_show_search'              => array(
					'type'    => 'checkbox',
					'section' => 'section_top_nav',
					'label'   => esc_html__( 'Show search form in the mobile menu', 'exs' ),
					'default' => esc_html( exs_option( 'menu_mobile_show_search', false ) ),
				),
				'menu_mobile_show_meta'              => array(
					'type'    => 'checkbox',
					'section' => 'section_top_nav',
					'label'   => esc_html__( 'Show theme meta in the mobile menu', 'exs' ),
					'default' => esc_html( exs_option( 'menu_mobile_show_meta', false ) ),
				),
				'menu_mobile_show_social'              => array(
					'type'    => 'checkbox',
					'section' => 'section_top_nav',
					'label'   => esc_html__( 'Show social links in the mobile menu', 'exs' ),
					'default' => esc_html( exs_option( 'menu_mobile_show_social', false ) ),
				),

				//bottom fixed nav
				'bottom_nav_height'                   => array(
					'type'        => 'slider',
					'section' => 'section_bottom_nav',
					'label'   => esc_html__( 'Bottom Menu Height', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_nav_height', '60' ) ),
					'atts'        => array(
						'min'         => '20',
						'max'         => '120',
						'step'        => '5',
					),
				),
				'bottom_nav_background'                     => array(
					'type'    => 'color-radio',
					'section' => 'section_bottom_nav',
					'label'   => esc_html__( 'Bottom Menu Background', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_nav_background', 'l' ) ),
					'choices' => exs_customizer_backgrounds_array( true ),
				),
				'bottom_nav_border'                     => array(
					'type'    => 'checkbox',
					'section' => 'section_bottom_nav',
					'label'   => esc_html__( 'Bottom Menu Border', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_nav_border', '1' ) ),
				),
				'bottom_nav_shadow'                     => array(
					'type'    => 'checkbox',
					'section' => 'section_bottom_nav',
					'label'   => esc_html__( 'Bottom Menu Shadow', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_nav_shadow', '' ) ),
				),
				'bottom_nav_font_size'                            => array(
					'type'    => 'slider',
					'section' => 'section_bottom_nav',
					'label'   => esc_html__( 'Bottom Menu font size', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_nav_font_size', '' ) ),
					'description' => esc_html__( 'Value in pixels', 'exs' ),
					'atts'        => array(
						'min'         => '9',
						'max'         => '26',
						'step'        => '1',
					),
				),
				'bottom_nav_bold'                     => array(
					'type'    => 'checkbox',
					'section' => 'section_bottom_nav',
					'label'   => esc_html__( 'Bold Text', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_nav_bold', '' ) ),
				),
				'bottom_nav_uppercase'                     => array(
					'type'    => 'checkbox',
					'section' => 'section_bottom_nav',
					'label'   => esc_html__( 'Uppercase Text', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_nav_uppercase', '' ) ),
				),
				'bottom_nav_show_social'                     => array(
					'type'    => 'checkbox',
					'section' => 'section_bottom_nav',
					'label'   => esc_html__( 'Show social icons', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_nav_show_social', '' ) ),
				),
				//menu icons in the bottom fixed menu
				'bottom_nav_icons_heading'        => array(
					'type'        => 'block-heading',
					'section'     => 'section_bottom_nav',
					'label'       => esc_html__( 'Menu Icons Options', 'exs' ),
					'active_callback' => function() { return class_exists( 'Menu_Icons' ); }
				),
				'bottom_nav_icons_center'                     => array(
					'type'    => 'checkbox',
					'section' => 'section_bottom_nav',
					'label'   => esc_html__( 'Top Centered Icons', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_nav_icons_center', '' ) ),
					'active_callback' => function() { return class_exists( 'Menu_Icons' ); }
				),
				'bottom_nav_icon_labels_hidden'                    => array(
					'type'    => 'select',
					'section' => 'section_bottom_nav',
					'label'   => esc_html__( 'Hide icon labels on screens:', 'exs' ),
					'default' => esc_html( exs_option( 'bottom_nav_icon_labels_hidden', '' ) ),
					'choices' => exs_customizer_responsive_display_array(),
					'active_callback' => function() { return class_exists( 'Menu_Icons' ); }
				),

				//fixed sidebar
				'fixed_sidebar_breakpoint'                   => array(
					'type'        => 'slider',
					'section' => 'section_fixed_sidebar',
					'label'   => esc_html__( 'Fixed Sidebar Breakpoint (px)', 'exs' ),
					'default' => esc_html( exs_option( 'fixed_sidebar_breakpoint', '1200' ) ),
					'atts'        => array(
						'min'         => '320',
						'max'         => '1600',
						'step'        => '1',
					),
				),
				'fixed_sidebar_width'                   => array(
					'type'        => 'slider',
					'section' => 'section_fixed_sidebar',
					'label'   => esc_html__( 'Fixed Sidebar Width (px)', 'exs' ),
					'default' => esc_html( exs_option( 'fixed_sidebar_width', '320' ) ),
					'atts'        => array(
						'min'         => '200',
						'max'         => '600',
						'step'        => '10',
					),
				),
				'fixed_sidebar_px'                   => array(
					'type'        => 'slider',
					'section' => 'section_fixed_sidebar',
					'label'   => esc_html__( 'Fixed Sidebar Horizontal Padding (px)', 'exs' ),
					'default' => esc_html( exs_option( 'fixed_sidebar_px', '30' ) ),
					'atts'        => array(
						'min'         => '0',
						'max'         => '60',
						'step'        => '5',
					),
				),
				'fixed_sidebar_background'                     => array(
					'type'    => 'color-radio',
					'section' => 'section_fixed_sidebar',
					'label'   => esc_html__( 'Fixed Sidebar Background', 'exs' ),
					'default' => esc_html( exs_option( 'fixed_sidebar_background', 'l' ) ),
					'choices' => exs_customizer_backgrounds_array( true ),
				),
				'fixed_sidebar_border'                     => array(
					'type'    => 'checkbox',
					'section' => 'section_fixed_sidebar',
					'label'   => esc_html__( 'Fixed Sidebar Border', 'exs' ),
					'default' => esc_html( exs_option( 'fixed_sidebar_border', '1' ) ),
				),
				'fixed_sidebar_shadow'                     => array(
					'type'    => 'checkbox',
					'section' => 'section_fixed_sidebar',
					'label'   => esc_html__( 'Fixed Sidebar Shadow', 'exs' ),
					'default' => esc_html( exs_option( 'fixed_sidebar_shadow', '' ) ),
				),
				'fixed_sidebar_font_size'                            => array(
					'type'    => 'slider',
					'section' => 'section_fixed_sidebar',
					'label'   => esc_html__( 'Fixed Sidebar font size', 'exs' ),
					'default' => esc_html( exs_option( 'fixed_sidebar_font_size', '' ) ),
					'description' => esc_html__( 'Value in pixels', 'exs' ),
					'atts'        => array(
						'min'         => '9',
						'max'         => '26',
						'step'        => '1',
					),
				),

				//header
				'header'                                => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Header Layout', 'exs' ),
					'default' => esc_html( exs_option( 'header', '1' ) ),
					'choices' => array(
						''  => esc_html__( 'Disabled', 'exs' ),
						'1' => esc_html__( 'Left logo and right menu', 'exs' ),
						'2' => esc_html__( 'Top centered logo and bottom menu', 'exs' ),
						'3' => esc_html__( 'Top left logo and bottom menu', 'exs' ),
						'4' => esc_html__( 'Topline inside header section', 'exs' ),
						'5' => esc_html__( 'Bottom left logo and top menu', 'exs' ),
						'6' => esc_html__( 'Bottom centered logo and top menu', 'exs' ),
						'7' => esc_html__( 'Logo between menu items', 'exs' ),
						'8' => esc_html__( 'Top left logo with big meta info', 'exs' ),
					),
				),
				'header_logo_hidden'                    => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Hide logo in header section on screens:', 'exs' ),
					'default' => esc_html( exs_option( 'header_logo_hidden', '' ) ),
					'choices' => exs_customizer_responsive_display_array(),
				),
				'header_fluid'                          => array(
					'type'    => 'checkbox',
					'section' => 'section_header',
					'label'   => esc_html__( 'Full Width Header', 'exs' ),
					'default' => esc_html( exs_option( 'header_fluid', true ) ),
				),
				'header_background'                     => array(
					'type'    => 'color-radio',
					'section' => 'section_header',
					'label'   => esc_html__( 'Header Background', 'exs' ),
					'default' => esc_html( exs_option( 'header_background', 'l' ) ),
					'choices' => exs_customizer_backgrounds_array( true ),
				),
				'header_absolute'                       => array(
					'type'    => 'checkbox',
					'section' => 'section_header',
					'label'   => esc_html__( 'Position absolute header', 'exs' ),
					'default' => esc_html( exs_option( 'header_absolute', false ) ),
				),
				'header_transparent'                    => array(
					'type'        => 'checkbox',
					'section'     => 'section_header',
					'label'       => esc_html__( 'Remove background color', 'exs' ),
					'description' => esc_html__( 'Make header transparent', 'exs' ),
					'default'     => esc_html( exs_option( 'header_transparent', false ) ),
				),
				'header_border_top'                     => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Top border', 'exs' ),
					'default' => esc_html( exs_option( 'header_border_top', '' ) ),
					'choices' => exs_customizer_borders_array(),
				),
				'header_border_bottom'                  => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Bottom border', 'exs' ),
					'default' => esc_html( exs_option( 'header_border_bottom', '' ) ),
					'choices' => exs_customizer_borders_array(),
				),
				'header_font_size'                      => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Header section font size', 'exs' ),
					'default' => esc_html( exs_option( 'header_font_size', '' ) ),
					'choices' => exs_customizer_font_size_array(),
				),
				'header_sticky'                         => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Sticky Header', 'exs' ),
					'default' => esc_html( exs_option( 'header_sticky', false ) ),
					'choices' => array(
						''                 => esc_html__( 'Disabled', 'exs' ),
						'always-sticky'    => esc_html__( 'Always visible', 'exs' ),
						'scrolltop-sticky' => esc_html__( 'Visible on scrolling to top', 'exs' ),
					),
				),
				'header_login_links'                    => array(
					'type'        => 'checkbox',
					'section'     => 'section_header',
					'label'       => esc_html__( 'Show Login/Logout links', 'exs' ),
					'default'     => esc_html( exs_option( 'header_login_links', false ) ),
					'description' => esc_html__( 'Show login link if user is not logged in. Show register link if registration enabled. Show logout link if user is logged in.', 'exs' ),
				),
				'header_login_links_hidden'             => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Hide Login/Logout links on screens:', 'exs' ),
					'default' => esc_html( exs_option( 'header_login_links_hidden', '' ) ),
					'choices' => exs_customizer_responsive_display_array(),
				),
				'header_search'                         => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Show Search in Header', 'exs' ),
					'default' => esc_html( exs_option( 'header_search', '' ) ),
					'choices' => array(
						''       => esc_html__( 'Disabled', 'exs' ),
						'button' => esc_html__( 'Search Modal button', 'exs' ),
						'form'   => esc_html__( 'Search Form', 'exs' ),
					),
				),
				'header_search_hidden'                    => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Hide search on screens:', 'exs' ),
					'default' => esc_html( exs_option( 'header_search_hidden', '' ) ),
					'choices' => exs_customizer_responsive_display_array(),
				),
				'header_button_text'                    => array(
					'type'    => 'text',
					'section' => 'section_header',
					'label'   => esc_html__( 'Header Action Button Text', 'exs' ),
					'default' => esc_html( exs_option( 'header_button_text', '' ) ),
				),
				'header_button_url'                     => array(
					'type'    => 'url',
					'section' => 'section_header',
					'label'   => esc_html__( 'Header Action Button URL', 'exs' ),
					'default' => esc_html( exs_option( 'header_button_url', '' ) ),
				),
				'header_button_hidden'                    => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Hide Action Button on screens:', 'exs' ),
					'default' => esc_html( exs_option( 'header_button_hidden', '' ) ),
					'choices' => exs_customizer_responsive_display_array(),
				),
				//social icons can appear not only on top logo , but in the header (8) as well
				//so we moved it from the top logo section here in the header section
				'header_toplogo_social_hidden'                    => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Hide Header Social icons on screens:', 'exs' ),
					'default' => esc_html( exs_option( 'header_toplogo_social_hidden', '' ) ),
					'choices' => exs_customizer_responsive_display_array(),
				),
				//toplogo in header
				'header_toplogo_options_heading'        => array(
					'type'        => 'block-heading',
					'section'     => 'section_header',
					'label'       => esc_html__( 'Header Logo section options', 'exs' ),
					'description' => esc_html__( 'Header Logo section appears only on certain heading layouts where logo is above or below the header (optional).', 'exs' ),
				),
				'header_toplogo_background'             => array(
					'type'        => 'color-radio',
					'section'     => 'section_header',
					'label'       => esc_html__( 'Header Logo Section Background', 'exs' ),
					'default'     => esc_html( exs_option( 'header_toplogo_background', 'l' ) ),
					'description' => esc_html__( 'Background for top logo section, if header layout contains it', 'exs' ),
					'choices'     => exs_customizer_backgrounds_array( true ),
				),
				'header_toplogo_border_top'                     => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Header Logo Section Top border', 'exs' ),
					'default' => esc_html( exs_option( 'header_toplogo_border_top', '' ) ),
					'choices' => exs_customizer_borders_array(),
				),
				'header_toplogo_hidden'                    => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Hide whole Logo Section on screens:', 'exs' ),
					'default' => esc_html( exs_option( 'header_toplogo_hidden', '' ) ),
					'choices' => exs_customizer_responsive_display_array(),
				),
				'header_toplogo_meta_hidden'                    => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Hide Header Logo Section Meta on screens:', 'exs' ),
					'default' => esc_html( exs_option( 'header_toplogo_meta_hidden', '' ) ),
					'choices' => exs_customizer_responsive_display_array(),
				),
				'header_toplogo_search_hidden'                    => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Hide Header Section Logo Search on screens:', 'exs' ),
					'default' => esc_html( exs_option( 'header_toplogo_search_hidden', '' ) ),
					'choices' => exs_customizer_responsive_display_array(),
				),

				//topline in header
				//heading
				'header_topline_options_heading'        => array(
					'type'        => 'block-heading',
					'section'     => 'section_header',
					'label'       => esc_html__( 'Header Topline section options', 'exs' ),
					'description' => esc_html__( 'You need to fill theme meta options to show them in header topline.', 'exs' ),
				),
				'topline'                               => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Topline Layout', 'exs' ),
					'default' => esc_html( exs_option( 'topline', '' ) ),
					'choices' => array(
						''  => esc_html__( 'Disabled', 'exs' ),
						'1' => esc_html__( 'Left meta and right social links', 'exs' ),
						'2' => esc_html__( 'Left menu and right social links', 'exs' ),
						'3' => esc_html__( 'Left social links and right menu', 'exs' ),
						'4' => esc_html__( 'Left custom text and right social links', 'exs' ),
						'5' => esc_html__( 'Fullwidth topline widget area', 'exs' ),
					),
				),
				'topline_fluid'                         => array(
					'type'    => 'checkbox',
					'section' => 'section_header',
					'label'   => esc_html__( 'Full Width Header Topline', 'exs' ),
					'default' => esc_html( exs_option( 'topline_fluid', true ) ),
				),
				'topline_background'                    => array(
					'type'    => 'color-radio',
					'section' => 'section_header',
					'label'   => esc_html__( 'Header Topline Background', 'exs' ),
					'default' => esc_html( exs_option( 'topline_background', 'l' ) ),
					'choices' => exs_customizer_backgrounds_array( true ),
				),
				'meta_topline_text'                     => array(
					'type'        => 'textarea',
					'section'     => 'section_header',
					'label'       => esc_html__( 'Topline custom text', 'exs' ),
					'description' => esc_html__( 'Appears on different topline layouts', 'exs' ),
					'default'     => esc_html( exs_option( 'meta_topline_text', '' ) ),
				),
				'topline_font_size'                     => array(
					'type'    => 'select',
					'section' => 'section_header',
					'label'   => esc_html__( 'Topline section font size', 'exs' ),
					'default' => esc_html( exs_option( 'topline_font_size', '' ) ),
					'choices' => exs_customizer_font_size_array(),
				),
				'topline_login_links'                    => array(
					'type'        => 'checkbox',
					'section'     => 'section_header',
					'label'       => esc_html__( 'Show Login/Logout links', 'exs' ),
					'default'     => esc_html( exs_option( 'topline_login_links', false ) ),
					'description' => esc_html__( 'Show login link if user is not logged in. Show register link if registration enabled. Show logout link if user is logged in.', 'exs' ),
				),
				'topline_disable_dropdown'                    => array(
					'type'        => 'checkbox',
					'section'     => 'section_header',
					'label'       => esc_html__( 'Disable topline dropdown for small screens', 'exs' ),
					'default'     => esc_html( exs_option( 'topline_disable_dropdown', false ) ),
					'description' => esc_html__( 'Topline menu and meta info are hidden into dropdown menu on small screens by default. You can disable this behavior by changing this option.', 'exs' ),
				),

				/////////////////
				//header_bottom//
				/////////////////
				'header_bottom'                                => array(
					'type'    => 'select',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Header Bottom Section Layout', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom', '1' ) ),
					'choices' => array(
						''  => esc_html__( 'Disabled', 'exs' ),
						'1' => esc_html__( 'Equal columns', 'exs' ),
						'4' => esc_html__( 'Full Width', 'exs' ),
						'5' => esc_html__( 'Full Width centered', 'exs' ),

					),
				),
				'header_bottom_layout_gap'                     => array(
					'type'        => 'select',
					'section'     => 'section_header_bottom',
					'label'       => esc_html__( 'Header Bottom widgets gap', 'exs' ),
					'description' => esc_html__( 'Used only for multiple columns layouts', 'exs' ),
					'default'     => esc_html( exs_option( 'header_bottom_layout_gap', '' ) ),
					'choices'     => exs_get_feed_layout_gap_options(),
				),
				'header_bottom_fluid'                          => array(
					'type'    => 'checkbox',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Full Width Section', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_fluid', false ) ),
				),
				'header_bottom_background'                     => array(
					'type'    => 'color-radio',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Header Bottom Background', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_background', '' ) ),
					'choices' => exs_customizer_backgrounds_array(),
				),
				'header_bottom_border_top'                     => array(
					'type'    => 'select',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Top border', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_border_top', '' ) ),
					'choices' => exs_customizer_borders_array(),
				),
				'header_bottom_border_bottom'                  => array(
					'type'    => 'select',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Bottom border', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_border_bottom', '' ) ),
					'choices' => exs_customizer_borders_array(),
				),
				'header_bottom_extra_padding_top'              => array(
					'type'    => 'select',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Top padding', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_extra_padding_top', 'pt-1' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pt-0'  => esc_html__( '0', 'exs' ),
						'pt-1'  => esc_html__( '1em', 'exs' ),
						'pt-2'  => esc_html__( '2em', 'exs' ),
						'pt-3'  => esc_html__( '3em', 'exs' ),
						'pt-4'  => esc_html__( '4em', 'exs' ),
						'pt-5'  => esc_html__( '5em', 'exs' ),
						'pt-6'  => esc_html__( '6em', 'exs' ),
						'pt-7'  => esc_html__( '7em', 'exs' ),
						'pt-8'  => esc_html__( '8em', 'exs' ),
						'pt-9'  => esc_html__( '9em', 'exs' ),
						'pt-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'header_bottom_extra_padding_bottom'           => array(
					'type'    => 'select',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Bottom padding', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_extra_padding_bottom', 'pb-1' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pb-0'  => esc_html__( '0', 'exs' ),
						'pb-1'  => esc_html__( '1em', 'exs' ),
						'pb-2'  => esc_html__( '2em', 'exs' ),
						'pb-3'  => esc_html__( '3em', 'exs' ),
						'pb-4'  => esc_html__( '4em', 'exs' ),
						'pb-5'  => esc_html__( '5em', 'exs' ),
						'pb-6'  => esc_html__( '6em', 'exs' ),
						'pb-7'  => esc_html__( '7em', 'exs' ),
						'pb-8'  => esc_html__( '8em', 'exs' ),
						'pb-9'  => esc_html__( '9em', 'exs' ),
						'pb-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'header_bottom_font_size'                      => array(
					'type'    => 'select',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Header Bottom section font size', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_font_size', '' ) ),
					'choices' => exs_customizer_font_size_array(),
				),
				'header_bottom_background_image_heading'      => array(
					'type'        => 'block-heading',
					'section'     => 'section_header_bottom',
					'label'       => esc_html__( 'Background image settings', 'exs' ),
				),
				'header_bottom_background_image'               => array(
					'type'    => 'image',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Header Bottom Section Background Image', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_background_image', '' ) ),
				),
				'header_bottom_background_image_cover'         => array(
					'type'    => 'checkbox',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Cover background image', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_background_image_cover', false ) ),
				),
				'header_bottom_background_image_fixed'         => array(
					'type'    => 'checkbox',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Fixed background image', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_background_image_fixed', false ) ),
				),
				'header_bottom_background_image_overlay'       => array(
					'type'    => 'select',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Overlay for background image', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_background_image_overlay', '' ) ),
					'choices' => exs_customizer_background_overlay_array(),
				),
				'header_bottom_background_image_overlay_opacity' => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Overlay Opacity', 'exs' ),
					'default'     => esc_html( exs_option( 'header_bottom_background_image_overlay_opacity', '' ) ),
					'section'     => 'section_header_bottom',
					'atts'        => array(
						'min'         => '1',
						'max'         => '99',
						'step'        => '1',
					),
				),
				'header_bottom_hide_widget_titles'         => array(
					'type'    => 'checkbox',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Hide widget titles in the section', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_hide_widget_titles', false ) ),
				),
				'header_bottom_lists_inline'         => array(
					'type'    => 'checkbox',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Make lists inside widgets inline', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_lists_inline', false ) ),
				),
				'header_bottom_hidden'                    => array(
					'type'    => 'select',
					'section' => 'section_header_bottom',
					'label'   => esc_html__( 'Hide Header Bottom Section on screens:', 'exs' ),
					'default' => esc_html( exs_option( 'header_bottom_hidden', '' ) ),
					'choices' => exs_customizer_responsive_display_array(),
				),

				/////////
				//title//
				/////////
				'title'                                 => array(
					'type'    => 'select',
					'section' => 'section_title',
					'label'   => esc_html__( 'Title Layout', 'exs' ),
					'default' => esc_html( exs_option( 'title', '1' ) ),
					'choices' => array(
						'1' => esc_html__( 'Title above breadcrumbs', 'exs' ),
						'2' => esc_html__( 'Title inline with breadcrumbs', 'exs' ),
						'3' => esc_html__( 'Title above breadcrumbs centered', 'exs' ),
						'4' => esc_html__( 'Title below breadcrumbs', 'exs' ),
						'5' => esc_html__( 'Title below breadcrumbs centered', 'exs' ),
						'6' => esc_html__( 'Title with a post featured image', 'exs' ),
					),
				),
				'title_fluid'                         => array(
					'type'    => 'checkbox',
					'section' => 'section_title',
					'label'   => esc_html__( 'Full Width Title Section', 'exs' ),
					'default' => esc_html( exs_option( 'title_fluid' ) ),
				),
				'title_show_title'                      => array(
					'type'    => 'checkbox',
					'section' => 'section_title',
					'label'   => esc_html__( 'Show title in title section instead of content area', 'exs' ),
					'default' => esc_html( exs_option( 'title_show_title', '' ) ),
				),
				'title_show_breadcrumbs'                => array(
					'type'    => 'checkbox',
					'section' => 'section_title',
					'label'   => esc_html__( 'Show breadcrumbs (Yoast SEO or Rank Math plugins required)', 'exs' ),
					'default' => esc_html( exs_option( 'title_show_breadcrumbs', true ) ),
				),
				'title_show_search'                     => array(
					'type'    => 'checkbox',
					'section' => 'section_title',
					'label'   => esc_html__( 'Show search form', 'exs' ),
					'default' => esc_html( exs_option( 'title_show_search', false ) ),
				),
				'title_background'                      => array(
					'type'    => 'color-radio',
					'section' => 'section_title',
					'label'   => esc_html__( 'Title Background', 'exs' ),
					'default' => esc_html( exs_option( 'title_background', '' ) ),
					'choices' => exs_customizer_backgrounds_array(),
				),
				'title_border_top'                      => array(
					'type'    => 'select',
					'section' => 'section_title',
					'label'   => esc_html__( 'Top border', 'exs' ),
					'default' => esc_html( exs_option( 'title_border_top', '' ) ),
					'choices' => exs_customizer_borders_array(),
				),
				'title_border_bottom'                   => array(
					'type'    => 'select',
					'section' => 'section_title',
					'label'   => esc_html__( 'Bottom border', 'exs' ),
					'default' => esc_html( exs_option( 'title_border_bottom', '' ) ),
					'choices' => exs_customizer_borders_array(),
				),
				'title_extra_padding_top'               => array(
					'type'    => 'select',
					'section' => 'section_title',
					'label'   => esc_html__( 'Top padding', 'exs' ),
					'default' => esc_html( exs_option( 'title_extra_padding_top', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pt-0'  => esc_html__( '0', 'exs' ),
						'pt-1'  => esc_html__( '1em', 'exs' ),
						'pt-2'  => esc_html__( '2em', 'exs' ),
						'pt-3'  => esc_html__( '3em', 'exs' ),
						'pt-4'  => esc_html__( '4em', 'exs' ),
						'pt-5'  => esc_html__( '5em', 'exs' ),
						'pt-6'  => esc_html__( '6em', 'exs' ),
						'pt-7'  => esc_html__( '7em', 'exs' ),
						'pt-8'  => esc_html__( '8em', 'exs' ),
						'pt-9'  => esc_html__( '9em', 'exs' ),
						'pt-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'title_extra_padding_bottom'            => array(
					'type'    => 'select',
					'section' => 'section_title',
					'label'   => esc_html__( 'Bottom padding', 'exs' ),
					'default' => esc_html( exs_option( 'title_extra_padding_bottom', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pb-0'  => esc_html__( '0', 'exs' ),
						'pb-1'  => esc_html__( '1em', 'exs' ),
						'pb-2'  => esc_html__( '2em', 'exs' ),
						'pb-3'  => esc_html__( '3em', 'exs' ),
						'pb-4'  => esc_html__( '4em', 'exs' ),
						'pb-5'  => esc_html__( '5em', 'exs' ),
						'pb-6'  => esc_html__( '6em', 'exs' ),
						'pb-7'  => esc_html__( '7em', 'exs' ),
						'pb-8'  => esc_html__( '8em', 'exs' ),
						'pb-9'  => esc_html__( '9em', 'exs' ),
						'pb-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'title_font_size'                       => array(
					'type'    => 'select',
					'section' => 'section_title',
					'label'   => esc_html__( 'Title section font size', 'exs' ),
					'default' => esc_html( exs_option( 'title_font_size', '' ) ),
					'choices' => exs_customizer_font_size_array(),
				),
				'title_hide_taxonomy_name'              => array(
					'type'        => 'checkbox',
					'section'     => 'section_title',
					'label'       => esc_html__( 'Hide taxonomy name', 'exs' ),
					'description' => esc_html__( 'You can hide a taxonomy name on taxonomy archives page', 'exs' ),
					'default'     => esc_html( exs_option( 'title_hide_taxonomy_name', false ) ),
				),

				'title_background_image_heading'      => array(
					'type'        => 'block-heading',
					'section'     => 'section_title',
					'label'       => esc_html__( 'Background image settings', 'exs' ),
				),
				'title_background_image'                => array(
					'type'    => 'image',
					'section' => 'section_title',
					'label'   => esc_html__( 'Title Section Background Image', 'exs' ),
					'default' => esc_html( exs_option( 'title_background_image', '' ) ),
				),
				'title_background_image_cover'          => array(
					'type'    => 'checkbox',
					'section' => 'section_title',
					'label'   => esc_html__( 'Cover background image', 'exs' ),
					'default' => esc_html( exs_option( 'title_background_image_cover', false ) ),
				),
				'title_background_image_fixed'          => array(
					'type'    => 'checkbox',
					'section' => 'section_title',
					'label'   => esc_html__( 'Fixed background image', 'exs' ),
					'default' => esc_html( exs_option( 'title_background_image_fixed', false ) ),
				),
				'title_background_image_overlay'        => array(
					'type'    => 'select',
					'section' => 'section_title',
					'label'   => esc_html__( 'Overlay for background image', 'exs' ),
					'default' => esc_html( exs_option( 'title_background_image_overlay', '' ) ),
					'choices' => exs_customizer_background_overlay_array(),
				),
				'title_background_image_overlay_opacity' => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Overlay Opacity', 'exs' ),
					'default'     => esc_html( exs_option( 'title_background_image_overlay_opacity', '' ) ),
					'section'     => 'section_title',
					'atts'        => array(
						'min'         => '1',
						'max'         => '99',
						'step'        => '1',
					),
				),

				'title_single_post_meta_heading'        => array(
					'type'        => 'block-heading',
					'section'     => 'section_title',
					'label'       => esc_html__( 'Single Post Meta in the Title Section', 'exs' ),
					'description' => esc_html__( 'You can show single post meta in the title section', 'exs' ),
				),
				'title_blog_single_hide_meta_icons'           => array(
					'type'    => 'checkbox',
					'section' => 'section_title',
					'label'   => esc_html__( 'Hide icons in the post meta', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_hide_meta_icons', true ) ),
				),
				'title_blog_single_show_author'               => array(
					'type'    => 'checkbox',
					'section' => 'section_title',
					'label'   => esc_html__( 'Show author', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_show_author', false ) ),
				),
				'title_blog_single_show_author_avatar'        => array(
					'type'    => 'checkbox',
					'section' => 'section_title',
					'label'   => esc_html__( 'Show author avatar', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_show_author_avatar', false ) ),
				),
				'title_blog_single_before_author_word'        => array(
					'type'    => 'text',
					'section' => 'section_title',
					'label'   => esc_html__( 'Text before author', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_before_author_word', '' ) ),
				),
				'title_blog_single_show_date'                 => array(
					'type'    => 'checkbox',
					'section' => 'section_title',
					'label'   => esc_html__( 'Show date', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_show_date', false ) ),
				),
				'title_blog_single_before_date_word'          => array(
					'type'    => 'text',
					'section' => 'section_title',
					'label'   => esc_html__( 'Text before date', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_before_date_word', '' ) ),
				),
				'title_blog_single_show_human_date'                        => array(
					'type'    => 'checkbox',
					'section' => 'section_title',
					'label'   => esc_html__( 'Show human difference date', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_show_human_date', false ) ),
				),
				'title_blog_single_show_date_type'                        => array(
					'type'    => 'select',
					'section' => 'section_title',
					'label'   => esc_html__( 'Date to display', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_show_date_type', 'publish' ) ),
					'choices' => array(
						'publish' => esc_html__( 'Date published', 'exs' ),
						'modify'  => esc_html__( 'Date modified', 'exs' ),
						'both'    => esc_html__( 'Dade published and modified', 'exs' ),
					),
				),
				'title_blog_single_before_date_modify_word'                 => array(
					'type'    => 'text',
					'section' => 'section_title',
					'label'   => esc_html__( 'Text before date modified', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_before_date_modify_word', '' ) ),
				),
				'title_blog_single_show_categories'           => array(
					'type'    => 'checkbox',
					'section' => 'section_title',
					'label'   => esc_html__( 'Show categories', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_show_categories', false ) ),
				),
				'title_blog_single_before_categories_word'    => array(
					'type'    => 'text',
					'section' => 'section_title',
					'label'   => esc_html__( 'Text before categories', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_before_categories_word', '' ) ),
				),
				'title_blog_single_show_tags'                 => array(
					'type'    => 'checkbox',
					'section' => 'section_title',
					'label'   => esc_html__( 'Show tags', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_show_tags', false ) ),
				),
				'title_blog_single_before_tags_word'          => array(
					'type'    => 'text',
					'section' => 'section_title',
					'label'   => esc_html__( 'Text before tags', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_before_tags_word', '' ) ),
				),
				'title_blog_single_show_comments_link'        => array(
					'type'    => 'select',
					'section' => 'section_title',
					'label'   => esc_html__( 'Show comments count', 'exs' ),
					'default' => esc_html( exs_option( 'title_blog_single_show_comments_link', false ) ),
					'choices' => array(
						''       => esc_html__( 'None', 'exs' ),
						'text'   => esc_html__( 'Comments number with text', 'exs' ),
						'number' => esc_html__( 'Only comments number', 'exs' ),
					),
				),


				////////////////
				//main section//
				////////////////
				'main_sidebar_width'                    => array(
					'type'    => 'select',
					'section' => 'section_main',
					'label'   => esc_html__( 'Sidebar width on big screens', 'exs' ),
					'default' => esc_html( exs_option( 'main_sidebar_width', '' ) ),
					'choices' => array(
						'33' => esc_html__( 'Default - 1/3 - 33%', 'exs' ),
						'25' => esc_html__( '1/4 - 25%', 'exs' ),
					),
				),
				'main_gap_width'                        => array(
					'type'    => 'slider',
					'section' => 'section_main',
					'label'   => esc_html__( 'Sidebar gap width (rem)', 'exs' ),
					'default' => esc_html( exs_option( 'main_gap_width', '' ) ),
					'atts'        => array(
						'min'         => '0',
						'max'         => '10',
						'step'        => '0.5',
					),
				),
				'main_sidebar_sticky'                   => array(
					'type'    => 'checkbox',
					'section' => 'section_main',
					'label'   => esc_html__( 'Sticky sidebar', 'exs' ),
					'default' => esc_html( exs_option( 'main_sidebar_sticky', false ) ),
				),
				'main_extra_padding_top'                => array(
					'type'    => 'select',
					'section' => 'section_main',
					'label'   => esc_html__( 'Top padding', 'exs' ),
					'default' => esc_html( exs_option( 'main_extra_padding_top', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pt-0'  => esc_html__( '0', 'exs' ),
						'pt-1'  => esc_html__( '1em', 'exs' ),
						'pt-2'  => esc_html__( '2em', 'exs' ),
						'pt-3'  => esc_html__( '3em', 'exs' ),
						'pt-4'  => esc_html__( '4em', 'exs' ),
						'pt-5'  => esc_html__( '5em', 'exs' ),
						'pt-6'  => esc_html__( '6em', 'exs' ),
						'pt-7'  => esc_html__( '7em', 'exs' ),
						'pt-8'  => esc_html__( '8em', 'exs' ),
						'pt-9'  => esc_html__( '9em', 'exs' ),
						'pt-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'main_extra_padding_bottom'             => array(
					'type'    => 'select',
					'section' => 'section_main',
					'label'   => esc_html__( 'Bottom padding', 'exs' ),
					'default' => esc_html( exs_option( 'main_extra_padding_bottom', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pb-0'  => esc_html__( '0', 'exs' ),
						'pb-1'  => esc_html__( '1em', 'exs' ),
						'pb-2'  => esc_html__( '2em', 'exs' ),
						'pb-3'  => esc_html__( '3em', 'exs' ),
						'pb-4'  => esc_html__( '4em', 'exs' ),
						'pb-5'  => esc_html__( '5em', 'exs' ),
						'pb-6'  => esc_html__( '6em', 'exs' ),
						'pb-7'  => esc_html__( '7em', 'exs' ),
						'pb-8'  => esc_html__( '8em', 'exs' ),
						'pb-9'  => esc_html__( '9em', 'exs' ),
						'pb-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'main_font_size'                        => array(
					'type'    => 'select',
					'section' => 'section_main',
					'label'   => esc_html__( 'Main section font size', 'exs' ),
					'default' => esc_html( exs_option( 'main_font_size', '' ) ),
					'choices' => exs_customizer_font_size_array(),
				),
				'sidebar_font_size'                     => array(
					'type'    => 'select',
					'section' => 'section_main',
					'label'   => esc_html__( 'Sidebar font size', 'exs' ),
					'default' => esc_html( exs_option( 'sidebar_font_size', '' ) ),
					'choices' => exs_customizer_font_size_array(),
				),
				'main_sidebar_widgets_heading'          => array(
					'type'        => 'block-heading',
					'section'     => 'section_main',
					'label'       => esc_html__( 'Style widget titles', 'exs' ),
					'description' => esc_html__( 'Change styles for sidebar widget titles.', 'exs' ),
				),
				'main_sidebar_widgets_title_uppercase'  => array(
					'type'    => 'checkbox',
					'section' => 'section_main',
					'label'   => esc_html__( 'Uppercase widget titles', 'exs' ),
					'default' => esc_html( exs_option( 'main_sidebar_widgets_title_uppercase', false ) ),
				),
				'main_sidebar_widgets_title_bold'       => array(
					'type'    => 'checkbox',
					'section' => 'section_main',
					'label'   => esc_html__( 'Bold widget titles', 'exs' ),
					'default' => esc_html( exs_option( 'main_sidebar_widgets_title_bold', false ) ),
				),
				'main_sidebar_widgets_title_decor'      => array(
					'type'    => 'checkbox',
					'section' => 'section_main',
					'label'   => esc_html__( 'Decorated widget titles', 'exs' ),
					'default' => esc_html( exs_option( 'main_sidebar_widgets_title_decor', false ) ),
				),

				//////////////
				//footer_top//
				//////////////
				'footer_top'                                => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Layout', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top', '' ) ),
					'choices' => array(
						''  => esc_html__( 'Disabled', 'exs' ),
						'1' => esc_html__( 'Single column', 'exs' ),
						'2' => esc_html__( 'Single column centered', 'exs' ),
						'3' => esc_html__( 'Two columns', 'exs' ),
					),
				),
				'footer_top_content_heading_text'                   => array(
					'type'        => 'block-heading',
					'section'     => 'section_footer_top',
					'label'       => esc_html__( 'Top Footer Section Content', 'exs' ),
					'description' => esc_html__( 'Set top footer section content. Leave blank if some options not needed.', 'exs' ),
				),
				'footer_top_image'                               => array(
					'type'        => 'image',
					'section'     => 'section_footer_top',
					'label'       => esc_html__( 'Top Footer Section Image', 'exs' ),
					'description' => esc_html__( 'Set top footer section image. Useful if you want to display another logo', 'exs' ),
				),
				'footer_top_pre_heading'                         => array(
					'type'    => 'text',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Pre Heading text', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_pre_heading', '' ) ),
				),
				'footer_top_pre_heading_mt'                      => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Pre Heading top margin', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_pre_heading_mt', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mt-0'  => '0',
						'mt-05' => '0.5em',
						'mt-1'  => '1em',
						'mt-15' => '1.5em',
						'mt-2'  => '2em',
						'mt-3'  => '3em',
						'mt-4'  => '4em',
						'mt-5'  => '5em',
					),
				),
				'footer_top_pre_heading_mb'                      => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Pre Heading bottom margin', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_pre_heading_mb', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mb-0'  => '0',
						'mb-05' => '0.5em',
						'mb-1'  => '1em',
						'mb-15' => '1.5em',
						'mb-2'  => '2em',
						'mb-3'  => '3em',
						'mb-4'  => '4em',
						'mb-5'  => '5em',
					),
				),
				'footer_top_pre_heading_animation'               => array(
					'type'        => 'select',
					'section'     => 'section_footer_top',
					'label'       => esc_html__( 'Animation for pre heading', 'exs' ),
					'description' => esc_html__( 'Animation should be enabled', 'exs' ),
					'default'     => esc_html( exs_option( 'footer_top_pre_heading_animation', '' ) ),
					'choices'     => exs_get_animation_options(),
				),
				'footer_top_heading'                         => array(
					'type'    => 'text',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Heading text', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_heading', '' ) ),
				),
				'footer_top_heading_mt'                      => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Heading top margin', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_heading_mt', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mt-0'  => '0',
						'mt-05' => '0.5em',
						'mt-1'  => '1em',
						'mt-15' => '1.5em',
						'mt-2'  => '2em',
						'mt-3'  => '3em',
						'mt-4'  => '4em',
						'mt-5'  => '5em',
					),
				),
				'footer_top_heading_mb'                      => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Heading bottom margin', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_heading_mb', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mb-0'  => '0',
						'mb-05' => '0.5em',
						'mb-1'  => '1em',
						'mb-15' => '1.5em',
						'mb-2'  => '2em',
						'mb-3'  => '3em',
						'mb-4'  => '4em',
						'mb-5'  => '5em',
					),
				),
				'footer_top_heading_animation'               => array(
					'type'        => 'select',
					'section'     => 'section_footer_top',
					'label'       => esc_html__( 'Animation for heading', 'exs' ),
					'description' => esc_html__( 'Animation should be enabled', 'exs' ),
					'default'     => esc_html( exs_option( 'footer_top_heading_animation', '' ) ),
					'choices'     => exs_get_animation_options(),
				),
				'footer_top_description'                     => array(
					'type'    => 'textarea',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Description text', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_description', '' ) ),
				),
				'footer_top_description_mt'                  => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Description top margin', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_description_mt', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mt-0'  => '0',
						'mt-05' => '0.5em',
						'mt-1'  => '1em',
						'mt-15' => '1.5em',
						'mt-2'  => '2em',
						'mt-3'  => '3em',
						'mt-4'  => '4em',
						'mt-5'  => '5em',
					),
				),
				'footer_top_description_mb'                  => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Description bottom margin', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_description_mb', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mb-0'  => '0',
						'mb-05' => '0.5em',
						'mb-1'  => '1em',
						'mb-15' => '1.5em',
						'mb-2'  => '2em',
						'mb-3'  => '3em',
						'mb-4'  => '4em',
						'mb-5'  => '5em',
					),
				),
				'footer_top_description_animation'           => array(
					'type'        => 'select',
					'section'     => 'section_footer_top',
					'label'       => esc_html__( 'Animation for description text', 'exs' ),
					'description' => esc_html__( 'Animation should be enabled', 'exs' ),
					'default'     => esc_html( exs_option( 'footer_top_description_animation', '' ) ),
					'choices'     => exs_get_animation_options(),
				),
				'footer_top_shortcode'                       => array(
					'type'        => 'text',
					'section'     => 'section_footer_top',
					'label'       => esc_html__( 'Shortcode', 'exs' ),
					'description' => esc_html__( 'You can put shortcode here. It will appear below description', 'exs' ),
					'default'     => esc_html( exs_option( 'footer_top_shortcode', '' ) ),
				),
				'footer_top_shortcode_mt'                    => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Shortcode top margin', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_shortcode_mt', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mt-0'  => '0',
						'mt-05' => '0.5em',
						'mt-1'  => '1em',
						'mt-15' => '1.5em',
						'mt-2'  => '2em',
						'mt-3'  => '3em',
						'mt-4'  => '4em',
						'mt-5'  => '5em',
					),
				),
				'footer_top_shortcode_mb'                    => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Shortcode bottom margin', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_shortcode_mb', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mb-0'  => '0',
						'mb-05' => '0.5em',
						'mb-1'  => '1em',
						'mb-15' => '1.5em',
						'mb-2'  => '2em',
						'mb-3'  => '3em',
						'mb-4'  => '4em',
						'mb-5'  => '5em',
					),
				),
				'footer_top_shortcode_animation'             => array(
					'type'        => 'select',
					'section'     => 'section_footer_top',
					'label'       => esc_html__( 'Animation for shortcode', 'exs' ),
					'description' => esc_html__( 'Animation should be enabled', 'exs' ),
					'default'     => esc_html( exs_option( 'footer_top_shortcode_animation', '' ) ),
					'choices'     => exs_get_animation_options(),
				),
				'footer_top_options_heading_text'                   => array(
					'type'        => 'block-heading',
					'section'     => 'section_footer_top',
					'label'       => esc_html__( 'Top Footer Section Options', 'exs' ),
					'description' => esc_html__( 'Set top footer section display and layout options.', 'exs' ),
				),
				'footer_top_fluid'                          => array(
					'type'    => 'checkbox',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Full Width Section', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_fluid', false ) ),
				),
				'footer_top_background'                     => array(
					'type'    => 'color-radio',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Background', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_background', '' ) ),
					'choices' => exs_customizer_backgrounds_array(),
				),
				'footer_top_border_top'                     => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Top border', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_border_top', '' ) ),
					'choices' => exs_customizer_borders_array(),
				),
				'footer_top_border_bottom'                  => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Bottom border', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_border_bottom', '' ) ),
					'choices' => exs_customizer_borders_array(),
				),
				'footer_top_extra_padding_top'              => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Top padding', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_extra_padding_top', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pt-0'  => esc_html__( '0', 'exs' ),
						'pt-1'  => esc_html__( '1em', 'exs' ),
						'pt-2'  => esc_html__( '2em', 'exs' ),
						'pt-3'  => esc_html__( '3em', 'exs' ),
						'pt-4'  => esc_html__( '4em', 'exs' ),
						'pt-5'  => esc_html__( '5em', 'exs' ),
						'pt-6'  => esc_html__( '6em', 'exs' ),
						'pt-7'  => esc_html__( '7em', 'exs' ),
						'pt-8'  => esc_html__( '8em', 'exs' ),
						'pt-9'  => esc_html__( '9em', 'exs' ),
						'pt-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'footer_top_extra_padding_bottom'           => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Bottom padding', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_extra_padding_bottom', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pb-0'  => esc_html__( '0', 'exs' ),
						'pb-1'  => esc_html__( '1em', 'exs' ),
						'pb-2'  => esc_html__( '2em', 'exs' ),
						'pb-3'  => esc_html__( '3em', 'exs' ),
						'pb-4'  => esc_html__( '4em', 'exs' ),
						'pb-5'  => esc_html__( '5em', 'exs' ),
						'pb-6'  => esc_html__( '6em', 'exs' ),
						'pb-7'  => esc_html__( '7em', 'exs' ),
						'pb-8'  => esc_html__( '8em', 'exs' ),
						'pb-9'  => esc_html__( '9em', 'exs' ),
						'pb-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'footer_top_font_size'                      => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Font Size', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_font_size', '' ) ),
					'choices' => exs_customizer_font_size_array(),
				),
				'footer_top_background_image'               => array(
					'type'    => 'image',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Background Image', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_background_image', '' ) ),
				),
				'footer_top_background_image_cover'         => array(
					'type'    => 'checkbox',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Cover background image', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_background_image_cover', false ) ),
				),
				'footer_top_background_image_fixed'         => array(
					'type'    => 'checkbox',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Fixed background image', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_background_image_fixed', false ) ),
				),
				'footer_top_background_image_overlay'       => array(
					'type'    => 'select',
					'section' => 'section_footer_top',
					'label'   => esc_html__( 'Overlay for background image', 'exs' ),
					'default' => esc_html( exs_option( 'footer_top_background_image_overlay', '' ) ),
					'choices' => exs_customizer_background_overlay_array(),
				),
				'footer_top_background_image_overlay_opacity' => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Overlay Opacity', 'exs' ),
					'default'     => esc_html( exs_option( 'footer_top_background_image_overlay_opacity', '' ) ),
					'section'     => 'section_footer_top',
					'atts'        => array(
						'min'         => '1',
						'max'         => '99',
						'step'        => '1',
					),
				),

				//////////
				//footer//
				//////////
				'footer'                                => array(
					'type'    => 'select',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Footer Layout', 'exs' ),
					'default' => esc_html( exs_option( 'footer', '1' ) ),
					'choices' => array(
						''  => esc_html__( 'Disabled', 'exs' ),
						'1' => esc_html__( 'Equal columns', 'exs' ),
						'2' => esc_html__( 'First one half column', 'exs' ),
						'3' => esc_html__( 'Second one half column', 'exs' ),
						'4' => esc_html__( 'Full Width', 'exs' ),
						'5' => esc_html__( 'Full Width centered', 'exs' ),
						'6' => esc_html__( 'Full Width centered narrow', 'exs' ),

					),
				),
				'footer_layout_gap'                     => array(
					'type'        => 'select',
					'section'     => 'section_footer',
					'label'       => esc_html__( 'Footer widgets gap', 'exs' ),
					'description' => esc_html__( 'Used only for multiple columns layouts', 'exs' ),
					'default'     => esc_html( exs_option( 'footer_layout_gap', '' ) ),
					'choices'     => exs_get_feed_layout_gap_options(),
				),
				'footer_fluid'                          => array(
					'type'    => 'checkbox',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Full Width Footer', 'exs' ),
					'default' => esc_html( exs_option( 'footer_fluid', false ) ),
				),
				'footer_background'                     => array(
					'type'    => 'color-radio',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Footer Background', 'exs' ),
					'default' => esc_html( exs_option( 'footer_background', '' ) ),
					'choices' => exs_customizer_backgrounds_array(),
				),
				'footer_border_top'                     => array(
					'type'    => 'select',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Top border', 'exs' ),
					'default' => esc_html( exs_option( 'footer_border_top', '' ) ),
					'choices' => exs_customizer_borders_array(),
				),
				'footer_border_bottom'                  => array(
					'type'    => 'select',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Bottom border', 'exs' ),
					'default' => esc_html( exs_option( 'footer_border_bottom', '' ) ),
					'choices' => exs_customizer_borders_array(),
				),
				'footer_extra_padding_top'              => array(
					'type'    => 'select',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Top padding', 'exs' ),
					'default' => esc_html( exs_option( 'footer_extra_padding_top', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pt-0'  => esc_html__( '0', 'exs' ),
						'pt-1'  => esc_html__( '1em', 'exs' ),
						'pt-2'  => esc_html__( '2em', 'exs' ),
						'pt-3'  => esc_html__( '3em', 'exs' ),
						'pt-4'  => esc_html__( '4em', 'exs' ),
						'pt-5'  => esc_html__( '5em', 'exs' ),
						'pt-6'  => esc_html__( '6em', 'exs' ),
						'pt-7'  => esc_html__( '7em', 'exs' ),
						'pt-8'  => esc_html__( '8em', 'exs' ),
						'pt-9'  => esc_html__( '9em', 'exs' ),
						'pt-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'footer_extra_padding_bottom'           => array(
					'type'    => 'select',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Bottom padding', 'exs' ),
					'default' => esc_html( exs_option( 'footer_extra_padding_bottom', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pb-0'  => esc_html__( '0', 'exs' ),
						'pb-1'  => esc_html__( '1em', 'exs' ),
						'pb-2'  => esc_html__( '2em', 'exs' ),
						'pb-3'  => esc_html__( '3em', 'exs' ),
						'pb-4'  => esc_html__( '4em', 'exs' ),
						'pb-5'  => esc_html__( '5em', 'exs' ),
						'pb-6'  => esc_html__( '6em', 'exs' ),
						'pb-7'  => esc_html__( '7em', 'exs' ),
						'pb-8'  => esc_html__( '8em', 'exs' ),
						'pb-9'  => esc_html__( '9em', 'exs' ),
						'pb-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'footer_font_size'                      => array(
					'type'    => 'select',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Footer section font size', 'exs' ),
					'default' => esc_html( exs_option( 'footer_font_size', '' ) ),
					'choices' => exs_customizer_font_size_array(),
				),
				'footer_background_image_heading'      => array(
					'type'        => 'block-heading',
					'section'     => 'section_footer',
					'label'       => esc_html__( 'Background image settings', 'exs' ),
				),
				'footer_background_image'               => array(
					'type'    => 'image',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Footer Section Background Image', 'exs' ),
					'default' => esc_html( exs_option( 'footer_background_image', '' ) ),
				),
				'footer_background_image_cover'         => array(
					'type'    => 'checkbox',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Cover background image', 'exs' ),
					'default' => esc_html( exs_option( 'footer_background_image_cover', false ) ),
				),
				'footer_background_image_fixed'         => array(
					'type'    => 'checkbox',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Fixed background image', 'exs' ),
					'default' => esc_html( exs_option( 'footer_background_image_fixed', false ) ),
				),
				'footer_background_image_overlay'       => array(
					'type'    => 'select',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Overlay for background image', 'exs' ),
					'default' => esc_html( exs_option( 'footer_background_image_overlay', '' ) ),
					'choices' => exs_customizer_background_overlay_array(),
				),
				'footer_background_image_overlay_opacity' => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Overlay Opacity', 'exs' ),
					'default'     => esc_html( exs_option( 'footer_background_image_overlay_opacity', '' ) ),
					'section'     => 'section_footer',
					'atts'        => array(
						'min'         => '1',
						'max'         => '99',
						'step'        => '1',
					),
				),

				'footer_widgets_heading'               => array(
					'type'        => 'block-heading',
					'section'     => 'section_footer',
					'label'       => esc_html__( 'Style widget titles', 'exs' ),
					'description' => esc_html__( 'Change styles for footer widget titles.', 'exs' ),
				),
				'footer_sidebar_widgets_title_uppercase' => array(
					'type'    => 'checkbox',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Uppercase widget titles', 'exs' ),
					'default' => esc_html( exs_option( 'footer_sidebar_widgets_title_uppercase', false ) ),
				),
				'footer_sidebar_widgets_title_bold'      => array(
					'type'    => 'checkbox',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Bold widget titles', 'exs' ),
					'default' => esc_html( exs_option( 'footer_sidebar_widgets_title_bold', false ) ),
				),
				'footer_sidebar_widgets_title_decor'     => array(
					'type'    => 'checkbox',
					'section' => 'section_footer',
					'label'   => esc_html__( 'Decorated widget titles', 'exs' ),
					'default' => esc_html( exs_option( 'footer_sidebar_widgets_title_decor', false ) ),
				),
				/////////////
				//copyright//
				/////////////
				'copyright'                             => array(
					'type'    => 'select',
					'section' => 'section_copyright',
					'label'   => esc_html__( 'Copyright Layout', 'exs' ),
					'default' => esc_html( exs_option( 'copyright', '1' ) ),
					'choices' => array(
						''  => esc_html__( 'Disabled', 'exs' ),
						'1' => esc_html__( 'Only copyright (centered)', 'exs' ),
						'2' => esc_html__( 'Only copyright (left aligned)', 'exs' ),
						'3' => esc_html__( 'Left copyright and right menu', 'exs' ),
						'4' => esc_html__( 'Left copyright and right social icons', 'exs' ),
						'5' => esc_html__( 'Left copyright, menu and right social icons', 'exs' ),
					),
				),
				'copyright_text'                        => array(
					'type'        => 'textarea',
					'section'     => 'section_copyright',
					'label'       => esc_html__( 'Copyright text', 'exs' ),
					'description' => esc_html__( 'Site name will be displayed, if leave empty', 'exs' ),
					'default'     => esc_html( exs_option( 'copyright_text', '' ) ),
				),
				'copyright_fluid'                       => array(
					'type'    => 'checkbox',
					'section' => 'section_copyright',
					'label'   => esc_html__( 'Full Width copyright', 'exs' ),
					'default' => esc_html( exs_option( 'copyright_fluid', true ) ),
				),
				'copyright_background'                  => array(
					'type'    => 'color-radio',
					'section' => 'section_copyright',
					'label'   => esc_html__( 'Copyright Background', 'exs' ),
					'default' => esc_html( exs_option( 'copyright_background', '' ) ),
					'choices' => exs_customizer_backgrounds_array(),
				),
				'copyright_extra_padding_top'           => array(
					'type'    => 'select',
					'section' => 'section_copyright',
					'label'   => esc_html__( 'Top padding', 'exs' ),
					'default' => esc_html( exs_option( 'copyright_extra_padding_top', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pt-0'  => esc_html__( '0', 'exs' ),
						'pt-1'  => esc_html__( '1em', 'exs' ),
						'pt-2'  => esc_html__( '2em', 'exs' ),
						'pt-3'  => esc_html__( '3em', 'exs' ),
						'pt-4'  => esc_html__( '4em', 'exs' ),
						'pt-5'  => esc_html__( '5em', 'exs' ),
						'pt-6'  => esc_html__( '6em', 'exs' ),
						'pt-7'  => esc_html__( '7em', 'exs' ),
						'pt-8'  => esc_html__( '8em', 'exs' ),
						'pt-9'  => esc_html__( '9em', 'exs' ),
						'pt-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'copyright_extra_padding_bottom'        => array(
					'type'    => 'select',
					'section' => 'section_copyright',
					'label'   => esc_html__( 'Bottom padding', 'exs' ),
					'default' => esc_html( exs_option( 'copyright_extra_padding_bottom', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pb-0'  => esc_html__( '0', 'exs' ),
						'pb-1'  => esc_html__( '1em', 'exs' ),
						'pb-2'  => esc_html__( '2em', 'exs' ),
						'pb-3'  => esc_html__( '3em', 'exs' ),
						'pb-4'  => esc_html__( '4em', 'exs' ),
						'pb-5'  => esc_html__( '5em', 'exs' ),
						'pb-6'  => esc_html__( '6em', 'exs' ),
						'pb-7'  => esc_html__( '7em', 'exs' ),
						'pb-8'  => esc_html__( '8em', 'exs' ),
						'pb-9'  => esc_html__( '9em', 'exs' ),
						'pb-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'copyright_font_size'                   => array(
					'type'    => 'select',
					'section' => 'section_copyright',
					'label'   => esc_html__( 'Copyright section font size', 'exs' ),
					'default' => esc_html( exs_option( 'copyright_font_size', '' ) ),
					'choices' => exs_customizer_font_size_array(),
				),
				'copyright_background_image_heading'      => array(
					'type'        => 'block-heading',
					'section'     => 'section_copyright',
					'label'       => esc_html__( 'Background image settings', 'exs' ),
				),
				'copyright_background_image'            => array(
					'type'    => 'image',
					'section' => 'section_copyright',
					'label'   => esc_html__( 'Copyright Section Background Image', 'exs' ),
					'default' => esc_html( exs_option( 'copyright_background_image', '' ) ),
				),
				'copyright_background_image_cover'      => array(
					'type'    => 'checkbox',
					'section' => 'section_copyright',
					'label'   => esc_html__( 'Cover background image', 'exs' ),
					'default' => esc_html( exs_option( 'copyright_background_image_cover', false ) ),
				),
				'copyright_background_image_fixed'      => array(
					'type'    => 'checkbox',
					'section' => 'section_copyright',
					'label'   => esc_html__( 'Fixed background image', 'exs' ),
					'default' => esc_html( exs_option( 'copyright_background_image_fixed', false ) ),
				),
				'copyright_background_image_overlay'    => array(
					'type'    => 'select',
					'section' => 'section_copyright',
					'label'   => esc_html__( 'Overlay for background image', 'exs' ),
					'default' => esc_html( exs_option( 'copyright_background_image_overlay', '' ) ),
					'choices' => exs_customizer_background_overlay_array(),
				),
				'copyright_background_image_overlay_opacity' => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Overlay Opacity', 'exs' ),
					'default'     => esc_html( exs_option( 'copyright_background_image_overlay_opacity', '' ) ),
					'section'     => 'section_copyright',
					'atts'        => array(
						'min'         => '1',
						'max'         => '99',
						'step'        => '1',
					),
				),
				////////////////
				//icons       //
				//since 1.8.12//
				////////////////
				'theme_icons'                       => array(
					'type'        => 'select',
					'label'       => esc_html__( 'Theme Icons Pack', 'exs' ),
					'description' => esc_html__( 'Choose your icons', 'exs' ),
					'default'     => esc_html( exs_option( 'theme_icons', '' ) ),
					'section'     => 'section_icons',
					'choices'     => array(
						''              => esc_html__( 'Default', 'exs' ),
						'ionic-filled'  => esc_html__( 'Ionic Filled', 'exs' ),
						'ionic-outline' => esc_html__( 'Ionic Outline', 'exs' ),
					),
				),

				////////////////
				//Read Time   //
				//since 2.0.2 //
				////////////////
				'reading_time_enabled'                       => array(
					'type'        => 'checkbox',
					'label'       => esc_html__( 'Show reading time', 'exs' ),
					'description' => esc_html__( 'Enable displaying reading time for posts', 'exs' ),
					'default'     => esc_html( exs_option( 'reading_time_enabled', '' ) ),
					'section'     => 'section_readtime',
				),
				'reading_time_words_per_minute' => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Reading speed', 'exs' ),
					'description' => esc_html__( 'Words per minute. Default is 200', 'exs' ),
					'default'     => esc_html( exs_option( 'reading_time_words_per_minute', '' ) ),
					'section'     => 'section_readtime',
					'atts'        => array(
						'min'         => '50',
						'max'         => '500',
						'step'        => '10',
					),
					'visible' => array( 'key' => 'reading_time_enabled', 'value' => true ),
				),
				'reading_time_prefix'                       => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Reading time prefix', 'exs' ),
					'description' => esc_html__( 'Text to display before reading time', 'exs' ),
					'default'     => esc_html( exs_option( 'reading_time_prefix', '' ) ),
					'section'     => 'section_readtime',
					'visible' => array( 'key' => 'reading_time_enabled', 'value' => true ),
				),
				'reading_time_suffix'                       => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Reading time suffix', 'exs' ),
					'description' => esc_html__( 'Text to display after reading time', 'exs' ),
					'default'     => esc_html( exs_option( 'reading_time_suffix', '' ) ),
					'section'     => 'section_readtime',
					'visible' => array( 'key' => 'reading_time_enabled', 'value' => true ),
				),
				'reading_time_position_blog'                       => array(
					'type'        => 'select',
					'label'       => esc_html__( 'Display reading time for archive', 'exs' ),
					'description' => esc_html__( 'Reading time position', 'exs' ),
					'default'     => esc_html( exs_option( 'reading_time_position_blog', '' ) ),
					'section'     => 'section_readtime',
					'choices'     => array(
						''              => esc_html__( 'No', 'exs' ),
						'before_author'  => esc_html__( 'Before Author', 'exs' ),
						'before_date' => esc_html__( 'Before Date', 'exs' ),
						'before_categories' => esc_html__( 'Before Categories', 'exs' ),
						'before_tags' => esc_html__( 'Before Tags', 'exs' ),
						'after_tags' => esc_html__( 'After Tags', 'exs' ),
					),
					'visible' => array( 'key' => 'reading_time_enabled', 'value' => true ),
				),
				'reading_time_position_blog_single'                       => array(
					'type'        => 'select',
					'label'       => esc_html__( 'Display reading time for single post', 'exs' ),
					'description' => esc_html__( 'Reading time position', 'exs' ),
					'default'     => esc_html( exs_option( 'reading_time_position_blog_single', '' ) ),
					'section'     => 'section_readtime',
					'choices'     => array(
						''              => esc_html__( 'No', 'exs' ),
						'before_author'  => esc_html__( 'Before Author', 'exs' ),
						'before_date' => esc_html__( 'Before Date', 'exs' ),
						'before_categories' => esc_html__( 'Before Categories', 'exs' ),
						'before_tags' => esc_html__( 'Before Tags', 'exs' ),
						'after_tags' => esc_html__( 'After Tags', 'exs' ),
					),
					'visible' => array( 'key' => 'reading_time_enabled', 'value' => true ),
				),
				'reading_time_blog_single_title_section'                       => array(
					'type'        => 'checkbox',
					'label'       => esc_html__( 'Show Reading time for single post in the Title section', 'exs' ),
					'description' => esc_html__( 'Reading time will be displayed in single post in the title section instead of post meta', 'exs' ),
					'default'     => esc_html( exs_option( 'reading_time_position_blog_single_title_section', '' ) ),
					'section'     => 'section_readtime',
					'visible' => array( 'key' => 'reading_time_enabled', 'value' => true ),
				),
				'reading_time_position_search'                       => array(
					'type'        => 'select',
					'label'       => esc_html__( 'Display reading time for search results', 'exs' ),
					'description' => esc_html__( 'Reading time position', 'exs' ),
					'default'     => esc_html( exs_option( 'reading_time_position_search', '' ) ),
					'section'     => 'section_readtime',
					'choices'     => array(
						''              => esc_html__( 'No', 'exs' ),
						'before_author'  => esc_html__( 'Before Author', 'exs' ),
						'before_date' => esc_html__( 'Before Date', 'exs' ),
						'before_categories' => esc_html__( 'Before Categories', 'exs' ),
						'before_tags' => esc_html__( 'Before Tags', 'exs' ),
						'after_tags' => esc_html__( 'After Tags', 'exs' ),
					),
					'visible' => array( 'key' => 'reading_time_enabled', 'value' => true ),
				),

				//////////////
				//typography//
				//////////////
				'typo_body_heading'      => array(
					'type'        => 'block-heading',
					'section'     => 'section_typography',
					'label'       => esc_html__( 'Settings for BODY', 'exs' ),
					'description' => esc_html__( 'Set your settings for BODY tag. Leave blank for theme defaults.', 'exs' ),
				),
				'typo_body_size'                       => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Global Font Size (px)', 'exs' ),
					'description' => esc_html__( 'Set a global font size in pixels. It will be applied to BODY tag. Can be overridden in the individual sections settings', 'exs' ),
					'default'     => esc_html( exs_option( 'typo_body_size', '' ) ),
					'section'     => 'section_typography',
					'atts'        => array(
						'min'         => '9',
						'max'         => '26',
						'step'        => '1',
					),
				),
				'typo_body_weight'                       => array(
					'type'        => 'select',
					'label'       => esc_html__( 'Global Font Weight', 'exs' ),
					'description' => esc_html__( 'Set a global font weight. It will be applied to BODY tag.', 'exs' ),
					'default'     => esc_html( exs_option( 'typo_body_weight', '' ) ),
					'section'     => 'section_typography',
					'choices'     => array(
						''    => esc_html__( 'Default', 'exs' ),
						'100' => esc_html__( '100', 'exs' ),
						'300' => esc_html__( '300', 'exs' ),
						'400' => esc_html__( '400', 'exs' ),
						'500' => esc_html__( '500', 'exs' ),
						'700' => esc_html__( '700', 'exs' ),
						'900' => esc_html__( '900', 'exs' ),
					),
				),
				'typo_body_line_height'                       => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Global Line Height (em)', 'exs' ),
					'description' => esc_html__( 'It will be applied to BODY tag. Value in ems', 'exs' ),
					'section'     => 'section_typography',
					'default'     => esc_html( exs_option( 'typo_body_line_height', '' ) ),
					'atts'        => array(
						'min'         => '0.8',
						'max'         => '3',
						'step'        => '0.01',
					),
				),
				'typo_body_letter_spacing'                    => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Global Letter Spacing (em)', 'exs' ),
					'description' => esc_html__( 'It will be applied to BODY tag. Value in ems', 'exs' ),
					'section'     => 'section_typography',
					'default'     => esc_html( exs_option( 'typo_body_letter_spacing', '' ) ),
					'atts'        => array(
						'min'         => '-0.2',
						'max'         => '0.5',
						'step'        => '0.005',
					),
				),
				'typo_p_margin_bottom'                        => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Paragraphs Bottom Margin (em)', 'exs' ),
					'description' => esc_html__( 'It will be applied to P tag. Value in ems', 'exs' ),
					'section'     => 'section_typography',
					'default'     => esc_html( exs_option( 'typo_p_margin_bottom', '' ) ),
					'atts'        => array(
						'min'         => '0',
						'max'         => '3',
						'step'        => '0.05',
					),
				),
				/////////
				//fonts//
				/////////
				'font_body_extra'                       => array(
					'type'        => 'extra-button',
					'label'       => esc_html__( 'Google Fonts', 'exs' ),
					'description' => esc_html__( 'Activate Google Fonts in your Customizer and set custom fonts for your body text and headings', 'exs' ),
					'section'     => 'section_fonts',
				),
				///////////////
				//local fonts//
				//since 1.9.9//
				///////////////
				'fonts_local'                       => array(
					'type'        => 'checkbox',
					'label'       => esc_html__( 'Load Google Fonts Locally', 'exs' ),
					'description' => esc_html__( 'Serving Google Fonts from local CSS files for better performance and privacy', 'exs' ),
					'section'     => 'section_performance',
					'default' => esc_html( exs_option( 'fonts_local', false ) ),
				),
				/////////////////
				//infinite loop//
				//since 1.7.4  //
				/////////////////
				'infinite_loop_extra'                           => array(
					'type'        => 'extra-button',
					'label'       => esc_html__( 'Infinite Loop', 'exs' ),
					'description' => esc_html__( 'Add infinite loop for your archive pages with "Load More" button or just on scrolling down.', 'exs' ),
					'section'     => 'section_infinite_loop',
				),
				/////////////////
				//share buttons//
				//since 1.7.5  //
				/////////////////
				'share_buttons_extra'                           => array(
					'type'        => 'extra-button',
					'label'       => esc_html__( 'Share Buttons', 'exs' ),
					'description' => esc_html__( 'You can add share buttons in various layouts to your posts, pages and archive pages.', 'exs' ),
					'section'     => 'section_share_buttons',
				),
				////////
				//blog//
				////////
				'blog_layout'                           => array(
					'type'    => 'select',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Blog feed layout', 'exs' ),
					'default' => esc_html( exs_option( 'blog_layout', '' ) ),
					'choices' => exs_get_feed_layout_options(),
				),
				'blog_layout_gap'                       => array(
					'type'        => 'select',
					'section'     => 'section_blog',
					'label'       => esc_html__( 'Blog feed layout gap', 'exs' ),
					'description' => esc_html__( 'Used only for grid and masonry layouts', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_layout_gap', '' ) ),
					'choices'     => exs_get_feed_layout_gap_options(),
				),
				'blog_featured_image_size'                       => array(
					'type'        => 'select',
					'section'     => 'section_blog',
					'label'       => esc_html__( 'Blog feed featured image size', 'exs' ),
					'description' => esc_html__( 'You can override image size that is set for the chosen blog layout', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_featured_image_size', '' ) ),
					'choices'     => exs_get_image_sizes_array(),
				),
				'blog_sidebar_position'                 => array(
					'type'        => 'radio',
					'section'     => 'section_blog',
					'label'       => esc_html__( 'Blog sidebar position', 'exs' ),
					'description' => esc_html__( 'Can be overridden for certain category on category edit page', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_sidebar_position', 'right' ) ),
					'choices'     => exs_get_sidebar_position_options(),
				),
				'blog_page_name'                        => array(
					'type'    => 'text',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Blog page name. Default: \'Blog\'', 'exs' ),
					'default' => esc_html( exs_option( 'blog_page_name', esc_html__( 'Blog', 'exs' ) ) ),
				),
				'blog_show_full_text'                   => array(
					'type'    => 'checkbox',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Show full text instead of excerpt', 'exs' ),
					'default' => esc_html( exs_option( 'blog_show_full_text', false ) ),
				),
				'blog_excerpt_length'                   => array(
					'type'        => 'number',
					'section'     => 'section_blog',
					'label'       => esc_html__( 'Custom excerpt length', 'exs' ),
					'description' => esc_html__( 'Words amount', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_excerpt_length', '' ) ),
				),
				'blog_read_more_text'                   => array(
					'type'    => 'text',
					'section' => 'section_blog',
					'label'   => esc_html__( '\'Read More\' text. Leave blank to hide', 'exs' ),
					'default' => esc_html( exs_option( 'blog_read_more_text', '' ) ),
				),
				'blog_read_more_style'                   => array(
					'type'        => 'radio',
					'section'     => 'section_blog',
					'label'       => esc_html__( 'Display \'Read More\' as a link or a button', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_read_more_style', '' ) ),
					'choices'     => array(
						'' => esc_html__( 'Simple Link', 'exs' ),
						'button' => esc_html__( 'Simple Button', 'exs' ),
						'button-arrow' => esc_html__( 'Button With Arrow', 'exs' ),
					)
				),
				'blog_read_more_block'                   => array(
					'type'    => 'checkbox',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Display \'Read More\' link on new line', 'exs' ),
					'default' => esc_html( exs_option( 'blog_read_more_block', false ) ),
				),
				'blog_hide_taxonomy_type_name'          => array(
					'type'        => 'checkbox',
					'section'     => 'section_blog',
					'label'       => esc_html__( 'Hide taxonomy type name in title section', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_hide_taxonomy_type_name', false ) ),
					'description' => esc_html__( 'You can hide taxonomy name (ex. \'Tag:\') word if you want.', 'exs' ),
				),
				'blog_meta_options_heading'             => array(
					'type'        => 'block-heading',
					'section'     => 'section_blog',
					'label'       => esc_html__( 'Post meta options', 'exs' ),
					'description' => esc_html__( 'Select what post meta you want to show in blog feed. Not all layouts will show post meta even if it is checked.', 'exs' ),
				),
				'blog_hide_meta_icons'                  => array(
					'type'    => 'checkbox',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Hide icons in the post meta', 'exs' ),
					'default' => esc_html( exs_option( 'blog_hide_meta_icons', false ) ),
				),
				'blog_show_author'                      => array(
					'type'    => 'checkbox',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Show author', 'exs' ),
					'default' => esc_html( exs_option( 'blog_show_author', true ) ),
				),
				'blog_show_author_avatar'               => array(
					'type'    => 'checkbox',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Show author avatar', 'exs' ),
					'default' => esc_html( exs_option( 'blog_show_author_avatar', false ) ),
				),
				'blog_before_author_word'               => array(
					'type'    => 'text',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Text before author', 'exs' ),
					'default' => esc_html( exs_option( 'blog_before_author_word', '' ) ),
				),
				'blog_show_date'                        => array(
					'type'    => 'checkbox',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Show date', 'exs' ),
					'default' => esc_html( exs_option( 'blog_show_date', true ) ),
				),
				'blog_before_date_word'                 => array(
					'type'    => 'text',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Text before date', 'exs' ),
					'default' => esc_html( exs_option( 'blog_before_date_word', '' ) ),
				),
				'blog_show_human_date'                        => array(
					'type'    => 'checkbox',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Show human difference date', 'exs' ),
					'default' => esc_html( exs_option( 'blog_show_human_date', false ) ),
				),
				'blog_show_date_type'                        => array(
					'type'    => 'select',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Date to display', 'exs' ),
					'default' => esc_html( exs_option( 'blog_show_date_type', 'publish' ) ),
					'choices' => array(
						'publish' => esc_html__( 'Date published', 'exs' ),
						'modify'  => esc_html__( 'Date modified', 'exs' ),
						'both'    => esc_html__( 'Dade published and modified', 'exs' ),
					),
				),
				'blog_before_date_modify_word'                 => array(
					'type'    => 'text',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Text before date modified', 'exs' ),
					'default' => esc_html( exs_option( 'blog_before_date_modify_word', '' ) ),
				),
				'blog_show_categories'                  => array(
					'type'    => 'checkbox',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Show categories', 'exs' ),
					'default' => esc_html( exs_option( 'blog_show_categories', true ) ),
				),
				'blog_before_categories_word'           => array(
					'type'    => 'text',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Text before categories', 'exs' ),
					'default' => esc_html( exs_option( 'blog_before_categories_word', '' ) ),
				),
				'blog_show_tags'                        => array(
					'type'    => 'checkbox',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Show tags', 'exs' ),
					'default' => esc_html( exs_option( 'blog_show_tags', '' ) ),
				),
				'blog_before_tags_word'                 => array(
					'type'    => 'text',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Text before tags', 'exs' ),
					'default' => esc_html( exs_option( 'blog_before_tags_word', '' ) ),
				),
				'blog_show_comments_link'               => array(
					'type'    => 'select',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Show comments count', 'exs' ),
					'default' => esc_html( exs_option( 'blog_show_comments_link', true ) ),
					'choices' => array(
						''       => esc_html__( 'None', 'exs' ),
						'text'   => esc_html__( 'Comments number with text', 'exs' ),
						'number' => esc_html__( 'Only comments number', 'exs' ),
					),
				),
				'blog_show_date_over_image'                 => array(
					'type'    => 'image-radio',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Show date over featured image', 'exs' ),
					'default' => esc_html( exs_option( 'blog_show_date_over_image', false ) ),
					'choices' => array(
						''       => array(
							'label' => esc_html__( 'None', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/none.png',
						),
						'top-left'  =>  array(
							'label' => esc_html__( 'Top Left', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/top-left.png'
						),
						'top-right' => array(
							'label' => esc_html__( 'Top Right', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/top-right.png'
						),
						'bottom-left' => array(
							'label' => esc_html__( 'Bottom Left', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/bottom-left.png'
						),
						'bottom-right' =>  array(
							'label' => esc_html__( 'Bottom Right', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/bottom-right.png'
						),
					),
				),
				'blog_show_categories_over_image'           => array(
					'type'    => 'image-radio',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Show categories over featured image', 'exs' ),
					'default' => esc_html( exs_option( 'blog_show_categories_over_image', false ) ),
					'choices' => array(
						''       => array(
							'label' => esc_html__( 'None', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/none.png',
						),
						'top-left'  =>  array(
							'label' => esc_html__( 'Top Left', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/top-left.png'
						),
						'top-right' => array(
							'label' => esc_html__( 'Top Right', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/top-right.png'
						),
						'bottom-left' => array(
							'label' => esc_html__( 'Bottom Left', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/bottom-left.png'
						),
						'bottom-right' =>  array(
							'label' => esc_html__( 'Bottom Right', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/bottom-right.png'
						),
					),
				),
				'blog_meta_font_size'                            => array(
					'type'    => 'slider',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Post meta font size', 'exs' ),
					'default' => esc_html( exs_option( 'blog_meta_font_size', '' ) ),
					'description' => esc_html__( 'Value in pixels', 'exs' ),
					'atts'        => array(
						'min'         => '10',
						'max'         => '20',
						'step'        => '1',
					),
				),
				'blog_meta_bold'                        => array(
					'type'    => 'checkbox',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Bold meta font weight', 'exs' ),
					'default' => esc_html( exs_option( 'blog_meta_bold', '' ) ),
				),
				'blog_meta_uppercase'                        => array(
					'type'    => 'checkbox',
					'section' => 'section_blog',
					'label'   => esc_html__( 'Uppercase font meta', 'exs' ),
					'default' => esc_html( exs_option( 'blog_meta_uppercase', '' ) ),
				),


				////////
				//post//
				////////
				//same as blog (except post nav and author bio)
				'blog_single_layout'                    => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Blog post layout', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_layout', '' ) ),
					'choices' => exs_get_post_layout_options(),
				),
				'blog_single_featured_image_size'                       => array(
					'type'        => 'select',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( 'Post featured image size', 'exs' ),
					'description' => esc_html__( 'You can override image size that is set for the chosen post layout', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_single_featured_image_size', '' ) ),
					'choices'     => exs_get_image_sizes_array(),
				),
				'blog_single_sidebar_position'          => array(
					'type'        => 'radio',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( 'Blog post sidebar position', 'exs' ),
					'description' => esc_html__( 'Can be overridden for certain post by selecting appropriate post template', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_single_sidebar_position', 'right' ) ),
					'choices'     => exs_get_sidebar_position_options(),
				),
				'blog_single_first_embed_featured'      => array(
					'type'        => 'select',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( 'Show first post oEmbed instead of featured image', 'exs' ),
					'description' => esc_html__( 'You can replace a featured image with first oEmbed video in the post', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_single_first_embed_featured', '' ) ),
					'choices'     => array(
						''        => esc_html__( 'No', 'exs' ),
						'all'     => esc_html__( 'All posts', 'exs' ),
						'video'   => esc_html__( 'Only video post format', 'exs' ),
					),
				),
				'blog_single_fullwidth_featured'      => array(
					'type'        => 'checkbox',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( 'Fullwidth featured image and video', 'exs' ),
					'description' => esc_html__( 'Blog post sidebar position should be set as "No Sidebar"', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_single_fullwidth_featured', false ) ),
				),
				'blog_single_show_author_bio'           => array(
					'type'        => 'checkbox',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( 'Show Author Bio', 'exs' ),
					'description' => esc_html__( 'You need to fill Biographical Info to display author bio', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_single_show_author_bio', true ) ),
				),
				'blog_single_author_bio_about_word'     => array(
					'type'        => 'text',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( '\'About author\' intro word', 'exs' ),
					'description' => esc_html__( 'Leave blank if not needed', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_single_author_bio_about_word', '' ) ),
				),
				'blog_single_post_nav_heading'          => array(
					'type'    => 'block-heading',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Posts navigation settings', 'exs' ),
				),
				'blog_single_post_nav'                  => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Posts Navigation', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_post_nav', '' ) ),
					'choices' => array(
						''          => esc_html__( 'Disabled', 'exs' ),
						'title'     => esc_html__( 'Only title', 'exs' ),
						'bg'        => esc_html__( 'Background featured image', 'exs' ),
						'thumbnail' => esc_html__( 'Thumbnail featured image', 'exs' ),
					),
				),
				'blog_single_post_nav_word_prev'        => array(
					'type'        => 'text',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( '\'Previous post\' word', 'exs' ),
					'description' => esc_html__( 'Post navigation has to be chosen', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_single_post_nav_word_prev', esc_html__( 'Prev', 'exs' ) ) ),
				),
				'blog_single_post_nav_word_next'        => array(
					'type'        => 'text',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( '\'Next post\' word', 'exs' ),
					'description' => esc_html__( 'Post navigation has to be chosen', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_single_post_nav_word_next', esc_html__( 'Next', 'exs' ) ) ),
				),
				'blog_single_related_posts_heading'     => array(
					'type'        => 'block-heading',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( 'Related posts settings', 'exs' ),
					'description' => esc_html__( 'Some of related posts options may be overridden by child themes and theme skins.', 'exs' ),
				),
				'blog_single_related_posts'             => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Related posts layout', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_related_posts', '' ) ),
					'choices' => array(
						''                => esc_html__( 'Hidden', 'exs' ),
						'list'            => esc_html__( 'Simple list', 'exs' ),
						'list-thumbnails' => esc_html__( 'List with thumbnails', 'exs' ),
						'list-big-thumbs' => esc_html__( 'Big thumbnails', 'exs' ),
						'grid'            => esc_html__( 'Posts grid - auto width', 'exs' ),
						'grid-2'          => esc_html__( 'Posts grid - 2 columns', 'exs' ),
						'grid-3'          => esc_html__( 'Posts grid - 3 columns', 'exs' ),
						'grid-4'          => esc_html__( 'Posts grid - 4 columns', 'exs' ),
						'grid-6'          => esc_html__( 'Posts grid - 6 columns', 'exs' ),
					),
				),
				'blog_single_related_posts_title'       => array(
					'type'        => 'text',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( 'Related posts title', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_single_related_posts_title', '' ) ),
					'description' => esc_html__( 'Related posts heading title', 'exs' ),
				),
				'blog_single_related_posts_number'      => array(
					'type'        => 'number',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( 'Related posts number', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_single_related_posts_number', '' ) ),
					'description' => esc_html__( 'Related posts layout has to be chosen', 'exs' ),
				),
				'blog_single_related_posts_image_size'             => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Thumbnail size', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_related_posts_image_size', '' ) ),
					'choices' => exs_get_image_sizes_array(),
				),
				'blog_single_related_posts_base'        => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Related posts by', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_related_posts_base', '' ) ),
					'choices' => array(
						''       => esc_html__( 'Tags', 'exs' ),
						'cat'    => esc_html__( 'Category', 'exs' ),
						'author' => esc_html__( 'Author', 'exs' ),
					),
				),
				'blog_single_related_show_date'           => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Show related post date', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_related_show_date', false ) ),
				),
				'blog_single_related_posts_readmore_text'       => array(
					'type'        => 'text',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( 'Related posts read more text', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_single_related_posts_readmore_text', '' ) ),
					'description' => esc_html__( 'Leave blank to hide', 'exs' ),
				),
				'blog_single_related_posts_hidden'                    => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Hide related posts on screens:', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_related_posts_hidden', '' ) ),
					'choices' => exs_customizer_responsive_display_array(),
				),
				'blog_single_related_posts_mt'                    => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Related posts top margin', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_related_posts_mt', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mt-0'  => '0',
						'mt-05' => '0.5em',
						'mt-1'  => '1em',
						'mt-15' => '1.5em',
						'mt-2'  => '2em',
						'mt-3'  => '3em',
						'mt-4'  => '4em',
						'mt-5'  => '5em',
					),
				),
				'blog_single_related_posts_mb'                    => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Related posts bottom margin', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_related_posts_mb', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mb-0'  => '0',
						'mb-05' => '0.5em',
						'mb-1'  => '1em',
						'mb-15' => '1.5em',
						'mb-2'  => '2em',
						'mb-3'  => '3em',
						'mb-4'  => '4em',
						'mb-5'  => '5em',
					),
				),
				'blog_single_related_posts_background'           => array(
					'type'    => 'color-radio',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Related posts background', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_related_posts_background', false ) ),
					'choices' => exs_customizer_backgrounds_array(),
				),
				'blog_single_related_posts_section'           => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Display related posts as separate section', 'exs' ),
					'description' => esc_html__( 'Useful if background color is selected and no main sidebar is displayed.', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_related_posts_section', false ) ),
				),
				'blog_single_related_posts_pt'                    => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Related posts top padding', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_related_posts_pt', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pt-0'  => esc_html__( '0', 'exs' ),
						'pt-1'  => esc_html__( '1em', 'exs' ),
						'pt-2'  => esc_html__( '2em', 'exs' ),
						'pt-3'  => esc_html__( '3em', 'exs' ),
						'pt-4'  => esc_html__( '4em', 'exs' ),
						'pt-5'  => esc_html__( '5em', 'exs' ),
						'pt-6'  => esc_html__( '6em', 'exs' ),
						'pt-7'  => esc_html__( '7em', 'exs' ),
						'pt-8'  => esc_html__( '8em', 'exs' ),
						'pt-9'  => esc_html__( '9em', 'exs' ),
						'pt-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'blog_single_related_posts_pb'                    => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Related posts bottom padding', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_related_posts_pb', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pb-0'  => esc_html__( '0', 'exs' ),
						'pb-1'  => esc_html__( '1em', 'exs' ),
						'pb-2'  => esc_html__( '2em', 'exs' ),
						'pb-3'  => esc_html__( '3em', 'exs' ),
						'pb-4'  => esc_html__( '4em', 'exs' ),
						'pb-5'  => esc_html__( '5em', 'exs' ),
						'pb-6'  => esc_html__( '6em', 'exs' ),
						'pb-7'  => esc_html__( '7em', 'exs' ),
						'pb-8'  => esc_html__( '8em', 'exs' ),
						'pb-9'  => esc_html__( '9em', 'exs' ),
						'pb-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'blog_single_related_posts_fullwidth'           => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Full width related posts', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_related_posts_fullwidth', false ) ),
					'description' => esc_html__( 'Works only if no main sidebar is displayed', 'exs' ),
				),

				'blog_single_meta_options_heading'      => array(
					'type'        => 'block-heading',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( 'Single post meta options', 'exs' ),
					'description' => esc_html__( 'Select what post meta you want to show in single post. Not all layouts will show post meta even if it is checked. Some options may be overridden by child themes and theme skins.', 'exs' ),
				),
				'blog_single_hide_meta_icons'           => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Hide icons in the post meta', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_hide_meta_icons', false ) ),
				),
				'blog_single_show_author'               => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Show author', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_show_author', true ) ),
				),
				'blog_single_show_author_avatar'        => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Show author avatar', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_show_author_avatar', false ) ),
				),
				'blog_single_before_author_word'        => array(
					'type'    => 'text',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Text before author', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_before_author_word', '' ) ),
				),
				'blog_single_show_date'                 => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Show date', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_show_date', true ) ),
				),
				'blog_single_before_date_word'          => array(
					'type'    => 'text',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Text before date', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_before_date_word', '' ) ),
				),
				'blog_single_show_human_date'                        => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Show human difference date', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_show_human_date', false ) ),
				),
				'blog_single_show_date_type'                        => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Date to display', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_show_date_type', 'publish' ) ),
					'choices' => array(
						'publish' => esc_html__( 'Date published', 'exs' ),
						'modify'  => esc_html__( 'Date modified', 'exs' ),
						'both'    => esc_html__( 'Dade published and modified', 'exs' ),
					),
				),
				'blog_single_before_date_modify_word'                 => array(
					'type'    => 'text',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Text before date modified', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_before_date_modify_word', '' ) ),
				),
				'blog_single_show_categories'           => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Show categories', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_show_categories', true ) ),
				),
				'blog_single_before_categories_word'    => array(
					'type'    => 'text',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Text before categories', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_before_categories_word', '' ) ),
				),
				'blog_single_show_tags'                 => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Show tags', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_show_tags', true ) ),
				),
				'blog_single_before_tags_word'          => array(
					'type'    => 'text',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Text before tags', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_before_tags_word', '' ) ),
				),
				'blog_single_show_comments_link'        => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Show comments count', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_show_comments_link', true ) ),
					'choices' => array(
						''       => esc_html__( 'None', 'exs' ),
						'text'   => esc_html__( 'Comments number with text', 'exs' ),
						'number' => esc_html__( 'Only comments number', 'exs' ),
					),
				),
				'blog_single_show_date_over_image'                 => array(
					'type'    => 'image-radio',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Show date over featured image', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_show_date_over_image', false ) ),
					'choices' => array(
						''       => array(
							'label' => esc_html__( 'None', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/none.png',
						),
						'top-left'  =>  array(
							'label' => esc_html__( 'Top Left', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/top-left.png'
						),
						'top-right' => array(
							'label' => esc_html__( 'Top Right', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/top-right.png'
						),
						'bottom-left' => array(
							'label' => esc_html__( 'Bottom Left', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/bottom-left.png'
						),
						'bottom-right' =>  array(
							'label' => esc_html__( 'Bottom Right', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/bottom-right.png'
						),
					),
				),
				'blog_single_show_categories_over_image'           => array(
					'type'    => 'image-radio',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Show categories over featured image', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_show_categories_over_image', false ) ),
					'choices' => array(
						''       => array(
							'label' => esc_html__( 'None', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/none.png',
						),
						'top-left'  =>  array(
							'label' => esc_html__( 'Top Left', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/top-left.png'
						),
						'top-right' => array(
							'label' => esc_html__( 'Top Right', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/top-right.png'
						),
						'bottom-left' => array(
							'label' => esc_html__( 'Bottom Left', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/bottom-left.png'
						),
						'bottom-right' =>  array(
							'label' => esc_html__( 'Bottom Right', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/bottom-right.png'
						),
					),
				),
				'blog_single_meta_font_size'                            => array(
					'type'    => 'slider',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Post meta font size', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_meta_font_size', '' ) ),
					'description' => esc_html__( 'Value in pixels', 'exs' ),
					'atts'        => array(
						'min'         => '10',
						'max'         => '20',
						'step'        => '1',
					),
				),
				'blog_single_meta_bold'                        => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Bold meta font weight', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_meta_bold', '' ) ),
				),
				'blog_single_meta_uppercase'                        => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Uppercase font meta', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_meta_uppercase', '' ) ),
				),
				//comments options - since 1.9.5
				'blog_single_comments_heading'     => array(
					'type'        => 'block-heading',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( 'Comments area settings', 'exs' ),
					'description' => esc_html__( 'Some comments area options may be overridden by child themes and theme skins', 'exs' ),

				),
				'blog_single_comments_mt'                    => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Comments area top margin', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_comments_mt', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mt-0'  => '0',
						'mt-05' => '0.5em',
						'mt-1'  => '1em',
						'mt-15' => '1.5em',
						'mt-2'  => '2em',
						'mt-3'  => '3em',
						'mt-4'  => '4em',
						'mt-5'  => '5em',
					),
				),
				'blog_single_comments_mb'                    => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Comments area bottom margin', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_comments_mb', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mb-0'  => '0',
						'mb-05' => '0.5em',
						'mb-1'  => '1em',
						'mb-15' => '1.5em',
						'mb-2'  => '2em',
						'mb-3'  => '3em',
						'mb-4'  => '4em',
						'mb-5'  => '5em',
					),
				),
				'blog_single_comments_background'           => array(
					'type'    => 'color-radio',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Comments area background', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_comments_background', false ) ),
					'choices' => exs_customizer_backgrounds_array(),
				),
				'blog_single_comments_section'           => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Display comments area as separate section', 'exs' ),
					'description' => esc_html__( 'Useful if background color is selected and no main sidebar is displayed. and no main sidebar is displayed.', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_comments_section', false ) ),
				),
				'blog_single_comments_pt'                    => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Comments area top padding', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_comments_pt', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pt-0'  => esc_html__( '0', 'exs' ),
						'pt-1'  => esc_html__( '1em', 'exs' ),
						'pt-2'  => esc_html__( '2em', 'exs' ),
						'pt-3'  => esc_html__( '3em', 'exs' ),
						'pt-4'  => esc_html__( '4em', 'exs' ),
						'pt-5'  => esc_html__( '5em', 'exs' ),
						'pt-6'  => esc_html__( '6em', 'exs' ),
						'pt-7'  => esc_html__( '7em', 'exs' ),
						'pt-8'  => esc_html__( '8em', 'exs' ),
						'pt-9'  => esc_html__( '9em', 'exs' ),
						'pt-10' => esc_html__( '10em', 'exs' ),
					),
				),
				'blog_single_comments_pb'                    => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Comments area bottom padding', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_comments_pb', '' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'pb-0'  => esc_html__( '0', 'exs' ),
						'pb-1'  => esc_html__( '1em', 'exs' ),
						'pb-2'  => esc_html__( '2em', 'exs' ),
						'pb-3'  => esc_html__( '3em', 'exs' ),
						'pb-4'  => esc_html__( '4em', 'exs' ),
						'pb-5'  => esc_html__( '5em', 'exs' ),
						'pb-6'  => esc_html__( '6em', 'exs' ),
						'pb-7'  => esc_html__( '7em', 'exs' ),
						'pb-8'  => esc_html__( '8em', 'exs' ),
						'pb-9'  => esc_html__( '9em', 'exs' ),
						'pb-10' => esc_html__( '10em', 'exs' ),
					),
				),

				//read progress - since 1.9.3
				'blog_single_read_progress_heading'      => array(
					'type'        => 'block-heading',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( 'Single Post Read Progress Bar Settings', 'exs' ),
					'description' => esc_html__( 'You can show reading progress bar for your single post', 'exs' ),
				),
				'blog_single_read_progress_enabled'                 => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Enable Read Progress Bar', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_read_progress_enabled', false ) ),
				),
				'blog_single_read_progress_height' => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Background image overlay opacity', 'exs' ),
					'default'     => esc_html( exs_option( 'blog_single_read_progress_height', '5' ) ),
					'section'     => 'section_blog_post',
					'atts'        => array(
						'min'         => '1',
						'max'         => '10',
						'step'        => '1',
					),
				),
				'blog_single_read_progress_position'        => array(
					'type'    => 'radio',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Read progress bar position', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_read_progress_position', 'top' ) ),
					'choices' => array(
						'top' => esc_html__( 'Top', 'exs' ),
						'bottom' => esc_html__( 'Bottom', 'exs' ),
					),
				),
				'blog_single_read_progress_background'        => array(
					'type'    => 'color-radio',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Bar background', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_read_progress_background', '' ) ),
					'choices' => exs_customizer_backgrounds_array(),
				),
				'blog_single_read_progress_bar_background'        => array(
					'type'    => 'color-radio',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Progress background', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_read_progress_bar_background', 'i c c2' ) ),
					'choices' => exs_customizer_backgrounds_array( true ),
				),
				//table of contents
				'blog_single_toc_heading'      => array(
					'type'        => 'block-heading',
					'section'     => 'section_blog_post',
					'label'       => esc_html__( 'Single post Table of Contents', 'exs' ),
					'description' => esc_html__( 'You can auto generate a Table of Contents based on heading tags that are exists in your post', 'exs' ),
				),
				'blog_single_toc_enabled'                 => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Enable Table of Contents', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_toc_enabled', false ) ),
				),
				'blog_single_toc_title'          => array(
					'type'    => 'text',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Table of Contents title', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_toc_title', '' ) ),
				),
				'blog_single_toc_background'        => array(
					'type'    => 'color-radio',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Table of Contents background', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_toc_background', '' ) ),
					'choices' => exs_customizer_backgrounds_array(),
				),
				'blog_single_toc_bordered'             => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Add border', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_toc_bordered', false ) ),
				),
				'blog_single_toc_shadow'               => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Add shadow', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_toc_shadow', false ) ),
				),
				'blog_single_toc_rounded'              => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Rounded', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_toc_rounded', false ) ),
				),
				'blog_single_toc_mt'                      => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Table of contents top margin', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_toc_mt', 'mt-2' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mt-0'  => '0',
						'mt-05' => '0.5em',
						'mt-1'  => '1em',
						'mt-15' => '1.5em',
						'mt-2'  => '2em',
						'mt-3'  => '3em',
						'mt-4'  => '4em',
						'mt-5'  => '5em',
					),
				),
				'blog_single_toc_mb'                      => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Table of contents bottom margin', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_toc_mb', 'mb-2' ) ),
					'choices' => array(
						''      => esc_html__( 'Default', 'exs' ),
						'mb-0'  => '0',
						'mb-05' => '0.5em',
						'mb-1'  => '1em',
						'mb-15' => '1.5em',
						'mb-2'  => '2em',
						'mb-3'  => '3em',
						'mb-4'  => '4em',
						'mb-5'  => '5em',
					),
				),
				'blog_single_toc_single_margins'              => array(
					'type'    => 'checkbox',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Indent items depending on heading size', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_toc_single_margins', false ) ),
				),
				'blog_single_toc_after_first_p'              => array(
					'type'    => 'select',
					'section' => 'section_blog_post',
					'label'   => esc_html__( 'Table of Contents position', 'exs' ),
					'default' => esc_html( exs_option( 'blog_single_toc_after_first_p', false ) ),
					'description' => esc_html__( 'You may want to put your table of contents below the first paragraph for SEO purposes or in the sidebar to make it visible outside the post content and make it sticky', 'exs' ),
					'choices' => array(
						''  => esc_html__( 'Above content', 'exs' ),
						'1' => esc_html__( 'Below first paragraph', 'exs' ),
						'2' => esc_html__( 'At the top of main sidebar', 'exs' ),
					),
				),
				//////////
				//search//
				//////////
				'search_layout'                  => array(
					'type'    => 'select',
					'section' => 'section_search',
					'label'   => esc_html__( 'Search results layout', 'exs' ),
					'default' => esc_html( exs_option( 'search_layout', '' ) ),
					'choices' => array(
						''                                => esc_html__( 'Default - top featured image', 'exs' ),
						'default-wide-image'              => esc_html__( 'Wide featured image', 'exs' ),
						'meta-top'                        => esc_html__( 'Meta above image', 'exs' ),
						'meta-side'                       => esc_html__( 'Side post meta', 'exs' ),
						'default-absolute'                => esc_html__( 'Image with meta overlap', 'exs' ),
						'side'                            => esc_html__( 'Side featured image', 'exs' ),
						'side-small'                      => esc_html__( 'Side small featured image', 'exs' ),
						'side-small 2'                    => esc_html__( 'Side small featured image - 2 columns', 'exs' ),
						'side-small 2 masonry'            => esc_html__( 'Side small featured image - 2 columns Masonry', 'exs' ),
						'title-only'                      => esc_html__( 'Only title (no image, meta and excerpt)', 'exs' ),
						'title-meta-only'                 => esc_html__( 'Only title and meta (no image and excerpt)', 'exs' ),
						'default-centered'                => esc_html__( 'Center aligned', 'exs' ),
						'meta-above-title'                => esc_html__( 'Meta above title', 'exs' ),
						'meta-above-title 2'              => esc_html__( 'Meta above title - 2 columns', 'exs' ),
						'meta-above-title 3'              => esc_html__( 'Meta above title - 3 columns', 'exs' ),
						'side-meta'                       => esc_html__( 'Right side featured image', 'exs' ),
						'side-alter'                      => esc_html__( 'Alteration side image', 'exs' ),
						'cols 2'                          => esc_html__( 'Grid - 2 columns', 'exs' ),
						'cols 3'                          => esc_html__( 'Grid - 3 columns', 'exs' ),
						'cols 4'                          => esc_html__( 'Grid - 4 columns', 'exs' ),
						'cols-absolute 2'                 => esc_html__( 'Grid - meta overlap - 2 cols', 'exs' ),
						'cols-absolute 3'                 => esc_html__( 'Grid - meta overlap - 3 cols', 'exs' ),
						'cols-absolute 4'                 => esc_html__( 'Grid - meta overlap - 4 cols', 'exs' ),
						'cols-absolute-no-meta 2'         => esc_html__( 'Grid - title overlap - 2 cols', 'exs' ),
						'cols-absolute-no-meta 3'         => esc_html__( 'Grid - title overlap - 3 cols', 'exs' ),
						'cols-absolute-no-meta 4'         => esc_html__( 'Grid - title overlap - 4 cols', 'exs' ),
						'cols-excerpt 2'                  => esc_html__( 'Grid - centered excerpt no meta - 2 cols', 'exs' ),
						'cols-excerpt 3'                  => esc_html__( 'Grid - centered excerpt no meta - 3 cols', 'exs' ),
						'cols-excerpt 4'                  => esc_html__( 'Grid - centered excerpt no meta - 4 cols', 'exs' ),
						'cols 2 masonry'                  => esc_html__( 'Masonry - 2 columns', 'exs' ),
						'cols 3 masonry'                  => esc_html__( 'Masonry - 3 columns', 'exs' ),
						'cols 4 masonry'                  => esc_html__( 'Masonry - 4 columns', 'exs' ),
						'cols-absolute 2 masonry'         => esc_html__( 'Masonry - meta overlap - 2 cols', 'exs' ),
						'cols-absolute 3 masonry'         => esc_html__( 'Masonry - meta overlap - 3 cols', 'exs' ),
						'cols-absolute 4 masonry'         => esc_html__( 'Masonry - meta overlap - 4 cols', 'exs' ),
						'cols-absolute-no-meta 2 masonry' => esc_html__( 'Masonry - title overlap - 2 cols', 'exs' ),
						'cols-absolute-no-meta 3 masonry' => esc_html__( 'Masonry - title overlap - 3 cols', 'exs' ),
						'cols-absolute-no-meta 4 masonry' => esc_html__( 'Masonry - title overlap - 4 cols', 'exs' ),
						'cols-excerpt 2 masonry'          => esc_html__( 'Masonry - centered excerpt no meta - 2 cols', 'exs' ),
						'cols-excerpt 3 masonry'          => esc_html__( 'Masonry - centered excerpt no meta - 3 cols', 'exs' ),
						'cols-excerpt 4 masonry'          => esc_html__( 'Masonry - centered excerpt no meta - 4 cols', 'exs' ),


					),
				),
				'search_layout_gap'                       => array(
					'type'        => 'select',
					'section'     => 'section_search',
					'label'       => esc_html__( 'Search feed layout gap', 'exs' ),
					'description' => esc_html__( 'Used only for grid and masonry layouts', 'exs' ),
					'default'     => esc_html( exs_option( 'search_layout_gap', '' ) ),
					'choices'     => exs_get_feed_layout_gap_options(),
				),
				'search_featured_image_size'                       => array(
					'type'        => 'select',
					'section'     => 'section_search',
					'label'       => esc_html__( 'Search feed featured image size', 'exs' ),
					'description' => esc_html__( 'You can override image size that is set for the chosen search layout', 'exs' ),
					'default'     => esc_html( exs_option( 'search_featured_image_size', '' ) ),
					'choices'     => exs_get_image_sizes_array(),
				),
				'search_sidebar_position'        => array(
					'type'    => 'radio',
					'section' => 'section_search',
					'label'   => esc_html__( 'Search results sidebar position', 'exs' ),
					'default' => esc_html( exs_option( 'search_sidebar_position', 'no' ) ),
					'choices' => exs_get_sidebar_position_options(),
				),
				'search_show_full_text'          => array(
					'type'    => 'checkbox',
					'section' => 'section_search',
					'label'   => esc_html__( 'Show full text instead of excerpt', 'exs' ),
					'default' => esc_html( exs_option( 'search_show_full_text', false ) ),
				),
				'search_excerpt_length'          => array(
					'type'        => 'number',
					'section'     => 'section_search',
					'label'       => esc_html__( 'Custom excerpt length', 'exs' ),
					'description' => esc_html__( 'Words amount', 'exs' ),
					'default'     => esc_html( exs_option( 'search_excerpt_length', '' ) ),
				),
				'search_read_more_text'          => array(
					'type'    => 'text',
					'section' => 'section_search',
					'label'   => esc_html__( '\'Read More\' text. Leave blank to hide', 'exs' ),
					'default' => esc_html( exs_option( 'search_read_more_text', '' ) ),
				),
				'search_read_more_style'                   => array(
					'type'        => 'radio',
					'section'     => 'section_search',
					'label'       => esc_html__( 'Display \'Read More\' as a link or a button', 'exs' ),
					'default'     => esc_html( exs_option( 'search_read_more_style', '' ) ),
					'choices'     => array(
						'' => esc_html__( 'Simple Link', 'exs' ),
						'button' => esc_html__( 'Simple Button', 'exs' ),
						'button-arrow' => esc_html__( 'Button With Arrow', 'exs' ),
					)
				),
				'search_read_more_block'                   => array(
					'type'    => 'checkbox',
					'section' => 'section_search',
					'label'   => esc_html__( 'Display \'Read More\' link on new line', 'exs' ),
					'default' => esc_html( exs_option( 'search_read_more_block', false ) ),
				),
				'search_meta_options_heading'    => array(
					'type'        => 'block-heading',
					'section'     => 'section_search',
					'label'       => esc_html__( 'Post meta options', 'exs' ),
					'description' => esc_html__( 'Select what post meta you want to show in blog feed. Not all layouts will show post meta even if it is checked.', 'exs' ),
				),
				'search_hide_meta_icons'         => array(
					'type'    => 'checkbox',
					'section' => 'section_search',
					'label'   => esc_html__( 'Hide icons in the post meta', 'exs' ),
					'default' => esc_html( exs_option( 'search_hide_meta_icons', false ) ),
				),
				'search_show_author'             => array(
					'type'    => 'checkbox',
					'section' => 'section_search',
					'label'   => esc_html__( 'Show author', 'exs' ),
					'default' => esc_html( exs_option( 'search_show_author', true ) ),
				),
				'search_show_author_avatar'      => array(
					'type'    => 'checkbox',
					'section' => 'section_search',
					'label'   => esc_html__( 'Show author avatar', 'exs' ),
					'default' => esc_html( exs_option( 'search_show_author_avatar', false ) ),
				),
				'search_before_author_word'      => array(
					'type'    => 'text',
					'section' => 'section_search',
					'label'   => esc_html__( 'Text before author', 'exs' ),
					'default' => esc_html( exs_option( 'search_before_author_word', '' ) ),
				),
				'search_show_date'               => array(
					'type'    => 'checkbox',
					'section' => 'section_search',
					'label'   => esc_html__( 'Show date', 'exs' ),
					'default' => esc_html( exs_option( 'search_show_date', true ) ),
				),
				'search_before_date_word'        => array(
					'type'    => 'text',
					'section' => 'section_search',
					'label'   => esc_html__( 'Text before date', 'exs' ),
					'default' => esc_html( exs_option( 'search_before_date_word', '' ) ),
				),
				'search_show_human_date'         => array(
					'type'    => 'checkbox',
					'section' => 'section_search',
					'label'   => esc_html__( 'Show human difference date', 'exs' ),
					'default' => esc_html( exs_option( 'search_show_human_date', false ) ),
				),
				'search_show_date_type'                        => array(
					'type'    => 'select',
					'section' => 'section_search',
					'label'   => esc_html__( 'Date to display', 'exs' ),
					'default' => esc_html( exs_option( 'search_show_date_type', 'publish' ) ),
					'choices' => array(
						'publish' => esc_html__( 'Date published', 'exs' ),
						'modify'  => esc_html__( 'Date modified', 'exs' ),
						'both'    => esc_html__( 'Dade published and modified', 'exs' ),
					),
				),
				'search_before_date_modify_word'                 => array(
					'type'    => 'text',
					'section' => 'section_search',
					'label'   => esc_html__( 'Text before date modified', 'exs' ),
					'default' => esc_html( exs_option( 'search_before_date_modify_word', '' ) ),
				),

				'search_show_categories'         => array(
					'type'    => 'checkbox',
					'section' => 'section_search',
					'label'   => esc_html__( 'Show categories', 'exs' ),
					'default' => esc_html( exs_option( 'search_show_categories', true ) ),
				),
				'search_before_categories_word'  => array(
					'type'    => 'text',
					'section' => 'section_search',
					'label'   => esc_html__( 'Text before categories', 'exs' ),
					'default' => esc_html( exs_option( 'search_before_categories_word', '' ) ),
				),
				'search_show_tags'               => array(
					'type'    => 'checkbox',
					'section' => 'section_search',
					'label'   => esc_html__( 'Show tags', 'exs' ),
					'default' => esc_html( exs_option( 'search_show_tags', '' ) ),
				),
				'search_before_tags_word'        => array(
					'type'    => 'text',
					'section' => 'section_search',
					'label'   => esc_html__( 'Text before tags', 'exs' ),
					'default' => esc_html( exs_option( 'search_before_tags_word', '' ) ),
				),
				'search_show_comments_link'      => array(
					'type'    => 'select',
					'section' => 'section_search',
					'label'   => esc_html__( 'Show comments count', 'exs' ),
					'default' => esc_html( exs_option( 'search_show_comments_link', true ) ),
					'choices' => array(
						''       => esc_html__( 'None', 'exs' ),
						'text'   => esc_html__( 'Comments number with text', 'exs' ),
						'number' => esc_html__( 'Only comments number', 'exs' ),
					),
				),
				'search_show_date_over_image'                 => array(
					'type'    => 'image-radio',
					'section' => 'section_search',
					'label'   => esc_html__( 'Show date over featured image', 'exs' ),
					'default' => esc_html( exs_option( 'search_show_date_over_image', false ) ),
					'choices' => array(
						''       => array(
							'label' => esc_html__( 'None', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/none.png',
						),
						'top-left'  =>  array(
							'label' => esc_html__( 'Top Left', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/top-left.png'
						),
						'top-right' => array(
							'label' => esc_html__( 'Top Right', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/top-right.png'
						),
						'bottom-left' => array(
							'label' => esc_html__( 'Bottom Left', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/bottom-left.png'
						),
						'bottom-right' =>  array(
							'label' => esc_html__( 'Bottom Right', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/bottom-right.png'
						),
					),
				),
				'search_show_categories_over_image'           => array(
					'type'    => 'image-radio',
					'section' => 'section_search',
					'label'   => esc_html__( 'Show categories over featured image', 'exs' ),
					'default' => esc_html( exs_option( 'search_show_categories_over_image', false ) ),
					'choices' => array(
						''       => array(
							'label' => esc_html__( 'None', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/none.png',
						),
						'top-left'  =>  array(
							'label' => esc_html__( 'Top Left', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/top-left.png'
						),
						'top-right' => array(
							'label' => esc_html__( 'Top Right', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/top-right.png'
						),
						'bottom-left' => array(
							'label' => esc_html__( 'Bottom Left', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/bottom-left.png'
						),
						'bottom-right' =>  array(
							'label' => esc_html__( 'Bottom Right', 'exs' ),
							'image' => EXS_THEME_URI . '/assets/img/customizer/bottom-right.png'
						),
					),
				),


				'search_meta_font_size'                            => array(
					'type'    => 'slider',
					'section' => 'section_search',
					'label'   => esc_html__( 'Post meta font size', 'exs' ),
					'default' => esc_html( exs_option( 'search_meta_font_size', '' ) ),
					'description' => esc_html__( 'Value in pixels', 'exs' ),
					'atts'        => array(
						'min'         => '10',
						'max'         => '20',
						'step'        => '1',
					),
				),
				'search_meta_bold'                        => array(
					'type'    => 'checkbox',
					'section' => 'section_search',
					'label'   => esc_html__( 'Bold meta font weight', 'exs' ),
					'default' => esc_html( exs_option( 'search_meta_bold', '' ) ),
				),
				'search_meta_uppercase'                        => array(
					'type'    => 'checkbox',
					'section' => 'section_search',
					'label'   => esc_html__( 'Uppercase font meta', 'exs' ),
					'default' => esc_html( exs_option( 'search_meta_uppercase', '' ) ),
				),

				'search_none_page_heading'                   => array(
					'type'        => 'block-heading',
					'section'     => 'section_search',
					'label'       => esc_html__( 'Nothing found page options', 'exs' ),
					'description' => esc_html__( 'Leave blank for defaults.', 'exs' ),
				),
				'search_none_heading'      => array(
					'type'    => 'text',
					'section' => 'section_search',
					'label'   => esc_html__( 'Not Found Title', 'exs' ),
					'default' => esc_html( exs_option( 'search_none_heading', '' ) ),
				),
				'search_none_text'      => array(
					'type'    => 'text',
					'section' => 'section_search',
					'label'   => esc_html__( 'Not Found Text', 'exs' ),
					'default' => esc_html( exs_option( 'search_none_text', '' ) ),
				),
				'search_none_content'      => array(
					'type'    => 'text',
					'section' => 'section_search',
					'label'   => esc_html__( 'No Content Text', 'exs' ),
					'default' => esc_html( exs_option( 'search_none_content', '' ) ),
				),
				///////
				//404//
				///////
				'404_title'      => array(
					'type'    => 'text',
					'section' => 'section_404',
					'label'   => esc_html__( '404 Page Title', 'exs' ),
					'default' => esc_html( exs_option( '404_title', esc_html__( '404', 'exs' ) ) ),
					'description' => esc_html__( 'Appears in the title section', 'exs' ),
				),
				'404_heading'      => array(
					'type'    => 'text',
					'section' => 'section_404',
					'label'   => esc_html__( '404 Page Heading', 'exs' ),
					'default' => esc_html( exs_option( '404_heading', esc_html__( '404', 'exs' ) ) ),
				),
				'404_subheading'      => array(
					'type'    => 'text',
					'section' => 'section_404',
					'label'   => esc_html__( '404 Page Subheading', 'exs' ),
					'default' => esc_html( exs_option( '404_subheading', esc_html__( 'Oops, page not found!', 'exs' ) ) ),
				),
				'404_text_top'      => array(
					'type'    => 'text',
					'section' => 'section_404',
					'label'   => esc_html__( '404 Page Top Text', 'exs' ),
					'default' => esc_html( exs_option( '404_text_top', esc_html__( 'You can search what interested:', 'exs' ) ) ),
				),
				'404_show_searchform'               => array(
					'type'    => 'checkbox',
					'section' => 'section_404',
					'label'   => esc_html__( 'Show Search Form', 'exs' ),
					'default' => esc_html( exs_option( '404_show_searchform', true ) ),
				),
				'404_text_bottom'      => array(
					'type'    => 'text',
					'section' => 'section_404',
					'label'   => esc_html__( '404 Page Bottom Text', 'exs' ),
					'default' => esc_html( exs_option( '404_text_bottom', esc_html__( 'Or', 'exs' ) ) ),
				),
				'404_text_button'      => array(
					'type'    => 'text',
					'section' => 'section_404',
					'label'   => esc_html__( '404 Page Button Text', 'exs' ),
					'default' => esc_html( exs_option( '404_text_button', esc_html__( 'Go To Home', 'exs' ) ) ),
				),
				'404_text_button_url'      => array(
					'type'    => 'text',
					'section' => 'section_404',
					'label'   => esc_html__( '404 Page Button URL', 'exs' ),
					'default' => esc_html( exs_option( '404_text_button_url', '' ) ),
					'description' => esc_html__( 'Home page ULR will be used by default', 'exs' ),

				),

				//////////////////////
				//special categories//
				//////////////////////
				'special_categories_extra'              => array(
					'type'        => 'extra-button',
					'label'       => esc_html__( 'Special categories', 'exs' ),
					'description' => esc_html__( 'Set a post categories for your Portfolio, Services and Team members without using Custom Post Types', 'exs' ),
					'section'     => 'section_special_categories',
				),
				/////////////
				//animation//
				/////////////
				'animation_extra'                       => array(
					'type'        => 'extra-button',
					'label'       => esc_html__( 'Animation Extra Features', 'exs' ),
					'description' => esc_html__( 'Activate animation in your Customizer and set animation for your posts, widgets and any Gutenberg block', 'exs' ),
					'section'     => 'section_animation',
				),
				////////////////////
				//contact messages//
				////////////////////
				'contact_message_success'               => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Success message', 'exs' ),
					'default' => esc_html( exs_option( 'contact_message_success', '' ) ),
					'description' => esc_html__( 'Message that will be displayed after success form submission', 'exs' ),
					'section'     => 'section_contact_messages',
				),
				'contact_message_fail'                  => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Error message', 'exs' ),
					'default' => esc_html( exs_option( 'contact_message_fail', '' ) ),
					'description' => esc_html__( 'Message that will be displayed if there was error', 'exs' ),
					'section'     => 'section_contact_messages',
				),
				//////////////////////
				//mailchimp messages//
				//////////////////////
				'mailchimp_api_key'               => array(
					'type'    => 'text',
					'label'   => esc_html__( 'MailChimp API key', 'exs' ),
					'default' => esc_html( exs_option( 'mailchimp_api_key', '' ) ),
					'description' => '<a href="https://mailchimp.com/help/about-api-keys/">' . esc_html__( 'MailChimp API key ', 'exs' ) . '<span class="dashicons dashicons-external" style="vertical-align:sub;text-decoration:none"></span></a>',
					'section'     => 'section_mailchimp_messages',
				),
				'mailchimp_audience_id'               => array(
					'type'    => 'text',
					'label'   => esc_html__( 'MailChimp Audience List ID', 'exs' ),
					'default' => esc_html( exs_option( 'mailchimp_audience_id', '' ) ),
					'description' => '<a href="https://mailchimp.com/help/find-audience-id/"> '. esc_html__( 'MailChimp Audience ID ', 'exs' ) . '<span class="dashicons dashicons-external" style="vertical-align:sub;text-decoration:none"></span></a>',
					'section'     => 'section_mailchimp_messages',
				),
				'mailchimp_message_success'               => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Success message', 'exs' ),
					'default' => esc_html( exs_option( 'mailchimp_message_success', '' ) ),
					'description' => esc_html__( 'Message that will be displayed after success subscription', 'exs' ),
					'section'     => 'section_mailchimp_messages',
				),
				'mailchimp_message_fail'                  => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Error message', 'exs' ),
					'default' => esc_html( exs_option( 'mailchimp_message_fail', '' ) ),
					'description' => esc_html__( 'Message that will be displayed if there was error', 'exs' ),
					'section'     => 'section_mailchimp_messages',
				),
				'mailchimp_message_already'                  => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Already subscribed message', 'exs' ),
					'default' => esc_html( exs_option( 'mailchimp_message_already', '' ) ),
					'description' => esc_html__( 'Message that will be displayed if email is already subscribed', 'exs' ),
					'section'     => 'section_mailchimp_messages',
				),
				//////////////////
				//popup messages//
				//////////////////
				'popup_extra'                           => array(
					'type'        => 'extra-button',
					'label'       => esc_html__( 'Pop-up Messages', 'exs' ),
					'description' => esc_html__( 'Add your top and bottom pop-up messages easily', 'exs' ),
					'section'     => 'section_messages',
				),
				/////////////////
				//Mouse Effects//
				//Since 1.9.9  //
				/////////////////
				'mouse_cursor_enabled'                        => array(
					'type'    => 'checkbox',
					'section' => 'section_mouse_cursor',
					'label'   => esc_html__( 'Enable mouse cursor effect', 'exs' ),
					'default' => esc_html( exs_option( 'mouse_cursor_enabled', '' ) ),
				),
				'mouse_cursor_background'                      => array(
					'type'    => 'color-radio',
					'section' => 'section_mouse_cursor',
					'label'   => esc_html__( 'Mouse Cursor Background Color', 'exs' ),
					'default' => esc_html( exs_option( 'mouse_cursor_background', 'i c' ) ),
					'choices' => exs_customizer_backgrounds_array(),
				),
				'mouse_cursor_border'                      => array(
					'type'    => 'color-radio',
					'section' => 'section_mouse_cursor',
					'label'   => esc_html__( 'Mouse Cursor Border Color', 'exs' ),
					'default' => esc_html( exs_option( 'mouse_cursor_border', '' ) ),
					'choices' => array(
						''       => esc_html__( 'Transparent', 'exs' ),
						'l'      => esc_html__( 'Light', 'exs' ),
						'l m'    => esc_html__( 'Grey', 'exs' ),
						'i'      => esc_html__( 'Dark', 'exs' ),
						'i m'    => esc_html__( 'Darker', 'exs' ),
						'i c'    => esc_html__( 'Accent color', 'exs' ),
						'i c c2' => esc_html__( 'Accent secondary color', 'exs' ),
						'i c c3' => esc_html__( 'Accent third color', 'exs' ),
						'i c c4' => esc_html__( 'Accent fourth color', 'exs' ),
					),
				),
				'mouse_cursor_size' => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Mouse Cursor Size', 'exs' ),
					'default'     => esc_html( exs_option( 'mouse_cursor_size', '20' ) ),
					'section'     => 'section_mouse_cursor',
					'atts'        => array(
						'min'         => '5',
						'max'         => '60',
						'step'        => '1',
					),
				),
				'mouse_cursor_opacity' => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Mouse Cursor Opacity', 'exs' ),
					'default'     => esc_html( exs_option( 'mouse_cursor_opacity', '0.7' ) ),
					'section'     => 'section_mouse_cursor',
					'atts'        => array(
						'min'         => '0.1',
						'max'         => '1',
						'step'        => '0.05',
					),
				),
				'mouse_cursor_opacity_hover' => array(
					'type'        => 'slider',
					'label'       => esc_html__( 'Mouse Cursor Hover Opacity', 'exs' ),
					'default'     => esc_html( exs_option( 'mouse_cursor_opacity_hover', '0.3' ) ),
					'section'     => 'section_mouse_cursor',
					'atts'        => array(
						'min'         => '0.05',
						'max'         => '1',
						'step'        => '0.05',
					),
				),
				'mouse_cursor_hidden' => array(
					'type'        => 'select',
					'label'       => esc_html__( 'Hide mouse cursor effect on screens', 'exs' ),
					'default'     => esc_html( exs_option( 'mouse_cursor_hidden', '' ) ),
					'section'     => 'section_mouse_cursor',
					'choices'     => exs_customizer_responsive_display_array(),
				),
			) //options array
		); //apply_filters
	}
endif;


//init customizer with 'exs_customizer_settings_array' settings filter
add_action( 'init', 'exs_init_customizer_class' );
if ( ! function_exists( 'exs_init_customizer_class' ) ) :
	function exs_init_customizer_class() {
		$exs_customizer = new ExS_Customizer(
			exs_customizer_settings_array()
		);
	}
endif;
