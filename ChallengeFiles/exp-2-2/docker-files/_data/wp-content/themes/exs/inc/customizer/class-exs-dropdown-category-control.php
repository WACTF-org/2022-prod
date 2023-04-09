<?php

/**
 * Custom customizer control with dropdown list of existing categories
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! class_exists( 'ExS_Dropdown_Category_Control' ) && class_exists( 'WP_Customize_Control' ) ) :

	class ExS_Dropdown_Category_Control extends WP_Customize_Control {
		public $type             = 'dropdown-category';
		protected $dropdown_args = false;

		protected function render_content() {
			?>
			<label>
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php
				endif;
				if ( ! empty( $this->description ) ) :
					?>
					<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
					<?php
				endif;
				$dropdown_args         = wp_parse_args(
					$this->dropdown_args,
					array(
						'taxonomy'          => 'category',
						'show_option_none'  => '-',
						'selected'          => $this->value(),
						'orderby'           => 'name',
						'order'             => 'ASC',
						'show_count'        => 1,
						'hide_empty'        => 0,
						'child_of'          => 0,
						'exclude'           => '',
						'hierarchical'      => 1,
						'depth'             => 0,
						'tab_index'         => 0,
						'hide_if_empty'     => false,
						'option_none_value' => '',
						'value_field'       => 'term_id',
						//prevend duplicate name and id atts if multiple dropdown-category used
						'name'              => 'cat-' . $this->id,
					)
				);
				$dropdown_args['echo'] = false;
				$dropdown              = wp_dropdown_categories( $dropdown_args );
				$dropdown              = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );
				// $dropdown;
				echo wp_kses(
					$dropdown,
					array(
						'select' => array(
							'name'                        => true,
							'class'                       => true,
							'data-customize-setting-link' => true,
						),
						'option' => array(
							'class' => true,
							'value' => true,
						),
					)
				);
			?>
			</label>
			<?php
		}
	}
endif;
