<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'section' => true,
	'colsHighlight' => true,
	'colsBordered' => true,
	'colsPadding' => true,
	'colsSingle' => 'cols-single-sm',
	'pt' => 'pt-3',
	'pb' => 'pb-3',
	'background' => 'l',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>"><!-- wp:column {"width":"66.66%","className":""} -->
	<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:heading {"level":3,"className":"mb-0","textColor":"main","mb":"mb-0"} -->
		<h3 class="mb-0 has-main-color has-text-color">Nulla bibendum interdum!</h3>
		<!-- /wp:heading -->

		<!-- wp:heading {"className":"mt-0","mt":"mt-0"} -->
		<h2 class="mt-0"><strong>Feugiat dolor eleifend vitae</strong></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":""} -->
		<p class="">Etiam eu risus porttitor nulla bibendum interdum eu iaculis diam. Aenean auctor orci sem, ut feugiat dolor eleifend vitae.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"className":""} -->
		<p class="">Nam eleifend elit in odio vehicula, bibendum efficitur mauris blandit. </p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"width":"33.33%","className":""} -->
	<div class="wp-block-column" style="flex-basis:33.33%">
		<?php get_template_part('template-parts/blocks/form-1' ); ?>
	</div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
