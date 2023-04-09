jQuery(document).ready(function ($) { 

        var rsvpfields = {
                booking_id: {
                    title: eme.translate_id,
                    key: true,
                    list: true,
                    width: '2%',
                    listClass: 'eme-jtable-center'
                },
                event_name: {
                    title: eme.translate_eventinfo
                },
                rsvp: {
                    title: eme.translate_rsvp,
                    sorting: false,
                    width: '2%',
                    listClass: 'eme-jtable-center'
                },
                datetime: {
                    title: eme.translate_datetime,
                    sorting: true
                },
                booker: {
                    title: eme.translate_booker
                },
                creation_date: {
                    title: eme.translate_bookingdate
                },
                seats: {
                    title: eme.translate_seats,
                    sorting: false,
                    listClass: 'eme-jtable-center'
                },
                eventprice: {
                    title: eme.translate_eventprice,
                    sorting: false
                },
                event_cats: {
                    title: eme.translate_event_cats,
                    sorting: false,
	            visibility: 'hidden'
                },
                discount: {
                    title: eme.translate_discount,
                    sorting: false,
	            visibility: 'hidden'
                },
                dcodes_used: {
                    title: eme.translate_dcodes_used,
                    sorting: false,
	            visibility: 'hidden'
                },
                totalprice: {
                    title: eme.translate_totalprice,
                    sorting: false
                },
                transfer_nbr_be97: {
                    title: eme.translate_uniquenbr
                },
                booking_paid: {
                    title: eme.translate_paid
                },
                remaining: {
                    title: eme.translate_remaining,
                    sorting: false,
	            visibility: 'hidden'
                },
                received: {
                    title: eme.translate_received,
                    sorting: false,
	            visibility: 'hidden'
                },
                payment_date: {
                    title: eme.translate_paymentdate,
	            visibility: 'hidden'
                },
                pg: {
                    title: eme.translate_pg,
                    visibility: 'hidden'
                },
                pg_pid: {
                    title: eme.translate_pg_pid,
                    visibility: 'hidden'
                },
                payment_id: {
                    title: eme.translate_paymentid
                },
                attend_count: {
                    title: eme.translate_attend_count,
	            visibility: 'hidden'
                },
                lastreminder: {
                    title: eme.translate_lastreminder,
                    sorting: false,
	            visibility: 'hidden'
                },
                booking_comment: {
                    title: eme.translate_comment,
                    sorting: false,
	            visibility: 'hidden'
                },
                wp_user: {
                    title: eme.translate_wpuser,
                    sorting: false,
	            visibility: 'hidden'
                }
	}
	var editfield = {
                edit_link: {
                    title: eme.translate_edit,
                    sorting: false,
                    visibility: 'fixed',
                    listClass: 'eme-jtable-center'
                }
	}

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

        //Prepare jtable plugin
        if ($('#BookingsTableContainer').length) {
		var extrafields=$('#BookingsTableContainer').data('extrafields').toString().split(',');
                var extrafieldnames=$('#BookingsTableContainer').data('extrafieldnames').toString().split(',');
                var extrafieldsearchable=$('#BookingsTableContainer').data('extrafieldsearchable').toString().split(',');
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
                                $.extend(rsvpfields,extrafield);
                        }
                });
		$.extend(rsvpfields,editfield);

		$('#BookingsTableContainer').jtable({
			title: eme.translate_bookings,
			paging: true,
			sorting: true,
			multiSorting: true,
			jqueryuiTheme: true,
			defaultSorting: '',
			selecting: true, //Enable selecting
			multiselect: true, //Allow multiple selecting
			selectingCheckboxes: true, //Show checkboxes on first column
			selectOnRowClick: true, //Enable this to only select using checkboxes
			toolbar: {
				items: [{
					text: eme.translate_paidandapprove,
					cssClass: 'eme_jtable_button_for_pending_only',
					click: function () {
						var selectedRows = $('#BookingsTableContainer').jtable('selectedRows');
						var do_action = 'paidandapprove';
						if (selectedRows.length > 0) {
							var ids = [];
							selectedRows.each(function () {
								ids.push($(this).data('record')['booking_id']);
							});
							var idsjoined = ids.join(); //will be such a string '2,5,7'
							$('.eme_jtable_button_for_pending_only .jtable-toolbar-item-text').text(eme.translate_pleasewait);
							$.post(ajaxurl, {'booking_ids': idsjoined, 'action': 'eme_manage_bookings', 'do_action': do_action, 'eme_admin_nonce': eme.translate_adminnonce }, function(data) {
								if (data.Result!='OK') {
									$('div#bookings-message').html(data.htmlmessage);
									$('div#bookings-message').show();
									$('div#bookings-message').delay(3000).fadeOut('slow');
								}

								$('#BookingsTableContainer').jtable('reload');
								$('.eme_jtable_button_for_pending_only .jtable-toolbar-item-text').text(eme.translate_paidandapprove);
							}, 'json');
						}
					}
				},
					{
						text: eme.translate_csv,
						click: function () {
							jtable_csv('#BookingsTableContainer');
						}
					},
					{
						text: eme.translate_print,
						click: function () {
							$('#BookingsTableContainer').printElement();
						}
					}
				]
			},
			actions: {
				listAction: ajaxurl+'?action=eme_bookings_list&trash='+$_GET['trash']
			},
			fields: rsvpfields
		});
	}

        // Load list from server, but only if the container is there
        // and only in the initial load we take a possible person id in the url into account
        // This person id can come from the eme_people page when clicking on "view all bookings"
        if ($('#BookingsTableContainer').length) {
           $('#BookingsTableContainer').jtable('load', {
               'scope': $('#scope').val(),
               'category': $('#category').val(),
               'booking_status': $('#booking_status').val(),
               'search_event': $('#search_event').val(),
               'search_person': $('#search_person').val(),
               'search_customfields': $('#search_customfields').val(),
               'search_unique': $('#search_unique').val(),
               'search_paymentid': $('#search_paymentid').val(),
               'search_pg_pid': $('#search_pg_pid').val(),
               'search_start_date': $('#search_start_date').val(),
               'search_end_date': $('#search_end_date').val(),
               'event_id': $('#event_id').val(),
               'person_id': $_GET['person_id']
           });
        }

        function updateShowHideStuff () {
	   var action=$('select#eme_admin_action').val();
           if ($.inArray(action,['trashBooking','approveBooking','pendingBooking','unsetwaitinglistBooking','setwaitinglistBooking','markPaid','markUnpaid']) >= 0) {
              $('span#span_sendmails').show();
           } else {
              $('span#span_sendmails').hide();
           }
           if (($.inArray(action,['trashBooking','pendingBooking','setwaitinglistBooking','markUnpaid']) >= 0) && (typeof $_GET['trash']==='undefined' || $_GET['trash']==0)) {
              $('span#span_refund').show();
           } else {
              $('span#span_refund').hide();
           }
           if ($.inArray(action,['partialPayment']) >= 0) {
              $('span#span_partialpayment').show();
           } else {
              $('span#span_partialpayment').hide();
           }
           if (action == 'rsvpMails') {
              jQuery('span#span_rsvpmailtemplate').show();
           } else {
              jQuery('span#span_rsvpmailtemplate').hide();
           }
           if (action == 'pdf') {
              jQuery('span#span_pdftemplate').show();
           } else {
              jQuery('span#span_pdftemplate').hide();
           }
           if (action == 'html') {
              jQuery('span#span_htmltemplate').show();
           } else {
              jQuery('span#span_htmltemplate').hide();
           }
        }
        $('select#eme_admin_action').on("change",updateShowHideStuff);
        updateShowHideStuff();

        // hide one toolbar button if not on pending approval and trash=0 (or not set)
        function hideButtonPaidApprove() {
           if ($('#booking_status').val() == "PENDING" && (typeof $_GET['trash']==='undefined' || $_GET['trash']==0)) {
              $('.eme_jtable_button_for_pending_only').show();
           } else {
              $('.eme_jtable_button_for_pending_only').hide();
           }
        }
        hideButtonPaidApprove();

        // Actions button
        $('#BookingsActionsButton').on("click",function (e) {
           e.preventDefault();
           var selectedRows = $('#BookingsTableContainer').jtable('selectedRows');
           var do_action = $('#eme_admin_action').val();
           var send_mail = $('#send_mail').val();
           var refund = $('#refund').val();
           var partial_amount = $('#partial_amount').val();
           var rsvpmail_template = $('#rsvpmail_template').val();
	   var rsvpmail_template_subject = $('#rsvpmail_template_subject').val();
           var pdf_template = $('#pdf_template').val();
	   var pdf_template_header = $('#pdf_template_header').val();
           var pdf_template_footer = $('#pdf_template_footer').val();
           var html_template = $('#html_template').val();
           var html_template_header = $('#html_template_header').val();
           var html_template_footer = $('#html_template_footer').val();

           var action_ok=1;
           if (selectedRows.length > 0) {
              if ((do_action=='deleteRegistration') && !confirm(eme.translate_areyousuretodeleteselected)) {
                 action_ok=0;
              }
              if ((do_action=='partialPayment') && selectedRows.length > 1) {
		 alert(eme.translate_selectonerowonlyforpartial);
                 action_ok=0;
              }
              if (action_ok==1) {
		 $('#BookingsActionsButton').text(eme.translate_pleasewait);
		 $('#BookingsActionsButton').prop('disabled', true);
                 var ids = [];
		 var form;
                 selectedRows.each(function () {
                   ids.push($(this).data('record')['booking_id']);
                 });

                 var idsjoined = ids.join(); //will be such a string '2,5,7'
                 var params = {
                        'booking_ids': idsjoined,
                        'action': 'eme_manage_bookings',
                        'do_action': do_action,
                        'send_mail': send_mail,
                        'refund': refund,
                        'partial_amount': partial_amount,
                        'rsvpmail_template': rsvpmail_template,
                        'rsvpmail_template_subject': rsvpmail_template_subject,
                        'pdf_template': pdf_template,
                        'pdf_template_header': pdf_template_header,
                        'pdf_template_footer': pdf_template_footer,
                        'html_template': html_template,
                        'html_template_header': html_template_header,
                        'html_templata_footer': html_template_footer,
                        'eme_admin_nonce': eme.translate_adminnonce };

                 if (do_action=='sendMails') {
                         form = $('<form method="POST" action="'+eme.translate_admin_sendmails_url+'">');
                         params = {
                                 'booking_ids': idsjoined,
                                 'eme_admin_action': 'new_mailing'
                                 };
                         $.each(params, function(k, v) {
                                 form.append($('<input type="hidden" name="' + k + '" value="' + v + '">'));
                         });
                         $('body').append(form);
                         form.trigger("submit");
                         return false;
                 }

                 if (do_action=='pdf' || do_action=='html') {
                         form = $('<form method="POST" action="' + ajaxurl + '">');
                         $.each(params, function(k, v) {
                                 form.append($('<input type="hidden" name="' + k + '" value="' + v + '">'));
                         });
                         $('body').append(form);
                         form.trigger("submit");
                         $('#BookingsActionsButton').text(eme.translate_apply);
                         $('#BookingsActionsButton').prop('disabled', false);
                         return false;
                 }
                 $.post(ajaxurl, params, function(data) {
	            $('#BookingsTableContainer').jtable('reload');
		    $('#BookingsActionsButton').text(eme.translate_apply);
		    $('#BookingsActionsButton').prop('disabled', false);
		    $('div#bookings-message').html(data.htmlmessage);
		    $('div#bookings-message').show();
		    $('div#bookings-message').delay(3000).fadeOut('slow');
                 }, 'json');
              }
           }
           // return false to make sure the real form doesn't submit
           return false;
        });

        // Re-load records when user click 'load records' button.
        $('#BookingsLoadRecordsButton').on("click",function (e) {
           e.preventDefault();
           $('#BookingsTableContainer').jtable('load', {
               'scope': $('#scope').val(),
               'category': $('#category').val(),
               'event_id': $('#event_id').val(),
               'booking_status': $('#booking_status').val(),
               'search_event': $('#search_event').val(),
               'search_person': $('#search_person').val(),
               'search_customfields': $('#search_customfields').val(),
               'search_unique': $('#search_unique').val(),
               'search_paymentid': $('#search_paymentid').val(),
               'search_pg_pid': $('#search_pg_pid').val(),
               'search_start_date': $('#search_start_date').val(),
               'search_end_date': $('#search_end_date').val()
           });
           // return false to make sure the real form doesn't submit
           return false;
        });

    // for autocomplete to work, the element needs to exist, otherwise JS errors occur
    // we check for that using length
    if ($('input[name=chooseevent]').length) {
          $('input[name=chooseevent]').autocomplete({
            source: function(request, response) {
		        var search_all=0;
                        if ($('#eventsearch_all').is(':checked')) {
                                    search_all=1;
                        }
                         $.post(ajaxurl,
                                  { q: request.term,
                                    exclude_id: $('#event_id').val(),
                                    only_rsvp: 1,
                                    search_all: search_all,
                                    action: 'eme_autocomplete_event'
                                  },
                                  function(data){
                                       response($.map(data, function(item) {
                                          return {
                                             eventinfo: eme_htmlDecode(item.eventinfo),
                                             transferto_id: eme_htmlDecode(item.event_id),
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
                            ui.content.push({ transferto_id: 0 });
                            $(event.target).val("");
                       }
            },
            select:function(event, ui) {
                    // when a person is selected, populate related fields in this form
                    if (ui.item.transferto_id>0) {
                         $('input[name=transferto_id]').val(ui.item.transferto_id);
                         $(event.target).val(ui.item.eventinfo).attr("readonly", true).addClass('clearable x');
                    }
                    return false;
            },
            minLength: 2
          }).data( 'ui-autocomplete' )._renderItem = function( ul, item ) {
            if (item.transferto_id==0) {
               return $( '<li></li>' )
               .append('<strong>'+eme.translate_nomatchevent+'</strong>')
               .appendTo( ul );
            } else {
               return $( '<li></li>' )
               .append('<a><strong>'+item.eventinfo+'</strong></a>')
               .appendTo( ul );
            }
          };

          // if manual input: set the hidden field empty again
          $('input[name=chooseevent]').on("change",function() {
             if ($(this).val()=='') {
		$(this).attr('readonly', false).removeClass('clearable');
                $('input[name=transferto_id]').val('');
             }
          });
    }
});
