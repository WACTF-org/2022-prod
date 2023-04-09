<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'section'       => '',
	'colsHighlight' => '',
	'colsShadow'    => '',
	'colsPadding'   => '',
	'pt'            => 'pt-3',
	'pb'            => 'pb-3',
	'background'    => 'l',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>"><!-- wp:column {"verticalAlignment":"center"} -->
	<div class="wp-block-column is-vertically-aligned-center"><!-- wp:paragraph {"className":"mb-0","mb":"mb-0"} -->
		<p class="mb-0">Ut sit amet neque urna. Curabitur semper ultrices erat, ac hendrerit tellus porttitor in. Nulla vestibulum libero quam, nec rhoncus enim aliquet quis. </p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"verticalAlignment":"center"} -->
	<div class="wp-block-column is-vertically-aligned-center"><!-- wp:paragraph {"className":"mb-0","mb":"mb-0"} -->
		<p class="mb-0">Id pretium urna nisi vel lacus. Aenean vitae malesuada massa, in commodo sapien. Pellentesque sit amet diam odio. Nunc ut tempor orci. </p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->