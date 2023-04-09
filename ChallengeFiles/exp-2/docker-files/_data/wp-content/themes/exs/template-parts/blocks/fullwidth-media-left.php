<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'align' => 'full',
	'background' => 'l m',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:group <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-group <?php echo esc_attr( $args['className'] ); ?>">
	<div class="wp-block-group__inner-container">
		<!-- wp:media-text {"align":"full","mediaId":405,"mediaType":"image","imageFill":true} -->
		<div class="wp-block-media-text alignfull is-stacked-on-mobile is-image-fill">
			<figure class="wp-block-media-text__media"
					style="background-image:url(<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/cup.jpg);background-position:50% 50%">
				<img src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/cup.jpg"
					 alt="" class="wp-image-405 size-full"/></figure>
			<div class="wp-block-media-text__content"><!-- wp:spacer {"className":"hidden-md","hidden":"hidden-md"} -->
				<div style="height:100px" aria-hidden="true" class="wp-block-spacer hidden-md"></div>
				<!-- /wp:spacer -->

				<!-- wp:heading {"className":"mt-2","mt":"mt-2"} -->
				<h2 class="mt-2" id="block-b841bda5-c108-45d3-a127-4c8d8b33a394"><strong>Cras massa</strong>: magna
					consequat magna accumsan ullamcorper <strong>eros sodales</strong></h2>
				<!-- /wp:heading -->

				<!-- wp:separator {"className":"mt-2 mb-2","mt":"mt-2","mb":"mb-2"} -->
				<hr class="wp-block-separator mt-2 mb-2"/>
				<!-- /wp:separator -->

				<!-- wp:paragraph {"className":""} -->
				<p class=""><strong>Donec rhoncus </strong>sit amet magna sit <strong>amet egestas.</strong></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":""} -->
				<p class="">Nulla ullamcorper ultricies sapien, at suscipit nibh auctor eu. Donec auctor sapien sit amet
					mauris semper, vitae dictum enim vestibulum. </p>
				<!-- /wp:paragraph -->

				<!-- wp:columns {"className":"cols-single-lg","colsSingle":"cols-single-lg"} -->
				<div class="wp-block-columns cols-single-lg"><!-- wp:column {"className":""} -->
					<div class="wp-block-column"><!-- wp:list {"className":"mb-2 styled","mb":"mb-2","styled":true} -->
						<ul class="mb-2 styled">
							<li>Ullamcorper ultricies</li>
							<li>Vitae dictum enim</li>
							<li>Enim vestibulum</li>
						</ul>
						<!-- /wp:list --></div>
					<!-- /wp:column -->

					<!-- wp:column {"className":""} -->
					<div class="wp-block-column"><!-- wp:list {"className":"mb-2 styled","mb":"mb-2","styled":true} -->
						<ul class="mb-2 styled">
							<li>Ullamcorper ultricies</li>
							<li>Vitae dictum enim</li>
							<li>Enim vestibulum</li>
						</ul>
						<!-- /wp:list --></div>
					<!-- /wp:column --></div>
				<!-- /wp:columns -->

				<!-- wp:buttons {"className":"mb-3","mb":"mb-3"} -->
				<div class="wp-block-buttons mb-3"><!-- wp:button {"className":""} -->
					<div class="wp-block-button"><a class="wp-block-button__link" href="#">Dictum Enim</a></div>
					<!-- /wp:button -->

					<!-- wp:button {"backgroundColor":"main","className":""} -->
					<div class="wp-block-button"><a
								class="wp-block-button__link has-main-background-color has-background" href="#">Vitae
							Donec</a></div>
					<!-- /wp:button --></div>
				<!-- /wp:buttons -->

				<!-- wp:spacer {"className":"hidden-md","hidden":"hidden-md"} -->
				<div style="height:100px" aria-hidden="true" class="wp-block-spacer hidden-md"></div>
				<!-- /wp:spacer -->

				<!-- wp:paragraph {"className":""} -->
				<p class=""></p>
				<!-- /wp:paragraph --></div>
		</div>
		<!-- /wp:media-text --></div>
</div>
<!-- /wp:group -->
