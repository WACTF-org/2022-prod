<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'verticalAlignment' => 'center',
	'section' => true,
	'colsSingle' => 'cols-single-none',
	'gap' => 'gap-10',
	'pt' => 'pt-4',
	'pb' => 'pb-4',
	'background' => 'l',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );
?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>"><!-- wp:column {"verticalAlignment":"center","width":"66.66%","className":""} -->
	<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:66.66%"><!-- wp:heading {"className":"mb-05","mb":"mb-05"} -->
		<h2 class="mb-05"><strong>Aenean</strong> non enim nec <strong>mi ultrices</strong>!</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":""} -->
		<p class=""><strong>Curabitur</strong> semper ultrices erat, ac hendrerit tellus porttitor in <strong>vestibulum libero</strong> quam. Sed consequat magna id justo malesuada, nec sodales ex placerat. Mauris aliquet <strong>maximus faucibus</strong>.</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"verticalAlignment":"center","width":"33.33%","className":""} -->
	<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:33.33%"><!-- wp:buttons {"align":"right","className":""} -->
		<div class="wp-block-buttons alignright"><!-- wp:button {"className":""} -->
			<div class="wp-block-button"><a class="wp-block-button__link" href="#">Duis consequat</a></div>
			<!-- /wp:button --></div>
		<!-- /wp:buttons --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->