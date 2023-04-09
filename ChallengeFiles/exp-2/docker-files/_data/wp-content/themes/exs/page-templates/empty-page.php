<?php
/**
 * Template Name: No header, No Footer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'empty' );

// Start the Loop.
while ( have_posts() ) :
	the_post();

	the_content();

endwhile;

get_footer( 'empty' );
