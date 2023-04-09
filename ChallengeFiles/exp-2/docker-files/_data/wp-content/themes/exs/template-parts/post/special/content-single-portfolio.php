<?php
/**
 * The template for displaying all single portfolio posts
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

//columns for single service if other portfolio exists
$exs_parent_portfolio_category = exs_option( 'category_portfolio', '' );
$exs_categories                = wp_list_categories(
	array(
		'child_of'         => $exs_parent_portfolio_category,
		'echo'             => '',
		'title_li'         => '',
		'show_option_none' => '',
	)
);
$exs_widget                    = exs_get_the_widget(
	'ExS_Widget_Theme_Posts',
	array(
		'title'        => esc_html__( 'Recent projects', 'exs' ),
		'number'       => '3',
		'layout'       => 'cols-absolute 3',
		'gap'          => '10',
		'category'     => $exs_parent_portfolio_category,
		'post__not_in' => array( get_the_ID() ),
	)
);
?>
<article
	id="post-<?php the_ID(); ?>" <?php post_class( 'single-special single-portfolio' ); ?>
	itemtype="https://schema.org/CreativeWork" itemscope="itemscope">
	<?php
	$exs_show_title = ! exs_option( 'title_show_title', '' ) && get_the_title();
	if ( $exs_show_title ) :
		?>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title" itemprop="headline"><span>', '</span></h1>' ); ?>
		</header>
		<?php
	endif; //show_title
	exs_post_thumbnail( '', '' );
	?>
	<div class="item-content">
		<div class="entry-content" itemprop="text">
			<?php
			the_content( '', true );

			wp_link_pages(
				exs_get_wp_link_pages_atts()
			);
			?>
		</div><!-- .entry-content -->
	</div><!-- .item-content -->
</article><!-- #post-<?php the_ID(); ?> -->
<?php if ( $exs_categories ) : ?>
	<aside class="related-categories mt-4">
		<div class="widgets-wrap">
			<?php if ( ! empty( $exs_categories ) ) : ?>
				<div class="widget widget_categories">
					<h3 class="widget-title">
						<?php esc_html_e( 'Portfolio categories', 'exs' ); ?>
					</h3>
					<ul>
						<?php echo wp_kses_post( $exs_categories ); ?>
					</ul>
				</div>
			<?php endif; //categories ?>
		</div>
	</aside><!-- .column-aside -->
	<?php
endif; // categories

if ( $exs_widget ) :
	echo '<div class="mt-4">' . wp_kses_post( $exs_widget ) . '</div>';
endif; //widget
