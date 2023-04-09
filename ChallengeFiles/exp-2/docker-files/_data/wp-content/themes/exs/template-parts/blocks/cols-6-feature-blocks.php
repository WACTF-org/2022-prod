<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	"gap" => "gap-30",
	"pt" => "pt-5",
	"pb" => "pb-5",
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>"><!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":743,"width":75,"height":75,"sizeSlug":"large","className":"is-style-bg-colormain-round"} -->
		<div class="wp-block-image is-style-bg-colormain-round"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/icon.png" alt="" class="wp-image-743" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":6,"className":"mt-0 mb-05","mt":"mt-0","mb":"mb-05"} -->
		<h6 class="has-text-align-center mt-0 mb-05"><strong>Lorem</strong></h6>
		<!-- /wp:heading --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":745,"width":75,"height":75,"sizeSlug":"large","className":"is-style-bg-colormain-round"} -->
		<div class="wp-block-image is-style-bg-colormain-round"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/location-outline.png" alt="" class="wp-image-745" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":6,"className":"mt-0 mb-05","mt":"mt-0","mb":"mb-05"} -->
		<h6 class="has-text-align-center mt-0 mb-05"><strong>Ipsum</strong></h6>
		<!-- /wp:heading --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":746,"width":75,"height":75,"sizeSlug":"large","className":"is-style-bg-colormain-round"} -->
		<div class="wp-block-image is-style-bg-colormain-round"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/color-wand-outline.png" alt="" class="wp-image-746" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":6,"className":"mt-0 mb-05","mt":"mt-0","mb":"mb-05"} -->
		<h6 class="has-text-align-center mt-0 mb-05"><strong>Dolor</strong></h6>
		<!-- /wp:heading --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":748,"width":75,"height":75,"sizeSlug":"large","className":"is-style-bg-colormain-round"} -->
		<div class="wp-block-image is-style-bg-colormain-round"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/bulb-outline.png" alt="" class="wp-image-748" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":6,"className":"mt-0 mb-05","mt":"mt-0","mb":"mb-05"} -->
		<h6 class="has-text-align-center mt-0 mb-05"><strong>Sit</strong></h6>
		<!-- /wp:heading --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":750,"width":75,"height":75,"sizeSlug":"full","className":"is-style-bg-colormain-round"} -->
		<div class="wp-block-image is-style-bg-colormain-round"><figure class="aligncenter size-full is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/pie-chart-outline.png" alt="" class="wp-image-750" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":6,"className":"mt-0 mb-05","mt":"mt-0","mb":"mb-05"} -->
		<h6 class="has-text-align-center mt-0 mb-05"><strong>Amet</strong></h6>
		<!-- /wp:heading --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":751,"width":75,"height":75,"sizeSlug":"large","className":"is-style-bg-colormain-round"} -->
		<div class="wp-block-image is-style-bg-colormain-round"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/alarm-outline.png" alt="" class="wp-image-751" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":6,"className":"mt-0 mb-05","mt":"mt-0","mb":"mb-05"} -->
		<h6 class="has-text-align-center mt-0 mb-05"><strong>Excepteur</strong></h6>
		<!-- /wp:heading --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
