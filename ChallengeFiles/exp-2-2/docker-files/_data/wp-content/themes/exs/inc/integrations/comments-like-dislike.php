<?php
/**
 * Comment Like Dislike
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//like-dislike for comments
if ( ! function_exists( 'exs_filter_cld_like_dislike_html' ) ) :
	function exs_filter_cld_like_dislike_html( $like_dislike_html ) {

		$like_dislike_html = str_replace( '<i class="fas fa-thumbs-up"></i>', exs_icon( 'thumb-up', true ), $like_dislike_html );
		$like_dislike_html = str_replace( '<i class="fas fa-thumbs-down"></i>', exs_icon( 'thumb-down', true ), $like_dislike_html );
		return $like_dislike_html;

	}
endif;
add_filter( 'cld_like_dislike_html', 'exs_filter_cld_like_dislike_html' );
