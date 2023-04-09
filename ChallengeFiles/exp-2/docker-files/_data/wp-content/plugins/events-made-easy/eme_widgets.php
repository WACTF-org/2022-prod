<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// enable shortcodes in widgets, if wanted
if (get_option('eme_shortcodes_in_widgets')) {
   add_filter('widget_text', 'do_shortcode', 11);
}

class WP_Widget_eme_list extends WP_Widget {

   function __construct() {
      parent::__construct(
            'eme_list', // Base ID
            __('Events Made Easy List of events', 'events-made-easy'), // Name
            array( 'description' => __( 'Events Made Easy List of events', 'events-made-easy'), ) // Args
            );
   }

   public function widget( $args, $instance ) {
      //extract($args);
      //$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Events','eme' ) : $instance['title'], $instance, $this->id_base);
      //$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
      $title = apply_filters('widget_title', $instance['title']);
      $limit = isset( $instance['limit'] ) ? intval($instance['limit']) : 5;
      $scope = empty( $instance['scope'] ) ? 'future' : urlencode($instance['scope']);
      $showperiod = empty( $instance['showperiod'] ) ? '' : $instance['showperiod'];
      $show_ongoing = isset( $instance['show_ongoing'] ) ? $instance['show_ongoing'] : true;
      $order = empty( $instance['order'] ) ? 'ASC' : $instance['order'];
      $header = empty( $instance['header'] ) ? '<ul>' : $instance['header'];
      $footer = empty( $instance['footer'] ) ? '</ul>' : $instance['footer'];
      $category = empty( $instance['category'] ) ? '' : $instance['category'];
      $notcategory = empty( $instance['notcategory'] ) ? '' : $instance['notcategory'];
      $recurrence_only_once = empty( $instance['recurrence_only_once'] ) ? false : $instance['recurrence_only_once'];
      if (eme_is_empty_string( $instance['format'] ) && empty( $instance['format_tpl'] )) {
         $format = DEFAULT_WIDGET_EVENT_LIST_ITEM_FORMAT;
      } elseif (eme_is_empty_string( $instance['format'] )) {
         $format = eme_get_template_format($instance['format_tpl']);
      } else {
         $format = $instance['format'];
      }
      $format=urlencode($format);
      $format_tpl = isset($instance['format_tpl']) ? intval($instance['format_tpl']) : 0;

      if ($instance['authorid']==-1 ) {
         $author='';
      } else {
         $authinfo=get_userdata($instance['authorid']);
         $author=$authinfo->user_login;
      }
      echo $args['before_widget'];
      if ( $title)
         echo $args['before_title'] . $title . $args['after_title'];

      if (is_array($category))
         $category=implode(',',$category);
      if (is_array($notcategory))
         $notcategory=implode('+',$notcategory);

      $events_list = eme_get_events_list ("limit=$limit&scope=$scope&order=$order&format=$format&category=$category&showperiod=$showperiod&author=$author&show_ongoing=$show_ongoing&show_recurrent_events_once=$recurrence_only_once&notcategory=$notcategory&template_id=$format_tpl");
      if (strstr($events_list,"events-no-events"))
         echo $events_list;
      else
         echo $header.$events_list.$footer;
      echo $args['after_widget'];
   }

   public function update( $new_instance, $old_instance ) {
      // before the merge, let's set the values of those elements that are checkboxes or multiselects (not returned in the POST if not selected)
      if (!isset($new_instance['recurrence_only_once']))
         $new_instance['recurrence_only_once']=false;
      if (!isset($new_instance['show_ongoing']))
         $new_instance['show_ongoing']=false;
      if (!isset($new_instance['category']))
         $new_instance['category']="";
      if (!isset($new_instance['notcategory']))
         $new_instance['notcategory']="";

      $instance = array_merge($old_instance,$new_instance);
      $instance['title'] = strip_tags($instance['title']);
      $instance['limit'] = intval($instance['limit']);
      if ( !in_array( $instance['showperiod'], array( 'daily', 'monthly', 'yearly' ) ) ) {
         $instance['showperiod'] = '';
      }
      if ( !in_array( $instance['order'], array( 'ASC', 'DESC' ) ) ) {
         $instance['order'] = 'ASC';
      }
      return $instance;
   }

   public function form( $instance ) {
      //Defaults
      $instance = wp_parse_args( (array) $instance, array( 'limit' => 5, 'scope' => 'future', 'order' => 'ASC', 'format' => '', 'format_tpl'=>0, 'authorid' => '', 'show_ongoing'=> 1 ) );
      $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
      $format_tpl = isset($instance['format_tpl']) ? intval($instance['format_tpl']) : 0;
      $limit = isset( $instance['limit'] ) ? intval($instance['limit']) : 5;
      $scope = empty( $instance['scope'] ) ? 'future' : eme_esc_html($instance['scope']);
      $showperiod = empty( $instance['showperiod'] ) ? '' : eme_esc_html($instance['showperiod']);
      if ( isset( $instance['show_ongoing'] ) && ( $instance['show_ongoing'] != false ))
         $show_ongoing = true;
      else
         $show_ongoing = false;
      $order = empty( $instance['order'] ) ? 'ASC' : eme_esc_html($instance['order']);
      $header = empty( $instance['header'] ) ? '<ul>' : eme_esc_html($instance['header']);
      $footer = empty( $instance['footer'] ) ? '</ul>' : eme_esc_html($instance['footer']);
      $category = empty( $instance['category'] ) ? '' : eme_esc_html($instance['category']);
      $notcategory = empty( $instance['notcategory'] ) ? '' : eme_esc_html($instance['notcategory']);
      $recurrence_only_once = empty( $instance['recurrence_only_once'] ) ? '' : eme_esc_html($instance['recurrence_only_once']);
      $authorid = empty( $instance['authorid'] ) ? '' : eme_esc_html($instance['authorid']);
      $categories = eme_get_categories();
      $option_categories=array();
      foreach ($categories as $cat) {
         $id=$cat['category_id'];
         $option_categories[$id]=$cat['category_name'];
      }
      if (empty( $instance['format_tpl']) && eme_is_empty_string( $instance['format'] )) {
         $format = eme_esc_html(DEFAULT_WIDGET_EVENT_LIST_ITEM_FORMAT);
      } elseif (empty( $instance['format_tpl']) && !eme_is_empty_string( $instance['format'] )) {
         $format = eme_esc_html($instance['format']);
      } else {
         $format = "";
      }

      $templates_array=eme_get_templates_array_by_id('event');
?>
  <p>
   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'events-made-easy'); ?></label>
   <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Number of events','events-made-easy'); ?>: </label>
    <input type="text" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" value="<?php echo $limit;?>" />
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('scope'); ?>"><?php _e('Scope of the events','events-made-easy'); ?><br /><?php _e('(See the doc for &#91;eme_events] for all possible values)', 'events-made-easy'); ?>:</label><br />
    <input type="text" id="<?php echo $this->get_field_id('scope'); ?>" name="<?php echo $this->get_field_name('scope'); ?>" value="<?php echo $scope;?>" />
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('showperiod'); ?>"><?php _e('Show events per period','events-made-easy'); ?>:</label><br />
   <select id="<?php echo $this->get_field_id('showperiod'); ?>" name="<?php echo $this->get_field_name('showperiod'); ?>">
         <option value="" <?php selected( $showperiod, '' ); ?>><?php _e('Select...','events-made-easy'); ?></option>
         <option value="daily" <?php selected( $showperiod, 'daily' ); ?>><?php _e('Daily','events-made-easy'); ?></option>
         <option value="monthly" <?php selected( $showperiod, 'monthly' ); ?>><?php _e('Monthly','events-made-easy'); ?></option>
         <option value="yearly" <?php selected( $showperiod, 'yearly' ); ?>><?php _e('Yearly','events-made-easy'); ?></option>
    </select>
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order of the events','events-made-easy'); ?>:</label><br />
    <select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
         <option value="ASC" <?php selected( $order, 'ASC' ); ?>><?php _e('Ascendant','events-made-easy'); ?></option>
         <option value="DESC" <?php selected( $order, 'DESC' ); ?>><?php _e('Descendant','events-made-easy'); ?></option>
    </select>
  </p>
<?php
  if(get_option('eme_categories_enabled')) {
?>
  <p>
    <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category','events-made-easy'); ?>:</label><br />
    <select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>[]" multiple="multiple">
      <?php
      eme_option_items($option_categories,$category);
      ?>
    </select>
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('notcategory'); ?>"><?php _e('Exclude Category','events-made-easy'); ?>:</label><br />
    <select id="<?php echo $this->get_field_id('notcategory'); ?>" name="<?php echo $this->get_field_name('notcategory'); ?>[]" multiple="multiple">
      <?php
      eme_option_items($option_categories,$notcategory);
      ?>
    </select>
  </p>
<?php
  }
?>
  <p>
    <label for="<?php echo $this->get_field_id('show_ongoing'); ?>"><?php _e('Show Ongoing Events?', 'events-made-easy'); ?>:</label>
    <input type="checkbox" id="<?php echo $this->get_field_id('show_ongoing'); ?>" name="<?php echo $this->get_field_name('show_ongoing'); ?>" value="1" <?php echo ($show_ongoing) ? 'checked="checked"':'' ;?> />
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('recurrence_only_once'); ?>"><?php _e('Show Recurrent Events Only Once?', 'events-made-easy'); ?>:</label>
    <input type="checkbox" id="<?php echo $this->get_field_id('recurrence_only_once'); ?>" name="<?php echo $this->get_field_name('recurrence_only_once'); ?>" value="1" <?php echo ($recurrence_only_once) ? 'checked="checked"':'' ;?> />
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('authorid'); ?>"><?php _e('Author','events-made-easy'); ?>:</label><br />
<?php
wp_dropdown_users ( array ('id' => $this->get_field_id('authorid'), 'name' => $this->get_field_name('authorid'), 'show_option_none' => __ ( "Select...", 'events-made-easy'), 'selected' => $authorid ) );
?>
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('header'); ?>"><?php _e('List header format<br />(if empty &lt;ul&gt; is used)','events-made-easy'); ?>: </label>
    <input type="text" id="<?php echo $this->get_field_id('header'); ?>" name="<?php echo $this->get_field_name('header'); ?>" value="<?php echo $header;?>" />
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('format_tpl'); ?>"><?php _e('List item format','events-made-easy'); ?>:</label>
    <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($format_tpl,$this->get_field_name('format_tpl'),$templates_array); ?>
  </p> 
  <p>
    <label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy'); ?>:</label>
    <textarea id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" rows="5" cols="24"><?php echo $format;?></textarea>
  </p> 
  <p>
    <label for="<?php echo $this->get_field_id('footer'); ?>"><?php _e('List footer format<br />(if empty &lt;/ul&gt; is used)','events-made-easy'); ?>: </label>
    <input type="text" id="<?php echo $this->get_field_id('footer'); ?>" name="<?php echo $this->get_field_name('footer'); ?>" value="<?php echo $footer;?>" />
  </p>
<?php
    }
}     

class WP_Widget_eme_calendar extends WP_Widget {

   function __construct() {
      parent::__construct(
            'eme_calendar', // Base ID
            __('Events Made Easy Calendar', 'events-made-easy'), // Name
            array( 'description' => __( 'Events Made Easy Calendar', 'events-made-easy'), ) // Args
            );
   }

   public function widget( $args, $instance ) {
      global $wp_query, $eme_timezone;
      //extract($args);
      //$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Calendar','eme' ) : $instance['title'], $instance, $this->id_base);
      //$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
      if (!isset($instance['title'])) $instance['title']=__('Calendar','events-made-easy');
      if (!isset($instance['authorid'])) $instance['authorid']=-1;
      $title = apply_filters('widget_title', $instance['title']);
      $long_events = isset( $instance['long_events'] ) ? $instance['long_events'] : false;
      $category = empty( $instance['category'] ) ? '' : $instance['category'];
      $notcategory = empty( $instance['notcategory'] ) ? '' : $instance['notcategory'];
      $holiday_id = empty( $instance['holiday_id'] ) ? 0 : $instance['holiday_id'];
      if ($instance['authorid']==-1 ) {
         $author='';
      } else {
         $authinfo=get_userdata($instance['authorid']);
         $author=$authinfo->user_login;
      }

      if (is_array($category))
         $category=implode(',',$category);
      if (is_array($notcategory))
         $notcategory=implode('+',$notcategory);

      $options=array();
      $options['title'] = $title;
      $options['long_events'] = $long_events;
      $options['category'] = $category;
      $options['notcategory'] = $notcategory;
      // the month shown depends on the calendar day clicked
      // make sure it is a valid date though ...
      if (get_query_var('calendar_day') && eme_is_date(get_query_var('calendar_day'))) {
          $eme_date_obj=new ExpressiveDate(get_query_var('calendar_day'),$eme_timezone);
      } else {
          $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
      }
      $options['month'] = $eme_date_obj->format('m');
      $options['year'] = $eme_date_obj->format('Y');
      $options['author'] = $author;
      $options['holiday_id'] = $holiday_id;

      echo $args['before_widget'];
      if ( $title)
         echo $args['before_title'] . $title . $args['after_title'];
      echo eme_get_calendar($options);
      echo $args['after_widget'];
   }
   
   public function update( $new_instance, $old_instance ) {
      // before the merge, let's set the values of those elements that are checkboxes or multiselects (not returned in the POST if not selected)
      if (!isset($new_instance['long_events']))
         $new_instance['long_events']=false;
      if (!isset($new_instance['category']))
         $new_instance['category']="";
      if (!isset($new_instance['notcategory']))
         $new_instance['notcategory']="";
      $instance = array_merge($old_instance,$new_instance);
      $instance['title'] = strip_tags($instance['title']);
      return $instance;
   }

   public function form( $instance ) {
      //Defaults
      $instance = wp_parse_args( (array) $instance, array( 'long_events' => 0 ) );
      $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
      $category = empty( $instance['category'] ) ? '' : eme_esc_html($instance['category']);
      $notcategory = empty( $instance['notcategory'] ) ? '' : eme_esc_html($instance['notcategory']);
      $long_events = isset( $instance['long_events'] ) ? eme_esc_html($instance['long_events']) : false;
      $authorid = isset( $instance['authorid'] ) ? eme_esc_html($instance['authorid']) : '';
      $holiday_id = isset( $instance['holiday_id'] ) ? intval($instance['holiday_id']) : 0;
      $categories = eme_get_categories();
      $holidays_array_by_id=eme_get_holidays_array_by_id();
      $option_categories=array();
      foreach ($categories as $cat) {
         $id=$cat['category_id'];
         $option_categories[$id]=$cat['category_name'];
      }
?>
  <p>
   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'events-made-easy'); ?></label>
   <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </p>      
  <p>
    <label for="<?php echo $this->get_field_id('long_events'); ?>"><?php _e('Show Long Events?', 'events-made-easy'); ?>:</label>
    <input type="checkbox" id="<?php echo $this->get_field_id('long_events'); ?>" name="<?php echo $this->get_field_name('long_events'); ?>" value="1" <?php echo ($long_events) ? 'checked="checked"':'' ;?> />
  </p>
  <?php
      if(get_option('eme_categories_enabled')) {
  ?>
  <p>
    <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category','events-made-easy'); ?>:</label><br />
   <select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>[]" multiple="multiple">
      <?php
      eme_option_items($option_categories,$category);
      ?>
   </select>
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('notcategory'); ?>"><?php _e('Exclude Category','events-made-easy'); ?>:</label><br />
   <select id="<?php echo $this->get_field_id('notcategory'); ?>" name="<?php echo $this->get_field_name('notcategory'); ?>[]" multiple="multiple">
      <?php
      eme_option_items($option_categories,$notcategory);
      ?>
   </select>
  </p>
<?php
      }
      if (!empty($holidays_array_by_id)) {
?>
    <label for="<?php echo $this->get_field_id('holiday_id'); ?>"><?php _e('Holidays','events-made-easy'); ?>:</label><br />
   <select id="<?php echo $this->get_field_id('holiday_id'); ?>" name="<?php echo $this->get_field_name('holiday_id'); ?>">
<?php
      eme_option_items($holidays_array_by_id,$holiday_id);
      }
?>
   </select>
  <p>
    <label for="<?php echo $this->get_field_id('authorid'); ?>"><?php _e('Author','events-made-easy'); ?>:</label><br />
<?php
wp_dropdown_users ( array ('id' => $this->get_field_id('authorid'), 'name' => $this->get_field_name('authorid'), 'show_option_none' => __ ( "Select...", 'events-made-easy'), 'selected' => $authorid ) );
?>
  </p>
<?php
   } 
}

?>
