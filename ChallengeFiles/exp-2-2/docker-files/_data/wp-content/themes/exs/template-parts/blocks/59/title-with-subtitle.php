<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'section' => false,
	'pt' => 'pt-1',
	'pb' => 'pb-1',
	'background' => '',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:group <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-group <?php echo esc_attr( $args['className'] ); ?>">
	<!-- wp:heading {"textAlign":"center","className":"mb-05","fontSize":"xxl","mb":"mb-05"} -->
	<h2 class="has-text-align-center mb-05 has-xxl-font-size">Curabitur <strong>Mauris</strong></h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","className":"","fontSize":"normal"} -->
	<p class="has-text-align-center has-normal-font-size">Consectetur adipisicing elit. Accusamus architecto aut commodi<br> cupiditate dicta earum error esse fugiat.</p>
	<!-- /wp:paragraph -->

	<!-- wp:separator {"className":"center mt-2","center":true,"mt":"mt-2"} -->
	<hr class="wp-block-separator center mt-2"/>
	<!-- /wp:separator --></div>
<!-- /wp:group -->
