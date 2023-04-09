<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'section'       => true,
	'colsHighlight' => true,
	'colsShadow'    => true,
	'colsPadding'   => true,
	'pt'            => 'pt-5',
	'pb'            => 'pb-5',
	'background'    => 'l m',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>">
	<!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:pullquote {"className":"is-style-default"} -->
		<figure class="wp-block-pullquote is-style-default">
			<blockquote>
				<p>

					<strong>ExS</strong> sint occaecat cupidatat <strong>fugiat nulla</strong> deserunt mollit anim
				</p>
				<cite>
					<img class="wp-image-443" style="width: 50px;" src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/person.png" alt="">
					<br>
					Voluptate Velit, <span class="has-inline-color has-main-color">Culpa</span>
					<br>
				</cite>
			</blockquote>
		</figure>
		<!-- /wp:pullquote -->
	</div>
	<!-- /wp:column -->
	<!-- wp:column {"className":""} -->
	<div class="wp-block-column">
		<!-- wp:pullquote -->
		<figure class="wp-block-pullquote">
			<blockquote>
				<p>
					Sint occaecat cupidatat <strong>fugiat nulla</strong> deserunt mollit anim
					<strong>ExS</strong>
				</p>
				<cite>
					<img class="wp-image-441" style="width: 50px;" src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/person.png" alt="">
					<br>
					Duis Culpa, <span class="has-inline-color has-main-color">Anim</span>
					<br>
				</cite>
			</blockquote>
		</figure>
		<!-- /wp:pullquote -->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->
