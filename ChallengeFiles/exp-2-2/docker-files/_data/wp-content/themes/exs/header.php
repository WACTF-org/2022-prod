<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$exs_body_itemtype = exs_get_body_schema_itemtype();

?><!doctype html>
<html <?php language_attributes(); ?> class="no-js-disabled<?php echo is_customize_preview() ? ' customize-preview' : ''; ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="profile" href="https://gmpg.org/xfn/11"/>
	<?php wp_head(); ?>
</head>
<body id="body" <?php body_class(); ?> itemtype="https://schema.org/<?php echo esc_attr( $exs_body_itemtype ); ?>" itemscope="itemscope" data-nonce="<?php echo esc_attr( wp_create_nonce( 'exs_nonce' ) ); ?>" data-ajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
	<?php exs_animated_elements_markup(); ?>
>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}

?>
<a id="skip_link" class="screen-reader-text skip-link" href="#main"><?php echo esc_html__( 'Skip to content', 'exs' ); ?></a>
<?php

get_template_part( 'template-parts/header/header-preloader' );

get_template_part( 'template-parts/header/fixed-sidebar' );

/**
 * Fires at the top of whole web page before the header.
 *
 * @since ExS 0.0.1
 */
do_action( 'exs_action_before_header' );

$exs_box_fade_in_class = exs_option( 'box_fade_in', '' ) ? 'box-fade-in' : 'box-normal';
?>
<div id="box" class="<?php echo esc_attr( $exs_box_fade_in_class ); ?>">
	<?php

	//topline, header and title here:
	get_template_part( 'template-parts/header/header-top' );

	//not opening container for 404 page or for full-width page template
	if ( ! is_page_template( 'page-templates/full-width.php' ) && ! is_404() ) :
		$exs_main_sidebar_width   = exs_option( 'main_sidebar_width', '' );
		$exs_main_gap_width       = exs_option( 'main_gap_width', 'default' );
		$exs_extra_padding_top    = exs_option( 'main_extra_padding_top', '' );
		$exs_extra_padding_bottom = exs_option( 'main_extra_padding_bottom', '' );
		$exs_font_size            = exs_option( 'main_font_size', '' );
		$exs_main_css_classes     = exs_get_page_main_section_css_classes();
		$exs_css_classes          = exs_get_layout_css_classes();

		if ( exs_option( 'header_absolute', '' ) || is_page_template( 'page-templates/header-overlap.php' ) ) {
			$exs_main_css_classes .= ' header-overlap-main';
		}

		if ( empty( $exs_extra_padding_top ) && ! exs_is_title_section_is_shown() ) {
			$exs_extra_padding_top = 'pt-5';
		}
		//no top padding for page template without title and padding
		if ( is_page_template( 'page-templates/no-sidebar-no-title.php' ) || is_page_template( 'page-templates/header-overlap.php' ) ) {
			$exs_extra_padding_top    = 'pt-0';
			$exs_extra_padding_bottom = 'pb-0';
		}

		//no bottom padding if singular post and comments are in separate section
		if ( is_singular( 'post') && exs_option( 'blog_single_comments_section' ) ){
			$exs_extra_padding_bottom = 'pb-0';
		}
		?>
	<div id="main" class="main <?php echo esc_attr( 'sidebar-' . $exs_main_sidebar_width . ' sidebar-gap-' . $exs_main_gap_width . ' ' . $exs_main_css_classes ); ?>">
		<div class="container <?php echo esc_attr( $exs_extra_padding_top . ' ' . $exs_extra_padding_bottom ); ?>">
			<?php
			/**
			 * Fires above columns
			 *
			 * @since ExS 1.8.2
			 */
			do_action( 'exs_action_top_of_columns' );

			//full width widget area before columns for home page
			if ( exs_is_front_page() && is_active_sidebar( 'sidebar-home-before-columns' ) ) :
				?>
				<div class="sidebar-home sidebar-home-before sidebar-home-before-columns">
					<?php dynamic_sidebar( 'sidebar-home-before-columns' ); ?>
				</div><!-- .sidebar-home-before-columns -->
			<?php endif; ?>
			<div id="columns" class="main-columns">
				<main id="col" class="<?php echo esc_attr( $exs_css_classes['main'] . ' ' . $exs_font_size ); ?>">
					<?php
					/**
					 * Fires at the top of main column.
					 *
					 * @since ExS 0.0.1
					 */
					do_action( 'exs_action_top_of_main_column' );

					endif; //full-width & 404
	if ( exs_is_front_page() && is_active_sidebar( 'sidebar-home-before-content' ) ) :
		?>
		<div class="sidebar-home sidebar-home-before sidebar-home-before-content">
			<?php dynamic_sidebar( 'sidebar-home-before-content' ); ?>
		</div><!-- .sidebar-home-before-content -->
		<?php
		endif; //exs_is_front_page
