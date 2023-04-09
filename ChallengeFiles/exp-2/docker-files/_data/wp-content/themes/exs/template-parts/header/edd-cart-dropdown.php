<?php
/**
 * The EDD cart dropdown template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.9.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$icon = exs_option( 'edd_header_cart_dropdown', '' );
if ( $icon ) :

	$cart_quantity = edd_get_cart_quantity();

	?>
	<div class="cart-dropdown">
		<a class="dropdown-toggle" href="#" role="button" id="dropdown-cart-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
			<?php if ( defined( 'AMP__VERSION' ) ) : ?>
			on="tap:dropdown-cart.toggleClass(class='active'),dropdown-cart-toggle.toggleClass(class='active'),body.toggleClass(class='cart-dropdown-active')"
			<?php endif; ?>
		>
			<?php

				//new icons
				exs_icon( $icon );

			echo '<span class="edd-cart-quantity cart-count">';
			if ( ! empty( $cart_quantity ) ) {
				echo esc_html( $cart_quantity );
			}
			echo '</span>';
		?>
		</a>
		<div class="cart-dropdown-menu dropdown-menu-right" id="dropdown-cart" aria-labelledby="dropdown-cart-toggle">
			<?php the_widget( 'edd_cart_widget', 'title=' ); ?>
		</div>
	</div>
	<?php
endif; //edd_header_cart_dropdown check
