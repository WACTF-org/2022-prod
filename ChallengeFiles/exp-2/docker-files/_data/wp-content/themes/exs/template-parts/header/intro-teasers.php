<?php
/**
 * The intro teasers section template file
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

$exs_teasers                  = exs_get_intro_teasers();
$exs_section_layout           = exs_option( 'intro_teaser_section_layout', '' );
$exs_intro_teaser_heading     = exs_option( 'intro_teaser_heading', '' );
$exs_intro_teaser_description = exs_option( 'intro_teaser_description', '' );

if ( empty( $exs_section_layout ) || ( empty( $exs_teasers ) && empty( $exs_intro_teaser_heading ) && empty( $exs_intro_teaser_description ) ) ) {
	if( is_customize_preview() ) {
		echo '<section id="intro-teasers" class="d-none"></section>';
	}
	return;
}

$exs_intro_teaser_section_overlap = strpos( $exs_section_layout, 'top-overlap' ) !== false ? 'intro-top-overlap' : 'no-top-over';

$exs_intro_teaser_section_background     = exs_option( 'intro_teaser_section_background', '' );
$exs_intro_teaser_section_padding_top    = exs_option( 'intro_teaser_section_padding_top', '' );
$exs_intro_teaser_section_padding_bottom = exs_option( 'intro_teaser_section_padding_bottom', '' );
$exs_font_size                           = exs_option( 'intro_teaser_font_size', '' );
$exs_count                               = count( $exs_teasers );
$exs_layout                              = exs_option( 'intro_teaser_layout', '' );
$exs_link_class                          = ( 'horizontal' === $exs_layout ) ? 'link' : 'btn';

$exs_intro_teaser_section_horizontal_padding = ! empty( $exs_intro_teaser_section_background ) ? 'px' : '';
//TODO do we need container-grid class?
?>
<section id="intro-teasers" class="intro-teasers <?php echo esc_attr( $exs_intro_teaser_section_overlap . $exs_font_size ); ?>">
	<div class="<?php echo esc_attr( $exs_section_layout ); ?>">
		<div class="intro-teasers-grid-wrap layout-gap-30 <?php echo esc_attr( $exs_intro_teaser_section_background . ' ' . $exs_intro_teaser_section_horizontal_padding . ' ' . $exs_intro_teaser_section_padding_top . ' ' . $exs_intro_teaser_section_padding_bottom ); ?>">
            <?php
            if ( ! empty( $exs_intro_teaser_heading ) ) :
                echo '<h2 class="intro-teasers-heading text-center">' . wp_kses_post( $exs_intro_teaser_heading ) . '</h2>';
            endif;
            if ( ! empty( $exs_intro_teaser_description ) ) :
                echo '<div class="intro-teasers-description text-center">' . wp_kses_post( $exs_intro_teaser_description ) . '</div>';
            endif;
            ?>
            <div class="container-grid d-grid grid-<?php echo esc_attr( $exs_count ); ?>-cols">
				<?php
				foreach ( $exs_teasers as $exs_index => $exs_teaser ) :
					$exs_title  = ( ! empty( $exs_teaser['title'] ) ) ? $exs_teaser['title'] : '';
					$exs_image  = ( ! empty( $exs_teaser['image'] ) ) ? $exs_teaser['image'] : '';
					$exs_text   = ( ! empty( $exs_teaser['text'] ) ) ? $exs_teaser['text'] : '';
					$exs_link   = ( ! empty( $exs_teaser['link'] ) ) ? $exs_teaser['link'] : '';
					$exs_button = ( ! empty( $exs_teaser['button'] ) ) ? $exs_teaser['button'] : '';
					?>
					<div class="column">
						<div class="icon-box icon-box-<?php echo esc_attr( $exs_index . ' ' . $exs_layout ); ?>">
							<?php if ( $exs_image ) : ?>
								<?php if ( $exs_link ) : ?>
									<a class="icon-box-media" href="<?php echo esc_url( $exs_link ); ?>">
								<?php endif; //link ?>
								<img src="<?php echo esc_url( $exs_image ); ?>"
									alt="<?php echo esc_attr( $exs_title ); ?> ">
								<?php if ( $exs_link ) : ?>
									</a>
								<?php endif; ?>
							<?php endif; //image ?>
							<div class="icon-box-content">
								<?php if ( $exs_title ) : ?>
									<h3>
										<?php if ( $exs_link ) : ?>
										<a href="<?php echo esc_url( $exs_link ); ?>">
											<?php
											endif; //link
											echo wp_kses_post( $exs_title );
										if ( $exs_link ) :
											?>
										</a>
									<?php endif; //link ?>
									</h3>
									<?php
								endif; //title

								if ( $exs_text ) :
									?>
									<p>
										<?php echo wp_kses_post( $exs_text ); ?>
									</p>
									<?php
								endif; //text

								if ( $exs_link && $exs_button ) :
									?>
									<a href="<?php echo esc_url( $exs_link ); ?>" class="<?php echo esc_attr( $exs_link_class ); ?>">
										<?php echo wp_kses_post( $exs_button ); ?>
									</a>
								<?php endif; //link & button ?>
							</div><!-- .icon-box-content -->
						</div><!-- .icon-box -->
					</div>
				<?php endforeach; //teasers ?>
			</div><!-- .columns -->
		</div><!-- .intro-teasers-grid-wrap -->
	</div><!-- .<?php echo esc_html( $exs_section_layout ); ?> -->
</section><!-- #title-teasers -->
