<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'section' => true,
	'colsSingle' => 'cols-single-md',
	'gap' => 'gap-60',
	'pt' => 'pt-3',
	'pb' => 'pb-1',
	'background' => 'l',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>"><!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:heading {"textAlign":"center","className":""} -->
		<h2 class="has-text-align-center"><strong>Mauris</strong> id ligula in massa ullamcorper sodales <strong>in vel
				leo</strong></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":""} -->
		<p class="has-text-align-center">Sed at sapien convallis ex congue rutrum sed at augue. Vivamus luctus feugiat
			felis nec cursus. Curabitur justo augue, venenatis id tellus eget, consequat tempus nulla.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"align":"center","className":""} -->
		<div class="wp-block-buttons aligncenter">
			<!-- wp:button {"backgroundColor":"main","className":"mt-3","mt":"mt-3"} -->
			<div class="wp-block-button mt-3"><a class="wp-block-button__link has-main-background-color has-background"
												 href="#">Magna Varius</a></div>
			<!-- /wp:button --></div>
		<!-- /wp:buttons --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:columns {"className":"cols-single-none gap-15","colsSingle":"cols-single-none","gap":"gap-15"} -->
		<div class="wp-block-columns cols-single-none gap-15"><!-- wp:column {"width":"10%","className":""} -->
			<div class="wp-block-column" style="flex-basis:10%">
				<!-- wp:image {"id":485,"width":50,"height":50,"sizeSlug":"large","className":""} -->
				<figure class="wp-block-image size-large is-resized"><img
							src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/planet-outline.png" alt=""
							class="wp-image-485" width="50" height="50"/></figure>
				<!-- /wp:image -->

				<!-- wp:paragraph {"className":""} -->
				<p class=""></p>
				<!-- /wp:paragraph --></div>
			<!-- /wp:column -->

			<!-- wp:column {"width":"90%","className":""} -->
			<div class="wp-block-column" style="flex-basis:90%">
				<!-- wp:heading {"level":4,"className":"mb-05","mb":"mb-05"} -->
				<h4 class="mb-05"><strong>Curabitur </strong>semper</h4>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"className":""} -->
				<p class="">Praesent dictum rutrum arcu sit amet dignissim. Nullam id ipsum eu neque tristique pulvinar.
					Aliquam gravida et justo faucibus tempus.</p>
				<!-- /wp:paragraph --></div>
			<!-- /wp:column --></div>
		<!-- /wp:columns -->

		<!-- wp:columns {"className":"cols-single-none gap-15","colsSingle":"cols-single-none","gap":"gap-15"} -->
		<div class="wp-block-columns cols-single-none gap-15"><!-- wp:column {"width":"10%","className":""} -->
			<div class="wp-block-column" style="flex-basis:10%">
				<!-- wp:image {"id":486,"width":50,"height":50,"sizeSlug":"large","className":""} -->
				<figure class="wp-block-image size-large is-resized"><img
							src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/settings-outline.png" alt=""
							class="wp-image-486" width="50" height="50"/></figure>
				<!-- /wp:image -->

				<!-- wp:paragraph {"className":""} -->
				<p class=""></p>
				<!-- /wp:paragraph --></div>
			<!-- /wp:column -->

			<!-- wp:column {"width":"90%","className":""} -->
			<div class="wp-block-column" style="flex-basis:90%">
				<!-- wp:heading {"level":4,"className":"mb-05","mb":"mb-05"} -->
				<h4 class="mb-05"><strong>Quisque </strong>a sagittis</h4>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"className":""} -->
				<p class="">Suspendisse quis convallis turpis. Nulla posuere, justo id mollis bibendum, felis magna
					rhoncus lacus, id placerat tellus purus vel est.</p>
				<!-- /wp:paragraph --></div>
			<!-- /wp:column --></div>
		<!-- /wp:columns -->

		<!-- wp:columns {"className":"cols-single-none gap-15 pb-2","colsSingle":"cols-single-none","gap":"gap-15","pb":"pb-2"} -->
		<div class="wp-block-columns cols-single-none gap-15 pb-2"><!-- wp:column {"width":"10%","className":""} -->
			<div class="wp-block-column" style="flex-basis:10%">
				<!-- wp:image {"id":487,"width":50,"height":50,"sizeSlug":"large","className":""} -->
				<figure class="wp-block-image size-large is-resized"><img
							src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/help-buoy.png" alt="" class="wp-image-487"
							width="50" height="50"/></figure>
				<!-- /wp:image -->

				<!-- wp:paragraph {"className":""} -->
				<p class=""></p>
				<!-- /wp:paragraph --></div>
			<!-- /wp:column -->

			<!-- wp:column {"width":"90%","className":""} -->
			<div class="wp-block-column" style="flex-basis:90%">
				<!-- wp:heading {"level":4,"className":"mb-05","mb":"mb-05"} -->
				<h4 class="mb-05"><strong>Sed </strong>consequat</h4>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"className":""} -->
				<p class="">Donec consectetur quam id consectetur luctus. Suspendisse diam quam, consectetur nec diam
					sed, blandit vehicula libero.</p>
				<!-- /wp:paragraph --></div>
			<!-- /wp:column --></div>
		<!-- /wp:columns --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
