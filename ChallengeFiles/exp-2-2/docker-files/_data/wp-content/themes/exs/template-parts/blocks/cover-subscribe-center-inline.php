<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!-- wp:cover {"url":"<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/cup.jpg","id":256,"dimRatio":70,"align":"full","className":""} -->
<div class="wp-block-cover alignfull"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-70 has-background-dim"></span><img class="wp-block-cover__image-background wp-image-256" alt="" src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/cup.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:columns {"className":"cols-single-md","colsSingle":"cols-single-md"} -->
		<div class="wp-block-columns cols-single-md"><!-- wp:column {"className":""} -->
			<div class="wp-block-column"></div>
			<!-- /wp:column -->

			<!-- wp:column {"width":"50%","className":""} -->
			<div class="wp-block-column" style="flex-basis:50%"><!-- wp:heading {"textAlign":"center","className":""} -->
				<h2 class="has-text-align-center">Praesent feugiat non eros et rutrum</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"align":"center","className":""} -->
				<p class="has-text-align-center">Vivamus eget pulvinar urna, in malesuada ipsum.</p>
				<!-- /wp:paragraph -->

				<?php get_template_part('template-parts/blocks/form-subscribe-inline' ); ?>
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"className":""} -->
			<div class="wp-block-column"></div>
			<!-- /wp:column --></div>
		<!-- /wp:columns --></div></div>
<!-- /wp:cover -->