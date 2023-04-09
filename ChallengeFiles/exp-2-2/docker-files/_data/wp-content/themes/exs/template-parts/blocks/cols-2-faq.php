<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'section' => true,
	'pt' => 'pt-3',
	'pb' => 'pb-2',
	'background' => 'l',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>"><!-- wp:column -->
	<div class="wp-block-column"><!-- wp:heading {"level":4,"className":""} -->
		<h4 class="">Exercitation ullamco laboris nisi?</h4>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":""} -->
		<p class="">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":4,"className":""} -->
		<h4 class="">Irure dolor in reprehenderit?</h4>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":""} -->
		<p class="">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column -->
	<div class="wp-block-column"><!-- wp:heading {"level":4,"className":""} -->
		<h4 class="">Officia deserunt mollit anim?</h4>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":""} -->
		<p class="">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":4,"className":""} -->
		<h4 class="">Aliquip ex ea commodo consequa?</h4>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":""} -->
		<p class="">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Excepteur sint occaecat cupidatat non proident nostrud exercitation ullamco.</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
