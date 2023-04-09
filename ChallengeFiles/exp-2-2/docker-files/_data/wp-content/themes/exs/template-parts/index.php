<?php
/**
 * The main template file
 * Also used in the Customizer preview
 * It contains the index.php file but without header and footer
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
$exs_layout     = exs_get_feed_layout();
$exs_layout_gap = exs_get_feed_gap();

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

if ( ! empty( $exs_masonry ) || is_customize_preview() ) {
	wp_enqueue_script( 'masonry', '', array( 'imagesloaded' ), '', true );
}

if ( have_posts() ) :
	//check if no file with selected layout - using default layout
	$exs_layout = file_exists( EXS_THEME_PATH . '/template-parts/blog/' . $exs_layout . '/content.php' ) ? $exs_layout : 'default';
	?>
	<div id="layout" class="<?php echo esc_attr( $exs_layout_class ); ?>">
		<?php if ( ! empty( $exs_show_title ) ) : ?>
			<h1 class="archive-title">
				<span><?php get_template_part( 'template-parts/title/title-text' ); ?></span>
			</h1>
		<?php
		endif; //show_title

		/**
		 * Fires at the top of archive column.
		 *
		 * @since ExS 1.4.0
		 */
		do_action( 'exs_action_top_of_archive' );

		if ( is_category() ) :
			$exs_category_description = category_description();
			if ( ! empty( $exs_category_description ) ) {
				echo '<div class="category-description">' . wp_kses_post( $exs_category_description ) . '</div><!-- .category-description -->';
			}
		endif; //is_category

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
					//SJB view
					if ( 'jobpost' === get_post_type() && class_exists( 'Simple_Job_Board' ) ) :
						?>
						<div class="sjb-page">
							<?php
							get_simple_job_board_template('content-job-listing-list-view.php');
							?>
						</div><!-- .sjb-page -->
					<?php
					else:
						get_template_part( 'template-parts/blog/' . $exs_layout . '/content', null, array( 'columns' => $exs_columns ) );
					endif;

				endwhile;

				/**
				 * Fires at the bottom of the main loop.
				 *
				 * @since ExS 1.7.4
				 */
				do_action( 'exs_action_after_posts_loop' );

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

	/**
	 * Fires at the top of archive column.
	 *
	 * @since ExS 1.4.0
	 */
	do_action( 'exs_action_bottom_of_archive' );

	?>
	</div><!-- #layout -->
<?php
else :

	// If no content, include the "No posts found" template.
	get_template_part( 'template-parts/content', 'none' );

endif; //have_posts
