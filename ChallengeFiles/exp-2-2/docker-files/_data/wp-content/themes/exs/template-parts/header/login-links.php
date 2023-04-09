<?php
/**
 * The login/logout/register links template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<span class="login-links icon-inline">
<?php

exs_icon( 'account-outline' );

if ( ! is_user_logged_in() ) :
	if ( class_exists( 'WooCommerce' ) ) {
		$login_page_id = get_option( 'woocommerce_myaccount_page_id' );
		$register = ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) );
	}
	?>
	<span class="login-link">
	<?php if ( ! empty( $login_page_id ) ) : ?>
		<a href="<?php echo esc_url( get_permalink(  $login_page_id ) ); ?>"><?php esc_html_e( 'Login', 'exs' ); ?></a>
	<?php else: ?>
		<a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>"><?php esc_html_e( 'Login', 'exs' ); ?></a>
	<?php endif; ?>
	</span>
	<?php if ( get_option( 'users_can_register' ) || ( ! empty( $register ) || ! empty( $login_page_id ) ) ) : ?>
	<span class="register-link">
	<?php if ( ! empty( $register ) && ! empty( $login_page_id ) ) : ?>
		<a href="<?php echo esc_url( get_permalink(  $login_page_id ) ); ?>"><?php esc_html_e( 'Register', 'exs' ); ?></a>
	<?php else: ?>
		<a href="<?php echo esc_url( wp_registration_url() ); ?>"><?php esc_html_e( 'Register', 'exs' ); ?></a>
	<?php endif; ?>
	</span>
	<?php endif; //users_can_register ?>
<?php else: ?>
	<span class="logout-link">
		<a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>"><?php esc_html_e( 'Logout', 'exs' ); ?></a>
	</span>
<?php endif; // ! is_user_logged_in ?>
</span>
