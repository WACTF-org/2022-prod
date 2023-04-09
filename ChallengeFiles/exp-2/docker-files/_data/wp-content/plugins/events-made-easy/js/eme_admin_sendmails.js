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

   // for autocomplete to work, the element needs to exist, otherwise JS errors occur
   // we check for that using length
   if ($('input[name=chooseperson]').length) {
          $('input[name=chooseperson]').autocomplete({
            source: function(request, response) {
                         $.post(ajaxurl,
                                  { q: request.term,
                                    action: 'eme_autocomplete_people',
                                    eme_searchlimit: 'people'
                                  },
                                  function(data){
                                       response($.map(data, function(item) {
                                          return {
                                             lastname: eme_htmlDecode(item.lastname),
                                             firstname: eme_htmlDecode(item.firstname),
                                             email: eme_htmlDecode(item.email),
                                             person_id: eme_htmlDecode(item.person_id)
                                          };
                                       }));
                                  }, 'json');
            },
            change: function (event, ui) {
                       if(!ui.item){
                            $(event.target).val("");
                       }
            }, 
	    response: function (event, ui) {
                       if (!ui.content.length) {
			    ui.content.push({ person_id: 0 });
                       }
            },
            select:function(event, ui) {
		    // when a person is selected, populate related fields in this form
		    if (ui.item.person_id>0) {
                         $('input[name=send_previewmailto_id]').val(ui.item.person_id);
                         $(event.target).val(ui.item.lastname+' '+ui.item.firstname).attr('readonly', true).addClass('clearable x');
		    }
                    return false;
            },
            minLength: 2
          }).data( 'ui-autocomplete' )._renderItem = function( ul, item ) {
            if (item.person_id==0) {
               return $( '<li></li>' )
               .append('<strong>'+eme.translate_nomatchperson+'</strong>')
               .appendTo( ul );
	    } else {
               return $( '<li></li>' )
               .append('<a><strong>'+item.lastname+' '+item.firstname+'</strong><br /><small>'+item.email+'</small></a>')
               .appendTo( ul );
	    }
          };

          // if manual input: set the hidden field empty again
          $('input[name=chooseperson]').on("keyup",function() {
             $('input[name=send_previewmailto_id]').val('');
          }).change(function() {
             if ($(this).val()=='') {
                $('input[name=send_previewmailto_id]').val('');
                $(this).attr('readonly', false).removeClass('clearable');
             }
          });
   }
   if ($('input[name=eventmail_chooseperson]').length) {
          $('input[name=eventmail_chooseperson]').autocomplete({
            source: function(request, response) {
                         $.post(ajaxurl,
                                  { q: request.term,
                                    action: 'eme_autocomplete_people',
                                    eme_searchlimit: 'people'
                                  },
                                  function(data){
                                       response($.map(data, function(item) {
                                          return {
                                             lastname: eme_htmlDecode(item.lastname),
                                             firstname: eme_htmlDecode(item.firstname),
                                             email: eme_htmlDecode(item.email),
                                             person_id: eme_htmlDecode(item.person_id)
                                          };
                                       }));
                                  }, 'json');
            },
            change: function (event, ui) {
                       if(!ui.item){
                            $(event.target).val("");
                       }
            }, 
	    response: function (event, ui) {
                       if (!ui.content.length) {
			    ui.content.push({ person_id: 0 });
                       }
            },
            select:function(event, ui) {
                    // when a person is selected, populate related fields in this form
		    if (ui.item.person_id>0) {
                         $('input[name=send_previeweventmailto_id]').val(ui.item.person_id);
                         $(event.target).val(ui.item.lastname+' '+ui.item.firstname).attr('readonly', true).addClass('clearable x');
		    }
                    return false;
            },
            minLength: 2
          }).data( 'ui-autocomplete' )._renderItem = function( ul, item ) {
            if (item.person_id==0) {
               return $( '<li></li>' )
               .append('<strong>'+eme.translate_nomatchperson+'</strong>')
               .appendTo( ul );
	    } else {
               return $( '<li></li>' )
               .append('<a><strong>'+item.lastname+' '+item.firstname+'</strong><br /><small>'+item.email+'</small></a>')
               .appendTo( ul );
	    }
          };

          // if manual input: set the hidden field empty again
          $('input[name=eventmail_chooseperson]').on("keyup",function() {
             $('input[name=send_previeweventmailto_id]').val('');
          }).change(function() {
             if ($(this).val()=='') {
                $('input[name=send_previeweventmailto_id]').val('');
                $(this).attr('readonly', false).removeClass('clearable');
             }
          });
   }

   // initially the div is not shown using display:none, so jquery has time to render it and then we call show()
   // we use localStorage to remember the last active tab
   $('#mail-tabs').tabs({
	   active: localStorage.getItem('mailtabs_currentIdx'),
	   activate: function(event, ui) {
		   localStorage.setItem('mailtabs_currentIdx', $(this).tabs('option', 'active'));
	   }
   });
   $('#mail-tabs').show();
   if ($('#mail-tabs').data('showtab')>=0) {
	   $('#mail-tabs').tabs( 'option', 'active', $('#mail-tabs').data('showtab') );
   }

   $('#eventmailButton').on("click",function (e) {
           e.preventDefault();
	   // if we want html mail, we need to save the html message first, otherwise the mail content is not ok via ajax submit
           if (eme.translate_htmlmail=='yes') {
                var editor = tinymce.get('event_mail_message');
		if ( editor !== null) {
                   editor.save();
		}
           }
	   var form_id = $(this.form).attr('id');
	   var alldata = new FormData($('#'+form_id)[0]);
	   alldata.append('action', 'eme_eventmail');
	   $('#eventmailButton').text(eme.translate_pleasewait);
	   $('#eventmailButton').prop('disabled', true);
	   $.ajax({url: ajaxurl, data: alldata, cache: false, contentType: false, processData: false, type: 'POST', dataType: 'json'})
	   .done(function(data){
		   $('div#eventmail-message').html(data.htmlmessage);
		   $('div#eventmail-message').show();
		   if (data.Result=='OK') {
		   	$('#'+form_id).trigger('reset');
			// the form reset doesn't reset select2 fields ...
			// so we call it ourselves
			$("#eme_eventmail_send_persons").val(null).trigger("change");
			$("#eme_eventmail_send_groups").val(null).trigger("change");
			$('input#event_send_now').trigger('change');
		        $('div#eventmail-message').delay(10000).fadeOut('slow');
		   }
		   $('#eventmailButton').text(eme.translate_sendmail);
		   $('#eventmailButton').prop('disabled', false);
	   });
           return false;
   });

   $('#genericmailButton').on("click",function (e) {
           e.preventDefault();
	   // if we want html mail, we need to save the html message first, otherwise the mail content is not ok via ajax submit
           if (eme.translate_htmlmail=='yes') {
                var editor = tinymce.get('generic_mail_message');
		if ( editor !== null) {
                   editor.save();
		}
           }
	   var form_id = $(this.form).attr('id');
	   var alldata = new FormData($('#'+form_id)[0]);
	   alldata.append('action', 'eme_genericmail');
	   $('#genericmailButton').text(eme.translate_pleasewait);
	   $('#genericmailButton').prop('disabled', true);
	   $.ajax({url: ajaxurl, data: alldata, cache: false, contentType: false, processData: false, type: 'POST', dataType: 'json'})
	   .done(function(data){
		   $('div#genericmail-message').html(data.htmlmessage);
		   $('div#genericmail-message').show();
		   if (data.Result=='OK') {
		   	$('#'+form_id).trigger('reset');
			// the form reset doesn't reset select2 fields ...
			// so we call it ourselves
			$("#eme_genericmail_send_persons").val(null).trigger("change");
			$("#eme_genericmail_send_peoplegroups").val(null).trigger("change");
			$("#eme_genericmail_send_members").val(null).trigger("change");
			$("#eme_genericmail_send_membergroups").val(null).trigger("change");
			$("#eme_send_memberships").val(null).trigger("change");
			// the form reset doesn't reset other show/hide stuff apparently ...
			// so we call it ourselves
			$('input#eme_send_all_people').trigger('change');
			$('input#generic_send_now').trigger('change');
			$('div#genericmail-message').delay(5000).fadeOut('slow');
		   }
		   $('#genericmailButton').text(eme.translate_sendmail);
		   $('#genericmailButton').prop('disabled', false);
	   });
           return false;
   });

   $('#previeweventmailButton').on("click",function (e) {
           e.preventDefault();
	   // if we want html mail, we need to save the html message first, otherwise the mail content is not ok via ajax submit
           if (eme.translate_htmlmail=='yes') {
                var editor = tinymce.get('event_mail_message');
		if ( editor !== null) {
                   editor.save();
		}
           }
	   var form_id = $(this.form).attr('id');
	   var alldata = new FormData($('#'+form_id)[0]);
	   alldata.append('action', 'eme_previeweventmail');
	   $.ajax({url: ajaxurl, data: alldata, cache: false, contentType: false, processData: false, type: 'POST', dataType: 'json'})
	   .done(function(data){
		   $('div#previeweventmail-message').html(data.htmlmessage);
		   $('div#previeweventmail-message').show();
		   $('div#previeweventmail-message').delay(5000).fadeOut('slow');
		   if (data.Result=='OK') {
                   	$('input[name=eventmail_chooseperson]').val('');
                   	$('input[name=send_previeweventmailto_id]').val('');
			$('input[name=eventmail_chooseperson]').attr('readonly', false);
		   }
	   });
           return false;
   });
   $('#previewmailButton').on("click",function (e) {
           e.preventDefault();
	   // if we want html mail, we need to save the html message first, otherwise the mail content is not ok via ajax submit
           if (eme.translate_htmlmail=='yes') {
                var editor = tinymce.get('generic_mail_message');
		if ( editor !== null) {
                   editor.save();
		}
           }
	   var form_id = $(this.form).attr('id');
	   var alldata = new FormData($('#'+form_id)[0]);
	   alldata.append('action', 'eme_previewmail');
	   $.ajax({url: ajaxurl, data: alldata, cache: false, contentType: false, processData: false, type: 'POST', dataType: 'json'})
	   .done(function(data){
		   $('div#previewmail-message').html(data.htmlmessage);
		   $('div#previewmail-message').show();
		   $('div#previewmail-message').delay(5000).fadeOut('slow');
		   if (data.Result=='OK') {
                   	$('input[name=chooseperson]').val('');
                   	$('input[name=send_previewmailto_id]').val('');
			$('input[name=chooseperson]').attr('readonly', false);
		   }
	   });
           return false;
   });

   $('#searchmailButton').on("click",function (e) {
           e.preventDefault();
	   var form_id = $(this.form).attr('id');
	   var alldata = new FormData($('#'+form_id)[0]);
	   alldata.append('action', 'eme_searchmail');
	   $.ajax({url: ajaxurl, data: alldata, cache: false, contentType: false, processData: false, type: 'POST', dataType: 'json'})
	   .done(function(data){
		   $('div#searchmail-message').html(data.htmlmessage);
		   $('div#searchmail-message').show();
	   });
           return false;
   });
   $('#searchmailButton').trigger('click');

   $('#testmailButton').on("click",function (e) {
           e.preventDefault();
	   var form_id = $(this.form).attr('id');
	   var alldata = new FormData($('#'+form_id)[0]);
	   alldata.append('action', 'eme_testmail');
	   $.ajax({url: ajaxurl, data: alldata, cache: false, contentType: false, processData: false, type: 'POST', dataType: 'json'})
	   .done(function(data){
		   $('div#testmail-message').html(data.htmlmessage);
		   $('div#testmail-message').show();
		   if (data.Result=='OK') {
		   	$('#'+form_id).trigger('reset');
		   }
	   });
           return false;
   });

   // show selected template in form
   $('select#event_subject_template').on("change",function (e) {
          e.preventDefault();
	  $.post(ajaxurl,
		  { action: 'eme_get_template',
		    template_id: $('select#event_subject_template').val(),
		  },
		  function(data){
		      $('input#event_mail_subject').val(data.htmlmessage);
		  }, 'json');

   });

   // show selected template in form
   $('select#event_message_template').on("change",function (e) {
          e.preventDefault();
	  $.post(ajaxurl,
		  { action: 'eme_get_template',
		    template_id: $('select#event_message_template').val(),
		  },
		  function(data){
			  $('textarea#event_mail_message').val(data.htmlmessage);
			  if (eme.translate_htmlmail=='yes') {
				  var editor = tinymce.get('event_mail_message');
				  if ( editor !== null) {
					  editor.setContent(data.htmlmessage);
					  editor.save();
				  }
			  }
		  }, 'json');

   });

   // show selected template in form
   //$('select#generic_subject_template').change(function (e) {
   //       e.preventDefault();
//	  $.post(ajaxurl,
//		  { action: 'eme_get_template',
//		    template_id: $('select#generic_subject_template').val(),
//		  },
//		  function(data){
//		      $('input#generic_mail_subject').val(data.htmlmessage);
//		  }, "json");
//  });

   // show selected template in form
   $('select#generic_message_template').on("change",function (e) {
          e.preventDefault();
	  $.post(ajaxurl,
		  { action: 'eme_get_template',
		    template_id: $('select#generic_message_template').val(),
		  },
		  function(data){
			  $('textarea#generic_mail_message').val(data.htmlmessage);
			  if (eme.translate_htmlmail=='yes') {
				  var editor = tinymce.get('generic_mail_message');
				  if ( editor !== null) {
					  editor.setContent(data.htmlmessage);
					  editor.save();
				  }
			  }
		  }, 'json');

   });

   function updateShowSendGroups () {
                if ($('input#eme_send_all_people').prop('checked')) {
                        $('div#div_eme_send_groups').hide();
                } else {
                        $('div#div_eme_send_groups').show();
                }
   }
   $('input#eme_send_all_people').on("change",updateShowSendGroups);
   updateShowSendGroups();

   function updateShowMailingName () {
                if ($('input#generic_send_now').prop('checked')) {
                        $('div#div_generic_mailing_definition').hide();
                } else {
                        $('div#div_generic_mailing_definition').show();
                }
                if ($('input#event_send_now').prop('checked')) {
                        $('div#div_event_mailing_definition').hide();
                } else {
                        $('div#div_event_mailing_definition').show();
                }
   }
   $('input#generic_send_now').on("change",updateShowMailingName);
   $('input#event_send_now').on("change",updateShowMailingName);
   updateShowMailingName();

   function updateShowMailTypes () {
	   if ($('select[name=eme_mail_type]').val() == 'attendees' || $('select[name=eme_mail_type]').val() == 'bookings') {
		   $('tr#eme_pending_approved_row').show();
		   $('tr#eme_only_unpaid_row').show();
		   $('tr#eme_exclude_registered_row').hide();
           } else {
		   $('tr#eme_pending_approved_row').hide();
		   $('tr#eme_only_unpaid_row').hide();
		   if ($('select[name=eme_mail_type]').val() != '') {
		      $('tr#eme_exclude_registered_row').show();
		   } else {
		      $('tr#eme_exclude_registered_row').hide();
		   }
           }
	   if ($('select[name=eme_mail_type]').val() == 'people_and_groups') {
		   $('tr#eme_people_row').show();
		   $('tr#eme_groups_row').show();
           } else {
		   $('tr#eme_people_row').hide();
		   $('tr#eme_groups_row').hide();
           }
   }
   $('select[name=eme_mail_type]').on("change",updateShowMailTypes);
   updateShowMailTypes();

    $('.eme_select2_events_class').select2({
        ajax: {
                    url: ajaxurl+'?action=eme_events_select2',
                    dataType: 'json',
                    delay: 1000,
                    data: function (params) {
                            var search_all=0;
			    if ($('#eventsearch_all').is(':checked')) {
				    search_all=1;
			    }
                            return {
                                    q: params.term, // search term
                                    search_all: search_all,
                                    eme_admin_nonce: emeadmin.translate_adminnonce
                            };
                    },
                    processResults: function (data, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data, except to indicate that infinite
                            // scrolling can be used
                            return {
                                    results: data.Records,
                            };
                    },
                    cache: true
        },
        placeholder: eme.translate_selectevents,
        width: '90%'
    });

        //Prepare jtable plugin
        jQuery('#MailingReportTableContainer').jtable({
            title: eme.translate_mailingreport,
            paging: true,
            sorting: true,
            jqueryuiTheme: true,
            defaultSorting: '',
            selecting: false, //Enable selecting
            multiselect: false, //Allow multiple selecting
            selectingCheckboxes: false, //Show checkboxes on first column
            selectOnRowClick: false, //Enable this to only select using checkboxes
            actions: {
                listAction: ajaxurl+'?action=eme_mailingreport_list&mailing_id='+$_GET['id'],
            },
            fields: {
                receiveremail: {
                    title: eme.translate_email,
                },
                receivername: {
                    title: eme.translate_name,
                },
                status: {
                    title: eme.translate_status,
                },
                sent_datetime: {
                    title: eme.translate_sentdatetime,
                    sorting: true
                },
                first_read_on: {
                    title: eme.translate_first_read_on,
                    sorting: true
                },
                last_read_on: {
                    title: eme.translate_last_read_on,
                    sorting: true
                },
                read_count: {
                    title: eme.translate_readcount,
                },
                error_msg: {
                    title: eme.translate_errormessage,
                    visibility: 'hidden',
                    sorting: false
                },
                action: {
                    title: eme.translate_action,
                    sorting: false
                }
            }
        });
        if ($('#MailingReportTableContainer').length) {
           $('#MailingReportTableContainer').jtable('load');
        }

        // Re-load records when user click 'load records' button.
        $('#ReportLoadRecordsButton').on("click",function (e) {
           e.preventDefault();
           $('#MailingReportTableContainer').jtable('load', {
               search_name: $('#search_name').val()
           });
           // return false to make sure the real form doesn't submit
           return false;
        });

        $('#eventmail_attach_button').on("click",function(e) {
                e.preventDefault();
                var custom_uploader = wp.media({
                        title: eme.translate_addattachments,
                        button: {
                                text: eme.translate_addattachments
                        },
                        multiple: true  // Set this to true to allow multiple files to be selected
                }).on('select', function() {
                        var selection = custom_uploader.state().get('selection');
                        // using map is not really needed, but this way we can reuse the code if multiple=true
                        // var attachment = custom_uploader.state().get('selection').first().toJSON();
                        selection.map( function(attach) {
                                attachment = attach.toJSON();
                                $('#eventmail_attach_links').append("<a target='_blank' href='"+attachment.url+"'>"+attachment.title+"</a><br />");
                                if ($('#eme_eventmail_attach_ids').val() != '') {
                                        tmp_ids_arr=$('#eme_eventmail_attach_ids').val().split(',');
                                } else {
                                        tmp_ids_arr=[];
                                }
                                tmp_ids_arr.push(attachment.id);
                                tmp_ids_val=tmp_ids_arr.join(',');
                                $('#eme_eventmail_attach_ids').val(tmp_ids_val);
                                $('#eventmail_attach_button').hide();
                                $('#eventmail_remove_attach_button').show();
                        });
                }).open();
        });
        if ($('#eme_eventmail_attach_ids').val() != '') {
                $('#eventmail_attach_button').hide();
                $('#eventmail_remove_attach_button').show();
        } else {
                $('#eventmail_attach_button').show();
                $('#eventmail_remove_attach_button').hide();
        }
        $('#eventmail_remove_attach_button').on("click",function(e) {
                e.preventDefault();
                $('#eventmail_attach_links').html('');
                $('#eme_eventmail_attach_ids').val('');
                $('#eventmail_attach_button').show();
                $('#eventmail_remove_attach_button').hide();
        });

        $('#generic_attach_button').on("click",function(e) {
                e.preventDefault();
                var custom_uploader = wp.media({
                        title: eme.translate_addattachments,
                        button: {
                                text: eme.translate_addattachments
                        },
                        multiple: true  // Set this to true to allow multiple files to be selected
                }).on('select', function() {
                        var selection = custom_uploader.state().get('selection');
                        // using map is not really needed, but this way we can reuse the code if multiple=true
                        // var attachment = custom_uploader.state().get('selection').first().toJSON();
                        selection.map( function(attach) {
                                attachment = attach.toJSON();
                                $('#generic_attach_links').append("<a target='_blank' href='"+attachment.url+"'>"+attachment.title+"</a><br />");
                                if ($('#eme_generic_attach_ids').val() != '') {
                                        tmp_ids_arr=$('#eme_generic_attach_ids').val().split(',');
                                } else {
                                        tmp_ids_arr=[];
                                }
                                tmp_ids_arr.push(attachment.id);
                                tmp_ids_val=tmp_ids_arr.join(',');
                                $('#eme_generic_attach_ids').val(tmp_ids_val);
                                $('#generic_attach_button').hide();
                                $('#generic_remove_attach_button').show();
                        });
                }).open();
        });
        if ($('#eme_generic_attach_ids').val() != '') {
                $('#generic_attach_button').hide();
                $('#generic_remove_attach_button').show();
        } else {
                $('#generic_attach_button').show();
                $('#generic_remove_attach_button').hide();
        }
        $('#generic_remove_attach_button').on("click",function(e) {
                e.preventDefault();
                $('#generic_attach_links').html('');
                $('#eme_generic_attach_ids').val('');
                $('#generic_attach_button').show();
                $('#generic_remove_attach_button').hide();
        });

	if ($('#eventmail_startdate').length) {
		$('#eventmail_startdate').fdatepicker({
			todayButton: new Date(),
                        clearButton: true,
                        timepicker: true,
                        minutesStep: parseInt(eme.translate_minutesStep),
                        language: eme.translate_flanguage,
                        firstDay: parseInt(eme.translate_firstDayOfWeek),
                        altFieldDateFormat: 'Y-m-d H:i:00',
                        dateFormat: eme.translate_fdateformat,
                        timeFormat: eme.translate_ftimeformat,
			onSelect: function(formattedDate,date,inst) {
				if (!Array.isArray(date)) {
					$('#eventmail-specificdates').text("");
				} else {
					$('#eventmail-specificdates').html('<br />'+eme.translate_selecteddates+'<br />');
					$.each(date, function( index, value ) {
						date_formatted = inst.formatDate(eme.translate_fdatetimeformat,value);
						$('#eventmail-specificdates').append(date_formatted+'<br />');
					});
				}
			}
		});
	}
	if ($('#genericmail_startdate').length) {
		$('#genericmail_startdate').fdatepicker({
			todayButton: new Date(),
                        clearButton: true,
                        timepicker: true,
                        minutesStep: parseInt(eme.translate_minutesStep),
                        language: eme.translate_flanguage,
                        firstDay: parseInt(eme.translate_firstDayOfWeek),
                        altFieldDateFormat: 'Y-m-d H:i:00',
                        dateFormat: eme.translate_fdateformat,
                        timeFormat: eme.translate_ftimeformat,
			onSelect: function(formattedDate,date,inst) {
				if (!Array.isArray(date)) {
					$('#genericmail-specificdates').text("");
				} else {
					$('#genericmail-specificdates').html('<br />'+eme.translate_selecteddates+'<br />');
					$.each(date, function( index, value ) {
						date_formatted = inst.formatDate(eme.translate_fdatetimeformat,value);
						$('#genericmail-specificdates').append(date_formatted+'<br />');
					});
				}
			}
		});
	}

	function updateGenericMailButtonText() {
		if ($('#generic_send_now').prop('checked')) {
			$('#genericmailButton').text(eme.translate_sendmail);
		} else {
			$('#genericmailButton').text(eme.translate_planmail);
		}
	}
	function updateEventMailButtonText() {
		if ($('#event_send_now').prop('checked')) {
			$('#eventmailButton').text(eme.translate_sendmail);
		} else {
			$('#eventmailButton').text(eme.translate_planmail);
		}
	}
	if ($('#generic_send_now').length) {
		updateGenericMailButtonText();
		$('#generic_send_now').on("change",updateGenericMailButtonText);
	} else {
		$('#genericmailButton').text(eme.translate_sendmail);
	}
	if ($('#event_send_now').length) {
		updateEventMailButtonText();
		$('#event_send_now').on("change",updateEventMailButtonText);
	} else {
		$('#eventmailButton').text(eme.translate_sendmail);
	}

});
