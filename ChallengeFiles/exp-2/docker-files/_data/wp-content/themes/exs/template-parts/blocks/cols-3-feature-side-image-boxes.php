<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'section' => true,
	'colsSingle' => 'cols-single-sm',
	'gap' => 'gap-50',
	'pt' => 'pt-2',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>">
	<!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:columns {"className":"cols-single-none gap-15","colsSingle":"cols-single-none","gap":"gap-15"} -->
		<div class="wp-block-columns cols-single-none gap-15">
			<!-- wp:column {"width":20,"className":""} -->
			<div class="wp-block-column" style="flex-basis:20%">
				<!-- wp:image {"id":485,"width":50,"height":50,"sizeSlug":"large","className":""} -->
				<figure class="wp-block-image size-large is-resized">
					<img
						src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/planet-outline.png" alt="" class="wp-image-485" width="50"
						height="50"/>
				</figure>
				<!-- /wp:image -->
			</div>
			<!-- /wp:column -->
			<!-- wp:column {"width":80,"className":""} -->
			<div class="wp-block-column" style="flex-basis:80%">
				<!-- wp:heading {"level":4,"className":"mb-05","mb":"mb-05"} -->
				<h4 class="mb-05">Laboris nisi</h4>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"className":""} -->
				<p class="">Excepteur sint occaecat cupidatat
					non proident, sunt in culpa qui.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:column -->
	<!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:columns {"className":"cols-single-none gap-15","colsSingle":"cols-single-none","gap":"gap-15"} -->
		<div class="wp-block-columns cols-single-none gap-15">
			<!-- wp:column {"width":20,"className":""} -->
			<div class="wp-block-column" style="flex-basis:20%">
				<!-- wp:image {"id":486,"width":50,"height":50,"sizeSlug":"large","linkDestination":"custom","className":""} -->
				<figure class="wp-block-image size-large is-resized">
					<a href="#">
						<img
							src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/pie-chart-outline.png" alt="" class="wp-image-486" width="50"
							height="50"/>
					</a>
				</figure>
				<!-- /wp:image -->
			</div>
			<!-- /wp:column -->
			<!-- wp:column {"width":80,"className":""} -->
			<div class="wp-block-column" style="flex-basis:80%">
				<!-- wp:heading {"level":4,"className":"mb-05","mb":"mb-05"} -->
				<h4 class="mb-05">Excepteur sint occaecat</h4>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"className":""} -->
				<p class="">Duis aute irure dolor in
					reprehenderit in voluptate sit amet.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:column -->
	<!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:columns {"className":"cols-single-none gap-15 pb-2","colsSingle":"cols-single-none","gap":"gap-15","pb":"pb-2"} -->
		<div class="wp-block-columns cols-single-none gap-15 pb-2">
			<!-- wp:column {"width":20,"className":""} -->
			<div class="wp-block-column" style="flex-basis:20%">
				<!-- wp:image {"id":487,"width":50,"height":50,"sizeSlug":"large","className":""} -->
				<figure class="wp-block-image size-large is-resized">
					<img
						src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/settings-outline.png" alt="" class="wp-image-487" width="50"
						height="50"/>
				</figure>
				<!-- /wp:image -->
			</div>
			<!-- /wp:column -->
			<!-- wp:column {"width":80,"className":""} -->
			<div class="wp-block-column" style="flex-basis:80%">
				<!-- wp:heading {"level":4,"className":"mb-05","mb":"mb-05"} -->
				<h4 class="mb-05">Fugiat nulla</h4>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"className":""} -->
				<p class="">Excepteur sint occaecat cupidatat
					non proident, sunt in culpa qui.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->
