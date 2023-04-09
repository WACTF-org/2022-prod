<?php
/**
 * The main template file
 * Also used in the Customizer preview
 * It is duplicating index.php file but without header and footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$exs_show_title = exs_get_feed_shot_title();
$exs_layout     = exs_option( 'search_layout', '' ) ? exs_option( 'search_layout', '' ) : 'default';
$exs_layout_gap = exs_option( 'search_layout_gap', '' ) ? exs_option( 'search_layout_gap', '' ) : '';

//layout may contain columns count separated by space and 'masonry' word after columns count
$exs_layout         = explode( ' ', $exs_layout );
$exs_columns_number = ( ! empty( $exs_layout[1] ) ) ? absint( $exs_layout[1] ) : '';
$exs_masonry        = ( ! empty( $exs_layout[2] ) && 'masonry' === $exs_layout[2] ) ? true : false;
$exs_grid_class     = ( ! empty( $exs_masonry ) ) ? 'masonry' : 'grid-wrapper';
$exs_layout         = $exs_layout[0];
$exs_columns        = ( ! empty( $exs_columns_number ) ) ? true : false;

//additional css classes for #layout div element
$exs_layout_class  = 'layout-' . $exs_layout;
$exs_layout_class .= ! empty( $exs_columns ) ? ' layout-cols-' . $exs_columns_number : ' layout-cols-1';
$exs_layout_class .= ! empty( $exs_layout_gap ) ? ' layout-gap-' . $exs_layout_gap : ' layout-gap-default';

if ( ! empty( $exs_masonry ) ) {
	wp_enqueue_script( 'masonry', '', array( 'imagesloaded' ), '', true );
}

$exs_special_cats = exs_get_special_categories_from_options();

//check if no file with selected layout - using default layout
$exs_layout = file_exists( EXS_THEME_PATH . '/template-parts/blog/' . $exs_layout . '/content.php' ) ? $exs_layout : 'default';

?>
	<div id="layout" class="layout-search <?php echo esc_attr( $exs_layout_class ); ?>">
		<?php if ( ! empty( $exs_show_title ) ) : ?>
			<h1><?php get_template_part( 'template-parts/title/title-text' ); ?></h1>
		<?php
		endif; //show_title
		?>
		<div class="mb-3 search-form-wrap">
			<?php get_search_form(); ?>
		</div>
		<?php
		if ( have_posts() ) {

			if ( ! empty( $exs_columns ) ) :
				// read about masonry layout here:
				// https://masonry.desandro.com/options.html
				// https://github.com/desandro/masonry/issues/549
				?>
				<div class="grid-columns-wrapper">
				<div class="<?php echo esc_attr( $exs_grid_class ); ?>">
				<div class="grid-sizer"></div>
			<?php
			endif; //columns

			// Load posts loop.
			while ( have_posts() ) :
				the_post();
				if ( 'product' === get_post_type() && function_exists( 'wc_get_template' ) ) :

					if ( ! empty( $exs_columns ) ) :
						echo '<div class="grid-item">';
					endif;

					?>
					<div class="woo woocommerce columns-1">
						<ul class="products search-results">
							<?php
							wc_get_template( 'content-product.php' );
							?>
						</ul>
					</div>
				<?php
					if ( ! empty( $exs_columns ) ) :
						echo '</div>';
					endif;
				elseif ( 'job_listing' === get_post_type() && function_exists( 'job_listing_class' ) ) :

					global $post;

					if ( ! empty( $exs_columns ) ) :
						echo '<div class="grid-item">';
					endif;
					?>
					<article>
						<ul class="job_listings">
							<li <?php job_listing_class(); ?> data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>">
								<a href="<?php the_job_permalink(); ?>">
									<?php the_company_logo(); ?>
									<div class="position">
										<h3><?php wpjm_the_job_title(); ?></h3>
										<div class="company">
											<?php the_company_name( '<strong>', '</strong> ' ); ?>
											<?php the_company_tagline( '<span class="tagline">', '</span>' ); ?>
										</div>
									</div>
									<div class="location">
										<?php the_job_location( false ); ?>
									</div>
									<ul class="meta">
										<?php do_action( 'job_listing_meta_start' ); ?>

										<?php if ( get_option( 'job_manager_enable_types' ) ) { ?>
											<?php $types = wpjm_get_the_job_types(); ?>
											<?php if ( ! empty( $types ) ) : foreach ( $types as $type ) : ?>
												<li class="job-type <?php echo esc_attr( sanitize_title( $type->slug ) ); ?>"><?php echo esc_html( $type->name ); ?></li>
											<?php endforeach; endif; ?>
										<?php } ?>

										<li class="date"><?php the_job_publish_date(); ?></li>

										<?php do_action( 'job_listing_meta_end' ); ?>
									</ul>
								</a>
							</li>
						</ul>
					</article>
				<?php
					if ( ! empty( $exs_columns ) ) :
						echo '</div>';
					endif;

				elseif ( 'jobpost' === get_post_type() && class_exists( 'Simple_Job_Board' ) ) :
					if ( ! empty( $exs_columns ) ) :
						echo '<div class="grid-item">';
					endif;
					?>
					<article class="sjb-page entry-content">
						<?php
						get_simple_job_board_template('content-job-listing-list-view.php');
						?>
					</article><!-- .sjb-page -->
				<?php
					if ( ! empty( $exs_columns ) ) :
						echo '</div>';
					endif;
				//regular post //exclude special categories
				elseif ( 'post' === get_post_type() && ! in_category( $exs_special_cats, get_the_ID() ) ):
					get_template_part( 'template-parts/blog/' . $exs_layout . '/content' );
				else :
					if ( ! empty( $exs_columns ) ) :
						echo '<div class="grid-item">';
					endif;
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php

						the_title( '<header class="entry-header"><h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2></header>' );

						if (
							'post' === get_post_type()
							&&
							//exclude special categories
							! in_category( $exs_special_cats, get_the_ID() )
						) :
							?>
							<footer class="entry-footer"><?php exs_entry_meta(); ?></footer><!-- .entry-footer -->
						<?php
						endif; //'post'

						the_excerpt();
						?>
					</article><!-- #post-<?php the_ID(); ?> -->
				<?php
					if ( ! empty( $exs_columns ) ) :
						echo '</div>';
					endif;
				endif;
			endwhile;

			if ( ! empty( $exs_columns ) ) :
				?>
				</div><!-- .<?php echo esc_html( $exs_grid_class ); ?>-->
				</div><!-- .grid-columns-wrapper -->
			<?php
			endif; //columns

			// Previous/next page navigation.
			the_posts_pagination(
				exs_get_the_posts_pagination_atts()
			);

		} else {

			// If no content, include the "No posts found" template.
			get_template_part( 'template-parts/content', 'none' );

		}
		?>
	</div><!-- #layout -->
<?php
