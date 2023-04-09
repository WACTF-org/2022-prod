<?php
/**
 * The copyright section template file
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

$exs_fluid = exs_option( 'copyright_fluid' ) ? '-fluid' : '';

$exs_text = exs_option( 'copyright_text', '' );
if ( empty( $exs_text ) ) {
	$exs_text = get_bloginfo( 'name', 'display' );
}

$exs_copyright_background = exs_option( 'copyright_background', '' );
$exs_extra_padding_top    = exs_option( 'copyright_extra_padding_top' );
$exs_extra_padding_bottom = exs_option( 'copyright_extra_padding_bottom' );
$exs_font_size            = exs_option( 'copyright_font_size', '' );

$exs_background_image = exs_section_background_image_array( 'copyright' );
?>
<div id="copyright" class="copyright <?php echo esc_attr( $exs_copyright_background . ' ' . $exs_font_size . ' ' . $exs_background_image['class'] ); ?>"
	<?php echo ( ! empty( $exs_background_image['url'] ) ) ? 'style="background-image: url(' . esc_url( $exs_background_image['url'] ) . ');' . esc_attr( $exs_background_image['overlay'] ) . '"' : ''; ?>
>
	<div class="container<?php echo esc_attr( $exs_fluid . ' ' . $exs_extra_padding_top . ' ' . $exs_extra_padding_bottom ); ?>">
		<div class="cols-2">

			<div class="copyright-text">
				<?php echo wp_kses_post( exs_get_copyright_text( $exs_text ) ); ?>
			</div>
			<?php

			if ( has_nav_menu( 'copyright' ) ) :
				?>
				<nav class="copyright-navigation" aria-label="<?php esc_attr_e( 'Copyright Menu', 'exs' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'copyright',
							'menu_class'     => 'copyright-menu',
							'depth'          => 1,
							'container'      => false,
						)
					);
					?>
				</nav><!-- .copyright-navigation -->
			<?php endif; ?>
		</div><!-- .cols-2 -->
	</div><!-- .container -->

</div><!-- #copyright -->
