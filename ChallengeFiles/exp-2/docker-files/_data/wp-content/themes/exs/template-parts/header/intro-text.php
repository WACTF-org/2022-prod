<?php
/**
 * The intro section text template file
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

//search
$exs_show_search = exs_option( 'intro_show_search', '' );

//text
$exs_intro_heading     = exs_option( 'intro_heading', '' );
$exs_intro_description = exs_option( 'intro_description', '' );
$exs_intro_shortcode   = exs_option( 'intro_shortcode', '' );

//buttons
$exs_intro_button_text_first  = exs_option( 'intro_button_text_first', '' );
$exs_intro_button_url_first   = exs_option( 'intro_button_url_first', '' );
$exs_intro_button_text_second = exs_option( 'intro_button_text_second', '' );
$exs_intro_button_url_second  = exs_option( 'intro_button_url_second', '' );

//animation
//animate an__XXX
//intro_heading_animation
//intro_description_animation
//intro_button_first_animation
//intro_button_second_animation
//intro_shortcode_animation
$exs_intro_heading_animation       = exs_option( 'intro_heading_animation', '' ) ? 'animate an__' . exs_option( 'intro_heading_animation' ) : '';
$exs_intro_description_animation   = exs_option( 'intro_description_animation', '' ) ? 'animate an__' . exs_option( 'intro_description_animation' ) : '';
$exs_intro_button_first_animation  = exs_option( 'intro_button_first_animation', '' ) ? 'animate an__' . exs_option( 'intro_button_first_animation' ) : '';
$exs_intro_button_second_animation = exs_option( 'intro_button_second_animation', '' ) ? 'animate an__' . exs_option( 'intro_button_second_animation' ) : '';
$exs_intro_shortcode_animation     = exs_option( 'intro_shortcode_animation', '' ) ? 'animate an__' . exs_option( 'intro_shortcode_animation' ) : '';


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
	empty( $exs_show_search )
) {
	return;
}

if ( ! empty( $exs_intro_heading ) ) :
	$exs_intro_heading_mt = exs_option( 'intro_heading_mt', '' );
	$exs_intro_heading_mb = exs_option( 'intro_heading_mb', '' );
	?>
	<h1 class="intro-heading <?php echo esc_attr( $exs_intro_heading_animation . ' ' . $exs_intro_heading_mt . ' ' . $exs_intro_heading_mb ); ?>">
		<?php echo wp_kses_post( $exs_intro_heading ); ?>
	</h1>
	<?php
endif; //intro_heading

if ( ! empty( $exs_intro_description ) ) :
	$exs_intro_description_mt = exs_option( 'intro_description_mt', '' );
	$exs_intro_description_mb = exs_option( 'intro_description_mb', '' );
	?>
	<div class="intro-description <?php echo esc_attr( $exs_intro_description_animation . ' ' . $exs_intro_description_mt . ' ' . $exs_intro_description_mb ); ?>">
		<?php echo wp_kses_post( $exs_intro_description ); ?>
	</div>
	<?php
endif; //intro_description

if ( ! empty( $exs_intro_button_text_first ) || ! empty( $exs_intro_button_text_second ) ) :
	$exs_intro_buttons_mt = exs_option( 'intro_buttons_mt', '' );
	$exs_intro_buttons_mb = exs_option( 'intro_buttons_mb', '' );
	?>
	<div class="intro-buttons <?php echo esc_attr( $exs_intro_buttons_mt . ' ' . $exs_intro_buttons_mb ); ?>">
		<?php if ( ! empty( $exs_intro_button_text_first ) ) : ?>
			<a class="wp-block-button__link <?php echo esc_attr( $exs_intro_button_first_animation ); ?>" href="<?php echo esc_url( $exs_intro_button_url_first ); ?>"><?php echo esc_html( $exs_intro_button_text_first ); ?></a>
		<?php endif; //intro_button_text_first ?>
		<?php if ( ! empty( $exs_intro_button_text_second ) ) : ?>
			<span class="is-style-outline"><a class="wp-block-button__link <?php echo esc_attr( $exs_intro_button_second_animation ); ?>" href="<?php echo esc_url( $exs_intro_button_url_second ); ?>"><?php echo esc_html( $exs_intro_button_text_second ); ?></a></span>
		<?php endif; //intro_button_text_second ?>
	</div>
	<?php
endif; //intro_heading
if ( ! empty( $exs_show_search ) ) {
	get_search_form();
}
if ( ! empty( $exs_intro_shortcode ) ) :
	$exs_intro_shortcode_mt = exs_option( 'intro_shortcode_mt', '' );
	$exs_intro_shortcode_mb = exs_option( 'intro_shortcode_mb', '' );
	?>
	<div class="intro-shortcode <?php echo esc_attr( $exs_intro_shortcode_animation . ' ' . $exs_intro_shortcode_mt . ' ' . $exs_intro_shortcode_mb ); ?>">
		<?php echo do_shortcode( $exs_intro_shortcode ); ?>
	</div>
	<?php
endif; //intro_shortcode
