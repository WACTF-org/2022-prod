<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'colsSingle' => 'cols-single-sm',
	'gap'        => 'gap-30',
	'className'  => 'cols-single-sm gap-30',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns cols-single-sm gap-30"><!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:cover {"url":"<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/cup.jpg","id":759,"dimRatio":60,"minHeight":440,"contentPosition":"center center","className":""} -->
		<div class="wp-block-cover has-background-dim-60 has-background-dim" style="min-height:440px"><img
					class="wp-block-cover__image-background wp-image-759" alt=""
					src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/cup.jpg" data-object-fit="cover"/>
			<div class="wp-block-cover__inner-container">
				<!-- wp:paragraph {"placeholder":"Write title…","className":"","fontSize":"large"} -->
				<p class="has-large-font-size"><strong>Commodo</strong> Placerat</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph -->
				<p class="">Nunc fringilla, nibh ac venenatis euismod</p>
				<!-- /wp:paragraph --></div>
		</div>
		<!-- /wp:cover --></div>
	<!-- /wp:column -->

	<!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:cover {"url":"<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/cup.jpg","id":807,"dimRatio":60,"minHeight":205,"minHeightUnit":"px","className":""} -->
		<div class="wp-block-cover has-background-dim-60 has-background-dim" style="min-height:205px"><img
					class="wp-block-cover__image-background wp-image-807" alt=""
					src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/cup.jpg" data-object-fit="cover"/>
			<div class="wp-block-cover__inner-container">
				<!-- wp:paragraph {"align":"right","placeholder":"Write title…","className":"","fontSize":"large"} -->
				<p class="has-text-align-right has-large-font-size"><strong>Vestibulum</strong> Enim</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"align":"right"} -->
				<p class="has-text-align-right">Etiam tempor metus a erat lacinia</p>
				<!-- /wp:paragraph --></div>
		</div>
		<!-- /wp:cover -->

		<!-- wp:spacer {"height":30,"className":""} -->
		<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:cover {"url":"<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/cup.jpg","id":827,"dimRatio":60,"minHeight":205,"minHeightUnit":"px","className":""} -->
		<div class="wp-block-cover has-background-dim-60 has-background-dim" style="min-height:205px"><img
					class="wp-block-cover__image-background wp-image-827" alt=""
					src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/cup.jpg" data-object-fit="cover"/>
			<div class="wp-block-cover__inner-container">
				<!-- wp:paragraph {"align":"right","placeholder":"Write title…","className":"","fontSize":"large"} -->
				<p class="has-text-align-right has-large-font-size"><strong>Quisque</strong> quis</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"align":"right"} -->
				<p class="has-text-align-right">Curabitur eu massa vitae neque</p>
				<!-- /wp:paragraph --></div>
		</div>
		<!-- /wp:cover --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
