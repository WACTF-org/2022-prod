<?php
/**
 * The header preloader template file
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

if ( is_customize_preview() ) {
	echo '<div id="preloader-wrap">';
}
//page preloader
$exs_preloader = exs_option( 'preloader', '' );

if ( ! empty( $exs_preloader ) ) :
	?>
	<!-- preloader -->
	<div id="preloader" class="preloader <?php echo esc_attr( $exs_preloader ); ?>">
		<div class="preloader_css"></div>
	</div>
<?php
endif; //preloader_enabled

if ( is_customize_preview() ) {
	echo '</div>';
}
