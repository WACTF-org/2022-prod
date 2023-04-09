function base64_encode(data) {
  if (!data) {
    return data;
  }

  return window.btoa(unescape(encodeURIComponent(data)));
}

function areyousure(message) {
   if (!confirm(message)) {
      return false;
   } else {
      return true;
   }
}

jQuery(document).ready( function($) {

	// let's set the default size to match placeholders if present
        $("input[placeholder]").each(function () {
                $(this).attr('size', $(this).attr('placeholder').length);
        });

	// using this on-syntax also works for selects added to the page afterwards
	$(document).on('click', 'input.select-all', function() {
                $('input.row-selector').prop('checked', this.checked)
        });
        $(document).on('click', 'input.row-selector', function() {
                if($("input.row-selector").length==$(".row-selector:checked").length) {
                        $("input.select-all").prop("checked",true);
                } else {
                        $("input.select-all").prop("checked",false);
                }
        });

        $('div[data-dismissible] button.notice-dismiss').on("click",function (event) {
            event.preventDefault();
            var $el = $('div[data-dismissible]');

            var attr_value, option_name, dismissible_length;

            attr_value = $el.attr('data-dismissible').split('-');

            // remove the dismissible length from the attribute value and rejoin the array.
            dismissible_length = attr_value.pop();

            option_name = attr_value.join('-');

            var ajaxdata = {
                'action': 'eme_dismiss_admin_notice',
                'option_name': option_name,
                'dismissible_length': dismissible_length,
                'eme_admin_nonce': emeadmin.translate_adminnonce
            };

            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            $.post(ajaxurl, ajaxdata);

        });
 
	$('#eme_attr_add_tag').on("click",function(event) {
		event.preventDefault();
		//Get All meta rows
		var metas = $('#eme_attr_body').children();
		//Copy first row and change values
		var metaCopy = $(metas[0]).clone(true);
		var newId = metas.length + 1;
		metaCopy.attr('id', 'eme_attr_'+newId);
		metaCopy.find('a').attr('rel', newId);
		metaCopy.find('[name=eme_attr_1_ref]').attr({
				name:'eme_attr_'+newId+'_ref' ,
				value:'' 
		});
		metaCopy.find('[name=eme_attr_1_content]').attr({ 
				name:'eme_attr_'+newId+'_content' , 
				value:'' 
		});
		metaCopy.find('[name=eme_attr_1_name]').attr({ 
				name:'eme_attr_'+newId+'_name' ,
				value:'' 
		});
		//Insert into end of file
		$('#eme_attr_body').append(metaCopy);
		//Duplicate the last entry, remove values and rename id
	});
	
	$('#eme_attr_body a').on("click",function(event) {
		event.preventDefault();
		//Only remove if there's more than 1 meta tag
		if($('#eme_attr_body').children().length > 1){
			//Remove the item
			$($(this).parent().parent().get(0)).remove();
			//Renumber all the items
			$('#eme_attr_body').children().each( function(id){
				var metaCopy = $(this);
				var oldId = metaCopy.attr('id').replace('eme_attr_','');
				var newId = id+1;
				metaCopy.attr('id', 'eme_attr_'+newId);
				metaCopy.find('a').attr('rel', newId);
				metaCopy.find('[name=eme_attr_'+ oldId +'_ref]').attr('name', 'eme_attr_'+newId+'_ref');
				metaCopy.find('[name=eme_attr_'+ oldId +'_content]').attr('name', 'eme_attr_'+newId+'_content');
				metaCopy.find('[name=eme_attr_'+ oldId +'_name]').attr( 'name', 'eme_attr_'+newId+'_name');
			});
		} else {
			var metaCopy = $($(this).parent().parent().get(0));
			metaCopy.find('[name=eme_attr_1_ref]').attr('value', '');
			metaCopy.find('[name=eme_attr_1_content]').attr('value', '');
			metaCopy.find('[name=eme_attr_1_name]').attr( 'value', '');
		}
	});

	$("#eme_dyndata_tbody").sortable({
		distance: 5,
		opacity: 0.6,
		cursor: 'move',
		handle: '.eme-sortable-handle',
		axis: 'y'
	});

	$('.eme_dyndata_add_tag').on("click",function(event) {
		event.preventDefault();
		//Get All meta rows
		var metas = $('#eme_dyndata_tbody').children();
		//Copy first row and change values
		var metaCopy = $(metas[0]).clone(true);
		var newId = 0;
		// make sure the newId doesn't exist yet
		while ($('#eme_dyndata_'+newId).length) {
			newId++;
		}
		var currentId = metaCopy.attr('id').replace('eme_dyndata_','');
		metaCopy.attr('id', 'eme_dyndata_'+newId);
		metaCopy.find('a').attr('rel', newId);
		// lets change the name, id and value for all text fields
		var metafields=['field','condition','condval','template_id_header','template_id','template_id_footer','repeat','grouping'];
		var arrayLength = metafields.length;
		for (var i = 0; i < arrayLength; i++) {
		   metaCopy.find('[name="eme_dyndata['+currentId+']['+metafields[i]+']"]').attr({
				'name':'eme_dyndata['+newId+']['+metafields[i]+']' ,
				'id':'eme_dyndata['+newId+']['+metafields[i]+']'
		   });
		}
		// set all values to defaults
		metaCopy.find('[name="eme_dyndata['+newId+'][field]"]').val('');
		metaCopy.find('[name="eme_dyndata['+newId+'][condition]"]').val('eq');
		metaCopy.find('[name="eme_dyndata['+newId+'][condval]"]').val('');
		metaCopy.find('[name="eme_dyndata['+newId+'][template_id_header]"]').val('0');
		metaCopy.find('[name="eme_dyndata['+newId+'][template_id]"]').val('0');
		metaCopy.find('[name="eme_dyndata['+newId+'][template_id_footer]"]').val('0');
		metaCopy.find('[name="eme_dyndata['+newId+'][repeat]"]').val('0');
		// set the html of the parent of the grouping field to empty
		// this also removes the hidden grouping field, it will dynamically added and set by EME
		metaCopy.find('[name="eme_dyndata['+newId+'][grouping]"]').parent().html('');
		// Insert at end of table
		$('#eme_dyndata_tbody').append(metaCopy);
	});
	
	$('.eme_remove_dyndatacondition').on("click",function(event) {
		event.preventDefault();
		//Get All meta rows
		var metas = $('#eme_dyndata_tbody').children();
		//Only remove if there's more than 1 meta tag
		if(metas.length > 1){
			//Remove the item
			$($(this).parent().parent().get(0)).remove();
		} else {
			// Get first row and change values (no clone this time)
			var metaCopy = $($(this).parent().parent().get(0));
			var newId = 0;
			// make sure the newId doesn't exist yet
			while ($('#eme_dyndata_'+newId).length) {
				newId++;
			}
			var currentId = metaCopy.attr('id').replace('eme_dyndata_','');
			metaCopy.attr('id', 'eme_dyndata_'+newId);
			metaCopy.find('a').attr('rel', newId);
			// lets change the name, id and value for all text fields
			var metafields=['field','condition','condval','template_id_header','template_id','template_id_footer','repeat','grouping'];
			var arrayLength = metafields.length;
			for (var i = 0; i < arrayLength; i++) {
				metaCopy.find('[name="eme_dyndata['+currentId+']['+metafields[i]+']"]').attr({
					'name':'eme_dyndata['+newId+']['+metafields[i]+']' ,
					'id':'eme_dyndata['+newId+']['+metafields[i]+']'
				});
			}
			// set all values to defaults
			metaCopy.find('[name="eme_dyndata['+newId+'][field]"]').val('');
			metaCopy.find('[name="eme_dyndata['+newId+'][condition]"]').val('eq');
			metaCopy.find('[name="eme_dyndata['+newId+'][condval]"]').val('');
			metaCopy.find('[name="eme_dyndata['+newId+'][template_id_header]"]').val('0');
			metaCopy.find('[name="eme_dyndata['+newId+'][template_id]"]').val('0');
			metaCopy.find('[name="eme_dyndata['+newId+'][template_id_footer]"]').val('0');
			metaCopy.find('[name="eme_dyndata['+newId+'][repeat]"]').val('0');
			// set the html of the parent of the grouping field to empty
			// this also removes the hidden grouping field, it will dynamically added and set by EME
			metaCopy.find('[name="eme_dyndata['+newId+'][grouping]"]').parent().html('');
			// since it is the first row, don't put stuff as required, it would prevent form submit
			for (var i = 0; i < arrayLength; i++) {
				metaCopy.find('[name="eme_dyndata['+newId+']['+metafields[i]+']"]').prop('required',false);
			}
		}
	});

	$("#eme_tasks_tbody").sortable({
		distance: 5,
		opacity: 0.6,
		cursor: 'move',
		handle: '.eme-sortable-handle',
		axis: 'y'
	});

	// since we don't clone the events when adding a row (because that causes trouble for cloned datepickers),
	//   we need to re-add the events on the new row (like the datepickers and the add/remove)
	//   So we'll define the eme_add_task_function/eme_remove_task_function
	$('.eme_add_task').on("click",function(event) {
		event.preventDefault();
		eme_add_task_function($(this));
	});
	$('.eme_remove_task').on("click",function(event) {
		event.preventDefault();
		eme_remove_task_function($(this));
	});
	function eme_add_task_function(myel) {
		var selectedItem = $(myel.parent().parent().get(0));
                var currentId = selectedItem.attr('id').replace('eme_row_task_','');
                //Get All meta rows
                //var metas = $('#eme_tasks_tbody').children();
                //Copy first row and change values, but not the events (that causes trouble for cloned datepickers)
                //var metaCopy = $(metas[0]).clone(false);
		var metaCopy = selectedItem.clone(false);
		var newId = 0;
		// make sure the newId doesn't exist yet
		while ($('#eme_row_task_'+newId).length) {
			newId++;
		}
		var currentId = metaCopy.attr('id').replace('eme_row_task_','');
		metaCopy.attr('id', 'eme_row_task_'+newId);
		metaCopy.find('a').attr('rel', newId);
		// lets change the name, id and value for all text fields
		var metafields=['task_id','name','task_start','task_end','spaces','dp_task_start','dp_task_end','description'];
		var arrayLength = metafields.length;
		for (var i = 0; i < arrayLength; i++) {
		   metaCopy.find('[name="eme_tasks['+currentId+']['+metafields[i]+']"]').attr({
				'name':'eme_tasks['+newId+']['+metafields[i]+']' ,
				'id':'eme_tasks['+newId+']['+metafields[i]+']'
		   });
		}
		// for the date fields
		metaCopy.find('[name="eme_tasks['+newId+'][dp_task_start]"]').attr({
			'data-alt-field':'#eme_tasks['+newId+'][task_start]'
		});
		metaCopy.find('[name="eme_tasks['+newId+'][dp_task_end]"]').attr({
			'data-alt-field':'#eme_tasks['+newId+'][task_end]'
		});
		// set all values to defaults
		metaCopy.find('[name="eme_tasks['+newId+'][name]"]').val('');
		metaCopy.find('[name="eme_tasks['+newId+'][spaces]"]').val('1');
		metaCopy.find('[name="eme_tasks['+newId+'][description]"]').val('');
		// set the html of the parent of the task_id field to empty
		// this also removes the hidden task_id field, it will dynamically added and set by EME
		metaCopy.find('[name="eme_tasks['+newId+'][task_id]"]').parent().html('');
		// Insert at end of table body
		$('#eme_tasks_tbody').append(metaCopy);
		// Now we set the datepickers and add/remove events
		$('#eme_row_task_'+newId+' .eme_formfield_fdatetime').fdatepicker({
                        todayButton: new Date(),
                        clearButton: true,
                        closeButton: true,
                        timepicker: true,
                        minutesStep: parseInt(emeadmin.translate_minutesStep),
                        language: emeadmin.translate_flanguage,
                        firstDay: parseInt(emeadmin.translate_firstDayOfWeek),
                        altFieldDateFormat: 'Y-m-d H:i:00',
                        dateFormat: emeadmin.translate_fdateformat,
                        timeFormat: emeadmin.translate_ftimeformat
                });
		current_start=metaCopy.find('[name="eme_tasks['+newId+'][task_start]"]').val();
                if (current_start != '') {
                        js_start_obj=new Date(metaCopy.find('[name="eme_tasks['+newId+'][task_start]"]').val());
                        metaCopy.find('[name="eme_tasks['+newId+'][dp_task_start]"]').fdatepicker().data('fdatepicker').selectDate(js_start_obj);
                }
                current_end=metaCopy.find('[name="eme_tasks['+newId+'][task_end]"]').val();
                if (current_end != '') {
                        js_end_obj=new Date(metaCopy.find('[name="eme_tasks['+newId+'][task_end]"]').val());
                        metaCopy.find('[name="eme_tasks['+newId+'][dp_task_end]"]').fdatepicker().data('fdatepicker').selectDate(js_end_obj);
                }
		$('#eme_row_task_'+newId+' .eme_add_task').on("click",function(event) {
			event.preventDefault();
			eme_add_task_function($(this));
		});
		$('#eme_row_task_'+newId+' .eme_remove_task').on("click",function(event) {
			event.preventDefault();
			eme_remove_task_function($(this));
		});
	}
	
	function eme_remove_task_function(myel) {
		//Get All meta rows
		var metas = $('#eme_tasks_tbody').children();
		//Only remove if there's more than 1 meta tag
		if(metas.length > 1){
			//Remove the item
			$(myel.parent().parent().get(0)).remove();
		} else {
			// Get first row and change values (no clone this time)
			var metaCopy = $(myel.parent().parent().get(0));
			var newId = 0;
			// make sure the newId doesn't exist yet
			while ($('#eme_row_task_'+newId).length) {
				newId++;
			}
			var currentId = metaCopy.attr('id').replace('eme_row_task_','');
			metaCopy.attr('id', 'eme_row_task_'+newId);
			metaCopy.find('a').attr('rel', newId);
			// lets change the name, id and value for all text fields
			var metafields=['task_id','name','task_start','task_end','spaces','dp_task_start','dp_task_end','description'];
			var arrayLength = metafields.length;
			for (var i = 0; i < arrayLength; i++) {
				metaCopy.find('[name="eme_tasks['+currentId+']['+metafields[i]+']"]').attr({
					'name':'eme_tasks['+newId+']['+metafields[i]+']' ,
					'id':'eme_tasks['+newId+']['+metafields[i]+']'
				});
			}
			// for the date fields
			metaCopy.find('[name="eme_tasks['+newId+'][dp_task_start]"]').attr({
				'data-alt-field':'#eme_tasks['+newId+'][task_start]'
			});
			metaCopy.find('[name="eme_tasks['+newId+'][dp_task_end]"]').attr({
				'data-alt-field':'#eme_tasks['+newId+'][task_end]'
			});
			// set all values to defaults
			metaCopy.find('[name="eme_tasks['+newId+'][name]"]').val('');
			metaCopy.find('[name="eme_tasks['+newId+'][spaces]"]').val('1');
			metaCopy.find('[name="eme_tasks['+newId+'][description]"]').val('');
			// set the html of the parent of the task_id field to empty
			// this also removes the hidden task_id field, it will dynamically added and set by EME
			metaCopy.find('[name="eme_tasks['+newId+'][task_id]"]').parent().html('');
			// since it is the first row, don't put stuff as required, it would prevent form submit
			for (var i = 0; i < arrayLength; i++) {
				metaCopy.find('[name="eme_tasks['+newId+']['+metafields[i]+']"]').prop('required',false);
			}
		}
	}

	$('.showhidebutton').on("click",function (e) {
		e.preventDefault();
		var elname= $(this).data( 'showhide' );
		$('#'+elname).toggle();
        });

	$('.eme_select2_customfieldids_class').select2({
		width: 'style',
		placeholder: emeadmin.translate_selectcustomfields
	});

	$('.eme_select2_members_class').select2({
		width: 'style',
		ajax: {
			url: ajaxurl+'?action=eme_members_select2',
			dataType: 'json',
			delay: 1000,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page,
					pagesize: 30,
					eme_admin_nonce: emeadmin.translate_adminnonce
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
						more: (params.page * 30) < data.TotalRecordCount
					}
				};
			},
			cache: true
		},
		placeholder: emeadmin.translate_selectmembers
	});
	$('.eme_select2_people_class').select2({
		width: 'style',
		ajax: {
			url: ajaxurl+'?action=eme_people_select2',
			dataType: 'json',
			delay: 1000,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page,
					pagesize: 30,
					eme_admin_nonce: emeadmin.translate_adminnonce
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
						more: (params.page * 30) < data.TotalRecordCount
					}
				};
			},
			cache: true
		},
		placeholder: emeadmin.translate_selectpersons
	});
	$('.eme_select2_groups_class').select2({
		placeholder: emeadmin.translate_selectgroups,
		width: 'style'
	});
	$('.eme_select2_people_groups_class').select2({
                width: 'style',
                placeholder: emeadmin.translate_anygroup
        });
        $('.eme_select2_memberstatus_class').select2({
                placeholder: emeadmin.translate_selectmemberstatus,
                width: 'style'
        });
	$('.eme_select2_memberships_class').select2({
		placeholder: emeadmin.translate_selectmemberships,
		width: 'style'
	});
	$('.eme_select2_discounts_class').select2({
		// ajax based results mess up the width, so we need to set it
		width: '100%',
		allowClear: true,
		placeholder: emeadmin.translate_selectdiscount,
		ajax: {
			url: ajaxurl+'?action=eme_discounts_select2',
			dataType: 'json',
			delay: 1000,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page,
					pagesize: 30,
					eme_admin_nonce: emeadmin.translate_adminnonce
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
						more: (params.page * 30) < data.TotalRecordCount
					}
				};
			},
			cache: true
		}
	});

	$('.eme_select2_dgroups_class').select2({
		// ajax based results mess up the width, so we need to set it
		width: '100%',
		allowClear: true,
		placeholder: emeadmin.translate_selectdiscountgroup,
		ajax: {
			url: ajaxurl+'?action=eme_dgroups_select2',
			dataType: 'json',
			delay: 1000,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page,
					pagesize: 30,
					eme_admin_nonce: emeadmin.translate_adminnonce
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
						more: (params.page * 30) < data.TotalRecordCount
					}
				};
			},
			cache: true
		}
	});

	// to make sure the placeholder shows after a hidden select2 is shown (bug workaround)
	$('.select2-search--inline, .select2-search__field').css('width', '100%');

	$('.eme_del_upload-button').on("click",function(event) {
		event.preventDefault();
		if (confirm(emeadmin.translate_areyousuretodeleteselected)) {
			var id = $(this).data('id');
			var name = $(this).data('name');
			var type = $(this).data('type');
			var random_id = $(this).data('random_id');
			var field_id = $(this).data('field_id');
			var extra_id = $(this).data('extra_id');
			$.post(ajaxurl, {'id': id, 'name': name, 'type': type, 'field_id': field_id, 'random_id': random_id, 'extra_id': extra_id, 'action': 'eme_del_upload', 'eme_admin_nonce': eme.translate_adminnonce }, function(data) {
				$('span#span_'+random_id).hide();
			});
		}
	});

	if ($('#eme-mailtemplates-accordion').length) {
		$("#eme-mailtemplates-accordion").accordion({
			collapsible: true, active: false, heightStyle: "content"
		});
	}
	if ($('#eme-payments-accordion').length) {
		$("#eme-payments-accordion").accordion({
			collapsible: true, active: false, heightStyle: "content"
		});
	}
	if ($('#tasks-accordion').length) {
		$("#tasks-accordion").accordion({
			collapsible: true, active: false, heightStyle: "content"
		});
	}
	if ($('#tasks-settings-accordion').length) {
		$("#tasks-settings-accordion").accordion({
			collapsible: true, active: false, heightStyle: "content"
		});
	}
	if ($('#tasks-mailtemplates-accordion').length) {
		$("#tasks-mailtemplates-accordion").accordion({
			collapsible: true, active: false, heightStyle: "content"
		});
	}
	if ($('#rsvp-accordion').length) {
		$("#rsvp-accordion").accordion({
			collapsible: true, active: false, heightStyle: "content"
		});
	}
	if ($('#mailformats-accordion').length) {
		$("#mailformats-accordion").accordion({
			collapsible: true, active: false, heightStyle: "content"
		});
	}

	$('#booking_attach_button').on("click",function(e) {
                e.preventDefault();
                var custom_uploader = wp.media({
                        title: emeadmin.translate_addattachments,
                        button: {
                                text: emeadmin.translate_addattachments
                        },
                        multiple: true  // Set this to true to allow multiple files to be selected
                }).on('select', function() {
                        var selection = custom_uploader.state().get('selection');
                        // using map is not really needed, but this way we can reuse the code if multiple=true
                        // var attachment = custom_uploader.state().get('selection').first().toJSON();
                        selection.map( function(attach) {
                                attachment = attach.toJSON();
                                $('#booking_attach_links').append("<a target='_blank' href='"+attachment.url+"'>"+attachment.title+"</a><br />");
				if ($('#eme_booking_attach_ids').val() != '') {
					tmp_ids_arr=$('#eme_booking_attach_ids').val().split(',');
				} else {
					tmp_ids_arr=[];
				}
				tmp_ids_arr.push(attachment.id);
				tmp_ids_val=tmp_ids_arr.join(',');
                                $('#eme_booking_attach_ids').val(tmp_ids_val);
                                $('#booking_attach_button').hide();
                                $('#booking_remove_attach_button').show();
                        });
                }).open();
        });
        if ($('#eme_booking_attach_ids').val() != '') {
		$('#booking_attach_button').hide();
		$('#booking_remove_attach_button').show();
        } else {
		$('#booking_attach_button').show();
		$('#booking_remove_attach_button').hide();
        }
	$('#booking_remove_attach_button').on("click",function(e) {
                e.preventDefault();
		$('#booking_attach_links').html('');
		$('#eme_booking_attach_ids').val('');
		$('#booking_attach_button').show();
		$('#booking_remove_attach_button').hide();
	});
	$('#pending_attach_button').on("click",function(e) {
                e.preventDefault();
                var custom_uploader = wp.media({
                        title: emeadmin.translate_addattachments,
                        button: {
                                text: emeadmin.translate_addattachments
                        },
                        multiple: true  // Set this to true to allow multiple files to be selected
                }).on('select', function() {
                        var selection = custom_uploader.state().get('selection');
                        // using map is not really needed, but this way we can reuse the code if multiple=true
                        // var attachment = custom_uploader.state().get('selection').first().toJSON();
                        selection.map( function(attach) {
                                attachment = attach.toJSON();
                                $('#pending_attach_links').append("<a target='_blank' href='"+attachment.url+"'>"+attachment.title+"</a><br />");
				if ($('#eme_pending_attach_ids').val() != '') {
					tmp_ids_arr=$('#eme_pending_attach_ids').val().split(',');
				} else {
					tmp_ids_arr=[];
				}
				tmp_ids_arr.push(attachment.id);
				tmp_ids_val=tmp_ids_arr.join(',');
                                $('#eme_pending_attach_ids').val(tmp_ids_val);
                                $('#pending_attach_button').hide();
                                $('#pending_remove_attach_button').show();
                        });
                }).open();
        });
        if ($('#eme_pending_attach_ids').val() != '') {
		$('#pending_attach_button').hide();
		$('#pending_remove_attach_button').show();
        } else {
		$('#pending_attach_button').show();
		$('#pending_remove_attach_button').hide();
        }
	$('#pending_remove_attach_button').on("click",function(e) {
                e.preventDefault();
		$('#pending_attach_links').html('');
		$('#eme_pending_attach_ids').val('');
		$('#pending_attach_button').show();
		$('#pending_remove_attach_button').hide();
	});
	$('#paid_attach_button').on("click",function(e) {
                e.preventDefault();
                var custom_uploader = wp.media({
                        title: emeadmin.translate_addattachments,
                        button: {
                                text: emeadmin.translate_addattachments
                        },
                        multiple: true  // Set this to true to allow multiple files to be selected
                }).on('select', function() {
                        var selection = custom_uploader.state().get('selection');
                        // using map is not really needed, but this way we can reuse the code if multiple=true
                        // var attachment = custom_uploader.state().get('selection').first().toJSON();
                        selection.map( function(attach) {
                                attachment = attach.toJSON();
                                $('#paid_attach_links').append("<a target='_blank' href='"+attachment.url+"'>"+attachment.title+"</a><br />");
				if ($('#eme_paid_attach_ids').val() != '') {
					tmp_ids_arr=$('#eme_paid_attach_ids').val().split(',');
				} else {
					tmp_ids_arr=[];
				}
				tmp_ids_arr.push(attachment.id);
				tmp_ids_val=tmp_ids_arr.join(',');
                                $('#eme_paid_attach_ids').val(tmp_ids_val);
                                $('#paid_attach_button').hide();
                                $('#paid_remove_attach_button').show();
                        });
                }).open();
        });
        if ($('#eme_paid_attach_ids').val() != '') {
		$('#paid_attach_button').hide();
		$('#paid_remove_attach_button').show();
        } else {
		$('#paid_attach_button').show();
		$('#paid_remove_attach_button').hide();
        }
	$('#paid_remove_attach_button').on("click",function(e) {
                e.preventDefault();
		$('#paid_attach_links').html('');
		$('#eme_paid_attach_ids').val('');
		$('#paid_attach_button').show();
		$('#paid_remove_attach_button').hide();
	});
        $('#subscribe_attach_button').on("click",function(e) {
                e.preventDefault();
                var custom_uploader = wp.media({
                        title: emeadmin.translate_addattachments,
                        button: {
                                text: emeadmin.translate_addattachments
                        },
                        multiple: true  // Set this to true to allow multiple files to be selected
                }).on('select', function() {
                        var selection = custom_uploader.state().get('selection');
                        // using map is not really needed, but this way we can reuse the code if multiple=true
                        // var attachment = custom_uploader.state().get('selection').first().toJSON();
                        selection.map( function(attach) {
                                attachment = attach.toJSON();
                                $('#subscribe_attach_links').append("<a target='_blank' href='"+attachment.url+"'>"+attachment.title+"</a><br />");
                                if ($('#eme_subscribe_attach_ids').val() != '') {
                                        tmp_ids_arr=$('#eme_subscribe_attach_ids').val().split(',');
                                } else {
                                        tmp_ids_arr=[];
                                }
                                tmp_ids_arr.push(attachment.id);
                                tmp_ids_val=tmp_ids_arr.join(',');
                                $('#eme_subscribe_attach_ids').val(tmp_ids_val);
                                $('#subscribe_attach_button').hide();
                                $('#subscribe_remove_attach_button').show();
                        });
                }).open();
        });
        if ($('#eme_subscribe_attach_ids').val() != '') {
                $('#subscribe_attach_button').hide();
                $('#subscribe_remove_attach_button').show();
        } else {
                $('#subscribe_attach_button').show();
                $('#subscribe_remove_attach_button').hide();
        }
        $('#subscribe_remove_attach_button').on("click",function(e) {
                e.preventDefault();
                $('#subscribe_attach_links').html('');
                $('#eme_subscribe_attach_ids').val('');
                $('#subscribe_attach_button').show();
                $('#subscribe_remove_attach_button').hide();
        });

	//$("input[placeholder]").each(function () {
	//	$(this).attr('size', $(this).attr('placeholder').length);
	//});
});

// the next is a Jtable CSV export function
function jtable_csv(container) {
	// create a copy to avoid messing with visual layout
	var newTable = jQuery(container).clone();
	// fix HTML table

	var csvData = [];

	//header
	var tmpRow = []; // construct header avalible array

	// th - remove attributes and header divs from jTable
	// newTable.find('th').each(function () {
	// use slice(1) to remove the first column, since that is the select box
	jQuery.each(newTable.find('th').slice(1),function () {
		if (jQuery(this).css('display') != 'none') {
			var val = jQuery(this).find('.jtable-column-header-text').text();
			tmpRow[tmpRow.length] = formatcsv(val);
		}
	});
	csvData[csvData.length] = tmpRow.join(',');

	// tr - remove attributes
	//newTable.find('tr').each(function () {
	jQuery.each(newTable.find('tr'),function () {
		var tmpRow = [];
		//jQuery(this).find('td').each(function() {
		// use slice(1) to remove the first column, since that is the select box
		jQuery.each(jQuery(this).find('td').slice(1),function() {
			if (jQuery(this).css('display') != 'none') {
				if (jQuery(this).find('img').length > 0)
					jQuery(this).html('');
				if (jQuery(this).find('button').length > 0)
					jQuery(this).html('');
				// we take the html and replace br
				var val = jQuery(this).html();
				var regexp = new RegExp(/\<br ?\/?\>/g);
				val = val.replace(regexp, '\n');
				jQuery(this).html(val);
				tmpRow[tmpRow.length] = formatcsv(jQuery(this).text());
			}
		});
		if (tmpRow.length>0) {
			csvData[csvData.length] = tmpRow.join(',');
		}
	});
	var mydata = csvData.join('\r\n');
	var url='data:text/csv;charset=utf8,' + encodeURIComponent(mydata);
	window.open(url);
	return true;
}

function formatcsv(input) {
	// double " according to rfc4180
	var regexp = new RegExp(/["]/g);
	var output = input.replace(regexp, '""');
	//HTML
	regexp = new RegExp(/\<[^\<]+\>/g);
	output = output.replace(regexp, "");
	output = output.replace(/&nbsp;/gi,' '); //replace &nbsp;
	if (output == "") return '';
	return '"' + output.trim() + '"';
}
