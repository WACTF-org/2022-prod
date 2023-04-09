<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eme_new_task() {
	$task=array(
		'event_id' => 0,
		'task_start' => '',
		'task_end' => '',
		'name' => '',
		'description' => '',
		'task_seq' => 1,
		'task_nbr' => 0,
		'spaces' => 1
	);
	return $task;
}

function eme_handle_tasks_post_adminform($event_id,$day_difference=0) {
	global $eme_timezone;
	$eme_tasks_arr = array();
	if (empty($_POST['eme_tasks'])) {
		return $eme_tasks_arr;
	}
	$seq_nbr=1;
	$task_nbr=1;
	$task_nbr_seen=0;
	foreach ($_POST['eme_tasks'] as $eme_task) {
		if (!empty($eme_task['task_nbr']) && $eme_task['task_nbr']>$task_nbr_seen) {
			$task_nbr_seen=$eme_task['task_nbr'];
		}
	}
	$next_task_nbr=$task_nbr_seen+1;
	foreach ($_POST['eme_tasks'] as $eme_task) {
		$eme_task['name'] = eme_sanitize_request($eme_task['name']);
		$eme_task['task_seq'] = $seq_nbr;
		$eme_task['event_id'] = $event_id;
		$eme_task['task_start'] = eme_sanitize_request($eme_task['task_start']);
		$eme_task['task_end'] = eme_sanitize_request($eme_task['task_end']);
		if (eme_is_empty_string($eme_task['name']) || eme_is_empty_datetime($eme_task['task_start']) || eme_is_empty_datetime($eme_task['task_end'])) {
			continue;
		}
		if ($day_difference!=0) {
			$eme_date_obj_start=new ExpressiveDate($eme_task['task_start'],$eme_timezone);
			$eme_date_obj_end=new ExpressiveDate($eme_task['task_end'],$eme_timezone);
			$eme_task['task_start'] = $eme_date_obj_start->addDays($day_difference)->getDateTime();
			$eme_task['task_end'] = $eme_date_obj_end->addDays($day_difference)->getDateTime();
		}
		$eme_task['description'] = eme_sanitize_request($eme_task['description']);
		// we check for task nbr to know if we need an update or insert
		if (empty($eme_task['task_nbr'])) {
			$eme_task['task_nbr']=$next_task_nbr;
			$next_task_nbr++;
			$task_id = eme_db_insert_task($eme_task);
		} else {
			// we update by the combo event_id and task_nbr and not by task_id
			// that way we can do task updates for recurrences too
			$task_id = eme_db_update_task_by_task_nbr($eme_task);
		}
		$eme_tasks_arr[] = $task_id;
		$seq_nbr++;
	}
	return $eme_tasks_arr;
}

function eme_db_insert_task($line) {
   global $wpdb;
   $table = $wpdb->prefix.TASKS_TBNAME;

   $tmp_task=eme_new_task();
   // we only want the columns that interest us
   $keys=array_intersect_key($line,$tmp_task);
   $task=array_merge($tmp_task,$keys);

   if ($wpdb->insert($table,$task) === false) {
      return false;
   } else {
      $task_id = $wpdb->insert_id;
      return $task_id;
   }
}

function eme_db_update_task_by_task_nbr($line) {
   global $wpdb;
   $table = $wpdb->prefix.TASKS_TBNAME;

   // get the task id
   $sql = $wpdb->prepare("SELECT task_id FROM $table WHERE event_id = %d AND task_nbr = %d",$line['event_id'],$line['task_nbr']);
   $task_id= $wpdb->get_var($sql);
   if (empty($task_id)) {
	   // this happens for recurrences where e.g. a new day is added to the recurrence
	   return eme_db_insert_task($line);
   } else {
	   $line['task_id']=$task_id;
	   eme_db_update_task($line);
	   return $task_id;
   }
}

function eme_db_update_task($line) {
   global $wpdb;
   $table = $wpdb->prefix.TASKS_TBNAME;
   $where=array();
   $where['task_id']=$line['task_id'];

   $tmp_task=eme_new_task();
   // we only want the columns that interest us
   $keys=array_intersect_key($line,$tmp_task);
   $task=array_merge($tmp_task,$keys);

   if ($wpdb->update($table, $task, $where) === false) {
	   return false;
   } else {
	   return true;
   }
}

function eme_db_delete_task($task_id) {
   global $wpdb;
   $table = $wpdb->prefix.TASKS_TBNAME;
   $wpdb->delete( $table, array( 'task_id' => $task_id ) );
}

function eme_delete_event_tasks($event_id) {
   global $wpdb;
   $table = $wpdb->prefix.TASKS_TBNAME;
   $sql = $wpdb->prepare( "DELETE FROM $table WHERE event_id=%d",$event_id) ;
   $wpdb->query($sql);

   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $sql = $wpdb->prepare( "DELETE FROM $table WHERE event_id=%d",$event_id) ;
   $wpdb->query($sql);
}

function eme_delete_event_old_tasks($event_id,$ids_arr) {
   global $wpdb;
   if (empty($ids_arr) || !eme_array_integers($ids_arr)) {
	   return;
   }
   $task_ids=join(',',$ids_arr);
   $table = $wpdb->prefix.TASKS_TBNAME;
   $wpdb->query( "DELETE FROM $table WHERE event_id=$event_id AND task_id NOT IN (".$task_ids.")" );
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $wpdb->query( "DELETE FROM $table WHERE event_id=$event_id AND task_id NOT IN (".$task_ids.")" );
}

function eme_cancel_task_signup($signup_randomid) {
   global $wpdb;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $sql = $wpdb->prepare( "DELETE FROM $table WHERE random_id=%s",$signup_randomid) ;
   return $wpdb->query($sql);
}

function eme_db_insert_task_signup($line) {
   global $wpdb;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $line['random_id']=eme_random_id();
   if ($wpdb->insert($table,$line) === false) {
      return false;
   } else {
      $signup_id = $wpdb->insert_id;
      return $signup_id;
   }
}

function eme_db_update_task_signup($line) {
   global $wpdb;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $where=array();
   $where['id']=$line['id'];
   if ($wpdb->update($table, $line, $where) === false)
	   $res=false;
   else
	   $res=true;
   return $res;
}

function eme_transfer_person_task_signups($person_ids,$to_person_id) {
   global $wpdb;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $sql = "UPDATE $table SET person_id = $to_person_id WHERE person_id IN ($person_ids)";
   return $wpdb->query($sql);
}

function eme_db_delete_task_signup($signup_id) {
   global $wpdb;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   if ($wpdb->delete( $table, array( 'id' => $signup_id )) === false)
           $res=false;
   else
           $res=true;
   return $res;
}

function eme_get_task($task_id) {
   global $wpdb;
   $table = $wpdb->prefix.TASKS_TBNAME;
   $sql = $wpdb->prepare("SELECT * FROM $table WHERE task_id=%d",$task_id);
   return $wpdb->get_row($sql, ARRAY_A);
}

function eme_get_event_tasks($event_id) {
   global $wpdb;
   $table = $wpdb->prefix.TASKS_TBNAME;
   $sql = $wpdb->prepare("SELECT * FROM $table WHERE event_id=%d ORDER BY task_seq ASC",$event_id);
   return $wpdb->get_results($sql, ARRAY_A);
}

function eme_get_task_signup($id) {
   global $wpdb;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $sql = $wpdb->prepare("SELECT * FROM $table WHERE id=%d",$id);
   return $wpdb->get_row($sql, ARRAY_A);
}

function eme_count_task_signups($task_id) {
   global $wpdb;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $sql = $wpdb->prepare("SELECT COUNT(*) FROM $table WHERE task_id=%d",$task_id);
   return $wpdb->get_var($sql);
}

function eme_get_task_signups($task_id) {
   global $wpdb;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $sql = $wpdb->prepare("SELECT * FROM $table WHERE task_id=%d",$task_id);
   return $wpdb->get_results($sql, ARRAY_A);
}

function eme_get_task_signups_by($wp_id,$task_id=0,$event_id=0,$scope='future') {
   global $wpdb,$eme_timezone;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   $events_table = $wpdb->prefix.EVENTS_TBNAME;

   $events_join = "LEFT JOIN $events_table ON $table.event_id=$events_table.event_id";
   $order_by = "ORDER BY $events_table.event_start ASC, $events_table.event_name ASC";

   $where_arr=["$people_table.wp_id=%d"];
   if ($scope=='future') {
      $eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
      $search_end_date = $eme_date_obj_now->getDateTime();
      $where_arr[] = "$events_table.event_end >= '$search_end_date'";
   } elseif ($scope=='past') {
      $eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
      $search_end_date = $eme_date_obj_now->getDateTime();
      $where_arr[] = "$events_table.event_end <= '$search_end_date'";
   }
   if (!empty($task_id))
      $where_arr[] = "$table.task_id = ".intval($task_id);
   if (!empty($event_id))
      $where_arr[] = "$table.event_id = ".intval($event_id);
   $where = "WHERE ".implode(" AND ",$where_arr);

   $sql = "SELECT $table.* FROM $table LEFT JOIN $people_table ON $table.person_id=$people_table.person_id $events_join $where $order_by";
   return $wpdb->get_results($sql, ARRAY_A);
}

function eme_get_tasksignup_personids($signup_ids) {
   global $wpdb; 
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $sql = "SELECT DISTINCT person_id FROM $table WHERE id IN ($signup_ids)";
   return $wpdb->get_col($sql);
}

function eme_count_event_task_signups($event_id) {
   global $wpdb;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $sql = $wpdb->prepare("SELECT task_id, COUNT(*) as signup_count FROM $table WHERE event_id=%d GROUP BY task_id",$event_id);
   $res = $wpdb->get_results($sql, ARRAY_A);
   $return_arr = array();
   foreach ($res as $row) {
	   $return_arr[$row['task_id']]=$row['signup_count'];
   }
   return $return_arr;
}

function eme_count_person_task_signups($task_id,$person_id) {
   global $wpdb;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $sql = $wpdb->prepare("SELECT COUNT(*) FROM $table WHERE task_id=%d AND person_id=%d",$task_id,$person_id);
   return $wpdb->get_var($sql);
}

function eme_check_task_signup_overlap($task, $person_id) {
   global $wpdb;
   $tasks_table = $wpdb->prefix.TASKS_TBNAME;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $sql = $wpdb->prepare("SELECT COUNT(*) FROM $table LEFT JOIN $tasks_table ON $table.task_id=$tasks_table.task_id WHERE $table.task_id<>%d AND person_id=%d AND task_start<%s AND task_end>%s",$task['task_id'],$person_id,$task['task_end'],$task['task_start']);
   return $wpdb->get_var($sql);
}

// for CRON
function eme_tasks_send_signup_reminders() {
   global $eme_timezone;
   $eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
   // this gets us future and ongoing events with tasks enabled
   $events = eme_get_events("extra_conditions=".urlencode("event_tasks=1"));
   foreach ($events as $event) {
	   if (eme_is_empty_string($event['event_properties']['task_reminder_days'])) {
		   continue;
	   }
	   $task_reminder_days=explode(',',$event['event_properties']['task_reminder_days']);
	   if (!eme_array_integers($task_reminder_days)) {
		   continue;
	   }
	   $tasks = eme_get_event_tasks($event['event_id']);
	   foreach ($tasks as $task) {
		   $eme_date_obj = new ExpressiveDate($task['task_start'],$eme_timezone);
		   $days_diff=intval($eme_date_obj_now->startOfDay()->getDifferenceInDays($eme_date_obj->startOfDay()));
		   foreach ($task_reminder_days as $reminder_day) {
			   $reminder_day=intval($reminder_day);
			   if ($days_diff==$reminder_day) {
				   $signups=eme_get_task_signups($task['task_id']);
				   foreach ($signups as $signup) {
					   eme_email_tasksignup_action($signup,"reminder");
				   }
			   }
		   }
	   }
   }
}

// for GDPR CRON
function eme_tasks_remove_old_signups() {
   global $wpdb,$eme_timezone;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $remove_old_signups_days = get_option('eme_gdpr_remove_old_signups_days');
   if (empty($remove_old_signups_days)) {
           return;
   } else {
           $remove_old_signups_days=abs($remove_old_signups_days);
   }

   $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
   $old_date = $eme_date_obj->minusDays($remove_old_signups_days)->getDateTime();

   // we don't remove old bookings, just anonymize them
   $sql = "DELETE FROM $table WHERE event_id IN (SELECT event_id FROM $events_table WHERE $events_table.event_end < '$old_date')";
   $wpdb->query($sql);
}


function eme_task_signups_page() {      
   global $wpdb;
   
   if (!current_user_can( get_option('eme_cap_manage_task_signups')) && (isset($_GET['eme_admin_action']) || isset($_POST['eme_admin_action']))) {
      $message = __('You have no right to manage task signups!','events-made-easy');
      eme_task_signups_table_layout($message);
      return;
   }

   // Insert/Update/Delete Record
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $message = '';
   if (isset($_POST['eme_admin_action'])) {
      check_admin_referer('eme_admin','eme_admin_nonce');
      if ($_POST['eme_admin_action'] == "do_delete_signup" && isset($_POST['task_signups'])) {
         // Delete template or multiple
         $task_signups = $_POST['task_signups'];
         if (!empty($task_signups) && eme_array_integers($task_signups)) {
               $validation_result = $wpdb->query( "DELETE FROM $table WHERE id IN (". implode(",",$task_signups) .")" );
               if ($validation_result !== false)
                  $message = __("Successfully deleted the selected task signups.",'events-made-easy');
	       else
                  $message = __("There was a problem deleting the selected task signups, please try again.",'events-made-easy');
         } else {
            $message = __("Incorrect IDs supplied. Please try again.",'events-made-easy');
         }
      }
   }
   eme_task_signups_table_layout($message);
} 

function eme_task_signups_table_layout($message = "") {
   global $plugin_page;

   $destination = admin_url("admin.php?page=$plugin_page"); 
   $nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
   if (empty($message))
           $hidden_style="display:none;";
   else
           $hidden_style="";

   echo "
      <div class='wrap nosubsub'>
      <div id='poststuff'>
         <div id='icon-edit' class='icon32'>
            <br />
         </div>
         <h1>".__('Manage task signups', 'events-made-easy')."</h1>\n ";   
         
?>
  <div id="tasksignups-message" class="notice is-dismissible eme-message-admin" style="<?php echo $hidden_style; ?>">
               <p><?php echo $message; ?></p>
  </div>

   <form action="#" method="post">
   <input type="text" class="clearable" name="search_name" id="search_name" placeholder="<?php _e('Task name','events-made-easy'); ?>" size=20 />
   <input type="text" class="clearable" name="search_event" id="search_event" placeholder="<?php _e('Event name','events-made-easy'); ?>" size=20 />
   <input type="text" class="clearable" name="search_person" id="search_person" placeholder="<?php _e('Person name','events-made-easy'); ?>" size=20 />
   <select id="search_scope" name="search_scope">
   <?php
   $scope_names = array ();
   $scope_names['past'] = __ ( 'Past events', 'events-made-easy');
   $scope_names['all'] = __ ( 'All events', 'events-made-easy');
   $scope_names['future'] = __ ( 'Future events', 'events-made-easy');
   foreach ( $scope_names as $key => $value ) {
      $selected = "";
      if ($key == 'future')
         $selected = "selected='selected'";
      echo "<option value='$key' $selected>$value</option>  ";
   }
   ?>
   </select>

   <input id="search_start_date" type="hidden" name="search_start_date" value="" />
   <input id="eme_localized_search_start_date" type="text" name="eme_localized_search_start_date" value="" style="background: #FCFFAA;" readonly="readonly" placeholder="<?php _e('Filter on start date','events-made-easy'); ?>" size=15 data-date='' data-alt-field='search_start_date' class='eme_formfield_fdate' />
   <input id="search_end_date" type="hidden" name="search_end_date" value="" />
   <input id="eme_localized_search_end_date" type="text" name="eme_localized_search_end_date" value="" style="background: #FCFFAA;" readonly="readonly" placeholder="<?php _e('Filter on end date','events-made-easy'); ?>" size=15 data-date='' data-alt-field='search_end_date' class='eme_formfield_fdate' />
   <button id="TaskSignupsLoadRecordsButton" class="button-secondary action"><?php _e('Filter task signups','events-made-easy'); ?></button>
   </form>

   <form id='task-signups-form' action="#" method="post">
   <?php echo $nonce_field; ?>
   <select id="eme_admin_action" name="eme_admin_action">
   <option value="" selected="selected"><?php _e ( 'Bulk Actions' , 'events-made-easy'); ?></option>
   <option value="sendMails"><?php _e ( 'Send generic email to selected persons','events-made-easy'); ?></option>
   <option value="deleteTaskSignups"><?php _e ( 'Delete selected task signups','events-made-easy'); ?></option>
   </select>
   <button id="TaskSignupsActionsButton" class="button-secondary action"><?php _e ( 'Apply' , 'events-made-easy'); ?></button>
   <span class="rightclickhint">
      <?php _e('Hint: rightclick on the column headers to show/hide columns','events-made-easy'); ?>
   </span>
   </form>
   <div id="TaskSignupsTableContainer"></div>
   </div>
   </div>
   <?php
}
function eme_meta_box_div_event_task_signup_made_email($event,$templates_array) {
?>
<div>
   <b><?php _e('Task Signup Made Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email sent to the respondent when that person signs up for a task.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['task_signup_email_subject_tpl'],'eme_prop_task_signup_email_subject_tpl',$templates_array); ?>
   <br />
   <br />
   <b><?php _e('Task Signup Made Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email sent to the respondent when that person signs up for a task.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['task_signup_email_body_tpl'],'eme_prop_task_signup_email_body_tpl',$templates_array); ?>
</div>
<br />
<div>
   <b><?php _e('Contact Person Task Signup Made Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the contact person when someone signs up for a task.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['cp_task_signup_email_subject_tpl'],'eme_prop_cp_task_signup_email_subject_tpl',$templates_array); ?>
   <br />
   <br />
   <b><?php _e('Contact Person Task Signup Made Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the contact person when someone signs up for a task.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['cp_task_signup_email_body_tpl'],'eme_prop_cp_task_signup_email_body_tpl',$templates_array); ?>
</div>
<?php
}

function eme_meta_box_div_event_task_signup_updated_email($event,$templates_array) {
?>
<div>
   <b><?php _e('Task Signup Updated Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the respondent if the task signup has been updated by an admin.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['task_signup_updated_email_subject_tpl'],'eme_prop_task_signup_updated_email_subject_tpl',$templates_array); ?>
   <br />
   <br />
   <b><?php _e('Task Signup Updated Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the respondent if the task signup has been updated by an admin.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['task_signup_updated_email_body_tpl'],'eme_prop_task_signup_updated_email_body_tpl',$templates_array); ?>
</div>
<?php
}

function eme_meta_box_div_event_task_signup_cancelled_email($event,$templates_array) {
?>
<div>
   <b><?php _e('Task Signup Cancelled Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the respondent when he himself cancels a task signup.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['task_signup_cancelled_email_subject_tpl'],'eme_prop_task_signup_cancelled_email_subject_tpl',$templates_array); ?>
   <br />
   <br />
   <b><?php _e('Task Signup Cancelled Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the respondent when he himself cancels a task signup.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['task_signup_cancelled_email_body_tpl'],'eme_prop_task_signup_cancelled_email_body_tpl',$templates_array); ?>
</div>
<br />
<div>
   <b><?php _e('Contact Person Task Signup Cancelled Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the contact person when a respondent cancels a task signup.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['cp_task_signup_cancelled_email_subject_tpl'],'eme_prop_cp_task_signup_cancelled_email_subject_tpl',$templates_array); ?>
   <br />
   <br />
   <b><?php _e('Contact Person Task Signup Cancelled Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the contact person when a respondent cancels a task signup.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['cp_task_signup_cancelled_email_body_tpl'],'eme_prop_cp_task_signup_cancelled_email_body_tpl',$templates_array); ?>
</div>
<?php
}

function eme_meta_box_div_event_task_signup_trashed_email($event,$templates_array) {
?>
<div id="div_event_task_signup_trashed_email">
   <b><?php _e('Task Signup Deleted Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the respondent if the task signup is deleted by an admin.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['task_signup_trashed_email_subject_tpl'],'eme_prop_task_signup_trashed_email_subject_tpl',$templates_array); ?>
   <br />
   <br />
   <b><?php _e('Task Signup Deleted Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the respondent if the task signup is deleted by an admin.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['task_signup_trashed_email_body_tpl'],'eme_prop_task_signup_trashed_email_body_tpl',$templates_array); ?>
</div>
<?php
}

function eme_meta_box_div_event_task_signup_reminder_email($event,$templates_array) {
?>
<div id="div_event_task_signup_reminder_email">
   <b><?php _e('Task Signup Reminder Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the reminder email which will be sent to the respondent.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['task_signup_reminder_email_subject_tpl'],'eme_prop_task_signup_reminder_email_subject_tpl',$templates_array); ?>
   <br />
   <br />
   <b><?php _e('Task Signup Reminder Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the reminder email which will be sent to the respondent.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy'); echo eme_ui_select($event['event_properties']['task_signup_reminder_email_body_tpl'],'eme_prop_task_signup_reminder_email_body_tpl',$templates_array); ?>
</div>
<?php
}

function eme_meta_box_div_event_task_signup_form_format($event,$templates_array) {
?>
<div id="div_event_task_signup_form_format">
   <b><?php _e('Task Signup Form', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The layout of the task signup form.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy');?>
   <?php echo eme_ui_select($event['event_properties']['task_signup_form_format_tpl'],'eme_prop_task_signup_form_format_tpl',$templates_array); ?>
</div>
<?php
}

function eme_meta_box_div_event_task_signup_recorded_ok_html($event,$templates_array) {
?>
<div id="div_event_task_signup_recorded_ok_html">
   <b><?php _e('Signup recorded message', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The text (html allowed) shown to the user when the task signup has been made successfully.','events-made-easy');?></p>
   <br />
   <?php _e ('Only choose a template if you want to override the default settings:', 'events-made-easy');?>
   <?php echo eme_ui_select($event['event_properties']['task_signup_recorded_ok_html_tpl'],'eme_prop_task_signup_recorded_ok_html_tpl',$templates_array); ?>
</div>
<?php
}

function eme_meta_box_div_event_tasks($event,$edit_recurrence=0) {
   global $eme_plugin_url, $eme_timezone;
   if (isset($event['is_duplicate'])) {
	   $tasks=eme_get_event_tasks($event['orig_id']);
   } elseif (!empty($event['event_id'])) {
	   $tasks=eme_get_event_tasks($event['event_id']);
   } else {
	   $tasks=array();
   }
   ?>
   <div id="div_tasks">
      <table class="eme_tasks">
         <thead>
            <tr>
               <th></th>
               <th></th>
               <th><strong><?php _e('Name','events-made-easy'); ?></strong></th>
               <th><strong><?php _e('Begin','events-made-easy'); ?></strong></th>
               <th><strong><?php _e('End','events-made-easy'); ?></strong></th>
               <th><strong><?php _e('Spaces','events-made-easy'); ?></strong></th>
               <th><strong><?php _e('Description','events-made-easy'); ?></strong></th>
               <th></th>
            </tr>
         </thead>    
         <tbody id="eme_tasks_tbody" class="eme_tasks_tbody">
            <?php
            // if there are no entries in the array, make 1 empty entry in it, so it renders at least 1 row
            if(!is_array($tasks) || count($tasks) == 0) {
		 $info=eme_new_task();
		 $tasks = [ $info ];
		 $required="";
	    } else {
		 $required="required='required'";
	    }
            foreach( $tasks as $count=>$task){
                  ?>
                  <tr id="eme_row_task_<?php echo $count ?>" >
                     <td>
			  <?php echo "<img class='eme-sortable-handle' src='".$eme_plugin_url."images/reorder.png' alt='".__('Reorder','events-made-easy')."' />";  ?>
                     </td>
                     <td>
			<?php if (!isset($event['is_duplicate'])): // we set the task ids only if it is not a duplicate event ?>
			<input type='hidden' id="eme_tasks[<?php echo $count; ?>][task_id]" name="eme_tasks[<?php echo $count; ?>][task_id]" aria-label="hidden index" size="5" value="<?php if (isset($task['task_id'])) echo $task['task_id']; ?>">
			<input type='hidden' id="eme_tasks[<?php echo $count; ?>][task_nbr]" name="eme_tasks[<?php echo $count; ?>][task_nbr]" aria-label="hidden index" size="5" value="<?php if (isset($task['task_nbr'])) echo $task['task_nbr']; ?>">
			<?php endif; ?>
                     </td>
                     <td>
			<input <?php echo $required; ?> id="eme_tasks[<?php echo $count; ?>][name]" name="eme_tasks[<?php echo $count; ?>][name]" size="12" aria-label="name" value="<?php echo $task['name']; ?>">
                     </td>
                     <td>
			<input type='hidden' readonly='readonly' name='eme_tasks[<?php echo $count; ?>][task_start]' id='eme_tasks[<?php echo $count; ?>][task_start]' />
	                <input <?php echo $required; ?> type='text' readonly='readonly' name='eme_tasks[<?php echo $count; ?>][dp_task_start]' id='eme_tasks[<?php echo $count; ?>][dp_task_start]' data-date='<?php if ($task['task_start']) echo eme_js_datetime($task['task_start']); ?>' data-alt-field='#eme_tasks[<?php echo $count; ?>][task_start]' class='eme_formfield_fdatetime' />
                     </td>
                     <td>
			<input type='hidden' readonly='readonly' name='eme_tasks[<?php echo $count; ?>][task_end]' id='eme_tasks[<?php echo $count; ?>][task_end]' />
			<input <?php echo $required; ?> type='text' readonly='readonly' name='eme_tasks[<?php echo $count; ?>][dp_task_end]' id='eme_tasks[<?php echo $count; ?>][dp_task_end]' data-date='<?php if ($task['task_end']) echo eme_js_datetime($task['task_end']); ?>' data-alt-field='#eme_tasks[<?php echo $count; ?>][task_end]' class='eme_formfield_fdatetime' />
                     </td>
                     <td>
			<input <?php echo $required; ?> id="eme_tasks[<?php echo $count; ?>][spaces]" name="eme_tasks[<?php echo $count; ?>][spaces]" size="12" aria-label="spaces" value="<?php echo $task['spaces']; ?>">
                     </td>
                     <td>
			<textarea id="eme_tasks[<?php echo $count; ?>][description]" name="eme_tasks[<?php echo $count; ?>][description]" ><?php echo eme_esc_html($task['description']);?></textarea>
                     </td>
                     <td>
                        <a href="#" class='eme_remove_task'><?php echo "<img src='".$eme_plugin_url."images/cross.png' alt='".__('Remove','events-made-easy')."' title='".__('Remove','events-made-easy')."' />"; ?></a><a href="#" class="eme_add_task"><?php echo "<img src='".$eme_plugin_url."images/plus_16.png' alt='".__('Add new task','events-made-easy')."' title='".__('Add new task','events-made-easy')."' />"; ?></a>
                     </td>
                  </tr>
            <?php
            }
            ?>
         </tbody>
      </table>
      <?php _e('If name, start date or end date of a task is empty, it will be ignored.','events-made-easy'); ?>
      <?php if ($edit_recurrence) {
                echo "<div style='background-color: lightgrey;'>";
		_e('For recurring events, enter the start and end date of the task as if you would do it for the first event in the series. The tasks for the other events will be adjusted accordingly.','events-made-easy');
		if (!eme_is_empty_date($event['event_start'])) {
			echo "<br />";
			sprintf(__('The start date of the first event in the series was initially %s','events-made-easy'),eme_localized_datetime($event['event_start'],$eme_timezone));
		}
		echo "</div>";
            }
      ?>
   </div>
<?php
}

function eme_meta_box_div_event_task_settings($event) {
   $eme_prop_task_registered_users_only = ($event['event_properties']['task_registered_users_only']) ? "checked='checked'" : "";
   $eme_prop_task_allow_overlap = ($event['event_properties']['task_allow_overlap']) ? "checked='checked'" : "";
   $eme_prop_task_reminder_days = eme_esc_html($event['event_properties']['task_reminder_days']);
?>
   <div id='div_event_task_settings'>
      <p id='p_task_registered_users_only'>
          <input id="eme_prop_task_registered_users_only" name='eme_prop_task_registered_users_only' value='1' type='checkbox' <?php echo $eme_prop_task_registered_users_only; ?> />
	  <label for="eme_prop_task_registered_users_only"><?php _e('Require WP membership to be able to sign up for tasks?','events-made-easy');?></label>
      </p>
      <p id='p_task_registered_users_only'>
          <input id="eme_prop_task_allow_overlap" name='eme_prop_task_allow_overlap' value='1' type='checkbox' <?php echo $eme_prop_task_allow_overlap; ?> />
	  <label for="eme_prop_task_allow_overlap"><?php _e('Allow overlap for task signups?','events-made-easy'); ?></label>
      </p>
      <p id='p_task_reminder_days'>
          <input id="eme_prop_task_reminder_days" name='eme_prop_task_reminder_days' type='text' value="<?php echo $eme_prop_task_reminder_days; ?>" />
	  <label for="eme_prop_task_reminder_days"><?php _e('Set the number of days before task signup reminder emails will be sent (counting from the start date of the task). If you want to send out multiple reminders, seperate the days here by commas. Leave empty for no reminder emails.','events-made-easy'); ?></label>
      </p>
   </div>
<?php
}

function eme_mytasks_signups_shortcode($atts) {
   if (is_user_logged_in()) {
	   $wp_id = get_current_user_id();
   } else {
	   return;
   }
   $person = eme_get_person_by_wp_id($wp_id);
   if (empty($person))
	   return;
   extract ( shortcode_atts ( array ('scope' => 'future', 'task_id' => 0, 'event_id'=>0, 'template_id' => 0, 'template_id_header' => 0, 'template_id_footer' => 0 ), $atts ) );
   $format = "";
   $header = "";
   $footer = "";
   if (!empty($template_id))
	   $format = eme_get_template_format($template_id);
   if (empty($format))
	   $format = get_option('eme_task_signup_format');

   if (!empty($template_id_header))
	   $header = eme_get_template_format($template_id_header);

   if (!empty($template_id_footer))
	   $footer = eme_get_template_format($template_id_footer);

   $signups = eme_get_task_signups_by($wp_id,$task_id,$event_id,$scope);
   $result = $header;
   foreach ($signups as $signup) {
	   $event = eme_get_event ($signup['event_id']);
	   $task = eme_get_task ($signup['task_id']);
	   $result .= eme_replace_tasksignup_placeholders($format,$signup,$person,$event,$task);
   }
   $result .= $footer;
   return $result;
}

function eme_tasks_signups_shortcode($atts) {
   extract ( shortcode_atts ( array ('scope' => 'future', 'order' => 'ASC', 'category' => '', 'showperiod' => '', 'author' => '', 'contact_person' => '', 'event_id' => 0, 'location_id' => 0, 'task_id' => 0, 'show_ongoing' => 1, 'notcategory' => '', 'show_recurrent_events_once' => 0, 'template_id' => 0, 'template_id_header' => 0, 'template_id_footer' => 0, 'ignore_filter' => 0 ), $atts ) );
   $event_id_arr = array();
   $location_id_arr = array();
   $result = '';

   // per event, the header and footer are repeated, the template_id itself is repeated per task
   $format = "";
   $header = "";
   $footer = "";
   if (!empty($template_id))
	   $format = eme_get_template_format($template_id);
   if (empty($format))
	   $format = get_option('eme_task_signup_format');

   if (!empty($template_id_header))
	   $header = eme_get_template_format($template_id_header);

   if (!empty($template_id_footer))
	   $footer = eme_get_template_format($template_id_footer);

   // if a task id is set, show only the signups for that task
   $task_id = intval($task_id);
   if ($task_id > 0) {
	   $signups = eme_get_task_signups($task_id);
	   foreach ($signups as $signup) {
		   $person = eme_get_person($signup['person_id']);
		   $result .= eme_replace_tasksignup_placeholders ($format,$signup,$person, $event, $task);
	   }
	   return $result;
   }

   // the filter list overrides the settings
   if (!$ignore_filter && isset($_REQUEST['eme_eventAction']) && $_REQUEST['eme_eventAction'] == 'filter') {
      if (!empty($_REQUEST['eme_scope_filter'])) {
         $scope = eme_sanitize_request($_REQUEST['eme_scope_filter']);
      }
      if (!empty($_REQUEST['eme_author_filter']) && intval($_REQUEST['eme_author_filter'])>0) {
         $author = intval($_REQUEST['eme_author_filter']);
      }
      if (!empty($_REQUEST['eme_contact_filter']) && intval($_REQUEST['eme_contact_filter'])>0) {
         $contact_person = intval($_REQUEST['eme_contact_filter']);
      }
      if (!empty($_REQUEST['eme_loc_filter'])) {
         if (is_array($_REQUEST['eme_loc_filter'])) {
	    $arr = eme_array_remove_empty_elements(eme_sanitize_request($_REQUEST['eme_loc_filter']));
            if (!empty($arr))
            	$location_id_arr=$arr;
	 } else {
            $location_id_arr[]=eme_sanitize_request($_REQUEST['eme_loc_filter']);
	 }
	 if (empty($location_id_arr))
		 $location_id=-1;
      }
      if (!empty($_REQUEST['eme_city_filter'])) {
         $cities=eme_sanitize_request($_REQUEST['eme_city_filter']);
         $tmp_ids=eme_get_city_location_ids($cities);
         if (empty($location_id_arr))
                 $location_id_arr=$tmp_ids;
         else
                 $location_id_arr=array_intersect($location_id_arr,$tmp_ids);
	 if (empty($location_id_arr))
		 $location_id=-1;

      }
      if (!empty($_REQUEST['eme_country_filter'])) {
         $countries=eme_sanitize_request($_REQUEST['eme_country_filter']);
         $tmp_ids=eme_get_country_location_ids($countries);
         if (empty($location_id_arr))
                 $location_id_arr=$tmp_ids;
         else
                 $location_id_arr=array_intersect($location_id_arr,$tmp_ids);
	 if (empty($location_id_arr))
		 $location_id=-1;
      }
      if (!empty($_REQUEST['eme_cat_filter'])) {
         if (is_array($_REQUEST['eme_cat_filter'])) {
	    $arr = eme_array_remove_empty_elements(eme_sanitize_request($_REQUEST['eme_cat_filter']));
            if (!empty($arr))
		    $category=join(',',$arr);
	 } else {
            $category=eme_sanitize_request($_REQUEST['eme_cat_filter']);
	 }
      }
      foreach($_REQUEST as $key=>$value) {
         if (preg_match('/eme_customfield_filter(\d+)/', eme_sanitize_request($key), $matches)) {
               $field_id = intval($matches[1]);
               $formfield = eme_get_formfield($field_id);
	       if (!empty($formfield)) {
		       $is_multi = eme_is_multifield($formfield['field_type']);
		       if ($formfield['field_purpose'] == 'events') {
			       $tmp_ids = eme_get_cf_event_ids(eme_sanitize_request($value),$field_id,$is_multi);
			       if (empty($event_id_arr))
				       $event_id_arr=$tmp_ids;
			       else
				       $event_id_arr=array_intersect($event_id_arr,$tmp_ids);
			       if (empty($event_id_arr))
				       $event_id=-1;
		       }
		       if ($formfield['field_purpose'] == 'locations') {
			       $tmp_ids = eme_get_cf_location_ids(eme_sanitize_request($value),$field_id,$is_multi);
			       if (empty($location_id_arr))
				       $location_id_arr=$tmp_ids;
			       else
				       $location_id_arr=array_intersect($location_id_arr,$tmp_ids);
			       if (empty($location_id_arr))
				       $location_id=-1;
		       }
               }
         }
      }
   }
   if ($event_id != -1 && !empty($event_id_arr))
	   $event_id=join(',',$event_id_arr);
   if ($location_id != -1 && !empty($location_id_arr))
	   $location_id=join(',',$location_id_arr);

   $extra_conditions_arr = array();
   $extra_conditions = "";
   if (!empty($event_id)) {
	   if (strstr(',',$event_id)) {
		   $extra_conditions_arr[] = "event_id in ($event_id)";
	   } else {
		   $extra_conditions_arr[] = "event_id = ".intval($event_id);
	   }
   }

   if (!empty($extra_conditions_arr)) {
	   $extra_conditions="(".join(" AND ",$extra_conditions_arr).")";
   }

   $extra_conditions_arr[] = "event_tasks = 1";
   $events = eme_get_events(0, $scope, $order, 0, $location_id, $category, $author, $contact_person,  $show_ongoing, $notcategory, $show_recurrent_events_once, $extra_conditions);

   foreach ($events as $event) {
	   $tasks = eme_get_event_tasks($event['event_id']);
	   if (empty($tasks))
		   continue;
	   $result .= "<div class='eme_event_tasks'>";
	   $result .= eme_replace_event_placeholders($header,$event);
	   $result .= "<br />";
	   foreach ($tasks as $task) {
		   $signups = eme_get_task_signups($task['task_id']);
		   foreach ($signups as $signup) {
			   $person = eme_get_person($signup['person_id']);
			   $result .= eme_replace_tasksignup_placeholders ($format,$signup,$person, $event, $task);
		   }
	   }
	   $result .= "<br />";
	   $result .= eme_replace_event_placeholders($footer,$event);
	   $result .= "</div>";
   }

   return $result;
}

function eme_tasks_signupform_shortcode($atts) {
   extract ( shortcode_atts ( array ('scope' => 'future', 'order' => 'ASC', 'category' => '', 'showperiod' => '', 'author' => '', 'contact_person' => '', 'event_id'=>0, 'location_id' => 0, 'show_ongoing' => 1, 'notcategory' => '', 'show_recurrent_events_once' => 0, 'template_id' => 0, 'template_id_header' => 0, 'template_id_footer' => 0, 'signupform_template_id' => 0, 'skip_full' => 0, 'ignore_filter' => 0 ), $atts ) );
   $event_id_arr=array();
   $location_id_arr=array();
   $result = '';

   // the filter list overrides the settings
   if (!$ignore_filter && isset($_REQUEST['eme_eventAction']) && $_REQUEST['eme_eventAction'] == 'filter') {
      if (!empty($_REQUEST['eme_scope_filter'])) {
         $scope = eme_sanitize_request($_REQUEST['eme_scope_filter']);
      }
      if (!empty($_REQUEST['eme_author_filter']) && intval($_REQUEST['eme_author_filter'])>0) {
         $author = intval($_REQUEST['eme_author_filter']);
      }
      if (!empty($_REQUEST['eme_contact_filter']) && intval($_REQUEST['eme_contact_filter'])>0) {
         $contact_person = intval($_REQUEST['eme_contact_filter']);
      }
      if (!empty($_REQUEST['eme_loc_filter'])) {
         if (is_array($_REQUEST['eme_loc_filter'])) {
	    $arr = eme_array_remove_empty_elements(eme_sanitize_request($_REQUEST['eme_loc_filter']));
            if (!empty($arr))
            	$location_id_arr=$arr;
	 } else {
            $location_id_arr[]=eme_sanitize_request($_REQUEST['eme_loc_filter']);
	 }
	 if (empty($location_id_arr))
		 $location_id=-1;
      }
      if (!empty($_REQUEST['eme_city_filter'])) {
         $cities=eme_sanitize_request($_REQUEST['eme_city_filter']);
         $tmp_ids=eme_get_city_location_ids($cities);
         if (empty($location_id_arr))
                 $location_id_arr=$tmp_ids;
         else
                 $location_id_arr=array_intersect($location_id_arr,$tmp_ids);
	 if (empty($location_id_arr))
		 $location_id=-1;

      }
      if (!empty($_REQUEST['eme_country_filter'])) {
         $countries=eme_sanitize_request($_REQUEST['eme_country_filter']);
         $tmp_ids=eme_get_country_location_ids($countries);
         if (empty($location_id_arr))
                 $location_id_arr=$tmp_ids;
         else
                 $location_id_arr=array_intersect($location_id_arr,$tmp_ids);
	 if (empty($location_id_arr))
		 $location_id=-1;
      }
      if (!empty($_REQUEST['eme_cat_filter'])) {
         if (is_array($_REQUEST['eme_cat_filter'])) {
	    $arr = eme_array_remove_empty_elements(eme_sanitize_request($_REQUEST['eme_cat_filter']));
            if (!empty($arr))
		    $category=join(',',$arr);
	 } else {
            $category=eme_sanitize_request($_REQUEST['eme_cat_filter']);
	 }
      }
      foreach($_REQUEST as $key=>$value) {
         if (preg_match('/eme_customfield_filter(\d+)/', eme_sanitize_request($key), $matches)) {
               $field_id = intval($matches[1]);
               $formfield = eme_get_formfield($field_id);
	       if (!empty($formfield)) {
		       $is_multi = eme_is_multifield($formfield['field_type']);
		       if ($formfield['field_purpose'] == 'events') {
			       $tmp_ids=eme_get_cf_event_ids(eme_sanitize_request($value),$field_id,$is_multi);
			       if (empty($event_id_arr))
				       $event_id_arr=$tmp_ids;
			       else
				       $event_id_arr=array_intersect($event_id_arr,$tmp_ids);
			       if (empty($event_id_arr))
				       $event_id=-1;
		       }
		       if ($formfield['field_purpose'] == 'locations') {
			       $tmp_ids=eme_get_cf_location_ids(eme_sanitize_request($value),$field_id,$is_multi);
			       if (empty($location_id_arr))
				       $location_id_arr=$tmp_ids;
			       else
				       $location_id_arr=array_intersect($location_id_arr,$tmp_ids);
			       if (empty($location_id_arr))
				       $location_id=-1;
		       }
               }
         }
      }
   }
   if ($event_id != -1 && !empty($event_id_arr))
	   $event_id=join(',',$event_id_arr);
   if ($location_id != -1 && !empty($location_id_arr))
	   $location_id=join(',',$location_id_arr);

   $extra_conditions_arr = array();
   $extra_conditions = "";
   if (!empty($event_id)) {
	   if (strstr(',',$event_id)) {
		   $extra_conditions_arr[] = "event_id in ($event_id)";
	   } else {
		   $extra_conditions_arr[] = "event_id = ".intval($event_id);
	   }
   }
   $extra_conditions_arr[] = "event_tasks = 1";

   if (!empty($extra_conditions_arr)) {
	   $extra_conditions="(".join(" AND ",$extra_conditions_arr).")";
   }

   $events = eme_get_events(0, $scope, $order, 0, $location_id, $category, $author, $contact_person,  $show_ongoing, $notcategory, $show_recurrent_events_once, $extra_conditions);
   if (empty($events)) {
	   $result = "<div id='eme-tasks-message' class='eme-message-info eme-tasks-message eme-no-tasks'>".__('There are no tasks to sign up for right now','events-made-easy')."</div>";
	   return $result;
   }

   // per event, the header and footer are repeated, the template_id itself is repeated per task
   $format = "";
   $header = "";
   $footer = "";
   if (!empty($template_id))
	   $format = eme_get_template_format($template_id);
   if (empty($format))
	   $format = get_option('eme_task_form_taskentry_format');

   if (!strstr($format,'#_TASKSIGNUPCHECKBOX')) {
                   $format="#_TASKSIGNUPCHECKBOX $format";
   }

   if (!empty($template_id_header))
	   $header = eme_get_template_format($template_id_header);

   if (!empty($template_id_footer))
	   $footer = eme_get_template_format($template_id_footer);

   $current_userid=get_current_user_id();
   if (current_user_can( get_option('eme_cap_edit_events')) ||
           (current_user_can( get_option('eme_cap_author_event')) && ($event['event_author']==$current_userid || $event['event_contactperson_id']==$current_userid))) {
           $search_tables=get_option('eme_autocomplete_sources');
           if ($search_tables!='none') {
                   wp_enqueue_script('eme-autocomplete-form');
           }
   }

   $nonce = wp_nonce_field('eme_frontend','eme_frontend_nonce',false,false);
   $form_id = uniqid();
   $result .= "<noscript><div class='eme-noscriptmsg'>".__('Javascript is required for this form to work properly','events-made-easy')."</div></noscript>
        <div id='eme-tasks-message-ok-$form_id' class='eme-message-success eme-tasks-message eme-tasks-message-success eme-hidden'></div><div id='eme-tasks-message-error-$form_id' class='eme-message-error eme-tasks-message eme-tasks-message-error eme-hidden'></div><div id='div_eme-tasks-form-$form_id' style='display: none' class='eme-showifjs'><form id='$form_id' name='eme-tasks-form' method='post' action='#'>
                $nonce
                <span id='honeypot_check'><input type='text' name='honeypot_check' value='' autocomplete='off' /></span>
                ";

   $open_tasks_found = 0;
   foreach ($events as $event) {
	   // we add the event ids for the autocomplete check, not used for anything else
	   $result .= "<input type='hidden' name='eme_event_ids[]' id='eme_event_ids[]' value='".$event['event_id']."' />";
	   $registered_users_only = $event['event_properties']['task_registered_users_only'];
	   if ($registered_users_only && !is_user_logged_in())
		   continue;
	   $tasks = eme_get_event_tasks($event['event_id']);
	   if (empty($tasks))
		   continue;
	   $result .= "<div class='eme_event_tasks'>";
	   $result .= eme_replace_event_placeholders($header,$event);
	   $result .= "<br />";
	   foreach ($tasks as $task) {
		   $skip = 0;
		   $used_spaces = eme_count_task_signups($task['task_id']);
		   $free_spaces = $task['spaces']-$used_spaces;
		   if ($free_spaces == 0 && $skip_full) {
			   // skip full option, so check the free spaces for that task, if 0: set $skip=1
			   $skip = 1;
		   }
		   if ($free_spaces > 0) {
			   $open_tasks_found++;
		   }
		   if (!$skip) {
			   $result .= eme_replace_eventtaskformfields_placeholders($format,$task,$event);
		   }
	   }
	   $result .= "<br />";
	   $result .= eme_replace_event_placeholders($footer,$event);
	   $result .= "</div>";
   }

   // now add the signup form
   if ($open_tasks_found > 0) {
	   if (!empty($signupform_template_id))
		   $signupform_format = eme_get_template_format($signupform_template_id);
	   else
		   $signupform_format = get_option('eme_task_form_format');

	   $result .= eme_replace_task_signupformfields_placeholders($signupform_format);
   } else {
	   $result = "<div id='eme-tasks-message' class='eme-message-info eme-tasks-message eme-no-tasks'>".__('There are no tasks to sign up for right now','events-made-easy')."</div>";
   }

   $result .= "</form></div>";
   return $result;
}

function eme_email_tasksignup_action($signup, $action) {
   $person = eme_get_person ($signup['person_id']);
   $event = eme_get_event ($signup['event_id']);
   $task = eme_get_task ($signup['task_id']);
   $person_email = $person['email'];

   $contact = eme_get_event_contact($event);
   $contact_email = $contact->user_email;
   $contact_name = $contact->display_name;
   $mail_text_html=get_option('eme_rsvp_send_html')?"htmlmail":"text";

   // first get the initial values
   if ($action == "new") {
	   $subject = eme_get_template_format_plain($event['event_properties']['task_signup_email_subject_tpl']);
	   if (empty($subject))
		   $subject = get_option('eme_task_signup_email_subject');
	   $body = eme_get_template_format_plain($event['event_properties']['task_signup_email_body_tpl']);
	   if (empty($body))
		   $body = get_option('eme_task_signup_email_body');
	   $cp_subject = eme_get_template_format_plain($event['event_properties']['cp_task_signup_email_subject_tpl']);
	   if (empty($cp_subject))
		   $cp_subject = get_option('eme_cp_task_signup_email_subject');
	   $cp_body = eme_get_template_format_plain($event['event_properties']['cp_task_signup_email_body_tpl']);
	   if (empty($cp_body))
		   $cp_body = get_option('eme_cp_task_signup_email_body');
   } elseif ($action == "reminder") {
	   $subject = eme_get_template_format_plain($event['event_properties']['task_signup_reminder_email_subject_tpl']);
	   if (empty($subject))
		   $subject = get_option('eme_task_signup_reminder_email_subject');
	   $body = eme_get_template_format_plain($event['event_properties']['task_signup_reminder_email_body_tpl']);
	   if (empty($body))
		   $body = get_option('eme_task_signup_reminder_email_body');
	   $cp_subject = "";
	   $cp_body = "";
   } elseif ($action == "cancel") {
	   $subject = eme_get_template_format_plain($event['event_properties']['task_signup_cancelled_email_subject_tpl']);
	   if (empty($subject))
		   $subject = get_option('eme_task_signup_cancelled_email_subject');
	   $body = eme_get_template_format_plain($event['event_properties']['task_signup_cancelled_email_body_tpl']);
	   if (empty($body))
		   $body = get_option('eme_task_signup_cancelled_email_body');
	   $cp_subject = eme_get_template_format_plain($event['event_properties']['cp_task_signup_cancelled_email_subject_tpl']);
	   if (empty($cp_subject))
		   $cp_subject = get_option('eme_cp_task_signup_cancelled_email_subject');
	   $cp_body = eme_get_template_format_plain($event['event_properties']['cp_task_signup_cancelled_email_body_tpl']);
	   if (empty($cp_body))
		   $cp_body = get_option('eme_cp_task_signup_cancelled_email_body');
   } elseif ($action == "delete") {
	   $subject = eme_get_template_format_plain($event['event_properties']['task_signup_trashed_email_subject_tpl']);
	   if (empty($subject))
		   $subject = get_option('eme_task_signup_trashed_email_subject');
	   $body = eme_get_template_format_plain($event['event_properties']['task_signup_trashed_email_body_tpl']);
	   if (empty($body))
		   $body = get_option('eme_task_signup_trashed_email_body');
	   $cp_subject = "";
	   $cp_body = "";
   }

   if (!empty($cp_subject) && !empty($cp_body)) {
	   $cp_subject = eme_replace_tasksignup_placeholders($cp_subject, $signup, $person, $event, $task, "text");
	   $cp_body = eme_replace_tasksignup_placeholders($cp_body, $signup, $person, $event, $task, $mail_text_html);
	   eme_queue_mail($cp_subject, $cp_body, $contact_email, $contact_name, $contact_email, $contact_name);
   }
   $subject = eme_replace_tasksignup_placeholders($subject, $signup, $person, $event, $task, "text");
   $body = eme_replace_tasksignup_placeholders($body, $signup, $person, $event, $task, $mail_text_html);
   $person_name=eme_format_full_name($person['firstname'],$person['lastname']);
   $mail_res = eme_queue_mail($subject,$body, $person_email, $person_name, $contact_email, $contact_name, 0,$person['person_id']);
   return $mail_res;
}

function eme_replace_task_placeholders ($format, $task,$event, $target="html",$lang="") {
   global $eme_timezone;

   $email_target = 0;
   $orig_target = $target;
   if ($target == "htmlmail") {
           $email_target = 1;
           $target = "html";
   }

   $used_spaces = eme_count_task_signups($task['task_id']);
   $free_spaces = $task['spaces']-$used_spaces;

   if (empty($lang))
        $lang = eme_detect_lang();

   preg_match_all("/#(REQ)?_[A-Za-z0-9_]+/", $format, $placeholders, PREG_OFFSET_CAPTURE);
   $needle_offset = 0;
   foreach($placeholders[0] as $orig_result) {
      $result = $orig_result[0];
      $orig_result_needle = $orig_result[1]-$needle_offset;
      $orig_result_length = strlen($orig_result[0]);
      $found = 1;
      $replacement = "";

      if (preg_match('/#_TASKNAME$/', $result)) {
	 $replacement = eme_translate($task['name'],$lang);
	 if ($target == "html") {
                 $replacement = eme_esc_html($replacement);
                 $replacement = apply_filters('eme_general', $replacement);
         } else {
                 $replacement = apply_filters('eme_text', $replacement);
         }
      } elseif (preg_match('/#_TASKDESCRIPTION$/', $result)) {
	 $replacement = eme_translate($task['description'],$lang);
	 if ($target == "html") {
                 $replacement = eme_esc_html($replacement);
                 $replacement = apply_filters('eme_general', $replacement);
         } else {
                 $replacement = apply_filters('eme_text', $replacement);
         }
      } elseif (preg_match('/#_TASKBEGIN|#_TASKSTARTDATE/', $result)) {
	 $replacement = eme_localized_datetime($task['task_start'],$eme_timezone);
      } elseif (preg_match('/#_TASKEND|#_TASKENDDATE/', $result)) {
	 $replacement = eme_localized_datetime($task['task_end'],$eme_timezone);
      } elseif (preg_match('/#_TASKSPACES$/', $result)) {
	 $replacement = intval($task['spaces']);
      } elseif (preg_match('/#_FREETASKSPACES$/', $result)) {
	 $replacement = $free_spaces;
      } elseif (preg_match('/#_USEDTASKSPACES$/', $result)) {
	 $replacement = $used_spaces;
      } elseif (preg_match('/#_TASKID$/', $result)) {
	 $replacement = $task['task_id'];
      } elseif (preg_match('/#_TASKSIGNUPS$/', $result)) {
	 $taskformat = get_option('eme_task_signup_format');
         $signups = eme_get_task_signups($task['task_id']);
         foreach ($signups as $signup) {
             $person = eme_get_person($signup['person_id']);
             $replacement .= eme_replace_tasksignup_placeholders ($taskformat,$signup,$person, $event, $task);
         }
      } else {
         $found = 0;
      }

      if ($found) {
	      $format = substr_replace($format, $replacement, $orig_result_needle,$orig_result_length );
	      $needle_offset += $orig_result_length-strlen($replacement);
      }
   }

   $format = eme_replace_event_placeholders($format, $event, $orig_target, $lang);
   // now, replace any language tags found in the format itself
   $format = eme_translate($format, $lang);

   return $format;
}

function eme_replace_tasksignup_placeholders ($format,$signup,$person, $event, $task,$target="html",$lang="") {
   global $eme_timezone;

   $email_target = 0;
   $orig_target = $target;
   if ($target == "htmlmail") {
           $email_target = 1;
           $target = "html";
   }

   if (empty($lang) && !empty($person['lang']))
           $lang = $person['lang'];
   if (empty($lang))
        $lang = eme_detect_lang();

   preg_match_all("/#(REQ)?_[A-Za-z0-9_]+/", $format, $placeholders, PREG_OFFSET_CAPTURE);
   $needle_offset = 0;
   foreach($placeholders[0] as $orig_result) {
      $result = $orig_result[0];
      $orig_result_needle = $orig_result[1]-$needle_offset;
      $orig_result_length = strlen($orig_result[0]);
      $found = 1;
      $replacement = "";

      if (preg_match('/#_TASKSIGNUPCANCEL_URL$/', $result)) {
	 $replacement = eme_tasksignup_cancel_url($signup);
	 if ($target == "html")
		 $replacement = esc_url($replacement);
      } elseif (preg_match('/#_TASKSIGNUPCANCEL_LINK$/', $result)) {
	      $url = eme_tasksignup_cancel_url($signup);
	      if ($target == "html")
		      $url = esc_url($url);
	      $replacement="<a href='$url'>".__('Cancel task signup','events-made-easy')."</a>";
      } elseif (preg_match('/#_USER_IS_REGISTERED$/', $result)) {
	      $wp_id = get_current_user_id();
	      if ($wp_id>0 && $wp_id==$person['wp_id'])
		      $replacement=1;
	      else 
		      $replacement=0;
      } else {
         $found = 0;
      }

      if ($found) {
	      $format = substr_replace($format, $replacement, $orig_result_needle,$orig_result_length );
	      $needle_offset += $orig_result_length-strlen($replacement);
      }
   }

   // the cancel url
   // now any leftover task/people placeholders
   $format = eme_replace_people_placeholders($format, $person, $orig_target, $lang);
   $format = eme_replace_task_placeholders($format, $task, $event, $orig_target, $lang);

   // now, replace any language tags found in the format itself
   $format = eme_translate($format, $lang);

   return $format;
}

add_action( 'wp_ajax_eme_tasks', 'eme_tasks_ajax' );
add_action( 'wp_ajax_nopriv_eme_tasks', 'eme_tasks_ajax' );
function eme_tasks_ajax() {
	// check for spammers as early as possible
	if (get_option('eme_honeypot_for_forms')) {
		if (!isset($_POST['honeypot_check']) || !empty($_POST['honeypot_check'])) {
			$message = __("Bot detected. If you believe you've received this message in error please contact the site owner.",'events-made-easy');
			echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
			wp_die();
		}
	}
	if (!isset($_POST['eme_frontend_nonce']) || !wp_verify_nonce($_POST['eme_frontend_nonce'],"eme_frontend")) {
                $message = __("Form tampering detected. If you believe you've received this message in error please contact the site owner.",'events-made-easy');
		echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
		wp_die();
	}
	if (!isset($_POST['eme_task_signups']) || empty($_POST['eme_task_signups'])) {
		$message = __("Please select at least one task.",'events-made-easy');
		echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
		wp_die();
	}

	$captcha_res = '';
	$remove_captcha_if_ok = 0;
	if (get_option('eme_recaptcha_for_forms')) {
		$captcha_res = eme_check_recaptcha();
		if (!$captcha_res) {
			$message = __("Please check the Google reCAPTCHA box",'events-made-easy');
			echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
			wp_die();
		}
	} elseif (get_option('eme_hcaptcha_for_forms')) {
		$captcha_res = eme_check_hcaptcha();
		if (!$captcha_res) {
			$message = __("Please check the hCaptcha box",'events-made-easy');
			echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
			wp_die();
		}
	} elseif (get_option('eme_captcha_for_forms')) {
		$captcha_res = eme_check_captcha(0);
		if (!$captcha_res) {
			$message = __("You entered an incorrect code",'events-made-easy');
			echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
			wp_die();
		} else {
			$remove_captcha_if_ok = 1;
		}
	}

	if (is_user_logged_in()) {
		$booker_wp_id = get_current_user_id();
	} else {
		$booker_wp_id = 0;
	}
	$bookerLastName = '';
	$bookerFirstName = '';
	$bookerEmail = '';

	// start with an empty person_id, once needed it will get a real id
	$person_id = 0;
	$message = "";
	$nok = 0;
	$ok = 0;
	foreach ($_POST['eme_task_signups'] as $event_id=>$task_id_arr) {
		$event_id = intval($event_id);
		$event = eme_get_event($event_id);
		$allow_overlap = $event['event_properties']['task_allow_overlap'];
		$registered_users_only = $event['event_properties']['task_registered_users_only'];
		//$status = ($event['event_properties']['task_require_approval'])? 0 : 1;
		if ($registered_users_only && !$booker_wp_id) {
			$message .= get_option('eme_rsvp_login_required_string');
			$nok = 1;
			continue;
		}
		// the next is an array with as key the task id and value the number of signups for it
		$event_task_count_signups = eme_count_event_task_signups($event_id);
		foreach ($task_id_arr as $task_id) {
			$task_id = intval($task_id);
			$task = eme_get_task($task_id);
			// if full, continue
			if (isset($event_task_count_signups[$task_id]) && $event_task_count_signups[$task_id] >= $task['spaces']) {
				$message .= __('No more open spaces for this task','events-made-easy');
				$message .= "<br />";
				$nok = 1;
				continue;
			}
			if (!$person_id && isset($_POST['task_lastname']) && isset($_POST['task_email'])) {
				$bookerLastName = eme_sanitize_request($_POST['task_lastname']);
				if (isset($_POST['task_firstname']))
					$bookerFirstName = eme_sanitize_request($_POST['task_firstname']);
				$bookerEmail = eme_sanitize_email($_POST['task_email']);
				$res = eme_add_update_person_from_form(0,$bookerLastName, $bookerFirstName, $bookerEmail);
				$person_id = $res[0];
			}
			if (!empty($person_id)) {
				// no doubles
				$person_task_count_signups = eme_count_person_task_signups($task_id,$person_id);
				if ($person_task_count_signups>0) {
					$message .= __('Duplicate signup detected','events-made-easy');
					$message .= "<br />";
					$nok = 1;
					continue;
				}
				// no overlaps (unless wanted)
				if (!$allow_overlap && eme_check_task_signup_overlap($task,$person_id)) {
					$message .= __('Signup overlap with another task detected','events-made-easy');
					$message .= "<br />";
					$nok = 1;
					continue;
				}
				// all ok, insert signup
				$signup = [ 'task_id' => $task_id, 'person_id' => $person_id, 'event_id' => $event_id ];
				eme_db_insert_task_signup($signup);
				eme_email_tasksignup_action($signup,"new");
				$message .= __('Signup done','events-made-easy');
				$message .= "<br />";
				$ok = 1;
			}
			
		}
	}

	// if some task signups were ok, but others not, show ok
	if ($ok && $nok) {
		//echo json_encode(array('Result'=>'OK','keep_form'=>1,'htmlmessage'=>$message));
		echo json_encode(array('Result'=>'OK','htmlmessage'=>$message));
	} elseif ($nok) {
		echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
	} elseif ($ok) {
		//echo json_encode(array('Result'=>'OK','keep_form'=>1,'htmlmessage'=>$message));
		echo json_encode(array('Result'=>'OK','htmlmessage'=>$message));
	}

	// remove the captcha if ok
	if ($remove_captcha_if_ok && $ok) {
		eme_captcha_remove($captcha_res);
	}
	wp_die();
}

add_action( 'wp_ajax_eme_task_signups_list', 'eme_ajax_task_signups_list' );
add_action( 'wp_ajax_eme_manage_task_signups', 'eme_ajax_manage_task_signups' );

function eme_ajax_task_signups_list() {
   global $wpdb, $eme_timezone;
   $table = $wpdb->prefix.TASK_SIGNUPS_TBNAME;
   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   $tasks_table = $wpdb->prefix.TASKS_TBNAME;
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   $jTableResult = array();
   $search_name = isset($_REQUEST['search_name']) ? eme_sanitize_request($_REQUEST['search_name']) : '';
   $search_scope = (isset($_REQUEST['search_scope'])) ? eme_sanitize_request($_REQUEST['search_scope']) : 'future';
   $search_event = isset($_REQUEST['search_event']) ? eme_sanitize_request($_REQUEST['search_event']) : '';
   $search_person = isset($_REQUEST['search_person']) ? eme_sanitize_request($_REQUEST['search_person']) : '';
   $search_start_date = isset($_REQUEST['search_start_date']) ? esc_sql($_REQUEST['search_start_date']) : '';
   $search_end_date = isset($_REQUEST['search_end_date']) ? esc_sql($_REQUEST['search_end_date']) : '';

   $where ='';
   $where_arr = array();
   if(!empty($search_name)) {
      $where_arr[] = "tasks.name like '%".$search_name."%'";
   }
   if(!empty($search_event)) {
      $where_arr[] = "events.event_name like '%".$search_event."%'";
   }
   if(!empty($search_person)) {
      $where_arr[] = "(people.lastname like '%".$search_person."%' OR people.firstname like '%".$search_person."%' OR people.email like '%".$search_person."%')";
   }

   if (!empty($search_start_date) && eme_is_date($search_start_date) &&
       !empty($search_end_date) && eme_is_date($search_end_date)) {
      $where_arr[] = "events.event_start >= '$search_start_date'";
      $where_arr[] = "events.event_end <= '$search_end_date 23:59:59'";
   } elseif (!empty($search_start_date) && eme_is_date($search_start_date)) {
      $where_arr[] = "events.event_start LIKE '$search_start_date%'";
   } elseif (!empty($search_end_date) && eme_is_date($search_end_date)) {
      $where_arr[] = "events.event_end LIKE '$search_end_date%'";
   } elseif ($search_scope=='future') {
      $eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
      $search_end_date = $eme_date_obj_now->getDateTime();
      $where_arr[] = "events.event_end >= '$search_end_date'";
   } elseif ($search_scope=='past') {
      $eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
      $search_end_date = $eme_date_obj_now->getDateTime();
      $where_arr[] = "events.event_end <= '$search_end_date'";
   } 

   if ($where_arr)
      $where = "WHERE ".implode(" AND ",$where_arr);

   $join = "LEFT JOIN $events_table AS events ON $table.event_id=events.event_id ";
   $join .= "LEFT JOIN $tasks_table AS tasks ON $table.task_id=tasks.task_id ";
   $join .= "LEFT JOIN $people_table AS people ON $table.person_id=people.person_id ";

   if (current_user_can( get_option('eme_cap_manage_task_signups'))) {
      $sql = "SELECT COUNT(*) FROM $table $join $where";
      $recordCount = $wpdb->get_var($sql);
      $start= (isset($_REQUEST['jtStartIndex'])) ? intval($_REQUEST['jtStartIndex']) : 0;
      $pagesize= (isset($_REQUEST['jtPageSize'])) ? intval($_REQUEST['jtPageSize']) : 10;
      $sorting = (isset($_REQUEST['jtSorting'])) ? 'ORDER BY '.esc_sql($_REQUEST['jtSorting']) : 'ORDER BY task_seq ASC';
      $sql="SELECT $table.*, events.event_id,events.event_name, events.event_start, events.event_end, people.person_id,people.lastname, people.firstname, people.email, tasks.name AS task_name, task_start, task_end FROM $table $join $where $sorting LIMIT $start,$pagesize";
      $rows=$wpdb->get_results($sql,ARRAY_A);
      foreach ($rows as $key=>$row) {
	      $localized_start_date = eme_localized_date($row['event_start'],$eme_timezone,1);
	      $localized_end_date = eme_localized_date($row['event_end'],$eme_timezone,1);
	      $localized_taskstart_date = eme_localized_datetime($row['task_start'],$eme_timezone,1);
	      $localized_taskend_date = eme_localized_datetime($row['task_end'],$eme_timezone,1);
	      $rows[$key]['event_name'] = "<strong><a href='".admin_url("admin.php?page=eme-manager&amp;eme_admin_action=edit_event&amp;event_id=".$row['event_id'])."' title='".__('Edit event','events-made-easy')."'>".eme_trans_esc_html($row['event_name'])."</a></strong><br />".$localized_start_date .' - '. $localized_end_date;
              $rows[$key]['task_name'] = eme_esc_html($row['task_name']);
              $rows[$key]['task_start'] = $localized_taskstart_date;
              $rows[$key]['task_end'] = $localized_taskend_date;
              $rows[$key]['person_info'] = "<a href='".admin_url("admin.php?page=eme-people&amp;eme_admin_action=edit_person&amp;person_id=".$row['person_id'])."' title='".__('Edit person','events-made-easy')."'>".eme_esc_html(eme_format_full_name($row['firstname'],$row['lastname']))."</a>";
      }

      $jTableResult['Result'] = "OK";
      $jTableResult['TotalRecordCount'] = $recordCount;
      $jTableResult['Records'] = $rows;
   } else {
      $jTableResult['Result'] = "Error";
      $jTableResult['Message'] = __('Access denied!','events-made-easy');
   }
   print json_encode($jTableResult);
   wp_die();

}
function eme_ajax_manage_task_signups() {
   check_ajax_referer('eme_admin','eme_admin_nonce');
   if (isset($_REQUEST['do_action'])) {
     $ids_arr=explode(',',$_POST['id']);
     $do_action=eme_sanitize_request($_REQUEST['do_action']);
     switch ($do_action) {
         case 'deleteTaskSignups':
	      eme_ajax_action_signup_delete($ids_arr);
              break;
      }
   }
   wp_die();
}

function eme_ajax_action_signup_delete($ids_arr) {
   $action_ok = 1;
   foreach ($ids_arr as $signup_id) {
      $signup = eme_get_task_signup($signup_id);
      $res = eme_db_delete_task_signup($signup_id);
      if (!$res) {
	      $action_ok = 0;
      } else {
	      eme_email_tasksignup_action($signup,"delete");
      }
   }
   $ajaxResult=array();
   if ($action_ok) {
         $ajaxResult['htmlmessage'] = "<div id='message' class='updated eme-message-admin'><p>".__('The action has been executed successfully.','events-made-easy')."</p></div>";
         $ajaxResult['Result'] = "OK";
   } else {
         $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('There was a problem executing the desired action, please check your logs.','events-made-easy')."</p></div>";
         $ajaxResult['Result'] = "ERROR";
   }
   print json_encode($ajaxResult);
}

?>
