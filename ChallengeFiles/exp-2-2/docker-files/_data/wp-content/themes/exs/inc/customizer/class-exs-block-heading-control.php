<?php

/**
 * Custom customizer control with heading - used as divider
 *
 * @package ExS
 * @since 0.0.1
 */
if ( ! class_exists( 'ExS_Block_Heading_Control' ) && class_exists( 'WP_Customize_Control' ) ) :

	class ExS_Block_Heading_Control extends WP_Customize_Control {
		public $type = 'block-heading';

		protected function render_content() {
			?>
			<hr><h2 class="customize-control-title-exs">
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
			<?php
		}
	}

endif;
