<?php
/**
 * The template to display post author bio
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$exs_author_id = get_the_author_meta( 'ID' );

$exs_show_bio   = exs_option( 'blog_single_show_author_bio', true );
$exs_author_bio = get_the_author_meta( 'description', $exs_author_id );

if ( empty( $exs_show_bio ) || empty( $exs_author_bio ) ) {
	return;
}

//SEO additional fields
$exs_twitter_username = get_the_author_meta( 'twitter', $exs_author_id );
$exs_facebook_url     = get_the_author_meta( 'facebook', $exs_author_id );
$exs_googleplus_url   = get_the_author_meta( 'googleplus', $exs_author_id );
$exs_custom_image_url = get_the_author_meta( 'custom_profile_image', $exs_author_id );
?>
	<div class="author-meta side-item has-post-thumbnail">
		<div class="item-media">
			<?php
			global $post;
			$author_id = $post->post_author;
			echo '<a href="' . esc_url( get_author_posts_url( $author_id ) ) . '" rel="author" itemprop="url">';
			if ( ! empty( $exs_custom_image_url ) ) {
				echo '<img src="' . esc_url( $exs_custom_image_url ) . '" alt="' . esc_attr( get_the_author_meta( 'display_name', $exs_author_id ) ) . '">';
			} else {
				echo get_avatar( $exs_author_id, 700 );
			}
			echo '</a>';
			?>
		</div><!-- eof .item-media -->
		<div class="item-content">
			<?php
			$exs_about_word = exs_option( 'blog_single_author_bio_about_word' );
			if ( ! empty( $exs_about_word ) ) :
				?>
				<h5 class="about-author-heading">
					<?php echo esc_html( $exs_about_word ); ?>
				</h5>
			<?php endif; ?>
			<h4 class="author-name">
			<?php
				the_author_posts_link();
			?>
			</h4>
			<?php if ( ! empty( $exs_author_bio ) ) : ?>
				<p class="author-bio">
					<?php echo wp_kses_post( $exs_author_bio ); ?>
				</p>
				<?php
			endif; //author_bio
			if ( $exs_twitter_username || $exs_facebook_url || $exs_googleplus_url ) :
				?>
				<span class="social-links author-social">
				<?php
				if ( $exs_twitter_username ) :
					exs_social_link( 'twitter', 'https://twitter.com/' . $exs_twitter_username );
				endif;
				if ( $exs_facebook_url ) :
					exs_social_link( 'facebook', $exs_facebook_url );
				endif;
				if ( $exs_googleplus_url ) :
					exs_social_link( 'google-plus', $exs_googleplus_url );
				endif;
				?>
				</span><!-- eof .author-social -->
			<?php endif; //author social ?>
		</div><!-- eof .item-content -->
	</div><!-- eof author-meta -->
<?php
