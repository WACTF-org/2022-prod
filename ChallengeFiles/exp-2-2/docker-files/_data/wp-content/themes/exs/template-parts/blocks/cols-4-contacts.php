<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'colsSingle' => 'cols-single-sm',
	'gap' => 'gap-50',
	'pt' => 'pt-4',
	'pb' => 'pb-2',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>"><!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":501,"width":100,"height":100,"sizeSlug":"large","className":"mb-0","mb":"mb-0"} -->
		<div class="wp-block-image mb-0"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/mail-outline.png" alt="" class="wp-image-501" width="100" height="100"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":4,"className":"mt-05 mb-0","mt":"mt-05","mb":"mb-0"} -->
		<h4 class="has-text-align-center mt-05 mb-0"><strong>Venenatis</strong></h4>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":"mt-1","mt":"mt-1"} -->
		<p class="has-text-align-center mt-1"><a href="mailto:mail@example.com">mail@example.com </a></p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":524,"width":100,"height":100,"sizeSlug":"large","className":"mb-0","mb":"mb-0"} -->
		<div class="wp-block-image mb-0"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/call-outline.png" alt="" class="wp-image-524" width="100" height="100"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":4,"className":"mt-05 mb-0","mt":"mt-05","mb":"mb-0"} -->
		<h4 class="has-text-align-center mt-05 mb-0"><strong>Vivamus</strong></h4>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":"mt-1","mt":"mt-1"} -->
		<p class="has-text-align-center mt-1">+1-800-555-1234<br>+2-970-555-5678</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":502,"width":100,"height":100,"sizeSlug":"large","className":"mb-0","mb":"mb-0"} -->
		<div class="wp-block-image mb-0"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/location-outline.png" alt="" class="wp-image-502" width="100" height="100"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":4,"className":"mt-05 mb-0","mt":"mt-05","mb":"mb-0"} -->
		<h4 class="has-text-align-center mt-05 mb-0"><strong>Aenean</strong></h4>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":"mt-1","mt":"mt-1"} -->
		<p class="has-text-align-center mt-1">18000, Sapien, Vivamus dolor, Dapibus sit. 156</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":500,"width":100,"height":100,"sizeSlug":"large","className":"mb-0","mb":"mb-0"} -->
		<div class="wp-block-image mb-0"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/alarm-outline.png" alt="" class="wp-image-500" width="100" height="100"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":4,"className":"mt-05 mb-0","mt":"mt-05","mb":"mb-0"} -->
		<h4 class="has-text-align-center mt-05 mb-0"><strong>Orci varius</strong></h4>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":"mt-1","mt":"mt-1"} -->
		<p class="has-text-align-center mt-1"><strong>SIT-VEL:</strong> 9:00AM - 6:00PM<br>          <strong>NISL:</strong> 10:00AM - 5:00PM<br>  <strong>ERAT:</strong> Day Off        </p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
