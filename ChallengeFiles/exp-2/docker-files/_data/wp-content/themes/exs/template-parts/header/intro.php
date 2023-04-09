<?php
/**
 * The intro section template file
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

$exs_intro_layout      = exs_option( 'intro_layout', '' );
$exs_intro_fullscreen  = exs_option( 'intro_fullscreen', '' );
$exs_intro_heading     = exs_option( 'intro_heading', '' );
$exs_intro_description = exs_option( 'intro_description', '' );
$exs_intro_shortcode   = exs_option( 'intro_shortcode', '' );
$exs_show_search       = exs_option( 'intro_show_search', '' );

$exs_extra_padding_top    = exs_option( 'intro_extra_padding_top', '' );
$exs_extra_padding_bottom = exs_option( 'intro_extra_padding_bottom', '' );
$exs_intro_background     = exs_option( 'intro_background', '' );
$exs_intro_alignment      = exs_option( 'intro_alignment', '' );
$exs_font_size            = exs_option( 'intro_font_size', '' );
$exs_background_image     = exs_section_background_image_array( 'intro' );

//buttons
$exs_intro_button_text_first  = exs_option( 'intro_button_text_first', '' );
$exs_intro_button_url_first   = exs_option( 'intro_button_url_first', '' );
$exs_intro_button_text_second = exs_option( 'intro_button_text_second', '' );
$exs_intro_button_url_second  = exs_option( 'intro_button_url_second', '' );

//animation
$exs_intro_image_animation = exs_option( 'intro_image_animation', '' ) ? 'animate an__' . exs_option( 'intro_image_animation' ) : '';

//not showing intro if no content specified
if (
	empty( $exs_intro_heading )
	&&
	empty( $exs_intro_description )
	&&
	empty( $exs_intro_shortcode )
	&&
	empty( $exs_intro_button_text_first )
	&&
	empty( $exs_intro_button_text_second )
	&&
	empty( $exs_background_image['url'] )
	&&
	empty( $exs_show_search )
) {
	if( is_customize_preview() ) {
		echo '<section id="intro" class="d-none"></section>';
	}
	return;
}
//if fullscreen - adding class to font size
if ( ! empty( $exs_intro_fullscreen ) ) {
	$exs_font_size .= ' screen';
}

//default layout is background image
switch ( $exs_intro_layout ) :
	//side image layout
	case 'image-left':
	case 'image-right':
		?>
		<section id="intro" class="intro intro-section layout-gap-60 <?php echo esc_attr( $exs_font_size . ' ' . $exs_intro_background . ' ' . $exs_intro_alignment . ' ' . $exs_intro_layout ); ?>">
			<div class="container <?php echo esc_attr( $exs_extra_padding_top . ' ' . $exs_extra_padding_bottom ); ?>">
				<div class="d-grid grid-2-cols">
					<div class="column">
					<?php if ( ! empty( $exs_background_image['url'] ) ) : ?>
						<img src="<?php echo esc_url( $exs_background_image['url'] ); ?>" alt="<?php echo esc_attr( $exs_intro_heading ); ?>" class="intro-image <?php echo esc_attr( $exs_intro_image_animation ); ?>">
					<?php endif; ?>
					</div>
					<div class="column intro-section-text">
					<?php
						get_template_part( 'template-parts/header/intro-text' );
					?>
					</div><!-- .column -->
				</div><!-- .d-grid -->
			</div><!-- .container -->
		</section><!-- #intro -->
		<?php
		break;

	case 'image-top':
		?>
		<section id="intro" class="intro intro-section <?php echo esc_attr( $exs_font_size . ' ' . $exs_intro_background . ' ' . $exs_intro_alignment . ' ' . $exs_intro_layout ); ?>">
			<div class="container <?php echo esc_attr( $exs_extra_padding_top . ' ' . $exs_extra_padding_bottom ); ?>">
				<div class="column">
				<?php if ( ! empty( $exs_background_image['url'] ) ) : ?>
					<img src="<?php echo esc_url( $exs_background_image['url'] ); ?>" alt="<?php echo esc_attr( $exs_intro_heading ); ?>" class="intro-image <?php echo esc_attr( $exs_intro_image_animation ); ?>">
				<?php endif; ?>
				</div>
				<div class="column intro-section-text">
				<?php
					get_template_part( 'template-parts/header/intro-text' );
				?>
				</div><!-- .column -->
			</div><!-- .container -->
		</section><!-- #intro -->
		<?php
		break;
	case 'image-bottom':
		?>
		<section id="intro" class="intro intro-section <?php echo esc_attr( $exs_font_size . ' ' . $exs_intro_background . ' ' . $exs_intro_alignment . ' ' . $exs_intro_layout ); ?>">
			<div class="container <?php echo esc_attr( $exs_extra_padding_top . ' ' . $exs_extra_padding_bottom ); ?>">

				<div class="column intro-section-text">
				<?php
					get_template_part( 'template-parts/header/intro-text' );
				?>
				</div><!-- .column -->
				<div class="column">
				<?php if ( ! empty( $exs_background_image['url'] ) ) : ?>
					<img src="<?php echo esc_url( $exs_background_image['url'] ); ?>" alt="<?php echo esc_attr( $exs_intro_heading ); ?>" class="intro-image <?php echo esc_attr( $exs_intro_image_animation ); ?>">
				<?php endif; ?>
				</div><!-- .column -->
			</div><!-- .container -->
		</section><!-- #intro -->
		<?php
		break;
	//background image
	default:
		?>
		<section id="intro" class="intro section-intro <?php echo esc_attr( $exs_font_size . ' ' . $exs_intro_background . ' ' . $exs_intro_alignment . ' ' . $exs_background_image['class'] ); ?>"
			<?php echo ( ! empty( $exs_background_image['url'] ) ) ? 'style="background-image: url(' . esc_url( $exs_background_image['url'] ) . ');' . esc_attr( $exs_background_image['overlay'] ) . '"' : ''; ?>
		>
			<div class="container <?php echo esc_attr( $exs_extra_padding_top . ' ' . $exs_extra_padding_bottom ); ?>">
			<?php
				get_template_part( 'template-parts/header/intro-text' );
			?>
			</div><!-- .container -->
		</section><!-- #intro -->
		<?php
endswitch;//$exs_intro_layout
