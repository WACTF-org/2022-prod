<?php
/**
 * The template for displaying all single team posts
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

//columns for single team if other team members exists
$exs_widget = exs_get_the_widget(
	'ExS_Widget_Theme_Posts',
	array(
		'title'        => esc_html__( 'Other team members', 'exs' ),
		'number'       => '50',
		'category'     => exs_option( 'category_team', '' ),
		'post__not_in' => array( get_the_ID() ),
	)
);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-special single-team' ); ?> itemtype="https://schema.org/Person" itemscope="itemscope">
	<?php
	$exs_show_title = ! exs_option( 'title_show_title', '' ) && get_the_title();
	if ( $exs_show_title ) :
		?>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title" itemprop="headline"><span>', '</span></h1>' ); ?>
		</header>
	<?php endif; //show_title ?>
	<div class="entry-content" itemprop="text">
		<?php
		if ( ! ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) ) :
			?>
			<div class="alignleft">
				<?php exs_post_thumbnail( 'medium' ); ?>
			</div><!-- .alignflet -->
			<?php
		endif; //post_thumbnail
		the_content( '', true );

		wp_link_pages(
			exs_get_wp_link_pages_atts()
		);
		?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
<?php
if ( $exs_widget ) :
	echo '<div class="mt-4">' . wp_kses_post( $exs_widget ) . '</div>';
endif; //widget
