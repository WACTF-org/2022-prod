<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 * @version 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$exs_css_classes = exs_get_layout_css_classes();

if ( empty( $exs_css_classes['aside'] ) ) {
	if( is_customize_preview() ) {
		echo '<aside id="aside" class="d-none"></aside>';
	}
	return;
}
$exs_sidebar_sticky       = exs_option( 'main_sidebar_sticky', false );
$exs_sidebar_sticky_class = ! empty( $exs_sidebar_sticky ) ? ' sticky' : '';
$exs_font_size            = exs_option( 'sidebar_font_size', '' );
$exs_sidebar_extra_class  = '';
if ( is_page_template( 'page-templates/home.php' ) || is_front_page() ) {
	if ( is_active_sidebar( 'sidebar-home-after-columns' ) ) {
		$exs_sidebar_extra_class .= 'with-after-columns-sidebar';
	}
}
if ( exs_option( 'main_sidebar_widgets_title_uppercase' ) ) {
	$exs_sidebar_extra_class .= ' wt-uppercase';
}
if ( exs_option( 'main_sidebar_widgets_title_bold' ) ) {
	$exs_sidebar_extra_class .= ' wt-bold';
}
if ( exs_option( 'main_sidebar_widgets_title_decor' ) ) {
	$exs_sidebar_extra_class .= ' wt-decor';
}
?>
<aside id="aside" itemtype="https://schema.org/WPSideBar" itemscope="itemscope" class="<?php echo esc_attr( $exs_css_classes['aside'] . ' ' . $exs_font_size . ' ' . $exs_sidebar_extra_class ); ?>">
	<div id="widgets-wrap" class="widgets-wrap<?php echo esc_attr( $exs_sidebar_sticky_class ); ?>">

		<?php
		/**
		 * Fires at the top of aside column.
		 *
		 * @since ExS 0.0.1
		 */
		do_action( 'exs_action_top_of_aside_column' );

		if ( is_singular( 'download' ) ) {
			get_template_part( 'template-parts/edd/sidebar-single-download' );
		}
		else if ( exs_is_downloads() ) {
			dynamic_sidebar( 'sidebar-downloads' );
		}
		else if ( exs_is_events() ) {
			dynamic_sidebar( 'sidebar-events' );
		}
		else if ( exs_is_learnpress_archive() ) {
			dynamic_sidebar( 'sidebar-courses' );
		}
		else if ( exs_is_learnpress_course() ) {
			dynamic_sidebar( 'sidebar-course' );
		}
		else if ( exs_is_wpjm() ) {
			dynamic_sidebar( 'sidebar-wpjm' );
		}
		else if ( exs_is_bbpress() ) {
			dynamic_sidebar( 'sidebar-bbpress' );
		}
		else if ( exs_is_buddypress() ) {
			dynamic_sidebar( 'sidebar-buddypress' );
		}
		else if ( exs_is_shop() ) {
			dynamic_sidebar( 'shop' );
		} else {
			if ( is_page_template( 'page-templates/home.php' ) || is_front_page() ) {
				if ( is_active_sidebar( 'sidebar-home-main' ) ) {
					dynamic_sidebar( 'sidebar-home-main' );
				} else {
					dynamic_sidebar( 'sidebar-1' );
				}
			} else {
				dynamic_sidebar( 'sidebar-1' );
			}
		}

		/**
		 * Fires at the bottom of aside column.
		 *
		 * @since ExS 0.0.1
		 */
		do_action( 'exs_action_bottom_of_aside_column' );

		?>

	</div><!-- .widgets-wrap -->
</aside><!-- .column-aside -->
