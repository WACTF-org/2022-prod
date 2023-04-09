<?php
/**
 * Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//load parent CSS
if ( ! function_exists( 'exs_dark_enqueue_static' ) ) :
	/**
	 * exs_dark_enqueue_static
	 *
	 * @return void
	 * @since 1.0.0
	 */
	function exs_dark_enqueue_static() {

		$min = exs_option( 'assets_min' ) && ! EXS_DEV_MODE ? 'min/' : '';
		//main theme css file
		wp_enqueue_style( 'exs-dark-style', get_stylesheet_directory_uri() . '/assets/css/' . $min . 'main.css', array( 'exs-style' ), wp_get_theme()->get( 'Version' ) );

		if ( function_exists( 'exs_extra_enqueue_static' ) ) {
			return;
		}

		//custom Google fonts css file and inline styles if option is enabled
		$exs_font_body     = json_decode( exs_option( 'font_body', '{"font":"","variant": [],"subset":[]}' ) );
		$exs_font_headings = json_decode( exs_option( 'font_headings', '{"font":"","variant": [],"subset":[]}' ) );

		if ( ! empty( $exs_font_body->font ) || ! empty( $exs_font_headings->font ) ) {
			/*
			Translators: If there are characters in your language that are not supported
			by chosen font(s), translate this to 'off'. Do not translate into your own language.
			*/

			if ( 'off' !== esc_html_x( 'on', 'Google font: on or off', 'exs-dark' ) ) {
				$exs_body_variants = array();
				$exs_body_subsets  = array();
				if ( ! empty( $exs_font_body->font ) ) {
					$exs_body_variants = $exs_font_body->variant;
					$exs_body_subsets  = $exs_font_body->subset;
				}

				$exs_headings_variants = array();
				$exs_headings_subsets  = array();
				if ( ! empty( $exs_font_headings->font ) ) {
					$exs_headings_variants = $exs_font_headings->variant;
					$exs_headings_subsets  = $exs_font_headings->subset;
				}

				$exs_fonts    = array(
					'body'     => $exs_font_body->font,
					'headings' => $exs_font_headings->font,
				);
				$exs_variants = array(
					'body'     => implode( ',', $exs_body_variants ),
					'headings' => implode( ',', $exs_headings_variants ),
				);
				$exs_subsets  = array(
					'body'     => implode( ',', $exs_body_subsets ),
					'headings' => implode( ',', $exs_headings_subsets ),
				);
				//'Montserrat|Bowlby One|Quattrocento Sans';
				$exs_fonts_string    = implode( '|', array_filter( $exs_fonts ) );
				$exs_variants_string = implode( ',', array_filter( $exs_variants ) );
				$exs_variants_string = ! empty( $exs_variants_string ) ? ':' . $exs_variants_string : '';
				$exs_subsets_string  = implode( ',', array_filter( $exs_subsets ) );

				$exs_query_args = array(
					'family' => urlencode( $exs_fonts_string . $exs_variants_string ),
				);
				if ( ! empty( $exs_subsets_string ) ) {
					$exs_query_args['subset'] = urlencode( $exs_subsets_string );
				}
				$exs_font_url = add_query_arg(
					$exs_query_args,
					'//fonts.googleapis.com/css'
				);

				//no need to provide anew version for Google fonts link - exs-style added to load it before google fonts style
				wp_enqueue_style( 'exs-google-fonts-style', $exs_font_url, array( 'exs-style' ), '1.0.0' );

				//printing header styles
				$exs_body_style = ( ! empty( $exs_font_body->font ) ) ? 'body,button,input,select,textarea{font-family:"' . $exs_font_body->font . '",sans-serif}' : '';

				$exs_headings_style = ( ! empty( $exs_font_headings->font ) ) ? 'h1,h2,h3,h4,h5,h6{font-family: "' . $exs_font_headings->font . '",sans-serif}' : '';

				wp_add_inline_style(
					'exs-google-fonts-style',
					wp_kses(
						$exs_body_style . $exs_headings_style,
						false
					)
				);
			}
		}
	}
endif;
add_action( 'wp_enqueue_scripts', 'exs_dark_enqueue_static', 20 );

//setup theme
if ( ! function_exists( 'exs_dark_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function exs_dark_setup() {
		//Background image for header and title sections
		$exs_custom_header_args = array(
			'default-image' => get_stylesheet_directory_uri() . '/assets/img/header-image.jpg',
			'width'       => 1920,
			'height'      => 800,
			'header-text' => false,
			'flex-height'   => true,
			'flex-width'    => true,
		);
		add_theme_support( 'custom-header', $exs_custom_header_args );
	}
endif;
add_action( 'after_setup_theme', 'exs_dark_setup' );

//theme options
if ( ! function_exists( 'exs_dark_default_options' ) ) :
	function exs_dark_default_options() {
		return array(
			'demo_number' => '',
			'colors_palette_block_heading' => '',
			'colorLight' => '#1e1e1e',
			'colorFont' => '#c1c1c1',
			'colorFontMuted' => '#aaaaaa',
			'colorBackground' => '#262626',
			'colorBorder' => '#636363',
			'colorDark' => '#f2f2f2',
			'colorDarkMuted' => '#ffffff',
			'colorMain' => '#ffa14f',
			'colorMain2' => '#ff7700',
			'colorMain3' => '#e678f5',
			'colorMain4' => '#7892f5',
			'colors_theme_elements_block_heading' => '',
			'color_meta_icons' => 'meta-icons-main',
			'color_meta_text' => '',
			'color_links_menu' => '',
			'color_links_menu_hover' => '',
			'color_links_content' => 'i c',
			'color_links_content_hover' => 'i c c2',
			'intro_block_heading' => '',
			'intro_position' => 'after',
			'intro_layout' => '',
			'intro_fullscreen' => '',
			'intro_background' => '',
			'intro_alignment' => 'text-center',
			'intro_extra_padding_top' => 'pt-4',
			'intro_extra_padding_bottom' => 'pb-8',
			'intro_show_search' => '1',
			'intro_font_size' => 'fs-18',
			'intro_background_image_heading' => '',
			'intro_background_image' => '',
			'intro_image_animation' => '',
			'intro_background_image_cover' => '1',
			'intro_background_image_fixed' => '1',
			'intro_background_image_overlay' => '',
			'intro_background_image_overlay_opacity' => '',
			'intro_section_content_heading' => '',
			'intro_heading' => 'Meet ExS Dark',
			'intro_heading_mt' => '',
			'intro_heading_mb' => '',
			'intro_heading_animation' => '',
			'intro_description' => '',
			'intro_description_mt' => '',
			'intro_description_mb' => '',
			'intro_description_animation' => '',
			'intro_button_text_first' => '',
			'intro_button_url_first' => '',
			'intro_button_first_animation' => '',
			'intro_button_text_second' => '',
			'intro_button_url_second' => '',
			'intro_button_second_animation' => '',
			'intro_buttons_mt' => '',
			'intro_buttons_mb' => '',
			'intro_shortcode' => '',
			'intro_shortcode_mt' => '',
			'intro_shortcode_mb' => '',
			'intro_shortcode_animation' => '',
			'intro_teasers_block_heading' => '',
			'intro_teaser_section_layout' => '',
			'intro_teaser_section_background' => '',
			'intro_teaser_section_padding_top' => 'pt-5',
			'intro_teaser_section_padding_bottom' => 'pb-5',
			'intro_teaser_font_size' => '',
			'intro_teaser_layout' => 'text-center',
			'intro_teaser_heading' => '',
			'intro_teaser_description' => '',
			'logo' => '1',
			'logo_text_primary' => 'ExS',
			'logo_text_primary_fs' => '26',
			'logo_text_primary_fs_xl' => '',
			'logo_text_primary_hidden' => '',
			'logo_text_secondary' => 'THEME',
			'logo_text_secondary_fs' => '14',
			'logo_text_secondary_fs_xl' => '',
			'logo_text_secondary_hidden' => '',
			'header_top_tall' => '1',
			'logo_background' => '',
			'logo_padding_horizontal' => '',
			'skins_extra' => '',
			'main_container_width' => '1400',
			'blog_container_width' => '',
			'blog_single_container_width' => '',
			'search_container_width' => '',
			'preloader' => '',
			'widgets_ul_margin' => '1.25',
			'box_fade_in' => '',
			'totop' => '1',
			'assets_lightbox' => '1',
			'assets_min' => '1',
			'post_thumbnails_fullwidth' => '1',
			'post_thumbnails_centered' => '',
			'assets_main_nob' => '',
			'assets_nob' => '',
			'remove_widgets_block_editor' => '1',
			'buttons_uppercase' => '',
			'buttons_bold' => '1',
			'buttons_colormain' => '1',
			'buttons_outline' => '',
			'buttons_big' => '1',
			'buttons_radius' => 'btns-rounded',
			'buttons_fs' => '',
			'button_burger' => '4',
			'buttons_pagination' => '2',
			'buttons_social' => '',
			'buttons_social_gap' => '',
			'meta_email' => '',
			'meta_email_label' => '',
			'meta_phone' => '',
			'meta_phone_label' => '',
			'meta_address' => '',
			'meta_address_label' => '',
			'meta_opening_hours' => '',
			'meta_opening_hours_label' => '',
			'meta_facebook' => '',
			'meta_twitter' => '',
			'meta_youtube' => '',
			'meta_instagram' => '',
			'meta_pinterest' => '',
			'meta_linkedin' => '',
			'meta_github' => '',
			'meta_tiktok' => '',
			'side_extra' => '',
			'header_image_background_image_inverse' => '',
			'header_image_background_image_cover' => '',
			'header_image_background_image_fixed' => '1',
			'header_image_background_image_overlay' => 'overlay-dark',
			'header_image_background_image_overlay_opacity' => '60',
			'header_menu_options_heading_desktop' => '',
			'header_align_main_menu' => 'menu-right',
			'header_menu_uppercase' => '',
			'header_menu_bold' => '',
			'menu_desktop' => '',
			'header_menu_options_heading_mobile' => '',
			'header_toggler_menu_main' => '1',
			'menu_breakpoint' => '',
			'mobile_nav_width' => '340',
			'mobile_nav_px' => '40',
			'menu_mobile' => '3',
			'header' => '1',
			'header_logo_hidden' => '',
			'header_fluid' => '',
			'header_background' => 'l',
			'header_absolute' => '',
			'header_transparent' => '',
			'header_border_top' => '',
			'header_border_bottom' => '',
			'header_font_size' => '',
			'header_sticky' => 'always-sticky',
			'header_login_links' => '',
			'header_login_links_hidden' => '',
			'header_search' => 'button',
			'header_search_hidden' => '',
			'header_button_text' => '',
			'header_button_url' => '',
			'header_button_hidden' => '',
			'header_toplogo_options_heading' => '',
			'header_toplogo_background' => 'l',
			'header_toplogo_hidden' => '',
			'header_toplogo_social_hidden' => '',
			'header_toplogo_meta_hidden' => '',
			'header_toplogo_search_hidden' => '',
			'header_topline_options_heading' => '',
			'topline' => '',
			'topline_fluid' => '1',
			'topline_background' => 'i c',
			'meta_topline_text' => '',
			'topline_font_size' => 'fs-14',
			'topline_login_links' => '',
			'title' => '3',
			'title_fluid' => '',
			'title_show_title' => '1',
			'title_show_breadcrumbs' => '1',
			'title_show_search' => '',
			'title_background' => 'l m',
			'title_border_top' => '',
			'title_border_bottom' => '',
			'title_extra_padding_top' => 'pt-4',
			'title_extra_padding_bottom' => 'pb-7',
			'title_font_size' => '',
			'title_hide_taxonomy_name' => '',
			'title_background_image_heading' => '',
			'title_background_image' => '',
			'title_background_image_cover' => '1',
			'title_background_image_fixed' => '1',
			'title_background_image_overlay' => '',
			'title_background_image_overlay_opacity' => '',
			'title_single_post_meta_heading' => '',
			'title_blog_single_hide_meta_icons' => '',
			'title_blog_single_show_author' => '',
			'title_blog_single_show_author_avatar' => '',
			'title_blog_single_before_author_word' => '',
			'title_blog_single_show_date' => '',
			'title_blog_single_before_date_word' => '',
			'title_blog_single_show_human_date' => '',
			'title_blog_single_show_categories' => '',
			'title_blog_single_before_categories_word' => '',
			'title_blog_single_show_tags' => '',
			'title_blog_single_before_tags_word' => '',
			'title_blog_single_show_comments_link' => '',
			'main_sidebar_width' => '25',
			'main_gap_width' => '4.5',
			'main_sidebar_sticky' => '1',
			'main_extra_padding_top' => 'pt-6',
			'main_extra_padding_bottom' => 'pb-6',
			'main_font_size' => '',
			'sidebar_font_size' => 'fs-14',
			'main_sidebar_widgets_heading' => '',
			'main_sidebar_widgets_title_uppercase' => '',
			'main_sidebar_widgets_title_bold' => '',
			'main_sidebar_widgets_title_decor' => '',
			'footer_top' => '',
			'footer_top_content_heading_text' => '',
			'footer_top_image' => '',
			'footer_top_pre_heading' => '',
			'footer_top_pre_heading_mt' => '',
			'footer_top_pre_heading_mb' => '',
			'footer_top_pre_heading_animation' => '',
			'footer_top_heading' => '',
			'footer_top_heading_mt' => '',
			'footer_top_heading_mb' => '',
			'footer_top_heading_animation' => '',
			'footer_top_description' => '',
			'footer_top_description_mt' => '',
			'footer_top_description_mb' => '',
			'footer_top_description_animation' => '',
			'footer_top_shortcode' => '',
			'footer_top_shortcode_mt' => '',
			'footer_top_shortcode_mb' => '',
			'footer_top_shortcode_animation' => '',
			'footer_top_options_heading_text' => '',
			'footer_top_fluid' => '',
			'footer_top_background' => '',
			'footer_top_border_top' => '',
			'footer_top_border_bottom' => '',
			'footer_top_extra_padding_top' => '',
			'footer_top_extra_padding_bottom' => '',
			'footer_top_font_size' => '',
			'footer_top_background_image' => '',
			'footer_top_background_image_cover' => '',
			'footer_top_background_image_fixed' => '',
			'footer_top_background_image_overlay' => '',
			'footer_top_background_image_overlay_opacity' => '',
			'footer' => '1',
			'footer_layout_gap' => '50',
			'footer_fluid' => '',
			'footer_background' => 'l m',
			'footer_border_top' => 'full',
			'footer_border_bottom' => 'full',
			'footer_extra_padding_top' => 'pt-7',
			'footer_extra_padding_bottom' => 'pb-1',
			'footer_font_size' => 'fs-14',
			'footer_background_image_heading' => '',
			'footer_background_image' => '',
			'footer_background_image_cover' => '1',
			'footer_background_image_fixed' => '1',
			'footer_background_image_overlay' => '',
			'footer_background_image_overlay_opacity' => '',
			'footer_widgets_heading' => '',
			'footer_sidebar_widgets_title_uppercase' => '1',
			'footer_sidebar_widgets_title_bold' => '',
			'footer_sidebar_widgets_title_decor' => '',
			'copyright' => '1',
			'copyright_text' => '&copy; [year]',
			'copyright_fluid' => '',
			'copyright_background' => 'l m',
			'copyright_extra_padding_top' => 'pt-2',
			'copyright_extra_padding_bottom' => 'pb-2',
			'copyright_font_size' => 'fs-14',
			'copyright_background_image_heading' => '',
			'copyright_background_image' => '',
			'copyright_background_image_cover' => '',
			'copyright_background_image_fixed' => '',
			'copyright_background_image_overlay' => '',
			'copyright_background_image_overlay_opacity' => '',
			'typo_body_heading' => '',
			'typo_body_size' => '',
			'typo_body_weight' => '',
			'typo_body_line_height' => '',
			'typo_body_letter_spacing' => '0.005',
			'typo_p_margin_bottom' => '',
			'font_body_extra' => '',
			'infinite_loop_extra' => '',
			'share_buttons_extra' => '',
			'blog_layout' => 'cols-absolute 4 masonry',
			'blog_layout_gap' => '40',
			'blog_featured_image_size' => '',
			'blog_sidebar_position' => 'no',
			'blog_page_name' => esc_html__( 'Latest Posts', 'exs-dark' ),
			'blog_show_full_text' => '',
			'blog_excerpt_length' => '20',
			'blog_read_more_text' =>  esc_html__( 'Continue Reading...', 'exs-dark' ),
			'blog_read_more_style' => 'button',
			'blog_read_more_block' => '1',
			'blog_hide_taxonomy_type_name' => '',
			'blog_meta_options_heading' => '',
			'blog_hide_meta_icons' => '',
			'blog_show_author' => '1',
			'blog_show_author_avatar' => '',
			'blog_before_author_word' => '',
			'blog_show_date' => '1',
			'blog_before_date_word' => '',
			'blog_show_human_date' => '',
			'blog_show_categories' => '1',
			'blog_before_categories_word' => '',
			'blog_show_tags' => '',
			'blog_before_tags_word' => '',
			'blog_show_comments_link' => 'number',
			'blog_show_date_over_image' => '',
			'blog_show_categories_over_image' => '',
			'blog_single_layout' => 'meta-side',
			'blog_single_featured_image_size' => '',
			'blog_single_sidebar_position' => 'right',
			'blog_single_first_embed_featured' => '',
			'blog_single_fullwidth_featured' => '',
			'blog_single_show_author_bio' => '1',
			'blog_single_author_bio_about_word' => '',
			'blog_single_post_nav_heading' => '',
			'blog_single_post_nav' => 'bg',
			'blog_single_post_nav_word_prev' => 'Prev',
			'blog_single_post_nav_word_next' => 'Next',
			'blog_single_related_posts_heading' => '',
			'blog_single_related_posts' => 'grid-3',
			'blog_single_related_posts_title' =>  esc_html__( 'You may also like:', 'exs-dark' ),
			'blog_single_related_posts_number' => '3',
			'blog_single_related_posts_image_size' => 'exs-square',
			'blog_single_related_posts_base' => '',
			'blog_single_related_show_date' => '1',
			'blog_single_related_posts_readmore_text' => '',
			'blog_single_related_posts_hidden' => '',
			'blog_single_meta_options_heading' => '',
			'blog_single_hide_meta_icons' => '',
			'blog_single_show_author' => '1',
			'blog_single_show_author_avatar' => '',
			'blog_single_before_author_word' => '',
			'blog_single_show_date' => '1',
			'blog_single_before_date_word' => '',
			'blog_single_show_human_date' => '',
			'blog_single_show_categories' => '1',
			'blog_single_before_categories_word' => '',
			'blog_single_show_tags' => '1',
			'blog_single_before_tags_word' => '',
			'blog_single_show_comments_link' => 'text',
			'blog_single_show_date_over_image' => '',
			'blog_single_show_categories_over_image' => '',
			'blog_single_toc_heading' => '',
			'blog_single_toc_enabled' => '',
			'blog_single_toc_title' => '',
			'blog_single_toc_background' => '',
			'blog_single_toc_bordered' => '',
			'blog_single_toc_shadow' => '',
			'blog_single_toc_rounded' => '',
			'blog_single_toc_mt' => '',
			'blog_single_toc_mb' => '',
			'search_layout' => '',
			'search_featured_image_size' => '',
			'search_sidebar_position' => '',
			'search_show_full_text' => '',
			'search_excerpt_length' => '20',
			'search_read_more_text' => '',
			'search_read_more_style' => '',
			'search_read_more_block' => '',
			'search_meta_options_heading' => '',
			'search_hide_meta_icons' => '',
			'search_show_author' => '',
			'search_show_author_avatar' => '',
			'search_before_author_word' => '',
			'search_show_date' => '',
			'search_before_date_word' => '',
			'search_show_human_date' => '',
			'search_show_categories' => '',
			'search_before_categories_word' => '',
			'search_show_tags' => '',
			'search_before_tags_word' => '',
			'search_show_comments_link' => '',
			'search_none_page_heading' => '',
			'search_none_heading' => '',
			'search_none_text' => '',
			'search_none_content' => '',
			'404_title' => '',
			'404_heading' => '',
			'404_subheading' => '',
			'404_text_top' => '',
			'404_show_searchform' => '',
			'404_text_bottom' => '',
			'404_text_button' => '',
			'404_text_button_url' => '',
			'special_categories_extra' => '',
			'animation_extra' => '',
			'contact_message_success' =>  esc_html__( 'Message was sent!', 'exs-dark' ),
			'contact_message_fail' =>  esc_html__( 'There was an error during message sending!', 'exs-dark' ),
			'popup_extra' => '',
			'preset' => '',
			'skin' => '',
			'jquery_to_footer' => '',
			'side_nav_position' => '',
			'side_nav_width' => '',
			'side_nav_px' => '',
			'side_nav_background' => 'l',
			'side_nav_type' => '',
			'side_nav_font_size' => '',
			'header_toggler_menu_side' => '1',
			'side_nav_logo_position' => '',
			'side_nav_meta_position' => 'bottom',
			'side_nav_social_position' => 'bottom',
			'side_nav_sticky_heading' => '',
			'side_nav_sticked' => '',
			'side_nav_sticked_shadow' => '',
			'side_nav_sticked_border' => '',
			'side_nav_header_overlap' => '',
			'infinite_scroll_type' => '',
			'infinite_scroll_label' => '',
			'share_buttons_enabled' => '',
			'share_buttons_post_heading' => '',
			'share_buttons_label_post' => '',
			'share_buttons_type_post' => '',
			'share_buttons_position_post' => '',
			'share_buttons_page_heading' => '',
			'share_buttons_archive_heading' => '',
			'share_buttons_label_archive' => '',
			'share_buttons_type_archive' => '',
			'share_buttons_position_archive' => '',
			'share_buttons_provider_heading' => '',
			'share_buttons_provider_facebook' => '',
			'share_buttons_provider_twitter' => '',
			'share_buttons_provider_pinterest' => '',
			'share_buttons_provider_linkedin' => '',
			'share_buttons_provider_email' => '',
			'share_buttons_provider_buffer' => '',
			'share_buttons_provider_tumblr' => '',
			'share_buttons_provider_reddit' => '',
			'share_buttons_provider_evernote' => '',
			'share_buttons_provider_delicious' => '',
			'share_buttons_provider_stumbleupon' => '',
			'share_buttons_provider_telegram' => '',
			'font_body_heading' => '',
			'font_body' => '{"font":"Inter","variant":["regular","700"],"subset":[]}',
			'font_headings_heading' => '',
			'font_headings' => '{"font":"","variant":[],"subset":[]}',
			'category_portfolio_heading' => '',
			'category_portfolio' => '',
			'category_portfolio_layout' => 'cols-absolute-no-meta 3',
			'category_portfolio_layout_gap' => '5',
			'category_portfolio_sidebar_position' => 'no',
			'category_services_heading' => '',
			'category_services' => '',
			'category_services_layout' => 'cols-excerpt 3',
			'category_services_layout_gap' => '60',
			'category_services_sidebar_position' => 'no',
			'category_team_heading' => '',
			'category_team' => '',
			'category_team_layout' => 'cols-excerpt 3',
			'category_team_layout_gap' => '50',
			'category_team_sidebar_position' => 'no',
			'animation_enabled' => '',
			'animation_sidebar_widgets' => '',
			'animation_footer_widgets' => '',
			'animation_feed_posts' => '',
			'animation_feed_posts_thumbnail' => 'fadeIn',
			'message_top_heading' => '',
			'message_top_id' => '',
			'message_top_text' => '',
			'message_top_close_button_text' => '',
			'message_top_background' => 'l m',
			'message_top_font_size' => '',
			'message_bottom_heading' => '',
			'message_bottom_id' => '',
			'message_bottom_text' => '',
			'message_bottom_close_button_text' => '',
			'message_bottom_background' => 'l m',
			'message_bottom_font_size' => '',
			'message_bottom_layout' => '',
			'message_bottom_bordered' => '',
			'message_bottom_shadow' => '',
			'message_bottom_rounded' => '',
			'intro_teaser_image_1' => '',
			'intro_teaser_title_1' => '',
			'intro_teaser_text_1' => '',
			'intro_teaser_link_1' => '',
			'intro_teaser_button_text_1' => '',
			'intro_teaser_image_2' => '',
			'intro_teaser_title_2' => '',
			'intro_teaser_text_2' => '',
			'intro_teaser_link_2' => '',
			'intro_teaser_button_text_2' => '',
			'intro_teaser_image_3' => '',
			'intro_teaser_title_3' => '',
			'intro_teaser_text_3' => '',
			'intro_teaser_link_3' => '',
			'intro_teaser_button_text_3' => '',
			'intro_teaser_image_4' => '',
			'intro_teaser_title_4' => '',
			'intro_teaser_text_4' => '',
			'intro_teaser_link_4' => '',
			'intro_teaser_button_text_4' => '',
			'typo_heading_h1' => '',
			'typo_size_h1' => '2.5',
			'typo_line_height_h1' => '',
			'typo_letter_spacing_h1' => '',
			'typo_weight_h1' => '700',
			'typo_mt_h1' => '',
			'typo_mb_h1' => '',
			'typo_heading_h2' => '',
			'typo_size_h2' => '1.75',
			'typo_line_height_h2' => '',
			'typo_letter_spacing_h2' => '',
			'typo_weight_h2' => '700',
			'typo_mt_h2' => '',
			'typo_mb_h2' => '',
			'typo_heading_h3' => '',
			'typo_size_h3' => '',
			'typo_line_height_h3' => '',
			'typo_letter_spacing_h3' => '',
			'typo_weight_h3' => '700',
			'typo_mt_h3' => '',
			'typo_mb_h3' => '',
			'typo_heading_h4' => '',
			'typo_size_h4' => '',
			'typo_line_height_h4' => '',
			'typo_letter_spacing_h4' => '',
			'typo_weight_h4' => '700',
			'typo_mt_h4' => '',
			'typo_mb_h4' => '',
			'typo_heading_h5' => '',
			'typo_size_h5' => '',
			'typo_line_height_h5' => '',
			'typo_letter_spacing_h5' => '',
			'typo_weight_h5' => '700',
			'typo_mt_h5' => '',
			'typo_mb_h5' => '',
			'typo_heading_h6' => '',
			'typo_size_h6' => '',
			'typo_line_height_h6' => '',
			'typo_letter_spacing_h6' => '',
			'typo_weight_h6' => '700',
			'typo_mt_h6' => '',
			'typo_mb_h6' => '',
		);
	}
endif;
add_filter( 'exs_default_theme_options', 'exs_dark_default_options' );

//filter page menu
if ( ! function_exists( 'exs_dark_filter_wp_page_menu_args' ) ) :
	function exs_dark_filter_wp_page_menu_args( $args ) {

		$args['menu_class'] = 'top-menu ';
		$args['container'] = 'ul';
		$args['show_home'] = '1';

		return $args;
	}
endif;
add_filter( 'wp_page_menu_args', 'exs_dark_filter_wp_page_menu_args' );

//set pages menu as a fallback menu to header menu if no menu set
if ( ! function_exists( 'exs_dark_filter_wp_page_menu_primary_location' ) ) :
	function exs_dark_filter_wp_page_menu_primary_location( $has_nav_menu, $location ) {
		return 'primary' === $location ? true : $has_nav_menu;
	}
endif;
add_filter( 'has_nav_menu', 'exs_dark_filter_wp_page_menu_primary_location', 10, 2 );

add_filter( 'exs_starter_content', '__return_empty_array' );
