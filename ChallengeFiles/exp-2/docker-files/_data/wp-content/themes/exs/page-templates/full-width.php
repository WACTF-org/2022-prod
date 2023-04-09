<?php
/**
 * Template Name: Full Width Page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

// Start the Loop.
while ( have_posts() ) :
	the_post();

	the_content();

	wp_link_pages(
		exs_get_wp_link_pages_atts()
	);

endwhile;

get_footer();
