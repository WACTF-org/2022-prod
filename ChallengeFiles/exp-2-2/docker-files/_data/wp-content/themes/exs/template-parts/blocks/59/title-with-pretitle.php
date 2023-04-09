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
	<!-- wp:heading {"textAlign":"center","className":"is-style-small-text"} -->
	<h2 class="has-text-align-center is-style-small-text"><mark style="background-color:var(--colorMain)" class="has-inline-color has-light-color">nunc elementum</mark></h2>
	<!-- /wp:heading -->

	<!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"top":"15px","bottom":"20px"}}},"className":"","fontSize":"huge"} -->
	<h2 class="has-text-align-center has-huge-font-size" style="margin-top:15px;margin-bottom:20px">Donec vulputate <mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-main-color">viverra venenatis</mark></h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","className":"","fontSize":"normal"} -->
	<p class="has-text-align-center has-normal-font-size">Mauris egestas mi eget dolor laoreet, ac facilisis nunc hendrerit.<br>Integer malesuada nisi vitae tortor iaculis luctus.</p>
	<!-- /wp:paragraph --></div>
<!-- /wp:group -->
