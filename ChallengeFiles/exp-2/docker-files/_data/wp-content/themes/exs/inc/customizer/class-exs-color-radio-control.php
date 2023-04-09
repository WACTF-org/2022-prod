<?php

/**
 * Custom customizer control with color picker from choosen colors
 *
 * @package ExS
 * @since 1.4.5
 */
if ( ! class_exists( 'ExS_Color_Radio_Control' ) && class_exists( 'WP_Customize_Control' ) ) :

	class ExS_Color_Radio_Control extends WP_Customize_Control {
		public $type = 'color-radio';

		protected function render_content() {

			$input_id         = '_customize-input-' . $this->id;
			$description_id   = '_customize-description-' . $this->id;

			$input_id = str_replace( '-', '_', $input_id );
			$input_id = str_replace( ' ', '_', $input_id );

			if ( empty( $this->choices ) ) {
				return;
			}

			$name = '_customize-color-radio-' . $this->id;
			?>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>

			<?php
				foreach ( $this->choices as $value => $label ) :
				$id_suffix = str_replace( ' ', '_', $value );
				?>
				<span class="customize-inside-control-inline exs-color-control-wrap">

					<input
							id="<?php echo esc_attr( $input_id . '-color-radio-' . $id_suffix ); ?>"
							type="radio"
							value="<?php echo esc_attr( $value ); ?>"
							name="<?php echo esc_attr( $name ); ?>"
						<?php $this->link(); ?>
						<?php checked( $this->value(), $value ); ?>
						/>
					<label class="exs-color-label <?php echo esc_attr( $value ); ?>" title="<?php echo esc_attr( $label ); ?>" for="<?php echo esc_attr( $input_id . '-color-radio-' . $id_suffix ); ?>">
						<span class="screen-reader-text">
							<?php echo esc_html( $label ); ?>
						</span>
					</label>
				</span>
			<?php
			endforeach;
		}

		public function enqueue() {
			wp_register_style( 'exs-customizer-parent-window-inline', false );
			if ( ! wp_style_is( 'exs-customizer-parent-window-inline',  'enqueued' ) ) {
				wp_enqueue_style( 'exs-customizer-parent-window-inline' );

				//inline styles for customizer parent window - colors
				$exs_colors_string = exs_get_root_colors_inline_styles_string();
				if ( ! empty( $exs_colors_string ) ) :
					$exs_styles_string = ':root{' . $exs_colors_string . '}';
					wp_add_inline_style(
						'exs-customizer-parent-window-inline',
						wp_kses(
							$exs_styles_string,
							false
						)
					);
				endif;
			}
		}
	}
endif;
