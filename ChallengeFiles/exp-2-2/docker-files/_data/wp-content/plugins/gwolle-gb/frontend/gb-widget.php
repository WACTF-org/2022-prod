<?php
/*
 * widget.php
 * Gwolle-GB Widget
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


if (function_exists('register_sidebar') && class_exists('WP_Widget')) {
	class GwolleGB_Widget extends WP_Widget {

		/* Constructor */
		public function __construct() {
			$widget_ops = array(
				'classname'   => 'gwolle_gb',
				'description' => esc_html__('Displays the recent guestbook entries.', 'gwolle-gb'),
			);
			parent::__construct('gwolle_gb', esc_html__('Gwolle Guestbook', 'gwolle-gb'), $widget_ops);
			$this->alt_option_name = 'gwolle_gb';
		}

		/** @see WP_Widget::widget */
		public function widget( $args, $instance ) {
			extract($args);

			$default_value = array(
					'title'       => esc_html__('Guestbook', 'gwolle-gb'),
					'num_entries' => 5,
					'best'        => '',
					'no_mod'      => 0,
					'name'        => 1,
					'date'        => 1,
					'slider'      => 0,
					'num_words'   => 10,
					'book_id'     => 0,
					'link_text'   => esc_html__('Visit guestbook', 'gwolle-gb'),
					'postid'      => 0,
				);
			$instance      = wp_parse_args( (array) $instance, $default_value );

			$widget_title  = esc_attr($instance['title']);
			$num_entries   = (int) esc_attr($instance['num_entries']);
			$best          = esc_attr($instance['best']);
			$best          = explode(',', $best);
			$no_mod        = (int) esc_attr($instance['no_mod']);
			if ( $no_mod ) {
				$no_mod = 'true';
			}
			$name          = (int) esc_attr($instance['name']);
			$date          = (int) esc_attr($instance['date']);
			$slider        = (int) esc_attr($instance['slider']);
			$num_words     = (int) esc_attr($instance['num_words']);
			$book_id       = (int) esc_attr($instance['book_id']);
			$link_text     = esc_attr($instance['link_text']);
			$postid        = (int) esc_attr($instance['postid']);

			// Prepare for SSS Slider. Registers Script with WordPress to wp_footer().
			$widget_class = 'gwolle_gb_widget gwolle-gb-widget';
			if ( $slider ) {
				wp_register_script( 'gwolle_gb_widget_sss', GWOLLE_GB_URL . '/frontend/js/sss/sss.js', 'jquery', GWOLLE_GB_VER, true );
				wp_enqueue_script( 'gwolle_gb_widget_sss' );
				$widget_class .= ' gwolle_gb_widget_slider gwolle-gb-widget-slider';
			}
			$widget_class = apply_filters( 'gwolle_gb_widget_list_class', $widget_class );
			$widget_item_class = 'gwolle_gb_widget gwolle-gb-widget';
			$widget_item_class = apply_filters( 'gwolle_gb_widget_item_class', $widget_item_class );

			// Init
			$widget_html = '';

			$widget_html .= $args['before_widget'];
			$widget_html .= '
				<div class="gwolle_gb_widget gwolle-gb-widget">';

			if ($widget_title !== false) {
				$widget_html .= $args['before_title'] . apply_filters('widget_title', $widget_title) . $args['after_title'];
			}

			$link = '';
			if ( (int) $postid > 0 ) {
				$permalink = gwolle_gb_get_permalink( $postid );
				$link = '
									<span class="gb-guestbook-link"><a href="' . esc_attr( $permalink ) . '" title="' . esc_attr__('Click here to get to the guestbook.', 'gwolle-gb') . '"></a></span>
								';
			}

			$widget_html .= '
					<ul class="' . $widget_class . '">';
			$counter = 0;

			// Get the best entries first
			if ( is_array( $best ) && ! empty( $best ) ) {
				foreach ($best as $entry_id) {
					if ( $counter === $num_entries ) {
						break; // we have enough
					}
					$entry = new gwolle_gb_entry();
					$entry_id = (int) $entry_id;
					if ( isset($entry_id) && $entry_id > 0 ) {
						$result = $entry->load( $entry_id );
						if ( ! $result ) {
							// No entry loaded
							continue;
						}

						$widget_html .= $this->widget_single_view( $entry, $instance, $widget_item_class, $link );

						$counter++;
					}
				}
			}

			// Get the latest $num_entries guestbook entries
			if ( $counter !== $num_entries) { // we have enough
				$entries = gwolle_gb_get_entries(
					array(
						'num_entries'   => $num_entries,
						'checked'       => 'checked',
						'trash'         => 'notrash',
						'spam'          => 'nospam',
						'book_id'       => $book_id,
						'no_moderators' => $no_mod,
						)
					);
				if ( is_array( $entries ) && ! empty( $entries ) ) {
					foreach ( $entries as $entry ) {
						if ( $counter === $num_entries) {
							break; // we have enough
						}
						if ( is_array( $best) && in_array( $entry->get_id(), $best ) ) {
							continue; // already listed
						}

						$widget_html .= $this->widget_single_view( $entry, $instance, $widget_item_class, $link );

						$counter++;
					}
				}
			}

			$widget_html .= '
					</ul>';

			// Post the link to the Guestbook.
			if ( (int) $postid > 0 ) {
				$widget_html .= '
					<p class="gwolle_gb_link gwolle-gb-link">
						<a href="' . esc_attr( $permalink ) . '" title="' . esc_attr__('Click here to get to the guestbook.', 'gwolle-gb') . '">' . $link_text . '</a>
					</p>';
			}
			$widget_html .= '
				</div>
				' . $args['after_widget'];

			// Add a filter for the entries, so devs can add or remove parts.
			$widget_html = apply_filters( 'gwolle_gb_widget', $widget_html);

			if ( $counter > 0 ) {
				// Only display widget if there are any entries.
				echo $widget_html;

				// Load Frontend CSS in Footer, only when it's active.
				wp_enqueue_style('gwolle_gb_frontend_css');
			}
		}

		public function widget_single_view( $entry, $instance, $widget_item_class, $link ) {

			$name      = (int) esc_attr($instance['name']);
			$date      = (int) esc_attr($instance['date']);
			$num_words = (int) esc_attr($instance['num_words']);

			$widget_html = '
						<li class="' . $widget_item_class . '">';

			$widget_html .= '
							<article>';

			// Use this filter to just add something
			$widget_html .= apply_filters( 'gwolle_gb_entry_widget_add_before', '', $entry );

			if ( $name ) {
				$widget_html .= '
								<span class="gb-author-name">' . $entry->get_author_name() . '</span>';
			}
			if ( $name && $date ) {
				$widget_html .= '<span class="gb-author-date-separator"> / </span>';
			}
			if ( $date ) {
				$widget_html .= '
								<span class="gb-date">' . date_i18n( get_option('date_format'), $entry->get_datetime() ) . '</span>';
			}
			if ( $name || $date ) {
				$widget_html .= '<br />';
			}

			if ( $num_words > 0 ) {
				$entry_content = gwolle_gb_get_excerpt( gwolle_gb_bbcode_strip( $entry->get_content() ), $num_words );
			} else {
				$entry_content = gwolle_gb_bbcode_strip( $entry->get_content() );
			}
			if ( get_option('gwolle_gb-showSmilies', 'true') === 'true' ) {
				$entry_content = convert_smilies($entry_content);
			}
			$widget_html .= '
								<span class="gb-entry-content">' . $entry_content . $link;

			// Use this filter to just add something
			$widget_html .= apply_filters( 'gwolle_gb_entry_widget_add_content', '', $entry );

			$widget_html .= '</span>';

			// Use this filter to just add something
			$widget_html .= apply_filters( 'gwolle_gb_entry_widget_add_after', '', $entry );

			$widget_html .= '
							</article>';

			$widget_html .= '
						</li>';

			return $widget_html;

		}

		/** @see WP_Widget::update */
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title']       = wp_strip_all_tags($new_instance['title']);
			$instance['num_entries'] = (int) wp_strip_all_tags($new_instance['num_entries']);
			$instance['best']        = wp_strip_all_tags($new_instance['best']);
			if ( isset($new_instance['no_mod']) ) {
				$instance['no_mod']  = (int) $new_instance['no_mod'];
			} else {
				$instance['no_mod']  = 0;
			}
			if ( isset($new_instance['name']) ) {
				$instance['name']    = (int) $new_instance['name'];
			} else {
				$instance['name']    = 0;
			}
			if ( isset($new_instance['date']) ) {
				$instance['date']    = (int) $new_instance['date'];
			} else {
				$instance['date']    = 0;
			}
			if ( isset($new_instance['slider']) ) {
				$instance['slider']  = (int) $new_instance['slider'];
			} else {
				$instance['slider']  = 0;
			}
			$instance['num_words']   = (int) $new_instance['num_words'];
			$instance['book_id']     = (int) $new_instance['book_id'];
			$instance['link_text']   = wp_strip_all_tags($new_instance['link_text']);
			$instance['postid']      = (int) $new_instance['postid'];

			return $instance;
		}

		/** @see WP_Widget::form */
		public function form( $instance ) {

			$default_value = array(
					'title'       => esc_html__('Guestbook', 'gwolle-gb'),
					'num_entries' => 5,
					'best'        => '',
					'no_mod'      => 0,
					'name'        => 1,
					'date'        => 1,
					'slider'      => 0,
					'num_words'   => 10,
					'book_id'     => 0,
					'link_text'   => esc_html__('Visit guestbook', 'gwolle-gb'),
					'postid'      => 0,
				);
			$instance      = wp_parse_args( (array) $instance, $default_value );

			$title         = esc_attr($instance['title']);
			$num_entries   = (int) esc_attr($instance['num_entries']);
			$best          = esc_attr($instance['best']);
			$no_mod        = (int) esc_attr($instance['no_mod']);
			$name          = (int) esc_attr($instance['name']);
			$date          = (int) esc_attr($instance['date']);
			$slider        = (int) esc_attr($instance['slider']);
			$num_words     = (int) esc_attr($instance['num_words']);
			$book_id       = (int) esc_attr($instance['book_id']);
			$link_text     = esc_attr($instance['link_text']);
			$postid        = (int) esc_attr($instance['postid']);
			?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>" /><?php esc_html_e('Title:', 'gwolle-gb'); ?></label>
				<br />
				<input type="text" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>" name="<?php echo $this->get_field_name('title'); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('num_entries'); ?>" /><?php esc_html_e('Number of entries:', 'gwolle-gb'); ?></label>
				<br />
				<select id="<?php echo $this->get_field_id('num_entries'); ?>" name="<?php echo $this->get_field_name('num_entries'); ?>">
					<?php
					for ($i = 1; $i <= 15; $i++) {
						echo '<option value="' . $i . '"';
						if ( $i === $num_entries ) {
							echo ' selected="selected"';
						}
						echo '>' . $i . '</option>';
					}
					?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('best'); ?>" /><?php esc_html_e('Best entries to show:', 'gwolle-gb'); ?></label>
				<br />
				<input type="text" id="<?php echo $this->get_field_id('best'); ?>" value="<?php echo $best; ?>" name="<?php echo $this->get_field_name('best'); ?>" placeholder="<?php esc_attr_e('List of entry_id\'s, comma-separated', 'gwolle-gb'); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('no_mod'); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id('no_mod'); ?>" <?php checked(1, $no_mod ); ?> name="<?php echo $this->get_field_name('no_mod'); ?>" value="1" />
				<?php esc_html_e('Do not show admin entries.', 'gwolle-gb'); ?></label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('name'); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id('name'); ?>" <?php checked(1, $name ); ?> name="<?php echo $this->get_field_name('name'); ?>" value="1" />
				<?php esc_html_e('Show name of author.', 'gwolle-gb'); ?></label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('date'); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id('date'); ?>" <?php checked(1, $date ); ?> name="<?php echo $this->get_field_name('date'); ?>" value="1" />
				<?php esc_html_e('Show date of entry.', 'gwolle-gb'); ?></label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('slider'); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id('slider'); ?>" <?php checked(1, $slider ); ?> name="<?php echo $this->get_field_name('slider'); ?>" value="1" />
				<?php esc_html_e('Use Slider View.', 'gwolle-gb'); ?></label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('num_words'); ?>" /><?php esc_html_e('Number of words for each entry:', 'gwolle-gb'); ?></label>
				<br />
				<select id="<?php echo $this->get_field_id('num_words'); ?>" name="<?php echo $this->get_field_name('num_words'); ?>">
					<?php
					$presets = array( 10, 30, 40, 50, 60, 70, 80, 90, 100 );
					echo '<option value="0"';
					if ( 0 === $num_words ) {
						echo ' selected="selected"';
					}
					/* translators: Number of words to display */
					echo '>' . esc_html__('Unlimited Words', 'gwolle-gb') . '</option>
					';

					foreach ( $presets as $preset ) {
						echo '<option value="' . (int) $preset . '"';
						if ( $preset === $num_words ) {
							echo ' selected="selected"';
						}
						/* translators: Number of words to display */
						echo '>' . $preset . ' ' . esc_html__('Words', 'gwolle-gb') . '</option>
						';
					} ?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('book_id'); ?>" /><?php esc_html_e('Book ID:', 'gwolle-gb'); ?></label>
				<br />
				<input type="text" id="<?php echo $this->get_field_id('book_id'); ?>" value="<?php echo $book_id; ?>" name="<?php echo $this->get_field_name('book_id'); ?>" /><br />
				<?php esc_html_e('0 means all.', 'gwolle-gb'); ?>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('link_text'); ?>" /><?php esc_html_e('Link text:', 'gwolle-gb'); ?></label>
				<br />
				<input type="text" id="<?php echo $this->get_field_id('link_text'); ?>" value="<?php echo $link_text; ?>" name="<?php echo $this->get_field_name('link_text'); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('postid'); ?>"><?php esc_html_e('Select the page of the guestbook:', 'gwolle-gb'); ?></label>
				<br />
				<select id="<?php echo $this->get_field_id('postid'); ?>" name="<?php echo $this->get_field_name('postid'); ?>">
					<option value="0"><?php esc_html_e('Select page', 'gwolle-gb'); ?></option>
					<?php
					$args = array(
						'post_type'           => 'any',
						'orderby'             => 'title',
						'order'               => 'ASC',
						'posts_per_page'      => 500,
						'meta_query'          => array(
							array(
								'key'   => 'gwolle_gb_read',
								'value' => 'true',
							),
						),
						'update_post_term_cache' => false,
						'update_post_meta_cache' => false,
					);

					$sel_query = new WP_Query( $args );
					if ( $sel_query->have_posts() ) {
						while ( $sel_query->have_posts() ) {
							$sel_query->the_post();
							$selected = false;
							if ( get_the_ID() === $postid ) {
								$selected = true;
							}
							echo '<option value="' . get_the_ID() . '"'
							. selected( $selected )
							. '>' . get_the_title() . '</option>';
						}
					}
					wp_reset_postdata(); ?>
				</select>
			</p>
			<?php
		}
	}

	function gwolle_gb_widget() {
		register_widget('GwolleGB_Widget');
	}
	add_action('widgets_init', 'gwolle_gb_widget' );
}
