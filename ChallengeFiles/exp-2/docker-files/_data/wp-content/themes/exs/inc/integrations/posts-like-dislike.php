<?php
/**
 * Posts Like Dislike
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//like dislike button - for posts
if ( ! function_exists( 'exs_action_exs_entry_meta_after' ) ) :
	function exs_action_exs_entry_meta_after() {
		if ( class_exists( 'PLD_Comments_like_dislike' ) ) {
			if ( is_singular() ) {
				echo do_shortcode('[posts_like_dislike]');
			}
		}
	}
endif;
add_action( 'exs_entry_meta_after', 'exs_action_exs_entry_meta_after' );

//show like-dislike only once - for posts
if ( ! function_exists( 'exs_filter_pld_like_dislike_html' ) ) :
	function exs_filter_pld_like_dislike_html( $like_dislike_html ) {
		//quit early if not singular post
		if ( ! is_singular() ) {
			return '';
		}
		// set state when shown once
		$exs = ExS::instance();
		if ( empty(  $exs->get( 'like_dislike_already_shown' ) ) ) {
			$exs->set( 'like_dislike_already_shown', true );
			$like_dislike_html = str_replace( '<i class="fas fa-thumbs-up"></i>', exs_icon( 'thumb-up', true ), $like_dislike_html );
			$like_dislike_html = str_replace( '<i class="fas fa-thumbs-down"></i>', exs_icon( 'thumb-down', true ), $like_dislike_html );
			return $like_dislike_html;
		}
		return '';
	}
endif;
add_filter( 'pld_like_dislike_html', 'exs_filter_pld_like_dislike_html' );
