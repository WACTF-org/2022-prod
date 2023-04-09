<?php
/**
 * Theme setup function and sidebars registering
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
//get columns block pattern args
if ( ! function_exists( 'exs_get_block_args_array' ) ) :
	function exs_get_block_args_array( $args = array() ) {

		$class_name = '';
		$class_name .= ! empty( $args['verticalAlignment'] ) ? ' are-vertically-aligned-' . $args['verticalAlignment'] : '';
		$class_name .= ! empty( $args['align'] ) ? ' align' . $args['align'] : '';
		$class_name .= ! empty( $args['section'] ) ? ' section' : '';
		$class_name .= ! empty( $args['padding'] ) ? ' extra-padding' : '';
		$class_name .= ! empty( $args['colsHighlight'] ) ? ' cols-highlight' : '';
		$class_name .= ! empty( $args['colsBordered'] ) ? ' cols-bordered' : '';
		$class_name .= ! empty( $args['colsShadow'] ) ? ' cols-shadow' : '';
		$class_name .= ! empty( $args['colsShadowHover'] ) ? ' cols-shadow-hover' : '';
		$class_name .= ! empty( $args['colsRounded'] ) ? ' cols-rounded' : '';
		$class_name .= ! empty( $args['colsPadding'] ) ? ' cols-padding' : '';
		$class_name .= ! empty( $args['colsSingle'] ) ? ' ' . $args['colsSingle'] : '';
		$class_name .= ! empty( $args['gap'] ) ? ' ' . $args['gap'] : '';
		$class_name .= ! empty( $args['pt'] ) ? ' ' . $args['pt'] : '';
		$class_name .= ! empty( $args['pb'] ) ? ' ' . $args['pb'] : '';
		$class_name .= ! empty( $args['background'] ) ? ' ' . $args['background'] : '';
		$class_name .= ! empty( $args['decorTop'] ) ? ' ' . $args['decorTop'] : '';
		$class_name .= ! empty( $args['decorBottom'] ) ? ' ' . $args['decorBottom'] : '';
		if ( ! empty( $args['decorTop'] ) || ! empty( $args['decorBottom'] ) ) {
			$class_name .= ' decor';
		}

		//group
		$class_name .= ! empty( $args['screen'] ) ? ' screen' : '';
		$class_name .= ! empty( $args['bordered'] ) ? ' bordered' : '';
		$class_name .= ! empty( $args['shadow'] ) ? ' shadow' : '';
		$class_name .= ! empty( $args['rounded'] ) ? ' rounded' : '';

		//separator
		$class_name .= ! empty( $args['mt'] ) ? ' ' . $args['mt'] : '';
		$class_name .= ! empty( $args['mb'] ) ? ' ' . $args['mb'] : '';
		$class_name .= ! empty( $args['height'] ) ? ' ' . $args['height'] : '';
		$class_name .= ! empty( $args['center'] ) ? ' center' : '';
		$class_name .= ! empty( $args['color'] ) ? ' has-text-color has-background has-' . $args['color'] . '-background-color has-' . $args['color'] . '-color' : '';

		$args['className'] = ! empty( $args['className'] ) ? $args['className'] . $class_name : $class_name;
		$args['json'] = json_encode( $args );

		return $args;
	}
endif;

//read HTML makrup from template file
//used in the block patterns and in the starter content
if ( ! function_exists( 'exs_get_html_markup_from_template' ) ) :
	function exs_get_html_markup_from_template( $template_name, $args = array(), $subfolder = '' ) {
		ob_start();
		get_template_part( 'template-parts/blocks/' . $subfolder . sanitize_title( $template_name ), null, $args );
		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'exs_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function exs_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on this theme, use a find and replace
		 * to change 'exs' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'exs', EXS_THEME_PATH . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1140, 855 );

		if ( ! isset( $content_width ) ) {
			$content_width = 1140;
		}

		//image sizes - cropped
		add_image_size( 'exs-square', 800, 800, true );
		add_image_size( 'exs-square-half', 800, 400, true );

		//Post formats
		add_theme_support( 'post-formats', array( 'video', 'audio', 'image', 'gallery', 'quote' ) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ) );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		$exs_custom_header_logo = array(
			'height'      => 60,
			'width'       => 150,
			'flex-width'  => true,
			'flex-height' => true,
		);

		add_theme_support( 'custom-logo', $exs_custom_header_logo );

		//Background image for header and title sections
		$exs_custom_header_args = array(
			'width'       => 1920,
			'height'      => 800,
			'header-text' => false,
		);
		add_theme_support( 'custom-header', $exs_custom_header_args );

		add_theme_support( 'custom-background' );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Gutenberg block editor
		add_theme_support(
			'editor-color-palette',
			array(
				// colorLight
				// colorFont
				// colorFontMuted
				// colorBackground
				// colorBorder
				// colorDark
				// colorDarkMuted
				// colorMain
				// colorMain2
				array(
					'name'  => esc_html__( 'Light', 'exs' ),
					'slug'  => 'light',
					'color' => 'var(--colorLight)',
				),
				array(
					'name'  => esc_html__( 'Font', 'exs' ),
					'slug'  => 'font',
					'color' => 'var(--colorFont)',
				),
				array(
					'name'  => esc_html__( 'Muted', 'exs' ),
					'slug'  => 'font-muted',
					'color' => 'var(--colorFontMuted)',
				),
				array(
					'name'  => esc_html__( 'Background', 'exs' ),
					'slug'  => 'background',
					'color' => 'var(--colorBackground)',
				),
				array(
					'name'  => esc_html__( 'Border', 'exs' ),
					'slug'  => 'border',
					'color' => 'var(--colorBorder)',
				),
				array(
					'name'  => esc_html__( 'Dark', 'exs' ),
					'slug'  => 'dark',
					'color' => 'var(--colorDark)',
				),
				array(
					'name'  => esc_html__( 'Dark Muted', 'exs' ),
					'slug'  => 'dark-muted',
					'color' => 'var(--colorDarkMuted)',
				),
				array(
					'name'  => esc_html__( 'Accent', 'exs' ),
					'slug'  => 'main',
					'color' => 'var(--colorMain)',
				),
				array(
					'name'  => esc_html__( 'Accent 2', 'exs' ),
					'slug'  => 'main-2',
					'color' => 'var(--colorMain2)',
				),
				array(
					'name'  => esc_html__( 'Accent 3', 'exs' ),
					'slug'  => 'main-3',
					'color' => 'var(--colorMain3)',
				),
				array(
					'name'  => esc_html__( 'Accent 4', 'exs' ),
					'slug'  => 'main-4',
					'color' => 'var(--colorMain4)',
				),
			)
		);
		//custom font sizes
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					/* translators: Font size name */
					'name' => esc_html__( 'Normal', 'exs' ),
					'size' => 18,
					'slug' => 'normal',
				),
				array(
					/* translators: Font size name */
					'name' => esc_html__( 'Medium', 'exs' ),
					'size' => '1.35em',
					'slug' => 'medium',
				),
				array(
					/* translators: Font size name */
					'name' => esc_html__( 'Large', 'exs' ),
					'size' => '1.45em',
					'slug' => 'large',
				),
				array(
					/* translators: Font size name */
					'name' => esc_html__( 'Huge', 'exs' ),
					'size' => '1.65em',
					'slug' => 'huge',
				),
				array(
					/* translators: Font size name */
					'name' => esc_html__( 'XL', 'exs' ),
					'size' => 'calc(1em + 1.5vmin)',
					'slug' => 'xl',
				),
				array(
					/* translators: Font size name */
					'name' => esc_html__( 'XXL', 'exs' ),
					'size' => 'calc(2em + 2vmin)',
					'slug' => 'xxl',
				),
				array(
					/* translators: Font size name */
					'name' => esc_html__( 'XXXL', 'exs' ),
					'size' => 'calc(2.25em + 5vmin)',
					'slug' => 'xxxl',
				),
				array(
					/* translators: Font size name */
					'name' => esc_html__( 'XXXXL', 'exs' ),
					'size' => 'calc(2.25em + 7vmin)',
					'slug' => 'xxxxl',
				),
			)
		);

		// Add support for Block Styles.
		// add_theme_support( 'wp-block-styles' );
		// 'wp-block-library-theme' - loads in the backend even if not defined here

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Enqueue editor styles.
		add_theme_support( 'editor-styles' );
		$min = get_theme_mod( 'assets_min' ) ? 'min/' : '';
		add_editor_style( 'assets/css/' . $min . 'editor-style.css' );

		// Add support for responsive embedded content.
		// It will add JS file to the footer
		// add_theme_support( 'responsive-embeds' );

		//Yoast breadcrumbs support
		add_theme_support( 'yoast-seo-breadcrumbs' );

		//WooCommerce
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		//remove block editor for widgets screen for WP >= 5.8
		if ( ! empty( get_theme_mod( 'remove_widgets_block_editor' ) ) ) {
			remove_theme_support( 'widgets-block-editor' );
		}

		//remove default SVG for duotones WP >= 5.8
		if ( ! empty( get_theme_mod( 'remove_wp_default_duotone_svg' ) ) ) {
			//remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
			remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
		}

		//remove default STYLE tags for .wp-container- auto class WP >= 5.8
		if ( empty( get_theme_mod( 'enable_wp_default_footer_container_styles', '1' ) ) ) {
			remove_action( 'render_block', 'wp_render_layout_support_flag', 10, 2 );
		}


		//starter content
		if ( is_customize_preview() && get_option( 'fresh_site' ) ) {
			require EXS_THEME_PATH . '/inc/starter-content.php';
			add_theme_support( 'starter-content', exs_get_starter_content() );
		}

		// This theme uses wp_nav_menu() in four locations.
		register_nav_menus(
			array(
				'topline'   => esc_html__( 'Topline Menu', 'exs' ),
				'primary'   => esc_html__( 'Main Menu', 'exs' ),
				'copyright' => esc_html__( 'Copyright Menu', 'exs' ),
				'bottom'    => esc_html__( 'Bottom Fixed Menu', 'exs' ),
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'exs_setup' );


if ( ! function_exists( 'exs_widgets_init' ) ) :
	/**
	 * Register widget area.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	function exs_widgets_init() {

		register_sidebar(
			array(
				'name'          => esc_html__( 'Main', 'exs' ),
				'id'            => 'sidebar-1',
				'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'exs' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title"><span>',
				'after_title'   => '</span></h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer', 'exs' ),
				'id'            => 'sidebar-2',
				'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'exs' ),
				'before_widget' => '<div id="%1$s" class="grid-item widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title"><span>',
				'after_title'   => '</span></h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Top Section', 'exs' ),
				'id'            => 'sidebar-3',
				'description'   => esc_html__( 'Add widgets here to appear in your top footer section.', 'exs' ),
				'before_widget' => '<div id="%1$s" class="grid-item widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title"><span>',
				'after_title'   => '</span></h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Header Bottom Section', 'exs' ),
				'id'            => 'sidebar-header-bottom',
				'description'   => esc_html__( 'Add widgets here to appear in your header bottom section.', 'exs' ),
				'before_widget' => '<div id="%1$s" class="grid-item widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title"><span>',
				'after_title'   => '</span></h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Home page above columns', 'exs' ),
				'id'            => 'sidebar-home-before-columns',
				'description'   => esc_html__( 'These widgets will appear on "Home" page above columns.', 'exs' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title"><span>',
				'after_title'   => '</span></h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Home page above content', 'exs' ),
				'id'            => 'sidebar-home-before-content',
				'description'   => esc_html__( 'These widgets will appear on "Home" page above content', 'exs' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title"><span>',
				'after_title'   => '</span></h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Home page main sidebar', 'exs' ),
				'id'            => 'sidebar-home-main',
				'description'   => esc_html__( 'These widgets will appear on "Home" page in main sidebar.', 'exs' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title"><span>',
				'after_title'   => '</span></h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Home page below content', 'exs' ),
				'id'            => 'sidebar-home-after-content',
				'description'   => esc_html__( 'These widgets will appear on "Home" page below main content', 'exs' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title"><span>',
				'after_title'   => '</span></h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Home page below columns', 'exs' ),
				'id'            => 'sidebar-home-after-columns',
				'description'   => esc_html__( 'These widgets will appear on "Home" page below columns', 'exs' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title"><span>',
				'after_title'   => '</span></h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Header Top Line (Layout 5)', 'exs' ),
				'id'            => 'sidebar-topline',
				'description'   => esc_html__( 'Fullwidth widget area. These widgets will appear above header if Header Top line layout #5 is selected in your Customizer. Good for ads banners', 'exs' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title"><span>',
				'after_title'   => '</span></h3>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Side Fixed Sidebar', 'exs' ),
				'id'            => 'sidebar-side-fixed',
				'description'   => esc_html__( 'Fixed Side Sidebar', 'exs' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title"><span>',
				'after_title'   => '</span></h3>',
			)
		);


		//WooCommerce sidebar
		if ( class_exists( 'WooCommerce' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'Shop', 'exs' ),
					'id'            => 'shop',
					'description'   => esc_html__( 'This sidebar will appear on shop pages', 'exs' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-title"><span>',
					'after_title'   => '</span></h3>',
				)
			);
		}
		//EDD single download sidebar
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'Download Sidebar', 'exs' ),
					'id'            => 'sidebar-download',
					/* translators: %s: 'Download' post type label name. */
					'description'   => sprintf( __( 'Add widgets here to appear in your %s sidebar.', 'exs' ), edd_get_label_singular() ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-title"><span>',
					'after_title'   => '</span></h3>',
				)
			);
			register_sidebar(
				array(
					'name'          => esc_html__( 'Downloads Sidebar', 'exs' ),
					'id'            => 'sidebar-downloads',
					/* translators: %s: 'Download' post type label name. */
					'description'   => sprintf( __( 'Add widgets here to appear in your %s sidebar.', 'exs' ), edd_get_label_plural() ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-title"><span>',
					'after_title'   => '</span></h3>',
				)
			);
		}
		//bbPress sidebar
		if ( class_exists( 'bbPress' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'bbPress Theme Sidebar', 'exs' ),
					'id'            => 'sidebar-bbpress',
					'description'   => esc_html__( 'This sidebar will appear on the bbPress pages', 'exs' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-title"><span>',
					'after_title'   => '</span></h3>',
				)
			);
		}
		//BuddyPress sidebar
		if ( class_exists( 'BuddyPress' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'BuddyPress Theme Global Sidebar', 'exs' ),
					'id'            => 'sidebar-buddypress',
					'description'   => esc_html__( 'This sidebar will appear on the BuddyPress pages', 'exs' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-title"><span>',
					'after_title'   => '</span></h3>',
				)
			);
		}
		//WP Job Manager sidebar
		if ( class_exists( 'WP_Job_Manager' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'WP Job Manager Theme Global Sidebar', 'exs' ),
					'id'            => 'sidebar-wpjm',
					'description'   => esc_html__( 'This sidebar will appear on the WP Job Manager pages', 'exs' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-title"><span>',
					'after_title'   => '</span></h3>',
				)
			);
		}

		//Events sidebar
		if ( class_exists( 'Tribe__Events__Main' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'Events Theme Global Sidebar', 'exs' ),
					'id'            => 'sidebar-events',
					'description'   => esc_html__( 'This sidebar will appear on the Events calendar pages', 'exs' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-title"><span>',
					'after_title'   => '</span></h3>',
				)
			);
		}

		//LearnPress
		if ( class_exists( 'LearnPress' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'LearnPress Courses', 'exs' ),
					'id'            => 'sidebar-courses',
					'description'   => esc_html__( 'This sidebar will appear on the LearnPress courses archive pages', 'exs' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-title"><span>',
					'after_title'   => '</span></h3>',
				)
			);
			register_sidebar(
				array(
					'name'          => esc_html__( 'LearnPress Single Course', 'exs' ),
					'id'            => 'sidebar-course',
					'description'   => esc_html__( 'This sidebar will appear on the LearnPress single course page', 'exs' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-title"><span>',
					'after_title'   => '</span></h3>',
				)
			);
		}
	}
endif;
add_action( 'widgets_init', 'exs_widgets_init' );

//copy parent theme mods on first child theme activation
if ( ! function_exists( 'exs_switch_theme_update_mods' ) ) :
	function exs_switch_theme_update_mods( $exs_new_theme ) {

		if ( is_child_theme() ) {
			//only for default child themes
			if (
				'exs-child' === get_stylesheet()
				||
				'exs-child-pro' === get_stylesheet()
			) {
				$exs_new_theme_mods = get_theme_mods();
				//if is child theme and current theme mods are empty - set theme mods from parent theme
				if ( empty( $exs_new_theme_mods ) || 1 === count( $exs_new_theme_mods ) || 2 === count( $exs_new_theme_mods ) ) {
					$exs_mods = get_option( 'theme_mods_' . get_template() );
					if ( ! empty( $exs_mods ) ) {
						foreach ( (array) $exs_mods as $exs_mod => $exs_mod_value ) {
							// if ( 'sidebars_widgets' !== $exs_mod )
							set_theme_mod( $exs_mod, $exs_mod_value );
						}
					}
				}
			}
		}
	}
endif;
add_action( 'after_switch_theme', 'exs_switch_theme_update_mods' );

if ( EXS_WP ) {
	require_once EXS_THEME_PATH . '/inc/wp/admin.php';
}
if ( EXS_TM ) {
	require_once EXS_THEME_PATH . '/inc/tm/admin.php';
}

//theme page
if ( ! function_exists( 'exs_theme_options_page_menu_item' ) ) :
	function exs_theme_options_page_menu_item() {
		add_theme_page(
			esc_html__( 'ExS Theme', 'exs' ),
			esc_html__( 'ExS Theme', 'exs' ),
			'edit_theme_options',
			'exs-theme',
			'exs_theme_options_page'
		);
	}
endif;
add_action( 'admin_menu', 'exs_theme_options_page_menu_item' );

//ajax form processing
if( ! function_exists( 'exs_process_ajax_form' ) ) :
	function exs_process_ajax_form() {

		// check the nonce
		if ( !  check_ajax_referer( 'exs_nonce', 'nonce', false ) ) {
			wp_send_json_error();
		}

		$post_delimiter = '|||';

		//post variables
		$sender_name  = isset( $_POST['name'] ) ? explode( $post_delimiter, sanitize_text_field( $_POST['name'] ) ): array( '' );
		$sender_email = isset( $_POST['email'] ) ? explode( $post_delimiter, sanitize_email( $_POST['email'] ) ) : array( '' );
		$mailchimp    = isset( $_POST['mailchimp'] ) ? explode( $post_delimiter, sanitize_email( $_POST['mailchimp'] ) ) : array();

		if ( empty( $mailchimp ) ) :
			//messages
			$message_success = exs_option( 'contact_message_success' );
			$message_fail    = exs_option( 'contact_message_fail' );
			$subject         = get_option( 'blogname' );
			$message      = isset( $_POST['message'] ) ? explode( $post_delimiter, sanitize_text_field( $_POST['message'] ) ) : array( '' );
			$subject      = isset( $_POST['subject'] ) ? explode( $post_delimiter, sanitize_text_field( $_POST['subject'] ) ) : array( '', $subject );

			$headers = array();
			if ( ! empty( $sender_email ) ) {
				$from_name = ( ! empty( $sender_name[0] ) && ! empty( $sender_name[1] ) ) ? $sender_name[1] : '';
				if ( ! empty( $from_name ) ) {
					$headers[] = 'From: ' . $from_name . ' <' . $sender_email[ count( $sender_email ) - 1 ] . '>';
				} else {
					$headers[] = 'From: ' . $sender_email[ count( $sender_email ) - 1 ];
				}
			}

			$message_text = $message[ count( $message ) - 1 ];
			$subject_text = $subject[ count( $subject ) - 1 ];

			//adding info to message body
			$message_meta = '';

			if ( ! empty( $sender_name[0] ) ) {
				$message_meta .= join( ': ', $sender_name ) . "\r\n";
			}
			if ( ! empty( $sender_email[0] ) ) {
				$message_meta .= join( ': ', $sender_email ) . "\r\n";
			}
			if ( ! empty( $subject[0] ) ) {
				$message_meta .= join( ': ', $subject ) . "\r\n";
			}

			//adding another CUSTOM contact form fields that added by user to email message body
			foreach ( $_POST as $key => $value ) {
				//checking for standard fields
				if (
					$key === 'nonce'
					||
					$key === 'action'
					||
					$key === 'name'
					||
					$key === 'message'
					||
					$key === 'subject'
					||
					$key === 'email'
				) {
					continue;
				}
				//adding key-value pare to email message body
				$message_meta .= sanitize_text_field( ucfirst( $key ) ) . ': ' . sanitize_text_field( $value ) . "\r\n";
			}

			$result = wp_mail( get_option( 'admin_email' ), $subject_text, $message_meta . "\r\n" . $message_text, $headers );

			if ( $result ) {
				wp_send_json_success(
					array(
						'message' => $message_success
					)
				);
			} else {
				wp_send_json_error(
					array(
						'message' => $message_fail
					)
				);
			}
		else: //this is mailchimp
			//messages
			$api_key         = exs_option( 'mailchimp_api_key' );
			$api_audience_id = exs_option( 'mailchimp_audience_id' );
			$message_fail    = exs_option( 'mailchimp_message_fail' );
			$message_success = exs_option( 'mailchimp_message_success' );
			$message_already = exs_option( 'mailchimp_message_already' );

			if (
				//checking email
				empty( $mailchimp[0] )
				||
				empty( $mailchimp[1] )
				||
				filter_var(  $mailchimp[1] , FILTER_VALIDATE_EMAIL) === false
				||
				//checking api
				empty( $api_key )
				||
				//checking audience ID (list ID)
				empty( $api_audience_id )
			) {
				wp_send_json_error(
					array(
						'message' => $message_fail
					)
				);
			}

			// MailChimp API URL
			$memberID = md5( strtolower( $mailchimp[1] ) );
			$dataCenter = substr( $api_key,strpos( $api_key,'-' ) + 1 );
			$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $api_audience_id . '/members/' . $memberID;

			// subscriber name
			$last_name = '';
			$first_name = '';
			if ( ! empty( $sender_name ) && ! empty( $sender_name[1] ) ) {
				$arr = explode(' ', $sender_name[1] );
				if ( count( $arr ) > 1 ) {
					$last_name = array_pop( $arr );
				}
				$first_name = join( ' ', $arr );
			}

			// request body
			$json = json_encode(
				array(
					'email_address' =>  $mailchimp[1],
					'status'        => 'subscribed',
					'merge_fields'  => [
						'FNAME'     => esc_html( $first_name ),
						'LNAME'     => esc_html( $last_name ),
					]
				)
			);

			$args = array(
				'method'      => 'PUT',
				'httpversion' => '1.1',
				'sslverify'   => false,
				'headers'     => array(
					'Authorization' => 'Bearer ' . $api_key,
					'Content-Type' => 'application/json',
				),
				'body'        => $json,
			);

			$answer = wp_remote_post(
				$url,
				$args
			);

			$httpCode = wp_remote_retrieve_response_code( $answer );

			// store the status message based on response code
			if ($httpCode == 200) {
					wp_send_json_success(
						array(
							'message' => $message_success
						)
					);
			} else {
				switch ($httpCode) {
					case 214:
						wp_send_json_error(
							array(
								'message' => $message_already
							)
						);
					case 0:
					default:
						wp_send_json_error(
							array(
								'message' => $message_fail
							)
						);
				}
				return false;
			}
		endif; //mailchimp
	}
endif;
add_action( 'wp_ajax_nopriv_exs_ajax_form', 'exs_process_ajax_form' );
add_action( 'wp_ajax_exs_ajax_form', 'exs_process_ajax_form' );
