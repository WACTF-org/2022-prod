<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'verticalAlignment' => 'center',
	'colsSingle' => 'cols-single-sm',
	'gap' => 'gap-20',
	'background' => 'l m',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>">
	<!-- wp:column {"verticalAlignment":"center","className":""} -->
	<div class="wp-block-column is-vertically-aligned-center">
		<!-- wp:image {"id":444,"sizeSlug":"full","className":"mb-0","mb":"mb-0"} -->
		<figure class="wp-block-image size-full mb-0"><img
					src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/person.png" alt="" class="wp-image-444"/>
		</figure>
		<!-- /wp:image --></div>
	<!-- /wp:column -->

	<!-- wp:column {"verticalAlignment":"center","width":"60%","className":"p-2","padding":"p-2"} -->
	<div class="wp-block-column is-vertically-aligned-center p-2" style="flex-basis:60%">
		<!-- wp:paragraph {"className":"mb-0","mb":"mb-0"} -->
		<p class="mb-0"><span class="has-inline-color has-main-color">CRAS MASSA MAGNA, ACCUMSAN ULLAMCORPER</span></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"className":"mt-0","mt":"mt-0"} -->
		<h2 class="mt-0">Donec felis eros, vestibulum et augue</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":""} -->
		<p class="">Vestibulum fringilla, est et faucibus dapibus, diam augue eleifend eros, non pellentesque justo
			turpis sit amet dui. Duis tristique magna varius leo dapibus molestie. Sed consequat magna. </p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"className":"mt-4","mt":"mt-4"} -->
		<div class="wp-block-buttons mt-4"><!-- wp:button {"className":""} -->
			<div class="wp-block-button"><a class="wp-block-button__link" href="#">Aliquet Quis</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link">Donec Eros</a></div>
			<!-- /wp:button --></div>
		<!-- /wp:buttons --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
