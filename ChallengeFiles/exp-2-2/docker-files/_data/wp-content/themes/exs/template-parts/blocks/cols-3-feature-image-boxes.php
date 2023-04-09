<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'colsBordered' => true,
	'colsPadding' => true,
	'gap' => 'gap-30',
	'pt' => 'pt-2',
	'pb' => 'pb-3',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>">
	<!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:image {"align":"center","id":270,"sizeSlug":"large","className":""} -->
		<div class="wp-block-image">
			<figure class="aligncenter size-large">
				<img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/pie-chart-outline.png" alt="" class="wp-image-270"/>
			</figure>
		</div>
		<!-- /wp:image -->
		<!-- wp:heading {"align":"center","level":3,"className":"mt-05 mb-05","mt":"mt-05","mb":"mb-05"} -->
		<h3 class="has-text-align-center mt-05 mb-05">Laboris</h3>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","className":""} -->
		<p class="has-text-align-center">Duis aute irure dolor in
			reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p>
		<!-- /wp:paragraph -->
		<!-- wp:button {"align":"center",className":""} -->
		<div class="wp-block-button aligncenter">
			<a class="wp-block-button__link" href="#">Lorem Ipsum</a>
		</div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:column -->
	<!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:image {"align":"center","id":479,"sizeSlug":"large","className":""} -->
		<div class="wp-block-image">
			<figure class="aligncenter size-large">
				<img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/bulb-outline.png" alt="" class="wp-image-479"/>
			</figure>
		</div>
		<!-- /wp:image -->
		<!-- wp:heading {"align":"center","level":3,"className":"mt-05 mb-05","mt":"mt-05","mb":"mb-05"} -->
		<h3 class="has-text-align-center mt-05 mb-05">Fugiat</h3>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","className":""} -->
		<p class="has-text-align-center">Excepteur sint cupidatat
			non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		<!-- /wp:paragraph -->
		<!-- wp:button {"align":"center",className":""} -->
		<div class="wp-block-button aligncenter">
			<a class="wp-block-button__link" href="#">Lorem Ipsum</a>
		</div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:column -->
	<!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:image {"align":"center","id":480,"sizeSlug":"large","className":"is-style-default"} -->
		<div class="wp-block-image is-style-default">
			<figure class="aligncenter size-large">
				<img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/color-wand-outline.png" alt="" class="wp-image-480"/>
			</figure>
		</div>
		<!-- /wp:image -->
		<!-- wp:heading {"align":"center","level":3,"className":"mt-05 mb-05","mt":"mt-05","mb":"mb-05"} -->
		<h3 class="has-text-align-center mt-05 mb-05">Consectetur</h3>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","className":""} -->
		<p class="has-text-align-center">Esse cillum dolore eu
			fugiat duis aute irure dolor in velit esse cillum dolore eu fugiat nulla pariatur. </p>
		<!-- /wp:paragraph -->
		<!-- wp:button {"align":"center",className":""} -->
		<div class="wp-block-button aligncenter">
			<a class="wp-block-button__link" href="#">Lorem Ipsum</a>
		</div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->
