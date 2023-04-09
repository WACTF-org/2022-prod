<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'colsShadowHover' => true,
	'colsPadding' => true,
	'colsSingle' => 'cols-single-sm',
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
		<!-- wp:image {"align":"wide","id":441,"sizeSlug":"large","className":""} -->
		<figure class="wp-block-image alignwide size-large">
			<img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/person.png" alt="" class="wp-image-441"/>
		</figure>
		<!-- /wp:image -->
		<!-- wp:heading {"align":"center","level":3,"className":"mt-1 mb-0","mt":"mt-1","mb":"mb-0"} -->
		<h3 class="has-text-align-center mt-1 mb-0">Laboris <strong>Commodo</strong>
		</h3>
		<!-- /wp:heading -->
		<!-- wp:heading {"align":"center","level":6,"textColor":"main","className":"mt-0 mb-1","mt":"mt-0","mb":"mb-1"} -->
		<h6 class="has-main-color has-text-color has-text-align-center mt-0 mb-1">Anim</h6>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","className":"mb-0","mb":"mb-0"} -->
		<p class="has-text-align-center mb-0">
			Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu. </p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->
	<!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:image {"align":"wide","id":442,"sizeSlug":"large","className":""} -->
		<figure class="wp-block-image alignwide size-large">
			<img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/person.png" alt="" class="wp-image-442"/>
		</figure>
		<!-- /wp:image -->
		<!-- wp:heading {"align":"center","level":3,"className":"mt-1 mb-0","mt":"mt-1","mb":"mb-0"} -->
		<h3 class="has-text-align-center mt-1 mb-0">Irure <strong>Voluptate</strong>
		</h3>
		<!-- /wp:heading -->
		<!-- wp:heading {"align":"center","level":6,"textColor":"main","className":"mt-0 mb-1","mt":"mt-0","mb":"mb-1"} -->
		<h6 class="has-main-color has-text-color has-text-align-center mt-0 mb-1">Cupidatat</h6>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","className":"mb-0","mb":"mb-0"} -->
		<p class="has-text-align-center mb-0">
			Excepteur sint cupidatat non proident, sunt in culpa qui officia deserunt.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->
	<!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:image {"align":"wide","id":443,"sizeSlug":"large","className":""} -->
		<figure class="wp-block-image alignwide size-large">
			<img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/person.png" alt="" class="wp-image-443"/>
		</figure>
		<!-- /wp:image -->
		<!-- wp:heading {"align":"center","level":3,"className":"mt-1 mb-0","mt":"mt-1","mb":"mb-0"} -->
		<h3 class="has-text-align-center mt-1 mb-0">Aliquip <strong>Mollit</strong>
		</h3>
		<!-- /wp:heading -->
		<!-- wp:heading {"align":"center","level":6,"textColor":"main","className":"mt-0 mb-1","mt":"mt-0","mb":"mb-1"} -->
		<h6 class="has-main-color has-text-color has-text-align-center mt-0 mb-1">Officia</h6>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","className":"mb-0","mb":"mb-0"} -->
		<p class="has-text-align-center mb-0">
			Esse cillum sunt in culpa esse voluptate dolore eu fugiat nulla pariatur. </p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->
	<!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:image {"align":"wide","id":444,"sizeSlug":"large","className":""} -->
		<figure class="wp-block-image alignwide size-large">
			<img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/person.png" alt="" class="wp-image-444"/>
		</figure>
		<!-- /wp:image -->
		<!-- wp:heading {"align":"center","level":3,"className":"mt-1 mb-0","mt":"mt-1","mb":"mb-0"} -->
		<h3 class="has-text-align-center mt-1 mb-0">Ullamco <strong>Minim</strong>
		</h3>
		<!-- /wp:heading -->
		<!-- wp:heading {"align":"center","level":6,"textColor":"main","className":"mt-0 mb-1","mt":"mt-0","mb":"mb-1"} -->
		<h6 class="has-main-color has-text-color has-text-align-center mt-0 mb-1">Pariatur</h6>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","className":"mb-0","mb":"mb-0"} -->
		<p class="has-text-align-center mb-0">
			Irure dolor in reprehenderit velit esse cillum dolore eu fugiat nulla. </p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->
