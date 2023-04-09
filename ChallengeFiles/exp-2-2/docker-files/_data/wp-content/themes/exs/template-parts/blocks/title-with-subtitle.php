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
		<!-- wp:heading {"align":"center","className":"mt-3 mb-05","mt":"mt-3","mb":"mb-05"} -->
		<h2 class="has-text-align-center mt-3 mb-05">Lorem <strong>Ipsum</strong></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":""} -->
		<p class="has-text-align-center">Consectetur adipisicing elit. Accusamus architecto aut commodi<br> cupiditate dicta earum error esse fugiat.</p>
		<!-- /wp:paragraph -->

		<!-- wp:separator {"className":"center mt-2","center":true,"mt":"mt-2"} -->
		<hr class="wp-block-separator center mt-2"/>
		<!-- /wp:separator --></div>
</div>
<!-- /wp:group -->
