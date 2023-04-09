jQuery(document).ready( function($) {
   function getQueryParams(qs) {
       qs = qs.split('+').join(' ');
       var params = {},
                    tokens,
                    re = /[?&]?([^=]+)=([^&]*)/g;

       while (tokens = re.exec(qs)) {
          params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
       }
       return params;
   }
   var $_GET = getQueryParams(document.location.search);

   function updateIntervalDescriptor () { 
      $('.interval-desc').hide();
      var number = '-plural';
      if ($('input#recurrence-interval').val() == 1 || $('input#recurrence-interval').val() == '') {
         number = '-singular';
      }
      var descriptor = 'span#interval-'+$('select#recurrence-frequency').val()+number;
      $(descriptor).show();
   }
   
   function updateIntervalSelectors () {
      $('span.alternate-selector').hide();
      $('span#'+ $('select#recurrence-frequency').val() + '-selector').show();
      //$('p.recurrence-tip').hide();
      //$('p#'+ $(this).val() + '-tip').show();
   }
   
   function updateShowHideRecurrence () {
      if($('input#event-recurrence').prop('checked')) {
         $('#event_recurrence_pattern').fadeIn();
         $('div#div_recurrence_event_duration').show();
         $('div#div_recurrence_date').show();
         $('div#div_event_date').hide();
      } else {
         $('#event_recurrence_pattern').hide();
         $('div#div_recurrence_event_duration').hide();
         $('div#div_recurrence_date').hide();
         $('div#div_event_date').show();
      }
   }
   
   function updateShowHideRecurrenceSpecificDays () {
      if ($('select#recurrence-frequency').val() == 'specific') {
         $('div#recurrence-intervals').hide();
         $('input#localized-rec-end-date').hide();
         $('p#recurrence-dates-explanation').hide();
         $('span#recurrence-dates-explanation-specificdates').show();
         $('#localized-rec-start-date').fdatepicker().data('fdatepicker').update('multipleDates',true);
      } else {
         $('div#recurrence-intervals').show();
         $('input#localized-rec-end-date').show();
         $('p#recurrence-dates-explanation').show();
         $('span#recurrence-dates-explanation-specificdates').hide();
         $('#localized-rec-start-date').fdatepicker().data('fdatepicker').update('multipleDates',false);
	 // if the recurrence contained specific days before, clear those because that would not work upon save
         if ($('#rec-start-date-to-submit').val().indexOf(',') !== -1) {
         	$('#localized-rec-start-date').fdatepicker().data('fdatepicker').clear();
	 }
      }
   }
   
   function updateShowHideRsvp() {
      if ($('input#event_rsvp').prop('checked')) {
	      $('div#rsvp-accordion').fadeIn();
	      //$('#event-tabs').tabs('enable','#tab-mailformats');
	      $('div#div_event_rsvp').fadeIn();
	      $('div#div_dyndata').fadeIn();
	      $('div#div_event_dyndata_allfields').fadeIn();
	      $('div#div_event_payment_methods').fadeIn();
	      $('div#div_event_registration_form_format').fadeIn();
	      $('div#div_event_cancel_form_format').fadeIn();
	      $('div#div_event_registration_recorded_ok_html').fadeIn();
	      $('div#div_event_attendance_info').fadeIn();
	      $('div#div_mailformats').fadeIn();
      } else {
         //$('#event-tabs').tabs('disable','#tab-mailformats');
         $('div#rsvp-accordion').fadeOut();
         $('div#div_mailformats').fadeOut();
      }
   }
   function updateShowHideTasks() {
      if ($('input#event_tasks').prop('checked')) {
         $('div#tab-tasks-container').fadeIn();
      } else {
         $('div#tab-tasks-container').fadeOut();
      }
   }

   function updateShowHideRsvpAutoApprove() {
      if ($('input#approval_required-checkbox').prop('checked')) {
         $('span#span_approval_required_mail_warning').fadeIn();
         $('p#p_auto_approve').fadeIn();
         $('p#p_auto_approve').fadeIn();
         $('p#p_ignore_pending').fadeIn();
         $('p#p_rsvp_pending_reminder_days').fadeIn();
      } else {
         $('span#span_approval_required_mail_warning').hide();
         $('p#p_auto_approve').fadeOut();
         $('p#p_ignore_pending').fadeOut();
         $('p#p_rsvp_pending_reminder_days').fadeOut();
      }
   }
   
   function updateShowHideTime() {
      if ($('input#eme_prop_all_day').prop('checked')) {
         $('div#time-selector').hide();
      } else {
         $('div#time-selector').show();
      }
   }
   
   function updateShowHideMultiPriceDescription() {
      if ($('input#price').length) {
         if ($('input#price').val().indexOf('||') !== -1) {
            $('tr#row_multiprice_desc').show();
            $('tr#row_price_desc').hide();
         } else {
            $('tr#row_multiprice_desc').hide();
            $('tr#row_price_desc').show();
         }
      }
   }

   function eme_event_location_autocomplete() {
       // for autocomplete to work, the element needs to exist, otherwise JS errors occur
       // we check for that using length
       if ($('input[name=location_name]').length) {
             $('input[name=location_name]').autocomplete({
               source: function(request, response) {
                            $.get(self.location.href,
                                      { q: request.term,
                                        eme_admin_action: 'autocomplete_locations'
                                      },
                                      function(data){
                                                   response($.map(data, function(item) {
                                                         return {
                                                            location_id: item.location_id,
                                                            name: eme_htmlDecode(item.name),
                                                            address1: eme_htmlDecode(item.address1),
                                                            address2: eme_htmlDecode(item.address2),
                                                            city: eme_htmlDecode(item.city),
                                                            state: eme_htmlDecode(item.state),
                                                            zip: eme_htmlDecode(item.zip),
                                                            country: eme_htmlDecode(item.country),
                                                            latitude: eme_htmlDecode(item.latitude),
                                                            longitude: eme_htmlDecode(item.longitude),
                                                            location_url: eme_htmlDecode(item.location_url),
                                                            map_icon: eme_htmlDecode(item.map_icon),
                                                            online_only: eme_htmlDecode(item.online_only)
                                                         };
                                                   }));
                                       }, 'json');
                       },
               select:function(evt, ui) {
                            evt.preventDefault();
                            // when a location is selected, populate related fields in this form
                            $('input#location_id').val(ui.item.location_id);
                            $('input[name=location_name]').val(ui.item.name).attr('readonly', true);
                            $('input#location_address1').val(ui.item.address1).attr('readonly', true);
                            $('input#location_address2').val(ui.item.address2).attr('readonly', true);
                            $('input#location_city').val(ui.item.city).attr('readonly', true);
                            $('input#location_state').val(ui.item.state).attr('readonly', true);
                            $('input#location_zip').val(ui.item.zip).attr('readonly', true);
                            $('input#location_country').val(ui.item.country).attr('readonly', true);
                            $('input#location_latitude').val(ui.item.latitude).attr('readonly', true);
                            $('input#location_longitude').val(ui.item.longitude).attr('readonly', true);
                            $('input#eme_loc_prop_map_icon').val(ui.item.map_icon).attr('readonly', true);
                            $('input#eme_loc_prop_online_only').val(ui.item.online_only).attr('disabled',true);
                            $('input#location_url').val(ui.item.location_url).attr('readonly', true);
                            $('#img_edit_location').show();
                            if(eme.translate_eme_map_is_active === 'true') {
                               loadMapLatLong(ui.item.name, ui.item.address1, ui.item.address2, ui.item.city,ui.item.state,ui.item.zip,ui.item.country, ui.item.latitude, ui.item.longitude);
                            }
                            return false;
                      },
               minLength: 1
             }).data( 'ui-autocomplete' )._renderItem = function( ul, item ) {
               return $( '<li></li>' )
               .append('<a><strong>'+item.name+'</strong><br /><small>'+item.address1+' - '+item.city+ '</small></a>')
               .appendTo( ul );
             };
   
             if ($('input#location_id').val()=='0') {
                $('input[name=location_name]').attr('readonly', false);
                $('input#location_address1').attr('readonly', false);
                $('input#location_address2').attr('readonly', false);
                $('input#location_city').attr('readonly', false);
                $('input#location_state').attr('readonly', false);
                $('input#location_zip').attr('readonly', false);
                $('input#location_country').attr('readonly', false);
                $('input#location_latitude').attr('readonly', false);
                $('input#location_longitude').attr('readonly', false);
		$('input#eme_loc_prop_map_icon').attr('readonly', false);
		$('input#eme_loc_prop_online_only').attr('disabled',false);
                $('input#location_url').attr('readonly', false);
   	     $('#img_edit_location').hide();
   	  } else {
                $('input[name=location_name]').attr('readonly', true);
                $('input#location_address1').attr('readonly', true);
                $('input#location_address2').attr('readonly', true);
                $('input#location_city').attr('readonly', true);
                $('input#location_state').attr('readonly', true);
                $('input#location_zip').attr('readonly', true);
                $('input#location_country').attr('readonly', true);
                $('input#location_latitude').attr('readonly', true);
                $('input#location_longitude').attr('readonly', true);
		$('input#eme_loc_prop_map_icon').attr('readonly', true);
		$('input#eme_loc_prop_online_only').attr('disabled',true);
                $('input#location_url').attr('readonly', true);
   	     $('#img_edit_location').show();
   	  }
   	  $('input#location_id').on("change",function(){
   		  if ($('input#location_id').val()=='') {
   			  $('#img_edit_location').hide();
   		  } else {
   			  $('#img_edit_location').show();
   		  }
             });
   	  $('#img_edit_location').on("click",function(e) {
                e.preventDefault();
                $('input#location_id').val('');
                $('input[name=location_name]').attr('readonly', false);
                $('input#location_address1').attr('readonly', false);
                $('input#location_address2').attr('readonly', false);
                $('input#location_city').attr('readonly', false);
                $('input#location_state').attr('readonly', false);
                $('input#location_zip').attr('readonly', false);
                $('input#location_country').attr('readonly', false);
                $('input#location_latitude').attr('readonly', false);
                $('input#location_longitude').attr('readonly', false);
		$('input#eme_loc_prop_map_icon').attr('readonly', false);
		$('input#eme_loc_prop_online_only').attr('disabled',false);
                $('input#location_url').attr('readonly', false);
                $('#img_edit_location').hide();
   	  });
   
       } else if ($('input[name="location-select-name"]').length) {
             $('#location-select-id').on("change",function() {
               $.getJSON(self.location.href,{eme_admin_action: 'autocomplete_locations',id: $(this).val()}, function(item){
                  $('input[name="location-select-name"]').val(item.name);
                  $('input[name="location-select-address1"]').val(item.address1);
                  $('input[name="location-select-address2"]').val(item.address2);
                  $('input[name="location-select-city"]').val(item.city);
                  $('input[name="location-select-state"]').val(item.state);
                  $('input[name="location-select-zip"]').val(item.zip);
                  $('input[name="location-select-country"]').val(item.country);
                  $('input[name="location-select-latitude"]').val(item.latitude);
                  $('input[name="location-select-longitude"]').val(item.longitude);
                  if(eme.translate_eme_map_is_active === 'true') {
                     loadMapLatLong(item.name,item.address1,item.address2,item.city,item.state,item.zip,item.country,item.latitude,item.longitude);
                  }
               })
             });
       }
   }

   if ($('#localized-start-date').length) {
	   $('#localized-start-date').fdatepicker({
		   autoClose: true,
		   onSelect: function(formattedDate,date,inst) {
			   //$('#localized-end-date').fdatepicker().data('fdatepicker').update('minDate',date);
			   startDate_formatted = inst.formatDate('Ymd',date);
			   endDate_basic = $('#localized-end-date').fdatepicker().data('fdatepicker').selectedDates[0];
			   endDate_formatted = inst.formatDate('Ymd',endDate_basic);
			   if (endDate_formatted<startDate_formatted) {
				   $('#localized-end-date').fdatepicker().data('fdatepicker').selectDate(date);
			   }
		   }
	   });
   }
   if ($('#localized-end-date').length) {
	   $('#localized-end-date').fdatepicker({
		   autoClose: true,
		   onSelect: function(formattedDate,date,inst) {
			   //$('#localized-start-date').fdatepicker().data('fdatepicker').update('maxDate',date);
			   endDate_formatted = inst.formatDate('Ymd',date);
			   startDate_basic = $('#localized-start-date').fdatepicker().data('fdatepicker').selectedDates[0];
			   startDate_formatted = inst.formatDate('Ymd',startDate_basic);
			   if (startDate_formatted>endDate_formatted) {
				   $('#localized-start-date').fdatepicker().data('fdatepicker').selectDate(date);
			   }
		   }
	   });
   }
   if ($('#localized-rec-start-date').length) {
	   $('#localized-rec-start-date').fdatepicker({
		   autoClose: true,
		   onSelect: function(formattedDate,date,inst) {
			   // if multiple days are selected, date is an array, and then we don't touch it for now
			   if (!Array.isArray(date)) {
				   $('#recurrence-dates-specificdates').text("");
				   //$('#localized-rec-end-date').fdatepicker().data('fdatepicker').update('minDate',date);
				   startDate_formatted = inst.formatDate('Ymd',date);
				   endDate_basic = $('#localized-rec-end-date').fdatepicker().data('fdatepicker').selectedDates[0];
				   endDate_formatted = inst.formatDate('Ymd',endDate_basic);
				   if (endDate_formatted<startDate_formatted) {
					   $('#localized-rec-end-date').fdatepicker().data('fdatepicker').selectDate(date);
				   }
			   } else {
				$('#recurrence-dates-specificdates').html('<br />'+eme.translate_selecteddates+'<br />');
				$.each(date, function( index, value ) {
			   		date_formatted = inst.formatDate(eme.translate_fdateformat,value);
					$('#recurrence-dates-specificdates').append(date_formatted+'<br />');
				});
			   }
		   }
	   });
   }
   if ($('#localized-rec-end-date').length) {
	   $('#localized-rec-end-date').fdatepicker({
		   autoClose: true,
		   onSelect: function(formattedDate,date,inst) {
			   if (!Array.isArray(date)) {
				   //$('#localized-rec-start-date').fdatepicker().data('fdatepicker').update('maxDate',date);
				   endDate_formatted = inst.formatDate('Ymd',date);
				   startDate_basic = $('#localized-rec-start-date').fdatepicker().data('fdatepicker').selectedDates[0];
				   startDate_formatted = inst.formatDate('Ymd',startDate_basic);
				   if (startDate_formatted>endDate_formatted) {
					   $('#localized-rec-start-date').fdatepicker().data('fdatepicker').selectDate(date);
				   }
			   }
		   }
	   });
   }

   $('#div_recurrence_date').hide();

   // if any of event_single_event_format,event_page_title_format,event_contactperson_email_body,event_respondent_email_body,event_registration_pending_email_body, event_registration_form_format, event_registration_updated_email_body
   // is empty: display default value on focus, and if the value hasn't changed from the default: empty it on blur

   function text_focus_blur(target,def_value) {
	   $(target).on("focus",function(){
		   if ($(this).val() == '') {
			   $(this).val(def_value);
		   }
	   }).on("blur",function(){
		   if ($(this).val() == def_value) {
			   $(this).val('');
		   }
	   }); 
   }
   text_focus_blur('textarea#event_page_title_format',eme_event_page_title_format());
   text_focus_blur('textarea#event_single_event_format',eme_single_event_format());
   text_focus_blur('textarea#event_registration_recorded_ok_html',eme_registration_recorded_ok_html());
   text_focus_blur('input#eme_prop_event_contactperson_email_subject',eme_contactperson_email_subject());
   text_focus_blur('textarea#event_contactperson_email_body',eme_contactperson_email_body());
   text_focus_blur('input#eme_prop_contactperson_registration_pending_email_subject',eme_contactperson_pending_email_subject());
   text_focus_blur('textarea#eme_prop_contactperson_registration_pending_email_body',eme_contactperson_pending_email_body());
   text_focus_blur('input#eme_prop_contactperson_registration_cancelled_email_subject',eme_contactperson_cancelled_email_subject());
   text_focus_blur('textarea#eme_prop_contactperson_registration_cancelled_email_body',eme_contactperson_cancelled_email_body());
   text_focus_blur('input#eme_prop_contactperson_registration_ipn_email_subject',eme_contactperson_ipn_email_subject());
   text_focus_blur('textarea#eme_prop_contactperson_registration_ipn_email_body',eme_contactperson_ipn_email_body());
   text_focus_blur('input#eme_prop_contactperson_registration_paid_email_subject',eme_contactperson_paid_email_subject());
   text_focus_blur('textarea#eme_prop_contactperson_registration_paid_email_body',eme_contactperson_paid_email_body());
   text_focus_blur('input#eme_prop_event_respondent_email_subject',eme_respondent_email_subject());
   text_focus_blur('textarea#event_respondent_email_body',eme_respondent_email_body());
   text_focus_blur('input#eme_prop_event_registration_pending_email_subject',eme_registration_pending_email_subject());
   text_focus_blur('textarea#event_registration_pending_email_body',eme_registration_pending_email_body());
   text_focus_blur('input#eme_prop_event_registration_pending_reminder_email_subject',eme_registration_pending_reminder_email_subject());
   text_focus_blur('textarea#event_registration_pending_reminder_email_body',eme_registration_pending_reminder_email_body());
   text_focus_blur('input#eme_prop_event_registration_updated_email_subject',eme_registration_updated_email_subject());
   text_focus_blur('textarea#event_registration_updated_email_body',eme_registration_updated_email_body());
   text_focus_blur('input#eme_prop_event_registration_reminder_email_subject',eme_registration_reminder_email_subject());
   text_focus_blur('textarea#event_registration_reminder_email_body',eme_registration_reminder_email_body());
   text_focus_blur('input#eme_prop_event_registration_cancelled_email_subject',eme_registration_cancelled_email_subject());
   text_focus_blur('textarea#event_registration_cancelled_email_body',eme_registration_cancelled_email_body());
   text_focus_blur('input#eme_prop_event_registration_trashed_email_subject',eme_registration_trashed_email_subject());
   text_focus_blur('textarea#event_registration_trashed_email_body',eme_registration_trashed_email_body());
   text_focus_blur('input#eme_prop_event_registration_paid_email_subject',eme_registration_paid_email_subject());
   text_focus_blur('textarea#event_registration_paid_email_body',eme_registration_paid_email_body());
   text_focus_blur('textarea#event_registration_form_format',eme_registration_form_format());
   text_focus_blur('textarea#event_cancel_form_format',eme_cancel_form_format());

   if ($('#event-tabs').length) {
	   // initially the div is not shown using display:none, so jquery has time to render it and then we call show()
	   $('#event-tabs').tabs();
	   $('#event-tabs').show();
	   $('#event-tabs').on('tabsactivate', function( event, ui ) {
		   // we call both functions to show the map, only 1 will work (either the select-based or the other) depending on the form shown
		   if (ui.newPanel.attr('id') == 'tab-locationdetails') {
			   // We need to call it here, because otherwise the map initially doesn't render correctly due to hidden tab div etc ...
			   if(eme.translate_eme_map_is_active === 'true') {
				   eme_SelectdisplayAddress();
				   eme_displayAddress(0);
			   }
			   // also initialize the code for auto-complete of location info
			   eme_event_location_autocomplete();
		   }
	   });
	   // the validate plugin can take other tabs/hidden fields into account
	   $('#eventForm').validate({
		   // ignore: false is added so the fields of tabs that are not visible when editing an event are evaluated too
		   ignore: false,
		   focusCleanup: true,
		   errorClass: "eme_required",
		   invalidHandler: function(e,validator) {
			   $.each(validator.invalid, function(key, value) {
				   // get the closest tabname
                                   var tabname=$('[name="'+key+'"]').closest('.ui-tabs-panel').attr('id');
                                   // activate the tab that has the error
                                   var tabindex = $('#event-tabs a[href="#'+tabname+'"]').parent().index();
                                   $("#event-tabs").tabs("option", "active", tabindex);
                                   // break the loop, we only want to switch to the first tab with the error
                                   return false;
			   });
		   }
	   });
   }

   updateIntervalDescriptor(); 
   updateIntervalSelectors();
   updateShowHideRecurrence();
   updateShowHideRsvp();
   updateShowHideTasks();
   updateShowHideRsvpAutoApprove();
   if ($('select#recurrence-frequency').length) {
      updateShowHideRecurrenceSpecificDays();
   }
   updateShowHideTime();
   updateShowHideMultiPriceDescription();
   $('input#event-recurrence').on("change",updateShowHideRecurrence);
   $('input#event_tasks').on("change",updateShowHideTasks);
   $('input#event_rsvp').on("change",updateShowHideRsvp);
   $('input#eme_prop_all_day').on("change",updateShowHideTime);
   $('input#price').on("change",updateShowHideMultiPriceDescription);
   $('input#approval_required-checkbox').on("change",updateShowHideRsvpAutoApprove);
   // recurrency elements
   $('input#recurrence-interval').on("keyup",updateIntervalDescriptor);
   $('select#recurrence-frequency').on("change",updateIntervalDescriptor);
   $('select#recurrence-frequency').on("change",updateIntervalSelectors);
   $('select#recurrence-frequency').on("change",updateShowHideRecurrenceSpecificDays);

   function validateEventForm() {
      // users cannot submit the event form unless some fields are filled
      var recurring = $('input[name=repeated_event]:checked').val();
      
      if (recurring && $('input#localized-rec-end-date').val() == '' && $('select#recurrence-frequency').val() != 'specific') {
         alert (eme.translate_enddate_required); 
         $('input#localized-rec-end-date').css('border','2px solid red');
         return false;
      } else if (recurring && $('input#localized-rec-start-date').val() == $('input#localized-rec-end-date').val()) {
         alert (eme.translate_startenddate_identical); 
         $('input#localized-rec-end-date').css('border','2px solid red');
         return false;
      } else {
         $('input#localized-rec-end-date').css('border','1px solid #DFDFDF');
      }
      // just before we return true, also set the disabled checkbox online_only to enabled, otherwise it won't submit if disabled
      $('input#eme_loc_prop_online_only').attr('disabled',false);
      return true;
   }
   $('#eventForm').bind('submit', validateEventForm);

   var eventfields = {
                event_id: {
                    key: true,
		    title: eme.translate_id,
                    visibility: 'hidden'
                },
                event_name: {
		    title: eme.translate_name,
                    visibility: 'fixed'
                },
                event_status: {
		    title: eme.translate_status,
                    width: '5%'
                },
                copy: {
		    title: eme.translate_copy,
                    sorting: false,
                    width: '2%',
                    listClass: 'eme-jtable-center'
                },
                rsvp: {
		    title: eme.translate_rsvp,
                    sorting: false,
                    width: '2%',
                    listClass: 'eme-jtable-center'
                },
	        eventprice: {
                    title: eme.translate_eventprice,
                    sorting: false
                },
                location_name: {
		    title: eme.translate_location
                },
                datetime: {
		    title: eme.translate_datetime,
                    width: '5%'
                },
                creation_date: {
		    title: eme.translate_created_on,
                    visibility: 'hidden',
                    width: '5%'
                },
                modif_date: {
		    title: eme.translate_modified_on,
                    visibility: 'hidden',
                    width: '5%'
                },
                recinfo: {
		    title: eme.translate_recinfo,
                    sorting: false
                }
   }
   var recurrencefields = {
                recurrence_id: {
                    key: true,
		    title: eme.translate_id,
                    visibility: 'hidden'
                },
                event_name: {
		    title: eme.translate_name,
		    sorting: false,
                    visibility: 'fixed'
                },
                event_status: {
		    title: eme.translate_status,
		    sorting: false,
                    width: '5%'
                },
                copy: {
		    title: eme.translate_copy,
                    sorting: false,
                    width: '2%',
                    listClass: 'eme-jtable-center'
                },
	        eventprice: {
                    title: eme.translate_eventprice,
                    sorting: false
                },
                location_name: {
		    title: eme.translate_location,
		    sorting: false,
                },
                creation_date: {
		    title: eme.translate_created_on,
                    visibility: 'hidden',
                    width: '5%'
                },
                modif_date: {
		    title: eme.translate_modified_on,
                    visibility: 'hidden',
                    width: '5%'
                },
                recinfo: {
		    title: eme.translate_recinfo,
                    sorting: false
                },
                rec_singledur: {
		    title: eme.translate_rec_singledur,
                    sorting: false
                }
   }
   if ($('#EventsTableContainer').length) {
      var extrafields=$('#EventsTableContainer').data('extrafields').toString().split(',');
      var extrafieldnames=$('#EventsTableContainer').data('extrafieldnames').toString().split(',');
      var extrafieldsearchable=$('#EventsTableContainer').data('extrafieldsearchable').toString().split(',');
      $.each(extrafields, function( index, value ) {
                        if (value != '') {
                                var fieldindex='FIELD_'+value;
                                var extrafield = {};
				if (extrafieldsearchable[index]=='1') {
					sorting=true;
				} else {
					sorting=false;
				}
                                extrafield[fieldindex] = {
                                        title: extrafieldnames[index],
                                        sorting: sorting,
                                        visibility: 'hidden'
                                };
                                $.extend(eventfields,extrafield);
                        }
      });

      //Prepare jtable plugin
      $('#EventsTableContainer').jtable({
            title: eme.translate_events,
            paging: true,
            pageSizes: [10, 25, 50, 100],
            sorting: true,
            multiSorting: true,
            jqueryuiTheme: true,
            defaultSorting: 'name ASC',
            selecting: true, //Enable selecting
            multiselect: true, //Allow multiple selecting
            selectingCheckboxes: true, //Show checkboxes on first column
            selectOnRowClick: true, //Enable this to only select using checkboxes
            toolbar: {
                items: [{
                        text: eme.translate_csv,
                        click: function () {
                                  jtable_csv('#EventsTableContainer');
                               }
                        },
                        {
                        text: eme.translate_print,
                        click: function () {
                                  $('#EventsTableContainer').printElement();
                               }
                        }
                        ]
            },
            deleteConfirmation: function(data) {
               data.deleteConfirmMessage = eme.translate_pressdeletetoremove + ' "' + data.record.event_name_simple + '"';
            },
            actions: {
		listAction: ajaxurl+'?action=eme_events_list&trash='+$_GET['trash']
                //deleteAction: ajaxurl+'?action=eme_manage_events&do_action=deleteEvents&eme_admin_nonce='+eme.translate_adminnonce
            },
            fields: eventfields
        });

        // Load list from server
        $('#EventsTableContainer').jtable('load', {
		'scope': $('#scope').val(),
		'status': $('#status').val(),
		'category': $('#category').val(),
		'search_name': $('#search_name').val(),
		'search_start_date': $('#search_start_date').val(),
		'search_end_date': $('#search_end_date').val(),
		'search_customfields': $('#search_customfields').val(),
		'search_customfieldids': $('#search_customfieldids').val()
        });
   }
 
   // Actions button
   $('#EventsActionsButton').on("click",function (e) {
	   e.preventDefault();
           var selectedRows = $('#EventsTableContainer').jtable('selectedRows');
           var do_action = $('#eme_admin_action').val();
	   var send_trashmails = $('#send_trashmails').val();
	   var addtocategory = $('#addtocategory').val();

           var action_ok=1;
           if (selectedRows.length > 0) {
              if ((do_action=='deleteEvents' || do_action=='deleteRecurrences') && !confirm(eme.translate_areyousuretodeleteselected)) {
                 action_ok=0;
              }
              if (action_ok==1) {
		 $('#EventsActionsButton').text(eme.translate_pleasewait);
                 $('#EventsActionsButton').prop('disabled', true);
	         var ids = [];
	         selectedRows.each(function () {
	           ids.push($(this).data('record')['event_id']);
	         });
    
	         var idsjoined = ids.join(); //will be such a string '2,5,7'
		 var params = {
                        'event_id': idsjoined,
                        'action': 'eme_manage_events',
                        'do_action': do_action,
                        'send_trashmails': send_trashmails,
                        'addtocategory': addtocategory,
                        'eme_admin_nonce': eme.translate_adminnonce };

                 $.post(ajaxurl, params, function(data) {
	            $('#EventsTableContainer').jtable('reload');
		    $('#EventsActionsButton').text(eme.translate_apply);
                    $('#EventsActionsButton').prop('disabled', false);
                    $('div#events-message').html(data.Message);
                    $('div#events-message').show();
                    $('div#events-message').delay(3000).fadeOut('slow');
                 }, 'json');
	      }
           }
           // return false to make sure the real form doesn't submit
           return false;
   });

   // Re-load records when user click 'load records' button.
   $('#EventsLoadRecordsButton').on("click",function (e) {
           e.preventDefault();
           $('#EventsTableContainer').jtable('load', {
		   'scope': $('#scope').val(),
		   'status': $('#status').val(),
		   'category': $('#category').val(),
		   'search_name': $('#search_name').val(),
		   'search_start_date': $('#search_start_date').val(),
		   'search_end_date': $('#search_end_date').val(),
		   'search_customfields': $('#search_customfields').val(),
		   'search_customfieldids': $('#search_customfieldids').val()
           });
           // return false to make sure the real form doesn't submit
           return false;
   });

   if ($('#RecurrencesTableContainer').length) {
      //Prepare jtable plugin
      $('#RecurrencesTableContainer').jtable({
            title: eme.translate_recurrences,
            paging: true,
            pageSizes: [10, 25, 50, 100],
            sorting: true,
            multiSorting: true,
            jqueryuiTheme: true,
            defaultSorting: 'name ASC',
            selecting: true, //Enable selecting
            multiselect: true, //Allow multiple selecting
            selectingCheckboxes: true, //Show checkboxes on first column
            selectOnRowClick: true, //Enable this to only select using checkboxes
            toolbar: {
                items: [{
                        text: eme.translate_csv,
                        click: function () {
                                  jtable_csv('#RecurrencesTableContainer');
                               }
                        },
                        {
                        text: eme.translate_print,
                        click: function () {
                                  $('#RecurrencesTableContainer').printElement();
                               }
                        }]
            },
            deleteConfirmation: function(data) {
               data.deleteConfirmMessage = eme.translate_pressdeletetoremove + ' "' + data.record.event_name_simple + '"';
            },
            actions: {
		listAction: ajaxurl+'?action=eme_recurrences_list&trash='+$_GET['trash']
                //deleteAction: ajaxurl+'?action=eme_manage_recurrences&do_action=deleteRecurrences&eme_admin_nonce='+eme.translate_adminnonce
            },
            fields: recurrencefields
        });

        // Load list from server
        $('#RecurrencesTableContainer').jtable('load', {
		'scope': $('#scope').val(),
		'status': $('#status').val(),
		'category': $('#category').val(),
		'search_name': $('#search_name').val(),
		'search_start_date': $('#search_start_date').val(),
		'search_end_date': $('#search_end_date').val(),
		'search_customfields': $('#search_customfields').val(),
		'search_customfieldids': $('#search_customfieldids').val()
        });
   }
 
   // Actions button
   $('#RecurrencesActionsButton').on("click",function (e) {
	   e.preventDefault();
           var selectedRows = $('#RecurrencesTableContainer').jtable('selectedRows');
           var do_action = $('#eme_admin_action').val();
	   var rec_new_start_date = $('#rec_new_start_date').val();
	   var rec_new_end_date = $('#rec_new_end_date').val();

           var action_ok=1;
           if (selectedRows.length > 0) {
              if (do_action=='deleteRecurrences' && !confirm(eme.translate_areyousuretodeleteselected)) {
                 action_ok=0;
              }
              if (action_ok==1) {
		 $('#RecurrencesActionsButton').text(eme.translate_pleasewait);
                 $('#RecurrencesActionsButton').prop('disabled', true);
	         var ids = [];
	         selectedRows.each(function () {
	           ids.push($(this).data('record')['recurrence_id']);
	         });
    
	         var idsjoined = ids.join(); //will be such a string '2,5,7'
		 var params = {
                        'recurrence_id': idsjoined,
                        'action': 'eme_manage_recurrences',
                        'do_action': do_action,
			'rec_new_start_date': rec_new_start_date,
			'rec_new_end_date': rec_new_end_date,
                        'eme_admin_nonce': eme.translate_adminnonce };

                 $.post(ajaxurl, params, function(data) {
	            $('#RecurrencesTableContainer').jtable('reload');
		    $('#RecurrencesActionsButton').text(eme.translate_apply);
                    $('#RecurrencesActionsButton').prop('disabled', false);
                    $('div#events-message').html(data.Message);
                    $('div#events-message').show();
                    $('div#events-message').delay(3000).fadeOut('slow');
                 }, 'json');
	      }
           }
           // return false to make sure the real form doesn't submit
           return false;
   });

   // Re-load records when user click 'load records' button.
   $('#RecurrencesLoadRecordsButton').on("click",function (e) {
           e.preventDefault();
           $('#RecurrencesTableContainer').jtable('load', {
		   'scope': $('#scope').val(),
		   'status': $('#status').val(),
		   'category': $('#category').val(),
		   'search_name': $('#search_name').val(),
		   'search_start_date': $('#search_start_date').val(),
		   'search_end_date': $('#search_end_date').val(),
		   'search_customfields': $('#search_customfields').val(),
		   'search_customfieldids': $('#search_customfieldids').val()
           });
           // return false to make sure the real form doesn't submit
           return false;
   });

   function updateShowHideStuff () {
           var action=$('select#eme_admin_action').val();
           if (action == 'addCategory') {
              jQuery('span#span_addtocategory').show();
           } else {
              jQuery('span#span_addtocategory').hide();
           }
           if (action == 'trashEvents') {
              $('span#span_sendtrashmails').show();
           } else {
              $('span#span_sendtrashmails').hide();
           }
           if (action == 'extendRecurrences') {
              $('span#span_extendrecurrences').show();
           } else {
              $('span#span_extendrecurrences').hide();
           }
   }
   updateShowHideStuff();
   $('select#eme_admin_action').on("change",updateShowHideStuff);

   function changeEventAdminPageTitle() {
           var eventname=$('input[name=event_name]').val();
           if (!eventname) {
                   title=eme.translate_insertnewevent;
           } else {
                   title=eme.translate_editeventstring;
		   title=title.replace(/%s/g, eventname);
	   }
           jQuery(document).prop('title', eme_htmlDecode(title));
   }
   if ($('input[name=event_name]').length) {
           changeEventAdminPageTitle();
           $('input[name=event_name]').on("keyup",changeEventAdminPageTitle);
   }

	// for the image 
	$('#event_remove_image_button').on("click",function(e) {
		$('#event_image_url').val('');
		$('#event_image_id').val('');
		$('#eme_event_image_example' ).attr('src','');
		$('#event_image_button' ).show();
		$('#event_remove_image_button' ).hide();
	});
	$('#event_image_button').on("click",function(e) {
		e.preventDefault();
		var custom_uploader = wp.media({
			title: eme.translate_selectfeaturedimg,
			button: {
				text: eme.translate_setfeaturedimg
			},
			// Tell the modal to show only images.
			library: {
				type: 'image'
			},
			multiple: false  // Set this to true to allow multiple files to be selected
		}).on('select', function() {
		        var selection = custom_uploader.state().get('selection');
			// using map is not really needed, but this way we can reuse the code if multiple=true
			// var attachment = custom_uploader.state().get('selection').first().toJSON();
			selection.map( function(attach) {
				attachment = attach.toJSON();
				$('#event_image_url').val(attachment.url);
				$('#event_image_id').val(attachment.id);
				$('#eme_event_image_example' ).attr('src',attachment.url);
				$('#event_image_button' ).hide();
				$('#event_remove_image_button' ).show();
			});
		}).open();
	});
	if ($('#event_image_url').val() != '') {
		$('#event_image_button' ).hide();
		$('#event_remove_image_button' ).show();
	} else {
		$('#event_image_button' ).show();
		$('#event_remove_image_button' ).hide();
	}

        $('#event_author.eme_select2_wpuser_class').select2({
                width: '100%',
                ajax: {
                        url: ajaxurl+'?action=eme_wpuser_select2',
                        dataType: 'json',
                        delay: 1000,
                        data: function (params) {
                                return {
                                        q: params.term, // search term
                                        page: params.page,
                                        pagesize: 10,
                                        eme_admin_nonce: eme.translate_adminnonce
                                };
                        },
                        processResults: function (data, params) {
                                // parse the results into the format expected by Select2
                                // since we are using custom formatting functions we do not need to
                                // alter the remote JSON data, except to indicate that infinite
                                // scrolling can be used
                                params.page = params.page || 1;
                                return {
                                        results: data.Records,
                                        pagination: {
                                                more: (params.page * 10) < data.TotalRecordCount
                                        }
                                };
                        },
                        cache: true
                }
        });
        $('#event_contactperson_id.eme_select2_wpuser_class').select2({
                width: '100%',
                ajax: {
                        url: ajaxurl+'?action=eme_wpuser_select2',
                        dataType: 'json',
                        delay: 1000,
                        data: function (params) {
                                return {
                                        q: params.term, // search term
                                        page: params.page,
                                        pagesize: 10,
                                        eme_admin_nonce: eme.translate_adminnonce
                                };
                        },
                        processResults: function (data, params) {
                                // parse the results into the format expected by Select2
                                // since we are using custom formatting functions we do not need to
                                // alter the remote JSON data, except to indicate that infinite
                                // scrolling can be used
                                params.page = params.page || 1;
                                return {
                                        results: data.Records,
                                        pagination: {
                                                more: (params.page * 10) < data.TotalRecordCount
                                        }
                                };
                        },
                        cache: true
                },
		allowClear: true,
                placeholder: eme.translate_selectcontact
        });

});
