<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
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
			<div id="layout" class="layout-job_listing">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="https://schema.org/Article" itemscope="itemscope">
					<?php

					$exs_show_title = ! exs_option( 'title_show_title', '' ) && get_the_title();
					if ( $exs_show_title ) :
						?>
						<header class="entry-header">
							<?php the_title( '<h1 class="entry-title" itemprop="headline"><span>', '</span></h1>' ); ?>
						</header>
					<?php
					else :
						echo '<h4 class="hidden" itemscope="itemscope" itemprop="headline" itemtype="https://schema.org/Text">' . esc_html( get_the_title() ) . '</h4>';
					endif; //show_title
					?>
					<div class="entry-content" itemprop="text">
						<?php

						the_content();

						wp_link_pages(
							exs_get_wp_link_pages_atts()
						);
						?>
					</div><!-- .entry-content -->
				</article><!-- #post-<?php the_ID(); ?> -->
			</div><!-- #layout -->
			<?php

	endwhile; // End of the loop.

	else :

		// If no content, include the "No posts found" template.
		get_template_part( 'template-parts/content', 'none' );

endif; //have_posts

	get_footer();
