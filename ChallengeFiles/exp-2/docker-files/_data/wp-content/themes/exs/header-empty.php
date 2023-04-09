<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section without any other markup
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$exs_body_itemtype = exs_get_body_schema_itemtype();

?><!doctype html>
<html <?php language_attributes(); ?> class="no-js-disabled<?php echo is_customize_preview() ? ' customize-preview' : ''; ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="profile" href="https://gmpg.org/xfn/11"/>
	<?php wp_head(); ?>
</head>

<body id="body" <?php body_class(); ?> itemtype="https://schema.org/<?php echo esc_attr( $exs_body_itemtype ); ?>" itemscope="itemscope" data-nonce="<?php echo esc_attr( wp_create_nonce( 'exs_nonce' ) ); ?>" data-ajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
	<?php exs_animated_elements_markup(); ?>
>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>
<a id="skip_link" class="screen-reader-text skip-link" href="#main"><?php echo esc_html__( 'Skip to content', 'exs' ); ?></a>
<?php

get_template_part( 'template-parts/header/header-preloader' );
