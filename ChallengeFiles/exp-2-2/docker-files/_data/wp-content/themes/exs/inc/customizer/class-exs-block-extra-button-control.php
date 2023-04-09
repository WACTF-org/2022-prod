<?php

/**
 * Custom customizer control with extra button - used as extra call to action
 *
 * @package ExS
 * @since 0.0.1
 */
if ( ! class_exists( 'ExS_Block_Extra_Button_Control' ) && class_exists( 'WP_Customize_Control' ) ) :

	class ExS_Block_Extra_Button_Control extends WP_Customize_Control {
		public $type = 'extra-button';

		protected function render_content() {
			if ( EXS_EXTRA ) {
				if ( function_exists( 'exs_fs' ) ) {
					if ( exs_fs()->is_plan( 'pro' ) ) {
						return;
					}
				}
				if ( EXS_TM ) {
					return;
				}
			}
			?>
			<hr><h2 class="customize-control-title">
			<?php
			if ( ! empty( $this->label ) ) :
				?>
				<span class="customize-control-block-heading-title"><?php echo esc_html( $this->label ); ?></span>
				<?php
			endif;
			if ( ! empty( $this->description ) ) :
				?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
				<?php
			endif;
			?>
			</h2>
			<p>
				<?php
				$panel_link =  add_query_arg( array( 'page' => 'exs-theme', 'tab' => 'upgrade' ), admin_url( 'themes.php' ) );
				?>
				<a href="<?php echo esc_url( $panel_link ); ?>" class="button button-primary">
					<?php echo esc_html__( 'Buy PRO features', 'exs' ); ?>
				</a>
			</p>
			<?php
		}
	}

endif;
