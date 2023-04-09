<?php
/**
 * The header template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.7.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$exs_fluid              = exs_option( 'topline_fluid' ) ? '-fluid' : '';
$exs_topline_background = exs_option( 'topline_background', '' );
//detect if we don't need inverse background based on selected header background color?
if ( is_page_template( 'page-templates/header-overlap.php' ) ) {
	$exs_topline_background = exs_get_transparent_class( exs_option( 'header_background', '' ) );
}
$exs_font_size          = exs_option( 'topline_font_size', '' );
$exs_login_links        = exs_option( 'topline_login_links' );
?>
<div id="topline" class="topline <?php echo esc_attr( $exs_topline_background . ' ' . $exs_font_size ); ?>">
	<div class="container<?php echo esc_attr( $exs_fluid ); ?>">
		<?php

		dynamic_sidebar( 'sidebar-topline' );

		if ( ! empty( $exs_login_links ) ) :
			get_template_part( 'template-parts/header/login-links' );
		endif; //search

		?>
	</div><!-- .container -->
</div><!-- #topline -->
