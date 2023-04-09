<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Clear the cache of the most common Cache plugins.
 *
 * @param object $entry instance of gb_entry class
 */
function gwolle_gb_clear_cache( $entry = false ) {

	/* Default WordPress */
	wp_cache_flush();


	/* Gwolle: Transient for admin menu counter */
	delete_transient( 'gwolle_gb_menu_counter' );

	/* Gwolle: Transient for frontend pagination counter */
	if ( is_object( $entry ) && is_a( $entry, 'gwolle_gb_entry' ) ) {
		$book_id = $entry->get_book_id();
		$key = 'gwolle_gb_frontend_pagination_book_' . $book_id;
		delete_transient( $key );
	} else {
		// no book_id available, clear all transients.
		$postids = gwolle_gb_get_books();
		if ( is_array($postids) && ! empty($postids) ) {
			foreach ( $postids as $postid ) {
				$bookid = (int) get_post_meta( $postid, 'gwolle_gb_book_id', true );
				if ( empty( $bookid ) ) {
					continue;
				}
				$key = 'gwolle_gb_frontend_pagination_book_' . $bookid;
				delete_transient( $key );
			}
		}
	}

	/* Cachify */
	if ( class_exists('Cachify') ) {
		$cachify = new Cachify();
		if ( method_exists($cachify, 'flush_total_cache') ) {
			$cachify->flush_total_cache(true);
		}
	}

	/* W3 Total Cache */
	if ( function_exists('w3tc_pgcache_flush') ) {
		w3tc_pgcache_flush();
	}

	/* WP Fastest Cache */
	if ( class_exists('WpFastestCache') ) {
		$wp_fastest_cache = new WpFastestCache();
		if ( method_exists($wp_fastest_cache, 'deleteCache') ) {
			$wp_fastest_cache->deleteCache();
		}
	}

	/* WP Super Cache */
	if ( function_exists('wp_cache_clear_cache') ) {
		$GLOBALS['super_cache_enabled'] = 1;
		wp_cache_clear_cache();
	}

	/* WP Rocket */
	if ( function_exists('rocket_clean_domain') ) {
		rocket_clean_domain();
	}

	/* Siteground Cache */
	if (function_exists('sg_cachepress_purge_cache')) {
		sg_cachepress_purge_cache();
	}

}
add_action( 'gwolle_gb_save_entry_admin', 'gwolle_gb_clear_cache' );
add_action( 'gwolle_gb_save_entry_frontend', 'gwolle_gb_clear_cache' );
