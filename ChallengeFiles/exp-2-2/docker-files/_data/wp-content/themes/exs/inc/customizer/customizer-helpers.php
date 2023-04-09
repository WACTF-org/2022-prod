<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'customize_register', 'exs_customize_register', 999 );
if ( ! function_exists( 'exs_customize_register' ) ) :
	function exs_customize_register( $wp_customize ) {
		//////////
		//colors//
		//////////
		// colorLight
		// colorFont
		// colorFontMuted
		// colorBackground
		// colorBorder
		// colorDark
		// colorDarkMuted
		// colorMain
		// colorMain2
		$wp_customize->get_setting( 'colorLight' )->transport      = 'postMessage';
		$wp_customize->get_setting( 'colorFont' )->transport       = 'postMessage';
		$wp_customize->get_setting( 'colorFontMuted' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'colorBackground' )->transport = 'postMessage';
		$wp_customize->get_setting( 'colorBorder' )->transport     = 'postMessage';
		$wp_customize->get_setting( 'colorDark' )->transport       = 'postMessage';
		$wp_customize->get_setting( 'colorDarkMuted' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'colorMain' )->transport       = 'postMessage';
		$wp_customize->get_setting( 'colorMain2' )->transport      = 'postMessage';
		$wp_customize->get_setting( 'colorMain3' )->transport      = 'postMessage';
		$wp_customize->get_setting( 'colorMain4' )->transport      = 'postMessage';

		//other colors
		$wp_customize->get_setting( 'color_links_menu' )->transport          = 'postMessage';
		$wp_customize->get_setting( 'color_links_menu_hover' )->transport    = 'postMessage';
		$wp_customize->get_setting( 'color_links_content' )->transport       = 'postMessage';
		$wp_customize->get_setting( 'color_links_content_hover' )->transport = 'postMessage';

		//mobile nav via JS
		$wp_customize->get_setting( 'mobile_nav_width' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'mobile_nav_px' )->transport     = 'postMessage';
		//bottom fixed nav
		$wp_customize->get_setting( 'bottom_nav_height' )->transport = 'postMessage';

		//fixed sidebar
		$wp_customize->get_setting( 'fixed_sidebar_width' )->transport = 'postMessage';
		$wp_customize->get_setting( 'fixed_sidebar_px' )->transport    = 'postMessage';

		//additional CSS files
		$wp_customize->get_setting( 'menu_desktop' )->transport       = 'postMessage';
		$wp_customize->get_setting( 'menu_mobile' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'button_burger' )->transport      = 'postMessage';
		$wp_customize->get_setting( 'buttons_pagination' )->transport = 'postMessage';
		$wp_customize->get_setting( 'search_modal' )->transport = 'postMessage';

		////////////////////////
		//buttons,menu,widgets//
		////////////////////////
		$wp_customize->get_setting( 'buttons_uppercase' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'buttons_bold' )->transport              = 'postMessage';
		$wp_customize->get_setting( 'buttons_big' )->transport               = 'postMessage';
		$wp_customize->get_setting( 'buttons_colormain' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'buttons_outline' )->transport           = 'postMessage';
		$wp_customize->get_setting( 'buttons_radius' )->transport            = 'postMessage';
		$wp_customize->get_setting( 'buttons_fs' )->transport                = 'postMessage';
		$wp_customize->get_setting( 'buttons_social_gap' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'header_menu_uppercase' )->transport     = 'postMessage';
		$wp_customize->get_setting( 'header_menu_bold' )->transport          = 'postMessage';
		$wp_customize->get_setting( 'post_thumbnails_fullwidth' )->transport = 'postMessage';
		$wp_customize->get_setting( 'widgets_ul_margin' )->transport         = 'postMessage';

		//////////////
		//color meta//
		//////////////
		$wp_customize->get_setting( 'color_meta_icons' )->transport = 'postMessage';
		$wp_customize->get_setting( 'color_meta_text' )->transport = 'postMessage';

		//////////////
		//containers//
		//////////////
		$section_ids = array(
			'main_container_width',
			'blog_single_container_width',
			'blog_container_width',
			'search_container_width',
			'bbpress_container_width',
			'buddypress_container_width',
			'wpjm_container_width',
			'event_container_width',
			'events_container_width',
			'product_container_width',
			'shop_container_width',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
		endforeach;

		///////////
		//sidebar//
		///////////
		$section_ids = array(
			'blog_sidebar_position',
			'blog_single_sidebar_position',
			'search_sidebar_position',
			'shop_sidebar_position',
			'product_sidebar_position',
			'bbpress_sidebar_position',
			'buddypress_sidebar_position',
			'events_sidebar_position',
			'event_sidebar_position',
			'wpjm_sidebar_position',
			'main_sidebar_widgets_title_uppercase',
			'main_sidebar_widgets_title_bold',
			'main_sidebar_widgets_title_decor',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#aside',
					'container_inclusive' => true,
					'render_callback' => function() {
						get_template_part( 'sidebar' );
					},
				)
			);
		endforeach;

		//////////////
		//typography//
		//////////////
		//body
		$wp_customize->get_setting( 'typo_body_size' )->transport = 'postMessage';
		$wp_customize->get_setting( 'typo_body_weight' )->transport = 'postMessage';
		$wp_customize->get_setting( 'typo_body_line_height' )->transport = 'postMessage';
		$wp_customize->get_setting( 'typo_body_letter_spacing' )->transport = 'postMessage';
		//p
		$wp_customize->get_setting( 'typo_p_margin_bottom' )->transport = 'postMessage';
		//headings
		foreach( array( 1, 2, 3, 4, 5, 6 ) as $h ) {
			$wp_customize->get_setting( 'typo_size_h' . $h )->transport = 'postMessage';
			$wp_customize->get_setting( 'typo_line_height_h' . $h )->transport = 'postMessage';
			$wp_customize->get_setting( 'typo_letter_spacing_h' . $h )->transport = 'postMessage';
			$wp_customize->get_setting( 'typo_weight_h' . $h )->transport = 'postMessage';
			$wp_customize->get_setting( 'typo_mt_h' . $h )->transport = 'postMessage';
			$wp_customize->get_setting( 'typo_mb_h' . $h )->transport = 'postMessage';
		}

		/////////////////////
		//selective refresh//
		/////////////////////

		//#logo
		$section_ids = array(
			'custom_logo',
			'blogname',
			'blogdescription',
			'logo',
			'logo_text_primary',
			'logo_text_primary_fs',
			'logo_text_primary_fs_xl',
			'logo_text_primary_hidden',
			'logo_text_secondary',
			'logo_text_secondary_fs',
			'logo_text_secondary_fs_xl',
			'logo_text_secondary_hidden',
			'logo_background',
			'logo_padding_horizontal',
			'logo_width_zero',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#logo',
					'container_inclusive' => true,
					'render_callback' => function() {
						get_template_part( 'template-parts/header/logo/logo', exs_template_part( 'logo', '1' ) );
					},
				)
			);
		endforeach;

		//////////
		//#intro//
		//////////
		$section_ids = array(
			'intro_layout',
			'intro_fullscreen',
			'intro_background',
			'intro_background_image',
			'intro_image_animation',
			'intro_background_image_cover',
			'intro_background_image_fixed',
			'intro_background_image_overlay',
			'intro_background_image_overlay_opacity',
			'intro_heading',
			'intro_heading_mt',
			'intro_heading_mb',
			'intro_heading_animation',
			'intro_description',
			'intro_description_mt',
			'intro_description_mb',
			'intro_description_animation',
			'intro_button_text_first',
			'intro_button_url_first',
			'intro_button_first_animation',
			'intro_button_text_second',
			'intro_button_url_second',
			'intro_button_second_animation',
			'intro_buttons_mt',
			'intro_buttons_mb',
			'intro_shortcode',
			'intro_shortcode_mt',
			'intro_shortcode_mb',
			'intro_shortcode_animation',
			'intro_alignment',
			'intro_extra_padding_top',
			'intro_extra_padding_bottom',
			'intro_show_search',
			'intro_font_size',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#intro',
					'container_inclusive' => true,
					'render_callback' => function() {
						if ( exs_is_front_page() ) :
							get_template_part( 'template-parts/header/intro' );
						endif;
					},
				)
			);
		endforeach;

		//////////////////
		//#intro-teasers//
		//////////////////
		$section_ids = array(
			'intro_teaser_section_layout',
			'intro_teaser_section_background',
			'intro_teaser_section_padding_top',
			'intro_teaser_section_padding_bottom',
			'intro_teaser_font_size',
			'intro_teaser_layout',
			'intro_teaser_heading',
			'intro_teaser_description',
			'intro_teaser_image_1',
			'intro_teaser_title_1',
			'intro_teaser_text_1',
			'intro_teaser_link_1',
			'intro_teaser_button_text_1',
			'intro_teaser_image_2',
			'intro_teaser_title_2',
			'intro_teaser_text_2',
			'intro_teaser_link_2',
			'intro_teaser_button_text_2',
			'intro_teaser_image_3',
			'intro_teaser_title_3',
			'intro_teaser_text_3',
			'intro_teaser_link_3',
			'intro_teaser_button_text_3',
			'intro_teaser_image_4',
			'intro_teaser_title_4',
			'intro_teaser_text_4',
			'intro_teaser_link_4',
			'intro_teaser_button_text_4',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#intro-teasers',
					'container_inclusive' => true,
					'render_callback' => function() {
						if ( exs_is_front_page() ) :
							get_template_part( 'template-parts/header/intro-teasers' );
						endif;
					},
				)
			);
		endforeach;

		////////////////////////
		//topline,header,title//
		////////////////////////
		$section_ids = array(
			//site meta
			'meta_email',
			'meta_email_label',
			'meta_phone',
			'meta_phone_label',
			'meta_phone_link',
			'meta_address',
			'meta_address_label',
			'meta_opening_hours',
			'meta_opening_hours_label',
			'meta_facebook',
			'meta_twitter',
			'meta_youtube',
			'meta_instagram',
			'meta_pinterest',
			'meta_linkedin',
			'meta_github',
			'meta_tiktok',
			'buttons_social',
			//inverse colors
			'colors_inverse_enabled',
			'colors_inverse_label_default',
			'colors_inverse_label_inverse',
			'colorLightInverse',
			'colorFontInverse',
			'colorFontMutedInverse',
			'colorBackgroundInverse',
			'colorBorderInverse',
			'colorDarkInverse',
			'colorDarkMutedInverse',
			'colors_inverse_hide_label',
			'colors_inverse_hide_switcher',
			'colors_inverse_hide_icon',
			//header
			'header',
			'header_logo_hidden',
			'header_fluid',
			'header_background',
			'header_toplogo_background',
			'header_toplogo_border_top',
			'header_toplogo_hidden',
			'header_toplogo_social_hidden',
			'header_toplogo_search_hidden',
			'header_toplogo_meta_hidden',
			'header_align_main_menu',
			'header_toggler_menu_main',
			'header_toggler_menu_main_center',
			'header_absolute',
			'header_transparent',
			'header_border_top',
			'header_border_bottom',
			'header_font_size',
			'header_sticky',
			'header_search',
			'header_search_hidden',
			'header_login_links',
			'header_login_links_hidden',
			'header_button_text',
			'header_button_url',
			'header_button_hidden',
			'header_topline_options_heading',
			//from site identity
			'header_top_tall',
			//mobile menu new checkboxes
			'menu_mobile_show_logo',
			'menu_mobile_show_search',
			'menu_mobile_show_meta',
			'menu_mobile_show_social',
			//from header image
			'header_image',
			'header_image_background_image_inverse',
			'header_image_background_image_cover',
			'header_image_background_image_fixed',
			'header_image_background_image_overlay',
			'header_image_background_image_overlay_opacity',
			//header bottom section
			'header_bottom',
			'header_bottom_layout_gap',
			'header_bottom_fluid',
			'header_bottom_background',
			'header_bottom_border_top',
			'header_bottom_border_bottom',
			'header_bottom_extra_padding_top',
			'header_bottom_extra_padding_bottom',
			'header_bottom_font_size',
			'header_bottom_background_image',
			'header_bottom_background_image_cover',
			'header_bottom_background_image_fixed',
			'header_bottom_background_image_overlay',
			'header_bottom_background_image_overlay_opacity',
			'header_bottom_hide_widget_titles',
			'header_bottom_lists_inline',
			'header_bottom_hidden',
			//from blog settings - hide taxonomy
			'blog_hide_taxonomy_type_name',
			//from blog settings - blog title
			'blog_page_name',
			//from homepage settings
			'intro_position',
			'intro_teaser_section_layout',
			'topline',
			'topline_fluid',
			'topline_background',
			'meta_topline_text',
			'topline_font_size',
			'topline_login_links',
			'topline_disable_dropdown',
			'title',
			'title_fluid',
			//'title_show_title',
			'title_show_breadcrumbs',
			'title_show_search',
			'title_background',
			'title_border_top',
			'title_border_bottom',
			'title_extra_padding_top',
			'title_extra_padding_bottom',
			'title_font_size',
			'title_hide_taxonomy_name',
			'title_background_image',
			'title_background_image_cover',
			'title_background_image_fixed',
			'title_background_image_overlay',
			'title_background_image_overlay_opacity',
			//title single blog meta
			'title_blog_single_hide_meta_icons',
			'title_blog_single_show_author',
			'title_blog_single_show_author_avatar',
			'title_blog_single_before_author_word',
			'title_blog_single_show_date',
			'title_blog_single_before_date_word',
			'title_blog_single_show_human_date',
			'title_blog_single_show_date_type',
			'title_blog_single_before_date_modify_word',
			'title_blog_single_show_categories',
			'title_blog_single_before_categories_word',
			'title_blog_single_show_tags',
			'title_blog_single_before_tags_word',
			'title_blog_single_show_comments_link',
			//woo
			'header_cart_dropdown',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#top-wrap',
					'container_inclusive' => true,
					'render_callback' => function() {
						get_template_part( 'template-parts/header/header-top' );
					},
				)
			);
		endforeach;

		$section_ids = array(
			'fixed_sidebar_background',
			'fixed_sidebar_border',
			'fixed_sidebar_shadow',
			'fixed_sidebar_font_size',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#sfix',
					'container_inclusive' => true,
					'render_callback' => function() {
						get_template_part( 'template-parts/header/fixed-sidebar' );
					},
				)
			);
		endforeach;

		////////
		//main//
		////////
		///
		$section_ids = array(
			//#main
		'main_sidebar_width',
		'main_gap_width',
		'main_extra_padding_top',
		'main_extra_padding_bottom',
		'main_font_size',
			//aside
		'main_sidebar_sticky',
		'sidebar_font_size',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
		endforeach;

		////////
		//blog//
		////////
		$section_ids = array(
			'blog_layout',
			'blog_featured_image_size',
			'blog_layout_gap',
			//moved to title section
			//'blog_page_name',
			'blog_show_full_text',
			'blog_excerpt_length',
			'blog_read_more_text',
			'blog_read_more_style',
			'blog_read_more_block',
			//moved to title section
			//'blog_hide_taxonomy_type_name',
			'blog_meta_options_heading',
			'blog_hide_meta_icons',
			'blog_show_author',
			'blog_show_author_avatar',
			'blog_before_author_word',
			'blog_show_date',
			'blog_before_date_word',
			'blog_show_human_date',
			'blog_show_date_type',
			'blog_before_date_modify_word',
			'blog_show_categories',
			'blog_before_categories_word',
			'blog_show_tags',
			'blog_before_tags_word',
			'blog_show_comments_link',
			'blog_show_date_over_image',
			'blog_show_categories_over_image',
			'blog_meta_font_size',
			'blog_meta_bold',
			'blog_meta_uppercase',
			'blog_acf_show',
			'blog_acf_title',
			'blog_acf_background',
			'blog_acf_bordered',
			'blog_acf_shadow',
			'blog_acf_rounded',
			'blog_acf_format',
			'blog_acf_hide_labels',
			'blog_acf_mt',
			'blog_acf_mb',
			'blog_acf_css_class',
			'blog_single_acf_show_in_loop',
			'infinite_scroll_label',
			'post_thumbnails_centered',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#layout',
					'container_inclusive' => true,
					'render_callback' => function() {
						get_template_part( 'template-parts/index' );
					},
				)
			);
		endforeach;

		////////
		//post//
		////////
		$section_ids = array(
			'blog_single_layout',
			'blog_single_featured_image_size',
			'blog_single_first_embed_featured',
			'blog_single_fullwidth_featured',
			'blog_single_show_author_bio',
			'blog_single_author_bio_about_word',
			'blog_single_post_nav_heading',
			'blog_single_post_nav',
			'blog_single_post_nav_word_prev',
			'blog_single_post_nav_word_next',
			'blog_single_related_posts_heading',
			'blog_single_related_posts',
			'blog_single_related_posts_title',
			'blog_single_related_posts_number',
			'blog_single_related_posts_image_size',
			'blog_single_related_posts_base',
			'blog_single_related_posts_hidden',
			'blog_single_related_show_date',
			'blog_single_related_posts_readmore_text',
			'blog_single_related_posts_mt',
			'blog_single_related_posts_mb',
			'blog_single_related_posts_background',
			'blog_single_related_posts_section',
			'blog_single_related_posts_pt',
			'blog_single_related_posts_pb',
			'blog_single_related_posts_fullwidth',

			'blog_single_comments_mt',
			'blog_single_comments_mb',
			'blog_single_comments_background',
			'blog_single_comments_section',
			'blog_single_comments_pt',
			'blog_single_comments_pb',

			'blog_single_meta_options_heading',
			'blog_single_hide_meta_icons',
			'blog_single_show_author',
			'blog_single_show_author_avatar',
			'blog_single_before_author_word',
			'blog_single_show_date',
			'blog_single_before_date_word',
			'blog_single_show_human_date',
			'blog_single_show_date_type',
			'blog_single_before_date_modify_word',
			'blog_single_show_categories',
			'blog_single_before_categories_word',
			'blog_single_show_tags',
			'blog_single_before_tags_word',
			'blog_single_show_comments_link',
			'blog_single_show_date_over_image',
			'blog_single_show_categories_over_image',
			'blog_single_meta_bold',
			'blog_single_meta_uppercase',
			'blog_single_meta_font_size',
			'blog_single_toc_enabled',
			'blog_single_toc_title',
			'blog_single_toc_background',
			'blog_single_toc_bordered',
			'blog_single_toc_shadow',
			'blog_single_toc_rounded',
			'blog_single_toc_mt',
			'blog_single_toc_mb',
			'blog_single_toc_single_margins',
			'blog_single_toc_after_first_p',
			'blog_single_acf_show',
			'blog_single_acf_title',
			'blog_single_acf_background',
			'blog_single_acf_bordered',
			'blog_single_acf_shadow',
			'blog_single_acf_rounded',
			'blog_single_acf_format',
			'blog_single_acf_hide_labels',
			'blog_single_acf_mt',
			'blog_single_acf_mb',
			//'blog_single_acf_all_post_types',
			'blog_single_acf_css_class',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#layout',
					'container_inclusive' => true,
					'render_callback' => function() {
						get_template_part( 'template-parts/single' );
					},
				)
			);
		endforeach;

		//////////
		//search//
		//////////
		$section_ids = array(
			'search_layout',
			'search_layout_gap',
			'search_featured_image_size',
			'search_show_full_text',
			'search_excerpt_length',
			'search_read_more_text',
			'search_read_more_style',
			'search_read_more_block',
			'search_meta_options_heading',
			'search_hide_meta_icons',
			'search_show_author',
			'search_show_author_avatar',
			'search_before_author_word',
			'search_show_date',
			'search_before_date_word',
			'search_show_human_date',
			'search_show_date_type',
			'search_before_date_modify_word',
			'search_show_categories',
			'search_before_categories_word',
			'search_show_tags',
			'search_before_tags_word',
			'search_show_comments_link',
			'search_show_date_over_image',
			'search_show_categories_over_image',
			'search_meta_font_size',
			'search_meta_bold',
			'search_meta_uppercase',
			'search_none_heading',
			'search_none_text',
			'search_none_content',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#layout',
					'container_inclusive' => true,
					'render_callback' => function() {
						get_template_part( 'template-parts/search' );
					},
				)
			);
		endforeach;

		////////////////
		//#bottom-wrap//
		////////////////
		$section_ids = array(
			'bottom_background_image',
			'bottom_background_image_cover',
			'bottom_background_image_fixed',
			'bottom_background_image_overlay',
			'bottom_background_image_overlay_opacity',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#bottom-wrap',
					'container_inclusive' => true,
					'render_callback' => function() {
						get_template_part( 'template-parts/bottom-wrap' );
					},
				)
			);
		endforeach;

		///////////
		//#footer//
		///////////
		$section_ids = array(
			'footer',
			'footer_layout_gap',
			'footer_fluid',
			'footer_background',
			'footer_border_top',
			'footer_border_bottom',
			'footer_extra_padding_top',
			'footer_extra_padding_bottom',
			'footer_font_size',
			'footer_background_image',
			'footer_background_image_cover',
			'footer_background_image_fixed',
			'footer_background_image_overlay',
			'footer_background_image_overlay_opacity',
			'footer_sidebar_widgets_title_uppercase',
			'footer_sidebar_widgets_title_bold',
			'footer_sidebar_widgets_title_decor',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#footer',
					'container_inclusive' => true,
					'render_callback' => function() {
						get_template_part( 'template-parts/footer/footer', exs_template_part( 'footer', '1' ) );
					},
				)
			);
		endforeach;

		///////////////
		//#footer-top//
		///////////////
		$section_ids = array(
			'footer_top',
			'footer_top_content_heading_text',
			'footer_top_image',
			'footer_top_pre_heading',
			'footer_top_pre_heading_mt',
			'footer_top_pre_heading_mb',
			'footer_top_pre_heading_animation',
			'footer_top_heading',
			'footer_top_heading_mt',
			'footer_top_heading_mb',
			'footer_top_heading_animation',
			'footer_top_description',
			'footer_top_description_mt',
			'footer_top_description_mb',
			'footer_top_description_animation',
			'footer_top_shortcode',
			'footer_top_shortcode_mt',
			'footer_top_shortcode_mb',
			'footer_top_shortcode_animation',
			'footer_top_options_heading_text',
			'footer_top_fluid',
			'footer_top_background',
			'footer_top_border_top',
			'footer_top_border_bottom',
			'footer_top_extra_padding_top',
			'footer_top_extra_padding_bottom',
			'footer_top_font_size',
			'footer_top_background_image',
			'footer_top_background_image_cover',
			'footer_top_background_image_fixed',
			'footer_top_background_image_overlay',
			'footer_top_background_image_overlay_opacity',
		);

		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#footer-top',
					'container_inclusive' => true,
					'render_callback' => function() {
						get_template_part( 'template-parts/footer-top/section', exs_template_part( 'footer_top', '' ) );
					},
				)
			);
		endforeach;

		//////////////
		//#copyright//
		//////////////
		$section_ids = array(
			'copyright',
			'copyright_text',
			'copyright_fluid',
			'copyright_background',
			'copyright_extra_padding_top',
			'copyright_extra_padding_bottom',
			'copyright_font_size',
			'copyright_background_image',
			'copyright_background_image_cover',
			'copyright_background_image_fixed',
			'copyright_background_image_overlay',
			'copyright_background_image_overlay_opacity',
		);

		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#copyright',
					'container_inclusive' => true,
					'render_callback' => function() {
						get_template_part( 'template-parts/copyright/copyright', exs_template_part( 'copyright', '1' ) );
					},
				)
			);
		endforeach;

		///////////////
		//#nav_bottom//
		///////////////
		$section_ids = array(
			'bottom_nav_background',
			'bottom_nav_border',
			'bottom_nav_shadow',
			'bottom_nav_bold',
			'bottom_nav_uppercase',
			'bottom_nav_show_social',
			'bottom_nav_font_size',
			'bottom_nav_icons_center',
			'bottom_nav_icon_labels_hidden',
		);

		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#nav_bottom',
					'container_inclusive' => true,
					'render_callback' => function() {
						get_template_part( 'template-parts/footer/bottom-nav' );
					},
				)
			);
		endforeach;

		//toTop, read progress
		$section_ids = array(
			'totop',
			//read progress - since 1.9.3
			'blog_single_read_progress_enabled',
			'blog_single_read_progress_height',
			'blog_single_read_progress_position',
			'blog_single_read_progress_background',
			'blog_single_read_progress_bar_background',
			//mouse effects - since 1.9.9
			'mouse_cursor_enabled',
			'mouse_cursor_background',
			'mouse_cursor_border',
			'mouse_cursor_size',
			'mouse_cursor_opacity',
			'mouse_cursor_opacity_hover',
			'mouse_cursor_hidden',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => '#to-top-wrap',
					'container_inclusive' => true,
					'render_callback' => function() {
						get_template_part( 'template-parts/footer/footer-totop' );
					},
				)
			);
		endforeach;
		//preloader
		$wp_customize->get_setting( 'preloader' )->transport = 'postMessage';
		$wp_customize->selective_refresh->add_partial(
			'preloader',
			array(
				'selector' => '#preloader-wrap',
				'container_inclusive' => true,
				'render_callback' => function() {
					get_template_part( 'template-parts/header/header-preloader' );
				},
			)
		);

		//no need to reload page for these sections - just set them as a post message
		//assets_min
		//contact_message_success
		//contact_message_fail
		$wp_customize->get_setting( 'box_fade_in' )->transport = 'postMessage';
		$wp_customize->get_setting( 'assets_lightbox' )->transport = 'postMessage';
		$wp_customize->get_setting( 'assets_min' )->transport = 'postMessage';
		$wp_customize->get_setting( 'contact_message_success' )->transport = 'postMessage';
		$wp_customize->get_setting( 'contact_message_fail' )->transport = 'postMessage';
		$wp_customize->get_setting( 'remove_widgets_block_editor' )->transport = 'postMessage';

		//need an additional JS
		//side_menu - body classes change on options change
		//ALL - remove VISIBLE callback for postMessage (meta as example) - move this in JS?

		////////
		//shop//
		////////

		//default WooCommerce

		//already postMessage?
		//woocommerce_demo_store_notice
		//woocommerce_demo_store

		//shop
		//woocommerce_shop_page_display
		//woocommerce_category_archive_display
		//woocommerce_default_catalog_orderby
		//woocommerce_catalog_columns
		//woocommerce_catalog_rows

		//checkout
		//woocommerce_checkout_company_field
		//woocommerce_checkout_address_2_field
		//woocommerce_checkout_phone_field
		//woocommerce_checkout_highlight_required_fields
		//wp_page_for_privacy_policy
		//woocommerce_terms_page_id

		$section_ids = array(
			'woocommerce_demo_store_notice',
			'woocommerce_demo_store',

			//shop
			'woocommerce_shop_page_display',
			'woocommerce_category_archive_display',
			'woocommerce_default_catalog_orderby',
			'woocommerce_catalog_columns',
			'woocommerce_catalog_rows',

			//checkout
			'woocommerce_checkout_company_field',
			'woocommerce_checkout_address_2_field',
			'woocommerce_checkout_phone_field',
			'woocommerce_checkout_highlight_required_fields',
			'wp_page_for_privacy_policy',
			'woocommerce_terms_page_id',
		);
		foreach( $section_ids as $id ) :
			if ( empty( $wp_customize->get_setting( $id ) ) ) {
				continue;
			}
			$wp_customize->get_setting( $id )->transport = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				$id,
				array(
					'selector' => 'div.woo',
					'container_inclusive' => true,
					'render_callback' => 'exs_woocommerce_pages_ajax_render',
				)
			);
		endforeach;
	}
endif;

//cutsomizer typical backgrounds array
if ( ! function_exists( 'exs_customizer_backgrounds_array' ) ) :
	function exs_customizer_backgrounds_array( $unset_empty = false ) {

		$bg = array(
			''                        => esc_html__( 'Transparent', 'exs' ),
			'l'                       => esc_html__( 'Light', 'exs' ),
			'l m'                     => esc_html__( 'Grey', 'exs' ),
			'i'                       => esc_html__( 'Dark', 'exs' ),
			'i m'                     => esc_html__( 'Darker', 'exs' ),
			'i c'                     => esc_html__( 'Accent color', 'exs' ),
			'i c c2'                  => esc_html__( 'Accent secondary color', 'exs' ),
			'i c c3'                  => esc_html__( 'Accent third color', 'exs' ),
			'i c c4'                  => esc_html__( 'Accent fourth color', 'exs' ),
			'i c gradient'            => esc_html__( 'Vertical gradient', 'exs' ),
			'i c gradient horizontal' => esc_html__( 'Horizontal gradient', 'exs' ),
			'i c gradient diagonal'   => esc_html__( 'Diagonal gradient', 'exs' ),
		);

		if ( ! empty( $unset_empty ) ) {
			unset( $bg[''] );
		}

		return $bg;

	}
endif;

//cutsomizer typical borders array
if ( ! function_exists( 'exs_customizer_borders_array' ) ) :
	function exs_customizer_borders_array() {

		return array(
			''          => esc_html__( 'None', 'exs' ),
			'container' => esc_html__( 'Container width', 'exs' ),
			'full'      => esc_html__( 'Full width', 'exs' ),
		);

	}
endif;

//cutsomizer typical font sizes array
if ( ! function_exists( 'exs_customizer_font_size_array' ) ) :
	function exs_customizer_font_size_array() {
		// see _variables.scss
		//9 10 11 12 13 14 15 16 17 18 19 20 21 22
		return array(
			''      => esc_html__( 'Inherit', 'exs' ),
			'fs-9'  => esc_html__( '9px', 'exs' ),
			'fs-10' => esc_html__( '10px', 'exs' ),
			'fs-11' => esc_html__( '11px', 'exs' ),
			'fs-12' => esc_html__( '12px', 'exs' ),
			'fs-13' => esc_html__( '13px', 'exs' ),
			'fs-14' => esc_html__( '14px', 'exs' ),
			'fs-15' => esc_html__( '15px', 'exs' ),
			'fs-16' => esc_html__( '16px', 'exs' ),
			'fs-17' => esc_html__( '17px', 'exs' ),
			'fs-18' => esc_html__( '18px', 'exs' ),
			'fs-19' => esc_html__( '19px', 'exs' ),
			'fs-20' => esc_html__( '20px', 'exs' ),
			'fs-21' => esc_html__( '21px', 'exs' ),
			'fs-22' => esc_html__( '22px', 'exs' ),
		);

	}
endif;

//cutsomizer typical background overlay array
if ( ! function_exists( 'exs_customizer_background_overlay_array' ) ) :
	function exs_customizer_background_overlay_array() {

		return array(
			''              => esc_html__( 'None', 'exs' ),
			'overlay-dark'  => esc_html__( 'Dark', 'exs' ),
			'overlay-light' => esc_html__( 'Light', 'exs' ),
		);

	}
endif;

//cutsomizer typical responsive_display array
if ( ! function_exists( 'exs_customizer_responsive_display_array' ) ) :
	function exs_customizer_responsive_display_array() {

		return array(
			''            => esc_html__( 'Always visible', 'exs' ),
			'hidden-xl'   => esc_html__( 'Below 1600px', 'exs' ),
			'hidden-lg'   => esc_html__( 'Below 1200px', 'exs' ),
			'hidden-md'   => esc_html__( 'Below 992px', 'exs' ),
			'hidden-sm'   => esc_html__( 'Below 768px', 'exs' ),
			'hidden-xs'   => esc_html__( 'Below 600px', 'exs' ),
			'hidden-xxs'  => esc_html__( 'Below 500px', 'exs' ),
			'hidden-xxxs' => esc_html__( 'Below 400px', 'exs' ),
			'hidden'      => esc_html__( 'Always hidden', 'exs' ),
		);

	}
endif;

//cutsomizer featured teasers options
if ( ! function_exists( 'exs_intro_teasers_options' ) ) :
	function exs_intro_teasers_options() {
		$array = array();
		for ( $i = 1; $i < 5; $i ++ ) {
			/*
			repeatable options:
				intro_teaser_image_
				intro_teaser_title_
				intro_teaser_text_
				intro_teaser_link_
				intro_teaser_button_text_
			*/
			$array[ 'intro_teaser_image_' . $i ]       = array(
				'type'    => 'image',
				'section' => 'static_front_page',
				'label'   => esc_html__( 'Featured block image #', 'exs' ) . $i,
				'default' => esc_html( exs_option( 'intro_teaser_image_' . $i, '' ) ),
			);
			$array[ 'intro_teaser_title_' . $i ]       = array(
				'type'    => 'text',
				'section' => 'static_front_page',
				'label'   => esc_html__( 'Featured block title #', 'exs' ) . $i,
				'default' => esc_html( exs_option( 'intro_teaser_title_' . $i, '' ) ),
			);
			$array[ 'intro_teaser_text_' . $i ]        = array(
				'type'    => 'textarea',
				'section' => 'static_front_page',
				'label'   => esc_html__( 'Featured block text #', 'exs' ) . $i,
				'default' => esc_html( exs_option( 'intro_teaser_text_' . $i, '' ) ),
			);
			$array[ 'intro_teaser_link_' . $i ]        = array(
				'type'    => 'url',
				'section' => 'static_front_page',
				'label'   => esc_html__( 'Featured block link #', 'exs' ) . $i,
				'default' => esc_html( exs_option( 'intro_teaser_link_' . $i, '' ) ),
			);
			$array[ 'intro_teaser_button_text_' . $i ] = array(
				'type'    => 'text',
				'section' => 'static_front_page',
				'label'   => esc_html__( 'Featured block button text #', 'exs' ) . $i,
				'default' => esc_html( exs_option( 'intro_teaser_button_text_' . $i, '' ) ),
			);
		}

		return $array;
	}
endif;

if ( ! function_exists( 'exs_headings_typography_customizer_options' ) ) :
	function exs_headings_typography_customizer_options() {
		$array = array();
		for ( $i = 1; $i < 7; $i ++ ) {
			/*
			repeatable options:
				typo_heading_h#
				typo_size_h#
				typo_line_height_h#
				typo_letter_spacing_h#
				typo_weight_h#
				typo_mt_h#
				typo_mb_h#
			*/

			//todo UPPERCASE?

			$array[ 'typo_heading_h' . $i ]       = array(
				'type'        => 'block-heading',
				'section'     => 'section_typography',
				'label'       => esc_html__( 'Settings for H', 'exs' ) . $i,
				'description' => esc_html__( 'Set your settings for headings. Leave blank for theme defaults.', 'exs' ),
			);
			$array[ 'typo_size_h' . $i ]       = array(
				'type'        => 'slider',
				'label'       => esc_html__( 'Font Size for H', 'exs' ) . $i,
				'description' => esc_html__( 'Value in ems', 'exs' ),
				'section'     => 'section_typography',
				'default'     => esc_html( exs_option( 'typo_size_h' . $i, '' ) ),
				'atts'        => array(
					'min'  => '1',
					'max'  => '4',
					'step' => '0.05',
				),
			);

			$array[ 'typo_line_height_h' . $i ]       = array(
				'type'        => 'slider',
				'label'       => esc_html__( 'Line Height for H', 'exs' ) . $i,
				'description' => esc_html__( 'Value in ems', 'exs' ),
				'section'     => 'section_typography',
				'default'     => esc_html( exs_option( 'typo_line_height_h' . $i, '' ) ),
				'atts'        => array(
					'min'  => '0.4',
					'max'  => '3',
					'step' => '0.05',
				),
			);
			$array[ 'typo_letter_spacing_h' . $i ]       = array(
				'type'        => 'slider',
				'label'       => esc_html__( 'Letter Spacing for H', 'exs' ) . $i,
				'description' => esc_html__( 'Value in ems', 'exs' ),
				'section'     => 'section_typography',
				'default'     => esc_html( exs_option( 'typo_letter_spacing_h' . $i, '' ) ),
				'atts'        => array(
					'min'         => '-0.2',
					'max'         => '0.5',
					'step'        => '0.005',
				),
			);
			$array[ 'typo_weight_h' . $i ] = array(
				'type'        => 'select',
				'label'       => esc_html__( 'Font Weight for H', 'exs' ) . $i,
				'default'     => esc_html( exs_option( 'typo_weight_h' . $i, '' ) ),
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
			);
			$array[ 'typo_mt_h' . $i ]       = array(
				'type'        => 'slider',
				'label'       => esc_html__( 'Top Margin in ems for H', 'exs' ) . $i,
				'section'     => 'section_typography',
				'default'     => esc_html( exs_option( 'typo_mt_h' . $i, '' ) ),
				'atts'        => array(
					'min'         => '0',
					'max'         => '4',
					'step'        => '0.05',
				),
			);
			$array[ 'typo_mb_h' . $i ]       = array(
				'type'        => 'slider',
				'label'       => esc_html__( 'Bottom Margin in ems for H', 'exs' ) . $i,
				'section'     => 'section_typography',
				'default'     => esc_html( exs_option( 'typo_mb_h' . $i, '' ) ),
				'atts'        => array(
					'min'         => '0',
					'max'         => '4',
					'step'        => '0.05',
				),
			);
		}

		return $array;
	}
endif;


//merge homepage featured boxes with main array
if ( ! function_exists( 'exs_add_repeatable_options_to_customizer_settings_array' ) ) :
	function exs_add_repeatable_options_to_customizer_settings_array( $customizer_settings ) {
		$teasers_options  = exs_intro_teasers_options();
		$headings_options = exs_headings_typography_customizer_options();

		return array_merge( $customizer_settings, $teasers_options, $headings_options );
	}
endif;
add_filter( 'exs_customizer_options', 'exs_add_repeatable_options_to_customizer_settings_array' );

//helper div for preview
if ( ! function_exists( 'exs_action_footer_print_preview_helper_div' ) ) :
	function exs_action_footer_print_preview_helper_div( $customizer_settings ) {
		if ( is_customize_preview() ) :
			$exs_view        = '';
			$exs_view_global = '';
			$exs_class       = '';

			//container width
			$exs_container_width            = exs_option( 'main_container_width', '1140' );
			$exs_container_post_width       = exs_option( 'blog_single_container_width', '' );
			$exs_container_blog_width       = exs_option( 'blog_container_width', '' );
			$exs_container_search_width     = exs_option( 'search_container_width', '' );
			$exs_container_bbpress_width    = exs_option( 'bbpress_container_width', '' );
			$exs_container_buddypress_width = exs_option( 'buddypress_container_width', '' );
			$exs_container_wpjm_width       = exs_option( 'wpjm_container_width', '' );
			$exs_container_events_width     = is_singular() ? exs_option( 'event_container_width', '' ) : exs_option( 'events_container_width', '' );
			$exs_container_shop_width       = is_singular() ? exs_option( 'product_container_width', '' ) : exs_option( 'shop_container_width', '' ) ;

			if ( exs_is_shop() ) {
				$exs_view_global = is_singular() ? 'product' : 'shop';
				$exs_view        = is_singular() ? 'product' : 'shop';
				if ( ! empty( $exs_container_shop_width ) ) {
					$exs_container_width = $exs_container_shop_width;
				}
			}

			if ( exs_is_events() ) {
				$exs_view_global = is_singular() ? 'event' : 'events';
				if ( ! empty( $exs_container_events_width ) ) {
					$exs_view            = is_singular() ? 'event' : 'events';
					$exs_container_width = $exs_container_events_width;
				}
			}
			if ( exs_is_wpjm() ) {
				$exs_view_global = 'wpjm';
				if ( ! empty( $exs_container_wpjm_width ) ) {
					$exs_view            = 'wpjm';
					$exs_container_width = $exs_container_wpjm_width;
				}
			}
			if ( exs_is_buddypress() ) {
				$exs_view_global = 'buddypress';
				if ( ! empty( $exs_container_buddypress_width ) ) {
					$exs_view            = 'buddypress';
					$exs_container_width = $exs_container_buddypress_width;
				}
			}
			if ( exs_is_bbpress() ) {
				$exs_view_global = 'bbpress';
				if ( ! empty( $exs_container_bbpress_width ) ) {
					$exs_view            = 'bbpress';
					$exs_container_width = $exs_container_bbpress_width;
				}
			}
			if ( is_singular( 'post' ) ) {
				$exs_view_global = 'post';
				if ( ! empty( $exs_container_post_width ) ) {
					$exs_view            = 'post';
					$exs_container_width = $exs_container_post_width;
				}
			}
			if ( is_search() ) {
				$exs_view_global = 'search';
				if ( ! empty( $exs_container_search_width ) ) {
					$exs_view            = 'search';
					$exs_container_width = $exs_container_search_width;
				}
			}
			if ( ( is_home() || is_category() || is_tag() || is_date() || is_author() ) ){
				$exs_view_global = 'archive';
				if ( ! empty( $exs_container_blog_width ) ) {
					$exs_view            = 'archive';
					$exs_container_width = $exs_container_blog_width;
				}
			}
			if ( '1400' === $exs_container_width ) {
				$exs_class = 'container-1400';
			}
			if ( '1140' === $exs_container_width ) {
				$exs_class = 'container-1140';
			}
			if ( '960' === $exs_container_width ) {
				$exs_class = 'container-960';
			}
			if ( '720' === $exs_container_width ) {
				$exs_class = 'container-720';
			}

			wp_localize_script(
				'exs-init-script',
				'exsPreviewObject',
				array(
					'view'       => $exs_view,
					'viewGlobal' => $exs_view_global,
					'container'  => $exs_class,
				)
			);

		endif;
	}
endif;
add_filter( 'exs_action_before_wp_footer', 'exs_action_footer_print_preview_helper_div' );
