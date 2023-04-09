<?php
/**
 * The template used for displaying a download's content.
 * Loaded by single-download.php
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
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="https://schema.org/Product" itemscope="itemscope">
		<?php

		exs_post_thumbnail();

		?>
		<div class="item-content">
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

			<div class="entry-content" itemprop="text">
				<?php

				the_content();

				wp_link_pages(
					exs_get_wp_link_pages_atts()
				);
				?>
			</div><!-- .entry-content -->

		</div><!-- .item-content -->
	</article><!-- #post-<?php the_ID(); ?> -->
<?php

// If comments are open or we have at least one comment, load up the comment template.
if ( comments_open() || get_comments_number() ) {
	comments_template();
}
