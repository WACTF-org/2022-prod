<?php

/**
 * Helper class for WordPress customizer
 *
 * based on: https://github.com/philipnewcomer/Customizer-Framework
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! class_exists( 'ExS_Customizer' ) ) :

	class ExS_Customizer {
		public $registered_settings;
		public $legacy_valid_control_types;

		public function __construct( $settings = array() ) {

			$this->legacy_valid_control_types = array(
				'checkbox',
				'color',
				'dropdown-pages',
				'file',
				'image',
				'radio',
				'select',
				'text',
			);

			//setting $this->registered_settings
			//last chance to modify settings array
			$settings = apply_filters( 'exs_customizer_framework_settings', $settings );
			foreach ( $settings as $id => $setting ) {
				$this->add_setting( $id, $setting );
			}

			add_action( 'customize_register', array( $this, 'register_settings' ) );
			add_action( 'customize_register', array( $this, 'enqueue_sanitization' ) );
		}

		/**
		 * Enqueues the sanitization function.
		 */
		public function enqueue_sanitization() {
			add_filter( 'sanitize_option_theme_mods_' . get_option( 'stylesheet' ), array( $this, 'sanitize' ) );
		}

		/**
		 * Registers with WordPress all registered framework settings
		 */
		public function register_settings( $customizer ) {
			foreach ( $this->registered_settings as $setting_id => $setting ) :

				//custom callback - visible depending from settings array 'visible' key
				//requires PHP 5.3
				$active_callback = ! empty( $setting['active_callback'] ) ? $setting['active_callback'] : '';
				if ( empty( $active_callback ) && ! empty( $setting['visible'] ) ) {
					$active_callback = function( $control ) {
						$settings = exs_customizer_settings_array();
						$visible  = $settings[ $control->id ]['visible'];
						//TODO need to convert to === (! empty or another solution)
						return ( (bool) $control->manager->get_setting( $visible['key'] )->value() === (bool) $visible['value'] );
					};
				}

				//panel type
				if ( 'panel' === $setting['type'] ) {
					//register panel
					$customizer->add_panel(
						$setting_id,
						array(
							'active_callback' => $active_callback,
							'title'           => $setting['label'],
							'description'     => $setting['description'],
							'priority'        => $setting['priority'],
						)
					);
					continue;
				}

				//section type
				if ( 'section' === $setting['type'] ) {
					//register section
					$customizer->add_section(
						$setting_id,
						array(
							'active_callback' => $active_callback,
							'title'           => $setting['label'],
							'panel'           => $setting['panel'],
							'description'     => $setting['description'],
							'priority'        => $setting['priority'],
						)
					);
					continue;
				}

				//for type is not 'panel' and not 'section'
				$customizer->add_setting(
					$setting_id,
					array(
						'default' => $setting['default'],
					)
				);
				switch ( $setting['type'] ) {
					// dropdown-category
					// see ExS_Dropdown_Category_Control class
					case 'dropdown-category':
						$customizer->add_control(
							new ExS_Dropdown_Category_Control(
								$customizer,
								$setting_id,
								array(
									'active_callback' => $active_callback,
									'description'     => $setting['description'],
									'input_attrs'     => $setting['input_attrs'],
									'label'           => $setting['label'],
									'priority'        => $setting['weight'],
									'section'         => $setting['section'],
									'settings'        => $setting_id,
								)
							)
						);
						break;
					// block-heading
					// see ExS_Block_Heading_Control class
					case 'block-heading':
						$customizer->add_control(
							new ExS_Block_Heading_Control(
								$customizer,
								$setting_id,
								array(
									'active_callback' => $active_callback,
									'description'     => $setting['description'],
									'input_attrs'     => $setting['input_attrs'],
									'label'           => $setting['label'],
									'priority'        => $setting['weight'],
									'section'         => $setting['section'],
									'settings'        => $setting_id,
								)
							)
						);
						break;
					// range
					// see ExS_Slider_Control class
					case 'slider':
						$customizer->add_control(
							new ExS_Slider_Control(
								$customizer,
								$setting_id,
								array(
									'active_callback' => $active_callback,
									'description'     => $setting['description'],
									'input_attrs'     => $setting['input_attrs'],
									'label'           => $setting['label'],
									'priority'        => $setting['weight'],
									'section'         => $setting['section'],
									'settings'        => $setting_id,
								)
							)
						);
						break;
					// color-radio
					// see ExS_Color_Radio_Control class
					case 'color-radio':
						$customizer->add_control(
							new ExS_Color_Radio_Control(
								$customizer,
								$setting_id,
								array(
									'active_callback' => $active_callback,
									'choices'         => $setting['choices'],
									'description'     => $setting['description'],
									'input_attrs'     => $setting['input_attrs'],
									'label'           => $setting['label'],
									'priority'        => $setting['weight'],
									'section'         => $setting['section'],
									'settings'        => $setting_id,
								)
							)
						);
						break;
					// image-radio
					// see ExS_Color_Radio_Control class
					case 'image-radio':
						$customizer->add_control(
							new ExS_Image_Radio_Control(
								$customizer,
								$setting_id,
								array(
									'active_callback' => $active_callback,
									'choices'         => $setting['choices'],
									'description'     => $setting['description'],
									'input_attrs'     => $setting['input_attrs'],
									'label'           => $setting['label'],
									'priority'        => $setting['weight'],
									'section'         => $setting['section'],
									'settings'        => $setting_id,
								)
							)
						);
						break;
					// extra-button
					// see ExS_Block_Extra_Button_Control class
					case 'extra-button':
						$customizer->add_control(
							new ExS_Block_Extra_Button_Control(
								$customizer,
								$setting_id,
								array(
									'active_callback' => $active_callback,
									'description'     => $setting['description'],
									'input_attrs'     => $setting['input_attrs'],
									'label'           => $setting['label'],
									'priority'        => $setting['weight'],
									'section'         => $setting['section'],
									'settings'        => $setting_id,
								)
							)
						);
						break;
					// hidden-option
					// see ExS_Hidden_Customize_Control class
					case 'hidden-option':
						$customizer->add_control(
							new ExS_Hidden_Customize_Control(
								$customizer,
								$setting_id,
								array(
									'active_callback' => $active_callback,
									'description'     => $setting['description'],
									'input_attrs'     => $setting['input_attrs'],
									'label'           => $setting['label'],
									'priority'        => $setting['weight'],
									'section'         => $setting['section'],
									'settings'        => $setting_id,
								)
							)
						);
						break;
					// google-font
					// see ExS_Google_Font_Control class
					case 'google-font':
						if ( ! class_exists( 'ExS_Google_Font_Control' ) ) {
							break;
						}
						$customizer->add_control(
							new ExS_Google_Font_Control(
								$customizer,
								$setting_id,
								array(
									'active_callback' => $active_callback,
									'description'     => $setting['description'],
									'input_attrs'     => $setting['input_attrs'],
									'label'           => $setting['label'],
									'priority'        => $setting['weight'],
									'section'         => $setting['section'],
									'settings'        => $setting_id,
								)
							)
						);
						break;
					case 'color':
						$customizer->add_control(
							new WP_Customize_Color_Control(
								$customizer,
								$setting_id,
								array(
									'active_callback' => $active_callback,
									'description'     => $setting['description'],
									'input_attrs'     => $setting['input_attrs'],
									'label'           => $setting['label'],
									'priority'        => $setting['weight'],
									'section'         => $setting['section'],
									'settings'        => $setting_id,
								)
							)
						);
						break;
					case 'file':
						$customizer->add_control(
							new WP_Customize_Upload_Control(
								$customizer,
								$setting_id,
								array(
									'active_callback' => $active_callback,
									'description'     => $setting['description'],
									'input_attrs'     => $setting['input_attrs'],
									'label'           => $setting['label'],
									'priority'        => $setting['weight'],
									'section'         => $setting['section'],
									'settings'        => $setting_id,
								)
							)
						);
						break;
					case 'image':
						$customizer->add_control(
							new WP_Customize_Image_Control(
								$customizer,
								$setting_id,
								array(
									'active_callback' => $active_callback,
									'description'     => $setting['description'],
									'input_attrs'     => $setting['input_attrs'],
									'label'           => $setting['label'],
									'priority'        => $setting['weight'],
									'section'         => $setting['section'],
									'settings'        => $setting_id,
								)
							)
						);
						break;
					default:
						$customizer->add_control(
							$setting_id,
							array(
								'active_callback' => $active_callback,
								'choices'         => $setting['choices'],
								'description'     => $setting['description'],
								'input_attrs'     => $setting['input_attrs'],
								'label'           => $setting['label'],
								'priority'        => $setting['weight'],
								'section'         => $setting['section'],
								'type'            => $setting['type'],
							)
						);
				}
			endforeach;
		}

		/**
		 * Sanitizes the setting values based on the setting type, and optionally the choices defined for the setting.
		 */
		public function sanitize( $data ) {
			foreach ( $this->registered_settings as $setting_id => $setting ) :
				//if this is section, panel or not our setting - continue
				if (
					! array_key_exists( $setting_id, $data )
					|| 'panel' === $setting['type']
					|| 'section' === $setting['type']
				) {
					continue;
				}
				$choices   = isset( $setting['choices'] ) ? $setting['choices'] : array();
				$input     = $data[ $setting_id ];
				$sanitized = null;
				if ( isset( $setting['sanitize_cb'] ) && is_callable( $setting['sanitize_cb'] ) ) :
					$sanitized = call_user_func( $setting['sanitize_cb'], $input );
				else :
					switch ( $setting['type'] ) {
						case 'checkbox':
							$sanitized = ! empty( $input ) ? 1 : '';
							break;
						case 'color':
							$sanitized = sanitize_hex_color( $input );
							break;
						case 'dropdown-category':
						case 'dropdown-pages':
							$sanitized = intval( $input );
							break;
						case 'file':
						case 'url':
						case 'image':
							$sanitized = esc_url_raw( $input );
							break;
						case 'radio':
						case 'select':
							$sanitized = array_key_exists( $input, $choices ) ? $input : null;
							break;
						case 'text':
						case 'textarea':
							$sanitized = wp_kses_post( $input );
							break;
						default:
							$sanitized = sanitize_text_field( $input );
							break;
					}
				endif;
				$data[ $setting_id ] = $sanitized;
			endforeach;

			return $data;
		}

		/**
		 * Adds a setting to the $registered_settings array.
		 */
		public function add_setting( $id, $setting ) {
			// Make sure the basic requirements for registering a setting are included.
			if ( ! ( isset( $id ) && isset( $setting['label'] ) ) ) {
				// for non panels and no sections - return if blank section
				if ( 'panel' !== $setting['type'] && 'section' !== $setting['type'] && empty( $setting['section'] ) ) {
					return;
				}

				return;
			}
			// Default to 'text' if no setting type is specified.
			if ( ! isset( $setting['type'] ) ) {
				$setting['type'] = 'text';
			}
			// If we're not running WordPress 4.0, change any unrecognized control types to "text".
			if ( ! version_compare( get_bloginfo( 'version' ), 4.0, '>=' ) && ! in_array( $setting['type'], $this->legacy_valid_control_types, true ) ) {
				$setting['type'] = 'text';
			}
			// If this is a radio or select setting, make sure there are choices specified.
			if ( ( 'radio' === $setting['type'] || 'select' === $setting['type'] ) && empty( $setting['choices'] ) ) {
				return;
			}
			$this->registered_settings[ $id ] = array(
				'label'           => $setting['label'],
				'section'         => isset( $setting['section'] ) ? $setting['section'] : null,
				'type'            => $setting['type'],
				'active_callback' => isset( $setting['active_callback'] ) ? $setting['active_callback'] : null,
				'choices'         => isset( $setting['choices'] ) ? $setting['choices'] : array(),
				'default'         => isset( $setting['default'] ) ? $setting['default'] : null,
				'description'     => isset( $setting['description'] ) ? $setting['description'] : null,
				'input_attrs'     => isset( $setting['atts'] ) ? $setting['atts'] : array(),
				'weight'          => isset( $setting['weight'] ) ? $setting['weight'] : null,
				//new - for panels and sections
				'panel'           => isset( $setting['panel'] ) ? $setting['panel'] : null,
				'priority'        => isset( $setting['priority'] ) ? $setting['priority'] : null,
				//our custom visible
				'visible'         => isset( $setting['visible'] ) ? $setting['visible'] : array(),
			);
		}
	}

endif; //class_exists
