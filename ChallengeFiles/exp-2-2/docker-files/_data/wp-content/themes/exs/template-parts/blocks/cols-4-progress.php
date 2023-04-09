<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'colsShadowHover' => true,
	'colsRounded' => true,
	'colsPadding' => true,
	'colsSingle' => 'cols-single-sm',
	'gap' => 'gap-30',
	'pt' => 'pt-5',
	'pb' => 'pb-5',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>"><!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":568,"width":75,"height":75,"sizeSlug":"large","className":"mb-0","mb":"mb-0"} -->
		<div class="wp-block-image mb-0"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/pie-chart-outline.png" alt="" class="wp-image-568" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","className":"mt-0 mb-0 big","mt":"mt-0","mb":"mb-0","size":"big"} -->
		<h2 class="has-text-align-center mt-0 mb-0 big"><span class="has-inline-color has-main-color">100+</span></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":"mb-05","fontSize":"small","mb":"mb-05"} -->
		<p class="has-text-align-center mb-05 has-small-font-size">Venenatis Erat</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":479,"width":75,"height":75,"sizeSlug":"large","className":"mb-0","mb":"mb-0"} -->
		<div class="wp-block-image mb-0"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/bulb-outline.png" alt="" class="wp-image-479" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","className":"mt-0 mb-0 big","mt":"mt-0","mb":"mb-0","size":"big"} -->
		<h2 class="has-text-align-center mt-0 mb-0 big"><span class="has-inline-color has-main-color">20+</span></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":"mb-05","fontSize":"small","mb":"mb-05"} -->
		<p class="has-text-align-center mb-05 has-small-font-size">Nisi Euismod</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":500,"width":75,"height":75,"sizeSlug":"large","className":"is-style-default mb-0","mb":"mb-0"} -->
		<div class="wp-block-image is-style-default mb-0"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/alarm-outline.png" alt="" class="wp-image-500" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","className":"mt-0 mb-0 big","mt":"mt-0","mb":"mb-0","size":"big"} -->
		<h2 class="has-text-align-center mt-0 mb-0 big"><span class="has-inline-color has-main-color">200+</span></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":"mb-05","fontSize":"small","mb":"mb-05"} -->
		<p class="has-text-align-center mb-05 has-small-font-size">Proin Elit Leo</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":487,"width":75,"height":75,"sizeSlug":"large","className":"is-style-default mb-0","mb":"mb-0"} -->
		<div class="wp-block-image is-style-default mb-0"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/help-buoy.png" alt="" class="wp-image-487" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","className":"mt-0 mb-0 big","mt":"mt-0","mb":"mb-0","size":"big"} -->
		<h2 class="has-text-align-center mt-0 mb-0 big"><span class="has-inline-color has-main-color">3000+</span></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":"mb-05","fontSize":"small","mb":"mb-05"} -->
		<p class="has-text-align-center mb-05 has-small-font-size">Maecenas Tincidunt</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
