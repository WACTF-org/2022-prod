<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eme_new_recurrence() {
   $recurrence = array(
   'recurrence_start_date' => '',
   'recurrence_end_date' => '',
   'recurrence_interval' => 1,
   'recurrence_freq' => '',
   'recurrence_byday' => '',
   'recurrence_byweekno' => '',
   'recurrence_specific_days' => '',
   'event_duration' => 1,
   'holidays_id' => 0
   );
   return $recurrence;
}

function eme_get_recurrence($recurrence_id) {
   global $wpdb;
   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   $recurrence_table = $wpdb->prefix.RECURRENCE_TBNAME;
   $sql = $wpdb->prepare("SELECT * FROM $recurrence_table WHERE recurrence_id = %d",$recurrence_id);
   $recurrence = $wpdb->get_row($sql, ARRAY_A);
   return $recurrence;
}

function eme_get_recurrence_days($recurrence) {
   global $eme_timezone;

   $matching_days = array(); 
   
   if($recurrence['recurrence_freq'] == 'specific') {
   	$specific_days = explode(",", $recurrence['recurrence_specific_days']);
	asort($specific_days);
	foreach ($specific_days as $day) {
		array_push($matching_days, $day);
	}
	return $matching_days;
   }
 
   $start_date_obj = new ExpressiveDate($recurrence['recurrence_start_date'],$eme_timezone);
   $end_date_obj = new ExpressiveDate($recurrence['recurrence_end_date'],$eme_timezone);

   $holidays=array();
   if (isset($recurrence['holidays_id']) && $recurrence['holidays_id']>0) {
      $holidays=eme_get_holiday_listinfo($recurrence['holidays_id']);
   }
 
   $last_week_start = array(25, 22, 25, 24, 25, 24, 25, 25, 24, 25, 24, 25);
   $weekdays = explode(",", $recurrence['recurrence_byday']);
   
   $counter = 0;
   $daycounter = 0;
   $weekcounter = 0;
   $monthcounter=0;
   $start_monthday = $start_date_obj->format('j');
   $cycle_date_obj = $start_date_obj->copy();

   while ($cycle_date_obj <= $end_date_obj) {
      $monthweek = floor((($cycle_date_obj->format('d')-1)/7))+1;
      $ymd=$cycle_date_obj->getDate();

      // skip holidays
      if (!empty($holidays) && isset($holidays[$ymd])) {
         $cycle_date_obj->addOneDay();
         continue;
      }

      if (empty($recurrence['recurrence_interval']))
	      $recurrence['recurrence_interval'] = 1;

      if($recurrence['recurrence_freq'] == 'daily') {
         if($daycounter % $recurrence['recurrence_interval']== 0)
            array_push($matching_days, $ymd);
      }

      if($recurrence['recurrence_freq'] == 'weekly') {
         if (!$recurrence['recurrence_byday'] && eme_N_weekday($cycle_date_obj)==eme_N_weekday($start_date_obj)) {
         // no specific days given, so we use 7 days as interval
            //if($daycounter % 7*$recurrence['recurrence_interval'] == 0 ) {
            if($weekcounter % $recurrence['recurrence_interval'] == 0 )
               array_push($matching_days, $ymd);
         } elseif (in_array(eme_N_weekday($cycle_date_obj), $weekdays )) {
         // specific days, so we only check for those days
            if($weekcounter % $recurrence['recurrence_interval'] == 0 )
               array_push($matching_days, $ymd);
         }
      }

      if($recurrence['recurrence_freq'] == 'monthly') { 
         $monthday = $cycle_date_obj->format('j');
         $month = $cycle_date_obj->format('n');
         // if recurrence_byweekno=0 ==> means to use the startday as repeating day
         if ( $recurrence['recurrence_byweekno'] == 0) {
            if ($monthday == $start_monthday) {
               if ($monthcounter % $recurrence['recurrence_interval'] == 0)
                  array_push($matching_days, $ymd);
               $counter++;
            }
         } elseif (in_array(eme_N_weekday($cycle_date_obj), $weekdays )) {
               if(($recurrence['recurrence_byweekno'] == -1) && ($monthday >= $last_week_start[$month-1])) {
               if ($monthcounter % $recurrence['recurrence_interval'] == 0)
                  array_push($matching_days, $ymd);
            } elseif($recurrence['recurrence_byweekno'] == $monthweek) {
               if ($monthcounter % $recurrence['recurrence_interval'] == 0)
                  array_push($matching_days, $ymd);
            }
            $counter++;
         }
      }
      $cycle_date_obj->addOneDay();
      $daycounter++;
      if ($daycounter%7==0) {
         $weekcounter++;
      }
      if ($cycle_date_obj->format('j')==1) {
         $monthcounter++;
      }
   }
   
   return $matching_days ;
}

// backwards compatible: eme_insert_recurrent_event renamed to eme_db_insert_recurrence
function eme_insert_recurrent_event($event, $recurrence) {
   return eme_db_insert_recurrence($recurrence,$event);
}

function eme_db_insert_recurrence($recurrence,$event){
   global $wpdb, $eme_timezone;
   $recurrence_table = $wpdb->prefix.RECURRENCE_TBNAME;
      
   // never try to update a autoincrement value ...
   if (isset($recurrence['recurrence_id']))
      unset ($recurrence['recurrence_id']);

   // some sanity checks
   $recurrence['recurrence_interval'] = intval($recurrence['recurrence_interval']);
   if ($recurrence['recurrence_freq'] != "specific") {
      $eme_date_obj1 = new ExpressiveDate($recurrence['recurrence_start_date'],$eme_timezone);
      $eme_date_obj2 = new ExpressiveDate($recurrence['recurrence_end_date'],$eme_timezone);
      if ($eme_date_obj2 < $eme_date_obj1) {
         $recurrence['recurrence_end_date']=$recurrence['recurrence_start_date'];
      }
   } else {
      // get the recurrence start days
      $matching_days = eme_get_recurrence_days($recurrence);
      // find the last start day
      $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
      $last_day = $eme_date_obj->getDate();
      foreach ($matching_days as $day) {
	      if ($day>$last_day) $last_day=$day;
      }
      $recurrence['recurrence_end_date']=$last_day;
   }
   $recurrence['event_duration'] = intval($recurrence['event_duration']);

   $wpdb->insert($recurrence_table, $recurrence);
   $recurrence_id = $wpdb->insert_id;

   $recurrence['recurrence_id'] = $recurrence_id;
   $event['recurrence_id'] = $recurrence['recurrence_id'];
   $count=eme_insert_events_for_recurrence($recurrence,$event);
   if ($count) {
      if (has_action('eme_insert_recurrence_action')) do_action('eme_insert_recurrence_action',$event,$recurrence);
      return $recurrence_id;
   } else {
      eme_db_delete_recurrence($recurrence_id);
      return 0;
   }
}

function eme_insert_events_for_recurrence($recurrence,$event) {
   global $wpdb, $eme_timezone;
   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   $matching_days = eme_get_recurrence_days($recurrence);
   sort($matching_days);
   $count=0;
   // in order to take tasks into account for recurring events, we need to know the difference in days between the events
   $eme_date_obj_orig = new ExpressiveDate($event['event_start'],$eme_timezone);
   $event_start_time=eme_get_time_from_dt($event['event_start']);
   $event_end_time=eme_get_time_from_dt($event['event_end']);
   foreach($matching_days as $day) {
      $event['event_start'] = "$day $event_start_time";
      $eme_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
      $day_difference = $eme_date_obj_orig->getDifferenceInDays($eme_date_obj);
      $eme_date_obj->addDays($recurrence['event_duration']-1);
      $event_end_date = $eme_date_obj->getDate();
      $event['event_end'] = "$event_end_date $event_end_time";
      $event_id=eme_db_insert_event($event,1,$day_difference);
      if ($event_id)
	            eme_event_store_cf_answers($event_id);
      $count++;
   }
   return $count;
}

// backwards compatible: eme_update_recurrence renamed to eme_db_update_recurrence
function eme_update_recurrence($event, $recurrence) {
   return eme_db_update_recurrence($recurrence,$event);
}

function eme_db_update_recurrence($recurrence,$event,$only_change_enddate=0) {
   global $wpdb, $eme_timezone;
   $recurrence_table = $wpdb->prefix.RECURRENCE_TBNAME;

   // some sanity checks
   if ($recurrence['recurrence_freq'] != "specific") {
      $eme_date_obj1 = new ExpressiveDate($recurrence['recurrence_start_date'],$eme_timezone);
      $eme_date_obj2 = new ExpressiveDate($recurrence['recurrence_end_date'],$eme_timezone);
      if ($eme_date_obj2 < $eme_date_obj1) {
         $recurrence['recurrence_end_date']=$recurrence['recurrence_start_date'];
      }
   } else {
      // get the recurrence start days
      $matching_days = eme_get_recurrence_days($recurrence);
      // find the last start day
      $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
      $last_day = $eme_date_obj->getDate();
      foreach ($matching_days as $day) {
	      if ($day>$last_day) $last_day=$day;
      }
      $recurrence['recurrence_end_date']=$last_day;
   }

   $where = array('recurrence_id' => $recurrence['recurrence_id']);
   $wpdb->show_errors(true);
   $wpdb->update($recurrence_table, $recurrence, $where); 
   $wpdb->show_errors(false);
   $event['recurrence_id'] = $recurrence['recurrence_id'];
   $count=eme_update_events_for_recurrence($recurrence,$event,$only_change_enddate); 
   if ($count) {
      if (has_action('eme_update_recurrence_action'))
         do_action('eme_update_recurrence_action',$event,$recurrence);
      return $count;
   } else {
      eme_db_delete_recurrence($recurrence['recurrence_id']);
      return 0;
   }
}

function eme_update_events_for_recurrence($recurrence,$event,$only_change_enddate=0) {
   global $wpdb, $eme_timezone;
   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   $matching_days = eme_get_recurrence_days($recurrence);
   sort($matching_days);

   // 2 steps for updating events for a recurrence:
   // First step: check the existing events and if they still match the recurrence days, update them
   //       otherwise delete the old event
   // Reason for doing this: we want to keep possible booking data for a recurrent event as well
   // and just deleting all current events for a recurrence and inserting new ones would break the link
   // between booking id and event id
   // Second step: check all days of the recurrence and if no event exists yet, insert it
   $sql = $wpdb->prepare("SELECT event_id,event_start FROM $events_table WHERE recurrence_id = %d AND event_status <> %d",$recurrence['recurrence_id'],EME_EVENT_STATUS_TRASH);
   $events = $wpdb->get_results($sql, ARRAY_A);

   // in order to take tasks into account for recurring events, we need to know the difference in days between the events
   $eme_date_obj_orig = new ExpressiveDate($event['event_start'],$eme_timezone);
   $event_start_time=eme_get_time_from_dt($event['event_start']);
   $event_end_time=eme_get_time_from_dt($event['event_end']);
   // we'll return the number of events in the recurrence at the end
   $count=0;
   // Doing step 1
   foreach($events as $existing_event) {
	   $update_done=0;
	   foreach($matching_days as $key=>$day) {
		   if (eme_get_date_from_dt($existing_event['event_start']) == $day) {
			   if (!$only_change_enddate) {
				   $event['event_start'] = "$day $event_start_time";
				   $eme_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
				   $day_difference = $eme_date_obj_orig->getDifferenceInDays($eme_date_obj);
				   $eme_date_obj->addDays($recurrence['event_duration']-1);
				   $event_end_date = $eme_date_obj->getDate();
				   $event['event_end'] = "$event_end_date $event_end_time";
				   $res=eme_db_update_event($event, $existing_event['event_id'], 1, $day_difference); 
				   if ($res)
					   eme_event_store_cf_answers($existing_event['event_id']);
			   }
			   // we handled a specific day, so remove it from the array
			   unset($matching_days[$key]);
			   // mark that we found a match
			   $update_done=1;
			   $count++;
			   // skip the rest of the loop, better for efficiency
			   continue;
		   }
	   }
	   if (!$update_done) {
		   eme_db_delete_event($existing_event['event_id'],1);
	   }
   }
   // Doing step 2
   foreach($matching_days as $day) {
      $event['event_start'] = "$day $event_start_time";
      $eme_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
      $day_difference = $eme_date_obj_orig->getDifferenceInDays($eme_date_obj);
      $eme_date_obj->addDays($recurrence['event_duration']-1);
      $event_end_date = $eme_date_obj->getDate();
      $event['event_end'] = "$event_end_date $event_end_time";
      $count++;
      eme_db_insert_event($event,1,$day_difference);
   }
   return $count;
}

function eme_db_delete_recurrence($recurrence_id) {
   global $wpdb;
   if (has_action('eme_delete_recurrence_action')) {
      $recurrence=eme_get_recurrence($recurrence_id);
      $event=eme_get_event(eme_get_recurrence_first_eventid($recurrence_id));
      if (!empty($event))
	      do_action('eme_delete_recurrence_action',$event,$recurrence);
   }

   $recurrence_table = $wpdb->prefix.RECURRENCE_TBNAME;
   $sql = $wpdb->prepare("DELETE FROM $recurrence_table WHERE recurrence_id = %d",$recurrence_id);
   $wpdb->query($sql);
   eme_trash_events_for_recurrence_id($recurrence_id);
   return true;
}

function eme_trash_events_for_recurrence_id($recurrence_id) {
   $ids_arr=eme_get_recurrence_eventids($recurrence_id);
   if (!empty($ids_arr)) {
	   $ids=join(',',$ids_arr);
	   eme_trash_events($ids);
   }
}

function eme_get_recurrence_first_eventid($recurrence_id) {
   global $wpdb;
   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   $sql = $wpdb->prepare("SELECT event_id FROM $events_table WHERE recurrence_id = %d AND event_status !=%d ORDER BY event_start ASC LIMIT 1",$recurrence_id,EME_EVENT_STATUS_TRASH);
   return $wpdb->get_var($sql);
}

function eme_get_recurrence_eventids($recurrence_id,$future_only=0) {
   global $wpdb, $eme_timezone;
   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   if ($future_only) {
      $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
      $today = $eme_date_obj->format('Y-m-d');
      $sql = $wpdb->prepare("SELECT event_id FROM $events_table WHERE recurrence_id = %d AND event_start > %s ORDER BY event_start ASC",$recurrence_id,$today);
   } else {
      $sql = $wpdb->prepare("SELECT event_id FROM $events_table WHERE recurrence_id = %d ORDER BY event_start ASC",$recurrence_id);
   }
   return $wpdb->get_col($sql);
}

function eme_get_recurrence_desc($recurrence_id) {
   global $wpdb, $eme_timezone;
   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   $recurrence_table = $wpdb->prefix.RECURRENCE_TBNAME;
   $sql = $wpdb->prepare("SELECT * FROM $recurrence_table WHERE recurrence_id = %d",$recurrence_id);
   $recurrence = $wpdb->get_row($sql, ARRAY_A);

   $weekdays_name = array(__('Monday'),__('Tuesday'),__('Wednesday'),__('Thursday'),__('Friday'),__('Saturday'),__('Sunday'));
   $monthweek_name = array('1' => __('the first %s of the month', 'events-made-easy'),'2' => __('the second %s of the month', 'events-made-easy'), '3' => __('the third %s of the month', 'events-made-easy'), '4' => __('the fourth %s of the month', 'events-made-easy'), '5' => __('the fifth %s of the month', 'events-made-easy'), '-1' => __('the last %s of the month', 'events-made-easy'));
   $output = sprintf (__('From %1$s to %2$s', 'events-made-easy'),  eme_localized_date($recurrence['recurrence_start_date'],$eme_timezone), eme_localized_date($recurrence['recurrence_end_date'],$eme_timezone)).", ";
   if ($recurrence['recurrence_freq'] == 'daily')  {
      $freq_desc =__('everyday', 'events-made-easy');
      if ($recurrence['recurrence_interval'] > 1 ) {
         $freq_desc = sprintf (__("every %s days", 'events-made-easy'), $recurrence['recurrence_interval']);
      }
   }
   elseif ($recurrence['recurrence_freq'] == 'weekly')  {
      if (!$recurrence['recurrence_byday']) {
         # no weekdays given for the recurrence, so we use the
         # day of the week of the startdate as reference
         $recurrence['recurrence_byday']= eme_localized_date($recurrence['recurrence_start_date'],$eme_timezone,'w');
         # Sunday is 7, not 0
         if ($recurrence['recurrence_byday']==0)
            $recurrence['recurrence_byday']=7; 
      }
      $weekday_array = explode(",", $recurrence['recurrence_byday']);
      $natural_days = array();
      foreach($weekday_array as $day)
         array_push($natural_days, $weekdays_name[$day-1]);
      $and_string=__(" and ",'events-made-easy');
      $output .= implode($and_string, $natural_days);
      $freq_desc =", ".__('every week', 'events-made-easy');
      if ($recurrence['recurrence_interval'] > 1 ) {
         $freq_desc = ", ".sprintf (__("every %s weeks", 'events-made-easy'), $recurrence['recurrence_interval']);
      }
   } 
   elseif ($recurrence['recurrence_freq'] == 'monthly')  {
      if (!$recurrence['recurrence_byday']) {
         # no monthday given for the recurrence, so we use the
         # day of the month of the startdate as reference
         $recurrence['recurrence_byday']= eme_localized_date($recurrence['recurrence_start_date'],$eme_timezone,'e');
      }
      $weekday_array = explode(",", $recurrence['recurrence_byday']);
      $natural_days = array();
      foreach($weekday_array as $day)
         array_push($natural_days, $weekdays_name[$day-1]);
      $and_string=__(" and ",'events-made-easy');
      $freq_desc = sprintf (($monthweek_name[$recurrence['recurrence_byweekno']]), implode($and_string, $natural_days));
      $freq_desc =", ".__('every month', 'events-made-easy');
      if ($recurrence['recurrence_interval'] > 1 ) {
         $freq_desc .= ", ".sprintf (__("every %s months",'events-made-easy'), $recurrence['recurrence_interval']);
      }
   } elseif ($recurrence['recurrence_freq'] == 'specific')  {
      $specific_days = eme_get_recurrence_days($recurrence);
      $natural_days = array();
      $eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
      foreach ($specific_days as $day) {
	      $date_obj = new ExpressiveDate($day,$eme_timezone);
	      if ($date_obj < $eme_date_obj_now) {
		      $natural_days[]='<s>'.eme_localized_date($day,$eme_timezone).'</s>';
	      } else {
		      $natural_days[]=eme_localized_date($day,$eme_timezone);
	      }
      }
      if (eme_is_admin_request()) {
         //return __("Specific days",'events-made-easy');
         return implode("<br />", $natural_days);
      } else {
         return implode(", ", $natural_days);
      }
   } else {
      $freq_desc = "";
   }
   $output .= $freq_desc;
   return  $output;
}

function eme_recurrence_count($recurrence_id) {
   # return the number of events for an recurrence
   global $wpdb;
   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   $sql = $wpdb->prepare("SELECT COUNT(*) FROM $events_table WHERE recurrence_id = %d",$recurrence_id);
   return $wpdb->get_var($sql);
}

add_action( 'wp_ajax_eme_recurrences_list', 'eme_ajax_recurrences_list' );
add_action( 'wp_ajax_eme_manage_recurrences', 'eme_ajax_manage_recurrences' );

function eme_ajax_recurrences_list() {
   global $wpdb, $eme_timezone, $eme_plugin_url;

   $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
   $today = $eme_date_obj->getDate();
   $event_status_array = eme_status_array ();

   $start= (isset($_REQUEST['jtStartIndex'])) ? intval($_REQUEST['jtStartIndex']) : 0;
   $pagesize= (isset($_REQUEST['jtPageSize'])) ? intval($_REQUEST['jtPageSize']) : 10;
   $scope = (isset($_REQUEST['scope'])) ? eme_sanitize_request($_REQUEST['scope']) : 'ongoing';
   $search_name = isset($_REQUEST['search_name']) ? eme_sanitize_request($_REQUEST['search_name']) : '';
   $search_start_date = isset($_REQUEST['search_start_date']) ? esc_sql($_REQUEST['search_start_date']) : '';
   $search_end_date = isset($_REQUEST['search_end_date']) ? esc_sql($_REQUEST['search_end_date']) : '';

   $where ='';
   $where_arr = array();
   if(!empty($search_name)) {
      $where_arr[] = "event_name like '%".$search_name."%'";
   }
   if (!empty($search_start_date) && eme_is_date($search_start_date) &&
       !empty($search_end_date) && eme_is_date($search_end_date)) {
      $where_arr[] = "recurrence_start_date >= '$search_start_date'";
      $where_arr[] = "recurrence_end_date <= '$search_end_date'";
   } elseif (!empty($search_start_date) && eme_is_date($search_start_date)) {
      $where_arr[] = "recurrence_start_date = '$search_start_date'";
   } elseif (!empty($search_end_date) && eme_is_date($search_end_date)) {
      $where_arr[] = "recurrence_end_date = '$search_end_date'";
   } elseif (!empty($scope)) {
           if ($scope=="ongoing") {
                   $where_arr[] = "recurrence_end_date >= '$today'";
           } elseif ($scope=="past") {
                   $where_arr[] = "recurrence_end_date < '$today'";
           }
   }
   if ($where_arr)
      $where = "WHERE ".implode(" AND ",$where_arr);

   $recurrence_table = $wpdb->prefix.RECURRENCE_TBNAME;
   if (!empty($search_name)) {
	   $events_table = $wpdb->prefix.EVENTS_TBNAME;
	   $count_sql = "SELECT COUNT(recurrence_id) FROM $recurrence_table NATURAL JOIN ( SELECT * FROM $events_table WHERE recurrence_id >0 GROUP BY recurrence_id ) as event $where";
	   $recurrences_count=$wpdb->get_var ($count_sql);
	   $sql = "SELECT * FROM $recurrence_table NATURAL JOIN ( SELECT * FROM $events_table WHERE recurrence_id >0 GROUP BY recurrence_id ) as event $where LIMIT $start,$pagesize";
   } else {
	   $count_sql = "SELECT COUNT(recurrence_id) FROM $recurrence_table $where";
	   $recurrences_count=$wpdb->get_var ($count_sql);
	   $sql = "SELECT * FROM $recurrence_table $where LIMIT $start,$pagesize";
   }
   $recurrences=$wpdb->get_results ( $sql, ARRAY_A );

   $rows=array();
   foreach ($recurrences as $recurrence) {
      // due to our select with natural join, $recurrence contains everything for an event too (except unserialized properties
      // for ease of code, we'll set $event=$recurrence and use $event where logical
      if (empty($search_name)) {
	      $event=eme_get_event(eme_get_recurrence_first_eventid($recurrence['recurrence_id']));
      } else {
	      $event=eme_get_extra_event_data($recurrence);
      }
      // if no event info, continue
      if (empty($event))
	      continue;
      if (!$recurrence['event_duration']) {
	      // older recurrences did not have event_duration
	      $event_start_obj=new ExpressiveDate($event['event_start'],$eme_timezone);
	      $event_end_obj=new ExpressiveDate($event['event_end'],$eme_timezone);
	      $duration_days=abs($event_end_obj->getDifferenceInDays($event_start_obj))+1;
      } else {
	      $duration_days=$recurrence['event_duration'];
      }
      if ($duration_days>1) {
	    $day_string=__( 'days', 'events-made-easy');
      } else {
	    $day_string=__( 'day', 'events-made-easy');
      }
      if ($event['event_properties']['all_day']) {
	      $duration_string=sprintf('%d %s',$duration_days,$day_string);
      } else {
	      $duration_string=sprintf('%d %s, %s-%s',$duration_days,$day_string,eme_localized_time($event['event_start'],$eme_timezone),eme_localized_time($event['event_end'],$eme_timezone));
      }

      $record=array();
      $record['recurrence_id'] = $recurrence['recurrence_id'];
      $record['event_name'] = "<strong><a href='".wp_nonce_url(admin_url("admin.php?page=eme-manager&amp;eme_admin_action=edit_recurrence&amp;recurrence_id=".$recurrence['recurrence_id']),'eme_admin','eme_admin_nonce')."' title='".__('Edit recurrence','events-made-easy')."'>".eme_trans_esc_html($event['event_name'])."</a></strong>";
      $record['copy'] = "<a href='".admin_url("admin.php?page=eme-manager&amp;eme_admin_action=duplicate_recurrence&amp;recurrence_id=".$recurrence['recurrence_id'])."' title='".__('Duplicate this recurrence','events-made-easy')."'><img src='".$eme_plugin_url."images/copy_24.png'/></a>";
      if ($event['event_rsvp']) {
	      $total_seats = eme_get_total($event['event_seats']);
	      if (eme_is_multi($event['event_seats'])) {
		      $total_seats_string = $total_seats.' ('.$event['event_seats'].')';
	      } else {
		      $total_seats_string = $total_seats;
	      }
	      $record['event_name'] .= '<br />'. __('Max: ','events-made-easy').$total_seats_string;
              if (empty($event['price']))
                      $record['eventprice']=__('Free','events-made-easy');
              else
                      $record['eventprice']=eme_convert_multi2br(eme_localized_price($event['price'],$event['currency']));
      } else {
	      $record['eventprice'] = "";
      }

      if (!empty($event['event_category_ids'])) {
            $categories = explode(',', $event['event_category_ids']);
            $record['event_name'] .= "<br /><span class='eme_categories_small' title='".__('Category','events-made-easy')."'>";
            $cat_names=array();
            foreach($categories as $cat){
               $category = eme_get_category($cat);
               if($category)
                  $cat_names[] = eme_trans_esc_html($category['category_name']);
            }
            $record['event_name'] .= implode(', ',$cat_names);
            $record['event_name'] .= "</span>";
      }
      $location=eme_get_location($event['location_id']);
      if (empty($location))
	      $location=eme_new_location();
      if (empty($location['location_name'])) {
              $record['location_name']="";
      } else {
              $record['location_name']= "<a href='".admin_url("admin.php?page=eme-locations&amp;eme_admin_action=edit_location&amp;location_id=".$location['location_id'])."' title='".__('Edit location','events-made-easy')."'>".eme_trans_esc_html($location['location_name'])."</a>";
              if (!$location['location_latitude'] && !$location['location_longitude'] && get_option('eme_map_is_active') && !$event['location_properties']['online_only'] ) {
                      $record['location_name'] .= "&nbsp;<img style='vertical-align: middle;' src='".$eme_plugin_url."images/warning.png' alt='warning' title='".__('Location map coordinates are empty! Please edit the location to correct this, otherwise it will not show correctly on your website.','events-made-easy')."'>";
              }
      }

      if (!empty($location['location_address1']) || !empty($location['location_address2']))
         $record['location_name'] .= "<br />". eme_trans_esc_html($location['location_address1']) ." ".eme_trans_esc_html($location['location_address2']);
      if (!empty($location['location_city']) || !empty($location['location_state']) || !empty($location['location_zip']) || !empty($location['location_country']))
         $record['location_name'] .= "<br />". eme_trans_esc_html($location['location_city']) ." ".eme_trans_esc_html($location['location_state'])." ".eme_trans_esc_html($location['location_zip'])." ".eme_trans_esc_html($location['location_country']);
      if (!$location['location_properties']['online_only'] && !empty($location['location_url'])) {
	      $record['location_name'] .= "<br />".eme_trans_esc_html($location['location_url']);
      }

      if (isset ($event_status_array[$event['event_status']])) {
         $record['event_status'] = $event_status_array[$event['event_status']];
      }
      $record['recinfo'] = eme_get_recurrence_desc ( $event['recurrence_id'] );
      $record['rec_singledur'] = $duration_string;
      $rows[]=$record;
   }

   $ajaxResult=array();
   $ajaxResult['Result'] = "OK";
   $ajaxResult['TotalRecordCount'] = $recurrences_count;
   $ajaxResult['Records'] = $rows;
   print json_encode($ajaxResult);
   wp_die();
}

function eme_ajax_manage_recurrences() {
   check_ajax_referer('eme_admin','eme_admin_nonce');
   $ajaxResult=array();
   if (isset($_REQUEST['do_action'])) {
     $do_action=eme_sanitize_request($_REQUEST['do_action']);
     $rec_new_start_date=eme_sanitize_request($_REQUEST['rec_new_start_date']);
     $rec_new_end_date=eme_sanitize_request($_REQUEST['rec_new_end_date']);
     $ids=$_REQUEST['recurrence_id'];
     $ids_arr=explode(',',$ids);
     if (!eme_array_integers($ids_arr) || !current_user_can( get_option('eme_cap_edit_events'))) {
          $ajaxResult['Result'] = "Error";
          $ajaxResult['Message'] = __('Access denied!','events-made-easy');
          print json_encode($ajaxResult);
          wp_die();
     }

     switch ($do_action) {
         case 'deleteRecurrences':
              eme_ajax_action_recurrences_delete($ids_arr);
              break;
         case 'publicRecurrences':
              eme_ajax_action_recurrences_status($ids_arr,EME_EVENT_STATUS_PUBLIC);
              break;
         case 'privateRecurrences':
              eme_ajax_action_recurrences_status($ids_arr,EME_EVENT_STATUS_PRIVATE);
              break;
         case 'draftRecurrences':
              eme_ajax_action_recurrences_status($ids_arr,EME_EVENT_STATUS_DRAFT);
              break;
         case 'extendRecurrences':
              eme_ajax_action_recurrences_extend($ids_arr,$rec_new_start_date,$rec_new_end_date);
              break;
     }
   } else {
      $ajaxResult['Result'] = "Error";
      $ajaxResult['Message'] = __('No action defined!','events-made-easy');
      print json_encode($ajaxResult);
   }
   wp_die();
}

function eme_ajax_action_recurrences_delete($ids_arr) {
   foreach ( $ids_arr as $recurrence_id ) {
	   eme_db_delete_recurrence ($recurrence_id);
   }
   $ajaxResult=array();
   $ajaxResult['Result'] = "OK";
   $ajaxResult['Message'] = __('Recurrences deleted and events moved to trash','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_recurrences_status($ids_arr,$status) {
   foreach ($ids_arr as $recurrence_id) {
	   $events_ids=eme_get_recurrence_eventids($recurrence_id);
	   eme_change_event_status($events_ids,$status);
   }
   $ajaxResult=array();
   $ajaxResult['Result'] = "OK";
   $ajaxResult['Message'] = __('Recurrences status updated','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_recurrences_extend($ids_arr,$rec_new_start_date,$rec_new_end_date) {
   foreach ($ids_arr as $recurrence_id) {
	   $recurrence=eme_get_recurrence($recurrence_id);
	   $event=eme_get_event(eme_get_recurrence_first_eventid($recurrence_id));
	   if (!empty($event)) {
		   $recurrence['recurrence_end_date']=$rec_new_end_date;
		   if (!eme_is_empty_date($rec_new_start_date)) {
			   $recurrence['recurrence_start_date']=$rec_new_start_date;
			   eme_db_update_recurrence ($recurrence,$event);
		   } else {
			   // we add the 1 as param so eme_db_update_recurrence knows it is for changing end dates only and can skip some things
			   eme_db_update_recurrence ($recurrence,$event,1);
		   }
		   wp_cache_delete("eme_event ".$event['event_id']);
		   unset($recurrence);unset($event);
	   }
   }
   $ajaxResult=array();
   $ajaxResult['Result'] = "OK";
   $ajaxResult['Message'] = __('End date adjusted for the selected recurrences','events-made-easy');
   print json_encode($ajaxResult);
}
?>
