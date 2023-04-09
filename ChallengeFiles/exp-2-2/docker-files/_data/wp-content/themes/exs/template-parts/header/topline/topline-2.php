<?php
/**
 * The header template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$exs_fluid              = exs_option( 'topline_fluid' ) ? '-fluid' : '';
$exs_topline_background = exs_option( 'topline_background', '' );
//detect if we don't need inverse background based on selected header background color?
if ( is_page_template( 'page-templates/header-overlap.php' ) ) {
	$exs_topline_background = exs_get_transparent_class( exs_option( 'header_background', '' ) );
}
$exs_font_size          = exs_option( 'topline_font_size', '' );
$exs_login_links        = exs_option( 'topline_login_links' );
$disable_dropdown       = exs_option( 'topline_disable_dropdown', '' );
?>
<div id="topline" class="topline <?php echo esc_attr( $exs_topline_background . ' ' . $exs_font_size ); ?>">
	<div class="container<?php echo esc_attr( $exs_fluid ); ?>">
		<?php if ( has_nav_menu( 'topline' ) ) : ?>
			<?php if ( empty( $disable_dropdown ) ) : ?>
			<div id="topline_dropdown" class="dropdown">
				<button id="topline_dropdown_toggle" class="nav-btn type-dots"
						aria-controls="topline_dropdown"
						aria-expanded="false"
						aria-label="<?php esc_attr_e( 'Topline Menu Toggler', 'exs' ); ?>"
						<?php if ( defined( 'AMP__VERSION' ) ) : ?>
						on="tap:topline_dropdown.toggleClass(class='active'),topline_dropdown_toggle.toggleClass(class='active'),body.toggleClass(class='topline-dropdown-active')"
						<?php endif; ?>
				>
					<span></span>
				</button>
				<div class="dropdown-menu dropdown-menu-md topline-menu-dropdown">
				<?php else: ?>
				<div class="topline-menu">
				<?php endif; //disable_dropdown ?>
					<nav class="topline-navigation" aria-label="<?php esc_attr_e( 'Topline Menu', 'exs' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'topline',
								'menu_class'     => 'topline-menu',
								'container'      => false,
								'depth'          => 1,
							)
						);
						?>
					</nav><!-- .topline-navigation -->
				</div><!-- .site-meta -->
					<?php if ( empty( $disable_dropdown ) ) : ?>
			</div><!-- #topline_dropdown -->
			<?php
			endif; //disable_dropdown
		endif; //has_nav_menu

		if ( ! empty( $exs_login_links ) ) :
			get_template_part( 'template-parts/header/login-links' );
		endif; //search

		exs_social_links();

		?>
	</div><!-- .container -->
</div><!-- #topline -->
