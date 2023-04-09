<?php
/**
 * Classic Editor plugin support
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.8.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//adding custom styles to TinyMCE
// Callback function to insert 'styleselect' into the $buttons array
if ( ! function_exists( 'exs_filter_mce_theme_format_insert_button' ) ) :
	function exs_filter_mce_theme_format_insert_button( $buttons ) {
		array_unshift( $buttons, 'styleselect' );

		return $buttons;
	} //exs_filter_mce_theme_format_insert_button()
endif;
// Register our callback to the appropriate filter
add_filter( 'mce_buttons_2', 'exs_filter_mce_theme_format_insert_button' );
// Callback function to filter the MCE settings
if ( ! function_exists( 'exs_filter_mce_theme_format_add_styles' ) ) :
	function exs_filter_mce_theme_format_add_styles( $init_array ) {
		// Define the style_formats array
		$style_formats = array(
			// Each array child is a format with it's own settings
			array(
				'title'   => esc_html__( 'Big', 'exs' ),
				'block'   => 'p',
				'classes' => 'special',
				'wrapper' => false,
			),
			array(
				'title'   => esc_html__( 'Bordered', 'exs' ),
				'block'   => 'p',
				'classes' => 'bordered',
				'wrapper' => false,
			),
			array(
				'title'   => esc_html__( 'Accent color background', 'exs' ),
				'inline'  => 'span',
				'classes' => 'bg-main',
				'wrapper' => false,
			),

		);
		// Insert the array, JSON ENCODED, into 'style_formats'
		$init_array['style_formats'] = json_encode( $style_formats );

		$styles = '';
		$css = '';
		//////////
		//colors//
		//////////
		$exs_colors_string = exs_get_root_colors_inline_styles_string();
		if ( ! empty( $exs_colors_string ) ) :
			$styles .= 'body.mce-content-body {' . wp_kses( $exs_colors_string, false ) . '}';
		endif;

		//////////////
		//typography//
		//////////////
		$exs_typography_string = '';
		$exs_body_string = '';

		//body
		$exs_body_string .= exs_option( 'typo_body_size' ) ? 'font-size:' . (int) exs_option( 'typo_body_size' ) . 'px;' : '';
		//override from main section
		$exs_main_section_fs = exs_option( 'main_font_size' ) ? 'font-size:' . (int) str_replace( 'fs-', '', exs_option( 'main_font_size' ) ). 'px;' : '';
		$exs_body_string = ! empty( $exs_main_section_fs ) ? $exs_main_section_fs : $exs_body_string;

		$exs_body_string .= exs_option( 'typo_body_weight' ) ? 'font-weight:' . (int) exs_option( 'typo_body_weight' ) . ';' : '';
		$exs_body_string .= exs_option( 'typo_body_line_height' ) ? 'line-height:' . (float) exs_option( 'typo_body_line_height' ) . 'em;' : '';
		$exs_body_string .= exs_option( 'typo_body_letter_spacing' ) ? 'letter-spacing:' . (float) exs_option( 'typo_body_letter_spacing' ) . 'em;' : '';

		if ( $exs_body_string ) {
			$exs_typography_string = 'body.mce-content-body{' .  $exs_body_string . '}';
		}

		//paragraph
		$exs_typography_string .= ! empty( exs_option( 'typo_p_margin_bottom' ) ) ? 'body.mce-content-body p{margin-bottom:' . (float) exs_option( 'typo_p_margin_bottom' ) . 'em;}' : '';

		//headings
		foreach( array( 1, 2, 3, 4, 5, 6 ) as $h ) {
			$h_string = '';
			$h_string .= ! empty( exs_option( 'typo_size_h' . $h ) ) ? 'font-size:' . (float) exs_option( 'typo_size_h' . $h ) . 'em;' : '';
			$h_string .= ! empty( exs_option( 'typo_line_height_h' . $h ) ) ? 'line-height:' . (float) exs_option( 'typo_line_height_h' . $h ) . 'em;' : '';
			$h_string .= ! empty( exs_option( 'typo_letter_spacing_h' . $h ) ) ? 'letter-spacing:' . exs_option( 'typo_letter_spacing_h' . $h ) .'em;' : '';
			$h_string .= ! empty( exs_option( 'typo_weight_h' . $h ) ) ? 'font-weight:' . (int) exs_option( 'typo_weight_h' . $h ) . ';' : '';
			$h_string .= ! empty( exs_option( 'typo_mt_h' . $h ) ) ? 'margin-top:' . (float) exs_option( 'typo_mt_h' . $h ) . 'em;': '';
			$h_string .= ! empty( exs_option( 'typo_mb_h' . $h ) ) ? 'margin-bottom:' . (float) exs_option( 'typo_mb_h' . $h ) . 'em;' : '';

			if ( $h_string ) {
				$exs_typography_string .= 'body.mce-content-body h' . $h . '{' .$h_string . '}';
			}
		}

		if ( ! empty( $exs_typography_string ) ) :
			$styles .= wp_kses(  $exs_typography_string, false );
		endif;


		/////////
		//fonts//
		/////////
		$exs_font_body     = json_decode( exs_option( 'font_body', '{"font":"","variant": [],"subset":[]}' ) );
		$exs_font_headings = json_decode( exs_option( 'font_headings', '{"font":"","variant": [],"subset":[]}' ) );
		if ( ! empty( $exs_font_body->font ) || ! empty( $exs_font_headings->font ) ) {
			/*
			Translators: If there are characters in your language that are not supported
			by chosen font(s), translate this to 'off'. Do not translate into your own language.
			*/

			if ( 'off' !== esc_html_x( 'on', 'Google font: on or off', 'exs' ) ) {
				$exs_body_subsets  = array();
				$exs_font_body_font = '';
				if (!empty($exs_font_body->font)) {
					$exs_font_body_font = $exs_font_body->font;
					if ( ! empty( $exs_font_body->variant ) ) {
						$exs_font_body->font .= ':'. implode( ',', $exs_font_body->variant );
					}
					$exs_body_subsets  = $exs_font_body->subset;
				}

				$exs_headings_subsets  = array();
				$exs_font_headings_font = '';
				if (!empty($exs_font_headings->font)) {
					$exs_font_headings_font = $exs_font_headings->font;
					if ( ! empty( $exs_font_headings->variant ) ) {
						$exs_font_headings->font .= ':'. implode( ',', $exs_font_headings->variant );
					}
					$exs_headings_subsets  = $exs_font_headings->subset;
				}

				$exs_fonts    = array(
					'body'     => $exs_font_body->font,
					'headings' => $exs_font_headings->font,
				);
				$exs_subsets  = array(
					'body'     => implode(',', $exs_body_subsets),
					'headings' => implode(',', $exs_headings_subsets),
				);
				//'Montserrat|Bowlby One|Quattrocento Sans';
				$exs_fonts_string    = implode('|', array_filter($exs_fonts));
				$exs_subsets_string  = implode(',', array_filter($exs_subsets));

				$exs_query_args = array(
					'family' => urlencode( $exs_fonts_string ),
				);
				if (!empty($exs_subsets_string)) {
					$exs_query_args['subset'] = urlencode( $exs_subsets_string );
				}
				$exs_query_args['display']='swap';
				$exs_font_url = add_query_arg(
					$exs_query_args,
					'https://fonts.googleapis.com/css'
				);

				if ( exs_option( 'fonts_local' ) ) {
					require_once EXS_THEME_PATH . '/inc/exs-webfont-loader.php';
					$css = exs_get_webfont_url( esc_url_raw( $exs_font_url ) );
				} else {
					$css = $exs_font_url;
				}

				//printing header styles
				$exs_body_style = ( ! empty( $exs_font_body_font ) ) ? '.mce-content-body,.mce-content-body button,.mce-content-body input,.mce-content-body select,.mce-content-body textarea{font-family:\'' . $exs_font_body_font . '\',sans-serif}' : '';

				$exs_headings_style = ( ! empty( $exs_font_headings_font ) ) ? '.mce-content-body h1,.mce-content-body h2,.mce-content-body h3,.mce-content-body h4,.mce-content-body h5,.mce-content-body h6{font-family: \'' . $exs_font_headings_font . '\',sans-serif}' : '';

				$styles .= $exs_body_style . $exs_headings_style;

			}
		}

		//custom CSS style - add main color
		//$styles .= 'body.mce-content-body { --colorMain:' . exs_option( 'colorMain' ) . '}';
		if ( isset( $init_array['content_style'] ) ) {
			$init_array['content_style'] .= ' ' . wp_kses( $styles , false ) . ' ';
		} else {
			$init_array['content_style'] = wp_kses( $styles , false ) . ' ';
		}
		//custom fonts CSS
		if ( isset( $init_array['content_css'] ) ) {
			$init_array['content_css'] .= ',' . esc_url( $css );
		} else {
			$init_array['content_css'] = esc_url( $css );
		}

		return $init_array;

	} //exs_filter_mce_theme_format_add_styles()
endif;
// Attach callback to 'tiny_mce_before_init'
add_filter( 'tiny_mce_before_init', 'exs_filter_mce_theme_format_add_styles', 1 );
