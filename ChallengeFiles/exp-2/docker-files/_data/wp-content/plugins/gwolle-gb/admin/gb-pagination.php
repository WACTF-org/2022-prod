<?php

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * gwolle_gb_pagination_admin
 * Pagination of the entries for the page-entries.php
 *
 * @param int $page_num the number of the requested page.
 * @param int $pages_total the total number of pages.
 * @param int $count total number of entries. Relative to the $show variable.
 * @param string $show the tab of the page that is shown.
 * @return string $pagination the html of the pagination.
 */
function gwolle_gb_pagination_admin( $page_num, $pages_total, $count, $show ) {

	$num_entries = (int) get_option('gwolle_gb-entries_per_page', 20);

	$book_id = 0;
	if ( isset( $_GET['book_id'] ) ) {
		$book_id = (int) $_GET['book_id'];
	}

	// Calculate written text with info "Showing 1 – 25 of 54"
	if ($count === 0) {
		$firstentry = 0;
		$lastentry = 0;
	} else {
		$firstentry = ( ( $page_num - 1 ) * $num_entries ) + 1;
		$total_on_this_page = $count - ( ( ( $page_num - 1 ) * $num_entries ) );
		if ( $total_on_this_page > $num_entries ) {
			$total_on_this_page = $num_entries;
		}
		$lastentry = $firstentry + $total_on_this_page - 1;
	}

	$pagination = '
				<h2 class="screen-reader-text">' . esc_html__('Guestbook list navigation', 'gwolle-gb') . '</h2>
				<div class="tablenav-pages">';

	$high_dots_made = false;
	$pages_done = array();

	$pagination .= '<span class="displaying-num">' . esc_html__('Showing:', 'gwolle-gb') .
		' ' . $firstentry . ' &#8211; ' . $lastentry . ' ' . esc_html__('of', 'gwolle-gb') . ' ' . $count . '</span>
		';


	if ($page_num > 1) {
		$link = admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&show=' . $show . '&pageNum=' . round($page_num - 1) . '&book_id=' . $book_id );
		$pagination .= '<a class="first page-numbers button" href="' . $link . '" rel="prev">&larr;</a>';
	}

	if ($page_num < 5) {
		$show_range = 5;
		if ($pages_total < 6) {
			$show_range = $pages_total;
			$high_dots_made = true; // no need for highdots.
		}
		for ( $i = 1; $i < ( $show_range + 1 ); $i++ ) {
			if ($i === $page_num) {
				if ( in_array( $i, $pages_done, true ) ) {
					continue;
				}
				$pagination .= '<span class="page-numbers current">' . $i . '</span>';
				$pages_done[] = $i;
			} else {
				if ( in_array( $i, $pages_done, true ) ) {
					continue;
				}
				$link = admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&show=' . $show . '&pageNum=' . $i . '&book_id=' . $book_id );
				$pagination .= '<a class="page-numbers button" href="' . $link . '">' . $i . '</a>';
				$pages_done[] = $i;
				if ( $i === $pages_total ) {
					break;
				}
			}
		}

		if ( ( $page_num + 4 < $pages_total ) && ( ! $high_dots_made ) ) {
			$pagination .= '<span class="page-numbers dots">...</span>';
			$high_dots_made = true;
		}
	} else if ($page_num > 4) {
		$link = admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&show=' . $show . '&pageNum=1&book_id=' . $book_id );
		$pagination .= '<a class="page-numbers button" href="' . $link . '">1</a>';
		if ($pages_total > 4) {
			$pagination .= '<span class="page-numbers dots">...</span>';
		}
		if ( ( $page_num + 2 ) < $pages_total ) {
			$min_range = $page_num - 2;
			$show_range = $page_num + 2;
		} else {
			$min_range = $page_num - 3;
			$show_range = $pages_total - 1;
		}
		for ($i = $min_range; $i <= $show_range; $i++) {
			if ($i === $page_num) {
				$pagination .= '<span class="page-numbers button current">' . $i . '</span>';
			} else {
				$link = admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&show=' . $show . '&pageNum=' . $i . '&book_id=' . $book_id );
				$pagination .= '<a class="page-numbers button" href="' . $link . '">' . $i . '</a>';
			}
		}
		if ($page_num === $pages_total) {
			$pagination .= '<span class="page-numbers button current">' . $page_num . '</span>';
		}
	}

	if ( $page_num < $pages_total ) {
		if ( ( $page_num + 3 < $pages_total ) && ( ! $high_dots_made ) ) {
			$pagination .= '<span class="page-numbers dots">...</span>';
			$high_dots_made = true;
		}
		if ( ! in_array( $pages_total, $pages_done, true ) ) {
			$link = admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&show=' . $show . '&pageNum=' . $pages_total . '&book_id=' . $book_id );
			$pagination .= '<a class="page-numbers button" href="' . $link . '">' . $pages_total . '</a>';
		}
		$link = admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&show=' . $show . '&pageNum=' . round($page_num + 1) . '&book_id=' . $book_id );
		$pagination .= '<a class="last page-numbers button" href="' . $link . '" rel="next">&rarr;</a>';
	}

	$pagination .= '</div>';

	return $pagination;

}
