<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'section'       => false,
	'colsHighlight' => false,
	'colsShadow'    => false,
	'colsPadding'   => false,
	'colsSingle'    => 'cols-single-sm',
	'gap'           => 'gap-30',
	'pt'            => 'pt-5',
	'pb'            => 'pb-3',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>"><!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":270,"width":75,"height":75,"sizeSlug":"large","className":""} -->
		<div class="wp-block-image"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/icon.png" alt="" class="wp-image-270" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":5,"className":"mt-05 mb-05","mt":"mt-05","mb":"mb-05"} -->
		<h5 class="has-text-align-center mt-05 mb-05"><strong>Excepteurnt</strong></h5>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":""} -->
		<p class="has-text-align-center">Duis aute irure dolor in reprehenderit in voluptate velit esse nulla pariatur</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":479,"width":75,"height":75,"sizeSlug":"large","className":""} -->
		<div class="wp-block-image"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/bulb-outline.png" alt="" class="wp-image-479" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":5,"className":"mt-05 mb-05","mt":"mt-05","mb":"mb-05"} -->
		<h5 class="has-text-align-center mt-05 mb-05"><strong>Deserunt</strong></h5>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":""} -->
		<p class="has-text-align-center">Excepteurnt in culpa qui officia deserunt mollit anim id est laborum efficitur ipsum</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":480,"width":75,"height":75,"sizeSlug":"large","className":"is-style-default"} -->
		<div class="wp-block-image is-style-default"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/color-wand-outline.png" alt="" class="wp-image-480" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":5,"className":"mt-05 mb-05","mt":"mt-05","mb":"mb-05"} -->
		<h5 class="has-text-align-center mt-05 mb-05"><strong>Pariatur</strong></h5>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":""} -->
		<p class="has-text-align-center">Esse cillum dolore eu fugiat duis aute irure  cillum dolore eu fugiat nulla pariatur</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:image {"align":"center","id":487,"width":75,"height":75,"sizeSlug":"large","className":"is-style-default"} -->
		<div class="wp-block-image is-style-default"><figure class="aligncenter size-large is-resized"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/help-buoy.png" alt="" class="wp-image-487" width="75" height="75"/></figure></div>
		<!-- /wp:image -->

		<!-- wp:heading {"align":"center","level":5,"className":"mt-05 mb-05","mt":"mt-05","mb":"mb-05"} -->
		<h5 class="has-text-align-center mt-05 mb-05"><strong>Posuere</strong></h5>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":""} -->
		<p class="has-text-align-center">Integer ante dui, posuere quis diam sed lobortis viverra est  quis tempus diam</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
