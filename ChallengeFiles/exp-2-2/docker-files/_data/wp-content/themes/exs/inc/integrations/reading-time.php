<?php
/**
 * Post Reading Time
 *
 * @package WordPress
 * @subpackage ExS
 * @since 2.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'exs_entry_meta_add_post_reading_time' ) ) :
	function exs_entry_meta_add_post_reading_time() {
		if ( ! exs_option( 'reading_time_enabled', '' ) ) {
			return;
		}

		$option_suffix         = is_single() ? 'blog_single' : ( is_search() ? 'search' : 'blog' );
		$reading_time_position = exs_option( 'reading_time_position_' . $option_suffix );

		if ( ! $reading_time_position ) {
			return;
		}

		//200
		$reading_time_words_per_minute = exs_option( 'reading_time_words_per_minute', '200' );
		//Read Time:
		$reading_time_prefix = exs_option( 'reading_time_prefix', '' );
		//min.
		$reading_time_suffix = exs_option( 'reading_time_suffix', '' );
		$show_icon = ! exs_option( $option_suffix . '_hide_meta_icons', false );

		//new options since 1.9.5
		$css_class = '';
		$css_class .= exs_option( $option_suffix . '_meta_bold' ) ? ' fw-700' : '';
		$css_class .= exs_option( $option_suffix . '_meta_uppercase' ) ? ' text-uppercase' : '';
		$css_class .= exs_option( $option_suffix . '_meta_font_size' ) ? ' fs-' . (int) exs_option( 'blog_meta_font_size' ) : '';

		add_action( 'exs_entry_meta_' . $reading_time_position , function ( $exs_title_section ) use ( $option_suffix, $reading_time_words_per_minute, $reading_time_prefix, $reading_time_suffix, $show_icon, $css_class, $reading_time_position ) {

			$actions_made = did_action(  'exs_entry_meta_' . $reading_time_position );

			//exit early to prevent duplicating of reading time display
			if( 'blog_single' === $option_suffix ) {
				//default meta or title section
				$show_in_title = exs_option( 'reading_time_blog_single_title_section' );
				if ( ( $exs_title_section && ! $show_in_title ) || ( ! $exs_title_section && $show_in_title ) ){
					return;
				}

				$even_or_odd = $show_in_title ? 0 : 1;
				//not showing at the bottom footer
				if ( ( $actions_made > 2 ) || ( $actions_made % 2 === $even_or_odd ) ) {
					return;
				}
			//archive or search - even
			} else {
				//not showing at the bottom footer
				if ( $actions_made % 2 === 0 ) {
					return;
				}
			}

			$reading_time_words_per_minute = $reading_time_words_per_minute ? $reading_time_words_per_minute : 200;

			$content      = get_the_content('', '', get_the_ID() );
			$word_count   = str_word_count( strip_tags( $content ) );
			$reading_time = ceil($word_count / ( int ) $reading_time_words_per_minute );

			?>
			<span class="entry-readtime-wrap icon-inline <?php echo esc_attr( $css_class ); ?>">
			<?php
			//icon
			if ( ! empty( $show_icon ) ) {
				exs_icon( 'hourglass-empty' );
			}
			//word
			if ( ! empty( $reading_time_prefix ) ) : ?>
				<span class="readtime-word meta-word">
					<?php echo esc_html( $reading_time_prefix ); ?>
				</span><!--.readtime-word-->
			<?php
			endif;
			//value
			?>
				<span class="readtime-time meta-word">
			<?php echo esc_html( $reading_time ); ?>
				</span><!--.readtime-time-->
			<?php
			//word
			if ( ! empty( $reading_time_suffix ) ) :
				?>
				<span class="readtime-word meta-word">
					<?php echo esc_html( $reading_time_suffix ); ?>
				</span><!--.readtime-word-->
			<?php endif; ?>
			</span><!--.entry-readtime-wrap-->
			<?php
		} );
	}
endif;

add_action( 'wp', 'exs_entry_meta_add_post_reading_time' );
