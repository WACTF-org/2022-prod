<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eme_new_category() {
   $category = array(
      'category_name' => '',
      'category_slug' => '',
      'category_prefix' => '',
      'description' => ''
   );
   return $category;
}

function eme_categories_page() {      
   global $wpdb;
   
   if (!current_user_can( get_option('eme_cap_categories')) && (isset($_GET['eme_admin_action']) || isset($_POST['eme_admin_action']))) {
      $message = __('You have no right to update categories!','events-made-easy');
      eme_categories_table_layout($message);
      return;
   }
   
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == "edit_category") { 
      check_admin_referer('eme_admin','eme_admin_nonce');
      // edit category  
      eme_categories_edit_layout();
      return;
   }

   if (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action'] == "add_category") {
      // edit template  
      check_admin_referer('eme_admin','eme_admin_nonce');
      eme_categories_edit_layout();
      return;
   }

   // Insert/Update/Delete Record
   $categories_table = $wpdb->prefix.CATEGORIES_TBNAME;
   $message = '';
   if (isset($_POST['eme_admin_action'])) {
      check_admin_referer('eme_admin','eme_admin_nonce');
      if ($_POST['eme_admin_action'] == "do_editcategory" ) {
         // category update required  
         $category = array();
         $category['category_name'] = eme_sanitize_request($_POST['category_name']);
         $category['description'] = eme_kses($_POST['description']);
	 if (!empty($_POST['category_prefix']))
		 $category['category_prefix'] = eme_sanitize_request($_POST['category_prefix']);
	 if (!empty($_POST['category_slug']))
		 $category['category_slug'] = eme_permalink_convert_noslash(eme_sanitize_request($_POST['category_slug']));
	 else
		 $category['category_slug'] = eme_permalink_convert_noslash($category['category_name']);
	 if (isset($_POST['category_id']) && intval($_POST['category_id'])>0) {
		 $validation_result = $wpdb->update( $categories_table, $category, array('category_id' => intval($_POST['category_id'])) );
		 if ($validation_result !== false) {
			 $message = __("Successfully edited the category", 'events-made-easy');
		 } else {
			 $message = __("There was a problem editing your category, please try again.",'events-made-easy');
		 }
	 } else {
		 $validation_result = $wpdb->insert($categories_table, $category);
		 if ($validation_result !== false) {
			 $message = __("Successfully added the category", 'events-made-easy');
		 } else {
			 $message = __("There was a problem adding your category, please try again.",'events-made-easy');
		 }
	 }
      } elseif ($_POST['eme_admin_action'] == "do_deletecategory" && isset($_POST['categories'])) {
         // Delete category or multiple
         $categories = $_POST['categories'];
         if (!empty($categories) && eme_array_integers($categories)) {
               $validation_result = $wpdb->query( "DELETE FROM $categories_table WHERE category_id IN ( ". implode(",", $categories) .")" );
               if ($validation_result !== false)
                  $message = __("Successfully deleted the selected categories.",'events-made-easy');
               else
                  $message = __("There was a problem deleting the selected categories, please try again.",'events-made-easy');
         } else {
            $message = __("Couldn't delete the categories. Incorrect category IDs supplied. Please try again.",'events-made-easy');
         }
      }
   }
   eme_categories_table_layout($message);
} 

function eme_categories_table_layout($message = "") {
   global $plugin_page;
   $categories = eme_get_categories();
   $destination = admin_url("admin.php?page=$plugin_page"); 
   $nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
   $table = "
      <div class='wrap nosubsub'>
      <div id='poststuff'>
         <div id='icon-edit' class='icon32'>
            <br />
         </div>
         <h1>".__('Manage categories', 'events-made-easy')."</h1>\n ";   
         
         if($message != "") {
            $table .= "
            <div id='message' class='updated notice notice-success is-dismissible'>
               <p>$message</p>
            </div>";
         }

	 $table.="
   <div class='wrap'>
         <form id='categories-new' method='post' action='$destination'>
            $nonce_field
            <input type='hidden' name='eme_admin_action' value='add_category' />
            <input type='submit' class='button-primary' name='submit' value='".__('Add category', 'events-made-easy')."' />
         </form>
   </div>
<br /><br />
         
                <form id='categories-form' method='post' action='$destination'>
                  <input type='hidden' name='eme_admin_action' value='do_deletecategory' />";
                  $table .= $nonce_field;
                  if (count($categories)>0) {
                     $table .= "<table class='widefat'>
                        <thead>
                           <tr>
                              <th class='manage-column column-cb check-column' scope='col'><input type='checkbox' class='select-all' value='1' /></th>
                              <th>".__('ID', 'events-made-easy')."</th>
                              <th>".__('Name', 'events-made-easy')."</th>
                           </tr>
                        </thead>
                        <tfoot>
                           <tr>
                              <td class='manage-column column-cb check-column' scope='col'><input type='checkbox' class='select-all' value='1' /></td>
                              <td>".__('ID', 'events-made-easy')."</td>
                              <td>".__('Name', 'events-made-easy')."</td>
                           </tr>
                        </tfoot>
                        <tbody>";
                     foreach ($categories as $this_category) {
			if (empty($this_category['category_name'])) $this_category['category_name']=__('No name','events-made-easy');
                        $table .= "    
                           <tr>
                           <td><input type='checkbox' class ='row-selector' value='".$this_category['category_id']."' name='categories[]' /></td>
                           <td><a href='".wp_nonce_url(admin_url("admin.php?page=eme-categories&amp;eme_admin_action=edit_category&amp;category_id=".$this_category['category_id']),'eme_admin','eme_admin_nonce')."'>".$this_category['category_id']."</a></td>
                           <td><a href='".wp_nonce_url(admin_url("admin.php?page=eme-categories&amp;eme_admin_action=edit_category&amp;category_id=".$this_category['category_id']),'eme_admin','eme_admin_nonce')."'>".eme_trans_esc_html($this_category['category_name'])."</a></td>
                           </tr>
                        ";
                     }
                     $delete_text=esc_html__("Are you sure you want to delete these categories?",'events-made-easy');
                     $delete_button_text=esc_html__("Delete",'events-made-easy');
                     $table .= <<<EOT
                        </tbody>
                     </table>
   
                     <div class='tablenav'>
                        <div class='alignleft actions'>
                        <input class='button-primary action' type='submit' name='doaction' value='$delete_button_text' onclick="return areyousure('$delete_text');" />
                        <br class='clear'/>
                        </div>
                        <br class='clear'/>
                     </div>
EOT;
                  } else {
                        $table .= "<p>".__('No categories defined.', 'events-made-easy');
                  }
                   $table .= "
                  </form>
         </div>
   </div>";
   echo $table;  
}

function eme_categories_edit_layout($message = "") {
   global $plugin_page;

   if (!empty($_GET['category_id'])) {
           $category_id = intval($_GET['category_id']);
	   $category = eme_get_category($category_id);
           $h1_string=__('Edit category', 'events-made-easy');
           $action_string=__('Update category', 'events-made-easy');
	   $permalink_string=__ ('Permalink: ', 'events-made-easy');
	   $action="edit";
   } else {
           $category_id=0;
           $category = eme_new_category();
           $h1_string=__('Create category', 'events-made-easy');
           $action_string=__('Add category', 'events-made-easy');
	   $permalink_string=__ ('Permalink prefix: ', 'events-made-easy');
	   $action="add";
   }

   $nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
?>
   <div class='wrap'>
      <div id='icon-edit' class='icon32'>
         <br />
      </div>
         
      <h1><?php echo $h1_string; ?></h1>   
      
      <?php if($message != "") { ?>
      <div id='message' class='updated notice notice-success is-dismissible'>
      <p><?php echo $message; ?></p>
      </div>
      <?php } ?>
    
      <div id='ajax-response'></div>
      <form name='edit_category' id='edit_category' method='post' action='<?php echo admin_url("admin.php?page=$plugin_page"); ?>'>
      <input type='hidden' name='eme_admin_action' value='do_editcategory' />
      <input type='hidden' name='category_id' value='<?php echo $category_id; ?>' />
      <?php echo $nonce_field; ?>
      <table class='form-table'>
            <tr class='form-field'>
	    <th scope='row' style='vertical-align:top'><label for='category_name'><? _e('Category name', 'events-made-easy'); ?></label></th>
	    <td><input name='category_name' id='category_name' type='text' required='required' value='<?php esc_html_e($category['category_name']); ?>' size='40' /><br />
		 <?php _e('The name of the category', 'events-made-easy'); ?></td>
            </tr>
            <tr class='form-field'>
	    <th scope='row' style='vertical-align:top'><label for='description'><?php _e('Category description', 'events-made-easy'); ?></label></th>
	       <td><div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
                  <!-- we need content for qtranslate as ID -->
                  <?php    $eme_editor_settings = eme_get_editor_settings();
                           wp_editor($category['description'],"description",$eme_editor_settings);
                  ?>
		 <br /><?php _e('The description of the category', 'events-made-easy'); ?>
               </div>
            </td>
            </tr>
            <tr>
	       <th scope='row' style='vertical-align:top'><label for='slug'><?php echo $permalink_string; ?></label></th>
               <td>
               <?php
               echo trailingslashit(home_url());
               $categories_prefixes = get_option('eme_permalink_categories_prefix','');
	       if (empty($categories_prefixes)) {
		       $extra_prefix="cat/";
		       $categories_prefixes = get_option('eme_permalink_events_prefix','events');
	       } else {
		       $extra_prefix="";
	       }
               if (preg_match('/,/',$categories_prefixes)) {
                       $categories_prefixes=explode(',',$categories_prefixes);
                       $categories_prefixes_arr=array();
                       foreach ($categories_prefixes as $categories_prefix) {
                               $categories_prefixes_arr[$categories_prefix]=eme_permalink_convert($categories_prefix);
                       }
                       $prefix = $category['category_prefix'] ? $category['category_prefix'] : '';
                       echo eme_ui_select($prefix,'category_prefix',$categories_prefixes_arr);
               } else {
                       echo eme_permalink_convert($categories_prefixes);
               }
	       echo $extra_prefix;
	       if ($action=="edit") {
		       $slug = $category['category_slug'] ? $category['category_slug'] : $category['category_name'];
		       $slug = eme_permalink_convert_noslash($slug);
		       ?>
	               <input type="text" id="slug" name="category_slug" value="<?php echo $slug; ?>" /><?php echo user_trailingslashit(""); ?>
                       <?php
	       }
               ?>
               </td>
               </tr>
         </table>
      <p class='submit'><input type='submit' class='button-primary' name='submit' value='<?php echo $action_string; ?>' /></p>
      </form>
   </div>
<?php
}

function eme_get_cached_categories() {
	$cats = wp_cache_get("eme_all_cats");
	if ($cats === false) {
		$cats=eme_get_categories();
		wp_cache_add("eme_all_cats", $cats, '', 60);
	}
	return $cats;
}

function eme_get_categories($eventful=false,$scope="future",$extra_conditions=""){
   global $wpdb;
   $categories_table = $wpdb->prefix.CATEGORIES_TBNAME; 
   $categories = array();
   $order_by = " ORDER BY category_name ASC";
   if ($eventful) {
      $events = eme_get_events(0, $scope, "ASC");
      if ($events) {
         foreach ($events as $event) {
            if (!empty($event['event_category_ids'])) {
               $event_cats=explode(",",$event['event_category_ids']);
               if (!empty($event_cats)) {
                  foreach ($event_cats as $category_id) {
                     $categories[$category_id]=$category_id;
                  }
               }
            }
         }
      }
      if (!empty($categories) && eme_array_integers($categories)) {
         $event_cats=join(",",$categories);
         if ($extra_conditions !="")
            $extra_conditions = " AND ($extra_conditions)";
         $result = $wpdb->get_results("SELECT * FROM $categories_table WHERE category_id IN ($event_cats) $extra_conditions $order_by", ARRAY_A);
      }
   } else {
      if ($extra_conditions !="")
         $extra_conditions = " WHERE ($extra_conditions)";
      $result = $wpdb->get_results("SELECT * FROM $categories_table $extra_conditions $order_by", ARRAY_A);
   }
   if (has_filter('eme_categories_filter')) $result=apply_filters('eme_categories_filter',$result); 
   return $result;
}

function eme_get_categories_filtered($category_ids,$categories) {
	$cat_id_arr=explode(',',$category_ids);
	$new_arr=array();
	foreach ($categories as $cat) {
		if (in_array($cat['category_id'],$cat_id_arr))
			$new_arr[]=$cat;
	}
	return $new_arr;
}

function eme_get_category($category_id) { 
   global $wpdb;
   $categories_table = $wpdb->prefix.CATEGORIES_TBNAME; 
   $sql = $wpdb->prepare("SELECT * FROM $categories_table WHERE category_id = %d",$category_id);
   return $wpdb->get_row($sql, ARRAY_A);
}

function eme_get_event_category_names($event_id,$extra_conditions="", $order_by="") { 
   global $wpdb;
   $event_table = $wpdb->prefix.EVENTS_TBNAME; 
   $categories_table = $wpdb->prefix.CATEGORIES_TBNAME; 
   if ($extra_conditions !="")
      $extra_conditions = " AND ($extra_conditions)";
   if ($order_by !="")
      $order_by = " ORDER BY $order_by";
   $sql = $wpdb->prepare("SELECT category_name FROM $categories_table, $event_table where event_id = %d AND FIND_IN_SET(category_id,event_category_ids) $extra_conditions $order_by",$event_id);
   return $wpdb->get_col($sql);
}

function eme_get_event_category_descriptions($event_id,$extra_conditions="", $order_by="") { 
   global $wpdb;
   $event_table = $wpdb->prefix.EVENTS_TBNAME; 
   $categories_table = $wpdb->prefix.CATEGORIES_TBNAME; 
   if ($extra_conditions !="")
      $extra_conditions = " AND ($extra_conditions)";
   if ($order_by !="")
      $order_by = " ORDER BY $order_by";
   $sql = $wpdb->prepare("SELECT description FROM $categories_table, $event_table where event_id = %d AND FIND_IN_SET(category_id,event_category_ids) $extra_conditions $order_by",$event_id);
   return $wpdb->get_col($sql);
}

function eme_get_event_categories($event_id,$extra_conditions="", $order_by="") { 
   global $wpdb;
   $event_table = $wpdb->prefix.EVENTS_TBNAME; 
   $categories_table = $wpdb->prefix.CATEGORIES_TBNAME; 
   if ($extra_conditions !="")
      $extra_conditions = " AND ($extra_conditions)";
   if ($order_by !="")
      $order_by = " ORDER BY $order_by";
   $sql = $wpdb->prepare("SELECT $categories_table.* FROM $categories_table, $event_table where event_id = %d AND FIND_IN_SET(category_id,event_category_ids) $extra_conditions $order_by",$event_id);
   return $wpdb->get_results($sql,ARRAY_A);
}

function eme_get_category_eventids($category_id,$future_only=1) {
   global $eme_timezone;
   // similar to eme_get_recurrence_eventids
   global $wpdb;
   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   $extra_condition="";
   if ($future_only) {
      $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
      $today = $eme_date_obj->getDateTime();
      $extra_condition="AND event_start > '$today'";
   }
   $cat_ids=explode(',',$category_id);
   $event_ids=array();
   foreach ($cat_ids as $cat_id) {
	   $sql = $wpdb->prepare("SELECT event_id FROM $events_table WHERE FIND_IN_SET(%d,event_category_ids) $extra_condition ORDER BY event_start ASC, event_name ASC",$cat_id);
	   if (empty($event_ids)) {
		   $event_ids=$wpdb->get_col($sql);
	   } else {
		   $event_ids=array_unique(array_merge($event_ids,$wpdb->get_col($sql)));
	   }
   }
   return $event_ids;
}

function eme_get_location_categories($location_id,$extra_conditions="", $order_by="") { 
   global $wpdb;
   $locations_table = $wpdb->prefix.LOCATIONS_TBNAME; 
   $categories_table = $wpdb->prefix.CATEGORIES_TBNAME; 
   if ($extra_conditions !="")
      $extra_conditions = " AND ($extra_conditions)";
   if ($order_by !="")
      $order_by = " ORDER BY $order_by";
   $sql = $wpdb->prepare("SELECT $categories_table.* FROM $categories_table, $locations_table where location_id = %d AND FIND_IN_SET(category_id,location_category_ids) $extra_conditions $order_by",$location_id);
   return $wpdb->get_results($sql,ARRAY_A);
}

function eme_get_location_category_names($location_id,$extra_conditions="", $order_by="") { 
   global $wpdb;
   $locations_table = $wpdb->prefix.LOCATIONS_TBNAME; 
   $categories_table = $wpdb->prefix.CATEGORIES_TBNAME; 
   if ($extra_conditions !="")
      $extra_conditions = " AND ($extra_conditions)";
   if ($order_by !="")
      $order_by = " ORDER BY $order_by";
   $sql = $wpdb->prepare("SELECT $categories_table.category_name FROM $categories_table, $locations_table where location_id = %d AND FIND_IN_SET(category_id,location_category_ids) $extra_conditions $order_by",$location_id);
   return $wpdb->get_col($sql);
}

function eme_get_location_category_descriptions($location_id,$extra_conditions="", $order_by="") { 
   global $wpdb;
   $locations_table = $wpdb->prefix.LOCATIONS_TBNAME; 
   $categories_table = $wpdb->prefix.CATEGORIES_TBNAME; 
   if ($extra_conditions !="")
      $extra_conditions = " AND ($extra_conditions)";
   if ($order_by !="")
      $order_by = " ORDER BY $order_by";
   $sql = $wpdb->prepare("SELECT $categories_table.description FROM $categories_table, $locations_table where location_id = %d AND FIND_IN_SET(category_id,location_category_ids) $extra_conditions $order_by",$location_id);
   return $wpdb->get_col($sql);
}

function eme_get_category_ids($cat_slug) {
   global $wpdb;
   $categories_table = $wpdb->prefix.CATEGORIES_TBNAME; 
   $cat_ids = array();
   if (!empty($cat_slug)) {
      $sql = $wpdb->prepare("SELECT DISTINCT category_id FROM $categories_table WHERE category_slug = %s",$cat_slug);
      $cat_ids = $wpdb->get_col($sql);
   }
   return $cat_ids;
}

function eme_get_categories_shortcode($atts) {
   extract(shortcode_atts(array(
      'event_id'  => 0,
      'eventful'  => false,
      'scope'     => 'all',
      'template_id' => 0,
      'template_id_header' => 0,
      'template_id_footer' => 0
   ), $atts));
   $eventful = ($eventful==="true" || $eventful==="1") ? true : $eventful;
   $eventful = ($eventful==="false" || $eventful==="0") ? false : $eventful;

   if ($event_id)
      $categories = eme_get_event_categories($event_id);
   else
      $categories = eme_get_categories($eventful,$scope);

   // format is not a locations shortcode, so we need to set the value to "" here, to avoid php warnings
   $format="";
   $eme_format_header="";
   $eme_format_footer="";

   if ($template_id) {
      $format = eme_get_template_format($template_id);
   }
   if ($template_id_header) {
      $format_header = eme_get_template_format($template_id_header);
      $eme_format_header=eme_replace_categories_placeholders($format_header);
   }
   if ($template_id_footer) {
      $format_footer = eme_get_template_format($template_id_footer);
      $eme_format_footer=eme_replace_categories_placeholders($format_footer);
   }
   if (eme_is_empty_string($format))
      $format = "<li class=\"cat-#_CATEGORYFIELD{category_id}\">#_CATEGORYFIELD{category_name}</li>";
   if (eme_is_empty_string($eme_format_header))
      $eme_format_header = '<ul>';
   if (eme_is_empty_string($eme_format_footer))
      $eme_format_header = '</ul>';

   $output = "";
   foreach ($categories as $cat) {
      $output .= eme_replace_categories_placeholders($format,$cat);
   }
   $output = $eme_format_header . $output . $eme_format_footer;
   return $output;
}

function eme_replace_categories_placeholders($format, $cat="", $target="html", $do_shortcode=1, $lang='') {

   $email_target = 0;
   $orig_target = $target;
   if ($target == "htmlmail") {
           $email_target = 1;
           $target = "html";
   }

   $needle_offset=0;
   preg_match_all('/#(ESC|URL)?@?_?[A-Za-z0-9_]+(\{(?>[^{}]+|(?2))*\})*+/', $format, $placeholders,PREG_OFFSET_CAPTURE);
   foreach($placeholders[0] as $orig_result) {
      $result = $orig_result[0];
      $orig_result_needle = $orig_result[1]-$needle_offset;
      $orig_result_length = strlen($orig_result[0]);
      $need_escape = 0;
      $need_urlencode = 0;
      $found=1;
      if (strstr($result,'#ESC')) {
         $result = str_replace("#ESC","#",$result);
         $need_escape=1;
      } elseif (strstr($result,'#URL')) {
         $result = str_replace("#URL","#",$result);
         $need_urlencode=1;
      }
      $replacement = "";

      if (preg_match('/#_CATEGORYFIELD\{(.+)\}/', $result, $matches)) {
         $tmp_attkey=$matches[1];
         if (isset($cat[$tmp_attkey]) && !is_array($cat[$tmp_attkey]))
            $replacement = $cat[$tmp_attkey];
      } elseif (preg_match('/#_CATEGORYURL/', $result)) {
         $replacement = eme_category_url($cat);
      } else {
         $found = 0;
      }

      if ($found) {
         if ($target == "html") {
            $replacement = eme_trans_esc_html($replacement,$lang);
            $replacement = apply_filters('eme_general', $replacement);
         } elseif ($target == "rss")  {
            $replacement = eme_translate($replacement,$lang);
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = eme_translate($replacement,$lang);
            $replacement = apply_filters('eme_text', $replacement);
         }
         if ($need_escape)
            $replacement = eme_esc_html(preg_replace('/\n|\r/','',$replacement));
         if ($need_urlencode)
            $replacement = rawurlencode($replacement);
	 $format = substr_replace($format, $replacement, $orig_result_needle,$orig_result_length );
	 $needle_offset += $orig_result_length-strlen($replacement);
      }
   }

   // now, replace any language tags found
   $format = eme_translate($format,$lang);

   // and now replace any shortcodes, if wanted
   if ($do_shortcode)
      return do_shortcode($format);   
   else
      return $format;   
}
?>
