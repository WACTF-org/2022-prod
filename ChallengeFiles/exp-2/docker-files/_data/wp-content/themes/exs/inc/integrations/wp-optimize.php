<?php
/**
 * WP Optimize support
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

remove_action( 'wp_enqueue_scripts', 'exs_enqueue_static_later', 9999 );
add_action( 'wp_head', 'exs_enqueue_static_later_wp_optimize_style', 100 );
if ( ! function_exists( 'exs_enqueue_static_later_wp_optimize_style' ) ) :
	function exs_enqueue_static_later_wp_optimize_style() {
		$exs_font_body     = json_decode( exs_option( 'font_body', '{"font":"","variant": [],"subset":[]}' ) );
		$exs_font_headings = json_decode( exs_option( 'font_headings', '{"font":"","variant": [],"subset":[]}' ) );
	?>
	<style id="exs-style-inline-inline-css">
		<?php
		//inline styles for customizer options - colors and typography
		$exs_colors_string = exs_get_root_colors_inline_styles_string();
		$exs_typography_string = exs_get_typography_inline_styles_string();
		if ( ! empty( $exs_colors_string ) || ! empty( $exs_typography_string ) ) :
			$exs_styles_string = '';
			if ( ! empty( $exs_colors_string ) ) {
				$exs_styles_string .= ':root{' . $exs_colors_string . '}';
			}

			echo wp_kses($exs_styles_string . $exs_typography_string,false );
		endif;
		$exs_body_style = ( ! empty( $exs_font_body->font ) ) ? 'body,button,input,select,textarea{font-family:"' . $exs_font_body->font . '",sans-serif}' : '';
		$exs_headings_style = ( ! empty( $exs_font_headings->font ) ) ? 'h1,h2,h3,h4,h5,h6{font-family: "' . $exs_font_headings->font . '",sans-serif}' : '';
		echo wp_kses($exs_body_style . $exs_headings_style,false );
		?>
	</style>
	<?php
	}
endif;
