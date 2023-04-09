<?php
/**
 * Template Name: No sidebar, No title
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

/* Start the Loop */
while ( have_posts() ) :
	the_post();
	?>
	<div id="layout" class="layout-page">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="https://schema.org/WebPage" itemscope="itemscope">
			<div class="entry-content" itemprop="text">
				<?php
				the_content();

				wp_link_pages(
					exs_get_wp_link_pages_atts()
				);
				?>
			</div><!-- .entry-content -->
		</article><!-- #post-<?php the_ID(); ?> -->
		<?php

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	</div><!-- #layout -->
	<?php
endwhile; // End of the loop.
get_footer();
