<?php
/**
 * The header-bottom section blank template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.8.10
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( is_customize_preview() ) {
	echo '<section id="header-bottom" class="d-none"></section>';
}
