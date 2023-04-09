<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'section' => true,
	'shadow' => true,
	'rounded' => true,
	'pt' => 'pt-4',
	'pb' => 'pb-4',
	'background' => 'l m',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:group <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-group <?php echo esc_attr( $args['className'] ); ?>">
	<div class="wp-block-group__inner-container">
		<!-- wp:paragraph {"align":"center","className":"mb-05","mb":"mb-05"} -->
		<p class="has-text-align-center mb-05"><span class="has-inline-color has-main-color"><strong>CURABITUR</strong> LAOREET ERAT CURSUS</span>
		</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"textAlign":"center","className":"mt-0","mt":"mt-0"} -->
		<h2 class="has-text-align-center mt-0"><strong>Mauris id ligula</strong> in massa <strong>ullamcorper
				sodales</strong> in vel leo.<br>Duis consequat augue id nisi iaculis.<br>Non justo ullamcorper.</h2>
		<!-- /wp:heading -->

		<!-- wp:buttons {"align":"center","className":"mt-3","mt":"mt-3"} -->
		<div class="wp-block-buttons aligncenter mt-3"><!-- wp:button {"className":"mt-05","mt":"mt-05"} -->
			<div class="wp-block-button mt-05"><a class="wp-block-button__link" href="#">Sagittis Tortor</a></div>
			<!-- /wp:button --></div>
		<!-- /wp:buttons --></div>
</div>
<!-- /wp:group -->
