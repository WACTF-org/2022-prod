<?php
/**
 * Template helpers fucntions
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'exs_get_body_schema_itemtype' ) ) :

	/**
	 * Get itemtype value for body tag
	 *
	 * @since 0.0.1
	 */
	function exs_get_body_schema_itemtype() {

		//Get default itemtype
		$exs_itemtype = ( is_page() ) ? 'WebPage' : 'Blog';

		//Change itemtype if is search page
		if ( is_search() ) {
			$exs_itemtype = 'SearchResultsPage';
		}

		//TODO process single post from special category

		return $exs_itemtype;
	}
endif;

//get theme template part
if ( ! function_exists( 'exs_template_part' ) ) :
	function exs_template_part( $exs_template_part_name, $exs_default_value = '1' ) {
		$exs_return = exs_option( $exs_template_part_name, $exs_default_value );

		//for demo
		if ( ! empty( $_GET[ $exs_template_part_name ] ) ) {
			$exs_return = absint( $_GET[ $exs_template_part_name ] );
		}

		return $exs_return;
	}
endif;

//get proper CSS classes for #main section based on page template
if ( ! function_exists( 'exs_get_page_main_section_css_classes' ) ) :
	function exs_get_page_main_section_css_classes() {

		$return = 'container-inherit';

		//container width
		$exs_container_width            = exs_option( 'main_container_width', '1140' );
		$exs_container_post_width       = exs_option( 'blog_single_container_width', '' );
		$exs_container_blog_width       = exs_option( 'blog_container_width', '' );
		$exs_container_search_width     = exs_option( 'search_container_width', '' );
		$exs_container_bbpress_width    = exs_option( 'bbpress_container_width', '' );
		$exs_container_buddypress_width = exs_option( 'buddypress_container_width', '' );
		$exs_container_wpjm_width       = exs_option( 'wpjm_container_width', '' );
		$exs_container_events_width     = is_singular() ? exs_option( 'event_container_width', '' ) : exs_option( 'events_container_width', '' ) ;
		$exs_container_shop_width       = is_singular() ? exs_option( 'product_container_width', '' ) : exs_option( 'shop_container_width', '' ) ;
		if ( exs_is_shop() && ! empty( $exs_container_shop_width ) && ! is_page() ) {
			$exs_container_width = $exs_container_shop_width;
		}
		if ( exs_is_events() && ! empty( $exs_container_events_width ) ) {
			$exs_container_width = $exs_container_events_width;
		}
		if ( exs_is_wpjm() && ! empty( $exs_container_wpjm_width ) ) {
			$exs_container_width = $exs_container_wpjm_width;
		}
		if ( exs_is_buddypress() && ! empty( $exs_container_buddypress_width ) ) {
			$exs_container_width = $exs_container_buddypress_width;
		}
		if ( exs_is_bbpress() && ! empty( $exs_container_bbpress_width ) ) {
			$exs_container_width = $exs_container_bbpress_width;
		}
		if ( is_singular( 'post' ) && ! empty( $exs_container_post_width ) ) {
			$exs_container_width = $exs_container_post_width;
		}
		if ( is_search() && ! empty( $exs_container_search_width ) ) {
			$exs_container_width = $exs_container_search_width;
		}
		if ( ( is_home() || is_category() || is_tag() || is_date() || is_author() ) && ! empty( $exs_container_blog_width ) ) {
			$exs_container_width = $exs_container_blog_width;
		}
		if ( '1400' === $exs_container_width ) {
			$return = 'container-1400';
		}
		if ( '1140' === $exs_container_width ) {
			$return = 'container-1140';
		}
		if ( '960' === $exs_container_width ) {
			$return = 'container-960';
		}
		if ( '720' === $exs_container_width ) {
			$return = 'container-720';
		}

		if ( is_page_template( 'page-templates/no-sidebar-720.php' ) ) {
			$return = 'container-720';
		}
		if ( is_page_template( 'page-templates/no-sidebar-960.php' ) ) {
			$return = 'container-960';
		}
		if ( is_page_template( 'page-templates/no-sidebar-1140.php' ) ) {
			$return = 'container-1140';
		}

		//overflow visible for sticky side meta
		if ( is_singular( 'post' ) && 'meta-side' === exs_option( 'blog_single_layout' ) ) {
			$return .= ' overflow-visible';
		}

		return $return;
	}
endif;

//get proper CSS classes for main column, aside column and body
if ( ! function_exists( 'exs_check_is_page_has_sidebar' ) ) :
	function exs_check_is_page_has_sidebar() {
		return is_page_template( 'page-templates/full-width.php' )
				||
				is_page_template( 'page-templates/empty-page.php' )
				||
				is_page_template( 'page-templates/empty-page-container.php' )
				||
				is_page_template( 'page-templates/no-sidebar-720.php' )
				||
				is_page_template( 'page-templates/no-sidebar-960.php' )
				||
				is_page_template( 'page-templates/no-sidebar-1140.php' )
				||
				is_page_template( 'page-templates/no-sidebar-no-title.php' )
				||
				is_page_template( 'page-templates/header-overlap.php' )
				||
				is_page_template( 'elementor_header_footer' )
				||
				is_page_template( 'elementor_theme' );
	}
endif;
//get proper CSS classes for main column, aside column and body
if ( ! function_exists( 'exs_get_layout_css_classes' ) ) :
	function exs_get_layout_css_classes() {

		//default - sidebar
		$exs_return = array(
			'body'  => 'with-sidebar',
			'main'  => 'column-main',
			'aside' => 'column-aside',
		);

		//check for WooCommerce
		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) :
			if ( is_product() ) {
				$exs_shop_sidebar_position_option = exs_option( 'product_sidebar_position', 'right' );
			}
			if ( is_shop() || is_product_taxonomy() ) {
				$exs_shop_sidebar_position_option = exs_option( 'shop_sidebar_position', 'right' );
			}
			//if empty sidebar or disabled in customizer - removing aside
			if ( ! is_active_sidebar( 'shop' ) || 'no' === $exs_shop_sidebar_position_option ) {
				$exs_return['body']  = 'no-sidebar';
				$exs_return['aside'] = false;

				return $exs_return;
			} //is_active_sidebar( 'shop' )
			//left sidebar
			if ( 'left' === $exs_shop_sidebar_position_option ) {
				$exs_return['body'] .= ' sidebar-left';

				return $exs_return;
				//default - right sidebar
			} else {
				return $exs_return;
			}
		endif; //is_woocommerce()

		//check for EDD
		//only for single product for now
		if ( is_singular( 'download' ) ) {
			return $exs_return;
		}

		//EDD downloads archive
		if ( exs_is_downloads() ) :
			$exs_downloads_sidebar_position_option = exs_option( 'downloads_sidebar_position', '' );
			//if empty sidebar or disabled in customizer - removing aside
			if ( ! is_active_sidebar( 'sidebar-downloads' ) || 'no' === $exs_downloads_sidebar_position_option ) {
				$exs_return['body']  = 'no-sidebar';
				$exs_return['aside'] = false;

				return $exs_return;
			} //is_active_sidebar( 'sidebar-downloads' )

			//left sidebar
			if ( 'left' === $exs_downloads_sidebar_position_option ) {
				$exs_return['body'] .= ' sidebar-left';

				return $exs_return;
				//default - right sidebar
			} else {
				return $exs_return;
			}
		endif; //EDD downloads archive

		//bbPress
		if ( exs_is_bbpress() ) :
			$exs_bbpress_sidebar_position_option = exs_option( 'bbpress_sidebar_position', '' );
			//if empty sidebar or disabled in customizer - removing aside
			if ( ! is_active_sidebar( 'sidebar-bbpress' ) || 'no' === $exs_bbpress_sidebar_position_option ) {
				$exs_return['body']  = 'no-sidebar';
				$exs_return['aside'] = false;

				return $exs_return;
			} //is_active_sidebar( 'sidebar-bbpress' )

			//if is page and one of page templates without sidebar is used:
			//pages
			if ( is_page() ) {

				//no sidebar
				if (
					exs_check_is_page_has_sidebar()
				) {
					$exs_return['body']  = 'no-sidebar';
					$exs_return['aside'] = false;

					return $exs_return;
				}
			}//is_page()

			//left sidebar
			if ( 'left' === $exs_bbpress_sidebar_position_option ) {
				$exs_return['body'] .= ' sidebar-left';

				return $exs_return;
				//default - right sidebar
			} else {
				return $exs_return;
			}
		endif; //bbPress

		//BuddyPress
		if ( exs_is_buddypress() ) :
			$exs_buddypress_sidebar_position_option = exs_option( 'buddypress_sidebar_position', '' );
			//if empty sidebar or disabled in customizer - removing aside
			if ( ! is_active_sidebar( 'sidebar-buddypress' ) || 'no' === $exs_buddypress_sidebar_position_option ) {
				$exs_return['body']  = 'no-sidebar';
				$exs_return['aside'] = false;

				return $exs_return;
			} //is_active_sidebar( 'sidebar-buddypress' )

			//if is page and one of page templates without sidebar is used:
			//pages
			if ( is_page() ) {

				//no sidebar
				if (
					exs_check_is_page_has_sidebar()
				) {
					$exs_return['body']  = 'no-sidebar';
					$exs_return['aside'] = false;

					return $exs_return;
				}
			}//is_page()

			//left sidebar
			if ( 'left' === $exs_buddypress_sidebar_position_option ) {
				$exs_return['body'] .= ' sidebar-left';

				return $exs_return;
				//default - right sidebar
			} else {
				return $exs_return;
			}
		endif; //BuddyPress

		//WP Job Manager
		if ( exs_is_wpjm() ) :
			$exs_wpjm_sidebar_position_option = exs_option( 'wpjm_sidebar_position', '' );
			//if empty sidebar or disabled in customizer - removing aside
			if ( ! is_active_sidebar( 'sidebar-wpjm' ) || 'no' === $exs_wpjm_sidebar_position_option ) {
				$exs_return['body']  = 'no-sidebar';
				$exs_return['aside'] = false;

				return $exs_return;
			} //is_active_sidebar( 'sidebar-wpjm' )

			//if is page and one of page templates without sidebar is used:
			//pages
			if ( is_page() ) {

				//no sidebar
				if (
					exs_check_is_page_has_sidebar()
				) {
					$exs_return['body']  = 'no-sidebar';
					$exs_return['aside'] = false;

					return $exs_return;
				}
			}//is_page()

			//left sidebar
			if ( 'left' === $exs_wpjm_sidebar_position_option ) {
				$exs_return['body'] .= ' sidebar-left';

				return $exs_return;
				//default - right sidebar
			} else {
				return $exs_return;
			}
		endif; //WP Job Manager

		//The Events Calendar
		if ( exs_is_events() ) :
			$exs_events_sidebar_position_option = is_singular() ? exs_option( 'event_sidebar_position', '' ) : exs_option( 'events_sidebar_position', '' );
			//if empty sidebar or disabled in customizer - removing aside
			if ( ! is_active_sidebar( 'sidebar-events' ) || 'no' === $exs_events_sidebar_position_option ) {
				$exs_return['body']  = 'no-sidebar';
				$exs_return['aside'] = false;

				return $exs_return;
			} //is_active_sidebar( 'sidebar-events' )

			//if is page and one of page templates without sidebar is used:
			//pages
			if ( is_page() ) {

				//no sidebar
				if (
					exs_check_is_page_has_sidebar()
				) {
					$exs_return['body']  = 'no-sidebar';
					$exs_return['aside'] = false;

					return $exs_return;
				}
			}//is_page()

			//left sidebar
			if ( 'left' === $exs_events_sidebar_position_option ) {
				$exs_return['body'] .= ' sidebar-left';

				return $exs_return;
				//default - right sidebar
			} else {
				return $exs_return;
			}
		endif; //The Events Calendar

		//LearnPress Archive
		if ( exs_is_learnpress_archive() ) :
			$exs_courses_sidebar_position_option = exs_option( 'courses_sidebar_position', '' );
			//if empty sidebar or disabled in customizer - removing aside
			if ( ! is_active_sidebar( 'sidebar-courses' ) || 'no' === $exs_courses_sidebar_position_option ) {
				$exs_return['body']  = 'no-sidebar';
				$exs_return['aside'] = false;

				return $exs_return;
			} //is_active_sidebar( 'sidebar-courses' )

			//if is page and one of page templates without sidebar is used:
			//pages
			if ( is_page() ) {

				//no sidebar
				if (
				exs_check_is_page_has_sidebar()
				) {
					$exs_return['body']  = 'no-sidebar';
					$exs_return['aside'] = false;

					return $exs_return;
				}
			}//is_page()

			//left sidebar
			if ( 'left' === $exs_courses_sidebar_position_option ) {
				$exs_return['body'] .= ' sidebar-left';

				return $exs_return;
				//default - right sidebar
			} else {
				return $exs_return;
			}
		endif; //exs_is_learnpress_archive

		//LearnPress Course
		if ( exs_is_learnpress_course() ) :
			$exs_course_sidebar_position_option = exs_option( 'course_sidebar_position', '' );
			//if empty sidebar or disabled in customizer - removing aside
			if ( ! is_active_sidebar( 'sidebar-course' ) || 'no' === $exs_course_sidebar_position_option ) {
				$exs_return['body']  = 'no-sidebar';
				$exs_return['aside'] = false;

				return $exs_return;
			} //is_active_sidebar( 'sidebar-course' )

			//if is page and one of page templates without sidebar is used:
			//pages
			if ( is_page() ) {

				//no sidebar
				if (
				exs_check_is_page_has_sidebar()
				) {
					$exs_return['body']  = 'no-sidebar';
					$exs_return['aside'] = false;

					return $exs_return;
				}
			}//is_page()

			//left sidebar
			if ( 'left' === $exs_course_sidebar_position_option ) {
				$exs_return['body'] .= ' sidebar-left';

				return $exs_return;
				//default - right sidebar
			} else {
				return $exs_return;
			}
		endif; //exs_is_learnpress_archive

		//if category has meta - overriding default customizer option option
		if ( is_category() ) {
			$exs_sidebar_position_option = exs_get_category_sidebar_position();
		} else {
			if ( ! is_single() ) {
				if ( is_search() ) {
					$exs_sidebar_position_option = exs_option( 'search_sidebar_position', 'no' );
				} else {
					$exs_sidebar_position_option = exs_option( 'blog_sidebar_position', 'right' );
				}
			} else {
				$exs_sidebar_position_option = exs_option( 'blog_single_sidebar_position', 'right' );
			}
		} //is_category

		if ( ! is_page_template( 'page-templates/home.php' ) ) {
			$sidebar = 'sidebar-1';
			if ( is_front_page() && is_active_sidebar( 'sidebar-home-main' ) ) {
				$sidebar = 'sidebar-home-main';
			}
			//if empty sidebar - removing aside
			if ( ! is_active_sidebar( $sidebar ) ) {
				if ( ! is_singular( 'post' ) || '2' !== exs_option( 'blog_single_toc_after_first_p' ) ) :
					$exs_return['body']  = 'no-sidebar';
					$exs_return['aside'] = false;

					return $exs_return;
				else:
					if ( false === stripos( get_the_content(), '<h'  ) ) {
						$exs_return['body']  = 'no-sidebar';
						$exs_return['aside'] = false;

						return $exs_return;
					}
				endif;
			} //sidebar-1
		} else {
			//if empty sidebar on home.php page template - removing aside
			if ( ! is_active_sidebar( 'sidebar-home-main' ) ) {
				$exs_return['body']  = 'no-sidebar';
				$exs_return['aside'] = false;

				return $exs_return;
			} //sidebar-nome-main
		} //! is_page_template( 'page-templates/home.php'

		//various cases with sidebar
		//single post without sidebars
		if ( is_single() ) {
			//process special post
			$exs_special_category_slug = exs_get_post_special_category_slug();

			//no sidebar for special posts and for posts layouts
			if (
				! empty( $exs_special_category_slug )
				||
				is_page_template( 'page-templates/post-full-width-no-meta-no-thumbnail.php' )
				||
				is_page_template( 'page-templates/post-full-width-no-meta.php' )
				||
				is_page_template( 'page-templates/post-full-width.php' )
			) {
				$exs_return['body']  = 'no-sidebar';
				$exs_return['aside'] = false;

				return $exs_return;
			}
		} //is_single

		//pages
		if ( is_page() ) :

			//no sidebar
			if (
				exs_check_is_page_has_sidebar()
				||
				! is_page_template()
			) {
				$exs_return['body']  = 'no-sidebar';
				$exs_return['aside'] = false;

				return $exs_return;
			}

			//left sidebar for page
			if (
				is_page_template( 'page-templates/sidebar-left.php' )
				||
				( 'left' === $exs_sidebar_position_option )
			) {
				$exs_return['body'] .= ' sidebar-left';

				return $exs_return;
			}

			//right sidebar is default
		endif; // is_page

		//if no sidebar option - removing aside
		if ( 'no' === $exs_sidebar_position_option && ! ( is_page_template( 'page-templates/home.php' ) ) ) {
			$exs_return['body']  = 'no-sidebar';
			$exs_return['aside'] = false;

			return $exs_return;
		}

		//left sidebar
		if ( 'left' === $exs_sidebar_position_option ) {
			$exs_return['body'] .= ' sidebar-left';
		}

		return $exs_return;
	}
endif;

//get category layout based on category meta with global blog option as fallback
if ( ! function_exists( 'exs_get_category_layout' ) ) :
	function exs_get_category_layout( $term_id = '' ) {
		$exs_layout = '';

		$exs_queried_object = get_queried_object();
		$exs_term_id        = $term_id ? $term_id : $exs_queried_object->term_id;

		//if is special category
		$exs_special_cats = exs_get_special_categories_from_options();
		foreach ( $exs_special_cats as $exs_special_cat_name => $exs_special_cat_id ) {
			if ( $exs_term_id === $exs_special_cat_id || cat_is_ancestor_of( $exs_special_cat_id, $exs_term_id ) ) {
				$exs_layout = exs_option( 'category_' . $exs_special_cat_name . '_layout', '' );
				break;
			}
		}

		//if layout is overridden for category in admin panel
		$exs_term_metas_layout = get_term_meta( $exs_term_id, 'layout', true );
		if ( ! empty( $exs_term_metas_layout ) ) {
			$exs_layout = $exs_term_metas_layout;
		}

		//if category layout not specified - getting default layout
		if ( empty( $exs_layout ) ) {
			$exs_layout = exs_option( 'blog_layout', '' ) ? exs_option( 'blog_layout', '' ) : 'default';
		}

		return $exs_layout;
	}
endif;

//get category layout gap based on category meta with global blog option as fallback
if ( ! function_exists( 'exs_get_category_layout_gap' ) ) :
	function exs_get_category_layout_gap() {
		$exs_layout_gap = '';

		$exs_queried_object = get_queried_object();
		$exs_term_id        = $exs_queried_object->term_id;

		//if is special category
		$exs_special_cats = exs_get_special_categories_from_options();
		foreach ( $exs_special_cats as $exs_special_cat_name => $exs_special_cat_id ) {
			if ( $exs_term_id === $exs_special_cat_id || cat_is_ancestor_of( $exs_special_cat_id, $exs_term_id ) ) {
				$exs_layout_gap = exs_option( 'category_' . $exs_special_cat_name . '_layout_gap', '' );
				break;
			}
		}

		//if layout is overridden for category in admin panel
		$exs_term_metas_layout = get_term_meta( $exs_term_id, 'gap', true );
		if ( ! empty( $exs_term_metas_layout ) ) {
			$exs_layout_gap = $exs_term_metas_layout;
		}

		//if category layout not specified - getting default layout
		if ( empty( $exs_layout_gap ) ) {
			$exs_layout_gap = exs_option( 'blog_layout_gap', '' ) ? exs_option( 'blog_layout_gap', '' ) : '';
		}

		return $exs_layout_gap;
	}
endif;

//get feed shot_title
if ( ! function_exists( 'exs_get_feed_shot_title' ) ) :
	function exs_get_feed_shot_title() {
		if ( is_category() ) {
			$exs_show_title = ! exs_option( 'title_show_title', '' );
		} else {
			$exs_show_title = ! exs_option( 'title_show_title', '' ) && ! is_front_page();
		}

		return $exs_show_title;
	}
endif;

//get feed layout
if ( ! function_exists( 'exs_get_feed_layout' ) ) :
	function exs_get_feed_layout() {
		if ( is_category() ) {
			$exs_layout = exs_get_category_layout();
		} else {
			$exs_layout = exs_option( 'blog_layout', '' ) ? exs_option( 'blog_layout', '' ) : 'default';
		}

		//override option for demo purposes
		if ( isset( $_GET['blog_layout'] ) ) {
			$exs_layout_id = absint( $_GET['blog_layout'] );
			$exs_layouts   = array_keys( exs_get_feed_layout_options() );
			$exs_layout    = ! empty( $exs_layouts[ $exs_layout_id ] ) ? $exs_layouts[ $exs_layout_id ] : $exs_layout;
		}

		return $exs_layout;
	}
endif;

//get feed gap
if ( ! function_exists( 'exs_get_feed_gap' ) ) :
	function exs_get_feed_gap() {
		if ( is_category() ) {
			$exs_layout_gap = exs_get_category_layout_gap();
		} else {
			$exs_layout_gap = exs_option( 'blog_layout_gap', '' ) ? exs_option( 'blog_layout_gap', '' ) : '';
		}

		//override option for demo purposes
		if ( isset( $_GET['blog_layout_gap'] ) ) {
			$exs_layout_gap_id = absint( $_GET['blog_layout_gap'] );
			$exs_layout_gaps   = array_keys( exs_get_feed_layout_gap_options() );
			$exs_layout_gap    = ! empty( $exs_layout_gaps[ $exs_layout_gap_id ] ) ? $exs_layout_gaps[ $exs_layout_gap_id ] : $exs_layout_gap;
		}

		return $exs_layout_gap;
	}
endif;

//get category sidebar_position based on category meta with global blog option as fallback
if ( ! function_exists( 'exs_get_category_sidebar_position' ) ) :
	function exs_get_category_sidebar_position() {
		$exs_sidebar_position = '';

		$exs_queried_object = get_queried_object();
		$exs_term_id        = $exs_queried_object->term_id;

		//if is special category
		$exs_special_cats = exs_get_special_categories_from_options();
		foreach ( $exs_special_cats as $exs_special_cat_name => $exs_special_cat_id ) {
			if ( $exs_term_id === $exs_special_cat_id || cat_is_ancestor_of( $exs_special_cat_id, $exs_term_id ) ) {
				$exs_sidebar_position = exs_option( 'category_' . $exs_special_cat_name . '_sidebar_position', 'no' );
				break;
			}
		}

		//term metas from category options has higher priority than customizer option for special categories
		$exs_term_metas = get_term_meta( $exs_term_id, 'sidebar_position', true );
		if ( ! empty( $exs_term_metas ) ) {
			$exs_sidebar_position = $exs_term_metas;
		}

		//if category sidebar_position not specified - getting default sidebar_position
		if ( empty( $exs_sidebar_position ) ) {
			$exs_sidebar_position = exs_option( 'blog_sidebar_position', '' ) ? exs_option( 'blog_sidebar_position', '' ) : 'right';
		}

		return $exs_sidebar_position;
	}
endif;

//get single post layout based on blog post option
if ( ! function_exists( 'exs_get_post_layout' ) ) :
	function exs_get_post_layout() {

		$exs_layout = exs_option( 'blog_single_layout', '' ) ? exs_option( 'blog_single_layout', '' ) : 'default';

		//override option for demo purposes
		if ( isset( $_GET['blog_single_layout'] ) ) {
			$exs_layout_id = absint( $_GET['blog_single_layout'] );
			$exs_layouts   = array_keys( exs_get_post_layout_options() );
			$exs_layout    = ! empty( $exs_layouts[ $exs_layout_id ] ) ? $exs_layouts[ $exs_layout_id ] : $exs_layout;
		}

		return $exs_layout;
	}
endif;

if ( ! function_exists( 'exs_body_classes' ) ) :
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $exs_classes Classes for the body element.
	 *
	 * @return array
	 */
	function exs_body_classes( $exs_classes ) {
		//header-empty
		if ( is_page_template( 'page-templates/empty-page.php' ) ) {
			$exs_classes[] = 'header-empty';
		}

		//header sticky
		if ( has_nav_menu( 'side' ) || is_active_sidebar( 'sidebar-side' ) ) {
			$exs_classes[] = 'has-side-nav';
			$exs_classes[] = exs_option( 'side_nav_position', '' ) ? 'side-nav-right' : 'side-nav-left';
			$exs_classes[] = exs_option( 'side_nav_sticked', '' ) ? 'side-nav-sticked' : '';
			$exs_classes[] = exs_option( 'side_nav_header_overlap', '' ) ? 'side-nav-header-overlap' : '';
		}

		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$exs_classes[] = 'hfeed';
		} else {
			//add 'singular' class for single post or page or any other post type
			$exs_classes[] = 'singular';
		}

		//Adds a sidebar classes
		$exs_css_classes = exs_get_layout_css_classes();

		$exs_classes[] = $exs_css_classes['body'];

		//Add icons in meta classes
		//single post
		if ( is_singular() ) {

			$exs_hide_meta_icons = exs_option( 'blog_single_hide_meta_icons', false );

			//blog loop
		} else {

			$exs_hide_meta_icons = exs_option( 'blog_hide_meta_icons', false );

		}
		if ( $exs_hide_meta_icons ) {
			$exs_classes[] = 'meta-icons-hidden';
		}

		//special category
		if ( is_category() ) {
			$exs_special_category_slug = exs_get_post_special_category_slug();
			if ( ! empty( $exs_special_category_slug ) ) {
				$exs_classes[] = 'special-category';
			}
		}

		//button classes
		//buttons_uppercase
		//buttons_bold
		//buttons_big
		//buttons_colormain
		//buttons_outline
		//buttons_radius
		if ( exs_option( 'buttons_uppercase', false ) ) {
			$exs_classes[] = 'btns-uppercase';
		}
		if ( exs_option( 'buttons_bold', false ) ) {
			$exs_classes[] = 'btns-bold';
		}
		if ( exs_option( 'buttons_big', false ) ) {
			$exs_classes[] = 'btns-big';
		}
		if ( exs_option( 'buttons_colormain', false ) ) {
			$exs_classes[] = 'btns-colormain';
		}
		if ( exs_option( 'buttons_outline', false ) ) {
			$exs_classes[] = 'btns-outline';
		}
		$exs_buttons_radius = exs_option( 'buttons_radius', '' );
		if ( $exs_buttons_radius ) {
			$exs_classes[] = esc_attr( $exs_buttons_radius );
		}

		//meta icons color class
		$exs_meta_icons_color = exs_option( 'color_meta_icons', '' );
		if ( $exs_meta_icons_color ) {
			$exs_classes[] = esc_attr( $exs_meta_icons_color );
		}

		//meta text color class
		$exs_meta_icons_color = exs_option( 'color_meta_text', '' );
		if ( $exs_meta_icons_color ) {
			$exs_classes[] = esc_attr( $exs_meta_icons_color );
		}

		//shop class
		if ( class_exists( 'WooCommerce' ) ) {
			$exs_classes[] = 'woo woocommerce';
		}

		//header class
		if ( 'always-sticky' === exs_option( 'header_sticky', '' ) ) {
			$exs_classes[] = 'header-sticky';
		}
		if ( exs_option( 'header_menu_bold', false ) ) {
			$exs_classes[] = 'menu-bold';
		}
		if ( exs_option( 'header_menu_uppercase', false ) ) {
			$exs_classes[] = 'menu-uppercase';
		}
		if ( exs_option( 'post_thumbnails_fullwidth', false ) ) {
			$exs_classes[] = 'thumbnail-fullwidth';
		}

		//animation enabled
		$exs_animation = exs_option( 'animation_enabled', '' );
		if ( ! empty( $exs_animation ) && ! is_customize_preview() ) {
			$exs_classes[] = 'animation-enabled';
		}

		//title section enabled
		$exs_title = exs_is_title_section_is_shown();
		if ( empty( $exs_title ) ) {
			$exs_classes[] = 'title-hidden';
		}

		return $exs_classes;
	}
endif;
add_filter( 'body_class', 'exs_body_classes' );

//wrap each word in span - for date over featured image
if ( ! function_exists( 'exs_wrap_each_word_in_span' ) ) :
	function exs_wrap_each_word_in_span( $string ) {

		//date dividers temporary replace
		$string = str_replace( '-', '{{-}} ', $string );
		$string = str_replace( '/', '{{/}} ', $string );
		$string = str_replace( ',', '{{,}} ', $string );
		$string = str_replace( '.', '{{.}} ', $string );

		//wrap each word in span
		$array = explode( ' ', $string );
		$return_string = '';
		foreach ( $array as $key => $value ) {
			$return_string .= '<span class="word-span word-' . ( $key + 1 ) . '">' . $value . '</span>' . ' ';
		}

		//wrap date dividers in span
		$return_string = str_replace( '{{-}}</span> ', '<span class="word-divider word-divider-hyphen">-</span></span>', $return_string );
		$return_string = str_replace( '{{/}}</span> ', '<span class="word-divider word-divider-slash">/</span></span>', $return_string );
		$return_string = str_replace( '{{,}}', '<span class="word-divider word-divider-comma">,</span>', $return_string );
		$return_string = str_replace( '{{.}}</span> ', '<span class="word-divider word-divider-dot">.</span></span>', $return_string );
		return $return_string;
	}
endif;


//markup for animated page elements
if ( ! function_exists( 'exs_animated_elements_markup' ) ) :
	function exs_animated_elements_markup() {
		if ( empty( EXS_EXTRA ) ) {
			return;
		}
		$exs_animation = exs_option( 'animation_enabled', '' );
		if ( empty( $exs_animation ) ) {
			return;
		}

		//get animations array from customizer. Keys - selectors
		$exs_animations = array(
			'.column-aside .widget'            => exs_option( 'animation_sidebar_widgets', '' ),
			'.footer-widgets .widget'          => exs_option( 'animation_footer_widgets', '' ),
			'.hfeed article.post'              => exs_option( 'animation_feed_posts', '' ),
			'.hfeed .post .post-thumbnail img' => exs_option( 'animation_feed_posts_thumbnail', '' ),
		);

		$exs_animations = array_filter( $exs_animations );
		if ( ! empty( $exs_animations ) && ! is_customize_preview() ) :
			?>
			data-animate='<?php echo esc_attr( str_replace( '&quot;', '"', json_encode( $exs_animations ) ) ); ?>'
			<?php
		endif;
	}
endif;

//markup for sticky post label
if ( ! function_exists( 'exs_sticky_post_label' ) ) :
	function exs_sticky_post_label() {
		if ( is_sticky() && is_home() && ! is_paged() ) :
			?>
			<span class="icon-inline sticky-post">
			<?php exs_icon( 'pin' ); ?>
			<span><?php echo esc_html_x( 'Featured', 'post', 'exs' ); ?></span>
			</span><!-- .sticky-post -->
			<?php
		endif; //is_sticky()
	}
endif;

//arguments for link pages
if ( ! function_exists( 'exs_get_wp_link_pages_atts' ) ) :
	function exs_get_wp_link_pages_atts() {
		return apply_filters(
			'exs_link_pages_atts',
			array(
				'before'      => '<div class="page-links"><span>' . esc_html__( 'Pages: ', 'exs' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			)
		);
	}
endif;

//arguments for link pages
if ( ! function_exists( 'exs_get_the_posts_pagination_atts' ) ) :
	function exs_get_the_posts_pagination_atts() {
		return array(
			'mid_size'  => 5,
			'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'exs' ) . '</span><span class="icon-inline">' . exs_icon( 'chevron-left', true ) . '</span>',
			'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'exs' ) . '</span><span class="icon-inline">' . exs_icon( 'chevron-right', true ) . '</span>',
		);
	}
endif;

//get top level menu items count
if ( ! function_exists( 'exs_get_menu_top_level_items_count' ) ) :
	function exs_get_menu_top_level_items_count( $exs_menu_name ) {

		$exs_locations   = get_nav_menu_locations();
		$exs_menu_id     = ! empty ( $exs_locations[ $exs_menu_name ] ) ? $exs_locations[ $exs_menu_name ] : false;
		if ( empty( $exs_menu_id ) ) {
			return '-1';
		}
		$exs_menu_object = wp_get_nav_menu_object( $exs_menu_id );
		if ( empty( $exs_menu_object ) ) {
			return '-1';
		}

		$exs_menu_items       = wp_get_nav_menu_items( $exs_menu_object->term_id );
		$exs_menu_items_count = 0;

		foreach ( (array) $exs_menu_items as $exs_key => $exs_menu_item ) {
			if ( '0' === $exs_menu_item->menu_item_parent ) {
				$exs_menu_items_count ++;
			}
		}

		return $exs_menu_items_count;
	}
endif;

//get menu class depending on menu top level items count
if ( ! function_exists( 'exs_get_menu_class_based_on_top_items_count' ) ) :
	function exs_get_menu_class_based_on_top_items_count( $exs_menu_name ) {

		$exs_menu_items_count = exs_get_menu_top_level_items_count( $exs_menu_name );

		if ( '-1' === $exs_menu_items_count || 0 === $exs_menu_items_count ) {
			return 'menu-empty';
		}

		$exs_css_class = 'menu-low-items';

		if ( $exs_menu_items_count > 6 ) {
			$exs_css_class = 'menu-many-items';
		}

		return $exs_css_class;
	}
endif;


//print svg icon
if ( ! function_exists( 'exs_icon' ) ) :
	function exs_icon( $exs_name, $exs_return = false, $exs_container_css_class = 'svg-icon', $exs_style_string = '' ) {
		//in the future we'll add option for this
		$exs_icons_pack = exs_option( 'theme_icons', 'google' );

		if ( $exs_return ) {
			ob_start();
		}

		echo '<span class="' . esc_attr( $exs_container_css_class ) . ' icon-' . esc_attr( $exs_name ) . '">';
		$founded = get_template_part( '/template-parts/svg/' . $exs_icons_pack . '/' . $exs_name, null, array( $exs_style_string ) );
		//load default 'google' template if no template exists in provided icon pack
		if ( false === $founded ) {
			$exs_icons_pack = 'google';
			get_template_part( '/template-parts/svg/' . $exs_icons_pack . '/' . $exs_name, null, array( $exs_style_string ) );
		}
		echo '</span>';

		if ( $exs_return ) {
			return ob_get_clean();
		}
	}
endif;

//print social link
if ( ! function_exists( 'exs_social_link' ) ) :
	function exs_social_link( $exs_name, $exs_url, $exs_style_string='' ) {
		echo '<a href="' . esc_url( $exs_url ) . '" class="social-icon social-icon-' . esc_attr( $exs_name ) . '">';
		exs_icon( $exs_name, false, 'svg-icon', $exs_style_string );
		echo '<span class="screen-reader-text">' . esc_html( $exs_name ) . '</span>';
		echo '</a>';
	}
endif;

//meta
//get meta array
if ( ! function_exists( 'exs_get_theme_meta' ) ) :
	function exs_get_theme_meta( $exs_meta_names = array() ) {
		/*
		customizer options with meta are:
			'meta_email'
			'meta_email_label'
			'meta_phone'
			'meta_phone_label'
			'meta_address'
			'meta_address_label'
			'meta_opening_hours'
			'meta_opening_hours_label'
		*/

		//if no names specified - using all meta
		if ( empty( $exs_meta_names ) ) :
			$exs_meta_names = array(
				'email',
				'phone',
				'address',
				'opening_hours',
			);
		endif;

		$exs_theme_meta = array();

		//meta values
		foreach ( $exs_meta_names as $exs_meta_name ) {
			$exs_value = exs_option( 'meta_' . $exs_meta_name );
			if ( ! empty( $exs_value ) ) {
				$exs_theme_meta[ $exs_meta_name ] = $exs_value;
			}
		}

		//labels for meta if it is not empty
		if ( ! empty( $exs_theme_meta ) ) {
			foreach ( $exs_theme_meta as $exs_meta_name => $exs_meta_value ) {
				$exs_label = exs_option( 'meta_' . $exs_meta_name . '_label' );
				if ( ! empty( $exs_label ) ) {
					$exs_theme_meta[ $exs_meta_name . '_label' ] = $exs_label;
				}
			}
		}

		return $exs_theme_meta;
	}
endif;

//print all social links based on theme_meta from Customizer
if ( ! function_exists( 'exs_social_links' ) ) :
	function exs_social_links() {

		$exs_facebook  = exs_option( 'meta_facebook' );
		$exs_twitter   = exs_option( 'meta_twitter' );
		$exs_youtube   = exs_option( 'meta_youtube' );
		$exs_instagram = exs_option( 'meta_instagram' );
		$exs_pinterest = exs_option( 'meta_pinterest' );
		$exs_linkedin  = exs_option( 'meta_linkedin' );
		$exs_github    = exs_option( 'meta_github' );
		$exs_tiktok    = exs_option( 'meta_tiktok' );

		if (
			! empty( $exs_facebook )
			||
			! empty( $exs_twitter )
			||
			! empty( $exs_youtube )
			||
			! empty( $exs_instagram )
			||
			! empty( $exs_pinterest )
			||
			! empty( $exs_linkedin )
			||
			! empty( $exs_github )
			||
			! empty( $exs_tiktok )
		) :
			$exs_social_style_string = '';
			$exs_social_buttons_social = exs_option( 'buttons_social', '' );

			switch ( $exs_social_buttons_social ):
				case( '1' ):
					$exs_social_style_string = 'fill:var(--c-';
					break;

				case( '2' ):
					$exs_social_style_string = 'width:40px;height:40px;max-width:40px;max-height:40px;padding:8px;fill:#fff;background:var(--c-';
					break;

				case( '3' ):
					$exs_social_style_string = 'border-radius:6px;width:40px;height:40px;max-width:40px;max-height:40px;padding:8px;fill:#fff;background:var(--c-';
					break;

				case( '4' ):
					$exs_social_style_string = 'border-radius:50%;width:40px;height:40px;max-width:40px;max-height:40px;padding:8px;fill:#fff;background:var(--c-';
					break;
			endswitch;

			echo '<span class="social-links">';

			if ( ! empty( $exs_facebook ) ) :
				exs_social_link( 'facebook', $exs_facebook, ! empty( $exs_social_style_string ) ? $exs_social_style_string . 'facebook)' : '' );
			endif;

			if ( ! empty( $exs_twitter ) ) :
				exs_social_link( 'twitter', $exs_twitter, ! empty( $exs_social_style_string ) ? $exs_social_style_string . 'twitter)' : '' );
			endif;

			if ( ! empty( $exs_youtube ) ) :
				exs_social_link( 'youtube', $exs_youtube, ! empty( $exs_social_style_string ) ? $exs_social_style_string . 'youtube)' : '' );
			endif;

			if ( ! empty( $exs_instagram ) ) :
				exs_social_link( 'instagram', $exs_instagram, ! empty( $exs_social_style_string ) ? $exs_social_style_string . 'instagram)' : '' );
			endif;

			if ( ! empty( $exs_pinterest ) ) :
				exs_social_link( 'pinterest', $exs_pinterest, ! empty( $exs_social_style_string ) ? $exs_social_style_string . 'pinterest)' : '' );
			endif;

			if ( ! empty( $exs_linkedin ) ) :
				exs_social_link( 'linkedin', $exs_linkedin, ! empty( $exs_social_style_string ) ? $exs_social_style_string . 'linkedin)' : '' );
			endif;

			if ( ! empty( $exs_github ) ) :
				exs_social_link( 'github-circle', $exs_github, ! empty( $exs_social_style_string ) ? $exs_social_style_string . 'github)' : '' );
			endif;
			if ( ! empty( $exs_tiktok ) ) :
				exs_social_link( 'tiktok', $exs_tiktok, ! empty( $exs_social_style_string ) ? $exs_social_style_string . 'tiktok)' : '' );
			endif;

			echo '</span><!--.social-links-->';

		endif;
	}
endif;

//get html for all social links based on theme_meta from Customizer
if ( ! function_exists( 'exs_social_links_html' ) ) :
	function exs_social_links_html() {
		ob_start();
		exs_social_links();
		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'exs_post_thumbnail_file_exists' ) ) :
	/**
	 * Check if post thumbnail file exists and thumbnail not loading from the external source
	 */
	function exs_post_thumbnail_file_exists( $exs_id = false ) {
		if ( empty( $exs_id ) ) {
			$exs_id = get_the_ID();
		}
		$file = get_attached_file( get_post_thumbnail_id( $exs_id ) );
		//if is  url - return exists
		if ( strpos( $file, 'http' ) === 0  ) {
			return true;
		}
		return file_exists( $file );
	}
endif;

if ( ! function_exists( 'exs_has_post_thumbnail' ) ) :
	/**
	 * Check if has post thumbnail and thumbnail file exists
	 */
	function exs_has_post_thumbnail( $exs_id = false ) {
		if ( empty( $exs_id ) ) {
			$exs_id = get_the_ID();
		}

		return ! (
			post_password_required( $exs_id )
			||
			is_attachment()
			||
			! has_post_thumbnail( $exs_id )
			//this line will prevent loads featured images from the external resources
			||
			! exs_post_thumbnail_file_exists( $exs_id )
		);
	}
endif;

if ( ! function_exists( 'exs_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function exs_post_thumbnail( $exs_size = 'post-thumbnail', $exs_css_class = '', $title_section = false ) {

		if (
		! exs_has_post_thumbnail()
		) {
			return;
		}

		$post_thumbnails_centered = ! empty( exs_option( 'post_thumbnails_centered', '' ) ) ? 'text-center' : '';

		if ( is_singular() ) :
				//since 2.0.4 - displaying featured image in the 'title-6' section layout
				//if it was shown in the title section - no double print it in the content
				static $shown = 0;
				if ( $shown ) {
					return;
				}
				if ( $title_section ) {
					$shown++;
				}

				$full_width_featured = exs_option( 'blog_single_fullwidth_featured' );
				if  ( ! empty( $full_width_featured ) ) {
					$exs_css_class .= ' alignfull';
				}
				$oembed_url            = false;
				$oembed_post_thumbnail = false;
				$enable_oembed_for_thumbnail_option = exs_option( 'blog_single_first_embed_featured', '' );
				//only video post format
				if ( ( 'video' === get_post_format() ) && ( 'video' === $enable_oembed_for_thumbnail_option ) ) {
					$oembed_post_thumbnail = true;
				}
				//all posts
				if ( 'all' === $enable_oembed_for_thumbnail_option ) {
					$oembed_post_thumbnail = true;
				}

				if ( is_single() && $oembed_post_thumbnail ) {
					$post_content = get_the_content();
					//get oEmbed URL
					$reg = preg_match('|^\s*(https?://[^\s"]+)\s*$|im', $post_content, $matches );

					$oembed_url = ! empty( $reg ) ? trim( $matches[0] ) : false;
					//if no youtube, trying to find self hosted

					$first_self_hosted = '';
					$embeds = array();
					if ( empty( $oembed_url ) ) {
						$post_content = apply_filters( 'the_content', $post_content );
						$embeds = get_media_embedded_in_content( $post_content );
					}
				}

				if ( $enable_oembed_for_thumbnail_option && ( $oembed_url || ! empty( $embeds[0] ) ) ) :
					//if youtube
					if( $oembed_url ) :
						add_filter( 'the_content', function ( $content ) use ( $oembed_url ) {
							//remove embed
							$content = str_replace( $oembed_url, '', $content );
							//hide embed wrapper
							$pos = strpos( $content, 'class="wp-block-embed' );
							if ($pos !== false) {
								$content = substr_replace( $content, 'class="d-none wp-block-embed', $pos, strlen( 'class="wp-block-embed' ) );
							}
							return $content;
							//1 - to run early
						}, 1 );
					?>
					<figure class="wp-block-embed wp-embed-aspect-16-9 post-thumbnail mb-0 <?php echo esc_attr( $exs_css_class . ' ' . $post_thumbnails_centered ); ?>">
						<div class="wp-block-embed__wrapper" itemprop="video" itemscope="itemscope" itemtype="https://schema.org/VideoObject">
						<?php
							if ( defined( 'AMP__VERSION' ) ) :
								$amp_youtube_oembed = new AMP_YouTube_Embed_Handler( array() );
								$amp_embed_html = wp_oembed_get( $oembed_url );
								echo wp_kses(
									$amp_youtube_oembed->filter_embed_oembed_html( $amp_embed_html, $oembed_url ),
									array(
										'amp-youtube' => array(
											'data-videoid' => true,
											'layout' => true,
											'title' => true,
											'width' => true,
											'height' => true,
											'class' => true,
										),
										'amp-img' => array(
											'src' => true,
											'placeholder' => true,
											'layout' => true,
										)
									)
								);
							else:
								echo wp_oembed_get( $oembed_url );
							endif;
							$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
							if ( $thumbnail_url ) :
						?>
							<meta itemprop="thumbnailUrl" content="<?php echo esc_url( $thumbnail_url ); ?>">
							<?php
							endif; //thumbnail_url
						?>
							<meta itemprop="uploadDate" content="<?php echo esc_attr( the_time( get_option( 'date_format' ) ) ); ?>">
							<meta itemprop="contentUrl" content="<?php echo esc_url( $oembed_url ); ?>">
							<?php
							the_title( '<h3 class="d-none" itemprop="name">', '</h3>' );
							?>
							<p class="d-none" itemprop="description">
								<?php echo wp_kses( get_the_excerpt(), false ); ?>
							</p>
						</div>
					</figure><!-- .post-thumbnail -->
				<?php
					//self hosted
					else:
						$embed = ( ! empty( $embeds[0] ) ) ? $embeds[0] : false;
						$url = preg_match( '`src="(.*)"`', $embed, $founds );
						$hosted_video_url = ! empty( $founds['1'] ) ? $founds['1'] : '';
						add_filter( 'the_content', function ( $content ) use ( $embed ) {
							//remove embed
							$content = str_replace( $embed, '', $content );
							//hide embed wrapper
							$pos = strpos( $content, 'class="wp-block-video');
							if ( $pos !== false ) {
								$content = substr_replace( $content, 'class="d-none wp-block-video', $pos, strlen( 'class="wp-block-embed' ) );
							}
							return $content;
							//1 - to run early
						}, 1 );
					?>
					<figure class="post-thumbnail mb-0 <?php echo esc_attr( $exs_css_class . ' ' . $post_thumbnails_centered ); ?>">
						<div class="wp-block-video" itemprop="video" itemscope="itemscope" itemtype="https://schema.org/VideoObject">
						<?php
							echo wp_kses_post( $embed );
							$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
						if ( $thumbnail_url ) :
							?>
							<meta itemprop="thumbnailUrl" content="<?php echo esc_url( $thumbnail_url ); ?>">
						<?php
						endif; //thumbnail_url
						?>
							<meta itemprop="uploadDate" content="<?php echo esc_attr( the_time( get_option( 'date_format' ) ) ); ?>">
							<meta itemprop="contentUrl" content="<?php echo esc_url( $hosted_video_url ); ?>">
							<?php
							the_title( '<h3 class="d-none" itemprop="name">', '</h3>' );
							?>
							<p class="d-none" itemprop="description">
								<?php echo wp_kses( get_the_excerpt(), false ); ?>
							</p>
						</div>
					</figure><!-- .post-thumbnail -->
					<?php
					endif; //$oembed_url
				else:
					$show_date = exs_option( 'blog_single_show_date_over_image' );
					$show_cats = exs_option( 'blog_single_show_categories_over_image' );
					$image_size_value = exs_option( 'blog_single_featured_image_size', '' );
					$image_size = $image_size_value ? $image_size_value : $exs_size;
				?>
				<figure class="<?php echo esc_attr( 'post-thumbnail ' . $exs_css_class . ' ' . $post_thumbnails_centered ); ?>">
					<?php
					the_post_thumbnail(
						$image_size,
						array(
							'itemprop' => 'image',
							'alt'      => esc_attr( get_the_title() ),
						)
					);
					if ( $show_date ) :
						?>
						<span class="featured-image-date <?php echo esc_attr( $show_date ); ?>" itemprop="datePublished">
							<?php echo wp_kses(
								exs_wrap_each_word_in_span(
										get_the_time( get_option( 'date_format' ) )
								),
								array( 'span' => array( 'class' => true ) )
							); ?>
						</span>
					<?php
					endif; //show_cate

					if ( $show_cats ) :
						?>
						<span class="featured-image-categories <?php echo esc_attr( $show_cats ); ?>">
						<?php
							echo wp_kses_post( get_the_category_list( '<span class="featured-image-categories-separator"> </span>' ) );
						?>
					</span><!--.categories-list-->
					<?php endif; //show_cats ?>
				</figure><!-- .post-thumbnail -->
				<?php
			endif; //oembed_url
			//not is_singular
		else :

			$show_date = is_search() ? exs_option( 'search_show_date_over_image' ) : exs_option( 'blog_show_date_over_image' );
			$show_cats = is_search() ? exs_option( 'search_show_categories_over_image' ) : exs_option( 'blog_show_categories_over_image' );
			$image_size_value = is_search() ? exs_option( 'search_featured_image_size', '' ) : exs_option( 'blog_featured_image_size', '' );
			$image_size = $image_size_value ? $image_size_value : $exs_size;
			?>
			<figure class="<?php echo esc_attr( 'post-thumbnail ' . $exs_css_class . ' ' . $post_thumbnails_centered ); ?>">
				<a class="post-thumbnail-inner" href="<?php the_permalink(); ?>">
					<?php
					the_post_thumbnail(
						$image_size,
						array(
							'itemprop' => 'image',
							'alt'      => esc_attr( get_the_title() ),
						)
					);
					exs_post_format_icon( get_post_format() );
					?>
				</a>
				<?php
				if ( $show_date ) :
					?>
					<a class="featured-image-date <?php echo esc_attr( $show_date ); ?>" href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" itemprop="mainEntityOfPage">
							<?php echo wp_kses(
								exs_wrap_each_word_in_span(
									get_the_time( get_option( 'date_format' ) )
								),
								array( 'span' => array( 'class' => true ) )
							); ?>
						</a>
					<?php
				endif; //show_date

				if ( $show_cats ) :
					?>
					<span class="featured-image-categories <?php echo esc_attr( $show_cats ); ?>">
					<?php
						echo wp_kses_post( get_the_category_list( '<span class="featured-image-categories-separator"> </span>' ) );
					?>
				</span><!--.categories-list-->
				<?php endif; //show_cats ?>
			</figure>
			<?php
		endif; // End is_singular().
	}
endif; //exs_post_thumbnail

//print post format icon
if ( ! function_exists( 'exs_post_format_icon' ) ) :
	function exs_post_format_icon( $exs_post_format = '' ) {
		// 'video', 'audio', 'image', 'gallery', 'quote'
		switch ( $exs_post_format ) :
			case 'video':
				exs_icon( 'video' );
				break;
			case 'audio':
				exs_icon( 'volume-high' );
				break;
			case 'image':
				exs_icon( 'image' );
				break;
			case 'gallery':
				exs_icon( 'camera' );
				break;
			case 'quote':
				exs_icon( 'format-quote-close' );
				break;

			default:
		endswitch;
	}
endif;


if ( ! function_exists( 'exs_the_author' ) ) :
	/**
	 * Prints author HTML with with link on author archive.
	 */
	function exs_the_author( $title_section_options = false ) {

		$css_class = '';

		//options
		//single post
		if ( is_singular() ) {

			$prefix = ! empty( $title_section_options ) ? 'title_' : '';
			$exs_show_author   = exs_option( $prefix . 'blog_single_show_author', true );
			$exs_author_avatar = exs_option( $prefix . 'blog_single_show_author_avatar', '' );
			$exs_author_word   = exs_option( $prefix . 'blog_single_before_author_word', '' );
			$exs_show_icons    = ! exs_option( $prefix . 'blog_single_hide_meta_icons', false );

			//new options since 1.9.5
			$css_class .= exs_option( 'blog_single_meta_bold' ) ? ' fw-700' : '';
			$css_class .= exs_option( 'blog_single_meta_uppercase' ) ? ' text-uppercase' : '';
			$css_class .= exs_option( 'blog_single_meta_font_size' ) ? ' fs-' . (int) exs_option( 'blog_single_meta_font_size' ) : '';

			//blog loop
		} else {

			if ( is_search() ) {
				$exs_show_author   = exs_option( 'search_show_author', true );
				$exs_author_avatar = exs_option( 'search_show_author_avatar', '' );
				$exs_author_word   = exs_option( 'search_before_author_word', '' );
				$exs_show_icons    = ! exs_option( 'search_hide_meta_icons', false );

				//new options since 1.9.5
				$css_class .= exs_option( 'search_meta_bold' ) ? ' fw-700' : '';
				$css_class .= exs_option( 'search_meta_uppercase' ) ? ' text-uppercase' : '';
				$css_class .= exs_option( 'search_meta_font_size' ) ? ' fs-' . (int) exs_option( 'search_meta_font_size' ) : '';
			} else {
				$exs_show_author   = exs_option( 'blog_show_author', true );
				$exs_author_avatar = exs_option( 'blog_show_author_avatar', '' );
				$exs_author_word   = exs_option( 'blog_before_author_word', '' );
				$exs_show_icons    = ! exs_option( 'blog_hide_meta_icons', false );

				//new options since 1.9.5
				$css_class .= exs_option( 'blog_meta_bold' ) ? ' fw-700' : '';
				$css_class .= exs_option( 'blog_meta_uppercase' ) ? ' text-uppercase' : '';
				$css_class .= exs_option( 'blog_meta_font_size' ) ? ' fs-' . (int) exs_option( 'blog_meta_font_size' ) : '';
			}
		}

		if ( ! empty( $exs_show_author ) ) :
			//author-wrapper
			if ( ! empty( $exs_author_avatar ) ) :
				echo '<span class="author-avatar">';
				$exs_author_id        = get_the_author_meta( 'ID' );
				$exs_custom_image_url = get_the_author_meta( 'custom_profile_image', $exs_author_id );
				if ( ! empty( $exs_custom_image_url ) ) {
					echo '<img src="' . esc_url( $exs_custom_image_url ) . '" alt="' . esc_attr( get_the_author_meta( 'display_name', $exs_author_id ) ) . '">';
				} else {
					echo get_avatar( $exs_author_id, 100 );
				}
				echo '</span><!-- .author-avatar-->';
			endif; //$exs_author_avatar
			?>
			<span class="entry-author-wrap icon-inline <?php echo esc_attr( $css_class ); ?>">
			<?php
			//icon
			if ( ( ! empty( $exs_show_icons ) ) && empty( $exs_author_avatar ) ) {
				exs_icon( 'account-outline' );
			}

			//word
			if ( ! empty( $exs_author_word ) ) :
				?>
				<span class="entry-author-word meta-word">
					<?php echo esc_html( $exs_author_word ); ?>
				</span><!--.entry-author-word-->
				<?php
			endif;
			//value
			?>
				<span class="vcard author" itemtype="https://schema.org/Person" itemscope="itemscope" itemprop="author">
				<?php
				//in the loop
				if ( in_the_loop() ) :
					the_author_posts_link();
				//in the title
				else:
					global $post;
					$author_id = $post->post_author;
					echo '<a href="' . esc_url( get_author_posts_url( $author_id ) ) . '" rel="author" itemprop="url"><span itemprop="name">';
					$user = get_userdata( $author_id );
					echo esc_html( $user->user_nicename );
					echo '</span></a>';
				endif;
				?>
				</span><!-- .author -->
			</span><!--.entry-author-wrap-->
			<?php
			do_action( 'exs_action_print_publisher' );
		endif; //author
	}
endif; //exs_the_author

add_filter( 'the_author_posts_link', 'exs_the_author_link_itemprop' );
if ( ! function_exists( 'exs_the_author_link_itemprop' ) ) :
	/**
	 * Add 'itemprop' attribute to the author link.
	 */
	function exs_the_author_link_itemprop( $exs_link ) {
		$exs_link = str_replace( 'rel="author">', 'rel="author" itemprop="url"><span itemprop="name">', $exs_link );
		$exs_link = str_replace( '</a>', '</span></a>', $exs_link );

		return $exs_link;
	}
endif;

if ( ! function_exists( 'exs_the_date' ) ) :
	/**
	 * Prints date HTML with the post link on blog.
	 */
	function exs_the_date( $exs_human_diff = null, $title_section_options = false ) {

		$css_class = '';

		//options
		//single post
		if ( is_singular() ) {
			$prefix = ! empty( $title_section_options ) ? 'title_' : '';

			$exs_show_date  = exs_option( $prefix . 'blog_single_show_date', true );
			$exs_date_word  = exs_option( $prefix . 'blog_single_before_date_word', '' );
			$exs_date_diff  = exs_option( $prefix . 'blog_single_show_human_date', '' );
			$exs_show_icons = ! exs_option( $prefix . 'blog_single_hide_meta_icons', false );

			//new options since 1.9.5
			$css_class .= exs_option( 'blog_single_meta_bold' ) ? ' fw-700' : '';
			$css_class .= exs_option( 'blog_single_meta_uppercase' ) ? ' text-uppercase' : '';
			$css_class .= exs_option( 'blog_single_meta_font_size' ) ? ' fs-' . (int) exs_option( 'blog_single_meta_font_size' ) : '';

			//new date modified since 1.9.9
			$exs_date_type         = exs_option( $prefix . 'blog_single_show_date_type', 'publish' );
			$exs_date_modify_word  = exs_option( $prefix . 'blog_single_before_date_modify_word', '' );

			//blog loop
		} else {

			if ( is_search() ) {
				$exs_show_date  = exs_option( 'search_show_date', true );
				$exs_date_word  = exs_option( 'search_before_date_word', '' );
				$exs_date_diff  = exs_option( 'search_show_human_date', '' );
				$exs_show_icons = ! exs_option( 'search_hide_meta_icons', false );

				//new options since 1.9.5
				$css_class .= exs_option( 'search_meta_bold' ) ? ' fw-700' : '';
				$css_class .= exs_option( 'search_meta_uppercase' ) ? ' text-uppercase' : '';
				$css_class .= exs_option( 'search_meta_font_size' ) ? ' fs-' . (int) exs_option( 'search_meta_font_size' ) : '';

				//new date modified since 1.9.9
				$exs_date_type         = exs_option( 'search_show_date_type', 'publish' );
				$exs_date_modify_word  = exs_option( 'search_before_date_modify_word', '' );
			} else {
				$exs_show_date  = exs_option( 'blog_show_date', true );
				$exs_date_word  = exs_option( 'blog_before_date_word', '' );
				$exs_date_diff  = exs_option( 'blog_show_human_date', '' );
				$exs_show_icons = ! exs_option( 'blog_hide_meta_icons', false );

				//new options since 1.9.5
				$css_class .= exs_option( 'blog_meta_bold' ) ? ' fw-700' : '';
				$css_class .= exs_option( 'blog_meta_uppercase' ) ? ' text-uppercase' : '';
				$css_class .= exs_option( 'blog_meta_font_size' ) ? ' fs-' . (int) exs_option( 'blog_meta_font_size' ) : '';

				//new date modified since 1.9.9
				$exs_date_type         = exs_option( 'blog_show_date_type', 'publish' );
				$exs_date_modify_word  = exs_option( 'blog_before_date_modify_word', '' );
			}
		}

		//override diff if it passed to function
		if ( null !== $exs_human_diff && empty( $title_section_options ) ) {
			$exs_date_diff = $exs_human_diff;
		}

		if ( ! empty( $exs_show_date ) ) :

			//date-wrapper
			?>
			<span class="entry-date-wrap icon-inline <?php echo esc_attr( $css_class ); ?>">
			<?php

			//icon
			if ( ! empty( $exs_show_icons ) ) {
				exs_icon( 'clock-outline' );
			}

			$modified_class = ( 'both' === $exs_date_type || 'publish' === $exs_date_type ) ? '' : 'hidden';

			//word
			if ( ! empty( $exs_date_word ) ) :
				?>
				<span class="date-word meta-word <?php echo esc_attr( $modified_class ); ?>">
					<?php echo esc_html( $exs_date_word ); ?>
				</span><!--.date-word-->
				<?php
			endif;
			//value
			//link date to post on archive
			if ( ! is_singular() ) :
				?>
					<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" itemprop="mainEntityOfPage">
				<?php endif; //is_singular ?>
				<span class="<?php echo esc_attr( $modified_class ); ?>" itemprop="datePublished">
				<?php
					if ( ! empty( $exs_date_diff ) ) {
						/* translators: %s: Human-readable time difference. */
						$post_date = sprintf( esc_html__( '%s ago', 'exs' ), human_time_diff( get_post_timestamp( get_the_ID() ) ) );
						echo wp_kses( $post_date, array() );
					} else {
						the_time( get_option( 'date_format' ) );
					}
				?>
				</span>
				<?php
				$modified_class = ( 'both' === $exs_date_type || 'modify' === $exs_date_type ) ? '' : 'hidden';
				//word
				if ( ! empty( $exs_date_modify_word ) ) :
				?>
				<span class="date-modify-word meta-word <?php echo esc_attr( $modified_class ); ?>">
					<?php echo esc_html( $exs_date_modify_word ); ?>
				</span><!--.date-modify-word-->
				<?php endif; ?>
				<span class="<?php echo esc_attr( $modified_class ); ?>" itemprop="dateModified">
					<?php
					if ( ! empty( $exs_date_diff ) ) {
						/* translators: %s: Human-readable time difference. */
						$post_date = sprintf( esc_html__( '%s ago', 'exs' ), human_time_diff( get_post_timestamp( get_the_ID(), 'modified' ) ) );
						echo wp_kses( $post_date, array() );
					} else {
						the_modified_time( get_option( 'date_format' ) );
					}
					?>
				</span>
				<?php if ( ! is_singular() ) : ?>
					</a>
				<?php endif; //is_singular ?>
		</span><!--.entry-date-wrap-->
			<?php
		endif; //date
	}
endif; //exs_the_date

if ( ! function_exists( 'exs_the_categories' ) ) :
	/**
	 * Prints categories HTML for the current post.
	 */

	function exs_the_categories( $title_section_options = false ) {

		$css_class = '';

		//options
		//single post
		if ( is_singular() ) {

			$prefix = ! empty( $title_section_options ) ? 'title_' : '';
			$exs_show_categories = exs_option( $prefix . 'blog_single_show_categories', false );
			$exs_categories_word = exs_option( $prefix . 'blog_single_before_categories_word', '' );
			$exs_show_icons      = ! exs_option( $prefix . 'blog_single_hide_meta_icons', false );

			//new options since 1.9.5
			$css_class .= exs_option( 'blog_single_meta_bold' ) ? ' fw-700' : '';
			$css_class .= exs_option( 'blog_single_meta_uppercase' ) ? ' text-uppercase' : '';
			$css_class .= exs_option( 'blog_single_meta_font_size' ) ? ' fs-' . (int) exs_option( 'blog_single_meta_font_size' ) : '';

			//blog loop
		} else {

			if ( is_search() ) {
				$exs_show_categories = exs_option( 'search_show_categories', false );
				$exs_categories_word = exs_option( 'search_before_categories_word', '' );
				$exs_show_icons      = ! exs_option( 'search_hide_meta_icons', false );

				//new options since 1.9.5
				$css_class .= exs_option( 'search_meta_bold' ) ? ' fw-700' : '';
				$css_class .= exs_option( 'search_meta_uppercase' ) ? ' text-uppercase' : '';
				$css_class .= exs_option( 'search_meta_font_size' ) ? ' fs-' . (int) exs_option( 'search_meta_font_size' ) : '';
			} else {
				$exs_show_categories = exs_option( 'blog_show_categories', false );
				$exs_categories_word = exs_option( 'blog_before_categories_word', '' );
				$exs_show_icons      = ! exs_option( 'blog_hide_meta_icons', false );

				//new options since 1.9.5
				$css_class .= exs_option( 'blog_meta_bold' ) ? ' fw-700' : '';
				$css_class .= exs_option( 'blog_meta_uppercase' ) ? ' text-uppercase' : '';
				$css_class .= exs_option( 'blog_meta_font_size' ) ? ' fs-' . (int) exs_option( 'blog_meta_font_size' ) : '';
			}


		}

		if ( ! empty( $exs_show_categories ) ) :
			$exs_c = wp_get_post_categories( get_the_ID() );

			//only if categories exists
			if ( ! empty( $exs_c ) ) :

				//categories-wrapper
				?>
				<span class="entry-categories-wrap icon-inline <?php echo esc_attr( $css_class ); ?>">
				<?php

				//icon
				if ( ! empty( $exs_show_icons ) ) {
					exs_icon( 'folder-outline' );
				}

				//word
				if ( ! empty( $exs_categories_word ) ) :
					?>
					<span class="categories-word meta-word">
						<?php echo esc_html( $exs_categories_word ); ?>
					</span><!--.categories-word-->
					<?php
				endif;

				//value
				?>
					<span class="categories-list">
				<?php
				echo wp_kses_post( get_the_category_list( '<span class="entry-categories-separator"> </span>' ) );
				?>
					</span><!--.categories-list-->
				</span><!--.entry-categories-wrap-->
				<?php
			endif; //$exs_c
		endif; //categories
	}
endif; //exs_the_categories


if ( ! function_exists( 'exs_the_tags' ) ) :
	/**
	 * Prints tags HTML for the current post.
	 */
	function exs_the_tags( $exs_title_section = false) {

		$css_class = '';

		//options
		//single post
		if ( is_singular() ) {

			$prefix = ! empty( $exs_title_section ) ? 'title_' : '';
			$exs_show_tags  = exs_option( $prefix . 'blog_single_show_tags', false );
			$exs_tags_word  = exs_option( $prefix . 'blog_single_before_tags_word', '' );
			$exs_show_icons = ! exs_option( $prefix . 'blog_single_hide_meta_icons', false );

			//new options since 1.9.5
			$css_class .= exs_option( 'blog_single_meta_bold' ) ? ' fw-700' : '';
			$css_class .= exs_option( 'blog_single_meta_uppercase' ) ? ' text-uppercase' : '';
			$css_class .= exs_option( 'blog_single_meta_font_size' ) ? ' fs-' . (int) exs_option( 'blog_single_meta_font_size' ) : '';

			//blog loop
		} else {

			if ( is_search() ) {
				$exs_show_tags  = exs_option( 'search_show_tags', false );
				$exs_tags_word  = exs_option( 'search_before_tags_word', '' );
				$exs_show_icons = ! exs_option( 'search_hide_meta_icons', false );

				//new options since 1.9.5
				$css_class .= exs_option( 'search_meta_bold' ) ? ' fw-700' : '';
				$css_class .= exs_option( 'search_meta_uppercase' ) ? ' text-uppercase' : '';
				$css_class .= exs_option( 'search_meta_font_size' ) ? ' fs-' . (int) exs_option( 'search_meta_font_size' ) : '';
			} else {
				$exs_show_tags  = exs_option( 'blog_show_tags', false );
				$exs_tags_word  = exs_option( 'blog_before_tags_word', '' );
				$exs_show_icons = ! exs_option( 'blog_hide_meta_icons', false );

				//new options since 1.9.5
				$css_class .= exs_option( 'blog_meta_bold' ) ? ' fw-700' : '';
				$css_class .= exs_option( 'blog_meta_uppercase' ) ? ' text-uppercase' : '';
				$css_class .= exs_option( 'blog_meta_font_size' ) ? ' fs-' . (int) exs_option( 'blog_meta_font_size' ) : '';
			}
		}

		if ( ! empty( $exs_show_tags ) ) :

			$exs_t = wp_get_post_tags( get_the_ID() );

			//only if tags exists
			if ( ! empty( $exs_t ) ) :

				//tags-wrapper
				?>
				<span class="entry-tags-wrap icon-inline <?php echo esc_attr( $css_class ); ?>">
					<?php

					//icon
					if ( ! empty( $exs_show_icons ) ) {
						exs_icon( 'tag' );
					}

					//word
					if ( ! empty( $exs_tags_word ) ) :
						?>
						<span class="tags-word meta-word">
							<?php echo esc_html( $exs_tags_word ); ?>
						</span><!--.tags-word-->
						<?php
					endif; //tags_word

					//value
					?>
				<span class="tags-list">
				<?php
				echo wp_kses_post( get_the_tag_list( '<span class="entry-tags">', '<span class="entry-tags-separator"> </span>', '</span>' ) );
				?>
				</span><!--.tags-list-->
			</span><!--.entry-tags-wrap-->
				<?php
			endif; //$exs_t
		endif; //tags
	}
endif; //exs_the_tags

if ( ! function_exists( 'exs_comment_count' ) ) :
	/**
	 * Prints HTML with the comment count for the current post.
	 */
	function exs_comment_count( $title_section_options = false  ) {

		$css_class = '';

		//options
		//single post
		if ( is_singular() ) {

			$prefix = ! empty( $title_section_options ) ? 'title_' : '';
			$exs_show_comments = exs_option( $prefix . 'blog_single_show_comments_link', 'number' );
			$exs_show_icons    = ! exs_option( $prefix . 'blog_single_hide_meta_icons', false );

			//new options since 1.9.5
			$css_class .= exs_option( 'blog_single_meta_bold' ) ? ' fw-700' : '';
			$css_class .= exs_option( 'blog_single_meta_uppercase' ) ? ' text-uppercase' : '';
			$css_class .= exs_option( 'blog_single_meta_font_size' ) ? ' fs-' . (int) exs_option( 'blog_single_meta_font_size' ) : '';

			//blog loop
		} else {

			if ( is_search() ) {
				$exs_show_comments = exs_option( 'search_show_comments_link', 'number' );
				$exs_show_icons    = ! exs_option( 'search_hide_meta_icons', false );

				//new options since 1.9.5
				$css_class .= exs_option( 'search_meta_bold' ) ? ' fw-700' : '';
				$css_class .= exs_option( 'search_meta_uppercase' ) ? ' text-uppercase' : '';
				$css_class .= exs_option( 'search_meta_font_size' ) ? ' fs-' . (int) exs_option( 'search_meta_font_size' ) : '';

			} else {
				$exs_show_comments = exs_option( 'blog_show_comments_link', 'number' );
				$exs_show_icons    = ! exs_option( 'blog_hide_meta_icons', false );

				//new options since 1.9.5
				$css_class .= exs_option( 'blog_meta_bold' ) ? ' fw-700' : '';
				$css_class .= exs_option( 'blog_meta_uppercase' ) ? ' text-uppercase' : '';
				$css_class .= exs_option( 'blog_meta_font_size' ) ? ' fs-' . (int) exs_option( 'blog_meta_font_size' ) : '';
			}
		}

		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) && $exs_show_comments ) :
			switch ( $exs_show_comments ) :
				case 'number':
					?>
					<span class="comments-link icon-inline <?php echo esc_attr( $css_class ); ?>">
					<?php
					if ( ! empty( $exs_show_icons ) ) {
						exs_icon( 'comment-outline' );
					}
					$comments_count = get_comments_number();
					comments_popup_link( $comments_count, $comments_count, $comments_count );
					?>
					</span><!-- .comments-link -->
					<?php
					break;
				//text
				default:
					?>
					<span class="comments-link icon-inline <?php echo esc_attr( $css_class ); ?>">
					<?php

					if ( ! empty( $exs_show_icons ) ) {
						exs_icon( 'comment-outline' );
					}
					?>

					<?php

					comments_popup_link(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers. */
								__( ' Leave a comment<span class="screen-reader-text"> on %s</span>', 'exs' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							get_the_title()
						)
					);
					?>
				</span><!-- .comments-link -->
					<?php
			endswitch;
		endif; //show_comments
	}
endif;

if ( ! function_exists( 'exs_entry_meta' ) ) :
	/**
	 * Prints HTML with the comment count for the current post.
	 */

	function exs_entry_meta( $exs_show_author = true, $exs_show_date = true, $exs_show_categories = true, $exs_show_tags = true, $exs_show_comments = true, $exs_human_diff = null, $exs_title_section = false ) {

		/**
		 * Fires before entry meta.
		 *
		 * @since ExS 0.5.1
		 */
		do_action( 'exs_entry_meta_before', $exs_show_author, $exs_show_date, $exs_show_categories, $exs_show_tags, $exs_show_comments, $exs_human_diff, $exs_title_section );

		$show_comments_first = ( 'default-centered' !== exs_option( 'blog_layout' ) ) && ( ! is_singular() );

		//float right comments
		if ( ! empty( $exs_show_comments ) && ! empty( $show_comments_first ) ) :
			exs_comment_count( $exs_title_section );
		endif; //comments

		/**
		 * Fires before entry meta author.
		 *
		 * @since ExS 2.0.2
		 */
		do_action( 'exs_entry_meta_before_author', $exs_title_section );

		if ( ! empty( $exs_show_author ) ) :
			exs_the_author( $exs_title_section );
		endif; //author

		/**
		 * Fires before entry meta date.
		 *
		 * @since ExS 2.0.2
		 */
		do_action( 'exs_entry_meta_before_date', $exs_title_section );

		if ( ! empty( $exs_show_date ) ) :
			exs_the_date( $exs_human_diff, $exs_title_section );
		endif; //date

		/**
		 * Fires before entry meta categories.
		 *
		 * @since ExS 2.0.2
		 */
		do_action( 'exs_entry_meta_before_categories', $exs_title_section );

		if ( ! empty( $exs_show_categories ) ) :
			exs_the_categories( $exs_title_section );
		endif; //categories

		/**
		 * Fires before entry meta tags.
		 *
		 * @since ExS 2.0.2
		 */
		do_action( 'exs_entry_meta_before_tags', $exs_title_section );

		if ( ! empty( $exs_show_tags ) ) :
			exs_the_tags( $exs_title_section );
		endif; //tags

		/**
		 * Fires after entry meta tags.
		 *
		 * @since ExS 2.0.2
		 */
		do_action( 'exs_entry_meta_after_tags', $exs_title_section );

		//centered comments in archive are last in order
		if ( ! empty( $exs_show_comments ) && empty( $show_comments_first ) ) :
			exs_comment_count( $exs_title_section );
		endif; //comments

		/**
		 * Fires after entry meta.
		 *
		 * @since ExS 0.5.1
		 */
		do_action( 'exs_entry_meta_after', $exs_show_author, $exs_show_date, $exs_show_categories, $exs_show_tags, $exs_show_comments, $exs_human_diff, $exs_title_section );

	}
endif;


if ( ! function_exists( 'exs_post_nav' ) ) :
	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function exs_post_nav() {

		$exs_blog_single_post_nav = exs_option( 'blog_single_post_nav', '' );

		if ( empty( $exs_blog_single_post_nav ) ) {
			return;
		}

		$exs_exluded_special_cats = exs_get_special_categories_from_options_ids_with_children();

		// Don't print empty markup if there's nowhere to navigate.
		$exs_previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, $exs_exluded_special_cats, true );
		$exs_next     = get_adjacent_post( false, $exs_exluded_special_cats, false );

		if ( ! $exs_next && ! $exs_previous ) {
			return;
		}

		$exs_word_prev = exs_option( 'blog_single_post_nav_word_prev', esc_html__( 'Prev', 'exs' ) );
		$exs_word_next = exs_option( 'blog_single_post_nav_word_next', esc_html__( 'Next', 'exs' ) );
		?>
		<nav class="post-nav post-nav-layout-<?php echo esc_attr( $exs_blog_single_post_nav ); ?>">
			<?php

			if ( is_attachment() && 'attachment' === $exs_previous->post_type ) {
				return;
			}

			if ( $exs_previous && has_post_thumbnail( $exs_previous->ID ) ) {
				$exs_prevthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $exs_previous->ID ), 'post-thumbnail' );
				if ( $exs_prevthumb ) {
					$exs_prevthumb_sm         = wp_get_attachment_image_src( get_post_thumbnail_id( $exs_previous->ID ), 'thumbnail' );
					$exs_prev_thumbnail_style = ' style="background-image: url(' . esc_url( $exs_prevthumb[0] ) . '); "';
					$exs_prev_thumbnail_class = 'has-image background-cover background-overlay';
					$exs_prev_thumbnail_img   = '<span class="post-nav-thumb"><img src="' . esc_url( $exs_prevthumb_sm[0] ) . '" alt="' . esc_attr( $exs_previous->post_title ) . '"></span>';
				} else {
					$exs_prev_thumbnail_style = '';
					$exs_prev_thumbnail_class = 'no-image';
					$exs_prev_thumbnail_img   = '';
				}
			} else {
				$exs_prev_thumbnail_style = '';
				$exs_prev_thumbnail_class = 'no-image';
				$exs_prev_thumbnail_img   = '';
			}

			if ( $exs_next && has_post_thumbnail( $exs_next->ID ) ) {
				$exs_nextthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $exs_next->ID ), 'post-thumbnail' );
				if ( $exs_nextthumb ) {
					$exs_nextthumb_sm         = wp_get_attachment_image_src( get_post_thumbnail_id( $exs_next->ID ), 'thumbnail' );
					$exs_next_thumbnail_style = ' style="background-image: url(' . esc_url( $exs_nextthumb[0] ) . '); "';
					$exs_next_thumbnail_class = 'has-image background-cover background-overlay';
					$exs_next_thumbnail_img   = '<span class="post-nav-thumb"><img src="' . esc_url( $exs_nextthumb_sm[0] ) . '" alt="' . esc_attr( $exs_next->post_title ) . '"></span>';
				} else {
					$exs_next_thumbnail_style = '';
					$exs_next_thumbnail_class = 'no-image';
					$exs_next_thumbnail_img   = '';
				}
			} else {
				$exs_next_thumbnail_style = '';
				$exs_next_thumbnail_class = 'no-image';
				$exs_next_thumbnail_img   = '';
			}

			//layouts
			switch ( $exs_blog_single_post_nav ) :
				case 'bg':
					echo '<div>';
					previous_post_link(
						'%link',
						'<div class="post-nav-item bg-item prev-item ' . esc_attr( $exs_prev_thumbnail_class ) . '"' . $exs_prev_thumbnail_style . '>
							<span class="post-nav-arrow">' . exs_icon( 'chevron-left', true ) . '</span>
							<span class="post-nav-words-wrap">
								<span class="post-nav-word">' . esc_html( $exs_word_prev ) . '</span>
								<span class="post-nav-title">%title</span>
							</span>
						</div>',
						false,
						$exs_exluded_special_cats
					);
					echo '</div>';

					echo '<div>';
					next_post_link(
						'%link',
						'<div class="post-nav-item bg-item next-item ' . esc_attr( $exs_next_thumbnail_class ) . '"' . $exs_next_thumbnail_style . '>
							<span class="post-nav-words-wrap">
								<span class="post-nav-word">' . esc_html( $exs_word_next ) . '</span>
								<span class="post-nav-title">%title</span>
							</span>
							<span class="post-nav-arrow">' . exs_icon( 'chevron-right', true ) . '</span>
						</div>',
						false,
						$exs_exluded_special_cats
					);
					echo '</div>';
					break;
				case 'thumbnail':
					echo '<div>';
					previous_post_link(
						'%link',
						'<div class="post-nav-item prev-item">
							<span class="post-nav-arrow">' . exs_icon( 'chevron-left', true ) . '</span>
							' . $exs_prev_thumbnail_img . '
							<span class="post-nav-words-wrap">
								<span class="post-nav-word">' . esc_html( $exs_word_prev ) . '</span>
								<span class="post-nav-title">%title</span>
							</span>
						</div>',
						false,
						$exs_exluded_special_cats
					);
					echo '</div>';

					echo '<div>';
					next_post_link(
						'%link',
						'<div class="post-nav-item next-item">
							<span class="post-nav-words-wrap">
								<span class="post-nav-word">' . esc_html( $exs_word_next ) . '</span> 
								<span class="post-nav-title">%title</span>
							</span>
							' . $exs_next_thumbnail_img . '
							<span class="post-nav-arrow">' . exs_icon( 'chevron-right', true ) . '</span>
						</div>',
						false,
						$exs_exluded_special_cats
					);
					echo '</div>';
					break;
				//title
				default:
					echo '<div>';
					previous_post_link(
						'%link',
						'<div class="post-nav-item prev-item">
							<span class="post-nav-arrow">' . exs_icon( 'chevron-left', true ) . '</span>
							<span class="post-nav-words-wrap">
								<span class="post-nav-word">' . esc_html( $exs_word_prev ) . '</span>
								<span class="post-nav-title">%title</span>
							</span>
						</div>',
						false,
						$exs_exluded_special_cats
					);
					echo '</div>';

					echo '<div>';
					next_post_link(
						'%link',
						'<div class="post-nav-item next-item">
							<span class="post-nav-words-wrap">
								<span class="post-nav-word">' . esc_html( $exs_word_next ) . '</span> 
								<span class="post-nav-title">%title</span>
							</span>
							<span class="post-nav-arrow">' . exs_icon( 'chevron-right', true ) . '</span>
						</div>',
						false,
						$exs_exluded_special_cats
					);
					echo '</div>';
			endswitch;

			?>
		</nav><!-- .navigation -->
		<?php
	} //exs_post_nav
endif;


if ( ! function_exists( 'exs_section_background_image_array' ) ) :
	/**
	 * Get array of section attributes to display background image.
	 */
	function exs_section_background_image_array( $exs_section, $exs_empty_image = false ) {

		//processing title section background for simple single post 'title-section-image' layout
		if ( is_single() && 'title' === $exs_section ) :
			//if selected layout and not special post
			$exs_special_category_slug = exs_get_post_special_category_slug();
			if ( exs_get_post_layout() === 'title-section-image' && empty( $exs_special_category_slug ) ) :
				//if has post thumbnail
				if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) {
					return array(
						'url'   => get_the_post_thumbnail_url( get_the_ID(), 'full' ),
						'class' => 'i post-thumbnail-background background-cover cover-center background-fixed background-overlay overlay-dark',
						'overlay' => '',
					);
				}
			endif;
		endif; //is_single

		//for page with feature image - override default header_image
		if ( 'header_image' === $exs_section ) {
			$exs_image = get_header_image();
			//for page with feature image - override default image
			if ( is_page() ) {
				if ( has_post_thumbnail() ) {
					$exs_image = get_the_post_thumbnail_url();
				}
			}
		} else {
			$exs_image = exs_option( $exs_section . '_background_image', '' );
			// override title background if page featured image is set
			if ( 'title' === $exs_section && $exs_image ) {
				//for page with feature image - override default image
				if ( is_page() ) {
					if ( has_post_thumbnail() ) {
						$exs_image = get_the_post_thumbnail_url();
					}
				}
			}
		}

		$exs_return = array(
			'url'   => $exs_image,
			'class' => '',
			'overlay' => '',
		);

		if ( empty( $exs_image ) && empty( $exs_empty_image ) ) {
			return $exs_return;
		}

		$exs_cover   = exs_option( $exs_section . '_background_image_cover', '' );
		$exs_fixed   = exs_option( $exs_section . '_background_image_fixed', '' );
		$exs_overlay = exs_option( $exs_section . '_background_image_overlay', '' );
		$exs_opacity = exs_option( $exs_section . '_background_image_overlay_opacity', '' );

		if ( ! empty( $exs_cover ) ) {
			$exs_return['class'] .= 'background-cover cover-center';
		}

		if ( ! empty( $exs_fixed ) ) {
			$exs_return['class'] .= ' background-fixed';
		}

		if ( ! empty( $exs_overlay ) ) {
			$exs_return['class'] .= ' background-overlay ' . $exs_overlay;
			if ( ! empty( $exs_opacity ) ) {
				$opacity = ( int ) $exs_opacity / 100;
				$exs_return['overlay'] = '--' . $exs_overlay . ':' . $opacity;
			}
		}

		return $exs_return;
	}
endif;

/////////////
//Read More//
/////////////

// Read more markup inside link for excertp and the_content
if ( ! function_exists( 'exs_read_more_inside_link_markup' ) ) :
	function exs_read_more_inside_link_markup( $exs_read_more_text = '' ) {

		if ( empty( $exs_read_more_text ) ) {
			$exs_read_more_text = is_search() ? exs_option( 'search_read_more_text', '' ) : exs_option( 'blog_read_more_text', '' );
		}

		if ( empty( $exs_read_more_text ) ) {
			return '';
		}

		return sprintf(
			wp_kses(
				$exs_read_more_text . '<span class="screen-reader-text"> "%s"</span>',
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			get_the_title()
		);

	}
endif;

//generated excerpt ending...
if ( ! function_exists( 'exs_excerpt_more' ) ) :
	function exs_excerpt_more( $exs_more ) {
		if ( is_admin() && ! wp_doing_ajax() ) {
			return $exs_more;
		}
		if ( empty( exs_option( 'blog_excerpt_length' ) ) ) {
			return '';
		}
		return '<span class="more-dots">...</span>';
	}
endif;
add_filter( 'excerpt_more', 'exs_excerpt_more', 21 );

//read more for excerpt
if ( ! function_exists( 'exs_read_more_markup_excerpt' ) ) :
	function exs_read_more_markup_excerpt() {
		global $post;


		$exs_read_more_style = is_search() ? exs_option( 'search_read_more_style', '' ) : exs_option( 'blog_read_more_style', '' );
		$exs_read_more_block = is_search() ? exs_option( 'search_read_more_block', '' ) : exs_option( 'blog_read_more_block', '' );

		$d_block = $exs_read_more_block ? ' d-block' : '';
		switch ( $exs_read_more_style ) :
			case 'button':
				$a_class = ' wp-block-button__link icon-inline';
				$d_block .= ' mt-15';
				break;
			case 'button-arrow':
				$a_class = ' wp-block-button__link';
				$d_block .= ' mt-15 is-style-arrow';
				break;
			default:
				$d_block .= $exs_read_more_block ? ' mt-05' : '';
				$a_class = '';
		endswitch;

		$exs_markup = ' <span class="more-tag' . $d_block . '"><a class="more-link' . $a_class . '" href="' .
		esc_url( get_permalink( $post->ID ) ) . '">' .
		exs_read_more_inside_link_markup() .
		'</a></span><!-- .more-tag -->';

		return $exs_markup;
	}
endif;

//putting read more text inside excerpt if text is not empty
if ( ! function_exists( 'exs_read_more_in_excerpt' ) ) :
	function exs_read_more_in_excerpt( $exs_excerpt ) {

		$exs_read_more_text = is_search() ? exs_option( 'search_read_more_text', '' ) : exs_option( 'blog_read_more_text', '' );
		$exs_excerpt_length = is_search() ? exs_option( 'search_excerpt_length', '' ) : exs_option( 'blog_excerpt_length', '' );

		//if excerpt length set to 0 manually and no 'read more' - return empty string
		if ( '0' === $exs_excerpt_length && empty( $exs_read_more_text ) ) {
			return '';
		}

		//if excerpt length set to 0 manually and 'read more' not empty - return empty string
		if ( '0' === $exs_excerpt_length && ! empty( $exs_read_more_text ) ) {
			return exs_read_more_markup_excerpt();
		}

		if ( empty( $exs_read_more_text ) ) {
			return $exs_excerpt;
		}

		$exs_excerpt = str_replace( '</p>', exs_read_more_markup_excerpt() . '</p>', $exs_excerpt );

		return $exs_excerpt;
	}
endif;
add_filter( 'the_excerpt', 'exs_read_more_in_excerpt', 21 );

//Filter the except length
if ( ! function_exists( 'exs_excerpt_custom_length' ) ) :
	function exs_excerpt_custom_length( $exs_length ) {
		if ( is_admin() && ! wp_doing_ajax() ) {
			return $exs_length;
		}
		$exs_excerpt_length = is_search() ? exs_option( 'search_excerpt_length', '' ) : exs_option( 'blog_excerpt_length', '' );
		return absint( $exs_excerpt_length );
	}
endif;
add_filter( 'excerpt_length', 'exs_excerpt_custom_length', 999 );

//home page intro teasers
if ( ! function_exists( 'exs_get_intro_teasers' ) ) :
	function exs_get_intro_teasers() {

		$exs_teasers = array();

		for ( $exs_i = 1; $exs_i < 5; $exs_i ++ ) {
			/*
			reeatable options:
				intro_teaser_image_
				intro_teaser_title_
				intro_teaser_text_
				intro_teaser_link_
				intro_teaser_button_text_
			*/
			$exs_teasers[ $exs_i ] = array_filter(
				array(
					'image'  => exs_option( 'intro_teaser_image_' . $exs_i, '' ),
					'title'  => exs_option( 'intro_teaser_title_' . $exs_i, '' ),
					'text'   => exs_option( 'intro_teaser_text_' . $exs_i, '' ),
					'link'   => exs_option( 'intro_teaser_link_' . $exs_i, '' ),
					'button' => exs_option( 'intro_teaser_button_text_' . $exs_i, '' ),
				)
			);
		}

		return array_filter( $exs_teasers );

	}
endif;

//related posts
if ( ! function_exists( 'exs_related_posts' ) ) :
	function exs_related_posts( $exs_id ) {
		$exs_layout = exs_option( 'blog_single_related_posts', '' );
		if ( empty( $exs_layout ) ) {
			return;
		}
		//tags, cats or author based related posts
		$related_base = exs_option( 'blog_single_related_posts_base', '' );
		//tags by default
		if ( empty( $related_base ) ) {
			$exs_related_meta = wp_get_post_tags( $exs_id, array( 'fields' => 'ids' ) );
		}
		if ( 'cat' === $related_base ) {
			$exs_related_meta = wp_get_post_categories( $exs_id, array( 'fields' => 'ids' ) );
		}
		if ( 'author' === $related_base ) {
			$exs_related_meta = get_the_author_meta( 'ID' );
		}
		if ( ! empty( $exs_related_meta ) ) :
			//list
			//list-thumbnails
			//grid
			//num of posts
			$exs_posts_number = absint( exs_option( 'blog_single_related_posts_number', 3 ) );
			if ( empty( $exs_posts_number ) ) {
				$exs_posts_number = 3;
			}
			//tags by default
			$exs_args  = array(
				'tag__in'        => $exs_related_meta,
				'post__not_in'   => array( $exs_id ),
				'posts_per_page' => $exs_posts_number,
			);

			if ( 'cat' === $related_base ) {
				$exs_args  = array(
					'category__in'        => $exs_related_meta,
					'post__not_in'   => array( $exs_id ),
					'posts_per_page' => $exs_posts_number,
				);
			}
			if ( 'author' === $related_base ) {
				$exs_args  = array(
					'author'         => $exs_related_meta,
					'post__not_in'   => array( $exs_id ),
					'posts_per_page' => $exs_posts_number,
				);
			}

			$exs_query = new WP_Query( $exs_args );
			if ( $exs_query->have_posts() ) :
				$exs_related_title = exs_option( 'blog_single_related_posts_title', esc_html__( 'Related Posts', 'exs' ) );

				$hidden_class  = exs_option( 'blog_single_related_posts_hidden', '' );
				$show_date     = exs_option( 'blog_single_related_show_date', '' );
				$readmore_text = exs_option( 'blog_single_related_posts_readmore_text', '' );
				$image_size    = exs_option( 'blog_single_related_posts_image_size', '' );
				//new since v1.9.5
				$mt        = exs_option( 'blog_single_related_posts_mt' );
				$mb        = exs_option( 'blog_single_related_posts_mb' );
				$bg        = exs_option( 'blog_single_related_posts_background' );
				$section   = exs_option( 'blog_single_related_posts_section' ) ? 'section' : '';
				if ( $bg && ! $section ) {
					$bg .= ' extra-padding';
				}
				$pt        = exs_option( 'blog_single_related_posts_pt' );
				$pb        = exs_option( 'blog_single_related_posts_pb' );
				$alignfull = exs_option( 'blog_single_related_posts_fullwidth' ) ? 'alignfull' : '';

				?>
				<div class="related-posts <?php echo esc_attr( $hidden_class . ' ' . $mt . ' ' . $mb . ' ' . $bg . ' ' . $section . ' ' . $pt . ' ' . $pb . ' ' . $alignfull ); ?>">
					<?php if ( ! empty( $exs_related_title ) ) : ?>
						<h3 class="related-posts-heading"><?php echo esc_html( $exs_related_title ); ?></h3>
						<?php
					endif; //related_title
					switch ( $exs_layout ) :
						case 'grid':
							switch ( $exs_posts_number ) :
								case 3:
									$exs_wrapper_class = 'layout-cols-3';
									break;
								case 4:
									$exs_wrapper_class = 'layout-cols-4';
									break;
								case 6:
									$exs_wrapper_class = 'layout-cols-6';
									break;
								default:
									$exs_wrapper_class = '';
							endswitch;
							if ( $exs_query->post_count < 3 ) {
								$exs_wrapper_class = 'layout-cols-' . $exs_query->post_count;
							}
							?>
							<div class="layout-gap-20">
								<div class="grid-wrapper <?php echo esc_attr( $exs_wrapper_class ); ?>">
									<?php
									$image_size = $image_size ? $image_size : 'large';
									while ( $exs_query->have_posts() ) :
										$exs_query->the_post();
										?>
										<div class=grid-item>
											<article <?php post_class(); ?>>
												<?php if ( has_post_thumbnail() ) : ?>
													<figure class="post-thumbnail">
														<a href="<?php the_permalink(); ?>">
															<?php the_post_thumbnail( $image_size ); ?>
														</a>
													</figure>
												<?php endif; ?>
												<div class="item-content">
													<h6>
														<a rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
													</h6>
													<?php if ( $show_date ) : ?>
													<footer class="small-text">
														<span class="icon-inline post-date mt-05 mb-05">
															<?php exs_icon( 'calendar' ); ?>
															<span><?php echo get_the_date( '', get_the_ID() ); ?></span>
														</span>
													</footer>
													<?php endif; ?>
													<?php if ( $readmore_text ) : ?>
													<span class="related-posts-read-more-text">
														<a rel="bookmark" href="<?php the_permalink(); ?>"><?php echo esc_html( $readmore_text ); ?></a>
													</span>
													<?php endif; ?>
												</div>
											</article>
										</div>
									<?php endwhile; ?>
								</div><!-- .grid-wrapper -->
							</div><!-- .layout-gap-* -->
							<?php
							break;

							case 'grid-2':
								$exs_wrapper_class = 'layout-cols-2';
							?>
							<div class="layout-gap-20">
								<div class="grid-wrapper <?php echo esc_attr( $exs_wrapper_class ); ?>">
									<?php
									$image_size = $image_size ? $image_size : 'large';
									while ( $exs_query->have_posts() ) :
										$exs_query->the_post();
										?>
										<div class=grid-item>
											<article <?php post_class(); ?>>
												<?php if ( has_post_thumbnail() ) : ?>
													<figure class="post-thumbnail">
														<a href="<?php the_permalink(); ?>">
															<?php the_post_thumbnail( $image_size ); ?>
														</a>
													</figure>
												<?php endif; ?>
												<div class="item-content">
													<h6>
														<a rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
													</h6>
													<?php if ( $show_date ) : ?>
														<footer class="small-text">
														<span class="icon-inline post-date mt-05 mb-05">
															<?php exs_icon( 'calendar' ); ?>
															<span><?php echo get_the_date( '', get_the_ID() ); ?></span>
														</span>
														</footer>
													<?php endif; ?>
													<?php if ( $readmore_text ) : ?>
														<span class="related-posts-read-more-text">
														<a rel="bookmark" href="<?php the_permalink(); ?>"><?php echo esc_html( $readmore_text ); ?></a>
													</span>
													<?php endif; ?>
												</div>
											</article>
										</div>
									<?php endwhile; ?>
								</div><!-- .grid-wrapper -->
							</div><!-- .layout-gap-* -->
							<?php
							break;

							case 'grid-3':
								$exs_wrapper_class = 'layout-cols-3';
							?>
							<div class="layout-gap-20">
								<div class="grid-wrapper <?php echo esc_attr( $exs_wrapper_class ); ?>">
									<?php
									$image_size = $image_size ? $image_size : 'large';
									while ( $exs_query->have_posts() ) :
										$exs_query->the_post();
										?>
										<div class=grid-item>
											<article <?php post_class(); ?>>
												<?php if ( has_post_thumbnail() ) : ?>
													<figure class="post-thumbnail">
														<a href="<?php the_permalink(); ?>">
															<?php the_post_thumbnail( $image_size ); ?>
														</a>
													</figure>
												<?php endif; ?>
												<div class="item-content">
													<h6>
														<a rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
													</h6>
													<?php if ( $show_date ) : ?>
														<footer class="small-text">
														<span class="icon-inline post-date mt-05 mb-05">
															<?php exs_icon( 'calendar' ); ?>
															<span><?php echo get_the_date( '', get_the_ID() ); ?></span>
														</span>
														</footer>
													<?php endif; ?>
													<?php if ( $readmore_text ) : ?>
														<span class="related-posts-read-more-text">
														<a rel="bookmark" href="<?php the_permalink(); ?>"><?php echo esc_html( $readmore_text ); ?></a>
													</span>
													<?php endif; ?>
												</div>
											</article>
										</div>
									<?php endwhile; ?>
								</div><!-- .grid-wrapper -->
							</div><!-- .layout-gap-* -->
							<?php
							break;

							case 'grid-4':
								$exs_wrapper_class = 'layout-cols-4';
							?>
							<div class="layout-gap-20">
								<div class="grid-wrapper <?php echo esc_attr( $exs_wrapper_class ); ?>">
									<?php
									$image_size = $image_size ? $image_size : 'large';
									while ( $exs_query->have_posts() ) :
										$exs_query->the_post();
										?>
										<div class=grid-item>
											<article <?php post_class(); ?>>
												<?php if ( has_post_thumbnail() ) : ?>
													<figure class="post-thumbnail">
														<a href="<?php the_permalink(); ?>">
															<?php the_post_thumbnail( $image_size ); ?>
														</a>
													</figure>
												<?php endif; ?>
												<div class="item-content">
													<h6>
														<a rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
													</h6>
													<?php if ( $show_date ) : ?>
													<footer class="small-text">
														<span class="icon-inline post-date mt-05 mb-05">
															<?php exs_icon( 'calendar' ); ?>
															<span><?php echo get_the_date( '', get_the_ID() ); ?></span>
														</span>
													</footer>
													<?php endif; ?>
													<?php if ( $readmore_text ) : ?>
													<span class="related-posts-read-more-text">
														<a rel="bookmark" href="<?php the_permalink(); ?>"><?php echo esc_html( $readmore_text ); ?></a>
													</span>
													<?php endif; ?>
												</div>
											</article>
										</div>
									<?php endwhile; ?>
								</div><!-- .grid-wrapper -->
							</div><!-- .layout-gap-* -->
							<?php
							break;

							case 'grid-6':
								$exs_wrapper_class = 'layout-cols-6';
							?>
							<div class="layout-gap-20">
								<div class="grid-wrapper <?php echo esc_attr( $exs_wrapper_class ); ?>">
									<?php
									$image_size = $image_size ? $image_size : 'large';
									while ( $exs_query->have_posts() ) :
										$exs_query->the_post();
										?>
										<div class=grid-item>
											<article <?php post_class(); ?>>
												<?php if ( has_post_thumbnail() ) : ?>
													<figure class="post-thumbnail">
														<a href="<?php the_permalink(); ?>">
															<?php the_post_thumbnail( $image_size ); ?>
														</a>
													</figure>
												<?php endif; ?>
												<div class="item-content">
													<h6>
														<a rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
													</h6>
													<?php if ( $show_date ) : ?>
														<footer class="small-text">
														<span class="icon-inline post-date mt-05 mb-05">
															<?php exs_icon( 'calendar' ); ?>
															<span><?php echo get_the_date( '', get_the_ID() ); ?></span>
														</span>
														</footer>
													<?php endif; ?>
													<?php if ( $readmore_text ) : ?>
														<span class="related-posts-read-more-text">
														<a rel="bookmark" href="<?php the_permalink(); ?>"><?php echo esc_html( $readmore_text ); ?></a>
													</span>
													<?php endif; ?>
												</div>
											</article>
										</div>
									<?php endwhile; ?>
								</div><!-- .grid-wrapper -->
							</div><!-- .layout-gap-* -->
							<?php
							break;

						case 'list-thumbnails':
							?>
							<ul class="posts-list">
								<?php
								$image_size = $image_size ? $image_size : 'thumbnail';
								while ( $exs_query->have_posts() ) :
									$exs_query->the_post();
									?>
									<li>
										<?php if ( has_post_thumbnail() ) : ?>
											<a class="posts-list-thumbnail" href="<?php the_permalink(); ?>">
												<?php the_post_thumbnail( $image_size ); ?>
											</a>
										<?php endif; ?>
										<div class="item-content">
											<h3 class="post-title">
												<a rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h3>
											<span class="icon-inline post-date">
												<?php exs_icon( 'calendar' ); ?>
												<span><?php echo get_the_date( '', get_the_ID() ); ?></span>
											</span>
											<?php if ( $readmore_text ) : ?>
												<span class="related-posts-read-more-text">
														<a rel="bookmark" href="<?php the_permalink(); ?>"><?php echo esc_html( $readmore_text ); ?></a>
													</span>
											<?php endif; ?>
										</div>
									</li>
								<?php endwhile; ?>
							</ul>
							<?php
							break;

							case 'list-big-thumbs':
							?>
							<div class="posts-list">
								<?php
								$image_size = $image_size ? $image_size : 'medium';
								while ( $exs_query->have_posts() ) :
									$exs_query->the_post();
									?>
									<div class="side-item mt-2">
										<?php if ( has_post_thumbnail() ) : ?>
											<a class="posts-thumbnail" href="<?php the_permalink(); ?>">
												<?php the_post_thumbnail( $image_size ); ?>
											</a>
										<?php endif; ?>
										<div class="related-item-content">
											<h3 class="post-title">
												<a rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h3>
											<span class="icon-inline post-date mt-05 mb-05">
												<?php exs_icon( 'calendar' ); ?>
												<span><?php echo get_the_date( '', get_the_ID() ); ?></span>
											</span>
											<?php the_excerpt(); ?>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
							<?php
							break;

						default:
							?>
							<ul>
								<?php
								while ( $exs_query->have_posts() ) :
									$exs_query->the_post();
									?>
									<li>
										<h6>
											<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
										</h6>
									</li>
								<?php endwhile; ?>
							</ul>
							<?php
					endswitch; //layout
					?>
				</div><!-- .related-posts -->
				<?php
			endif; //have_posts
			wp_reset_postdata();
		endif; //$exs_related_meta
	}
endif;

//get any widget HTML markup
if ( ! function_exists( 'exs_get_the_widget' ) ) :
	function exs_get_the_widget( $exs_widget_class, $exs_instance = array() ) {

		if ( ! class_exists( $exs_widget_class ) ) {
			return '';
		}
		//same as in inc/setup.php file
		$exs_args = array(
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title'  => '</span></h3>',
		);

		ob_start();

		the_widget( $exs_widget_class, $exs_instance, $exs_args );

		return ob_get_clean();
	}
endif;

//count widgets in sidebar
if ( ! function_exists( 'exs_get_sidebar_widgets_count' ) ) :
	function exs_get_sidebar_widgets_count( $sidebar_id ) {
		$widgets = get_option( 'sidebars_widgets' );

		return count( $widgets[ $sidebar_id ] );
	}
endif;

//detect shop - handy for sidebar and breadcrumbs
if ( ! function_exists( 'exs_is_shop' ) ) :
	function exs_is_shop() {
		$exs_return = false;
		if ( function_exists( 'is_woocommerce' ) ) {
			if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
				$exs_return = true;
			}
		}
		if ( function_exists( 'yith_wcwl_is_wishlist_page' ) ) {
			if ( yith_wcwl_is_wishlist_page() ) {
				$exs_return = true;
			}
		}

		return $exs_return;
	}
endif;

//detect EDD downloads archive - handy for sidebar and breadcrumbs
if ( ! function_exists( 'exs_is_downloads' ) ) :
	function exs_is_downloads() {
		$exs_return = false;

		if ( is_post_type_archive( 'download' ) || is_tax( 'download_category' ) || is_tax( 'download_tag' ) ) {
				$exs_return = true;
		}

		return $exs_return;
	}
endif;

//detect bbPress - handy for sidebar and breadcrumbs
if ( ! function_exists( 'exs_is_bbpress' ) ) :
	function exs_is_bbpress() {
		$exs_return = false;
		if ( function_exists( 'is_bbpress' ) ) {
			if ( is_bbpress() ) {
				$exs_return = true;
			}
		}

		return $exs_return;
	}
endif;

//detect BuddyPress - handy for sidebar and breadcrumbs
if ( ! function_exists( 'exs_is_buddypress' ) ) :
	function exs_is_buddypress() {
		$exs_return = false;
		if ( function_exists( 'is_buddypress' ) ) {
			if ( is_buddypress() ) {
				$exs_return = true;
			}
		}

		return $exs_return;
	}
endif;

//detect BuddyPress - handy for sidebar and breadcrumbs
if ( ! function_exists( 'exs_is_wpjm' ) ) :
	function exs_is_wpjm() {
		$exs_return = false;
		if ( function_exists( 'is_wpjm' ) ) {
			if ( is_wpjm() ) {
				$exs_return = true;
			}
		}

		return $exs_return;
	}
endif;

//detect The Events Calendar - handy for sidebar and breadcrumbs
if ( ! function_exists( 'exs_is_events' ) ) :
	function exs_is_events() {
		$exs_return = false;
		if ( function_exists( 'tribe_is_event_query' ) ) {
			if ( tribe_is_event_query() ) {
				$exs_return = true;
			}
		}

		return $exs_return;
	}
endif;

//detect LearnPress archive - handy for sidebar and breadcrumbs
if ( ! function_exists( 'exs_is_learnpress_archive' ) ) :
	function exs_is_learnpress_archive() {
		$exs_return = false;
		if ( function_exists( 'is_learnpress' ) ) {
			if ( is_learnpress() && ! is_singular() ) {
				$exs_return = true;
			}
		}

		return $exs_return;
	}
endif;

//detect LearnPress Course - handy for sidebar and breadcrumbs
if ( ! function_exists( 'exs_is_learnpress_course' ) ) :
	function exs_is_learnpress_course() {
		$exs_return = false;
		if ( function_exists( 'learn_press_is_course' ) ) {
			if ( learn_press_is_course() ) {
				$exs_return = true;
			}
		}

		return $exs_return;
	}
endif;

//echo breadcrumbs markup
if ( ! function_exists( 'exs_breadcrumbs' ) ) :
	function exs_breadcrumbs() {
		$exs_args              = array(
			'before' => '<nav class="breadcrumbs">',
			'after'  => '</nav>',
		);
		$exs_seo_options       = get_option( 'wpseo_titles' );
		$exs_args['delimiter'] = ! empty( $exs_seo_options['breadcrumbs-sep'] ) ? $exs_seo_options['breadcrumbs-sep'] : '/';
		if ( exs_is_shop() ) :
			woocommerce_breadcrumb(
				array(
					'wrap_before' => $exs_args['before'] . '<span>',
					'wrap_after'  => '</span>' . $exs_args['after'],
					'before'      => '<span>',
					'after'       => '</span>',
					'delimiter'   => ' ' . $exs_args['delimiter'] . ' ',
				)
			);
		elseif ( function_exists( 'learn_press_breadcrumb' ) && is_learnpress() ) :
			$args = array(
				'delimiter'   => '&nbsp;&#47;&nbsp;',
				'wrap_before' => '<nav class="breadcrumbs">',
				'wrap_after'  => '</nav>',
				'before'      => '',
				'after'       => '',
			);
			learn_press_breadcrumb( $args );
		elseif ( function_exists( 'yoast_breadcrumb' ) ) :
			yoast_breadcrumb( '<nav class="breadcrumbs">', '</nav>' );
		elseif ( function_exists( 'rank_math_the_breadcrumbs' ) ) :
			$args = array(
				'delimiter'   => '&nbsp;&#47;&nbsp;',
				'wrap_before' => '<nav class="breadcrumbs">',
				'wrap_after'  => '</nav>',
				'before'      => '',
				'after'       => '',
			);
			rank_math_the_breadcrumbs( $args );
		endif;
	}
endif;

//check if breadcrumbs are enabled and plugins to show them are active
if ( ! function_exists( 'exs_breadcrumbs_enabled' ) ) :
	function exs_breadcrumbs_enabled() {
		$exs_return = exs_option( 'title_show_breadcrumbs', true );
		if ( exs_is_shop() && $exs_return ) {
			return $exs_return;
		} elseif ( function_exists( 'learn_press_breadcrumb' ) && is_learnpress() && $exs_return ) {
			return $exs_return;
		} elseif ( function_exists( 'yoast_breadcrumb' ) && $exs_return ) {
			return $exs_return;
		} elseif ( function_exists( 'rank_math_the_breadcrumbs' ) && $exs_return ) {
			return $exs_return;
		} else {
			return false;
		}
	}
endif;

//copyright text - year
if ( ! function_exists( 'exs_get_copyright_text' ) ) :
	function exs_get_copyright_text( $exs_text = '' ) {
		$exs_text = str_replace( '[year]', '<span class="copyright-year">' . date( 'Y' ) . '</span>', $exs_text );
		return $exs_text;
	}
endif;

//detect is_front_page and not is paged
if ( ! function_exists( 'exs_is_front_page' ) ) :
	function exs_is_front_page() {
		return is_front_page() && ! is_paged();
	}
endif;

//detect for displaying title section
if ( ! function_exists( 'exs_is_title_section_is_shown' ) ) :
	function exs_is_title_section_is_shown() {
		if ( is_page_template( 'page-templates/no-sidebar-no-title.php' ) || is_page_template( 'page-templates/header-overlap.php' ) ) {
			return false;
		}
		$exs_show_title       = exs_option( 'title_show_title', '' );
		$exs_show_search      = exs_option( 'title_show_search', '' );
		$exs_show_breadcrumbs = exs_breadcrumbs_enabled();
		$exs_is_front_page    = exs_is_front_page();

		if ( ! empty( $exs_is_front_page ) && empty( $exs_show_search ) ) {
			return false;
		}

		if ( empty( $exs_show_title ) && empty( $exs_show_breadcrumbs ) && empty( $exs_show_search ) ) {
			return false;
		} else {
			return true;
		}
	}
endif;

//based on the WooCommerce wc_hex_is_light
if ( ! function_exists( 'exs_hex_is_light' ) ) {

	/**
	 * Determine whether a hex color is light.
	 *
	 * @param mixed $color Color.
	 * @return bool  True if a light color.
	 */
	function exs_hex_is_light( $color ) {

		$hex = str_replace( '#', '', $color );

		//hex is 6 signs
		if ( 6 === strlen( $hex ) ) {
			$c_r = hexdec( substr( $hex, 0, 2 ) );
			$c_g = hexdec( substr( $hex, 2, 2 ) );
			$c_b = hexdec( substr( $hex, 4, 2 ) );
			//hex is 3 signs
		} elseif ( 3 === strlen( $hex ) ) {
			$c_r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$c_g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$c_b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			//light is default is color is failure
			return true;
		}

		$brightness = ( ( $c_r * 299 ) + ( $c_g * 587 ) + ( $c_b * 114 ) ) / 1000;

		$return = $brightness > 155;

		return $return;
	}
}

//if inverse background
if ( ! function_exists( 'exs_is_inverse_background' ) ) {

	/**
	 * Determine whether background class has "i" as a first letter
	 *
	 * @param string $class string.
	 * @return bool  True if a reverse section.
	 */
	function exs_is_inverse_background( $class ) {
		return 0 === strpos( $class, 'i' );
	}
}

//if inverse background
if ( ! function_exists( 'exs_get_transparent_class' ) ) {
	function exs_get_transparent_class( $class ) {
		$is_inverse    = exs_is_inverse_background( $class );
		$header_bg_hex = exs_option( exs_get_color_name_based_on_bg_class( $class ) );
		$is_light      = exs_hex_is_light( $header_bg_hex );
		$return        = 'i';
		if ( $is_inverse ) {
			if ( $is_light ) {
				$return = 'header-inverse l';
			}
		} else {
			if ( ! $is_light ) {
				$return = 'header-inverse l';
			}
		}

		return $return;
	}
}

//inverse color scheme switcher
if ( ! function_exists( 'exs_print_inverse_color_scheme_switcher' ) ) {
	function exs_print_inverse_color_scheme_switcher() {
		if ( exs_option( 'colors_inverse_enabled' ) ) :
			$hide_switch_class = exs_option( 'colors_inverse_hide_switcher' ) ? 'switch-hidden' : '';
			$btn_inverse_class = ! empty ( $_COOKIE['exs-color-inverse'] ) ? 'active' : '';
			?>
			<button id="toggle_inverse" class="<?php echo esc_attr( $btn_inverse_class . ' ' . $hide_switch_class ); ?>">
				<?php
				if ( ! exs_option( 'colors_inverse_hide_icon' ) ) :
					exs_icon('dark-mode');
				endif;

				if ( ! exs_option( 'colors_inverse_hide_label' ) ) :
					?>
					<span class="inverse-label c-default"><?php echo esc_html( exs_option( 'colors_inverse_label_default' ) ); ?></span>
					<span class="inverse-label c-inverse"><?php echo esc_html( exs_option( 'colors_inverse_label_inverse' ) ); ?></span>
				<?php endif; ?>
			</button>
		<?php
		endif;
	}
}
add_action( 'exs_action_header_before_header_button', 'exs_print_inverse_color_scheme_switcher' );
