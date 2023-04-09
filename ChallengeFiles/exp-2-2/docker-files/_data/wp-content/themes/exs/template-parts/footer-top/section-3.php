<?php
/**
 * The footer top section template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//text
$exs_footer_top_image       = exs_option( 'footer_top_image', '' );
$exs_footer_top_pre_heading = exs_option( 'footer_top_pre_heading', '' );
$exs_footer_top_heading     = exs_option( 'footer_top_heading', '' );
$exs_footer_top_description = exs_option( 'footer_top_description', '' );
$exs_footer_top_shortcode   = exs_option( 'footer_top_shortcode', '' );

if (
	empty( $exs_footer_top_image )
	&&
	empty( $exs_footer_top_pre_heading )
	&&
	empty( $exs_footer_top_heading )
	&&
	empty( $exs_footer_top_description )
	&&
	empty( $exs_footer_top_shortcode )
	&&
	( ! is_active_sidebar( 'sidebar-3' ) )
) {
	if( is_customize_preview() ) {
		echo '<section id="footer-top" class="d-none"></section>';
	}
	return;
}


$exs_fluid = exs_option( 'footer_top_fluid' ) ? '-fluid' : '';

$exs_footer_top_background = exs_option( 'footer_top_background', '' );
$exs_extra_padding_top     = exs_option( 'footer_top_extra_padding_top', '' );
$exs_extra_padding_bottom  = exs_option( 'footer_top_extra_padding_bottom', '' );

$exs_border_top    = exs_option( 'footer_top_border_top', '' );
$exs_border_bottom = exs_option( 'footer_top_border_bottom', '' );
$exs_font_size     = exs_option( 'footer_top_font_size', '' );

$exs_background_image = exs_section_background_image_array( 'footer_top' );

//animation
//animate an__XXX
//footer_top_heading_animation
//footer_top_description_animation
//footer_top_button_first_animation
//footer_top_button_second_animation
//footer_top_shortcode_animation
$exs_footer_top_pre_heading_animation   = exs_option( 'footer_top_pre_heading_animation', '' ) ? 'animate an__' . exs_option( 'footer_top_pre_heading_animation' ) : '';
$exs_footer_top_heading_animation       = exs_option( 'footer_top_heading_animation', '' ) ? 'animate an__' . exs_option( 'footer_top_heading_animation' ) : '';
$exs_footer_top_description_animation   = exs_option( 'footer_top_description_animation', '' ) ? 'animate an__' . exs_option( 'footer_top_description_animation' ) : '';
$exs_footer_top_button_first_animation  = exs_option( 'footer_top_button_first_animation', '' ) ? 'animate an__' . exs_option( 'footer_top_button_first_animation' ) : '';
$exs_footer_top_button_second_animation = exs_option( 'footer_top_button_second_animation', '' ) ? 'animate an__' . exs_option( 'footer_top_button_second_animation' ) : '';
$exs_footer_top_shortcode_animation     = exs_option( 'footer_top_shortcode_animation', '' ) ? 'animate an__' . exs_option( 'footer_top_shortcode_animation' ) : '';

?>
<section id="footer-top"
		class="footer-top footer-top-3 <?php echo esc_attr( $exs_footer_top_background . ' ' . $exs_font_size . ' ' . $exs_background_image['class'] ); ?>"
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
		<div class="layout-cols-2 layout-gap-10">
			<div class="grid-wrapper">
				<div class="grid-item">
					<?php

					//top footer image
					if ( ! empty( $exs_footer_top_image ) ) {
						echo '<img src="' . $exs_footer_top_image . '" alt="' . esc_attr( $exs_footer_top_heading ) . '">';
					}

					if ( ! empty( $exs_footer_top_pre_heading ) ) :
						$exs_footer_top_pre_heading_mt = exs_option( 'footer_top_pre_heading_mt', '' );
						$exs_footer_top_pre_heading_mb = exs_option( 'footer_top_pre_heading_mb', '' );
						?>
						<p class="footer_top-pre-heading has-medium-font-size <?php echo esc_attr( $exs_footer_top_pre_heading_animation . ' ' . $exs_footer_top_pre_heading_mt . ' ' . $exs_footer_top_pre_heading_mb ); ?>">
							<?php echo wp_kses_post( $exs_footer_top_pre_heading ); ?>
						</p>
					<?php
					endif; //footer_top_heading

					if ( ! empty( $exs_footer_top_heading ) ) :
						$exs_footer_top_heading_mt = exs_option( 'footer_top_heading_mt', '' );
						$exs_footer_top_heading_mb = exs_option( 'footer_top_heading_mb', '' );
						?>
						<h3 class="footer_top-heading <?php echo esc_attr( $exs_footer_top_heading_animation . ' ' . $exs_footer_top_heading_mt . ' ' . $exs_footer_top_heading_mb ); ?>">
							<?php echo wp_kses_post( $exs_footer_top_heading ); ?>
						</h3>
					<?php
					endif; //footer_top_heading

					if ( ! empty( $exs_footer_top_description ) ) :
						$exs_footer_top_description_mt = exs_option( 'footer_top_description_mt', '' );
						$exs_footer_top_description_mb = exs_option( 'footer_top_description_mb', '' );
						?>
						<div
							class="footer_top-description <?php echo esc_attr( $exs_footer_top_description_animation . ' ' . $exs_footer_top_description_mt . ' ' . $exs_footer_top_description_mb ); ?>">
							<?php echo wp_kses_post( $exs_footer_top_description ); ?>
						</div>
					<?php
					endif; //footer_top_description

					?>
				</div><!-- .grid-item -->
				<div class="grid-item">
					<?php
					if ( ! empty( $exs_footer_top_shortcode ) ) :
						$exs_footer_top_shortcode_mt = exs_option( 'footer_top_shortcode_mt', '' );
						$exs_footer_top_shortcode_mb = exs_option( 'footer_top_shortcode_mb', '' );
						?>
						<div
							class="footer_top-shortcode <?php echo esc_attr( $exs_footer_top_shortcode_animation . ' ' . $exs_footer_top_shortcode_mt . ' ' . $exs_footer_top_shortcode_mb ); ?>">
							<?php echo do_shortcode( $exs_footer_top_shortcode ); ?>
						</div>
					<?php
					endif; //footer_top_shortcode

					?>
				</div><!-- .grid-item -->
			</div><!-- .grid-wrapper -->
		</div>
		<?php
		if ( is_active_sidebar( 'sidebar-3' ) ) :
			global $sidebars_widgets;
			$exs_footer_widgets_count = ! empty( $sidebars_widgets['sidebar-3'] ) ? count( $sidebars_widgets['sidebar-3'] ) : '1';
			?>
			<div class="layout-cols-<?php echo esc_attr( $exs_footer_widgets_count . ' layout-gap-30' ); ?>">
				<aside class="footer-widgets grid-wrapper">
					<?php
					dynamic_sidebar( 'sidebar-3' );
					?>
				</aside><!-- .footer-widgets> -->
			</div>
			<?php
		endif; //sidebar-3
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
</section><!-- #footer -->
