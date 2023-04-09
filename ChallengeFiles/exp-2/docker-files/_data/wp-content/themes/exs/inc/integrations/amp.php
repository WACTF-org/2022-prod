<?php
/**
 * AMP support
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.8.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//add sidebar position option for product and shop
add_filter( 'exs_customizer_options', 'exs_amp_filter_exs_customizer_options' );
if ( ! function_exists( 'exs_amp_filter_exs_customizer_options' ) ) :
	function exs_amp_filter_exs_customizer_options( $options ) {
		//sections
		$options['contact_form_amp'] = array(
			'type'        => 'checkbox',
			'label'   => esc_html__( 'Optimize ExS Contact Form for the AMP plugin', 'exs' ),
			'default' => esc_html( exs_option( 'contact_form_amp', '' ) ),
			'description' => esc_html__( 'If checked will modify contact form markup for work with AMP plugin', 'exs' ),
			'section'     => 'section_contact_messages',
		);

		return $options;
	}
endif;

if ( exs_option( 'contact_form_amp' ) ) :
	add_action( 'wp', function (){
		if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
			add_filter( 'the_content', function ($html){
				$close_html='<input type="hidden" name="nonce" value="'.wp_create_nonce( 'exs_nonce' ).'"><input type="hidden" name="action" value="exs_ajax_form">';

				//contact_message_success
				//contact_message_fail
				$close_html .= '<div submit-success><template type="amp-mustache">'.exs_option('contact_message_success').'</template></div>';
				$close_html .='<div submit-error><template type="amp-mustache">'.exs_option('contact_message_fail').'</template></div>';

				$html = str_replace( '<form ', '<form method="post" action-xhr="' .esc_url( admin_url( 'admin-ajax.php' ) ) . '" ', $html );
				$html = str_replace( '</form>', $close_html . '</form>', $html );
				return $html;
			}, 999 );
		}
	});
endif;
