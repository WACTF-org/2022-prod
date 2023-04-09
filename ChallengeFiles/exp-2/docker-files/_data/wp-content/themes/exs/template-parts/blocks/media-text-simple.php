<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'section' => true,
	'pt' => 'pt-2',
	'pb' => 'pb-2',
	'background' => 'l',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:group <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-group <?php echo esc_attr( $args['className'] ); ?>">
	<div class="wp-block-group__inner-container">
		<!-- wp:media-text {"align":"","mediaId":908,"mediaType":"image","mediaWidth":39,"isStackedOnMobile":false,"imageFill":false} -->
		<div class="wp-block-media-text" style="grid-template-columns:39% auto">
			<figure class="wp-block-media-text__media"><img
						src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/phone.png" alt=""
						class="wp-image-908 size-full"/></figure>
			<div class="wp-block-media-text__content">
				<!-- wp:spacer {"height":50,"className":"hidden-sm","hidden":"hidden-sm"} -->
				<div style="height:50px" aria-hidden="true" class="wp-block-spacer hidden-sm"></div>
				<!-- /wp:spacer -->

				<!-- wp:paragraph {"className":"mb-0","mb":"mb-0"} -->
				<p class="mb-0"><span
							class="has-inline-color has-main-color">CRAS MASSA MAGNA, ACCUMSAN ULLAMCORPER</span></p>
				<!-- /wp:paragraph -->

				<!-- wp:heading {"className":"mt-0","mt":"mt-0"} -->
				<h2 class="mt-0">Donec felis eros, vestibulum et augue ac, cursus vestibulum felis</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"className":""} -->
				<p class="">Vestibulum fringilla, est et faucibus dapibus, diam augue eleifend eros, non pellentesque
					justo turpis sit amet dui. Duis tristique magna varius leo dapibus molestie. Sed consequat magna id
					justo malesuada, nec sodales ex placerat. Mauris aliquet maximus faucibus. Cras massa magna,
					accumsan ullamcorper eros sodales, ornare bibendum orci.</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":""} -->
				<p class="">Aenean non enim nec mi ultrices commodo. Vestibulum molestie lectus facilisis mi dignissim,
					rhoncus dapibus ex aliquam. Integer suscipit vulputate tempor. Etiam scelerisque dui ut urna
					placerat lobortis. Curabitur laoreet erat cursus, volutpat tellus vitae, aliquam libero. Sed tempor
					aliquet sem et posuere. Mauris id ligula in massa ullamcorper sodales in vel leo. Duis consequat
					augue id nisi iaculis, non consequat justo ullamcorper. Vivamus non tincidunt lectus. Nunc eu tortor
					risus.</p>
				<!-- /wp:paragraph -->

				<!-- wp:buttons {"className":"mt-4","mt":"mt-4"} -->
				<div class="wp-block-buttons mt-4"><!-- wp:button {"className":""} -->
					<div class="wp-block-button"><a class="wp-block-button__link" href="#">Aliquet Quis</a></div>
					<!-- /wp:button -->

					<!-- wp:button {"className":"is-style-outline"} -->
					<div class="wp-block-button is-style-outline"><a class="wp-block-button__link">Donec Eros</a></div>
					<!-- /wp:button --></div>
				<!-- /wp:buttons --></div>
		</div>
		<!-- /wp:media-text --></div>
</div>
<!-- /wp:group -->
