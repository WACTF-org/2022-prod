<?php
/**
 * The copyright section blank template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( is_customize_preview() ) {
	echo '<div id="copyright" class="d-none"></div>';
}
