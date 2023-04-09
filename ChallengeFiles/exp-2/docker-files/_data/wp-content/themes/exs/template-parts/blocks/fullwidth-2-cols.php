<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'verticalAlignment' => 'center',
	'align' => 'full',
	'colsSingle' => 'cols-single-lg',
	'gap' => 'gap-0',
	'background' => 'l m',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>">
	<!-- wp:column {"verticalAlignment":"center","width":"50%","className":""} -->
	<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%">
		<!-- wp:image {"id":395,"sizeSlug":"full","className":"mb-0","mb":"mb-0"} -->
		<figure class="wp-block-image size-full mb-0"><img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/cup-square.jpg" alt=""
														   class="wp-image-395"/></figure>
		<!-- /wp:image --></div>
	<!-- /wp:column -->

	<!-- wp:column {"verticalAlignment":"center","width":"50%","className":"p-big","padding":"p-big"} -->
	<div class="wp-block-column is-vertically-aligned-center p-big" style="flex-basis:50%">
		<!-- wp:heading {"className":"mb-0 mt-1","mt":"mt-1","mb":"mb-0"} -->
		<h2 class="mb-0 mt-1"><strong>Cras massa</strong>: magna consequat magna accumsan ullamcorper <strong>eros
				sodales</strong></h2>
		<!-- /wp:heading -->

		<!-- wp:separator {"className":"mt-2 mb-2","mt":"mt-2","mb":"mb-2"} -->
		<hr class="wp-block-separator mt-2 mb-2"/>
		<!-- /wp:separator -->

		<!-- wp:paragraph {"className":""} -->
		<p class="">Donec rhoncus sit <strong>amet magna</strong> sit amet egestas.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"className":""} -->
		<p class="">Nulla ullamcorper ultricies sapien, at suscipit nibh auctor eu. Donec auctor sapien sit amet mauris
			semper, vitae dictum enim vestibulum. </p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"className":"mt-3 mb-1","mt":"mt-3","mb":"mb-1"} -->
		<div class="wp-block-buttons mt-3 mb-1"><!-- wp:button {"className":""} -->
			<div class="wp-block-button"><a class="wp-block-button__link" href="#">Dictum Enim</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"backgroundColor":"main","className":""} -->
			<div class="wp-block-button"><a class="wp-block-button__link has-main-background-color has-background"
											href="#">Vitae Donec</a></div>
			<!-- /wp:button --></div>
		<!-- /wp:buttons --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
