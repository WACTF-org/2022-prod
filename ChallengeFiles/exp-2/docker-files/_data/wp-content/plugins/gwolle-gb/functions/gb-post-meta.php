<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Set meta_keys so we can find the post with the shortcode back.
 *
 * @param int $id ID of the post
 */
function gwolle_gb_save_post( $id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		return;
	if ( defined( 'DOING_CRON' ) && DOING_CRON )
		return;

		$post = get_post( $id );

	if ( has_shortcode( $post->post_content, 'gwolle_gb' ) || has_shortcode( $post->post_content, 'gwolle_gb_read' ) ) {
		// Set a meta_key so we can find the post with the shortcode back.
		$meta_value = get_post_meta( $id, 'gwolle_gb_read', true );
		if ( $meta_value !== 'true' ) {
			update_post_meta( $id, 'gwolle_gb_read', 'true' );
		}
	} else {
		// Remove the meta_key in case it is set.
		delete_post_meta( $id, 'gwolle_gb_read' );
	}

	if ( has_shortcode( $post->post_content, 'gwolle_gb' ) || has_shortcode( $post->post_content, 'gwolle_gb_read' ) || has_shortcode( $post->post_content, 'gwolle_gb_write' ) ) {
		// Nothing to do
	} else {
		delete_post_meta( $id, 'gwolle_gb_book_id' );
	}

}
add_action('save_post', 'gwolle_gb_save_post');


/*
 * Set meta_keys so we can find the post with the shortcode back.
 *
 * @param string $content Content of the post
 * @return string $content Content of the post
 *
 * @since 3.1.8
 */
function gwolle_gb_content_filter_for_meta_keys( $content ) {

	if ( ! is_singular() || ! is_main_query() || is_admin() ) {
		return $content;
	}

	$id = get_the_ID();

	if ( has_shortcode( $content, 'gwolle_gb' ) || has_shortcode( $content, 'gwolle_gb_read' ) ) {
		// Set a meta_key so we can find the post with the shortcode back.
		$meta_value = get_post_meta( $id, 'gwolle_gb_read', true );
		if ( $meta_value !== 'true' ) {
			update_post_meta( $id, 'gwolle_gb_read', 'true' );
		}
	} else {
		// Remove the meta_key in case it is set.
		delete_post_meta( $id, 'gwolle_gb_read' );
	}

	if ( has_shortcode( $content, 'gwolle_gb' ) || has_shortcode( $content, 'gwolle_gb_read' ) || has_shortcode( $content, 'gwolle_gb_write' ) ) {
		// Nothing to do
	} else {
		delete_post_meta( $id, 'gwolle_gb_book_id' );
	}

	return $content;
}
add_filter( 'the_content', 'gwolle_gb_content_filter_for_meta_keys', 1 ); // before shortcodes are done.


/*
 * Make our meta fields protected, so they are not in the custom fields metabox.
 *
 * @since 2.1.5
 */
function gwolle_gb_is_protected_meta( $protected, $meta_key, $meta_type ) {

	switch ($meta_key) {
		case 'gwolle_gb_read':
			return true;

		case 'gwolle_gb_book_id':
			return true;

		default:
			return $protected;
	}

	return $protected;
}
add_filter( 'is_protected_meta', 'gwolle_gb_is_protected_meta', 10, 3 );


/*
 * Set Meta_keys so we can find the post with the shortcode back.
 * Gets called from frontend/gb-shortcodes.php.
 *
 * @param string $shortcode      value 'write' or 'read'.
 * @param array  $shortcode_atts array with the shortcode attributes.
 *
 * @since      1.5.6
 * @deprecated 3.1.8 Meta keys are now set in gwolle_gb_content_filter_for_meta_keys()
 */
function gwolle_gb_set_meta_keys( $shortcode, $shortcode_atts ) {

	_deprecated_function( __FUNCTION__, ' 3.1.8', 'gwolle_gb_content_filter_for_meta_keys()' );

}


/*
 * Check whether this post/page is a guestbook.
 * Will test if the 'gwolle_gb_read' meta key is set to 'true'.
 *
 * @param  bool $post_id the ID of the post to check.
 * @return bool          true if this post has a guestbook shortcode.
 *
 * @since 3.0.0
 */
function gwolle_gb_post_is_guestbook( $post_id ) {

	$meta_value_read = get_post_meta( $post_id, 'gwolle_gb_read', true );
	if ( $meta_value_read === 'true' ) {
		return true;
	}

	return false;

}
