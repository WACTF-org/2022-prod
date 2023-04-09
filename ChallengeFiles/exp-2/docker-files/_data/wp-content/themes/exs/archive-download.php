<?php
/**
 * Downloads archive page.
 * This is used by default unless EDD_DISABLE_ARCHIVE is set to true.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.6
 */

$exs_show_title = exs_get_feed_shot_title();

get_header();

if ( have_posts() ) :
	?>
	<div id="layout" class="layout-downloads <?php echo esc_attr( exs_edd_downloads_list_wrapper_classes() ); ?>">
		<?php if ( ! empty( $exs_show_title ) ) : ?>
			<h1 class="archive-title">
				<span><?php get_template_part( 'template-parts/title/title-text' ); ?></span>
			</h1>
		<?php
		endif; //show_title

		// Load posts loop.
		while ( have_posts() ) :

			the_post();
			get_template_part( 'template-parts/edd/download-grid' );

		endwhile;

		echo '<div class="clearfix"></div>';
		/**
		 * Download pagination
		 */
		exs_edd_download_nav();

	?>
	</div><!-- #layout -->
<?php
else :

	// If no content, include the "No posts found" template.
	get_template_part( 'template-parts/content', 'none' );

endif; //have_posts

get_footer();
