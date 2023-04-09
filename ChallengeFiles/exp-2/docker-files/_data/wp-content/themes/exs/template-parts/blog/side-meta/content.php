<?php
/**
 * Template part for displaying posts in side meta layout
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.9.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'side-item' ); ?> itemtype="https://schema.org/Article" itemscope="itemscope">
	<footer class="entry-footer entry-footer-side entry-footer-top"><?php exs_entry_meta( true, true, true, true, true ); ?></footer>
	<!-- .entry-footer -->
	<div class="item-content-side">
		<?php if ( get_the_title() ) : ?>
		<header class="entry-header">
			<?php
			exs_sticky_post_label();
			the_title( sprintf( '<h2 class="entry-title" itemprop="headline"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			?>
		</header><!-- .entry-header -->
		<?php endif; //get_the_title ?>
		<?php exs_post_thumbnail(); ?>
		<div class="entry-content mt-15" itemprop="text">
			<?php

			do_action( 'exs_action_loop_before_content' );

			$exs_show_full_text = is_search() ? exs_option( 'search_show_full_text', false ) : exs_option( 'blog_show_full_text', false );

			if ( empty( $exs_show_full_text ) ) :

				the_excerpt();

			else :

				the_content(
					exs_read_more_inside_link_markup()
				);

			endif; // show_full_text

			wp_link_pages(
				exs_get_wp_link_pages_atts()
			);
			?>
		</div><!-- .entry-content -->

	</div><!-- .item-content -->
</article><!-- #post-<?php the_ID(); ?> -->
