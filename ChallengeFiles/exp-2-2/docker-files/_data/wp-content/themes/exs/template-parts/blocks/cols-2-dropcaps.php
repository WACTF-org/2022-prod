<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'section' => true,
	'colsHighlight' => true,
	'colsPadding' => true,
	'pt' => 'pt-2',
	'pb' => 'pb-4',
	'background' => 'l',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>"><!-- wp:column -->
	<div class="wp-block-column"><!-- wp:paragraph {"dropCap":true,"className":""} -->
		<p class="has-drop-cap">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"className":"mb-0","mb":"mb-0"} -->
		<p class="mb-0">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column -->
	<div class="wp-block-column"><!-- wp:paragraph {"dropCap":true,"className":""} -->
		<p class="has-drop-cap">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"className":"mb-0","mb":"mb-0"} -->
		<p class="mb-0">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Excepteur sint occaecat cupidatat non proident nostrud exercitation ullamco.</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
