<?php
/**
 * The header-bottom section template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.8.10
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//this section displays only widgets so if has no widgets - it will be hidden
if ( ! is_active_sidebar( 'sidebar-header-bottom' ) ) {
	if( is_customize_preview() ) {
		echo '<section id="header-bottom" class="d-none"></section>';
	}
	return;
}

$exs_fluid = exs_option( 'header_bottom_fluid' ) ? '-fluid' : '';

$exs_header_bottom_hidden_class  = exs_option( 'header_bottom_hidden', '' );
$exs_header_bottom_background    = exs_option( 'header_bottom_background', '' );
$exs_extra_padding_top    = exs_option( 'header_bottom_extra_padding_top', '' );
$exs_extra_padding_bottom = exs_option( 'header_bottom_extra_padding_bottom', '' );

$exs_border_top        = exs_option( 'header_bottom_border_top', '' );
$exs_border_bottom     = exs_option( 'header_bottom_border_bottom', '' );
$exs_font_size         = exs_option( 'header_bottom_font_size', '' );
$exs_header_bottom_layout_gap = exs_option( 'header_bottom_layout_gap', '' );

$exs_background_image = exs_section_background_image_array( 'header_bottom' );

$exs_extra_css_class  = '';
if ( exs_option( 'header_bottom_hide_widget_titles' ) ) {
	$exs_extra_css_class .= ' wt-hide';
}
if ( exs_option( 'header_bottom_lists_inline' ) ) {
	$exs_extra_css_class .= ' lists-inline';
}

?>
<section id="header-bottom"
		class="header-bottom header-bottom-5 text-center <?php echo esc_attr(  $exs_header_bottom_hidden_class . ' ' . $exs_header_bottom_background . ' ' . $exs_font_size . ' ' . $exs_background_image['class'] . $exs_extra_css_class ); ?>"
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
		<div class="layout-cols-1 <?php echo esc_attr( 'layout-gap-' . $exs_header_bottom_layout_gap ); ?>">
			<aside class="header-bottom-widgets cols-1">
				<?php
				dynamic_sidebar( 'sidebar-header-bottom' );
				?>
			</aside><!-- .header-bottom-widgets> -->
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
</section><!-- #header-bottom -->
