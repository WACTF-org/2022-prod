<?php
/**
 * Template part for displaying posts
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

?>
<div class="grid-item">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="https://schema.org/Article" itemscope="itemscope">
		<?php
		exs_post_thumbnail();
		?>
		<div class="item-content">
			<?php if ( get_the_title() ) : ?>
			<header class="entry-header entry-header-small">
				<?php
				exs_sticky_post_label();
				the_title( sprintf( '<h2 class="entry-title" itemprop="headline"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
				?>
			</header><!-- .entry-header -->
			<?php endif; //get_the_title ?>

			<footer
				class="entry-footer entry-footer-top"><?php exs_entry_meta( true, true, true, false, true ); ?></footer>
			<!-- .entry-footer -->

			<div class="entry-content" itemprop="text">
				<?php

				do_action( 'exs_action_loop_before_content' );

				echo wp_kses_post( wp_trim_words( get_the_excerpt( get_the_ID() ), 15 ) );

				wp_link_pages(
					exs_get_wp_link_pages_atts()
				);
				?>
			</div><!-- .entry-content -->

			<footer
				class="entry-footer  entry-footer-bottom"><?php exs_entry_meta( false, false, false, true, false ); ?></footer>
			<!-- .entry-footer -->

		</div><!-- .item-content -->
	</article><!-- #post-<?php the_ID(); ?> -->
</div><!-- .grid-item -->
