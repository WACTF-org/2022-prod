<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'colsShadow' => true,
	'colsShadowHover' => true,
	'pt' => 'pt-3',
	'pb' => 'pb-2',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>"><!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:group {"className":""} -->
		<div class="wp-block-group"><div class="wp-block-group__inner-container"><!-- wp:heading {"textAlign":"center","className":"","backgroundColor":"font","textColor":"light"} -->
				<h2 class="has-text-align-center has-light-color has-font-background-color has-text-color has-background">Mauris</h2>
				<!-- /wp:heading -->

				<!-- wp:group {"className":"extra-padding","padding":true} -->
				<div class="wp-block-group extra-padding"><div class="wp-block-group__inner-container"><!-- wp:heading {"textAlign":"center","level":3,"className":""} -->
						<h3 class="has-text-align-center">$0<sub>/vel</sub></h3>
						<!-- /wp:heading -->

						<!-- wp:list {"className":"styled bordersli","styled":true,"bordersli":true} -->
						<ul class="styled bordersli"><li>Praesent luctus</li><li>Mauris hendrerit</li><li><s>Duis iaculis</s></li><li><s>Quisque malesuada</s></li><li><s>Vestibulum fringilla</s></li></ul>
						<!-- /wp:list -->

						<!-- wp:buttons {"align":"center","className":""} -->
						<div class="wp-block-buttons aligncenter"><!-- wp:button {"className":"is-style-outline mt-05 mb-1","mt":"mt-05","mb":"mb-1"} -->
							<div class="wp-block-button is-style-outline mt-05 mb-1"><a class="wp-block-button__link">Suspendisse</a></div>
							<!-- /wp:button --></div>
						<!-- /wp:buttons --></div></div>
				<!-- /wp:group --></div></div>
		<!-- /wp:group --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:group {"className":""} -->
		<div class="wp-block-group"><div class="wp-block-group__inner-container"><!-- wp:heading {"textAlign":"center","className":"","backgroundColor":"dark-muted","textColor":"light"} -->
				<h2 class="has-text-align-center has-light-color has-dark-muted-background-color has-text-color has-background">Nullam</h2>
				<!-- /wp:heading -->

				<!-- wp:group {"className":"extra-padding","padding":true} -->
				<div class="wp-block-group extra-padding"><div class="wp-block-group__inner-container"><!-- wp:heading {"textAlign":"center","level":3,"className":""} -->
						<h3 class="has-text-align-center">$3.99<sub>/vel</sub></h3>
						<!-- /wp:heading -->

						<!-- wp:list {"className":"styled bordersli","styled":true,"bordersli":true} -->
						<ul class="styled bordersli"><li>Praesent luctus</li><li>Mauris hendrerit</li><li>Duis iaculis</li><li><s>Quisque malesuada</s></li><li><s>Vestibulum fringilla</s></li></ul>
						<!-- /wp:list -->

						<!-- wp:buttons {"align":"center","className":""} -->
						<div class="wp-block-buttons aligncenter"><!-- wp:button {"className":"is-style-outline mt-05 mb-1","mt":"mt-05","mb":"mb-1"} -->
							<div class="wp-block-button is-style-outline mt-05 mb-1"><a class="wp-block-button__link">Suspendisse</a></div>
							<!-- /wp:button --></div>
						<!-- /wp:buttons --></div></div>
				<!-- /wp:group --></div></div>
		<!-- /wp:group --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:group {"className":""} -->
		<div class="wp-block-group"><div class="wp-block-group__inner-container"><!-- wp:heading {"textAlign":"center","className":"","backgroundColor":"main","textColor":"light"} -->
				<h2 class="has-text-align-center has-light-color has-main-background-color has-text-color has-background">Aliquam</h2>
				<!-- /wp:heading -->

				<!-- wp:group {"className":"extra-padding","padding":true} -->
				<div class="wp-block-group extra-padding"><div class="wp-block-group__inner-container"><!-- wp:heading {"textAlign":"center","level":3,"className":""} -->
						<h3 class="has-text-align-center">$39.99<sub>/nisi</sub></h3>
						<!-- /wp:heading -->

						<!-- wp:list {"className":"styled bordersli","styled":true,"bordersli":true} -->
						<ul class="styled bordersli"><li>Praesent luctus</li><li>Mauris hendrerit</li><li>Duis iaculis</li><li>Quisque malesuada</li><li>Vestibulum fringilla</li></ul>
						<!-- /wp:list -->

						<!-- wp:buttons {"align":"center","className":""} -->
						<div class="wp-block-buttons aligncenter"><!-- wp:button {"backgroundColor":"main","className":"mt-05 mb-1","mt":"mt-05","mb":"mb-1"} -->
							<div class="wp-block-button mt-05 mb-1"><a class="wp-block-button__link has-main-background-color has-background">Suspendisse</a></div>
							<!-- /wp:button --></div>
						<!-- /wp:buttons --></div></div>
				<!-- /wp:group --></div></div>
		<!-- /wp:group --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column"><!-- wp:group {"className":""} -->
		<div class="wp-block-group"><div class="wp-block-group__inner-container"><!-- wp:heading {"textAlign":"center","className":"","backgroundColor":"dark-muted","textColor":"light"} -->
				<h2 class="has-text-align-center has-light-color has-dark-muted-background-color has-text-color has-background">Phasellus</h2>
				<!-- /wp:heading -->

				<!-- wp:group {"className":"extra-padding","padding":true} -->
				<div class="wp-block-group extra-padding"><div class="wp-block-group__inner-container"><!-- wp:heading {"textAlign":"center","level":3,"className":""} -->
						<h3 class="has-text-align-center">$199.00<sub>/nisi</sub></h3>
						<!-- /wp:heading -->

						<!-- wp:list {"className":"styled bordersli","styled":true,"bordersli":true} -->
						<ul class="styled bordersli"><li>Praesent luctus</li><li>Mauris hendrerit</li><li>Duis iaculis</li><li>Quisque malesuada</li><li>Vestibulum fringilla</li></ul>
						<!-- /wp:list -->

						<!-- wp:buttons {"align":"center","className":""} -->
						<div class="wp-block-buttons aligncenter"><!-- wp:button {"className":"is-style-fill mt-05 mb-1","mt":"mt-05","mb":"mb-1"} -->
							<div class="wp-block-button is-style-fill mt-05 mb-1"><a class="wp-block-button__link">Suspendisse</a></div>
							<!-- /wp:button --></div>
						<!-- /wp:buttons --></div></div>
				<!-- /wp:group --></div></div>
		<!-- /wp:group --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
