<?php
/**
 * Template Name: Home - Top and Right Sidebars
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();


/* Start the Loop */
while ( have_posts() ) :
	the_post();
	$exs_content = get_the_content();
	if ( ! empty( $exs_content ) ) :
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="https://schema.org/WebPage" itemscope="itemscope">
			<div class="entry-content">
				<?php
				the_content();

				wp_link_pages(
					exs_get_wp_link_pages_atts()
				);
				?>
			</div><!-- .entry-content -->
		</article><!-- #post-<?php the_ID(); ?> -->
		<?php
	endif; //get_the_content
endwhile; // End of the loop.

get_footer();
