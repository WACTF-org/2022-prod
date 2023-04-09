<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	"section" => true,
	"pt" => "pt-2",
	"pb" => "pb-2",
	"background" => "l",
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:group <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-group <?php echo esc_attr( $args['className'] ); ?>">
	<div class="wp-block-group__inner-container">
		<!-- wp:media-text {"align":"","mediaId":908,"mediaType":"image","mediaWidth":35,"isStackedOnMobile":false,"verticalAlignment":"center","imageFill":false} -->
		<div class="wp-block-media-text is-vertically-aligned-center" style="grid-template-columns:35% auto">
			<figure class="wp-block-media-text__media"><img
						src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/phone.png" alt=""
						class="wp-image-908 size-full"/></figure>
			<div class="wp-block-media-text__content"><!-- wp:heading {"className":""} -->
				<h2 class=""><strong>Duis consequat</strong> augue id nisi iaculis non <strong>consequat justo</strong>
					ullamcorper</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"className":""} -->
				<p class="">Nulla ullamcorper ultricies sapien, at suscipit nibh auctor eu. </p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":""} -->
				<p class=""><em>Donec auctor sapien sit amet mauris semper, vitae dictum enim vestibulum.</em></p>
				<!-- /wp:paragraph -->

				<!-- wp:columns {"className":""} -->
				<div class="wp-block-columns"><!-- wp:column {"className":""} -->
					<div class="wp-block-column">
						<!-- wp:heading {"level":4,"className":"mb-05","textColor":"main","mb":"mb-05"} -->
						<h4 class="mb-05 has-main-color has-text-color">Curabitur semper ultrices</h4>
						<!-- /wp:heading -->

						<!-- wp:paragraph {"className":""} -->
						<p class="">Orci varius natoque penatibus et magnis dis parturient montes</p>
						<!-- /wp:paragraph --></div>
					<!-- /wp:column -->

					<!-- wp:column {"className":""} -->
					<div class="wp-block-column">
						<!-- wp:heading {"level":4,"className":"mb-05","textColor":"main","mb":"mb-05"} -->
						<h4 class="mb-05 has-main-color has-text-color">Cras et nisl in est</h4>
						<!-- /wp:heading -->

						<!-- wp:paragraph {"className":""} -->
						<p class="">Dis parturient montes orci varius natoque penatibus et magnis</p>
						<!-- /wp:paragraph --></div>
					<!-- /wp:column --></div>
				<!-- /wp:columns -->

				<!-- wp:columns {"className":""} -->
				<div class="wp-block-columns"><!-- wp:column {"className":""} -->
					<div class="wp-block-column">
						<!-- wp:heading {"level":4,"className":"mb-05","textColor":"main","mb":"mb-05"} -->
						<h4 class="mb-05 has-main-color has-text-color">Cras et nisl in est</h4>
						<!-- /wp:heading -->

						<!-- wp:paragraph {"className":""} -->
						<p class="">Orci varius natoque penatibus et magnis dis parturient montes</p>
						<!-- /wp:paragraph --></div>
					<!-- /wp:column -->

					<!-- wp:column {"className":""} -->
					<div class="wp-block-column">
						<!-- wp:heading {"level":4,"className":"mb-05","textColor":"main","mb":"mb-05"} -->
						<h4 class="mb-05 has-main-color has-text-color">Proin vel egestas</h4>
						<!-- /wp:heading -->

						<!-- wp:paragraph {"className":""} -->
						<p class="">Dis parturient montes orci varius natoque penatibus et magnis</p>
						<!-- /wp:paragraph --></div>
					<!-- /wp:column --></div>
				<!-- /wp:columns -->

				<!-- wp:buttons {"className":"mt-2","mt":"mt-2"} -->
				<div class="wp-block-buttons mt-2"><!-- wp:button {"className":""} -->
					<div class="wp-block-button"><a class="wp-block-button__link" href="#">Natoque Penatibus</a></div>
					<!-- /wp:button -->

					<!-- wp:button {"backgroundColor":"main","className":""} -->
					<div class="wp-block-button"><a
								class="wp-block-button__link has-main-background-color has-background" href="#">Montes
							Orci</a></div>
					<!-- /wp:button --></div>
				<!-- /wp:buttons --></div>
		</div>
		<!-- /wp:media-text --></div>
</div>
<!-- /wp:group -->
