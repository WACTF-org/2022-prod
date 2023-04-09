<?php
/**
 * The template for displaying page title in page title section
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_search() ) :
	$exs_search_query = esc_html( get_search_query() );
	if ( (bool) trim( $exs_search_query ) === false ) :
		echo esc_html__( 'Search', 'exs' );
	else :
		echo esc_html__( 'Search Results for: ', 'exs' );
		echo esc_html( $exs_search_query );
	endif;

	return;
endif;

if ( is_home() ) :
	$exs_title = exs_option( 'blog_page_name', esc_html__( 'Blog', 'exs' ) );
	echo esc_html( $exs_title );

	return;
endif;

if ( is_404() ) :
	$exs_title = exs_option( '404_title', esc_html__( '404', 'exs' ) );
	echo esc_html( $exs_title );

	return;
endif;

if ( function_exists( 'is_shop' ) ) :
	if ( is_shop() ) :
		$exs_title = esc_html__( 'Shop', 'exs' );
		echo esc_html( $exs_title );

		return;
	endif;
endif;

if ( is_singular() ) :
	the_title();

	return;
endif;

if ( is_archive() ) :
	$exs_hide_tax_name_class = exs_option( 'title_hide_taxonomy_name', '' ) ? 'hide-tax-name' : 'tax-name';
	echo '<span class="' . esc_attr( $exs_hide_tax_name_class ) . '">';
	the_archive_title();
	echo '</span>';

	return;
endif;
