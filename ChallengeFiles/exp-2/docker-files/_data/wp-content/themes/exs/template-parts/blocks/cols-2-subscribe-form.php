<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"66.66%"} -->
	<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:paragraph {"className":"is-style-small-text mb-1","mb":"mb-1"} -->
		<p class="is-style-small-text mb-1"><mark style="background-color:var(--colorMain)" class="has-inline-color has-light-color">posuere suscipit</mark></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":3,"className":"mt-0 mb-1","mt":"mt-0","mb":"mb-1"} -->
		<h3 class="mt-0 mb-1">Nam commodo elit eget enim</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p class="">Nullam non metus dui. Ut vehicula laoreet neque non efficitur. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Etiam ligula massa, sollicitudin a finibus et, volutpat non neque.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p class="">Donec porta mauris nunc, ut imperdiet purus dapibus quis. In dapibus neque nisi, sed sagittis ante sollicitudin.</p>
		<!-- /wp:paragraph --></div>
	<!-- /wp:column -->

	<!-- wp:column {"width":"33.33%"} -->
	<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:group {"className":"extra-padding l m","padding":true,"background":"l m"} -->
		<div class="wp-block-group extra-padding l m">
			<?php get_template_part('template-parts/blocks/form-subscribe' ); ?>
		</div>
		<!-- /wp:group --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->