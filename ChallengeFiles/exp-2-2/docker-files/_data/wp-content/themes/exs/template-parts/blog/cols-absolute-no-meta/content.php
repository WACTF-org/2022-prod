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

$exs_css_class = ( ! exs_has_post_thumbnail() ) ? 'no-post-thumbnail content-absolute-no-image' : 'content-absolute';

?>
<div class="grid-item">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="https://schema.org/Article" itemscope="itemscope">
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

			</div><!-- .overlap-content -->
		</div><!-- <?php echo esc_attr( $exs_css_class ); ?> -->
	</article><!-- #post-<?php the_ID(); ?> -->
</div><!-- .grid-item -->
