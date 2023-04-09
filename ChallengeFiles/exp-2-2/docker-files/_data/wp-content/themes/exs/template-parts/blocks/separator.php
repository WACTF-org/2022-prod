<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//className: is-style-default is-style-wide is-style-dots

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'mt'            => 'mt-3',
	'mb'            => 'mb-3',
//	'className'     => 'is-style-wide',
//	'center'        => 'true',
//	'color'         => 'main',
//	'align'         => 'full',
//	'height'        => 'h-3',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:separator <?php echo wp_kses( $args['json'], array() ); ?> -->
<hr class="wp-block-separator <?php echo esc_attr( $args['className'] ); ?>"/>
<!-- /wp:separator -->
