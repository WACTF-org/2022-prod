<?php
/**
 * Template part for displaying site meta from the Customizer
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$exs_meta = ! empty( $args ) ? $args : exs_get_theme_meta();
?>
<?php if ( ! empty( $exs_meta['phone'] ) ) : ?>
	<span class="icon-inline">
	<?php exs_icon( 'phone-outline' ); ?>
		<span>
			<?php if ( ! empty( $exs_meta['phone_label'] ) ) : ?>
				<strong><?php echo wp_kses_post( $exs_meta['phone_label'] ); ?></strong>
			<?php endif; ?>
			<span>
				<?php
				if ( exs_option( 'meta_phone_link' ) ) {
					echo '<a href="tel:' . esc_attr( $exs_meta['phone'] ) . '">' . esc_html( $exs_meta['phone'] ) . '</a>';
				} else {
					echo wp_kses_post( $exs_meta['phone'] );
				}
				?>
			</span>
		</span>
	</span>
	<?php
endif; //phone
if ( ! empty( $exs_meta['email'] ) ) :
	?>
	<span class="icon-inline">
	<?php exs_icon( 'email-outline' ); ?>
		<span>
			<?php if ( ! empty( $exs_meta['email_label'] ) ) : ?>
				<strong><?php echo wp_kses_post( $exs_meta['email_label'] ); ?></strong>
			<?php endif; ?>
			<a href="mailto:<?php echo esc_attr( $exs_meta['email'] ); ?>"><?php echo wp_kses_post( $exs_meta['email'] ); ?></a>
		</span>
	</span>
	<?php
endif; //email
if ( ! empty( $exs_meta['address'] ) ) :
	?>
	<span class="icon-inline">
	<?php exs_icon( 'map-marker-outline' ); ?>
		<span>
			<?php if ( ! empty( $exs_meta['address_label'] ) ) : ?>
				<strong><?php echo wp_kses_post( $exs_meta['address_label'] ); ?></strong>
			<?php endif; ?>
			<span><?php echo wp_kses_post( $exs_meta['address'] ); ?></span>
		</span>
	</span>
	<?php
endif; //address
if ( ! empty( $exs_meta['opening_hours'] ) ) :
	?>
	<span class="icon-inline">
	<?php exs_icon( 'clock-outline' ); ?>
		<span>
			<?php if ( ! empty( $exs_meta['opening_hours_label'] ) ) : ?>
				<strong><?php echo wp_kses_post( $exs_meta['opening_hours_label'] ); ?></strong>
			<?php endif; ?>
			<span><?php echo wp_kses_post( $exs_meta['opening_hours'] ); ?></span>
		</span>
	</span>
<?php endif; //opening_hours ?>
