<?php
/**
 * The 404 page template file
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

get_header();

$heading = exs_option( '404_heading', esc_html__( '404', 'exs' ) );
$subheading = exs_option( '404_subheading', esc_html__( 'Oops, page not found!', 'exs' ) );
$text_top = exs_option( '404_text_top', esc_html__( 'You can search what interested:', 'exs' ) );
$show_searchform = exs_option( '404_show_searchform', true );
$text_bottom = exs_option( '404_text_bottom', esc_html__( 'Or', 'exs' ) );
$text_button = exs_option( '404_text_button', esc_html__( 'Go To Home', 'exs' ) );
$text_button_url = exs_option( '404_text_button_url' );
if ( empty( $text_button_url ) ) {
	$text_button_url = home_url( '/' );
}

?>
	<div id="main" class="main section-404 container-720">
		<div class="container pt-5 pb-5">
			<main>
				<div id="layout" class="text-center">
					<div class="wrap-404">
						<?php if ( ! empty( $heading ) ) :  ?>
						<h2 class="not_found mb-0 animate an__fadeInDown">
							<span class="has-huge-font-size"><?php echo esc_html( $heading ); ?></span>
						</h2>
						<?php endif; ?>
						<?php if ( ! empty( $subheading ) ) : ?>
							<h5 class=" animate an__fadeInDown">
								<?php echo esc_html( $subheading ); ?>
							</h5>
						<?php endif; ?>
						<?php if ( ! empty( $text_top ) ) : ?>
							<p class="animate an__fadeInLeft">
								<?php echo esc_html( $text_top ); ?>
							</p>
						<?php endif; ?>
						<?php if ( ! empty( $show_searchform ) ) : ?>
							<div class="widget widget_search mb-1 animate an__fadeInLeft">
								<?php get_search_form(); ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $text_bottom ) ) : ?>
							<p class="mb-1 animate an__fadeInUp">
								<?php echo esc_html( $text_bottom ); ?>
							</p>
						<?php endif; ?>
						<p class="mb-0 animate an__fadeInUp">
							<a href="<?php echo esc_url( $text_button_url ); ?>" class="btn wp-block-button__link">
								<?php echo esc_html( $text_button ); ?>
							</a>
						</p>
					</div>

				</div><!-- #layout -->
			</main>
		</div><!-- .container -->
	</div><!-- #main -->
<?php
get_footer();
