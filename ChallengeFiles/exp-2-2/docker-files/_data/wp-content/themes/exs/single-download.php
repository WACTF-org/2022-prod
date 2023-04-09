<?php
/**
 * The template for displaying all single downloads
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

if ( have_posts() ) :

	/* Start the Loop */
	while ( have_posts() ) :
		the_post();
		?>
		<div id="layout" class="layout-<?php echo esc_attr( 'single-download' ); ?>">
			<?php
			get_template_part( 'template-parts/edd/content', 'download' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>
		</div><!-- #layout -->
		<?php

	endwhile; // End of the loop.

	do_action( 'exs_edd_single_download_primary_end' );

	else :

	// If no content, include the "No posts found" template.
	get_template_part( 'template-parts/content', 'none' );

endif; //have_posts

	get_footer();
