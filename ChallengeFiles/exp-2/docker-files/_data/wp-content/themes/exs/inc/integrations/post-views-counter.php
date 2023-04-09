<?php
/**
 * Post Views Counter
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//views count - for posts
if ( ! function_exists( 'exs_video_action_exs_entry_meta_before' ) ) :
	function exs_video_action_exs_entry_meta_before() {
		if ( class_exists( 'Post_Views_Counter' ) ) {
			if ( is_singular() ) {
				// set state when shown once
				$exs = ExS::instance();
				if ( empty(  $exs->get( 'post_views_count_already_shown' ) ) ) {
					$exs->set( 'post_views_count_already_shown', true );
					echo do_shortcode( '[post-views]' ) . ' ';
				}
			} else {
				$exs = ExS::instance();
				if ( empty(  $exs->get( 'post_views_count_already_shown-' . get_the_ID() ) ) ) {
					$exs->set( 'post_views_count_already_shown-' . get_the_ID() , true );
					echo do_shortcode( '[post-views]' ) . ' ';
				}
			}
		}
	}
endif;
add_action( 'exs_entry_meta_before', 'exs_video_action_exs_entry_meta_before' );
