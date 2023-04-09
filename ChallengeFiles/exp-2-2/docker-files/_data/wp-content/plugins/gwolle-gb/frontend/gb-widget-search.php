<?php
/*
 * Gwolle-GB Search Widget.
 *
 * @since 3.0.0
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


if (function_exists('register_sidebar') && class_exists('WP_Widget')) {
	class GwolleGB_Widget_search extends WP_Widget {

		/* Constructor */
		public function __construct() {
			$widget_ops = array(
				'classname'   => 'gwolle_gb_search',
				'description' => esc_html__('Search for guestbook entries.', 'gwolle-gb'),
			);
			parent::__construct('gwolle_gb_search', esc_html__('Guestbook Search', 'gwolle-gb'), $widget_ops);
			$this->alt_option_name = 'gwolle_gb_search';
		}

		/** @see WP_Widget::widget */
		public function widget( $args, $instance ) {
			extract($args);

			$default_value = array(
				'title' => esc_html__('Guestbook Search', 'gwolle-gb'),
			);
			$instance      = wp_parse_args( (array) $instance, $default_value );
			$widget_title  = esc_attr($instance['title']);
			$widget_class  = 'gwolle-gb-widget-search';

			// Only show on singular post and when we are on a guestbook.
			if ( ! is_singular() || ! gwolle_gb_post_is_guestbook( get_the_ID() ) ) {
				return;
			}

			// Init
			$widget_html = '';

			$widget_html .= $args['before_widget'];
			$widget_html .= '
				<div class="' . $widget_class . '">';

			if ($widget_title !== FALSE) {
				$widget_html .= $args['before_title'] . apply_filters('widget_title', $widget_title) . $args['after_title'];
			}

			$searchwords = '';
			$is_search = gwolle_gb_is_search();
			if ( is_array($is_search) && ! empty($is_search) ) {
				$searchwords = implode( ' ', $is_search);
			}

			$widget_html .= '
					<form id="gwolle-gb-widget-search" action="#" method="GET" class="' . esc_attr( $widget_class ) . '" role="search" aria-label="' . esc_html__( 'Guestbook', 'gwolle-gb' ) . '">
						<div class="label">
							<label for="gwolle-gb-search-input" class="text-info">' . esc_html__('Search:', 'gwolle-gb') . '</label>
						</div>
						<div class="input">
							<input class="wp-exclude-emoji" value="' . esc_attr( $searchwords ) . '" type="text" name="gwolle-gb-search-input" id="gwolle-gb-search-input" placeholder="' . esc_attr__('Search...', 'gwolle-gb') . '" required="required" />
						</div>
						<div class="gwolle-gb-search-submit">
							<div class="input">
								<input type="submit" class="button btn btn-primary" value="' . esc_attr__('Search', 'gwolle-gb') . '" />
							</div>
						</div>
					</form>';

			$widget_html .= '
				</div>
				' . $args['after_widget'];

			// Add a filter for the widget, so devs can add or remove parts.
			$widget_html = apply_filters( 'gwolle_gb_widget_search', $widget_html);

			echo $widget_html;

			// Load Frontend CSS in Footer, only when it's active.
			wp_enqueue_style('gwolle_gb_frontend_css');
		}

		/** @see WP_Widget::update */
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = wp_strip_all_tags($new_instance['title']);

			return $instance;
		}

		/** @see WP_Widget::form */
		public function form( $instance ) {

			$default_value = array(
				'title' => esc_html__('Guestbook Search', 'gwolle-gb'),
			);
			$instance = wp_parse_args( (array) $instance, $default_value );
			$title    = esc_attr($instance['title']);
			?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>" /><?php esc_html_e('Title:', 'gwolle-gb'); ?></label>
				<br />
				<input type="text" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>" name="<?php echo $this->get_field_name('title'); ?>" />
			</p>

			<?php
		}
	}

	function gwolle_gb_widget_search() {
		register_widget('GwolleGB_Widget_search');
	}
	add_action('widgets_init', 'gwolle_gb_widget_search' );
}
