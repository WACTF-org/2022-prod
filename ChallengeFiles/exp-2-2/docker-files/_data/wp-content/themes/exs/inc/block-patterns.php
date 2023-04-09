<?php
/**
 * Block patterns support
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.1.0
 * @link https://developer.wordpress.org/block-editor/developers/block-api/block-patterns/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'register_block_pattern_category' ) ) {
	return;
}

if ( ! function_exists( 'exs_register_theme_block_patterns' ) ) {
	function exs_register_theme_block_patterns() {
		register_block_pattern_category(
			'exs',
			array( 'label' => esc_html__( 'ExS', 'exs' ) )
		);

		$exs_patterns = apply_filters(
			'exs_block_patterns',
			array(
				'exs/title-with-subtitle'             => array(
					'title'       => esc_html__( 'Title with subtitle', 'exs' ),
					'description' => esc_html__( 'Title heading with sub title and separator below it.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'title-with-subtitle' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'title', 'subtitle', 'heading' ),
				),
				'exs/cols-3-feature-image-boxes'      => array(
					'title'       => esc_html__( 'Three featured columns', 'exs' ),
					'description' => esc_html__( 'Three columns with image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-3-feature-image-boxes' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-4-feature-blocks'           => array(
					'title'       => esc_html__( 'Four featured columns', 'exs' ),
					'description' => esc_html__( 'Four columns with image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-feature-blocks' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-4-feature-blocks-left'      => array(
					'title'       => esc_html__( 'Four featured columns', 'exs' ),
					'description' => esc_html__( 'Four columns with left aligned image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-feature-blocks-left' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-3-feature-side-image-boxes' => array(
					'title'       => esc_html__( 'Three side featured columns', 'exs' ),
					'description' => esc_html__( 'Three columns with side image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-3-feature-side-image-boxes' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-6-feature-blocks'           => array(
					'title'       => esc_html__( 'Six featured columns', 'exs' ),
					'description' => esc_html__( 'Six columns with image boxes with titles.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-6-feature-blocks' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-4-progress'                 => array(
					'title'       => esc_html__( 'Four columns with progress', 'exs' ),
					'description' => esc_html__( 'Four columns with progress image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-progress' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'progress', 'image box' ),
				),
				'exs/cols-4-team-members'             => array(
					'title'       => esc_html__( 'Four columns with team', 'exs' ),
					'description' => esc_html__( 'Four columns with team members photo and description.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-team-members' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-4-contacts'                 => array(
					'title'       => esc_html__( 'Four columns with contacts', 'exs' ),
					'description' => esc_html__( 'Four columns with contact info.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-contacts' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'contact', 'contacts', 'image box' ),
				),
				'exs/cols-2-blockquotes'              => array(
					'title'       => esc_html__( 'Two columns with blockquotes', 'exs' ),
					'description' => esc_html__( 'Two columns with testimonials blockquotes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-blockquotes' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'testimonials', 'blockquotes' ),
				),
				'exs/cols-2-blockquotes-simple'              => array(
					'title'       => esc_html__( 'Two columns with simple blockquotes', 'exs' ),
					'description' => esc_html__( 'Two columns with simple testimonials blockquotes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-blockquotes-simple' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'testimonials', 'blockquotes' ),
				),
				'exs/cover-call-to-action'            => array(
					'title'       => esc_html__( 'Cover call to action', 'exs' ),
					'description' => esc_html__( 'Call to action cover block with title and button.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cover-call-to-action' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'cover', 'call', 'action' ),
				),
				'exs/cols-2-cta-side-boxes'            => array(
					'title'       => esc_html__( '2 columns call to action', 'exs' ),
					'description' => esc_html__( 'Call to action in two columns', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-cta-side-boxes' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'call', 'action' ),
				),
				'exs/cols-3-text-actions'            => array(
					'title'       => esc_html__( 'Text columns call to action', 'exs' ),
					'description' => esc_html__( 'Call to action columns block with text and button.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-3-text-actions' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'call', 'action' ),
				),
				'exs/cols-2-call-to-action'           => array(
					'title'       => esc_html__( 'Two columns call to action', 'exs' ),
					'description' => esc_html__( 'Call to action text block with heading and button in two columns.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-call-to-action' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'call', 'action', 'columns' ),
				),
				'exs/cta-1'                          => array(
					'title'       => esc_html__( 'Call to action heading', 'exs' ),
					'description' => esc_html__( 'Call to action heading text with button.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cta-1' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'call', 'action' ),
				),
				'exs/cta-2'                          => array(
					'title'       => esc_html__( 'Call to action heading', 'exs' ),
					'description' => esc_html__( 'Call to action heading text with two buttons.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cta-2' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'call', 'action' ),
				),
				'exs/media-text-simple'              => array(
					'title'       => esc_html__( 'Side image', 'exs' ),
					'description' => esc_html__( 'Simple side image block.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'media-text-simple' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'call', 'action' ),
				),
				'exs/media-text-2-cols'              => array(
					'title'       => esc_html__( 'Call to action side image', 'exs' ),
					'description' => esc_html__( 'Call to action side image block.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'media-text-2-cols' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'call', 'action' ),
				),
				'exs/cols-2-dropcaps'                 => array(
					'title'       => esc_html__( 'Two columns text with dropcaps', 'exs' ),
					'description' => esc_html__( 'Text with two columns with dropcaps.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-dropcaps' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'text', 'columns' ),
				),
				'exs/cols-2-faq'                      => array(
					'title'       => esc_html__( 'Two columns simple text', 'exs' ),
					'description' => esc_html__( 'Text with two columns.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-faq' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'text', 'columns', 'faq' ),
				),
				'exs/cols-2-image-side-boxes'         => array(
					'title'       => esc_html__( 'Two columns with image', 'exs' ),
					'description' => esc_html__( 'Two columns with image and side image boxes', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-image-side-boxes' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'text', 'columns' ),
				),
				'exs/cols-2-person'                   => array(
					'title'       => esc_html__( 'Two columns with person image', 'exs' ),
					'description' => esc_html__( 'Two columns with person  image and description', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-person' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'text', 'columns', 'about', 'image' ),
				),
				'exs/form-1'           => array(
					'title'       => esc_html__( 'Simple Contact Form', 'exs' ),
					'description' => esc_html__( 'Contact form with name, email and message fields.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'form-1' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'contact', 'form' ),
				),
				'exs/form-2'           => array(
					'title'       => esc_html__( 'Simple 2 Columns Contact Form', 'exs' ),
					'description' => esc_html__( 'Contact form with name, email and message fields in 2 columns.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'form-2' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'contact', 'form', 'columns' ),
				),
				'exs/form-subscribe'           => array(
					'title'       => esc_html__( 'Simple MailChimp Subscribe Form', 'exs' ),
					'description' => esc_html__( 'Mailchimp subscribe form with name and email fields.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'form-subscribe' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'subscribe', 'mailchimp' ),
				),
				'exs/form-subscribe-inline'           => array(
					'title'       => esc_html__( 'Inline MailChimp Subscribe Form', 'exs' ),
					'description' => esc_html__( 'Inline MailChimp subscribe form with email field.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'form-subscribe-inline' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'subscribe', 'mailchimp', 'inline' ),
				),
				'exs/cover-subscribe-center-inline'           => array(
					'title'       => esc_html__( 'Inline subscribe form in a cover', 'exs' ),
					'description' => esc_html__( 'Inline MailChimp subscribe form within a cover block.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cover-subscribe-center-inline' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'subscribe', 'mailchimp', 'inline', 'cover', 'fullwidth' ),
				),
				'exs/cols-2-subscribe-form'           => array(
					'title'       => esc_html__( 'Subscribe form in two columns', 'exs' ),
					'description' => esc_html__( 'MailChimp subscribe form within a two columns with heading and description.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-subscribe-form' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'subscribe', 'mailchimp', 'columns' ),
				),
				'exs/pricing-plan-columns'     => array(
					'title'       => esc_html__( 'Pricing Plan Columns', 'exs' ),
					'description' => esc_html__( 'Pricing Plan in four columns.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'pricing-plan-columns' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'pricing', 'plan', 'columns' ),
				),
				'exs/fullwidth-2-cols'     => array(
					'title'       => esc_html__( 'Fullwidth columns with image', 'exs' ),
					'description' => esc_html__( 'Fullwidth columns with one half left image.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'fullwidth-2-cols' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'fullwidth', 'image', 'columns' ),
				),
				'exs/fullwidth-media-left'     => array(
					'title'       => esc_html__( 'Fullwidth left image', 'exs' ),
					'description' => esc_html__( 'Fullwidth media with left one half left image.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'fullwidth-media-left' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'fullwidth', 'image', 'media' ),
				),
				'exs/fullwidth-media-right'     => array(
					'title'       => esc_html__( 'Fullwidth right image', 'exs' ),
					'description' => esc_html__( 'Fullwidth media with right one half right image.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'fullwidth-media-right' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'fullwidth', 'image', 'media' ),
				),
				'exs/fullwidth-screen'     => array(
					'title'       => esc_html__( 'Fullwidth full height cover', 'exs' ),
					'description' => esc_html__( 'Fullwidth and full height cover image.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'fullwidth-screen' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'fullwidth', 'image' ),
				),
				'exs/cols-2-paragraphs'     => array(
					'title'       => esc_html__( 'Two columns paragraphs', 'exs' ),
					'description' => esc_html__( 'Two columns simpoe paragraphs.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-paragraphs' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'text' ),
				),
				'exs/cols-2-paragraphs-2'     => array(
					'title'       => esc_html__( 'Two columns paragraphs', 'exs' ),
					'description' => esc_html__( 'Two columns simpoe paragraphs.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-paragraphs', array(
							'verticalAlignment' => 'center',
							'align' => 'full',
							'section' => true,
							'colsHighlight' => true,
							'colsBordered' => true,
							'colsShadow' => true,
							'colsShadowHover' => true,
							'colsRounded' => true,
							'colsPadding' => true,
							'colsSingle' => 'cols-single-sm',
							'gap' => 'gap-50',
							'pt' => 'pt-3',
							'pb' => 'pb-3',
							'background' => 'l m',
							'decorTop' => 'decor decor-t',
							'decorBottom' => 'decor decor-b',
					) ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'text' ),
				),
				'exs/cols-2-paragraphs-3'     => array(
					'title'       => esc_html__( 'Two columns paragraphs', 'exs' ),
					'description' => esc_html__( 'Two columns simple paragraphs.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-paragraphs', array(
						'verticalAlignment' => 'center',
						'align' => '',
						'section' => false,
						'padding' => true,
						'colsHighlight' => true,
						'colsBordered' => true,
						'colsShadow' => true,
						'colsShadowHover' => true,
						'colsRounded' => true,
						'colsPadding' => true,
						'colsSingle' => 'cols-single-sm',
						'gap' => 'gap-30',
						'pt' => 'pt-2',
						'pb' => 'pb-2',
						'background' => 'i m',
						'decorTop' => '',
						'decorBottom' => '',
					) ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'text' ),
				),
				'exs/cols-2-covers'     => array(
					'title'       => esc_html__( 'Two column covers', 'exs' ),
					'description' => esc_html__( 'Two column cover blocks.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-covers', array() ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'cover' ),
				),
			)
		);

		if ( ! empty( $exs_patterns ) ) {
			foreach ( $exs_patterns as $id => $pattern ) {
				register_block_pattern( $id, $pattern );
			}
		}

		//new WordPress 5.9 version patterns
		if ( version_compare( get_bloginfo( 'version' ), '5.9', '<' ) ) {
			return;
		}

		register_block_pattern_category(
			'exs-wp59plus',
			array( 'label' => esc_html__( 'ExS - For WP5.9+', 'exs' ) )
		);
		$exs_patterns = apply_filters(
			'exs_block_patterns59',
			array(
				'exs59/title-with-subtitle'             => array(
					'title'       => esc_html__( 'Title with subtitle', 'exs' ),
					'description' => esc_html__( 'Title heading with sub title and separator below it.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'title-with-subtitle', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'title', 'subtitle', 'heading' ),
				),
				'exs59/title-with-pretitle'             => array(
					'title'       => esc_html__( 'Title with pre title', 'exs' ),
					'description' => esc_html__( 'Title heading with pre title and separator above it.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'title-with-pretitle', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'title', 'subtitle', 'heading', 'pretitle' ),
				),
				'exs59/media-left'             => array(
					'title'       => esc_html__( 'Media with left image', 'exs' ),
					'description' => esc_html__( 'Image with headings progress info', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'media-left', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'media', 'heading', 'progress' ),
				),
				'exs59/media-right'             => array(
					'title'       => esc_html__( 'Media with right image', 'exs' ),
					'description' => esc_html__( 'Image with headings and buttons', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'media-right', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'media', 'heading' ),
				),
				'exs59/media-right-lists'             => array(
					'title'       => esc_html__( 'Media with right image and lists', 'exs' ),
					'description' => esc_html__( 'Image with headings lists', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'media-right-lists', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'media', 'heading', 'list' ),
				),
				'exs59/media-right-side-icons'             => array(
					'title'       => esc_html__( 'Media with right image', 'exs' ),
					'description' => esc_html__( 'Image with headings side-icons', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'media-right-side-icons', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'media', 'heading', 'icons' ),
				),
				'exs59/media-left-service'             => array(
					'title'       => esc_html__( 'Media with image as service or portfolio', 'exs' ),
					'description' => esc_html__( 'Image with heading, text and buttons', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'media-left-service', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'media', 'heading', 'buttons', 'service', 'portfolio' ),
				),
				'exs59/cols-4-numbers'             => array(
					'title'       => esc_html__( 'Columns with numbers', 'exs' ),
					'description' => esc_html__( 'Columns with number, heading and text', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-numbers', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'columns', 'numbers', 'progress' ),
				),
				'exs59/cols-4-icons-simple'             => array(
					'title'       => esc_html__( 'Columns with features', 'exs' ),
					'description' => esc_html__( 'Columns with icon, heading and text', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-icons-simple', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'columns', 'icons', 'features' ),
				),
				'exs59/cols-4-icons-side'             => array(
					'title'       => esc_html__( 'Columns with features', 'exs' ),
					'description' => esc_html__( 'Columns with side icon, heading and text', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-icons-side', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'columns', 'icons', 'features' ),
				),
				'exs59/cols-6-logos'             => array(
					'title'       => esc_html__( 'Columns with logos', 'exs' ),
					'description' => esc_html__( 'Six columns with clients logos', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-6-logos', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'columns', 'logos', 'clients' ),
				),
				'exs59/gallery-6-logos'             => array(
					'title'       => esc_html__( 'Gallery with logos', 'exs' ),
					'description' => esc_html__( 'Gallery with six images with clients logos', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'gallery-6-logos', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'gallery', 'logos', 'clients' ),
				),
				'exs59/cols-2-testimonial'             => array(
					'title'       => esc_html__( 'Columns testimonial', 'exs' ),
					'description' => esc_html__( 'Two columns with clients image and logo', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-testimonial', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'columns', 'testimonial' ),
				),
				'exs59/media-right-testimonial'             => array(
					'title'       => esc_html__( 'Media with featured testimonial', 'exs' ),
					'description' => esc_html__( 'Right media image with big testimonial', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'media-right-testimonial', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'media', 'testimonial', 'testimonials' ),
				),
				'exs59/cols-3-testimonials'             => array(
					'title'       => esc_html__( 'Columns testimonials', 'exs' ),
					'description' => esc_html__( 'Three columns with clients image and logo', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-3-testimonials', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'columns', 'testimonial', 'testimonials' ),
				),
				'exs59/cols-4-testimonials'             => array(
					'title'       => esc_html__( 'Columns testimonials', 'exs' ),
					'description' => esc_html__( 'Four columns with clients image and logo', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-testimonials', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'columns', 'testimonial', 'testimonials' ),
				),
				'exs59/cols-3-faq'             => array(
					'title'       => esc_html__( 'Columns FAQ', 'exs' ),
					'description' => esc_html__( 'Three columns with FAQ', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-3-faq', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'columns', 'faq' ),
				),
				'exs59/cols-3-pricing'             => array(
					'title'       => esc_html__( 'Columns Pricing', 'exs' ),
					'description' => esc_html__( 'Three columns with Pricing plans', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-3-pricing', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'columns', 'pricing', 'plan' ),
				),
				'exs59/cta-title-subtitle'             => array(
					'title'       => esc_html__( 'Call to Action', 'exs' ),
					'description' => esc_html__( 'Title, subtitle and button', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cta-title-subtitle', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'cta', 'call to action', 'title', 'subtitle', 'button' ),
				),
				'exs59/cover-fullheight-left'             => array(
					'title'       => esc_html__( 'Intro section - Left Aligned', 'exs' ),
					'description' => esc_html__( 'Intro section with title, subtitle and buttons', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cover-fullheight-left', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'intro', 'cta', 'call to action', 'title', 'subtitle', 'button' ),
				),
				'exs59/cover-center'             => array(
					'title'       => esc_html__( 'Intro section - Center Aligned', 'exs' ),
					'description' => esc_html__( 'Intro section with title, subtitle and buttons - center aligned', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cover-center', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'intro', 'cta', 'call to action', 'title', 'subtitle', 'button' ),
				),
				'exs59/cols-team-member'             => array(
					'title'       => esc_html__( 'Horizontal team member in columns', 'exs' ),
					'description' => esc_html__( 'Team member with name and description in columns', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-team-member', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'team', 'member', 'columns' ),
				),
				'exs59/cols-events'             => array(
					'title'       => esc_html__( 'Horizontal events in columns', 'exs' ),
					'description' => esc_html__( 'Events list with date and description in columns', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-events', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'events', 'columns' ),
				),
				'exs59/media-contact-form'             => array(
					'title'       => esc_html__( 'Contact form with image', 'exs' ),
					'description' => esc_html__( 'Side image with contact form', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'media-contact-form', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'contact', 'form', 'media' ),
				),
				'exs59/cols-2-dates'             => array(
					'title'       => esc_html__( 'Two columns with dates headings', 'exs' ),
					'description' => esc_html__( 'Columns with dates will be good for your bio or progress', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-dates', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'columns', 'date', 'bio', 'progress' ),
				),
				'exs59/cols-2-dates-about'             => array(
					'title'       => esc_html__( 'Two columns with info and dates', 'exs' ),
					'description' => esc_html__( 'Columns with about info and dates will be good for your bio or progress', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-dates-about', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'columns', 'date', 'bio', 'progress', 'about' ),
				),
				'exs59/cols-2-features'             => array(
					'title'       => esc_html__( 'Two columns with features', 'exs' ),
					'description' => esc_html__( 'Columns with about info and features description', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-features', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'columns', 'features', 'services', 'feature', 'service', 'about' ),
				),
				'exs59/media-left-icons-info'             => array(
					'title'       => esc_html__( 'Media with info icons', 'exs' ),
					'description' => esc_html__( 'Left media image with info icons', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'media-left-icons-info', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'media', 'info', 'icons', 'about' ),
				),
				'exs59/media-left-services'             => array(
					'title'       => esc_html__( 'Media with services icons boxes', 'exs' ),
					'description' => esc_html__( 'Left media image with services or features list with top icons', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'media-left-services', array(), '59/' ),
					'categories'  => array( 'exs-wp59plus' ),
					'keywords'    => array( 'media', 'info', 'icons', 'services', 'about' ),
				),
			)
		);
		if ( ! empty( $exs_patterns ) ) {
			foreach ( $exs_patterns as $id => $pattern ) {
				register_block_pattern( $id, $pattern );
			}
		}
	}
}
add_action( 'after_setup_theme', 'exs_register_theme_block_patterns' );
