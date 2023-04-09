<?php
/**
 * Template part for displaying site bottom part, including Top Footer section, Footer and Copyright sections
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.9.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$exs_background_image = exs_section_background_image_array( 'bottom' );

/**
 * Fires below #main and before #bottom-wrap.
 *
 * @since ExS 1.2.0
 */
do_action( 'exs_action_before_bottom_wrap' );

//wrapper for top footer, footer and copyright section
$exs_container_width = exs_option( 'main_container_width', '1140' );
?>
<div id="bottom-wrap"
	 class="container-<?php echo esc_attr( $exs_container_width . ' ' . $exs_background_image['class'] ); ?>"
	<?php echo ( ! empty( $exs_background_image['url'] ) ) ? 'style="background-image: url(' . esc_url( $exs_background_image['url'] ) . ');' . esc_attr( $exs_background_image['overlay'] ) . '"' : ''; ?>
>
<?php
get_template_part( 'template-parts/footer-top/section', exs_template_part( 'footer_top', '' ) );

get_template_part( 'template-parts/footer/footer', exs_template_part( 'footer', '1' ) );

get_template_part( 'template-parts/copyright/copyright', exs_template_part( 'copyright', '1' ) );

get_template_part( 'template-parts/footer/bottom-nav' );

?>
</div><!-- #bottom-wrap -->
