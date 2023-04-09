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

$exs_side_item = ( ! exs_has_post_thumbnail() ) ? '' : 'side-item';
//_ve($wp_query);
if ( ! empty( $wp_query ) ) {
	$exs_side_item .= ( ( $wp_query->current_post + 1 ) % 2 ) ? ' article-odd' : ' article-even';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $exs_side_item ); ?> itemtype="https://schema.org/Article" itemscope="itemscope">
	<?php
	exs_post_thumbnail( 'exs-square' );
	?>
	<div class="item-content">
		<footer class="entry-footer entry-footer-top"><?php exs_entry_meta( true, true, true, false, true ); ?></footer>
		<!-- .entry-footer -->
		<?php if ( get_the_title() ) : ?>
		<header class="entry-header">
			<?php
			exs_sticky_post_label();
			the_title( sprintf( '<h2 class="entry-title" itemprop="headline"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			?>
		</header><!-- .entry-header -->
		<?php endif; //get_the_title ?>

		<div class="entry-content" itemprop="text">
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

		<footer
			class="entry-footer  entry-footer-bottom"><?php exs_entry_meta( false, false, false, true, false ); ?></footer>
		<!-- .entry-footer -->

	</div><!-- .item-content -->
</article><!-- #post-<?php the_ID(); ?> -->
