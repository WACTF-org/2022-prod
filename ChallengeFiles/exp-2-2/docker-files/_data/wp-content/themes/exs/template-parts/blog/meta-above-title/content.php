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

$args = ! empty( $args ) ? $args : array();
$columns = ! empty( $args['columns'] );

if ( $columns ) :
?>
<div class="grid-item">
<?php endif; //columns ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="https://schema.org/Article" itemscope="itemscope">
	<?php
	exs_post_thumbnail();
	?>
	<div class="item-content">
		<footer class="entry-footer entry-footer-top"><?php exs_entry_meta( false, false, true, false, true ); ?></footer>
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

		<?php
		echo '<footer class="entry-footer entry-footer-bottom last-right">';
		exs_entry_meta( true, false, false, true, false );
		exs_entry_meta( false, true, false, false, false );
		echo '</footer>';
		?>
		<!-- .entry-footer -->
	</div><!-- .item-content -->
</article><!-- #post-<?php the_ID(); ?> -->
<?php  if ( $columns ) : ?>
	</div><!--.grid-item -->
<?php endif; //columns