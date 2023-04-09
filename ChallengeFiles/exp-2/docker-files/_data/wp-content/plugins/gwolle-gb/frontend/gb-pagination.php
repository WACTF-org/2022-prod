<?php

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * gwolle_gb_pagination_frontend
 * Pagination of the entries for the guestbook frontend
 *
 * @param int $page_num the number of the requested page.
 * @param int $pages_total the total number of pages.
 * @return string $pagination the html of the pagination.
 */
function gwolle_gb_pagination_frontend( $page_num, $pages_total ) {

	$high_dots_made = false;
	$pages_done = array();

	$permalink = gwolle_gb_get_permalink(get_the_ID());
	$is_search = gwolle_gb_is_search();
	if ( $is_search ) {
		$search_query = gwolle_gb_sanitize_input( $_GET['gwolle-gb-search-input'] );
		$permalink = add_query_arg( 'gwolle-gb-search-input', $search_query, $permalink );
	}

	$pagination = '
				<div class="page-navigation">
					<span class="screen-reader-text">' . esc_html__('Guestbook list navigation', 'gwolle-gb') . '</span>';

	if ($page_num > 1) {
		$pagination .= '<a href="' . add_query_arg( 'pageNum', round($page_num - 1), $permalink ) . '" title="' . esc_attr__('Previous page', 'gwolle-gb') . '" rel="prev">&larr;</a>';
	}

	if ($page_num < 5) {
		$showrange = 5;
		if ($pages_total < 6) {
			$showrange = $pages_total;
			$high_dots_made = true; // no need for highdots.
		}
		for ( $i = 1; $i < ( $showrange + 1 ); $i++ ) {
			if ($i === $page_num) {
				if ( in_array( $i, $pages_done ) ) { continue; }
				$pagination .= '<span class="page-numbers current">' . $i . '</span>';
				$pages_done[] = $i;
			} else {
				if ( in_array( $i, $pages_done ) ) { continue; }
				$pagination .= '<a href="' . add_query_arg( 'pageNum', $i, $permalink ) . '" title="' . esc_attr__('Page', 'gwolle-gb') . ' ' . $i . '">' . $i . '</a>';
				$pages_done[] = $i;
				if ( $i === $pages_total ) { break; }
			}
		}

		if ( ( $page_num + 4 < $pages_total ) && ( ! $high_dots_made ) ) {
			$pagination .= '<span class="page-numbers dots">...</span>';
			$high_dots_made = true;
		}
	} else if ($page_num > 4) {
		$pagination .= '<a href="' . add_query_arg( 'pageNum', 1, $permalink ) . '" title="' . esc_attr__('Page', 'gwolle-gb') . ' 1">1</a>';
		if ($pages_total > 4) {
			$pagination .= '<span class="page-numbers dots">...</span>';
		}
		if ( ($page_num + 2 ) < $pages_total ) {
			$minrange = $page_num - 2;
			$showrange = $page_num + 2;
		} else {
			$minrange = $page_num - 3;
			$showrange = $pages_total - 1;
		}
		for ($i = $minrange; $i <= $showrange; $i++) {
			if ($i === $page_num) {
				$pagination .= '<span class="page-numbers current">' . $i . '</span>';
			} else {
				$pagination .= '<a href="' . add_query_arg( 'pageNum', $i, $permalink ) . '" title="' . esc_attr__('Page', 'gwolle-gb') . ' ' . $i . '">' . $i . '</a>';
			}
		}
		if ($page_num === $pages_total) {
			$pagination .= '<span class="page-numbers current">' . $page_num . '</span>';
		}
	}

	if ($page_num < $pages_total) {
		if ( ( ( $page_num + 3 ) < $pages_total ) && ( ! $high_dots_made ) ) {
			$pagination .= '<span class="page-numbers dots">...</span>';
			$high_dots_made = true;
		}
		if ( ! in_array( $pages_total, $pages_done ) ) {
			$pagination .= '<a href="' . add_query_arg( 'pageNum', $pages_total, $permalink ) . '" title="' . esc_attr__('Page', 'gwolle-gb') . ' ' . $pages_total . '">' . $pages_total . '</a>';
		}
		$pagination .= '<a href="' . add_query_arg( 'pageNum', round($page_num + 1), $permalink ) . '" title="' . esc_attr__('Next page', 'gwolle-gb') . '" rel="next">&rarr;</a>';
	}

	// 'All' link
	if ( $pages_total >= 2 && get_option( 'gwolle_gb-paginate_all', 'false' ) === 'true' && ! $is_search ) {
		if ( isset($_GET['show_all']) && $_GET['show_all'] === 'true' ) {
			$pagination .= '<span class="page-numbers all">' . esc_html__('All', 'gwolle-gb') . '</span>';
		} else {
			$pagination .= '<a href="' . add_query_arg( 'show_all', 'true', $permalink ) . '" title="' . esc_attr__('All entries', 'gwolle-gb') . '">' . esc_html__('All', 'gwolle-gb') . '</a>';
		}
	}

	$pagination .= '</div>
		';

	if ($pages_total > 1) {
		return $pagination;
	}

}
