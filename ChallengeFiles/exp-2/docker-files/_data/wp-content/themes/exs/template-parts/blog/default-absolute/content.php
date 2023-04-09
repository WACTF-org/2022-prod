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

$exs_has_thumbnail = exs_has_post_thumbnail();
$exs_css_class     = ( ! $exs_has_thumbnail ) ? 'no-post-thumbnail content-absolute-no-image' : 'content-absolute';

$args = ! empty( $args ) ? $args : array();
$columns = ! empty( $args['columns'] );

if ( $columns ) :
?>
<div class="grid-item">
<?php endif; //columns ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="https://schema.org/Article" itemscope="itemscope">
	<?php
	if ( ! empty( $exs_has_thumbnail ) ) :
		?>

		<div class="<?php echo esc_attr( $exs_css_class ); ?>">
			<?php
			exs_post_thumbnail();
			?>
			<div class="overlap-content">
				<?php if ( get_the_title() ) : ?>
					<header class="entry-header">
						<?php
						exs_sticky_post_label();
						the_title( sprintf( '<h2 class="entry-title" itemprop="headline"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
						?>
					</header><!-- .entry-header -->
				<?php endif; //get_the_title ?>
				<footer
					class="entry-footer entry-footer-top"><?php exs_entry_meta( true, true, true, false, true ); ?></footer>
				<!-- .entry-footer -->
			</div><!-- .overlap-content -->
		</div><!-- <?php echo esc_attr( $exs_css_class ); ?> -->


		<div class="item-content">
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
		<?php
		//no thumbnail
		else :
			?>
			<?php if ( get_the_title() ) : ?>
		<header class="entry-header">
				<?php
				exs_sticky_post_label();
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
				?>
		</header><!-- .entry-header -->
		<?php endif; //get_the_title ?>
		<footer
			class="entry-footer entry-footer-top"><?php exs_entry_meta( true, true, true, false, true ); ?></footer><!-- .entry-footer -->

		<div class="entry-content">
			<?php
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
			class="entry-footer  entry-footer-bottom"><?php exs_entry_meta( false, false, false, true, false ); ?></footer><!-- .entry-footer -->

	<?php endif; //has_thumbnail ?>
</article><!-- #post-<?php the_ID(); ?> -->
<?php if ( $columns ) : ?>
	</div><!--.grid-item-->
<?php endif; //columns ?>

