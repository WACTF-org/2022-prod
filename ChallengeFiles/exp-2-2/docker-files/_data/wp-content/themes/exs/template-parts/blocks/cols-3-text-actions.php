<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'verticalAlignment' => 'top',
	'section' => true,
	'colsSingle' => 'cols-single-sm',
	'gap' => 'gap-30',
	'pt' => 'pt-3',
	'pb' => 'pb-3',
	'background' => 'l m',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>"><!-- wp:column {"verticalAlignment":"top","className":""} -->
	<div class="wp-block-column is-vertically-aligned-top"><!-- wp:heading {"level":3,"className":"mb-0","textColor":"main","mb":"mb-0"} -->
		<h3 class="mb-0 has-main-color has-text-color">Nulla bibendum interdum!</h3>
		<!-- /wp:heading -->

		<!-- wp:heading {"className":"mt-0 mb-2","mt":"mt-0","mb":"mb-2"} -->
		<h2 class="mt-0 mb-2"><strong>Feugiat dolor eleifend vitae</strong></h2>
		<!-- /wp:heading -->

		<!-- wp:buttons {"className":""} -->
		<div class="wp-block-buttons"><!-- wp:button {"className":""} -->
			<div class="wp-block-button"><a class="wp-block-button__link" href="#">Mauris Blandit</a></div>
			<!-- /wp:button --></div>
		<!-- /wp:buttons --></div>
	<!-- /wp:column -->

	<!-- wp:column {"verticalAlignment":"top","className":""} -->
	<div class="wp-block-column is-vertically-aligned-top"><!-- wp:heading {"level":4,"className":"mb-05","mb":"mb-05"} -->
		<h4 class="mb-05"><strong>Risus</strong> iaculis diam</h4>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":""} -->
		<p class="">Etiam eu risus porttitor nulla bibendum interdum eu iaculis diam. Aenean auctor orci sem, ut feugiat dolor eleifend vitae.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"className":""} -->
		<p class="">Nam eleifend elit in odio vehicula, bibendum efficitur mauris blandit. </p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"verticalAlignment":"top","className":""} -->
	<div class="wp-block-column is-vertically-aligned-top"><!-- wp:heading {"level":4,"className":"mb-05","mb":"mb-05"} -->
		<h4 class="mb-05"><strong>Sed</strong> in sapien ac</h4>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":""} -->
		<p class="">Nulla posuere sed quam at lacinia. Praesent in sapien ac sem aliquet volutpat.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"className":""} -->
		<p class="">Maecenas metus enim, finibus id orci ultrices, ornare dapibus libero. Vivamus luctus feugiat felis nec cursus.&nbsp;</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->