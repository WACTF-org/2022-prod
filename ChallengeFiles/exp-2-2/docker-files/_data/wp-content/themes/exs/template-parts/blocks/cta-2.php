<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'section' => true,
	'pt' => 'pt-4',
	'pb' => 'pb-4',
	'background' => 'l',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:group <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-group <?php echo esc_attr( $args['className'] ); ?>">
	<div class="wp-block-group__inner-container">
		<!-- wp:heading {"textAlign":"center","className":"mb-05","mb":"mb-05"} -->
		<h2 class="has-text-align-center mb-05"><strong>Mauris id ligula</strong> in massa <strong>ullamcorper
				sodales</strong> in vel leo.<br>Duis consequat augue id nisi iaculis.<br>Non justo ullamcorper.</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":""} -->
		<p class="has-text-align-center"><span
					class="has-inline-color has-main-color"><strong>Curabitur semper ultrices</strong></span></p>
		<!-- /wp:paragraph -->

		<!-- wp:group {"className":""} -->
		<div class="wp-block-group">
			<div class="wp-block-group__inner-container">
				<!-- wp:buttons {"align":"center","className":"mt-3","mt":"mt-3"} -->
				<div class="wp-block-buttons aligncenter mt-3">
					<!-- wp:button {"backgroundColor":"main","className":""} -->
					<div class="wp-block-button"><a
								class="wp-block-button__link has-main-background-color has-background" href="#">Magna
							Varius</a></div>
					<!-- /wp:button -->

					<!-- wp:button {"className":""} -->
					<div class="wp-block-button"><a class="wp-block-button__link" href="#">Sed Consequat</a></div>
					<!-- /wp:button --></div>
				<!-- /wp:buttons --></div>
		</div>
		<!-- /wp:group --></div>
</div>
<!-- /wp:group -->
