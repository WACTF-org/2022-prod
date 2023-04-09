<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!-- wp:cover {"url":"<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/cup.jpg","id":391,"dimRatio":60,"overlayColor":"light","align":"full","className":""} -->
<div class="wp-block-cover alignfull has-background-dim-60 has-light-background-color has-background-dim"
	 style="background-image:url(<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/cup.jpg)">
	<div class="wp-block-cover__inner-container"><!-- wp:group {"className":"screen","screen":true} -->
		<div class="wp-block-group screen">
			<div class="wp-block-group__inner-container">
				<!-- wp:image {"align":"center","id":500,"width":115,"height":115,"sizeSlug":"large","className":"mb-0","mb":"mb-0"} -->
				<div class="wp-block-image mb-0">
					<figure class="aligncenter size-large is-resized"><img
								src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/alarm-outline.png" alt=""
								class="wp-image-500" width="115" height="115"/></figure>
				</div>
				<!-- /wp:image -->

				<!-- wp:paragraph {"align":"center","className":"mt-05 mb-15","textColor":"dark-muted","fontSize":"huge","mt":"mt-05","mb":"mb-15"} -->
				<p class="has-text-align-center mt-05 mb-15 has-dark-muted-color has-text-color has-huge-font-size">
					<strong>Quisque posuere</strong> dictum nisl<br>quis ornare velit <strong>mollis</strong> </p>
				<!-- /wp:paragraph -->

				<!-- wp:button {"align":"center","className":""} -->
				<div class="wp-block-button aligncenter"><a class="wp-block-button__link"
																				 href="../">Vivamus Dolor Risus</a>
				</div>
				<!-- /wp:button --></div>
		</div>
		<!-- /wp:group --></div>
</div>
<!-- /wp:cover -->
