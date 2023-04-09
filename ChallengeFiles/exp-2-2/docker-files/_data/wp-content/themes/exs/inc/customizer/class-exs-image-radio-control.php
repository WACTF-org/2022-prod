<?php

/**
 * Custom customizer control with color picker from choosen colors
 *
 * @package ExS
 * @since 1.4.5
 */
if ( ! class_exists( 'ExS_Image_Radio_Control' ) && class_exists( 'WP_Customize_Control' ) ) :

	class ExS_Image_Radio_Control extends WP_Customize_Control {
		public $type = 'image-radio';

		protected function render_content() {

			$input_id         = '_customize-input-' . $this->id;
			$description_id   = '_customize-description-' . $this->id;

			$input_id = str_replace( '-', '_', $input_id );
			$input_id = str_replace( ' ', '_', $input_id );

			if ( empty( $this->choices ) ) {
				return;
			}

			$name = '_customize-image-radio-' . $this->id;
			?>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>

			<?php
				foreach ( $this->choices as $value => $label_array ) :
				$id_suffix = str_replace( ' ', '_', $value );
				?>
				<span class="customize-inside-control-inline exs-image-control-wrap">

					<input
							id="<?php echo esc_attr( $input_id . '-image-radio-' . $id_suffix ); ?>"
							type="radio"
							value="<?php echo esc_attr( $value ); ?>"
							name="<?php echo esc_attr( $name ); ?>"
						<?php $this->link(); ?>
						<?php checked( $this->value(), $value ); ?>
						/>
					<label class="exs-image-radio-label <?php echo esc_attr( $value ); ?>" title="<?php echo esc_attr( $label_array['label'] ); ?>" for="<?php echo esc_attr( $input_id . '-image-radio-' . $id_suffix ); ?>">
						<img src="<?php echo esc_url( $label_array['image'] ); ?>" alt="<?php echo esc_attr( $label_array['label'] ); ?>">
						<span class="screen-reader-text">
							<?php echo esc_html( $label_array['label'] ); ?>
						</span>
					</label>
				</span>
			<?php
			endforeach;
		}
	}
endif;
