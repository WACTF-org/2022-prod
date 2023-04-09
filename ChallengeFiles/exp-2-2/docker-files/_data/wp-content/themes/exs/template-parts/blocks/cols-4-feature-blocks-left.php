<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'colsShadow' => true,
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
	<div class="wp-block-column"><!-- wp:image {"id":270,"width":75,"height":75,"sizeSlug":"large","className":""} -->
		<figure class="wp-block-image size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/icon.png" alt="" class="wp-image-270" width="75" height="75"/></figure>
		<!-- /wp:image -->

		<!-- wp:heading {"level":6,"className":"mt-0 mb-05","mt":"mt-0","mb":"mb-05"} -->
		<h6 class="mt-0 mb-05"><strong>Reprehenderit</strong></h6>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"mb-05","mb":"mb-05"} -->
		<p class="mb-05">Duis aute irure dolor in reprehenderit in voluptate velit esse nulla pariatur</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"className":"mb-0","mb":"mb-0"} -->
		<p class="mb-0"><a href="#">Velit Esse...</a></p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"id":479,"width":75,"height":75,"sizeSlug":"large","className":""} -->
		<figure class="wp-block-image size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/bulb-outline.png" alt="" class="wp-image-479" width="75" height="75"/></figure>
		<!-- /wp:image -->

		<!-- wp:heading {"level":6,"className":"mt-0 mb-05","mt":"mt-0","mb":"mb-05"} -->
		<h6 class="mt-0 mb-05"><strong>Mollit Anim</strong></h6>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"mb-05","mb":"mb-05"} -->
		<p class="mb-05">Excepteurnt in culpa qui officia deserunt mollit anim id est laborum efficitur</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"className":"mb-0","mb":"mb-0"} -->
		<p class="mb-0"><a href="#">Velit Esse...</a></p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"id":480,"width":75,"height":75,"sizeSlug":"large","className":"is-style-default"} -->
		<figure class="wp-block-image size-large is-resized is-style-default"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/color-wand-outline.png" alt="" class="wp-image-480" width="75" height="75"/></figure>
		<!-- /wp:image -->

		<!-- wp:heading {"level":6,"className":"mt-0 mb-05","mt":"mt-0","mb":"mb-05"} -->
		<h6 class="mt-0 mb-05"><strong>Cillum Dolore</strong></h6>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"mb-05","mb":"mb-05"} -->
		<p class="mb-05">Esse cillum dolore eu fugiat duis aute irure  cillum dolore eu fugiat nulla pariatur</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"className":"mb-0","mb":"mb-0"} -->
		<p class="mb-0"><a href="#">Velit Esse...</a></p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"id":487,"width":75,"height":75,"sizeSlug":"large","className":"is-style-default"} -->
		<figure class="wp-block-image size-large is-resized is-style-default"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/help-buoy.png" alt="" class="wp-image-487" width="75" height="75"/></figure>
		<!-- /wp:image -->

		<!-- wp:heading {"level":6,"className":"mt-0 mb-05","mt":"mt-0","mb":"mb-05"} -->
		<h6 class="mt-0 mb-05"><strong>Posuere Quis</strong></h6>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"mb-05","mb":"mb-05"} -->
		<p class="mb-05">Integer ante dui, posuere quis diam sed lobortis viverra est  quis tempus diam</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"className":"mb-0","mb":"mb-0"} -->
		<p class="mb-0"><a href="#">Velit Esse...</a></p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
