<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = ! empty( $args ) ? $args : array();
$default_args = array(
	'pt' => 'pt-5',
	'pb' => 'pb-5',
);
$args = array_merge( $default_args, $args );
$args = exs_get_block_args_array( $args );

?>
<!-- wp:columns <?php echo wp_kses( $args['json'], array() ); ?> -->
<div class="wp-block-columns <?php echo esc_attr( $args['className'] ); ?>"><!-- wp:column -->
	<div class="wp-block-column"><!-- wp:quote -->
		<blockquote class="wp-block-quote"><p><strong>Curabitur laoreet </strong>erat cursus, volutpat tellus vitae, aliquam libero. Aenean suscipit interdum mattis. Aenean tempus <strong>quam eget</strong> molestie maximus.</p><cite><img class="wp-image-443" style="width: 50px;" src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/person.png" alt=""> Aenean Suscipit <strong><span class="has-inline-color has-main-color">Donec</span></strong></cite></blockquote>
		<!-- /wp:quote --></div>
	<!-- /wp:column -->

	<!-- wp:column -->
	<div class="wp-block-column"><!-- wp:quote {"className":"is-style-default"} -->
		<blockquote class="wp-block-quote is-style-default"><p><strong>Ut sit amet </strong>neque urna. Curabitur semper ultrices erat, ac hendrerit tellus porttitor in. Nulla <strong>vestibulum libero</strong> quam, nec rhoncus enim aliquet quis.</p><cite><img class="wp-image-444" style="width: 50px;" src="<?php echo esc_url( EXS_THEME_URI ); ?>/assets/img/person.png" alt=""> Curabitur Semper, <strong><span class="has-inline-color has-main-color">Proin</span></strong></cite></blockquote>
		<!-- /wp:quote --></div>
	<!-- /wp:column --></div>
<!-- /wp:columns -->
