<?php

/**
 * Custom customizer control with range - used as range input
 *
 * @package ExS
 * @since 0.9.0
 */
if ( ! class_exists( 'ExS_Slider_Control' ) && class_exists( 'WP_Customize_Control' ) ) :

	class ExS_Slider_Control extends WP_Customize_Control {
		public $type = 'range';

		protected function render_content() {

			$input_id         = '_customize-input-' . $this->id;
			$description_id   = '_customize-description-' . $this->id;

			$input_id = str_replace( '-', '_', $input_id );

			if ( ! empty( $this->label ) ) :
				?>
				<label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
			<?php endif; ?>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>

			<input
				id="<?php echo esc_attr( $input_id ); ?>_input"
				type="<?php echo esc_attr( $this->type ); ?>"
				style="width:100%"

				oninput="<?php echo esc_attr( $input_id ); ?>.value=<?php echo esc_attr( $input_id ); ?>_input.value;<?php echo esc_attr( $input_id ); ?>.dispatchEvent(new Event('input'));"
				<?php if ( ! empty( $this->description ) ) : ?>
					aria-describedby="<?php echo esc_attr( $description_id ); ?>"
				<?php endif; ?>
				<?php $this->input_attrs(); ?>
				<?php if ( ! isset( $this->input_attrs['value'] ) ) : ?>
					value="<?php echo esc_attr( $this->value() ); ?>"
				<?php endif; ?>
			/>
			<input
				id="<?php echo esc_attr( $input_id ); ?>"
				type="number"
				<?php $this->input_attrs(); ?>
				id="<?php echo esc_attr( $input_id ); ?>_output"
				style="display:inline-block;width:30%"
				<?php if ( ! isset( $this->input_attrs['value'] ) ) : ?>
					value="<?php echo esc_attr( $this->value() ); ?>"
				<?php endif; ?>
				<?php $this->link(); ?>
			>
			</input>
			<button class="button button-secondary" type="button" onclick="<?php echo esc_attr( $input_id ); ?>.value='';<?php echo esc_attr( $input_id ); ?>.dispatchEvent(new Event('input'));">
				<?php echo esc_html__( 'Default', 'exs' ); ?>
			</button>
			<?php
		}
	}

endif;
