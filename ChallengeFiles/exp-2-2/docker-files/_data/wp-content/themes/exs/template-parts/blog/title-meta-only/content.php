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
<article id="post-<?php the_ID(); ?>" <?php post_class( 'title-only' ); ?> itemtype="https://schema.org/Article" itemscope="itemscope">
	<footer class="entry-footer entry-footer-top"><?php exs_entry_meta( true, true, true, true, true ); ?></footer>
		<!-- .entry-footer -->
	<?php if ( get_the_title() ) : ?>
	<header class="entry-header">
		<?php
		exs_sticky_post_label();
		the_title( sprintf( '<h3 class="entry-title icon-inline" itemprop="headline">%s<a href="%s" rel="bookmark">', exs_icon( 'file-document-outline', true ), esc_url( get_permalink() ) ), '</a></h3>' );
		?>
	</header><!-- .entry-header -->
	<?php endif; //get_the_title ?>
</article><!-- #post-<?php the_ID(); ?> -->
<?php if ( $columns ) : ?>
</div><!--.grid-item-->
<?php endif; //columns ?>

