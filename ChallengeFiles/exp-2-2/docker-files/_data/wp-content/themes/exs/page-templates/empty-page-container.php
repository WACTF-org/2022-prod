<?php
/**
 * Template Name: No header, No Footer - Container
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'empty' );

echo '<div id="main"><div class="container">';

// Start the Loop.
while ( have_posts() ) :
	the_post();

	the_content();

endwhile;

echo '</div><!-- .container --></div><!-- #main -->';

get_footer( 'empty' );
