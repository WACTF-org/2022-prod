<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eme_new_holidays() {
   $hol = array(
      'name' => '',
      'list' => ''
   );
   return $hol;
}

function eme_holidays_page() {      
   global $wpdb;
   
   if (!current_user_can( get_option('eme_cap_holidays')) && (isset($_GET['eme_admin_action']) || isset($_POST['eme_admin_action']))) {
      $message = __('You have no right to update holidays!','events-made-easy');
      eme_holidays_table_layout($message);
      return;
   }
   
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == "edit_holidays") { 
      // edit holidays  
      check_admin_referer('eme_admin','eme_admin_nonce');
      eme_holidays_edit_layout();
      return;
   }

   if (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action'] == "add_holidays") {
      check_admin_referer('eme_admin','eme_admin_nonce');
      eme_holidays_edit_layout();
      return;
   }

   // Insert/Update/Delete Record
   $holidays_table = $wpdb->prefix.HOLIDAYS_TBNAME;
   $message = '';
   if (isset($_POST['eme_admin_action'])) {
      check_admin_referer('eme_admin','eme_admin_nonce');
      if ($_POST['eme_admin_action'] == "do_editholidays" ) {
         // holidays update required  
         $holidays = array();
         $holidays['name'] =  eme_sanitize_request($_POST['name']);
         $holidays['list'] =  eme_sanitize_textarea($_POST['list']);
         if (!empty($_POST['id'])) {
		 $validation_result = $wpdb->update( $holidays_table, $holidays, array('id' => intval($_POST['id'])) );
		 if ($validation_result !== false) {
			 $message = __("Successfully edited the list of holidays", 'events-made-easy');
		 } else {
			 $message = __("There was a problem editing the list of holidays, please try again.", 'events-made-easy');
		 }
	 } else {
		 $validation_result = $wpdb->insert($holidays_table, $holidays);
		 if ($validation_result !== false) {
			 $message = __("Successfully added the list of holidays", 'events-made-easy');
		 } else {
			 $message = __("There was a problem adding the list of holidays, please try again.", 'events-made-easy');
		 }
	 }
      } elseif ($_POST['eme_admin_action'] == "do_deleteholidays" && isset($_POST['holidays'])) {
         // Delete holidays
         $holidays = $_POST['holidays'];
         if (is_array($holidays) && eme_array_integers($holidays)) {
            //Run the query if we have an array of holidays ids
            if (count($holidays > 0)) {
               $validation_result = $wpdb->query( "DELETE FROM $holidays_table WHERE id IN ( ". implode(",", $holidays) .")" );
               if ($validation_result !== false)
                  $message = __("Successfully deleted the selected holiday lists.", 'events-made-easy');
               else
                  $message = __("There was a problem deleting the selected holiday lists, please try again.", 'events-made-easy');
            } else {
               $message = __("Couldn't delete the holiday lists. Incorrect IDs supplied. Please try again.", 'events-made-easy');
            }
         } else {
            $message = __("Couldn't delete the holiday lists. Incorrect IDs supplied. Please try again.",'events-made-easy');
         }
      }
   }
   eme_holidays_table_layout($message);
} 

function eme_holidays_table_layout($message = "") {
   global $plugin_page;
   $holidays = eme_get_holiday_lists();
   $destination = admin_url("admin.php?page=$plugin_page"); 
   $nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
   $table = "
      <div class='wrap nosubsub'>\n
      <div id='poststuff'>
         <div id='icon-edit' class='icon32'>
            <br />
         </div>
         <h1>".__('Manage holidays', 'events-made-easy')."</h1>\n ";   
         
         if($message != "") {
            $table .= "
            <div id='message' class='updated notice notice-success is-dismissible'>
               <p>$message</p>
            </div>";
         }
         
         $table .= "
   <div class='wrap'>
         <form id='holidays-new' method='post' action='$destination'>
            $nonce_field
            <input type='hidden' name='eme_admin_action' value='add_holidays' />
            <input type='submit' class='button-primary' name='submit' value='".__('Add list of holidays', 'events-made-easy')."' />
         </form>
   </div>
<br /><br />

                <form id='holidays-form' method='post' action='$destination'>
                  <input type='hidden' name='eme_admin_action' value='do_deleteholidays' />";
                  $table .= $nonce_field;
                  if (count($holidays)>0) {
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
                     foreach ($holidays as $this_holidays) {
			if (empty($this_holidays['name'])) $this_holidays['name']=__('No name','events-made-easy');
                        $table .= "    
                           <tr>
                           <td><input type='checkbox' class ='row-selector' value='".$this_holidays['id']."' name='holidays[]' /></td>
                           <td><a href='".wp_nonce_url(admin_url("admin.php?page=eme-holidays&amp;eme_admin_action=edit_holidays&amp;id=".$this_holidays['id']),'eme_admin','eme_admin_nonce')."'>".$this_holidays['id']."</a></td>
                           <td><a href='".wp_nonce_url(admin_url("admin.php?page=eme-holidays&amp;eme_admin_action=edit_holidays&amp;id=".$this_holidays['id']),'eme_admin','eme_admin_nonce')."'>".eme_trans_esc_html($this_holidays['name'])."</a></td>
                           </tr>
                        ";
                     }
                     $delete_text=esc_html__("Are you sure you want to delete these holiday lists?",'events-made-easy');
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
                        $table .= "<p>".__('No holiday lists have been inserted yet!', 'events-made-easy');
                  }
                   $table .= "
                  </form>
         </div>
   </div>";
   echo $table;  
}

function eme_holidays_edit_layout($message = "") {
   global $plugin_page;

   if(isset($_GET['id'])) {
           $holidays_id = intval($_GET['id']);
	   $holidays = eme_get_holiday_list($holidays_id);
           $h1_string=__('Edit holidays list', 'events-made-easy');
           $action_string=__('Update list of holidays', 'events-made-easy');
   } else {
           $holidays_id=0;
           $holidays = eme_new_holidays();
           $h1_string=__('Create holidays list', 'events-made-easy');
           $action_string=__('Add list of holidays', 'events-made-easy');
   }

   $nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
   $layout = "
   <div class='wrap'>
      <div id='icon-edit' class='icon32'>
         <br />
      </div>
         
      <h1>".$h1_string."</h1>";   
      
      if($message != "") {
         $layout .= "
      <div id='message' class='updated notice notice-success is-dismissible'>
         <p>$message</p>
      </div>";
      }
      $layout .= "
      <div id='ajax-response'></div>

      <form name='edit_holidays' id='edit_holidays' method='post' action='".admin_url("admin.php?page=$plugin_page")."'>
      <input type='hidden' name='eme_admin_action' value='do_editholidays' />
      <input type='hidden' name='id' value='".$holidays_id."' />
      $nonce_field
      <table class='form-table'>
            <tr class='form-field'>
               <th scope='row' style='vertical-align:top'><label for='name'>".__('Holidays listname', 'events-made-easy')."</label></th>
               <td><input name='name' id='name' required='required' type='text' value='".eme_esc_html($holidays['name'])."' size='40' /><br />
                 ".__('The name of the holidays list', 'events-made-easy')."</td>
            </tr>
            <tr class='form-field'>
               <th scope='row' style='vertical-align:top'><label for='description'>".__('Holidays list', 'events-made-easy')."</label></th>
               <td><textarea name='list' id='description' rows='5' >".eme_esc_html($holidays['list'])."</textarea><br />
                 ".__('Basic format: YYYY-MM-DD, one per line','events-made-easy').'<br />'.__('For more information about holidays, see ', 'events-made-easy')." <a target='_blank' href='http://www.e-dynamics.be/wordpress/?cat=6086'>".__('the documentation', 'events-made-easy')."</a></td>
            </tr>
         </table>
      <p class='submit'><input type='submit' class='button-primary' name='submit' value='".$action_string."' /></p>
      </form>
   </div>
   ";  
   echo $layout;
}

function eme_get_holiday_lists() { 
   global $wpdb;
   $holidays_table = $wpdb->prefix.HOLIDAYS_TBNAME; 
   $sql = "SELECT id,name FROM $holidays_table";
   return $wpdb->get_results($sql, ARRAY_A);
}
function eme_get_holiday_list($id) { 
   global $wpdb;
   $holidays_table = $wpdb->prefix.HOLIDAYS_TBNAME; 
   $sql = $wpdb->prepare("SELECT * FROM $holidays_table WHERE id = %d",$id);
   return $wpdb->get_row($sql, ARRAY_A);
}

function eme_get_holiday_listinfo($id) {
   $holiday_list = eme_get_holiday_list($id);
   $res_days = array();
   $days=explode("\n", str_replace("\r","\n",$holiday_list['list']));
   foreach ($days as $day_info) {
      //$info=explode(',',$day_info);
      list($day,$name,$class)=array_pad(explode(',',$day_info),3,'');
      $res_days[$day]['name']=$name;
      $res_days[$day]['class']=$class;
   }
   return $res_days;
}

function eme_get_holidays_array_by_id() {
   $holidays = eme_get_holiday_lists();
   $holidays_by_id=array();
   if (!empty($holidays)) {
      $holidays_by_id[]='';
      foreach ($holidays as $holiday_list) {
         $holidays_by_id[$holiday_list['id']]=$holiday_list['name'];
      }
   }
   return $holidays_by_id;
}

# return number of days until next event or until the specified event
function eme_holidays_shortcode($atts) {
   global $eme_timezone;
   extract ( shortcode_atts ( array ('id'=>'', 'scope'=>'all'), $atts ) );

   if ($id!="") {
      $holiday_list=eme_get_holiday_list(intval($id));
   } else {
      return;
   }

   $list=preg_replace('/\r\n|\n\r/',"\n",$holiday_list['list']);
   $days=explode("\n",$list);
   $eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
   print '<div id="eme_holidays_list">';
   foreach ($days as $day_info) {
      list($day,$name,$class)=array_pad(explode(',',$day_info),3,'');
      if (empty($day)) continue;
      $eme_date_obj=new ExpressiveDate($day,$eme_timezone);
      if ($scope=="future" && $eme_date_obj < $eme_date_obj_now) continue;
      if ($scope=="past" && $eme_date_obj > $eme_date_obj_now) continue;
      print '<span id="eme_holidays_date">'.eme_localized_date($day,$eme_timezone).'</span>';
      print '&nbsp; <span id="eme_holidays_name">'.eme_trans_esc_html($name).'</span><br />';
   }
   print '</div>';
}


?>
