<?php
/**
 * The template for displaying single download
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$download_grid_options = exs_edd_download_grid_options();
?>

<div class="<?php echo esc_attr( apply_filters( 'exs_edd_download_class', 'edd_download', get_the_ID(), '', '' ) ); ?>" id="edd_download_<?php the_ID(); ?>">

	<div class="edd_download_inner">

		<?php

		do_action( 'exs_edd_download_before' );

		if ( true === $download_grid_options['thumbnails'] ) {
			edd_get_template_part( 'shortcode', 'content-image' );
			do_action( 'exs_edd_download_after_thumbnail' );
		}

		do_action( 'exs_edd_download_before_title' );

		if ( true === $download_grid_options['title'] ) {
			edd_get_template_part( 'shortcode', 'content-title' );
		}

		do_action( 'exs_edd_download_after_title' );

		if ( true === $download_grid_options['excerpt'] && true !== $download_grid_options['full_content'] ) {
			edd_get_template_part( 'shortcode', 'content-excerpt' );
			do_action( 'exs_edd_download_after_content' );
		} elseif ( true === $download_grid_options['full_content'] ) {
			edd_get_template_part( 'shortcode', 'content-full' );
			do_action( 'exs_edd_download_after_content' );
		}

		exs_edd_download_footer();

		do_action( 'exs_edd_download_after' );

		?>

	</div>
</div>
