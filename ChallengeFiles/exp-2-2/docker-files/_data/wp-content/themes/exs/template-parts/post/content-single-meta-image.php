<?php
/**
 * The template for displaying all default single posts
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

$exs_thumbnail = ! ( post_password_required() || is_attachment() || ! has_post_thumbnail() || ( '6' === exs_option( 'title' ) ) );
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="https://schema.org/Article" itemscope="itemscope">
		<?php
		if ( ! empty( $exs_thumbnail ) ) :
			?>
			<div class="content-absolute">
				<?php
				exs_post_thumbnail();
				?>
				<div class="overlap-content">
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

					<footer class="entry-footer entry-footer-top"><?php exs_entry_meta( true, true, true, false, true ); ?></footer>
					<!-- .entry-footer -->
				</div><!-- .overlap-content -->
			</div><!-- .content-absolute -->
			<?php
				//no thumbnail
			else :
				$exs_show_title = ! exs_option( 'title_show_title', '' ) && get_the_title();
				if ( $exs_show_title ) :
					?>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title" itemprop="headline"><span>', '</span></h1>' ); ?>
				</header>
			<?php endif; //show_title ?>
			<footer
				class="entry-footer entry-footer-top"><?php exs_entry_meta( true, true, true, false, true ); ?></footer><!-- .entry-footer -->
		<?php endif; //thumbnail ?>
		<div class="item-content">
			<div class="entry-content" itemprop="text">
				<?php

				the_content();

				wp_link_pages(
					exs_get_wp_link_pages_atts()
				);
				?>
			</div><!-- .entry-content -->

			<footer
				class="entry-footer entry-footer-bottom"><?php exs_entry_meta( false, false, false, true, false ); ?></footer>
			<!-- .entry-footer -->
		</div><!-- .item-content -->
	</article><!-- #post-<?php the_ID(); ?> -->
<?php

exs_related_posts( get_the_ID() );

get_template_part( 'template-parts/post/bio' );

exs_post_nav();

// If comments are open or we have at least one comment, load up the comment template.
if ( comments_open() || get_comments_number() ) {
	comments_template();
}
