<?php
/**
 * Advanced Custom Fields support
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.3.11
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//ACF customizer options

//add sidebar position option for product and shop
add_filter( 'exs_customizer_options', 'exs_filter_exs_customizer_options_for_acf' );
if ( ! function_exists( 'exs_filter_exs_customizer_options_for_acf' ) ) :
	function exs_filter_exs_customizer_options_for_acf( $options ) {

		//single post
		$options['blog_single_acf_heading'] = array(
			'type'        => 'block-heading',
			'section'     => 'section_blog_post',
			'label'       => esc_html__( 'Advanced Custom Fields Display Options', 'exs' ),
			'description' => esc_html__( 'You can display your custom fields before or after your post content', 'exs' ),
		);
		$options['blog_single_acf_show'] = array(
			'type'        => 'radio',
			'section'     => 'section_blog_post',
			'default'     => exs_option( 'blog_single_acf_show', '' ),
			'label'       => esc_html__( 'Show Custom Fields', 'exs' ),
			'choices' => array(
				''     => esc_html__( 'No', 'exs' ),
				'before' => esc_html__( 'Before Content', 'exs' ),
				'after' => esc_html__( 'After Content', 'exs' ),
			),
		);
		$options['blog_single_acf_title']      = array(
			'type'    => 'text',
			'section' => 'section_blog_post',
			'label'   => esc_html__( 'Fields Title', 'exs' ),
			'default' => esc_html( exs_option( 'blog_single_acf_title', '' ) ),
		);
		$options['blog_single_acf_background'] = array(
			'type'    => 'color-radio',
			'section' => 'section_blog_post',
			'label'   => esc_html__( 'Fields Background', 'exs' ),
			'default' => esc_html( exs_option( 'blog_single_acf_background', '' ) ),
			'choices' => exs_customizer_backgrounds_array(),
		);
		$options['blog_single_acf_bordered']   = array(
			'type'    => 'checkbox',
			'section' => 'section_blog_post',
			'label'   => esc_html__( 'Add border', 'exs' ),
			'default' => exs_option( 'blog_single_acf_bordered', false ),
		);
		$options['blog_single_acf_shadow']     = array(
			'type'    => 'checkbox',
			'section' => 'section_blog_post',
			'label'   => esc_html__( 'Add shadow', 'exs' ),
			'default' => exs_option( 'blog_single_acf_shadow', false ),
		);
		$options['blog_single_acf_rounded']    = array(
			'type'    => 'checkbox',
			'section' => 'section_blog_post',
			'label'   => esc_html__( 'Rounded', 'exs' ),
			'default' => exs_option( 'blog_single_acf_rounded', false ),
		);
		$options['blog_single_acf_format'] = array(
			'type'        => 'radio',
			'section'     => 'section_blog_post',
			'default'     => exs_option( 'blog_single_acf_format', '' ),
			'label'       => esc_html__( 'Fields Wrapper', 'exs' ),
			'choices' => array(
				''     => esc_html__( 'Unordered List', 'exs' ),
				'div' => esc_html__( 'Div tag', 'exs' ),
			),
		);
		$options['blog_single_acf_hide_labels']     = array(
			'type'    => 'checkbox',
			'section' => 'section_blog_post',
			'label'   => esc_html__( 'Hide field labels', 'exs' ),
			'default' => exs_option( 'blog_single_acf_hide_labels', false ),
		);
		$options['blog_single_acf_mt']         = array(
			'type'    => 'select',
			'section' => 'section_blog_post',
			'label'   => esc_html__( 'Fields top margin', 'exs' ),
			'default' => esc_html( exs_option( 'blog_single_acf_mt', 'mt-2' ) ),
			'choices' => array(
				''      => esc_html__( 'Default', 'exs' ),
				'mt-0'  => '0',
				'mt-05' => '0.5em',
				'mt-1'  => '1em',
				'mt-15' => '1.5em',
				'mt-2'  => '2em',
				'mt-3'  => '3em',
				'mt-4'  => '4em',
				'mt-5'  => '5em',
			),
		);
		$options['blog_single_acf_mb']         = array(
			'type'    => 'select',
			'section' => 'section_blog_post',
			'label'   => esc_html__( 'Fields bottom margin', 'exs' ),
			'default' => esc_html( exs_option( 'blog_single_acf_mb', 'mb-2' ) ),
			'choices' => array(
				''      => esc_html__( 'Default', 'exs' ),
				'mb-0'  => '0',
				'mb-05' => '0.5em',
				'mb-1'  => '1em',
				'mb-15' => '1.5em',
				'mb-2'  => '2em',
				'mb-3'  => '3em',
				'mb-4'  => '4em',
				'mb-5'  => '5em',
			),
		);
		$options['blog_single_acf_css_class']      = array(
			'type'    => 'text',
			'section' => 'section_blog_post',
			'label'   => esc_html__( 'Fields Wrapper CSS class', 'exs' ),
			'default' => esc_html( exs_option( 'blog_single_acf_css_class', '' ) ),
		);
		$options['blog_single_acf_all_post_types']   = array(
			'type'    => 'checkbox',
			'section' => 'section_blog_post',
			'label'   => esc_html__( 'Enable for all singular post types', 'exs' ),
			'default' => exs_option( 'blog_single_acf_all_post_types', false ),
			'description' => esc_html__( 'You can display your custom fields for pages, products or any other custom post types', 'exs' ),
		);

		//taxonomy
		$options['blog_acf_heading'] = array(
			'type'        => 'block-heading',
			'section'     => 'section_blog',
			'label'       => esc_html__( 'Advanced Custom Fields Display Options', 'exs' ),
			'description' => esc_html__( 'You can display your custom fields before or after your taxonomies', 'exs' ),
		);
		$options['blog_single_acf_show_in_loop']   = array(
			'type'    => 'checkbox',
			'section' => 'section_blog',
			'label'   => esc_html__( 'Show post custom fields in the posts loop', 'exs' ),
			'default' => exs_option( 'blog_single_acf_show_in_loop', false ),
			'description' => esc_html__( 'You can display your custom fields for the posts archive if the chosen blog layout supports custom fields for posts', 'exs' ),
		);
		$options['blog_acf_show'] = array(
			'type'        => 'radio',
			'section'     => 'section_blog',
			'default'     => exs_option( 'blog_acf_show', '' ),
			'label'       => esc_html__( 'Show Taxonomy Custom Fields', 'exs' ),
			'choices' => array(
				''     => esc_html__( 'No', 'exs' ),
				'before' => esc_html__( 'Before Taxonomy Loop', 'exs' ),
				'after' => esc_html__( 'After Taxonomy Loop', 'exs' ),
			),
		);
		$options['blog_acf_title']      = array(
			'type'    => 'text',
			'section' => 'section_blog',
			'label'   => esc_html__( 'Fields Title', 'exs' ),
			'default' => esc_html( exs_option( 'blog_acf_title', '' ) ),
		);
		$options['blog_acf_background'] = array(
			'type'    => 'color-radio',
			'section' => 'section_blog',
			'label'   => esc_html__( 'Fields Background', 'exs' ),
			'default' => esc_html( exs_option( 'blog_acf_background', '' ) ),
			'choices' => exs_customizer_backgrounds_array(),
		);
		$options['blog_acf_bordered']   = array(
			'type'    => 'checkbox',
			'section' => 'section_blog',
			'label'   => esc_html__( 'Add border', 'exs' ),
			'default' => exs_option( 'blog_acf_bordered', false ),
		);
		$options['blog_acf_shadow']     = array(
			'type'    => 'checkbox',
			'section' => 'section_blog',
			'label'   => esc_html__( 'Add shadow', 'exs' ),
			'default' => exs_option( 'blog_acf_shadow', false ),
		);
		$options['blog_acf_rounded']    = array(
			'type'    => 'checkbox',
			'section' => 'section_blog',
			'label'   => esc_html__( 'Rounded', 'exs' ),
			'default' => exs_option( 'blog_acf_rounded', false ),
		);
		$options['blog_acf_format'] = array(
			'type'        => 'radio',
			'section'     => 'section_blog',
			'default'     => exs_option( 'blog_acf_format', '' ),
			'label'       => esc_html__( 'Fields Wrapper', 'exs' ),
			'choices' => array(
				''     => esc_html__( 'Unordered List', 'exs' ),
				'div' => esc_html__( 'Div tag', 'exs' ),
			),
		);
		$options['blog_acf_hide_labels']     = array(
			'type'    => 'checkbox',
			'section' => 'section_blog',
			'label'   => esc_html__( 'Hide field labels', 'exs' ),
			'default' => exs_option( 'blog_acf_hide_labels', false ),
		);
		$options['blog_acf_mt']         = array(
			'type'    => 'select',
			'section' => 'section_blog',
			'label'   => esc_html__( 'Fields top margin', 'exs' ),
			'default' => esc_html( exs_option( 'blog_acf_mt', 'mt-2' ) ),
			'choices' => array(
				''      => esc_html__( 'Default', 'exs' ),
				'mt-0'  => '0',
				'mt-05' => '0.5em',
				'mt-1'  => '1em',
				'mt-15' => '1.5em',
				'mt-2'  => '2em',
				'mt-3'  => '3em',
				'mt-4'  => '4em',
				'mt-5'  => '5em',
			),
		);
		$options['blog_acf_mb']         = array(
			'type'    => 'select',
			'section' => 'section_blog',
			'label'   => esc_html__( 'Fields bottom margin', 'exs' ),
			'default' => esc_html( exs_option( 'blog_single_acf_mb', 'mb-2' ) ),
			'choices' => array(
				''      => esc_html__( 'Default', 'exs' ),
				'mb-0'  => '0',
				'mb-05' => '0.5em',
				'mb-1'  => '1em',
				'mb-15' => '1.5em',
				'mb-2'  => '2em',
				'mb-3'  => '3em',
				'mb-4'  => '4em',
				'mb-5'  => '5em',
			),
		);
		$options['blog_acf_css_class']      = array(
			'type'    => 'text',
			'section' => 'section_blog',
			'label'   => esc_html__( 'Fields Wrapper CSS class', 'exs' ),
			'default' => esc_html( exs_option( 'blog_acf_css_class', '' ) ),
		);

		return $options;
	}
endif;

//get fields html
//return array with table and default fields html
if ( ! function_exists( 'exs_acf_get_html_from_fields' ) ) :
	function exs_acf_get_html_from_fields( $fields = array(), $format = 'ul', $hide_labels = false ) {

		$fields_html = '';
		$table_html = '';

		$ul_tag = ( 'ul' === $format || empty( $format ) ) ? 'ul' : 'div';
		$li_tag = ( 'ul' === $format || empty( $format ) ) ? 'li' : 'div';

		$label_class = $hide_labels ? 'screen-reader-text' : '';

		foreach( $fields as $field ) :
			$id = ! empty( $field['wrapper']['id'] ) ? ' id="' . esc_attr( $field['wrapper']['id'] ). '"' : '';
			$class = ! empty( $field['wrapper']['class'] ) ? ' class="' . esc_attr( $field['wrapper']['class'] ). '"' : '';
			if ( ! empty( $field['value'] ) ) {
				if ( is_string( $field['value' ] ) ) :
					if( 'oembed' === $field['type'] ) {
						$fields_html .= '<' . esc_html( $li_tag ) . $id . $class . '><strong class="' . esc_attr( $label_class ) . '">' . esc_html( $field['label'] ) . ':</strong> ' . $field['value'] . '</' . esc_html( $li_tag ) . '>';
					} else {
						$fields_html .= '<' . esc_html( $li_tag ) . $id . $class . '><strong class="' . esc_attr( $label_class ) . '">' . esc_html( $field['label'] ) . ':</strong> ' . wp_kses_post( $field['value'] ) . '</' . esc_html( $li_tag ) . '>';
					}
				elseif ( is_double( $field['value' ] ) || is_int( $field['value' ] ) ) :
					//https://github.com/kevinruscoe/acf-star-rating-field
					if ( 'star_rating_field' === $field['type'] ) {
						$fields_html .= '<' . esc_html( $li_tag ) . $id . $class . '><strong class="' . esc_attr( $label_class ) . '">' . esc_html( $field['label'] ) . ':</strong> ' . esc_html( (int) $field['value'] ) . '/' . esc_html( (int) $field['max_stars'] ) . '</' . esc_html( $li_tag ) . '>';
					}
					else {
						$fields_html.= '<' . esc_html( $li_tag ) . $id . $class . '><strong class="' . esc_attr( $label_class ) . '">' . esc_html( $field['label'] ) . ':</strong> ' . wp_kses_post( $field['value'] ) . '</' . esc_html( $li_tag ) . '>';
					}
				elseif ( is_array( $field['value'] ) ) :
					if ( 'image' === $field['type'] ) :
						if ( ! empty( $field['value']['url'] ) ) {
							$fields_html .= '<' . esc_html( $li_tag ) . $id . $class . '><img src="' . esc_url( $field['value']['url'] ) . '" alt="' . esc_attr( $field['value']['alt'] ) .  '"></' . esc_html( $li_tag ) . '>';
						}
					endif; //image
					if ( 'repeater' === $field['type'] ) :
						foreach ( $field['value'] as $repeater_group ) :
							$fields_html .= '<' . esc_html( $li_tag ) . $id . $class . '><' . esc_html( $ul_tag ) . ' class="exs-acf-repeater">';
							foreach ( $repeater_group as $value_type => $value_value ) :
								if ( is_string( $value_value ) ) :
									$fields_html.= '<' . esc_html( $li_tag ) . $id . $class . '>' . wp_kses_post( $value_value ) . '</' . esc_html( $li_tag ) . '>';
								elseif ( is_array( $value_value ) ) :
									if ( 'image' === $value_type && ( ! empty( $value_value['url'] ) ) ) {
										$fields_html.= '<' . esc_html( $li_tag ) . $id . $class . '><img src="' . esc_url( $value_value['url'] ) . '" alt="' . esc_attr( $value_value['alt'] ) .  '"></' . esc_html( $li_tag ) . '>';
									}
								endif;
							endforeach; //repeater fields
							$fields_html .= '</' . esc_html( $ul_tag ) . '></' . esc_html( $li_tag ) . '>';
						endforeach; //repeater fields
					endif; //repeater
					if ( 'table' === $field['type'] ) :
						$table_html .= '<table class="exs-acf-table-field mt-1 mb-1">';
						if ( ! empty( $field['value']['caption'] ) ) {
							$table_html .= '<caption>' . $field['value']['caption'] . '</caption>';
						}
						//heading
						$table_html .= '<tr>';
						foreach ( $field['value']['header'] as $header_item ) {
							$table_html .= '<th>' . $header_item['c']. '</th>';
						}
						$table_html .= '</tr>';
						//body
						foreach ( $field['value']['body'] as $table_row ) {
							$table_html .= '<tr>';
							foreach ( $table_row as $table_td ) {
								$table_html .= '<td>' . $table_td['c'] . '</td>';
							}
							$table_html .= '</tr>';
						}
						$table_html .= '</table>';
					endif; //table
					//https://github.com/gillesgoetsch/acf-smart-button
					if ( 'smart_button' === $field['type'] ) :
						if ( ! empty( $field['value']['url'] ) ) {
							$fields_html .= '<' . esc_html( $li_tag ) . $id . $class . '><a class="wp-block-button__link" href="' . esc_url( $field['value']['url'] ) . '">' . esc_html( $field['value']['text'] ) . '</a></' . esc_html( $li_tag ) .'>';
						}
					endif; //image
				endif;
			}
		endforeach;

		return array(
			'fields_html' => $fields_html,
			'table_html'  => $table_html,
		);
	}
endif;

//add custom fields values to the content
if ( ! function_exists( 'exs_filter_the_content_add_acf_fields_in_post' ) ) :
	function exs_filter_the_content_add_acf_fields_in_post( $html ) {

		if ( ! is_main_query() || ! in_the_loop() ) {
			return $html;
		}

		$acf_position  = exs_option( 'blog_single_acf_show' );
		$acf_posttypes = exs_option( 'blog_single_acf_all_post_types' ) ? '' : 'post';
		//return if not single post
		if ( ! is_singular( $acf_posttypes ) || empty( $acf_position ) ) {
			return $html;
		}

		$fields = get_field_objects();

		if ( empty( $fields ) ) {
			return $html;
		}

		$acf_format = exs_option( 'blog_single_acf_format' );
		$acf_labels = exs_option( 'blog_single_acf_hide_labels' );
		$fields_html_array = exs_acf_get_html_from_fields( $fields, $acf_format, $acf_labels );

		$fields_html = $fields_html_array['fields_html'];
		$table_html = $fields_html_array['table_html'];

		if ( empty( $fields_html ) && empty( $table_html ) ) {
			return $html;
		}

		$ul_tag = ( 'ul' === $acf_format || empty( $acf_format ) ) ? 'ul' : 'div';

		$acf_html = '';
		//create an ACF markup
		$acf_title = exs_option( 'blog_single_acf_title', '' );
		$acf_bg = exs_option( 'blog_single_acf_background', '' );
		$acf_mt = exs_option( 'blog_single_acf_mt', 'mt-2' );
		$acf_mb = exs_option( 'blog_single_acf_mb', 'mb-2' );
		$acf_padding = $acf_bg ? ' extra-padding' : '';
		$acf_bordered = exs_option( 'blog_single_acf_bordered', '' ) ? ' bordered' : '';
		$acf_shadow = exs_option( 'blog_single_acf_shadow', '' ) ? ' shadow' : '';
		$acf_rounded = exs_option( 'blog_single_acf_rounded', '' ) ? ' rounded' : '';
		$acf_css_class = exs_option( 'blog_single_acf_css_class', '' ) ? ' ' . exs_option( 'blog_single_acf_css_class', '' ) : '';
		$acf_html .= '<aside class="exs-acf ' . esc_attr( $acf_mt . ' ' . $acf_mb . ' ' . $acf_bg . $acf_padding . $acf_bordered . $acf_shadow . $acf_rounded . $acf_css_class ) . '">';
		if ( ! empty( $acf_title ) ) {
			$acf_html .= '<h3 class="exs-acf-title mb-05">' . esc_html ( $acf_title ) . '</h3>';
		}
		$acf_html .= '<' . esc_html( $ul_tag ) . ' class="exs-acf-fields mb-0">';
		$acf_html .= $fields_html;
		$acf_html .= '</'. esc_html( $ul_tag ) .'>' . $table_html . '</aside>';

		if( 'before' === $acf_position ) {
			return $acf_html . $html;
		} elseif ( 'after' === $acf_position ) {
			return $html . $acf_html;
		} else {
			return $html;
		}
	}
endif;
add_filter( 'the_content', 'exs_filter_the_content_add_acf_fields_in_post', 99 );

//add custom fields values to the content in the loop
if ( ! function_exists( 'exs_action_add_acf_fields_in_post_loop' ) ) :
	function exs_action_add_acf_fields_in_post_loop() {

		$acf_position      = exs_option( 'blog_single_acf_show' );
		$acf_position_loop = exs_option( 'blog_single_acf_show_in_loop' );
		//return if not single post
		if ( is_singular() || ! $acf_position || ! $acf_position_loop ) {
			return;
		}

		$fields = get_field_objects();

		if ( empty( $fields ) ) {
			return;
		}

		$acf_format = exs_option( 'blog_single_acf_format' );
		$acf_labels = exs_option( 'blog_single_acf_hide_labels' );
		$fields_html_array = exs_acf_get_html_from_fields( $fields, $acf_format, $acf_labels );

		$fields_html = $fields_html_array['fields_html'];
		$table_html = $fields_html_array['table_html'];

		if ( empty( $fields_html ) && empty( $table_html ) ) {
			return;
		}

		$ul_tag = ( 'ul' === $acf_format || empty( $acf_format ) ) ? 'ul' : 'div';

		$acf_html = '';
		//create an ACF markup
		$acf_title = exs_option( 'blog_single_acf_title', '' );
		$acf_bg = exs_option( 'blog_single_acf_background', '' );
		$acf_mt = exs_option( 'blog_single_acf_mt', 'mt-2' );
		$acf_mb = exs_option( 'blog_single_acf_mb', 'mb-2' );
		$acf_padding = $acf_bg ? ' extra-padding' : '';
		$acf_bordered = exs_option( 'blog_single_acf_bordered', '' ) ? ' bordered' : '';
		$acf_shadow = exs_option( 'blog_single_acf_shadow', '' ) ? ' shadow' : '';
		$acf_rounded = exs_option( 'blog_single_acf_rounded', '' ) ? ' rounded' : '';
		$acf_css_class = exs_option( 'blog_single_acf_css_class', '' ) ? ' ' . exs_option( 'blog_single_acf_css_class', '' ) : '';
		$acf_html .= '<aside class="exs-acf ' . esc_attr( $acf_mt . ' ' . $acf_mb . ' ' . $acf_bg . $acf_padding . $acf_bordered . $acf_shadow . $acf_rounded . $acf_css_class ) . '">';
		if ( ! empty( $acf_title ) ) {
			$acf_html .= '<h3 class="exs-acf-title mb-05">' . esc_html ( $acf_title ) . '</h3>';
		}
		$acf_html .= '<' . esc_html( $ul_tag ) . ' class="exs-acf-fields mb-0">';
		$acf_html .= $fields_html;
		$acf_html .= '</'. esc_html( $ul_tag ) .'>' . $table_html . '</aside>';

		echo wp_kses_post( $acf_html );
	}
endif;
add_action( 'exs_action_loop_before_content', 'exs_action_add_acf_fields_in_post_loop' );

//terms
if ( ! function_exists( 'exs_action_acf_top_of_archive' ) ) :
	function exs_action_acf_top_of_archive() {
		$acf_position = exs_option( 'blog_acf_show' );

		//return if not single post
		if ( is_singular() || 'before' !== $acf_position ) {
			return;
		}

		$term = get_queried_object();

		//fix for is_home
		if( empty( $term ) ) {
			return;
		}

		$fields = get_field_objects( $term );

		if ( empty( $fields ) ) {
			return;
		}

		$acf_format = exs_option( 'blog_acf_format' );
		$acf_labels = exs_option( 'blog_acf_hide_labels' );
		$fields_html_array = exs_acf_get_html_from_fields( $fields, $acf_format, $acf_labels );

		$fields_html = $fields_html_array['fields_html'];
		$table_html = $fields_html_array['table_html'];

		if ( empty( $fields_html ) && empty( $table_html ) ) {
			return;
		}

		$ul_tag = ( 'ul' === $acf_format || empty( $acf_format ) ) ? 'ul' : 'div';

		$acf_html = '';
		//create an ACF markup
		$acf_title = exs_option( 'blog_acf_title', '' );
		$acf_bg = exs_option( 'blog_acf_background', '' );
		$acf_mt = exs_option( 'blog_acf_mt', 'mt-2' );
		$acf_mb = exs_option( 'blog_acf_mb', 'mb-2' );
		$acf_padding = $acf_bg ? ' extra-padding' : '';
		$acf_bordered = exs_option( 'blog_acf_bordered', '' ) ? ' bordered' : '';
		$acf_shadow = exs_option( 'blog_acf_shadow', '' ) ? ' shadow' : '';
		$acf_rounded = exs_option( 'blog_acf_rounded', '' ) ? ' rounded' : '';
		$acf_css_class = exs_option( 'blog_acf_css_class', '' ) ? ' ' . exs_option( 'blog_acf_css_class', '' ) : '';
		$acf_html .= '<aside class="exs-acf ' . esc_attr( $acf_mt . ' ' . $acf_mb . ' ' . $acf_bg . $acf_padding . $acf_bordered . $acf_shadow . $acf_rounded . $acf_css_class ) . '">';
		if ( ! empty( $acf_title ) ) {
			$acf_html .= '<h3 class="exs-acf-title mb-05">' . esc_html ( $acf_title ) . '</h3>';
		}
		$acf_html .= '<' . esc_html( $ul_tag ) . ' class="exs-acf-fields mb-0">';
		$acf_html .= $fields_html;
		$acf_html .= '</'. esc_html( $ul_tag ) .'>' . $table_html . '</aside>';

		echo wp_kses_post( $acf_html );
	}
endif;
if ( ! function_exists( 'exs_action_acf_bottom_of_archive' ) ) :
	function exs_action_acf_bottom_of_archive() {
		$acf_position = exs_option( 'blog_acf_show' );

		//return if not single post
		if ( is_singular() || 'after' !== $acf_position ) {
			return;
		}

		$term = get_queried_object();

		//fix for is_home
		if( empty( $term ) ) {
			return;
		}

		$fields = get_field_objects( $term );

		if ( empty( $fields ) ) {
			return;
		}

		$acf_format = exs_option( 'blog_acf_format' );
		$acf_labels = exs_option( 'blog_acf_hide_labels' );
		$fields_html_array = exs_acf_get_html_from_fields( $fields, $acf_format, $acf_labels );

		$fields_html = $fields_html_array['fields_html'];
		$table_html = $fields_html_array['table_html'];

		if ( empty( $fields_html ) && empty( $table_html ) ) {
			return;
		}

		$ul_tag = ( 'ul' === $acf_format || empty( $acf_format ) ) ? 'ul' : 'div';

		$acf_html = '';
		//create an ACF markup
		$acf_title = exs_option( 'blog_acf_title', '' );
		$acf_bg = exs_option( 'blog_acf_background', '' );
		$acf_mt = exs_option( 'blog_acf_mt', 'mt-2' );
		$acf_mb = exs_option( 'blog_acf_mb', 'mb-2' );
		$acf_padding = $acf_bg ? ' extra-padding' : '';
		$acf_bordered = exs_option( 'blog_acf_bordered', '' ) ? ' bordered' : '';
		$acf_shadow = exs_option( 'blog_acf_shadow', '' ) ? ' shadow' : '';
		$acf_rounded = exs_option( 'blog_acf_rounded', '' ) ? ' rounded' : '';
		$acf_css_class = exs_option( 'blog_acf_css_class', '' ) ? ' ' . exs_option( 'blog_acf_css_class', '' ) : '';
		$acf_html .= '<aside class="exs-acf ' . esc_attr( $acf_mt . ' ' . $acf_mb . ' ' . $acf_bg . $acf_padding . $acf_bordered . $acf_shadow . $acf_rounded . $acf_css_class ) . '">';
		if ( ! empty( $acf_title ) ) {
			$acf_html .= '<h3 class="exs-acf-title mb-05">' . esc_html ( $acf_title ) . '</h3>';
		}
		$acf_html .= '<' . esc_html( $ul_tag ) . ' class="exs-acf-fields mb-0">';
		$acf_html .= $fields_html;
		$acf_html .= '</'. esc_html( $ul_tag ) .'>' . $table_html . '</aside>';

		echo wp_kses_post( $acf_html );
	}
endif;
add_action( 'exs_action_top_of_archive', 'exs_action_acf_top_of_archive' );
add_action( 'exs_action_bottom_of_archive', 'exs_action_acf_bottom_of_archive' );
