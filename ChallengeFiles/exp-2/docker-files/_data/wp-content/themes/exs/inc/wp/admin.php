<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'exs_theme_options_page' ) ) :
	function exs_theme_options_page() {
		echo '<div class="wrap">';
		echo '<h1>';
		echo esc_html__( 'ExS Theme', 'exs' );
		echo '</h1>';
		$pro = false;
		if ( EXS_EXTRA ) :
			if ( function_exists( 'exs_fs' ) ) {
				if ( exs_fs()->is_plan( 'pro' ) ) {
					$pro = true;
				}
			}
		endif;

		$current_tab = ! empty( $_GET['tab'] ) ? sanitize_title( $_REQUEST['tab'] ) : 'pro';
		$tabs        = array(
			'pro'     => esc_html__( 'Pro Features', 'exs' ),
		);
		if ( empty( $pro ) ) {
			$tabs['upgrade'] = esc_html__( 'Upgrade to Pro', 'exs' );
		}

		$tabs = apply_filters( 'exs_admin_theme_tabs', $tabs );
		?>
		<nav class="nav-tab-wrapper">
			<?php
			foreach ( $tabs as $name => $label ) :
				$tab_link =  add_query_arg( array( 'page' => 'exs-theme', 'tab' => $name ), admin_url( 'themes.php' ) );
				$tab_class = 'nav-tab';
				if ( $current_tab === $name ) {
					$tab_class .= ' nav-tab-active';
				}
				?>
				<a href="<?php echo esc_url( $tab_link ); ?>" class="<?php echo esc_attr( $tab_class ); ?>"><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
		</nav>
		<?php if ( 'upgrade' === $current_tab ) : ?>
			<h3>
				<?php echo esc_html__( 'You can choose between monthly, yearly or buy out license. Any of them is for unlimited number of sites.', 'exs' ); ?>
			</h3>
			<a href="https://exsthemewp.com/download/" class="button button-primary" target="_blank" style="padding:10px 20px; margin-right:10px;">
				<?php echo esc_attr__( 'Get PRO version', 'exs' ); ?>
				<span class="dashicons dashicons-external" style="vertical-align:sub"></span>
			</a>
			<a href="https://exsthemewp.com/demo/" target="_blank" class="button button-secondary" style="padding:10px 20px;">
				<?php
				echo esc_html__( 'See theme demos', 'exs' );
				?>
				<span class="dashicons dashicons-external" style="vertical-align:sub"></span>
			</a>
		<?php endif; // UPGRADE tab ?>
		<?php if ( 'pro' === $current_tab ) : ?>
			<h3>
				<?php echo esc_html__( 'Thanks for using ExS theme', 'exs' ); ?>!
			</h3>
			<p>
				<?php echo esc_html__( 'ExS is a next generation WordPress theme and it holds its options in the Customizer', 'exs' ); ?>
			</p>
			<?php if ( ! empty( $pro ) ) : ?>
				<h3>
					<?php echo esc_html__( 'You have following PRO features:', 'exs' ); ?>
				</h3>
			<?php else : ?>
				<h3>
					<?php echo esc_html__( 'Unlock PRO features:', 'exs' ); ?>
				</h3>
			<?php endif; ?>

			<table class="widefat striped">
				<thead>
				<tr>
					<th><?php echo esc_html__( 'Feature', 'exs' ); ?>:</th>
					<th><?php echo esc_html__( 'Description', 'exs' ); ?>:</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<th><?php echo esc_html__( 'Site Skins', 'exs' ); ?>:</th>
					<td><?php echo esc_html__( 'Change your site look and feel without changing your theme with growing number of CSS skins in your Customizer', 'exs' ); ?></td>
				</tr>
				<tr>
					<th><?php echo esc_html__( 'Elements Animation', 'exs' ); ?>:</th>
					<td><?php echo esc_html__( 'Activate animation in your Customizer and set animation for your posts, widgets and any Gutenberg block', 'exs' ); ?></td>
				</tr>
				<tr>
					<th><?php echo esc_html__( 'Google Fonts', 'exs' ); ?>:</th>
					<td><?php echo esc_html__( 'Activate Google Fonts in your Customizer and set custom fonts for your body text and headings', 'exs' ); ?></td>
				</tr>
				<tr>
					<th><?php echo esc_html__( 'Infinite Scroll', 'exs' ); ?>:</th>
					<td><?php echo esc_html__( 'Load next page on the Archive pages without page reload - by clicking on the Load More button or just on scroll page.', 'exs' ); ?></td>
				</tr>
				<tr>
					<th><?php echo esc_html__( 'Share Buttons', 'exs' ); ?>:</th>
					<td><?php echo esc_html__( 'Share buttons for single posts and posts archives.', 'exs' ); ?></td>
				</tr>
				<tr>
					<th><?php echo esc_html__( 'Pop-up Messages', 'exs' ); ?>:</th>
					<td><?php echo esc_html__( 'Add your top and bottom pop-up messages easily via Customizer', 'exs' ); ?></td>
				</tr>
				<tr>
					<th><?php echo esc_html__( 'Side panel (menu)', 'exs' ); ?>:</th>
					<td><?php echo esc_html__( 'Set your side menu, make it always visible for large screens and many more in your Customizer', 'exs' ); ?></td>
				</tr>
				<tr>
					<th><?php echo esc_html__( 'Premium demo contents', 'exs' ); ?>:</th>
					<td><?php echo esc_html__( 'Use growing number of built in demo contents for quick start of your new project', 'exs' ); ?></td>
				</tr>
				<tr>
					<th><?php echo esc_html__( 'Categories options', 'exs' ); ?>:</th>
					<td><?php echo esc_html__( 'Set a different display options for different post categories', 'exs' ); ?></td>
				</tr>
				<tr>
					<th><?php echo esc_html__( 'Special categories', 'exs' ); ?>:</th>
					<td><?php echo esc_html__( 'Set a post categories for your Portfolio, Services and Team members without using Custom Post Types', 'exs' ); ?></td>
				</tr>
				<tr>
					<th><?php echo esc_html__( 'WooCommerce extra options', 'exs' ); ?>:</th>
					<td><?php echo esc_html__( 'Change your products list layout easily in your Customizer', 'exs' ); ?></td>
				</tr>
				</tbody>
			</table>
			<p>
				<?php
				if ( ! empty( $pro ) ) :
					$panel_link = add_query_arg( array( 'autofocus[panel]' => 'panel_theme' ), admin_url( 'customize.php' ) );
					?>
					<a href="<?php echo esc_url( $panel_link ); ?>" class="button button-primary">
						<?php echo esc_html__( 'Go to Customizer', 'exs' ); ?>
					</a>
					<?php
					if ( 'exs-pro' === get_stylesheet() ) :
					$panel_link =  add_query_arg( array( 'page' => 'exs-theme', 'exs-action' => 'exs-free-to-pro-import' ), admin_url( 'themes.php' ) ); ?>
					<a href="<?php echo esc_url( $panel_link ); ?>" class="button button-secondary">
						<?php echo esc_html__( 'Import Customizer settings from ExS to Exs PRO theme', 'exs' ); ?>
					</a>
				<?php
					endif;
				else :
					$panel_link =  add_query_arg( array( 'page' => 'exs-theme', 'tab' => 'upgrade' ), admin_url( 'themes.php' ) );
					?>
					<a href="https://exsthemewp.com/download/" class="button button-primary">
						<?php echo esc_html__( 'Get PRO version', 'exs' ); ?>
						<span class="dashicons dashicons-external" style="vertical-align:sub"></span>
					</a>
					<a href="<?php echo esc_url( $panel_link ); ?>" class="button button-secondary">
						<?php echo esc_html__( 'See PRO features', 'exs' ); ?>
					</a>
					-
					<span>
				<?php esc_html_e( 'Unlimited sites license', 'exs' ); ?>
				</span>
				<?php endif; ?>
			</p>
		<?php
			//Extra features goes here
		endif; //PRO tab
		?>
		<h3>
			<?php esc_html_e( 'Free vs Pro', 'exs' ); ?>
		</h3>
		<table class="widefat striped">
			<thead>
			<tr>
				<th>
					<?php esc_html_e( 'Feature Description', 'exs' ); ?>
				</th>
				<th>
					<?php esc_html_e('Free', 'exs' ); ?>
				</th>
				<th>
					<?php esc_html_e('Pro', 'exs' ); ?>
				</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>
					<?php esc_html_e('100% Google Page Speed', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e('No jQuery Dependency', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e('Blog Layouts', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e('Header layouts', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Title section layouts', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Footer layouts', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Unlimited colours', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Sidebar position management', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Multiple page templates for any needs', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Light and Dark color scheme switcher', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Single post read progress bar', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'WooCommerce support', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Easy Digital Downloads (EDD) support', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'bbPress support', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'BuddyPress support', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'WP Job Manager support', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Simple Job Board support', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'The Events Calendar support', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'LearnPress support', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Ultimate Member support', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Advanced Custom Fields auto display support', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'WP Optimize support', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'AMP support', 'exs' ); ?>
				</td>
				<td><span>+</span></td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Custom ExS widgets plugin support', 'exs' ); ?>
				</td>
				<td>+</td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<strong><?php esc_html_e( 'Theme skins', 'exs' ); ?></strong>
				</td>
				<td>–</td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<strong><?php esc_html_e( 'Gutenberg Blocks advanced UI (sections, margin etc.)', 'exs' ); ?></strong>
				</td>
				<td>–</td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<strong><?php esc_html_e( 'Google Fonts', 'exs' ); ?></strong>
				</td>
				<td>–</td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<strong><?php esc_html_e( 'Infinite Scroll', 'exs' ); ?></strong>
				</td>
				<td>–</td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<strong><?php esc_html_e( 'Share Buttons', 'exs' ); ?></strong>
				</td>
				<td>–</td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<strong><?php esc_html_e( 'CSS Animations', 'exs' ); ?></strong>
				</td>
				<td>–</td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<strong><?php esc_html_e( 'Premium Demo Contents (Starter Sites)', 'exs' ); ?></strong>
				</td>
				<td>–</td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<strong><?php esc_html_e( 'Side panel (menu)', 'exs' ); ?></strong>
				</td>
				<td>–</td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<strong><?php esc_html_e( 'Categories different layouts', 'exs' ); ?></strong>
				</td>
				<td>–</td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<strong><?php esc_html_e( 'Special categories (services, portfolio, team)', 'exs' ); ?></strong>
				</td>
				<td>–</td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<strong><?php esc_html_e( 'Popup messages (for GDPR, actions or any other needs)', 'exs' ); ?></strong>
				</td>
				<td>–</td>
				<td><span>+</span></td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Customers support', 'exs' ); ?>
				</td>
				<td>+</td>
				<td><span>+</span></td>
			</tr>
			</tbody>
		</table>

		<h3>
			<?php esc_html_e( 'Changelog', 'exs' ); ?>
		</h3>

		<p>
			<a href="https://exsthemewp.com/changelog/" class="button button-secondary">
				<?php
				echo esc_html__( 'Theme changelog', 'exs' );
				?>
				<span class="dashicons dashicons-external" style="vertical-align:sub"></span>
			</a>
		</p>

		<?php
		echo '</div><!--.wrap-->';
	}
endif;

if( ! function_exists( 'exs_admin_notice_message' ) ) :
	function exs_admin_notice_message() {
		if ( ! current_user_can( 'manage_options' ) || EXS_EXTRA ) {
			return;
		}
		$user_id = get_current_user_id();
		if ( isset( $_GET['exs-dismiss'] ) && check_admin_referer( 'exs-dismiss-' . $user_id ) ) {
			update_user_meta( $user_id, 'exs_dismissed_notice_' . str_replace( '.', '', EXS_THEME_VERSION ), 1 );
		}
		if ( get_user_meta( $user_id, 'exs_dismissed_notice_' . str_replace( '.', '', EXS_THEME_VERSION ) ) ) {
			return;
		}

		?>
		<div class="notice notice-info is-dismissible">
			<h3>
				<?php echo esc_html__( 'Thanks for using the ExS - fastest WordPress theme', 'exs' ); ?>!
			</h3>
			<p>
				<?php echo esc_html__( 'ExS is a next generation WordPress theme and it holds its options in the Customizer', 'exs' ); ?>
			</p>

			<p>
				<a href="https://exsthemewp.com/demo/" class="button button-secondary">
					<?php
					echo esc_html__( 'See theme demos', 'exs' );
					?>
					<span class="dashicons dashicons-external" style="vertical-align:sub"></span>
				</a>
				|
				<a href="https://exsthemewp.com/changelog/" class="button button-secondary">
					<?php
					echo esc_html__( 'See Change Log', 'exs' );
					?>
					<span class="dashicons dashicons-external" style="vertical-align:sub"></span>
				</a>
				|
				<?php
				$panel_link = add_query_arg( array( 'autofocus[panel]' => 'panel_theme' ), admin_url( 'customize.php' ) );
				?>
				<a href="<?php echo esc_url( $panel_link ); ?>" class="button button-secondary">
					<?php echo esc_html__( 'Go to Customizer', 'exs' ); ?>
				</a>
				|
				<?php
				$panel_link =  add_query_arg( array( 'page' => 'exs-theme', 'tab' => '' ), admin_url( 'themes.php' ) );
				?>
				<a href="<?php echo esc_url( $panel_link ); ?>" class="button button-secondary">
					<strong><?php echo esc_html__( 'See PRO features', 'exs' ); ?></strong>
				</a>
				|
				<a href="https://exsthemewp.com/download/" class="button button-primary">
					<?php echo esc_html__( 'Get PRO version', 'exs' ); ?>
					<span class="dashicons dashicons-external" style="vertical-align:sub"></span>
				</a>
				|
				<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'exs-dismiss', 'dismiss_admin_notices' ), 'exs-dismiss-' . $user_id ) ); ?>" class="dismiss-notice" target="_parent">
					<?php esc_html_e( 'Dismiss this notice', 'exs' ); ?>
				</a>
			</p>
		</div>
		<?php
	}
endif;
add_action( 'admin_notices', 'exs_admin_notice_message' );