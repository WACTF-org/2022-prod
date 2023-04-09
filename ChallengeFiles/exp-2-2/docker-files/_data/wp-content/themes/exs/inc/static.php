<?php
/**
 * Theme static files
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Enqueue scripts and styles.
 */
//front end styles and scripts
if ( ! function_exists( 'exs_enqueue_static' ) ) :
	function exs_enqueue_static() {

		//Remove WP5.8 default global styles as we do not use them. For best performance
		//This will remove 'display:flex' on the `.is-layout-flex` .wp-container class
		//Since 2.0.4 we have added these CSS rules to our main.css file to continue preventing enqueue 14kb of unused CSS code in the HEAD
		wp_dequeue_style( 'global-styles' );

		$min             = exs_option( 'assets_min' ) && ! EXS_DEV_MODE ? 'min/' : '';
		$main_nob        = exs_option( 'assets_main_nob' );
		$nob             = exs_option( 'assets_nob' );
		$menu_breakpoint = exs_option( 'menu_breakpoint', '1200' );
		//default value should be 1200 even if empty in the Customizer
		if ( empty( $menu_breakpoint ) ) {
			$menu_breakpoint = '1200';
		}
		//main theme css file
		if ( ! empty( $main_nob ) ) {
			if ( '1200' === $menu_breakpoint ) {
				wp_enqueue_style( 'exs-style', EXS_THEME_URI . '/assets/css/' . $min . 'main-nob-1200.css', array(), EXS_THEME_VERSION );
			} else {
				wp_enqueue_style( 'exs-style', EXS_THEME_URI . '/assets/css/' . $min . 'main-nob.css', array(), EXS_THEME_VERSION );
			}
		} else {
			if ( '1200' === $menu_breakpoint ) {
				wp_enqueue_style( 'exs-style', EXS_THEME_URI . '/assets/css/' . $min . 'main-1200.css', array(), EXS_THEME_VERSION );
			} else {
				wp_enqueue_style( 'exs-style', EXS_THEME_URI . '/assets/css/' . $min . 'main.css', array(), EXS_THEME_VERSION );
			}
		}

		if ( ! empty( $nob ) ) {
			//deregister block library CSS
			wp_dequeue_style( 'wp-block-library' );
		}

		//menu styles
		if ( '1200' !== $menu_breakpoint ) {
			$menu_breakpoint_mobile = (int) $menu_breakpoint - 1;
			wp_enqueue_style( 'exs-menu-desktop-style', EXS_THEME_URI . '/assets/css/' . $min . 'menu-desktop.css', array( 'exs-style' ), EXS_THEME_VERSION, 'all and (min-width: ' . (int) $menu_breakpoint . 'px)' );
			wp_enqueue_style( 'exs-menu-mobile-style', EXS_THEME_URI . '/assets/css/' . $min . 'menu-mobile.css', array( 'exs-style' ), EXS_THEME_VERSION, 'all and (max-width: ' . $menu_breakpoint_mobile . 'px)' );
		}

		//menu desktop type
		$menu_desktop_type = exs_option( 'menu_desktop', '');
		if ( ! empty( $menu_desktop_type ) ) {
			wp_enqueue_style( 'exs-menu-desktop-type-style', EXS_THEME_URI . '/assets/css/' . $min . 'menu-desktop' . (int) $menu_desktop_type . '.css', array( 'exs-style' ), EXS_THEME_VERSION, 'all and (min-width: ' . (int) $menu_breakpoint . 'px)' );
		} else {
			if ( is_customize_preview() ) {
				wp_enqueue_style( 'exs-menu-desktop-type-style', EXS_THEME_URI . '/assets/css/' . $min . 'menu-desktop0.css', array( 'exs-style' ), EXS_THEME_VERSION, 'all and (min-width: ' . (int) $menu_breakpoint . 'px)' );
			}
		}

		//menu mobile type
		$menu_mobile_type = exs_option( 'menu_mobile', '');
		if ( ! empty( $menu_mobile_type ) ) {
			wp_enqueue_style( 'exs-menu-mobile-type-style', EXS_THEME_URI . '/assets/css/' . $min . 'menu-mobile' . (int) $menu_mobile_type . '.css', array( 'exs-style' ), EXS_THEME_VERSION, 'all and (max-width: ' . (int) ( $menu_breakpoint - 1 ) . 'px)' );
		} else {
			if ( is_customize_preview() ) {
				wp_enqueue_style( 'exs-menu-mobile-type-style', EXS_THEME_URI . '/assets/css/' . $min . 'menu-mobile0.css', array( 'exs-style' ), EXS_THEME_VERSION, 'all and (max-width: ' . (int) ( $menu_breakpoint - 1 ) . 'px)' );
			}
		}

		//bottom fixed menu
		if ( has_nav_menu( 'bottom' ) || exs_option( 'bottom_nav_show_social' ) ) {
			wp_enqueue_style( 'exs-bottom-menu-style', EXS_THEME_URI . '/assets/css/' . $min . 'bottom-menu.css', array(), EXS_THEME_VERSION );
		}

		//side fixed sidebar
		if ( is_active_sidebar( 'sidebar-side-fixed' ) || is_customize_preview() ) {
			$menu_breakpoint = exs_option( 'fixed_sidebar_breakpoint', '1200' );
			//default value should be 1200 even if empty in the Customizer
			$menu_breakpoint = $menu_breakpoint ? $menu_breakpoint : '1200';
			$menu_breakpoint_mobile = (int) $menu_breakpoint - 1;
			//main style
			wp_enqueue_style( 'exs-fixed-sidebar-style', EXS_THEME_URI . '/assets/css/' . $min . 'fixed-sidebar.css', array( 'exs-style' ), EXS_THEME_VERSION );
			//large screen style
			wp_enqueue_style( 'exs-fixed-sidebar-desktop-style', EXS_THEME_URI . '/assets/css/' . $min . 'fixed-sidebar-lg.css', array( 'exs-fixed-sidebar-style' ), EXS_THEME_VERSION, 'all and (min-width: ' . (int) $menu_breakpoint . 'px)' );
			//small screen style
			wp_enqueue_style( 'exs-fixed-sidebar-mobile-style', EXS_THEME_URI . '/assets/css/' . $min . 'fixed-sidebar-sm.css', array( 'exs-fixed-sidebar-style' ), EXS_THEME_VERSION, 'all and (max-width: ' . $menu_breakpoint_mobile . 'px)' );
		}

		//burger type
		$burger_type = exs_option( 'button_burger', '');
		if ( ! empty( $burger_type ) ) {
			wp_enqueue_style( 'exs-burger-type-style', EXS_THEME_URI . '/assets/css/' . $min . 'burger-type' . (int) $burger_type . '.css', array( 'exs-style' ), EXS_THEME_VERSION );
		} else {
			if ( is_customize_preview() ) {
				wp_enqueue_style( 'exs-burger-type-style', EXS_THEME_URI . '/assets/css/' . $min . 'burger-type0.css', array( 'exs-style' ), EXS_THEME_VERSION );
			}
		}

		//pagination type
		$pagination_type = exs_option( 'buttons_pagination', '');
		if ( ! empty( $pagination_type ) ) {
			wp_enqueue_style( 'exs-pagination-type-style', EXS_THEME_URI . '/assets/css/' . $min . 'pagination-type' . (int) $pagination_type . '.css', array( 'exs-style' ), EXS_THEME_VERSION );
		} else {
			if ( is_customize_preview() ) {
				wp_enqueue_style( 'exs-pagination-type-style', EXS_THEME_URI . '/assets/css/' . $min . 'pagination-type0.css', array( 'exs-style' ), EXS_THEME_VERSION );
			}
		}

		//toTop type
		$totop_type = exs_option( 'totop', '');
		if ( ! empty( $totop_type ) && (int) $totop_type > 1 ) {
			wp_enqueue_style( 'exs-totop-type-style', EXS_THEME_URI . '/assets/css/' . $min . 'totop-type' . (int) $totop_type . '.css', array( 'exs-style' ), EXS_THEME_VERSION );
		} else {
			if ( is_customize_preview() ) {
				wp_enqueue_style( 'exs-totop-type-style', EXS_THEME_URI . '/assets/css/' . $min . 'totop-type0.css', array( 'exs-style' ), EXS_THEME_VERSION );
			}
		}

		//search modal type
		$search_type = exs_option( 'search_modal', '');
		if ( ! empty( $search_type ) && (int) $search_type > 1 ) {
			wp_enqueue_style( 'exs-search-type-style', EXS_THEME_URI . '/assets/css/' . $min . 'search-type' . (int) $search_type . '.css', array( 'exs-style' ), EXS_THEME_VERSION );
		} else {
			if ( is_customize_preview() ) {
				wp_enqueue_style( 'exs-search-type-style', EXS_THEME_URI . '/assets/css/' . $min . 'search-type0.css', array( 'exs-style' ), EXS_THEME_VERSION );
			}
		}

		//Woo styles
		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style( 'exs-shop-style', EXS_THEME_URI . '/assets/css/' . $min . 'shop.css', array( 'exs-style' ), EXS_THEME_VERSION );
			$shop_animation = exs_option( 'shop_animation', '');
			if ( ! empty( $shop_animation ) ) {
				wp_enqueue_style( 'exs-shop-animation-style', EXS_THEME_URI . '/assets/css/' . $min . 'shop-animation' . (int) $shop_animation . '.css', array( 'exs-style', 'exs-shop-style' ), EXS_THEME_VERSION );
			}
		}
		//AMP styles
		if ( defined( 'AMP__VERSION' ) ) {
			wp_enqueue_style( 'exs-amp-style', EXS_THEME_URI . '/assets/css/' . $min . 'amp.css', array( 'exs-style' ), EXS_THEME_VERSION );
		}
		//EDD styles
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			wp_enqueue_style( 'exs-edd-style', EXS_THEME_URI . '/assets/css/' . $min . 'edd.css', array( 'exs-style' ), EXS_THEME_VERSION );
		}
		//bbPress styles
		if ( class_exists( 'bbPress' ) ) {
			wp_enqueue_style( 'exs-bbpress-style', EXS_THEME_URI . '/assets/css/' . $min . 'bbpress.css', array( 'exs-style' ), EXS_THEME_VERSION );
		}
		//BuddyPress styles
		if ( class_exists( 'BuddyPress' ) ) {
			wp_enqueue_style( 'exs-buddypress-style', EXS_THEME_URI . '/assets/css/' . $min . 'buddypress.css', array( 'exs-style' ), EXS_THEME_VERSION );
		}
		//WP Job Manager styles
		if ( class_exists( 'WP_Job_Manager' ) ) {
			wp_enqueue_style( 'exs-wpjm-style', EXS_THEME_URI . '/assets/css/' . $min . 'wpjm.css', array( 'exs-style' ), EXS_THEME_VERSION );
		}
		//Simple Job Board styles
		if ( class_exists( 'Simple_Job_Board' ) ) {
			wp_enqueue_style( 'exs-sjb-style', EXS_THEME_URI . '/assets/css/' . $min . 'sjb.css', array( 'exs-style' ), EXS_THEME_VERSION );
		}
		//Ultimate Member styles
		if ( class_exists( 'UM_Functions' ) ) {
			wp_enqueue_style( 'exs-um-style', EXS_THEME_URI . '/assets/css/' . $min . 'um.css', array( 'exs-style' ), EXS_THEME_VERSION );
		}
		//Events Calendar
		if ( class_exists( 'Tribe__Events__Main' ) ) {
			wp_enqueue_style( 'exs-events-calendar-style', EXS_THEME_URI . '/assets/css/' . $min . 'events-calendar.css', array( 'exs-style' ), EXS_THEME_VERSION );
		}
		//LearnPress
		if ( class_exists( 'LearnPress' ) ) {
			if ( version_compare(LEARNPRESS_VERSION, '4.0.0', '>') ) {
				wp_enqueue_style( 'exs-learnpress-style', EXS_THEME_URI . '/assets/css/' . $min . 'learnpress4.css', array( 'exs-style' ), EXS_THEME_VERSION );
			} else {
				wp_enqueue_style( 'exs-learnpress-style', EXS_THEME_URI . '/assets/css/' . $min . 'learnpress.css', array( 'exs-style' ), EXS_THEME_VERSION );
			}
		}

		//views, post and comments likes
		if (
			class_exists( 'Post_Views_Counter' )
			||
			class_exists( 'PLD_Comments_like_dislike' )
			||
			class_exists( 'CLD_Comments_like_dislike' )
		) {
			wp_enqueue_style( 'exs-views-likes-style', EXS_THEME_URI . '/assets/css/' . $min . 'views-likes.css', array( 'exs-style' ), EXS_THEME_VERSION );
		}

		//elementor
		if (
			defined( 'ELEMENTOR_VERSION' )
			||
			defined( 'ELEMENTOR_PRO_VERSION' )
		) {
			wp_enqueue_style( 'exs-elementor-style', EXS_THEME_URI . '/assets/css/' . $min . 'elementor.css', array( 'exs-style' ), EXS_THEME_VERSION );
		}

		//admin-bar styles for front end
		if ( is_admin_bar_showing() ) {
			//Add Frontend admin styles
			wp_enqueue_style(
				'exs-admin-bar-style',
				EXS_THEME_URI . '/assets/css/admin-frontend.css',
				array(),
				EXS_THEME_VERSION
			);
		}

		$min_js = ! EXS_DEV_MODE ? 'min/' : '';
		//main theme script
		wp_enqueue_script( 'exs-init-script', EXS_THEME_URI . '/assets/js/' . $min_js . 'init.js', array(), EXS_THEME_VERSION, true );
		//inverse color
		if ( exs_option( 'colors_inverse_enabled', '' ) || is_customize_preview() ) :
			wp_enqueue_style( 'exs-inverse-style', EXS_THEME_URI . '/assets/css/' . $min . 'inverse.css', array( 'exs-style' ), EXS_THEME_VERSION );
			wp_enqueue_script( 'exs-inverse-script', EXS_THEME_URI . '/assets/js/' . $min_js . 'inverse.js', array(), EXS_THEME_VERSION, true );
			wp_localize_script('exs-inverse-script', 'exsInverse', array(
				'light' => array(
					'colorLight'      => exs_option( 'colorLight' ),
					'colorFont'       => exs_option( 'colorFont' ),
					'colorFontMuted'  => exs_option( 'colorFontMuted' ),
					'colorBackground' => exs_option( 'colorBackground' ),
					'colorBorder'     => exs_option( 'colorBorder' ),
					'colorDark'       => exs_option( 'colorDark' ),
					'colorDarkMuted'  => exs_option( 'colorDarkMuted' ),
				),
				'dark'  => array(
					// 'colorLightInverse' => '#0a0a0a',
					// 'colorFontInverse' => '#d8d8d8',
					// 'colorFontMutedInverse' => '#aaaaaa',
					// 'colorBackgroundInverse' => '#161616',
					// 'colorBorderInverse' => '#3a3a3a',
					// 'colorDarkInverse' => '#dbdbdb',
					// 'colorDarkMutedInverse' => '#ffffff',
					'colorLight'      => exs_option( 'colorLightInverse', '#0a0a0a' ),
					'colorFont'       => exs_option( 'colorFontInverse', '#d8d8d8' ),
					'colorFontMuted'  => exs_option( 'colorFontMutedInverse', '#aaaaaa' ),
					'colorBackground' => exs_option( 'colorBackgroundInverse', '#161616' ),
					'colorBorder'     => exs_option( 'colorBorderInverse', '#3a3a3a' ),
					'colorDark'       => exs_option( 'colorDarkInverse', '#dbdbdb' ),
					'colorDarkMuted'  => exs_option( 'colorDarkMutedInverse', '#ffffff' ),
				),
			));
		endif;
		//read progress - since 1.9.3
		if ( ( is_singular( 'post' ) && exs_option( 'blog_single_read_progress_enabled' ) ) || ( is_singular( 'post' ) && is_customize_preview() ) ) :
			wp_enqueue_script( 'exs-read-progress-script', EXS_THEME_URI . '/assets/js/' . $min_js . 'read-progress.js', array(), EXS_THEME_VERSION, true );
		endif;

		//glightbox gallery
		if ( exs_option( 'assets_lightbox', '' ) ) :
			wp_enqueue_script( 'exs-glightbox-script', EXS_THEME_URI . '/assets/vendors/glightbox/glightbox.min.js', array(), EXS_THEME_VERSION, true );
			wp_enqueue_script( 'exs-glightbox-init-script', EXS_THEME_URI . '/assets/vendors/glightbox/glightbox.init.js', array(), EXS_THEME_VERSION, true );
			wp_enqueue_style( 'exs-glightbox-style', EXS_THEME_URI . '/assets/vendors/glightbox/glightbox.min.css', array(), EXS_THEME_VERSION );
		endif;

		//comments script
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		if ( is_customize_preview() ) {
			wp_enqueue_script( 'exs-customize-preview-script', EXS_THEME_URI . '/assets/js/' . $min_js . 'customize-preview.js', array( 'jquery', 'customize-selective-refresh' ), EXS_THEME_VERSION, true );
		}
	}
endif;
add_action( 'wp_enqueue_scripts', 'exs_enqueue_static' );

//front end styles and scripts
if ( ! function_exists( 'exs_enqueue_static_later' ) ) :
	function exs_enqueue_static_later() {

		wp_register_style( 'exs-style-inline', false );
		wp_enqueue_style( 'exs-style-inline' );

		//inline styles for customizer options - colors and typography
		$exs_colors_string = exs_get_root_colors_inline_styles_string();
		$exs_typography_string = exs_get_typography_inline_styles_string();
		$exs_links_color_string = exs_get_links_color_inline_styles_string();
		if ( ! empty( $exs_colors_string ) || ! empty( $exs_typography_string ) || ! empty( $exs_links_color_string ) ) :
			$exs_styles_string = '';
			if ( ! empty( $exs_colors_string ) ) {
				$exs_styles_string .= ':root{' . $exs_colors_string . '}';
			}
			wp_add_inline_style(
				'exs-style-inline',
				wp_kses(
					$exs_styles_string . $exs_typography_string . $exs_links_color_string,
					false
				)
			);
		endif;


		//AMP later remove scripts
		if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
			wp_dequeue_script( 'exs-init-script' );
			wp_dequeue_script( 'exs-animation-script' );
			wp_dequeue_script( 'exs-glightbox-script' );
			wp_dequeue_script( 'exs-glightbox-init-script' );
		}

	}
endif;
add_action( 'wp_enqueue_scripts', 'exs_enqueue_static_later', 9999 );

//enqueue masonry for grid layout
if ( ! function_exists( 'exs_enqueue_masonry' ) ) :
	function exs_enqueue_masonry() {
		wp_enqueue_script( 'masonry', '', array( 'imagesloaded' ), '', true );
	}
endif;
//enqueue masonry for grid layout action
if ( ! function_exists( 'exs_enqueue_masonry_action' ) ) :
	function exs_enqueue_masonry_action() {
		add_action( 'wp_enqueue_scripts', 'exs_enqueue_masonry' );
	}
endif;

//customizer panel
if ( ! function_exists( 'exs_customizer_js' ) ) :
	function exs_customizer_js() {
		wp_enqueue_style(
			'exs-customizer-style',
			EXS_THEME_URI . '/assets/css/customizer.css',
			array(),
			EXS_THEME_VERSION
		);
		$min = ! EXS_DEV_MODE ? 'min/' : '';
		wp_register_script(
			'exs-customize-controls',
			EXS_THEME_URI . '/assets/js/' . $min . 'customize-controls.js',
			array( 'jquery' ),
			EXS_THEME_VERSION,
			true
		);
		$exs_blog_url = get_post_type_archive_link( 'post' );
		$exs_post     = wp_get_recent_posts(
			array(
				'numberposts' => 1,
				'post_status' => 'publish',
			)
		);
		wp_reset_postdata();
		$exs_post_url   = ( ! empty( $exs_post[0] ) ) ? get_permalink( $exs_post[0]['ID'] ) : $exs_blog_url;
		$exs_search_url = home_url( '/' ) . '?s=';
		$exs_shop_url = esc_html( home_url( '/' ) );
		$exs_checkout_url = $exs_shop_url;
		$exs_product_url = $exs_shop_url;
		if ( class_exists( 'WooCommerce' ) ) {
			$exs_shop_url = wc_get_page_permalink( 'shop' );
			$exs_checkout_url = wc_get_page_permalink( 'checkout' );
			$exs_products = wc_get_products( array( 'limit' => 1 ) );
			if ( ! empty( $exs_products[0] ) ) {
				$exs_product_url = get_permalink( $exs_products[0]->get_id());
			} else {
				$exs_product_url = $exs_shop_url;
			}
		}
		wp_localize_script(
			'exs-customize-controls',
			'exsCustomizerObject',
			array(
				'homeUrl'     => esc_url_raw( home_url() ),
				'blogUrl'     => esc_url_raw( $exs_blog_url ),
				'postUrl'     => esc_url_raw( $exs_post_url ),
				'searchUrl'   => esc_url_raw( $exs_search_url ),
				'shopUrl'     => esc_url_raw( $exs_shop_url ),
				'productUrl'  => esc_url_raw( $exs_product_url ),
				'checkoutUrl' => esc_url_raw( $exs_checkout_url ),
				'themeUrl'    => esc_url_raw( EXS_THEME_URI ),
			)
		);
		wp_enqueue_script( 'exs-customize-controls' );
	}
endif;
add_action( 'customize_controls_enqueue_scripts', 'exs_customizer_js' );

//admin styles
if ( ! function_exists( 'exs_action_load_custom_wp_admin_style' ) ) :
	function exs_action_load_custom_wp_admin_style( $exs_page ) {
		if (
			$exs_page !== 'edit.php'
			&&
			$exs_page !== 'post.php'
			&&
			$exs_page !== 'post-new.php'
			&&
			$exs_page !== 'site-editor.php'
			&&
			$exs_page !== 'appearance_page_pt-one-click-demo-import'
			&&
			$exs_page !== 'appearance_page_one-click-demo-import'
			&&
			$exs_page !== 'appearance_page_advanced-import'
		) {
			return;
		}
		wp_register_style( 'exs-custom-wp-admin-css', EXS_THEME_URI . '/assets/css/admin-backend.css', false, EXS_THEME_VERSION );
		wp_enqueue_style( 'exs-custom-wp-admin-css' );

		//////////
		//colors//
		//////////
		$exs_colors_string = exs_get_root_colors_inline_styles_string();
		if ( ! empty( $exs_colors_string ) ) :
			wp_add_inline_style(
				'exs-custom-wp-admin-css',
				wp_kses(
					':root{' . $exs_colors_string . '}',
					false
				)
			);
		endif;

		//no need to load these styles in the classic editor
		if ( class_exists( 'Classic_Editor' ) ) {
			return;
		}

		//////////////
		//typography//
		//////////////
		$exs_typography_string = '';
		$exs_body_string = '';

		//body
		$exs_body_string .= exs_option( 'typo_body_size' ) ? 'font-size:' . (int) exs_option( 'typo_body_size' ) . 'px;' : '';
		//override from main section
		$exs_main_section_fs = exs_option( 'main_font_size' ) ? 'font-size:' . (int) str_replace( 'fs-', '', exs_option( 'main_font_size' ) ). 'px;' : '';
		$exs_body_string = ! empty( $exs_main_section_fs ) ? $exs_main_section_fs : $exs_body_string;

		$exs_body_string .= exs_option( 'typo_body_weight' ) ? 'font-weight:' . (int) exs_option( 'typo_body_weight' ) . ';' : '';
		$exs_body_string .= exs_option( 'typo_body_line_height' ) ? 'line-height:' . (float) exs_option( 'typo_body_line_height' ) . 'em;' : '';
		$exs_body_string .= exs_option( 'typo_body_letter_spacing' ) ? 'letter-spacing:' . (float) exs_option( 'typo_body_letter_spacing' ) . 'em;' : '';

		if ( $exs_body_string ) {
			$exs_typography_string = '#editor .editor-styles-wrapper{' . $exs_body_string . '}';
		}

		//paragraph
		$exs_typography_string .= ! empty( exs_option( 'typo_p_margin_bottom' ) ) ? '.editor-styles-wrapper p{margin-bottom:' . (float) exs_option( 'typo_p_margin_bottom' ) . 'em;}' : '';

		//headings
		foreach( array( 1, 2, 3, 4, 5, 6 ) as $h ) {
			$h_string = '';
			$h_string .= ! empty( exs_option( 'typo_size_h' . $h ) ) ? 'font-size:' . (float) exs_option( 'typo_size_h' . $h ) . 'em;' : '';
			$h_string .= ! empty( exs_option( 'typo_line_height_h' . $h ) ) ? 'line-height:' . (float) exs_option( 'typo_line_height_h' . $h ) . 'em;' : '';
			$h_string .= ! empty( exs_option( 'typo_letter_spacing_h' . $h ) ) ? 'letter-spacing:' . exs_option( 'typo_letter_spacing_h' . $h ) .'em;' : '';
			$h_string .= ! empty( exs_option( 'typo_weight_h' . $h ) ) ? 'font-weight:' . (int) exs_option( 'typo_weight_h' . $h ) . ';' : '';
			$h_string .= ! empty( exs_option( 'typo_mt_h' . $h ) ) ? 'margin-top:' . (float) exs_option( 'typo_mt_h' . $h ) . 'em;': '';
			$h_string .= ! empty( exs_option( 'typo_mb_h' . $h ) ) ? 'margin-bottom:' . (float) exs_option( 'typo_mb_h' . $h ) . 'em;' : '';

			if ( $h_string ) {
				$exs_typography_string .= '#editor .editor-styles-wrapper h' . $h . '{' . $h_string . '}';
			}
		}

		if ( ! empty( $exs_typography_string ) ) :
			wp_add_inline_style(
				'exs-custom-wp-admin-css',
				wp_kses(
					$exs_typography_string,
					false
				)
			);
		endif;

		/////////
		//fonts//
		/////////
		$exs_font_body     = json_decode( exs_option( 'font_body', '{"font":"","variant": [],"subset":[]}' ) );
		$exs_font_headings = json_decode( exs_option( 'font_headings', '{"font":"","variant": [],"subset":[]}' ) );
		if ( ! empty( $exs_font_body->font ) || ! empty( $exs_font_headings->font ) ) {
			/*
			Translators: If there are characters in your language that are not supported
			by chosen font(s), translate this to 'off'. Do not translate into your own language.
			*/

			if ( 'off' !== esc_html_x( 'on', 'Google font: on or off', 'exs' ) ) {
				$exs_body_subsets  = array();
				$exs_font_body_font = '';
				if (!empty($exs_font_body->font)) {
					$exs_font_body_font = $exs_font_body->font;
					if ( ! empty( $exs_font_body->variant ) ) {
						$exs_font_body->font .= ':'. implode( ',', $exs_font_body->variant );
					}
					$exs_body_subsets  = $exs_font_body->subset;
				}

				$exs_headings_subsets  = array();
				$exs_font_headings_font = '';
				if (!empty($exs_font_headings->font)) {
					$exs_font_headings_font = $exs_font_headings->font;
					if ( ! empty( $exs_font_headings->variant ) ) {
						$exs_font_headings->font .= ':'. implode( ',', $exs_font_headings->variant );
					}
					$exs_headings_subsets  = $exs_font_headings->subset;
				}

				$exs_fonts    = array(
					'body'     => $exs_font_body->font,
					'headings' => $exs_font_headings->font,
				);
				$exs_subsets  = array(
					'body'     => implode(',', $exs_body_subsets),
					'headings' => implode(',', $exs_headings_subsets),
				);
				//'Montserrat|Bowlby One|Quattrocento Sans';
				$exs_fonts_string    = implode('|', array_filter($exs_fonts));
				$exs_subsets_string  = implode(',', array_filter($exs_subsets));

				$exs_query_args = array(
					'family' => urlencode( $exs_fonts_string ),
				);
				if (!empty($exs_subsets_string)) {
					$exs_query_args['subset'] = urlencode( $exs_subsets_string );
				}
				$exs_query_args['display']='swap';
				$exs_font_url = add_query_arg(
					$exs_query_args,
					'https://fonts.googleapis.com/css'
				);

				if ( exs_option( 'fonts_local' ) ) {
					require_once EXS_THEME_PATH . '/inc/exs-webfont-loader.php';
					wp_enqueue_style(
						'exs-backend-google-fonts-style',
						exs_get_webfont_url( esc_url_raw( $exs_font_url ) ),
						array( 'exs-custom-wp-admin-css' ),
						'1.0.0'
					);
				} else {
					wp_enqueue_style( 'exs-backend-google-fonts-style', $exs_font_url, array( 'exs-custom-wp-admin-css' ), '1.0.0' );
				}


				//printing header styles
				$exs_body_style = ( ! empty( $exs_font_body_font ) ) ? '#editor .editor-styles-wrapper,#editor .editor-styles-wrapper button,#editor .editor-styles-wrapper input,#editor .editor-styles-wrapper select,#editor .editor-styles-wrapper textarea{font-family:"' . $exs_font_body_font . '",sans-serif}' : '';

				$exs_headings_style = ( ! empty( $exs_font_headings_font ) ) ? '#editor .editor-styles-wrapper h1,#editor .editor-styles-wrapper h2,#editor .editor-styles-wrapper h3,#editor .editor-styles-wrapper h4,#editor .editor-styles-wrapper h5,#editor .editor-styles-wrapper h6{font-family: "' . $exs_font_headings_font . '",sans-serif}' : '';

				wp_add_inline_style(
					'exs-backend-google-fonts-style',
					wp_kses(
						$exs_body_style . $exs_headings_style,
						false
					)
				);
			}
		}

	} //exs_action_load_custom_wp_admin_style()
endif;
add_action( 'admin_enqueue_scripts', 'exs_action_load_custom_wp_admin_style' );

//Gutenberg script
//https://developer.wordpress.org/block-editor/tutorials/javascript/loading-javascript/
if ( ! function_exists( 'exs_action_enqueue_block_editor_assets' ) ) :
	function exs_action_enqueue_block_editor_assets( $exs_page ) {
		$min = ! EXS_DEV_MODE ? 'min/' : '';
		wp_enqueue_script(
			'exs-gutenberg-script',
			EXS_THEME_URI . '/assets/js/' . $min . 'gutenberg.js',
			array(
				'wp-i18n',
				'wp-blocks',
			),
			EXS_THEME_VERSION
		);

	}
endif;
add_action( 'enqueue_block_editor_assets', 'exs_action_enqueue_block_editor_assets' );

//Gutenberg styles
//https://developer.wordpress.org/block-editor/how-to-guides/javascript/extending-the-block-editor/
//does not work for the iframe editor - https://make.wordpress.org/core/2021/06/29/blocks-in-an-iframed-template-editor/
//because this styles are not for content - only for editor itself:
//https://github.com/WordPress/gutenberg/issues/33212#issuecomment-879290553
//if( ! function_exists( 'exs_action_enqueue_block_editor_styles' ) ) :
//	function exs_action_enqueue_block_editor_styles() {
//		$exs_colors_string = exs_get_root_colors_inline_styles_string();
//		if ( ! empty( $exs_colors_string ) ) :
//			wp_add_inline_style(
//				'wp-edit-blocks',
//				wp_kses(
//					':root{' . $exs_colors_string . '}',
//					false
//				)
//			);
//		endif;
//	}
//endif;
//add_action( 'enqueue_block_assets', 'exs_action_enqueue_block_editor_styles' );

if ( ! function_exists( 'exs_action_enqueue_google_fonts_locally' ) ) :
	function exs_action_enqueue_google_fonts_locally(  ) {
		if ( exs_option( 'fonts_local' ) && wp_style_is( 'exs-google-fonts-style' ) ) :
			wp_dequeue_style( 'exs-google-fonts-style' );

			require_once EXS_THEME_PATH . '/inc/exs-webfont-loader.php';

			$exs_font_body     = json_decode( exs_option( 'font_body', '{"font":"","variant": [],"subset":[]}' ) );
			$exs_font_headings = json_decode( exs_option( 'font_headings', '{"font":"","variant": [],"subset":[]}' ) );
			//TODO subsets can exists even if no font selected
			if ( ! empty( $exs_font_body->font ) || ! empty( $exs_font_headings->font ) ) {
				/*
				Translators: If there are characters in your language that are not supported
				by chosen font(s), translate this to 'off'. Do not translate into your own language.
				*/

				if ( 'off' !== esc_html_x( 'on', 'Google font: on or off', 'exs' ) ) {
					$exs_body_subsets   = array();
					$exs_font_body_font = '';
					if ( ! empty( $exs_font_body->font ) ) {
						$exs_font_body_font = $exs_font_body->font;
						if ( ! empty( $exs_font_body->variant ) ) {
							$exs_font_body->font .= ':' . implode( ',', $exs_font_body->variant );
						}
						$exs_body_subsets = $exs_font_body->subset;
					}

					$exs_headings_subsets   = array();
					$exs_font_headings_font = '';
					if ( ! empty( $exs_font_headings->font ) ) {
						$exs_font_headings_font = $exs_font_headings->font;
						if ( ! empty( $exs_font_headings->variant ) ) {
							$exs_font_headings->font .= ':' . implode( ',', $exs_font_headings->variant );
						}
						$exs_headings_subsets = $exs_font_headings->subset;
					}

					$exs_fonts   = array(
						'body'     => $exs_font_body->font,
						'headings' => $exs_font_headings->font,
					);
					$exs_subsets = array(
						'body'     => implode( ',', $exs_body_subsets ),
						'headings' => implode( ',', $exs_headings_subsets ),
					);
					//'Montserrat|Bowlby One|Quattrocento Sans';
					$exs_fonts_string   = implode( '|', array_filter( $exs_fonts ) );
					$exs_subsets_string = implode( ',', array_filter( $exs_subsets ) );

					$exs_query_args = array(
						'family' => urlencode( $exs_fonts_string ),
					);
					if ( ! empty( $exs_subsets_string ) ) {
						$exs_query_args['subset'] = urlencode( $exs_subsets_string );
					}
					$exs_query_args['display'] = 'swap';
					$exs_font_url              = add_query_arg(
						$exs_query_args,
						'https://fonts.googleapis.com/css'
					);

					//no need to provide a new version for Google fonts link - exs-style added to load it before google fonts style
					wp_enqueue_style(
						'exs-google-fonts-style-local',
						exs_get_webfont_url( esc_url_raw( $exs_font_url ) ),
						array( 'exs-style' ),
						'1.0.0'
					);

					//printing header styles
					$exs_body_style = ( ! empty( $exs_font_body_font ) ) ? 'body,button,input,select,textarea{font-family:"' . $exs_font_body_font . '",sans-serif}' : '';

					$exs_headings_style = ( ! empty( $exs_font_headings_font ) ) ? 'h1,h2,h3,h4,h5,h6{font-family: "' . $exs_font_headings_font . '",sans-serif}' : '';

					wp_add_inline_style(
						'exs-google-fonts-style-local',
						wp_kses(
							$exs_body_style . $exs_headings_style,
							false
						)
					);
				}
			}
		endif;
	}
endif;
add_action( 'wp_enqueue_scripts', 'exs_action_enqueue_google_fonts_locally', 10000 );
