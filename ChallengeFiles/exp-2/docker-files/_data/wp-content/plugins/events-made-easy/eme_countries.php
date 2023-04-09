<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eme_new_country() {
   $country = array(
   'alpha_2' => '',
   'alpha_3' => '',
   'num_3' => '',
   'name' => '',
   'lang' => ''
   );
   return $country;
}

function eme_new_state() {
   $state = array(
   'code' => '',
   'name' => '',
   'country_id' => 0
   );
   return $state;
}

function eme_countries_page() {      
   
   if (!current_user_can( get_option('eme_cap_settings')) && (isset($_GET['eme_admin_action']) || isset($_POST['eme_admin_action']))) {
      $message = __('You have no right to manage discounts!','events-made-easy');
      eme_countries_main_layout($message);
      return;
   }
  
   $message = '';
   $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

   // handle possible ations
   if (isset($_POST['eme_admin_action'])) {
      check_admin_referer('eme_admin','eme_admin_nonce');
      if ($_POST['eme_admin_action'] == "do_importcountries" && isset($_FILES['eme_csv']) && current_user_can(get_option('eme_cap_cleanup'))) {
	 $inserted=0;
	 $errors=0;
	 $error_msg='';
	 //validate whether uploaded file is a csv file
         if (!empty($_FILES['eme_csv']['name']) && in_array($_FILES['eme_csv']['type'],$csvMimes)) {
		 if (is_uploaded_file($_FILES['eme_csv']['tmp_name'])) {
			 $handle = fopen($_FILES['eme_csv']['tmp_name'], "r");
			 if (!$handle) {
				 $message = __('Problem accessing the uploaded the file, maybe some security issue?','events-made-easy');
			 } else {
				 // BOM as a string for comparison.
				 $bom = "\xef\xbb\xbf";
				 // Progress file pointer and get first 3 characters to compare to the BOM string.
				 if (fgets($handle, 4) !== $bom) {
					 // BOM not found - rewind pointer to start of file.
					 rewind($handle);
				 }
				 if (!eme_is_empty_string($_POST['enclosure'])) {
					 $enclosure=eme_sanitize_request($_POST['enclosure']);
					 $enclosure=substr($enclosure, 0, 1);
				 } else {
					 $enclosure='"';
				 }
				 if (!eme_is_empty_string($_POST['delimiter'])) {
					 $delimiter=eme_sanitize_request($_POST['delimiter']);
				 } else {
					 $delimiter=',';
				 }
				 // first line is the column headers
				 $headers = array_map('strtolower', fgetcsv($handle,0,$delimiter,$enclosure));
				 // check required columns
				 if (!in_array('alpha_2',$headers)||!in_array('name',$headers)) {
					 $message = __("Not all required fields present.",'events-made-easy');
				 } else {
					 while (($row = fgetcsv($handle,0,$delimiter,$enclosure)) !== FALSE) {
						 $country = array_combine($headers, $row);
						 $res = eme_db_insert_country($country);
						 if ($res) {
							 $inserted++;
						 } else {
							 $errors++;
							 $error_msg.='<br />'.eme_esc_html(sprintf(__('Not imported: %s','events-made-easy'),implode(',',$row)));
						 }
					 }
					 $message = sprintf(__('Import finished: %d inserts, %d errors','events-made-easy'),$inserted,$errors);
					 if ($errors) $message.='<br />'.$error_msg;
				 }
				 fclose($handle);
			 }
		 } else {
			 $message = __('Problem detected while uploading the file','events-made-easy');
		 }
	 } else {
		 $message = sprintf(__('No CSV file detected: %s','events-made-easy'),$_FILES['eme_csv']['type']);
	 }
      } elseif ($_POST['eme_admin_action'] == "do_importstates" && isset($_FILES['eme_csv']) && current_user_can(get_option('eme_cap_cleanup'))) {
	 $inserted=0;
	 $errors=0;
	 $error_msg='';
	 //validate whether uploaded file is a csv file
         if (!empty($_FILES['eme_csv']['name']) && in_array($_FILES['eme_csv']['type'],$csvMimes)) {
		 if (is_uploaded_file($_FILES['eme_csv']['tmp_name'])) {
			 $handle = fopen($_FILES['eme_csv']['tmp_name'], "r");
			 if (!$handle) {
                                 $message = __('Problem accessing the uploaded the file, maybe some security issue?','events-made-easy');
			 } else {
				 // BOM as a string for comparison.
				 $bom = "\xef\xbb\xbf";
				 // Progress file pointer and get first 3 characters to compare to the BOM string.
				 if (fgets($handle, 4) !== $bom) {
					 // BOM not found - rewind pointer to start of file.
					 rewind($handle);
				 }
				 if (!eme_is_empty_string($_POST['enclosure'])) {
					 $enclosure=eme_sanitize_request($_POST['enclosure']);
					 $enclosure=substr($enclosure, 0, 1);
				 } else {
					 $enclosure='"';
				 }
				 if (!eme_is_empty_string($_POST['delimiter'])) {
					 $delimiter=eme_sanitize_request($_POST['delimiter']);
				 } else {
					 $delimiter=',';
				 }

				 // first line is the column headers
				 $headers = array_map('strtolower', fgetcsv($handle,0,$delimiter,$enclosure));
				 // check required columns
				 if (!in_array('code',$headers)||!in_array('name',$headers)||!in_array('country_id',$headers)) {
					 $message = __("Not all required fields present.",'events-made-easy');
				 } else {
					 while (($row = fgetcsv($handle,0,$delimiter,$enclosure)) !== FALSE) {
						 $state = array_combine($headers, $row);
						 $res = eme_db_insert_state($state);
						 if ($res) {
							 $inserted++;
						 } else {
							 $errors++;
							 $error_msg.='<br />'.eme_esc_html(sprintf(__('Not imported: %s','events-made-easy'),implode(',',$row)));
						 }
					 }
					 $message = sprintf(__('Import finished: %d inserts, %d errors','events-made-easy'),$inserted,$errors);
					 if ($errors) $message.='<br />'.$error_msg;
				 }
				 fclose($handle);
			 }
		 } else {
			 $message = __('Problem detected while uploading the file','events-made-easy');
		 }
	 } else {
		 $message = sprintf(__('No CSV file detected: %s','events-made-easy'),$_FILES['eme_csv']['type']);
         }

      } elseif ($_POST['eme_admin_action'] == "do_editstate" ) {
              if (!empty($_POST['id'])) {
                      $state_id=intval($_POST['id']);
                      $state=eme_get_state($state_id);
              } else {
                      $state_id=0;
                      $state=eme_new_state();
              }
              foreach ($state as $key=>$val) {
                      if (isset($_POST[$key]))
                              $state[$key]=eme_sanitize_request($_POST[$key]);
              }
	      $validation_result = eme_validate_state($state);
	      if (!empty($validation_result)) {
		      $message = sprintf(__("Problem detected: %s, please correct try again.", 'events-made-easy'), $validation_result);
	      } elseif ($state_id) {
                      $update_result = eme_db_update_state($state_id,$state);
                      if ($update_result !== false) {
                              $message = __("Successfully edited the state", 'events-made-easy');
                              if (get_option('eme_stay_on_edit_page')) {
                                      eme_states_edit_layout($state_id,$message);
                                      return;
                              }
                      } else {
                              $message = __("There was a problem editing the state, please try again.", 'events-made-easy');
                      }
              } else {
                      $new_id = eme_db_insert_state($state);
                      if ($new_id) {
                              $message = __("Successfully added the state", 'events-made-easy');
                              if (get_option('eme_stay_on_edit_page')) {
                                      eme_states_edit_layout($new_id,$message);
                                      return;
                              }
                      } else {
                              $message = __("There was a problem adding the state, please try again.", 'events-made-easy');
                      }
              }
              eme_manage_states_layout($message);
              return;
      } elseif ($_POST['eme_admin_action'] == "do_editcountry" ) {
              if (!empty($_POST['id'])) {
                      $country_id=intval($_POST['id']);
                      $country=eme_get_country($country_id);
              } else {
                      $country_id=0;
                      $country=eme_new_country();
              }
              foreach ($country as $key=>$val) {
                      if (isset($_POST[$key]))
                              $country[$key]=eme_sanitize_request($_POST[$key]);
              }
	      // the language POST var has a different name ('language', not 'lang') to avoid a conflict with qtranslate-xt that
	      //    also checks for $_POST['lang'] and redirects to that lang, which is of course not the intention when editing a country
	      if (isset($_POST['language']))
		      $country['lang']=eme_sanitize_request($_POST['language']);
	      $validation_result = eme_validate_country($country);
	      if (!empty($validation_result)) {
		      $message = sprintf(__("Problem detected: %s, please correct try again.", 'events-made-easy'), $validation_result);
	      } elseif ($country_id) {
                      $update_result = eme_db_update_country($country_id,$country);
                      if ($update_result !== false) {
                              $message = __("Successfully edited the country", 'events-made-easy');
                              if (get_option('eme_stay_on_edit_page')) {
                                      eme_countries_edit_layout($country_id,$message);
                                      return;
                              }
                      } else {
                              $message = __("There was a problem editing the country, please try again.", 'events-made-easy');
                      }
              } else {
                      $new_id = eme_db_insert_country($country);
                      if ($new_id) {
                              $message = __("Successfully added the country", 'events-made-easy');
                              if (get_option('eme_stay_on_edit_page')) {
                                      eme_countries_edit_layout($new_id,$message);
                                      return;
                              }
                      } else {
                              $message = __("There was a problem adding the country, please try again.", 'events-made-easy');
                      }
              }
              eme_manage_countries_layout($message);
              return;
      }
   }
   
   // now that we handled possible ations, let's show the wanted screen
   if (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action'] == "add_country") {
      check_admin_referer('eme_admin','eme_admin_nonce');
      eme_countries_edit_layout();
      return;
   }
   if (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action'] == "add_state") {
      check_admin_referer('eme_admin','eme_admin_nonce');
      eme_states_edit_layout();
      return;
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == "edit_country") {
      check_admin_referer('eme_admin','eme_admin_nonce');
      eme_countries_edit_layout();
      return;
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == "edit_state") {
      check_admin_referer('eme_admin','eme_admin_nonce');
      eme_states_edit_layout();
      return;
   }

   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == "countries") { 
      eme_manage_countries_layout($message);
      return;
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == "states") { 
      eme_manage_states_layout($message);
      return;
   }
   eme_countries_main_layout();
}

function eme_countries_main_layout($message='') {
   $nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
   $countries_destination = admin_url("admin.php?page=eme-countries&amp;eme_admin_action=countries");
   $states_destination = admin_url("admin.php?page=eme-countries&amp;eme_admin_action=states");
   $html = "
      <div class='wrap nosubsub'>\n
         <div id='icon-edit' class='icon32'>
            <br />
         </div>
         <h1>".__('Manage countries and states', 'events-made-easy')."</h1>
   ";
   if (!empty($message)) {
           $html .= '<div id="countries-message" class="eme-message-admin"><p>'.$message.'</p></div>';
   }

   $html .= "<p>".__('For personal info (people, members, event rsvp) EME allows you to use auto-completion on states, based on the country choice. However, since there are way too many states and languages, it is impossible to provide that list in EME itself. So if you want to use country and state info, you should enter the countries and states here.','events-made-easy')."</p>";

   $html .= "<p><b>".__('This is NOT used for event locations, only for personal info of people.', 'events-made-easy')."</b></p>";
   $html .= "<h2>".__('Manage countries', 'events-made-easy')."</h2>";
   $html .= "<a href='$countries_destination'>".__("Manage countries",'events-made-easy')."</a><br />";
   $html .= "<h2>".__('Manage states', 'events-made-easy')."</h2>";
   $countries_count=eme_get_countries_count();
   if ($countries_count>0)
	   $html .= "<a href='$states_destination'>".__("Manage states",'events-made-easy')."</a><br />";
   else
	   $html .= __("There are no countries defined yet. First define some countries, then you can manage the states.",'events-made-easy')."<br />";
   echo $html;  
}

function eme_manage_countries_layout($message="") {
   global $plugin_page, $eme_plugin_url;
   $lang = eme_detect_lang();
   $nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
   if (empty($message))
           $hidden_style="display:none;";
   else
           $hidden_style="";

   $countries_count=eme_get_countries_count();
   if ($countries_count==1) {
	   $countries=eme_get_countries();
	   $country=$countries[0];
	   if (!empty($country['lang']) && $country['lang']!=$lang) {
		   $message.=sprintf(__("There's only one country defined, but the language of the country is not empty and doesn't match the Wordpress detected language (%s), meaning it won't show up when editing people. Either add extra countries or correct the language of this country.",'events-made-easy'),$lang);
	   }
   }

   ?>
      <div class="wrap nosubsub">
       <div id="poststuff">
         <div id="icon-edit" class="icon32">
            <br />
         </div>
         
         <div id="countries-message" class="notice is-dismissible eme-message-admin" style="<?php echo $hidden_style; ?>">
               <p><?php echo $message; ?></p>
         </div>

         <h1><?php _e('Add a new country', 'events-made-easy') ?></h1>
         <div class="wrap">
         <form method="post" action="<?php echo admin_url("admin.php?page=$plugin_page"); ?>">
            <?php echo $nonce_field; ?>
            <input type="hidden" name="eme_admin_action" value="add_country" />
            <input type="submit" class="button-primary" name="submit" value="<?php _e('Add country', 'events-made-easy');?>" />
         </form>
         </div>

         <h1><?php _e('Manage countries', 'events-made-easy') ?></h1>

   <?php if (current_user_can(get_option('eme_cap_cleanup'))) { ?>
   <span class="eme_import_form_img">
   <?php _e('Click on the icon to show the import form','events-made-easy'); ?>
   <img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="div_import" style="cursor: pointer; vertical-align: middle; ">
   </span>
   <div id='div_import' style='display:none;'>
   <form id='countries-import' method='post' enctype='multipart/form-data' action='#'>
   <?php echo $nonce_field; ?>
   <input type="file" name="eme_csv" />
   <?php _e('Delimiter:','events-made-easy'); ?>
   <input type="text" size=1 maxlength=1 name="delimiter" value=',' required='required' />
   <?php _e('Enclosure:','events-made-easy'); ?>
   <input required="required" type="text" size=1 maxlength=1 name="enclosure" value='"' required='required' />
   <input type="hidden" name="eme_admin_action" value="do_importcountries" />
   <input type="submit" value="<?php _e ( 'Import','events-made-easy'); ?>" name="doaction" id="doaction" class="button-primary action" />
   <?php _e('If you want, use this to import countries into the database', 'events-made-easy'); ?>
   </form>
   </div>
   <br />
   <?php echo sprintf(__('See <a href="%s">here</a> for more info on country codes', 'events-made-easy'),'https://en.wikipedia.org/wiki/ISO_3166-1'); ?>
   <br />
   <?php _e('The language should correspond to one of the wordpress languages you want to support, or leave it empty as a default or fallback. Some examples are: nl, fr, en, de', 'events-made-easy'); ?>
   <br />
   <?php } ?>
   <br />
   <form id='countries-form' action="#" method="post">
   <?php echo $nonce_field; ?>
   <select id="eme_admin_action" name="eme_admin_action">
   <option value="" selected="selected"><?php _e ( 'Bulk Actions' , 'events-made-easy'); ?></option>
   <option value="deleteCountries"><?php _e ( 'Delete selected countries','events-made-easy'); ?></option>
   </select>
   <button id="CountriesActionsButton" class="button-secondary action"><?php _e ( 'Apply' , 'events-made-easy'); ?></button>
   <span class="rightclickhint">
      <?php _e('Hint: rightclick on the column headers to show/hide columns','events-made-easy'); ?>
   </span>
   <div id="CountriesTableContainer"></div>
   </form>
      </div> 
   </div>
   <?php
}

function eme_manage_states_layout($message="") {
   global $plugin_page, $eme_plugin_url;
   $nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
   if (empty($message))
           $hidden_style="display:none;";
   else
           $hidden_style="";
   ?>
      <div class="wrap nosubsub">
       <div id="poststuff">
         <div id="icon-edit" class="icon32">
            <br />
         </div>
         
	 <div id="states-message" class="notice is-dismissible eme-message-admin" style="<?php echo $hidden_style; ?>">
               <p><?php echo $message; ?></p>
         </div>

         <h1><?php _e('Add a new state', 'events-made-easy') ?></h1>
         <div class="wrap">
         <form method="post" action="<?php echo admin_url("admin.php?page=$plugin_page"); ?>">
            <?php echo $nonce_field; ?>
            <input type="hidden" name="eme_admin_action" value="add_state" />
            <input type="submit" class="button-primary" name="submit" value="<?php _e('Add state', 'events-made-easy');?>" />
         </form>
         </div>

         <h1><?php _e('Manage states', 'events-made-easy') ?></h1>

   <?php if (current_user_can(get_option('eme_cap_cleanup'))) { ?>
   <span class="eme_import_form_img">
   <?php _e('Click on the icon to show the import form','events-made-easy'); ?>
   <img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="div_import" style="cursor: pointer; vertical-align: middle; ">
   </span>
   <div id='div_import' style='display:none;'>
   <form id='states-import' method='post' enctype='multipart/form-data' action='#'>
   <?php echo $nonce_field; ?>
   <input type="file" name="eme_csv" />
   <?php _e('Delimiter:','events-made-easy'); ?>
   <input type="text" size=1 maxlength=1 name="delimiter" value=',' required='required' />
   <?php _e('Enclosure:','events-made-easy'); ?>
   <input required="required" type="text" size=1 maxlength=1 name="enclosure" value='"' required='required' />
   <input type="hidden" name="eme_admin_action" value="do_importstates" />
   <input type="submit" value="<?php _e ( 'Import','events-made-easy'); ?>" name="doaction" id="doaction" class="button-primary action" />
   <?php _e('If you want, use this to import states into the database', 'events-made-easy'); ?>
   </form>
   </div>
   <br />
   <?php echo sprintf(__('See <a href="%s">here</a> for more info on state codes', 'events-made-easy'),'https://wikipedia.org/wiki/ISO_3166-2'); ?>
   <?php _e('The code should consist of 2 letters. An example would be the code "WA" for "Washington, US"', 'events-made-easy'); ?>
   <br />
   <?php } ?>
   <br />
   <form id='states-form' action="#" method="post">
   <?php echo $nonce_field; ?>
   <select id="eme_admin_action" name="eme_admin_action">
   <option value="" selected="selected"><?php _e ( 'Bulk Actions' , 'events-made-easy'); ?></option>
   <option value="deleteStates"><?php _e ( 'Delete selected states','events-made-easy'); ?></option>
   </select>
   <button id="StatesActionsButton" class="button-secondary action"><?php _e ( 'Apply' , 'events-made-easy'); ?></button>
   <span class="rightclickhint">
      <?php _e('Hint: rightclick on the column headers to show/hide columns','events-made-easy'); ?>
   </span>
   <div id="StatesTableContainer"></div>
   </form>
   </div> 
   </div>
   <?php
}

function eme_states_edit_layout($state_id=0,$message = "") {
   global $plugin_page;

   $countries= eme_get_countries_lang();
   if (!empty($state_id)) {
	   $state = eme_get_state($state_id);
           $h1_string=__('Edit state', 'events-made-easy');
           $action_string=__('Update state', 'events-made-easy');
   } elseif (isset($_GET['id'])) {
           $state_id = intval($_GET['id']);
	   $state = eme_get_state($state_id);
           $h1_string=__('Edit state', 'events-made-easy');
           $action_string=__('Update state', 'events-made-easy');
   } else {
           $state_id=0;
           $state = eme_new_state();
           $h1_string=__('Create state', 'events-made-easy');
           $action_string=__('Add state', 'events-made-easy');
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

      <form name='edit_states' id='edit_states' method='post' action='".admin_url("admin.php?page=$plugin_page")."'>
      <input type='hidden' name='eme_admin_action' value='do_editstate' />
      <input type='hidden' name='id' value='".$state_id."' />
      $nonce_field
      <table class='form-table'>
            <tr class='form-field'>
               <th scope='row' style='vertical-align:top'><label for='name'>".__('State name', 'events-made-easy')."</label></th>
               <td><input name='name' id='name' required='required' type='text' value='".eme_esc_html($state['name'])."' size='40' /><br />
                 ".__('The name of the state', 'events-made-easy')."</td>
            </tr>
            <tr class='form-field'>
               <th scope='row' style='vertical-align:top'><label for='value'>".__('Code', 'events-made-easy')."</label></th>
	       <td><input name='code' id='code' type='text' value='".eme_esc_html($state['code'])."' size='40' />
               <br />".sprintf(__('See <a href="%s">here</a> for more info on state codes', 'events-made-easy'),'https://wikipedia.org/wiki/ISO_3166-2')."
               <br />".__('The code should consist of 2 letters. An example would be the code "WA" for "Washington, US"', 'events-made-easy')."
               </td>
            </tr>
            <tr class='form-field'>
               <th scope='row' style='vertical-align:top'><label for='type'>".__('Country', 'events-made-easy')."</label></th>
               <td>".eme_ui_select_key_value($state['country_id'],"country_id",$countries,'id','name')."
            </tr>
         </table>
      <p class='submit'><input type='submit' class='button-primary' name='submit' value='".$action_string."' /></p>
      </form>
   </div>
   ";  
   echo $layout;
}

function eme_countries_edit_layout($country_id=0,$message = "") {
   global $plugin_page;
   $lang = eme_detect_lang();

   if (!empty($country_id)) {
	   $country = eme_get_country($country_id);
           $h1_string=__('Edit country', 'events-made-easy');
           $action_string=__('Update country', 'events-made-easy');
   } elseif (isset($_GET['id'])) {
           $country_id = intval($_GET['id']);
	   $country = eme_get_country($country_id);
           $h1_string=__('Edit country', 'events-made-easy');
           $action_string=__('Update country', 'events-made-easy');
   } else {
           $country_id=0;
           $country = eme_new_country();
           $h1_string=__('Create country', 'events-made-easy');
           $action_string=__('Add country', 'events-made-easy');
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

      <form name='edit_countries' id='edit_countries' method='post' action='".admin_url("admin.php?page=$plugin_page")."'>
      <input type='hidden' name='eme_admin_action' value='do_editcountry' />
      <input type='hidden' name='id' value='".$country_id."' />
      $nonce_field
      <table class='form-table'>
            <tr class='form-field'>
               <th scope='row' style='vertical-align:top'><label for='name'>".__('Country name', 'events-made-easy')."</label></th>
               <td><input name='name' id='name' required='required' type='text' value='".eme_esc_html($country['name'])."' size='40' /><br />
                 ".__('The name of the country', 'events-made-easy')."</td>
            </tr>
            <tr class='form-field'>
               <th scope='row' style='vertical-align:top'><label for='alpha_2'>".__('Alpha-2', 'events-made-easy')."</label></th>
               <td><input name='alpha_2' id='alpha_2' required='required' type='text' value='".eme_esc_html($country['alpha_2'])."' size='40' /></td>
            </tr>
            <tr class='form-field'>
               <th scope='row' style='vertical-align:top'><label for='alpha_3'>".__('Alpha-3', 'events-made-easy')."</label></th>
               <td><input name='alpha_3' id='alpha_3' required='required' type='text' value='".eme_esc_html($country['alpha_3'])."' size='40' /></td>
            </tr>
            <tr class='form-field'>
               <th scope='row' style='vertical-align:top'><label for='num_3'>".__('Num-3', 'events-made-easy')."</label></th>
               <td><input name='num_3' id='num_3' required='required' type='text' value='".eme_esc_html($country['num_3'])."' size='40' /></td>
            </tr>
            <tr class='form-field'>
               <th scope='row' style='vertical-align:top'><label for='lang'>".__('Language', 'events-made-easy')."</label></th>
	       <td><input name='language' id='language' type='text' value='".eme_esc_html($country['lang'])."' size='40' />
               <br />".  __('The language should correspond to one of the wordpress languages you want to support, or leave it empty as a default or fallback. Some examples are: nl, fr, en, de', 'events-made-easy')."
               </td>
            </tr>
         </table>
         <br />". sprintf(__('See <a href="%s">here</a> for more info on country codes', 'events-made-easy'),'https://en.wikipedia.org/wiki/ISO_3166-1')."
      <p class='submit'><input type='submit' class='button-primary' name='submit' value='".$action_string."' /></p>
      </form>
   </div>
   ";  
   echo $layout;
}
function eme_get_states($country_id=0) {
   global $wpdb;
   $table = $wpdb->prefix.STATES_TBNAME;
   $countries_table = $wpdb->prefix.COUNTRIES_TBNAME;
   $sql = "SELECT state.*,country.lang FROM $table AS state LEFT JOIN $countries_table AS country ON state.country_id=country.id";
   return $wpdb->get_results($sql, ARRAY_A);
}
function eme_get_localized_states($country_code='') {
   global $wpdb;
   $table = $wpdb->prefix.STATES_TBNAME;
   $countries_table = $wpdb->prefix.COUNTRIES_TBNAME;
   $lang = eme_detect_lang();
   if (empty($country_code))
	   $sql = "SELECT state.*,country.name AS country,country.lang FROM $table AS state LEFT JOIN $countries_table AS country ON state.country_id=country.id WHERE lang='$lang'";
   else
	   $sql = $wpdb->prepare("SELECT state.*,country.name AS country,country.lang FROM $table AS state LEFT JOIN $countries_table AS country ON state.country_id=country.id WHERE country.alpha_2=%s AND country.lang='$lang'",$country_code);
   $res = $wpdb->get_results($sql, ARRAY_A);
   if (empty($res)) {
	   if (empty($country_code))
		   $sql = "SELECT state.*,country.name AS country,country.lang FROM $table AS state LEFT JOIN $countries_table AS country ON state.country_id=country.id WHERE lang=''";
	   else
		   $sql = $wpdb->prepare("SELECT state.*,country.name AS country,country.lang FROM $table AS state LEFT JOIN $countries_table AS country ON state.country_id=country.id WHERE country.alpha_2=%s AND country.lang=''",$country_code);
	   $res = $wpdb->get_results($sql, ARRAY_A);
   }
   return $res;
}

function eme_get_countries_count() {
   global $wpdb;
   $table = $wpdb->prefix.COUNTRIES_TBNAME;
   $sql = "SELECT count(*) FROM $table";
   return $wpdb->get_var($sql);
}
function eme_get_countries() {
   global $wpdb;
   $table = $wpdb->prefix.COUNTRIES_TBNAME;
   $sql = "SELECT * FROM $table";
   return $wpdb->get_results($sql, ARRAY_A);
}

function eme_get_localized_countries() {
   global $wpdb;
   $table = $wpdb->prefix.COUNTRIES_TBNAME;
   $lang = eme_detect_lang();
   $sql = "SELECT * FROM $table WHERE lang='$lang'";
   $res = $wpdb->get_results($sql, ARRAY_A);
   if (empty($res)) {
	   $sql = "SELECT * FROM $table WHERE lang=''";
	   $res = $wpdb->get_results($sql, ARRAY_A);
   }
   return $res;
}
function eme_get_countries_alpha2() {
   global $wpdb;
   $table = $wpdb->prefix.COUNTRIES_TBNAME;
   $sql = "SELECT alpha_2 FROM $table GROUP BY alpha_2";
   return $wpdb->get_col($sql);
}

function eme_validate_state($state) {
   return "";
}

function eme_validate_country($country) {
   if (strlen($country['alpha_2']) != 2) {
           return __("Incorrect alpha-2 code",'events-made-easy');
   }
   if ($country['alpha_3'] && strlen($country['alpha_3']) != 3) {
           return __("Incorrect alpha-3 code",'events-made-easy');
   }
   if ($country['num_3'] && strlen($country['num_3']) != 3) {
           return __("Incorrect num-3 code",'events-made-easy');
   }
   return "";
}

function eme_db_insert_country($line) {
	global $wpdb;
	$table = $wpdb->prefix.COUNTRIES_TBNAME;

	$country=eme_new_country();
	// we only want the columns that interest us
	$keys=array_intersect_key($line,$country);
	$new_line=array_merge($country,$keys);
        $new_line['alpha_2'] = strtoupper($new_line['alpha_2']);
        $new_line['alpha_3'] = strtoupper($new_line['alpha_3']);

	if ($wpdb->insert($table,$new_line) === false) {
		return false;
	} else {
		return $wpdb->insert_id;
	}
}

function eme_db_update_country($id,$line) {
	global $wpdb;
	$table = $wpdb->prefix.COUNTRIES_TBNAME;
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;

        $line['alpha_2'] = strtoupper($line['alpha_2']);
        $line['alpha_3'] = strtoupper($line['alpha_3']);
	// first get the existing country, compary the alpha_2 and change ALL countries and people from the old to new alpha_2 if not the same
	$country=eme_get_country($id);
	if ($country['alpha_2'] != $line['alpha_2']) {
		$sql = $wpdb->prepare("UPDATE $people_table SET country_code=%s WHERE country_code=%s",$line['alpha_2'],$country['alpha_2']); 
		$wpdb->query($sql);
		$sql = $wpdb->prepare("UPDATE $table SET alpha_2=%s WHERE alpha_2=%s",$line['alpha_2'],$country['alpha_2']); 
		$wpdb->query($sql);
	}
	if ($country['alpha_3'] != $line['alpha_3']) {
		$sql = $wpdb->prepare("UPDATE $table SET alpha_3=%s WHERE alpha_3=%s",$line['alpha_3'],$country['alpha_3']); 
		$wpdb->query($sql);
	}
	if ($country['num_3'] != $line['num_3']) {
		// we take into account that old values were not correctly prefixed by a 0
		$sql = $wpdb->prepare("UPDATE $table SET num_3=%03d WHERE num_3=%03d or num_3=%s",$line['num_3'],$country['num_3'], $country['num_3']); 
		$wpdb->query($sql);
	}

	// we only want the columns that interest us
	$keys=array_intersect_key($line,$country);
	$new_line=array_merge($country,$keys);
	$where = array ('id'=>$id);
	if (isset($new_line['id'])) unset($new_line['id']);

	if ($wpdb->update($table, $new_line, $where) === false) {
		return false;
	} else {
		return $id;
	}
}

function eme_db_insert_state($line) {
	global $wpdb;
	$table = $wpdb->prefix.STATES_TBNAME;

	$state=eme_new_state();
	// we only want the columns that interest us
	$keys=array_intersect_key($line,$state);
	$new_line=array_merge($state,$keys);
        $new_line['code'] = strtoupper($new_line['code']);
	if ($wpdb->insert($table,$new_line) === false) {
		return false;
	} else {
		return $wpdb->insert_id;
	}
}

function eme_db_update_state($id,$line) {
	global $wpdb;
	$table = $wpdb->prefix.STATES_TBNAME;
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
        $line['code'] = strtoupper($line['code']);

	// first get the existing state, compary the state code and change ALL relevant states and people from the old to new code if not the same
	$state=eme_get_state($id);
	if ($state['code'] != $line['code']) {
		$sql = $wpdb->prepare("UPDATE $people_table SET state_code=%s WHERE state_code=%s",$line['code'],$state['code']); 
		$wpdb->query($sql);
		$sql = $wpdb->prepare("UPDATE $table SET code=%s WHERE code=%s AND country_id=%d",$line['code'],$state['code'],$state['country_id']); 
		$wpdb->query($sql);
	}

	// we only want the columns that interest us
	$keys=array_intersect_key($line,$state);
	$new_line=array_merge($state,$keys);
	$where= array ('id'=>$id);
	if (isset($new_line['id'])) unset($new_line['id']);

	if ($wpdb->update($table, $new_line, $where) === false) {
		return false;
	} else {
		return $id;
	}
}

function eme_get_country($id) {
   global $wpdb;
   $table = $wpdb->prefix.COUNTRIES_TBNAME;
   $sql = $wpdb->prepare("SELECT * FROM $table WHERE id = %d",$id);
   $res = $wpdb->get_row($sql, ARRAY_A);
   $res['num_3']=sprintf("%03s",$res['num_3']);
   return $res;
}

function eme_get_country_name($code,$lang='') {
   global $wpdb;
   $table = $wpdb->prefix.COUNTRIES_TBNAME;
   if (empty($lang))
	   $lang = eme_detect_lang();
   $sql = $wpdb->prepare("SELECT name FROM $table WHERE alpha_2 = %s AND lang='$lang' LIMIT 1",$code);
   $res = $wpdb->get_var($sql);
   if (empty($res)) {
	   $sql = $wpdb->prepare("SELECT name FROM $table WHERE alpha_2 = %s AND lang='' LIMIT 1",$code);
	   $res = $wpdb->get_var($sql);
   }
   return $res;
}

function eme_get_state($id) {
   global $wpdb;
   $table = $wpdb->prefix.STATES_TBNAME;
   $sql = $wpdb->prepare("SELECT * FROM $table WHERE id=%d",$id);
   return $wpdb->get_row($sql, ARRAY_A);
}

function eme_get_state_lang($id) {
   global $wpdb;
   $table = $wpdb->prefix.STATES_TBNAME;
   $countries_table = $wpdb->prefix.COUNTRIES_TBNAME;
   $sql = $wpdb->prepare("SELECT country.lang FROM $table AS state LEFT JOIN $countries_table AS country ON state.country_id=country.id WHERE state.id=%d",$id);
   return $wpdb->get_var($sql);
}

function eme_get_state_name($code,$country_code,$lang='') {
   global $wpdb;
   $table = $wpdb->prefix.STATES_TBNAME;
   $countries_table = $wpdb->prefix.COUNTRIES_TBNAME;
   if (empty($lang))
	   $lang = eme_detect_lang();
   if (empty($country_code))
	   $sql = $wpdb->prepare("SELECT state.name FROM $table AS state LEFT JOIN $countries_table AS country ON state.country_id=country.id WHERE state.code=%s AND (country.lang='' OR country.lang=%s) LIMIT 1",$code,$lang);
   else
	   $sql = $wpdb->prepare("SELECT state.name FROM $table AS state LEFT JOIN $countries_table AS country ON state.country_id=country.id WHERE state.code=%s and country.alpha_2=%s AND (country.lang='' OR country.lang=%s) LIMIT 1",$code,$country_code,$lang);
   return $wpdb->get_var($sql);
}

function eme_get_countries_lang() {
   global $wpdb;
   $table = $wpdb->prefix.COUNTRIES_TBNAME;
   $jTableResult = array();
   $sql="SELECT id, CONCAT (name, ' (', lang, ')') AS name FROM $table";
   return $wpdb->get_results($sql, ARRAY_A);
}

add_action( 'wp_ajax_eme_countries_list', 'eme_ajax_countries_list' );
add_action( 'wp_ajax_eme_manage_countries', 'eme_ajax_manage_countries' );
add_action( 'wp_ajax_eme_states_list', 'eme_ajax_states_list' );
add_action( 'wp_ajax_eme_manage_states', 'eme_ajax_manage_states' );

function eme_ajax_countries_list() {
   global $wpdb;
   $table = $wpdb->prefix.COUNTRIES_TBNAME;
   $jTableResult = array();
   // The toolbar search input
   $q = isset($_REQUEST['q'])?$_REQUEST['q']:"";
   $opt = isset($_REQUEST['opt'])?$_REQUEST['opt']:"";
   $where ='';
   $where_array = array();
   if ($q) {
        for ($i = 0; $i < count($opt); $i++) {
                $fld = esc_sql($opt[$i]);
                $where_array[] = $fld." like '%".esc_sql($q[$i])."%'";
        }
        $where = " WHERE ".implode(" AND ",$where_array);
   }
   if (current_user_can( get_option('eme_cap_settings'))) {
      $sql = "SELECT COUNT(*) FROM $table $where";
      $recordCount = $wpdb->get_var($sql);
      if (!empty($_REQUEST["jtSorting"]))
              $sorting="ORDER BY ".esc_sql($_REQUEST["jtSorting"]);
      else
              $sorting="";
      if (isset($_REQUEST["jtStartIndex"]) && isset($_REQUEST["jtPageSize"]))
              $limit=" LIMIT ".intval($_REQUEST["jtStartIndex"]).",".intval($_REQUEST["jtPageSize"]);
      else
              $limit="";

      $sql="SELECT * FROM $table $where $sorting $limit";
      $rows=$wpdb->get_results($sql,ARRAY_A);
      foreach ($rows as $key=>$row) {
              $rows[$key]['name']="<a href='".wp_nonce_url(admin_url("admin.php?page=eme-countries&amp;eme_admin_action=edit_country&amp;id=".$row['id']),'eme_admin','eme_admin_nonce')."'>".$row['name']."</a>";
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

function eme_ajax_states_list() {
   global $wpdb;
   $table = $wpdb->prefix.STATES_TBNAME;
   $countries_table = $wpdb->prefix.COUNTRIES_TBNAME;
   $jTableResult = array();
   if (current_user_can( get_option('eme_cap_settings'))) {
      $sql = "SELECT COUNT(*) FROM $table";
      $recordCount = $wpdb->get_var($sql);
      if (!empty($_REQUEST["jtSorting"]))
              $sorting="ORDER BY ".esc_sql($_REQUEST["jtSorting"]);
      else
              $sorting="";
      if (isset($_REQUEST["jtStartIndex"]) && isset($_REQUEST["jtPageSize"]))
              $limit=" LIMIT ".intval($_REQUEST["jtStartIndex"]).",".intval($_REQUEST["jtPageSize"]);
      else
              $limit="";

      $sql = "SELECT state.*,country.lang,country.name AS country_name FROM $table AS state LEFT JOIN $countries_table AS country ON state.country_id=country.id $sorting $limit";
      $rows = $wpdb->get_results($sql, ARRAY_A);
      foreach ($rows as $key=>$row) {
              $rows[$key]['name']="<a href='".wp_nonce_url(admin_url("admin.php?page=eme-countries&amp;eme_admin_action=edit_state&amp;id=".$row['id']),'eme_admin','eme_admin_nonce')."'>".$row['name']."</a>";
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
function eme_ajax_manage_countries() {
   check_ajax_referer('eme_admin','eme_admin_nonce');
   if (!current_user_can( get_option('eme_cap_settings'))) {
      $jTableResult['Result'] = "Error";
      $jTableResult['Message'] = __('Access denied!','events-made-easy');
      print json_encode($jTableResult);
      wp_die();
   }
   if (isset($_REQUEST['do_action'])) {
     $do_action=eme_sanitize_request($_REQUEST['do_action']);
     switch ($do_action) {
         case 'deleteCountries':
              eme_ajax_country_delete();
              break;
      }
   }
   wp_die();
}
function eme_ajax_manage_states() {
   check_ajax_referer('eme_admin','eme_admin_nonce');
   if (isset($_REQUEST['do_action'])) {
     $do_action=eme_sanitize_request($_REQUEST['do_action']);
     switch ($do_action) {
         case 'deleteStates':
              eme_ajax_record_delete(STATES_TBNAME, 'eme_cap_settings', 'id');
              break;
      }
   }
   wp_die();
}
function eme_ajax_country_delete() {
   global $wpdb;
   $countries_table = $wpdb->prefix.COUNTRIES_TBNAME;
   $states_table = $wpdb->prefix.STATES_TBNAME;

   check_ajax_referer('eme_admin','eme_admin_nonce');
   $ids_arr=explode(',',$_POST['id']);
   if (eme_array_integers($ids_arr)) {
         $wpdb->query("DELETE FROM $countries_table WHERE id in ( ".$_POST['id'].")");
         $wpdb->query("UPDATE $states_table SET country_id=0 where country_id in ( ".$_POST['id'].")");
   }
   $jTableResult['Result'] = "OK";
   $jTableResult['Message'] = __('Country deleted!','events-made-easy');
   print json_encode($jTableResult);
   wp_die();
}

function eme_ajax_state_edit() {
   check_ajax_referer('eme_admin','eme_admin_nonce');
   $jTableResult = array();

   if (isset($_POST['id'])) {
      $state=eme_get_state(intval($_POST['id']));
      $update=1;
      if (!$state) {
	      $jTableResult['Result'] = "Error";
	      $jTableResult['Message'] = __("No such state",'events-made-easy');
	      print json_encode($jTableResult);
	      wp_die();
      }
   } else {
      $state=eme_new_state();
      $update=0;
   }
   foreach ($state as $key=>$val) {
      if (isset($_POST[$key]))
         $state[$key]=eme_sanitize_request($_POST[$key]);
   }
   if (strlen($state['code']) != 2) {
	   $jTableResult['Result'] = "Error";
	   $jTableResult['Message'] = __("Incorrect state code",'events-made-easy');
	   print json_encode($jTableResult);
	   wp_die();
   } else {
	   $state['code'] = strtoupper($state['code']);
   }
   if (empty($state['country_id'])) {
	   $jTableResult['Result'] = "Error";
	   $jTableResult['Message'] = __("Incorrect country selection",'events-made-easy');
	   print json_encode($jTableResult);
	   wp_die();
   }

   if (current_user_can( get_option('eme_cap_settings'))) {
      if ($update)
         $res=eme_db_update_state($state['id'],$state);
      else
         $res=eme_db_insert_state($state);
      if (!$res) {
         $jTableResult['Result'] = "Error";
         if ($update)
            $jTableResult['Message'] = __('Update failed: ','events-made-easy');
         else
            $jTableResult['Message'] = __('Insert failed: ','events-made-easy');
      } else {
         $jTableResult['Result'] = "OK";
         if (!$update) {
            $record=eme_get_state($res);
	    // for the new record, we also need to provide the lang (as done in eme_get_states)
            $record['lang']=eme_get_state_lang($res);
            $jTableResult['Record'] = eme_esc_html($record);
         }
      }
   } else {
      $jTableResult['Result'] = "Error";
      $jTableResult['Message'] = __('Access denied!','events-made-easy');
   }

   //Return result to jTable
   print json_encode($jTableResult);
   wp_die();
}

// for both logged in and not logged in users
add_action( 'wp_ajax_eme_select_state', 'eme_select_state_ajax' );
add_action( 'wp_ajax_nopriv_eme_select_state', 'eme_select_state_ajax' );
function eme_select_state_ajax() {
   check_ajax_referer('eme_frontend','eme_frontend_nonce');
   $q = isset($_REQUEST['q']) ? eme_sanitize_request($_REQUEST['q']) : '';
   $country_code= isset($_POST['country_code'])? eme_sanitize_request($_POST['country_code']) : '';
   // the country code can be empty, in which case eme_get_localized_states will return states if only 1 country exists
   $records=array();
   $states = eme_get_localized_states($country_code);
   foreach ($states as $state) {
      if (!empty($q) && !stristr($state['name'],$q)) continue;
      $record = array();
      $record['id']= $state['code'];
      $record['text'] = $state['name'];
      $records[]  = $record;
   }
   $jTableResult['TotalRecordCount'] = count($records);
   $jTableResult['Records'] = $records;
   print json_encode($jTableResult);
   wp_die();
}

// for both logged in and not logged in users
add_action( 'wp_ajax_eme_select_country', 'eme_select_country_ajax' );
add_action( 'wp_ajax_nopriv_eme_select_country', 'eme_select_country_ajax' );
function eme_select_country_ajax() {
   check_ajax_referer('eme_frontend','eme_frontend_nonce');
   $q = isset($_REQUEST['q']) ? eme_sanitize_request($_REQUEST['q']) : '';
   $records=array();
   $countries = eme_get_localized_countries();
   foreach ($countries as $country) {
      if (!empty($q) && !stristr($country['name'],$q)) continue;
      $record = array();
      $record['id']= $country['alpha_2'];
      $record['text'] = $country['name'];
      $records[]  = $record;
   }
   $jTableResult['TotalRecordCount'] = count($records);
   $jTableResult['Records'] = $records;
   print json_encode($jTableResult);
   wp_die();
}

?>
