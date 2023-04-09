<?php
/**
 * The footer section template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//this footer displays only widgets so if has no widgets - it will be hidden
if ( ! is_active_sidebar( 'sidebar-2' ) ) {
	if( is_customize_preview() ) {
		echo '<footer id="footer" class="d-none"></footer>';
	}
	return;
}

$exs_fluid = exs_option( 'footer_fluid' ) ? '-fluid' : '';

$exs_footer_background    = exs_option( 'footer_background', '' );
$exs_extra_padding_top    = exs_option( 'footer_extra_padding_top', '' );
$exs_extra_padding_bottom = exs_option( 'footer_extra_padding_bottom', '' );

$exs_border_top        = exs_option( 'footer_border_top', '' );
$exs_border_bottom     = exs_option( 'footer_border_bottom', '' );
$exs_font_size         = exs_option( 'footer_font_size', '' );
$exs_footer_layout_gap = exs_option( 'footer_layout_gap', '' );

$exs_background_image = exs_section_background_image_array( 'footer' );

$exs_extra_css_class  = '';
if ( exs_option( 'footer_sidebar_widgets_title_uppercase' ) ) {
	$exs_extra_css_class .= ' wt-uppercase';
}
if ( exs_option( 'footer_sidebar_widgets_title_bold' ) ) {
	$exs_extra_css_class .= ' wt-bold';
}
if ( exs_option( 'footer_sidebar_widgets_title_decor' ) ) {
	$exs_extra_css_class .= ' wt-decor';
}

?>
<footer id="footer"
		class="footer footer-6 text-center <?php echo esc_attr( $exs_footer_background . ' ' . $exs_font_size . ' ' . $exs_background_image['class'] . $exs_extra_css_class ); ?>"
	<?php echo ( ! empty( $exs_background_image['url'] ) ) ? 'style="background-image: url(' . esc_url( $exs_background_image['url'] ) . ');' . esc_attr( $exs_background_image['overlay'] ) . '"' : ''; ?>
>
	<?php
	if ( 'full' === $exs_border_top ) {
		?>
		<hr class="section-hr">
		<?php
	}
	?>
	<div class="container<?php echo esc_attr( $exs_fluid . ' ' . $exs_extra_padding_top . ' ' . $exs_extra_padding_bottom ); ?>">
		<?php
		if ( 'container' === $exs_border_top ) {
			?>
			<hr class="section-hr">
			<?php
		}

		?>
		<div class="layout-cols-1 <?php echo esc_attr( 'layout-gap-' . $exs_footer_layout_gap ); ?>">
			<aside class="footer-widgets one-half-only">
				<?php
				dynamic_sidebar( 'sidebar-2' );
				?>
			</aside><!-- .footer-widgets> -->
		</div>
		<?php

		if ( 'container' === $exs_border_bottom ) {
			?>
			<hr class="section-hr">
			<?php
		}
		?>
	</div><!-- .container -->
	<?php
	if ( 'full' === $exs_border_bottom ) {
		?>
		<hr class="section-hr">
		<?php
	}
	?>
</footer><!-- #footer -->
