jQuery(document).ready(function ($) { 

        var memberfields = {
                'members.member_id': {
                    key: true,
		    title: eme.translate_memberid,
                    visibility: 'hidden'
                },
		person_id: {
		    title: eme.translate_personid,
		    visibility: 'hidden'
		},
                lastname: {
		    title: eme.translate_lastname
                },
                firstname: {
		    title: eme.translate_firstname
                },
                email: {
		    title: eme.translate_email
                },
		related_member_id: {
                    title: eme.translate_related_to,
                    visibility: 'hidden'
                },
		address1: {
                    title: eme.translate_address1,
                    visibility: 'hidden'
                },
                address2: {
                    title: eme.translate_address2,
                    visibility: 'hidden'
                },
                city: {
                    title: eme.translate_city,
                    visibility: 'hidden'
                },
                zip: {
                    title: eme.translate_zip,
                    visibility: 'hidden'
                },
                state: {
                    title: eme.translate_state,
                    visibility: 'hidden'
                },
                country: {
                    title: eme.translate_country,
                    visibility: 'hidden'
                },
		birthdate: {
                    title: eme.translate_birthdate,
                    visibility: 'hidden'
                },
                birthplace: {
                    title: eme.translate_birthplace,
                    visibility: 'hidden'
                },
                membership_name: {
		    title: eme.translate_membership,
                    visibility: 'hidden'
                },
		membershipprice: {
                    title: eme.translate_membershipprice,
                    visibility: 'hidden',
                    sorting: false
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
                    visibility: 'hidden',
                    sorting: false
                },
                start_date: {
		    title: eme.translate_startdate,
                    visibility: 'hidden'
                },
                end_date: {
		    title: eme.translate_enddate,
                    visibility: 'hidden'
                },
                creation_date: {
		    title: eme.translate_registrationdate,
                    visibility: 'hidden'
                },
                last_seen: {
		    title: eme.translate_last_seen,
                    visibility: 'hidden'
                },
                paid: {
		    title: eme.translate_paid,
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
                    title: eme.translate_paymentid,
                    visibility: 'hidden'
                },
                reminder_date: {
		    title: eme.translate_lastreminder,
                    visibility: 'hidden'
                },
                reminder: {
		    title: eme.translate_nbrreminder,
                    visibility: 'hidden'
                },
                status: {
		    title: eme.translate_status,
                    visibility: 'hidden'
                },
                wp_user: {
                    title: eme.translate_wpuser,
                    sorting: false,
                    visibility: 'hidden'
                }
	}
        var membershipfields = {
		membership_id: {
			key: true,
			title: eme.translate_id,
			visibility: 'hidden'
		},
		name: {
			title: eme.translate_name
		},
		description: {
			title: eme.translate_description
		},
		membercount: {
			title: eme.translate_membercount,
			sorting: false
		},
		contact: {
			title: eme.translate_contact,
			sorting: false
		}
	}
        if ($('#MembersTableContainer').length) {
                var extrafields=$('#MembersTableContainer').data('extrafields').toString().split(',');
                var extrafieldnames=$('#MembersTableContainer').data('extrafieldnames').toString().split(',');
                var extrafieldsearchable=$('#MembersTableContainer').data('extrafieldsearchable').toString().split(',');
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
                        	$.extend(memberfields,extrafield);
			}
                });

		//Prepare jtable plugin
		$('#MembersTableContainer').jtable({
			title: eme.translate_members,
			paging: true,
			sorting: true,
			multiSorting: true,
			jqueryuiTheme: true,
			defaultSorting: '',
			selecting: true, //Enable selecting
			multiselect: true, //Allow multiple selecting
			selectingCheckboxes: true, //Show checkboxes on first column
			selectOnRowClick: true,
			toolbar: {
				items: [{
						text: eme.translate_csv,
						click: function () {
							jtable_csv('#MembersTableContainer');
						}
					},
					{
						text: eme.translate_print,
						click: function () {
							$('#MembersTableContainer').printElement();
						}
					}
				]
			},
			actions: {
				listAction: ajaxurl+'?action=eme_members_list'
			},
			fields: memberfields
		});
		$('#MembersTableContainer').jtable('load', {
			'search_person': $('#search_person').val(),
			'search_memberstatus': $('#search_memberstatus').val(),
			'search_membershipids': $('#search_membershipids').val(),
			'search_memberid': $('#search_memberid').val(),
			'search_customfields': $('#search_customfields').val(),
			'search_customfieldids': $('#search_customfieldids').val()
		});
	}

        if ($('#MembershipsTableContainer').length) {
                var extrafields=$('#MembershipsTableContainer').data('extrafields').toString().split(',');
                var extrafieldnames=$('#MembershipsTableContainer').data('extrafieldnames').toString().split(',');
                var extrafieldsearchable=$('#MembershipsTableContainer').data('extrafieldsearchable').toString().split(',');
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
                        	$.extend(membershipfields,extrafield);
			}
                });
		$('#MembershipsTableContainer').jtable({
			title: eme.translate_memberships,
			paging: true,
			sorting: true,
			multiSorting: true,
			jqueryuiTheme: true,
			defaultSorting: 'name ASC',
			selecting: true, //Enable selecting
			multiselect: true, //Allow multiple selecting
			selectingCheckboxes: true, //Show checkboxes on first column
			selectOnRowClick: true,
			actions: {
				listAction: ajaxurl+'?action=eme_memberships_list'
			},
			fields: membershipfields
		});
		$('#MembershipsTableContainer').jtable('load');
        }
 
        // Actions button
        $('#MembershipsActionsButton').on("click",function (e) {
	   e.preventDefault();
           var selectedRows = $('#MembershipsTableContainer').jtable('selectedRows');
           var do_action = $('#eme_admin_action').val();
           var action_ok=1;
           if (selectedRows.length > 0) {
              if ((do_action=='deleteMemberships') && !confirm(eme.translate_areyousuretodeleteselected)) {
                 action_ok=0;
              }
              if (action_ok==1) {
                 $('#MembershipsActionsButton').text(eme.translate_pleasewait);
		 $('#MembershipsActionsButton').prop('disabled', true);
                 var ids = [];
                 selectedRows.each(function () {
                   ids.push($(this).data('record')['membership_id']);
                 });

                 var idsjoined = ids.join(); //will be such a string '2,5,7'
                 $.post(ajaxurl, {'membership_id': idsjoined, 'action': 'eme_manage_memberships', 'do_action': do_action, 'eme_admin_nonce': eme.translate_adminnonce }, function(data) {
			$('#MembershipsTableContainer').jtable('reload');
			$('#MembershipsActionsButton').text(eme.translate_apply);
		 	$('#MembershipsActionsButton').prop('disabled', false);
			$('div#memberships-message').html(data.htmlmessage);
			$('div#memberships-message').show();
			$('div#memberships-message').delay(3000).fadeOut('slow');
                 }, 'json');
              }
           }
           // return false to make sure the real form doesn't submit
           return false;
        });

        // Actions button
        $('#MembersActionsButton').on("click",function (e) {
	   e.preventDefault();
           var selectedRows = $('#MembersTableContainer').jtable('selectedRows');
           var do_action = $('#eme_admin_action').val();
	   var send_mail = $('#send_mail').val();
	   var trash_person = $('#trash_person').val();
	   var membermail_template = $('#membermail_template').val();
	   var membermail_template_subject = $('#membermail_template_subject').val();
	   var pdf_template = $('#pdf_template').val();
           var pdf_template_header = $('#pdf_template_header').val();
           var pdf_template_footer = $('#pdf_template_footer').val();
           var html_template = $('#html_template').val();
           var html_template_header = $('#html_template_header').val();
           var html_template_footer = $('#html_template_footer').val();

           var action_ok=1;
           if (selectedRows.length > 0) {
              if ((do_action=='deleteMembers') && !confirm(eme.translate_areyousuretodeleteselected)) {
                 action_ok=0;
              }
              if (action_ok==1) {
                 $('#MembersActionsButton').text(eme.translate_pleasewait);
		 $('#MembersActionsButton').prop('disabled', true);
                 var ids = [];
                 selectedRows.each(function () {
                   ids.push($(this).data('record')['members.member_id']);
                 });

                 var idsjoined = ids.join(); //will be such a string '2,5,7'
		 var form;
		 var params = {
			'member_id': idsjoined,
			'action': 'eme_manage_members',
			'do_action': do_action,
			'send_mail': send_mail,
			'trash_person': trash_person,
			'pdf_template': pdf_template,
                        'pdf_template_header': pdf_template_header,
                        'pdf_template_footer': pdf_template_footer,
                        'membermail_template': membermail_template,
                        'membermail_template_subject': membermail_template_subject,
                        'html_template': html_template,
                        'html_template_header': html_template_header,
                        'html_templata_footer': html_template_footer,
			'eme_admin_nonce': eme.translate_adminnonce };

		 if (do_action=='sendMails') {
                         form = $('<form method="POST" action="'+eme.translate_admin_sendmails_url+'">');
                         params = {
                                 'member_ids': idsjoined,
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
			 $('#MembersActionsButton').text(eme.translate_apply);
			 $('#MembersActionsButton').prop('disabled', false);
			 return false;
		 }
                 $.post(ajaxurl, params, function(data) {
	                        $('#MembersTableContainer').jtable('reload');
                                $('#MembersActionsButton').text(eme.translate_apply);
		                $('#MembersActionsButton').prop('disabled', false);
				$('div#members-message').html(data.htmlmessage);
				$('div#members-message').show();
			        $('div#members-message').delay(3000).fadeOut('slow');
                        }, 'json');
              }
           }
           // return false to make sure the real form doesn't submit
           return false;
        });
 
        // Re-load records when user click 'load records' button.
        $('#MembersLoadRecordsButton').on("click",function (e) {
           e.preventDefault();
           $('#MembersTableContainer').jtable('load', {
               'search_person': $('#search_person').val(),
               'search_memberstatus': $('#search_memberstatus').val(),
               'search_membershipids': $('#search_membershipids').val(),
               'search_memberid': $('#search_memberid').val(),
               'search_customfields': $('#search_customfields').val(),
               'search_customfieldids': $('#search_customfieldids').val()
           });
	   if ($('#search_person').val().length || $('#search_memberstatus').val().length || $('#search_membershipids').val().length || $('#search_memberid').val().length || $('#search_customfields').val().length || $('#search_customfieldids').val().length) {
		   $('#StoreQueryButton').show();
	   } else {
		   $('#StoreQueryButton').hide();
	   }
	   $('#StoreQueryDiv').hide();
           // return false to make sure the real form doesn't submit
           return false;
        });
	$('#StoreQueryButton').on("click",function (e) {
           e.preventDefault();
	   $('#StoreQueryButton').hide();
	   $('#StoreQueryDiv').show();
           // return false to make sure the real form doesn't submit
           return false;
	});
	$('#StoreQuerySubmitButton').on("click",function (e) {
           e.preventDefault();
	   var params = {
               'search_person': $('#search_person').val(),
               'search_memberstatus': $('#search_memberstatus').val(),
               'search_membershipids': $('#search_membershipids').val(),
               'search_memberid': $('#search_memberid').val(),
               'search_customfields': $('#search_customfields').val(),
               'search_customfieldids': $('#search_customfieldids').val(),
	       'action': 'eme_store_members_query',
	       'eme_admin_nonce': eme.translate_adminnonce,
               'dynamicgroupname': $('#dynamicgroupname').val()
	   };
           $.post(ajaxurl, params, function(data) {
		   $('#StoreQueryButton').hide();
		   $('#StoreQueryDiv').hide();
		   $('div#members-message').html(data.htmlmessage);
		   $('div#members-message').show();
		   $('div#members-message').delay(3000).fadeOut('slow');
           }, 'json');
           // return false to make sure the real form doesn't submit
           return false;
	});
	$('#StoreQueryButton').hide();
	$('#StoreQueryDiv').hide();

        function updateShowHideFixedStartdate () {
           if ($('select#type').val() == 'fixed') {
              $('tr#startdate').show();
           } else {
              $('tr#startdate').hide();
           }
        }
	if ($('select#type').length) {
           $('select#type').on("change",updateShowHideFixedStartdate);
           updateShowHideFixedStartdate();
	}
        function updateShowHideReminder () {
           if ($('select#duration_period').val() == 'forever') {
              $('tr#reminder').hide();
              $('#duration_count').hide();
           } else {
              $('tr#reminder').show();
              $('#duration_count').show();
           }
        }
	if ($('select#duration_period').length) {
           $('select#duration_period').on("change",updateShowHideReminder);
           updateShowHideReminder();
	}

        //function updateShowHideMemberState () {
        //   if ($('select#status_automatic').val() == '1') {
        //      $('select#status').attr('disabled', true);
        //   } else {
        //      $('select#status').attr('disabled', false);
        //   }
        //}
	//if ($('select#status_automatic').length) {
        //   $('select#status_automatic').on("change",updateShowHideMemberState);
        //   updateShowHideMemberState();
	//}

	function updateShowHideRenewal () {
		if ($('input#allow_renewal').prop('checked')) {
			$('tr#tr_renewal_cutoff_days').fadeIn();
		} else {
			$('tr#tr_renewal_cutoff_days').fadeOut();
		}
	}
	$('input#allow_renewal').on("change",updateShowHideRenewal);
	updateShowHideRenewal();

	function updateShowHideOffline () {
		if ($('input[name="properties[use_offline]"]').prop('checked')) {
			$('tr#tr_offline').fadeIn();
		} else {
			$('tr#tr_offline').fadeOut();
		}
	}
	$('input[name="properties[use_offline]"]').on("change",updateShowHideOffline);
	updateShowHideOffline();

	// initially the div is not shown using display:none, so jquery has time to render it and then we call show()
	if ($('#membership-tabs').length) {
		$('#membership-tabs').tabs();
		$('#membership-tabs').show();
		// the validate plugin can take other tabs/hidden fields into account
		$('#membershipForm').validate({
			// ignore: false is added so the fields of tabs that are not visible when editing an event are evaluated too
			ignore: false,
			focusCleanup: true,
			errorClass: "eme_required",
			invalidHandler: function(e,validator) {
				$.each(validator.invalid, function(key, value) {
					// get the closest tabname
					var tabname=$('[name='+key+']').closest('.ui-tabs-panel').attr('id');
					// activate the tab that has the error
					var tabindex = $('#membership-tabs a[href="#'+tabname+'"]').parent().index();
					$("#membership-tabs").tabs("option", "active", tabindex);
					// break the loop, we only want to switch to the first tab with the error
					return false;
				});
			}
		});

	}

	// for autocomplete to work, the element needs to exist, otherwise JS errors occur
	// we check for that using length
	if ($('input[name=chooserelatedmember]').length) {
		$('input[name=chooserelatedmember]').autocomplete({
			source: function(request, response) {
				$.post(ajaxurl,
					{ q: request.term,
					  'member_id': $('#member_id').val(),
					  'membership_id': $('#membership_id').val(),
					  action: 'wp_ajax_eme_autocomplete_membermainaccount'
					},
					function(data){
						response($.map(data, function(item) {
							return {
								lastname: eme_htmlDecode(item.lastname),
								firstname: eme_htmlDecode(item.firstname),
								email: eme_htmlDecode(item.email),
								member_id: eme_htmlDecode(item.member_id)
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
					ui.content.push({ member_id: 0 });
					$(event.target).val("");
				}
			},
			select:function(event, ui) {
				// when a person is selected, populate related fields in this form
				if (ui.item.member_id>0) {
					$('input[name=related_member_id]').val(ui.item.member_id);
					$(event.target).val(ui.item.lastname+' '+ui.item.firstname+' ('+ui.item.member_id+')').attr('readonly', true).addClass('clearable x');
				}
				return false;
			},
			minLength: 2
		}).data( 'ui-autocomplete' )._renderItem = function( ul, item ) {
			if (item.member_id==0) {
				return $( '<li></li>' )
					.append('<strong>'+eme.translate_nomatchmember+'</strong>')
					.appendTo( ul );
			} else {
				return $( '<li></li>' )
					.append('<a><strong>'+item.lastname+' '+item.firstname+' ('+item.member_id+')'+'</strong><br /><small>'+item.email+ '</small></a>')
					.appendTo( ul );
			}
		};

		// if manual input: set the hidden field empty again
		$('input[name=chooserelatedmember]').on("keyup",function() {
			$('input[name=related_member_id]').val('');
		}).on("change",function() {
			if ($('input[name=chooserelatedmember]').val()=='') {
				$('input[name=related_member_id]').val('');
				$('input[name=chooserelatedmember]').attr('readonly', false).removeClass('clearable');
			}
		});
	}

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
								person_id: item.person_id,
								wp_id: item.wp_id
							};
						}));
					}, 'json');
			},
			change: function (event, ui) {
				if(!ui.item){
					$(event.target).val('');
				}
			},
			response: function (event, ui) {
				if (!ui.content.length) {
					ui.content.push({ person_id: 0 });
					$(event.target).val('');
				}
			},
			select:function(event, ui) {
				// when a person is selected, populate some fields in this form, hide the rest of personal info (too complicated)
				event.preventDefault();
				if (ui.item.person_id>0) {
					$('.personal_info').hide();
					$('input[name=lastname]').val(ui.item.lastname).attr('readonly', true).show();
					$('input[name=firstname]').val(ui.item.firstname).attr('readonly', true).show();
					$('input[name=email]').val(ui.item.email).attr('readonly', true).show();
					$('input[name=person_id]').val(ui.item.person_id);
					$(event.target).val(ui.item.lastname+' '+ui.item.firstname).attr('readonly', true).addClass('clearable x');
					$('input[name=wp_id]').val(ui.item.wp_id);
					$('input[name=wp_id]').trigger('input');
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
		$('input[name=chooseperson]').on("change",function() {
			if ($(this).val()=='') {
				$('input[name=person_id]').val('');
				$('input[name=lastname]').val('').attr('readonly', false);
				$('input[name=firstname]').val('').attr('readonly', false);
				$('input[name=email]').val('').attr('readonly', false);
				$('input[name=wp_id]').val('');
				$('input[name=wp_id]').trigger('input');
				$(this).attr('readonly', false).removeClass('clearable');
				$('.personal_info').show();
			}
		});
	}

	// for autocomplete to work, the element needs to exist, otherwise JS errors occur
	// we check for that using length
	if ($('input[name=transferperson]').length) {
		$('input[name=transferperson]').autocomplete({
			source: function(request, response) {
				$.post(ajaxurl,
					{ 'q': request.term,
					  'action': 'eme_autocomplete_memberperson',
					  'exclude_personid': $('input[name=person_id]').val(),
					  'membership_id': $('#membership_id').val(),
					  'related_member_id': $('#related_member_id').val()
					},
					function(data){
						response($.map(data, function(item) {
							return {
								lastname: eme_htmlDecode(item.lastname),
								firstname: eme_htmlDecode(item.firstname),
								email: eme_htmlDecode(item.email),
								person_id: item.person_id
							};
						}));
					}, 'json');
			},
			change: function (event, ui) {
				if(!ui.item){
					$(event.target).val('');
				}
			},
			response: function (event, ui) {
				if (!ui.content.length) {
					ui.content.push({ person_id: 0 });
					$(event.target).val('');
				}
			},
			select:function(event, ui) {
				event.preventDefault();
				if (ui.item.person_id>0) {
					$('input[name=transferto_personid]').val(ui.item.person_id);
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
		$('input[name=transferperson]').on("change",function() {
			if ($(this).val()=='') {
				$('input[name=transferto_personid]').val('');
				$(this).attr('readonly', false).removeClass('clearable');
			}
		});
	}

	function updateShowHideAdminActions () {
           var action=$('select#eme_admin_action').val();
           if ($.inArray(action,['acceptPayment','stopMembership']) >= 0) {
	      $('#send_mail').val(1);
              $('span#span_sendmails').show();
	   } else if (action == 'markUnpaid') {
	      $('#send_mail').val(0);
              $('span#span_sendmails').show();
           } else {
              $('span#span_sendmails').hide();
           }
           if (action == 'deleteMembers') {
              $('span#span_trashperson').show();
           } else {
              $('span#span_trashperson').hide();
           }
           if (action == 'memberMails') {
              $('span#span_membermailtemplate').show();
           } else {
              $('span#span_membermailtemplate').hide();
           }
           if (action == 'pdf') {
              $('span#span_pdftemplate').show();
           } else {
              $('span#span_pdftemplate').hide();
           }
           if (action == 'html') {
              $('span#span_htmltemplate').show();
           } else {
              $('span#span_htmltemplate').hide();
           }
        }
        $('select#eme_admin_action').on("change",updateShowHideAdminActions);
        updateShowHideAdminActions();

	function updateShowHideFamilytpl () {
                if ($('input#family_membership').prop('checked')) {
                        $('tr#familymember_form_tpl').fadeIn();
                } else {
                        $('tr#familymember_form_tpl').fadeOut();
                }
        }
        $('input#family_membership').on("change",updateShowHideFamilytpl);
        updateShowHideFamilytpl();

        $('#newmember_attach_button').on("click",function(e) {
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
                                $('#newmember_attach_links').append("<a target='_blank' href='"+attachment.url+"'>"+attachment.title+"</a><br />");
                                if ($('#eme_newmember_attach_ids').val() != '') {
                                        tmp_ids_arr=$('#eme_newmember_attach_ids').val().split(',');
                                } else {
                                        tmp_ids_arr=[];
                                }
                                tmp_ids_arr.push(attachment.id);
                                tmp_ids_val=tmp_ids_arr.join(',');
                                $('#eme_newmember_attach_ids').val(tmp_ids_val);
                                $('#newmember_attach_button').hide();
                                $('#newmember_remove_attach_button').show();
                        });
                }).open();
        });
        if ($('#eme_newmember_attach_ids').val() != '') {
                $('#newmember_attach_button').hide();
                $('#newmember_remove_attach_button').show();
        } else {
                $('#newmember_attach_button').show();
                $('#newmember_remove_attach_button').hide();
        }
        $('#newmember_remove_attach_button').on("click",function(e) {
                e.preventDefault();
                $('#newmember_attach_links').html('');
                $('#eme_newmember_attach_ids').val('');
                $('#newmember_attach_button').show();
                $('#newmember_remove_attach_button').hide();
        });

});
