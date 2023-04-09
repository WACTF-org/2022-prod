<?php
/**
 * Theme starter content
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//retrieve starter content for fresh WordPress installation
if ( ! function_exists( 'exs_get_starter_content' ) ) :
	function exs_get_starter_content() {
		// Example - get_theme_starter_content();
		// Define and register starter content to showcase the theme on new sites.
		$starter_content = array(
			'widgets'     => array(
				// Place one core-defined widgets in the first footer widget area.
				'sidebar-1' => array(
					'search',
					'text_about',
					'categories',
				),
				// Place one core-defined widgets in the second footer widget area.
				'sidebar-2' => array(
					'text_business_info',
					'meta',
					'recent-posts',
				),
			),

			// Create the custom image attachments used as post thumbnails for pages.
			'attachments' => array(
				'image-cup' => array(
					'post_title' => _x( 'Example image 1', 'Theme starter content', 'exs' ),
					'file'       => 'assets/img/cup.jpg', // URL relative to the template directory.
				),
				'image-map' => array(
					'post_title' => _x( 'Example image 2', 'Theme starter content', 'exs' ),
					'file'       => 'assets/img/map.jpg', // URL relative to the template directory.
				),
			),

			// Specify the core-defined pages to create and add custom thumbnails to some of them.
			'posts'       => array(
				//wp_block
				'block_title' => array(
					'post_type'    => 'wp_block',
					'post_title'   => esc_html__( 'Title with subtitle', 'exs' ),
					'post_content' => exs_get_html_markup_from_template( 'title-with-subtitle' ),
				),

				//post
				'postone'     => array(
					'post_type'    => 'post',
					'post_title'   => esc_html__( 'Post', 'exs' ),
					'post_content' => exs_get_html_markup_from_template( 'sample-post' ),
					'thumbnail'    => '{{image-cup}}',
					'taxonomy'     => array(
						'category' => array(
							array(
								'term' => esc_html__( 'Blog', 'exs' ),
								'slug' => 'blog',
							),
						),
					),
				),

				//pages
				'front'       => array(
					'post_type'    => 'page',
					'post_title'   => esc_html__( 'Home', 'exs' ),
					'thumbnail'    => '{{image-cup}}',
					'post_content' => exs_get_html_markup_from_template( 'sample-page-home' ),
				),
				'home2'       => array(
					'post_type'    => 'page',
					'post_title'   => esc_html__( 'Home 2', 'exs' ),
					'thumbnail'    => '{{image-cup}}',
					'post_content' => exs_get_html_markup_from_template( 'sample-page-home2' ),
					'template'     => 'page-templates/header-overlap.php',
				),
				'about'       => array(
					'post_type'    => 'page',
					'post_title'   => esc_html__( 'About', 'exs' ),
					'thumbnail'    => '{{image-cup}}',
					'post_content' => exs_get_html_markup_from_template( 'sample-page-about' ),
				),
				'pricing'       => array(
					'post_type'    => 'page',
					'post_title'   => esc_html__( 'Pricing', 'exs' ),
					'thumbnail'    => '{{image-cup}}',
					'post_content' => exs_get_html_markup_from_template( 'sample-page-pricing' ),
					'template'     => 'page-templates/no-sidebar-no-title.php',
				),
				'typography'       => array(
					'post_type'    => 'page',
					'post_title'   => esc_html__( 'Typography', 'exs' ),
					'thumbnail'    => '{{image-cup}}',
					'post_content' => exs_get_html_markup_from_template( 'sample-page-typography' ),
				),
				'contact'     => array(
					'post_type'    => 'page',
					'post_title'   => esc_html__( 'Contact', 'exs' ),
					'thumbnail'    => '{{image-cup}}',
					'post_content' => exs_get_html_markup_from_template( 'sample-page-contact' ),
				),
				'blog',
			),

			// Default to a static front page and assign the front and posts pages.
			'options'     => array(
				'show_on_front'  => 'page',
				'page_on_front'  => '{{front}}',
				'page_for_posts' => '{{blog}}',
			),

			// Set up nav menus for each of the two areas registered in the theme.
			'nav_menus'   => array(
				// Assign a menu to the "primary" location.
				'primary'  => array(
					'name'  => esc_html__( 'Primary', 'exs' ),
					'items' => array(
						'link_home',
						// Note that the core "home" page is actually a link in case a static front page is not used.
						'page_home2' => array(
							'type'      => 'post_type',
							'object'    => 'page',
							'object_id' => '{{home2}}',
						),
						'page_about',
						'page_pricing'  => array(
							'type'      => 'post_type',
							'object'    => 'page',
							'object_id' => '{{pricing}}',
						),
						'page_blog',
						'post_postone' => array(
							'type'      => 'post_type',
							'object'    => 'post',
							'object_id' => '{{postone}}',
						),
						'page_contact',
					),
				),
				// This replicates primary just to demonstrate the expanded menu.
				'expanded' => array(
					'name'  => esc_html__( 'Primary', 'exs' ),
					'items' => array(
						'link_home',
						// Note that the core "home" page is actually a link in case a static front page is not used.
						'page_home2' => array(
							'type'      => 'post_type',
							'object'    => 'page',
							'object_id' => '{{home2}}',
						),
						'page_about',
						'page_pricing' => array(
							'type'      => 'post_type',
							'object'    => 'page',
							'object_id' => '{{pricing}}',
						),
						'page_blog',
						'page_contact',
					),
				),
			),
		);

		/**
		 * Filters ExS array of starter content.
		 *
		 * @param array $starter_content Array of starter content.
		 *
		 * @since ExS 0.0.1
		 *
		 */
		return apply_filters( 'exs_starter_content', $starter_content );

	}
endif;
