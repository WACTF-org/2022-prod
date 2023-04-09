jQuery(document).ready(function ($) { 
        //Prepare jtable plugin
        $('#DiscountsTableContainer').jtable({
            title: emediscounts.translate_discounts,
            paging: true,
            sorting: true,
            toolbarsearch: true,
            toolbarreset: false,
            jqueryuiTheme: true,
            defaultSorting: 'name ASC',
            selecting: true, //Enable selecting
            multiselect: true, //Allow multiple selecting
            selectingCheckboxes: true, //Show checkboxes on first column
            selectOnRowClick: true, //Enable this to only select using checkboxes
            deleteConfirmation: function(data) {
               data.deleteConfirmMessage = emediscounts.translate_pressdeletetoremove + ' "' + data.record.name + '"';
            },
            actions: {
                listAction: ajaxurl+'?action=eme_discounts_list',
                deleteAction: ajaxurl+'?action=eme_manage_discounts&do_action=deleteDiscounts&eme_admin_nonce='+emediscounts.translate_adminnonce
            },
            fields: {
                id: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
                name: {
		    title: emediscounts.translate_name
                },
                description: {
		    title: emediscounts.translate_description
                },
                dgroup: {
		    title: emediscounts.translate_discountgroups
                },
                coupon: {
		    title: emediscounts.translate_coupon
                },
                strcase: {
		    title: emediscounts.translate_casesensitive,
                    searchable: false
                },
                use_per_seat: {
		    title: emediscounts.translate_use_per_seat,
                    searchable: false
                },
                value: {
		    title: emediscounts.translate_value
                },
                type: {
                    title: emediscounts.translate_type,
                    searchable: false
                },
                maxcount: {
		    title: emediscounts.translate_maxusage
                },
                count: {
		    title: emediscounts.translate_usage
                },
                valid_from: {
		    title: emediscounts.translate_validfrom
                },
                valid_to: {
		    title: emediscounts.translate_validto
                }
            }
        });

        $('#DiscountGroupsTableContainer').jtable({
            title: emediscounts.translate_discountgroups,
            paging: true,
            sorting: true,
            jqueryuiTheme: true,
            defaultSorting: 'name ASC',
            toolbarsearch: true,
            toolbarreset: false,
            selecting: true, //Enable selecting
            multiselect: true, //Allow multiple selecting
            selectingCheckboxes: true, //Show checkboxes on first column
            selectOnRowClick: false, //Enable this to only select using checkboxes
            deleteConfirmation: function(data) {
               data.deleteConfirmMessage = emediscounts.translate_pressdeletetoremove + ' "' + data.record.name + '"';
            },
            actions: {
                listAction: ajaxurl+'?action=eme_discountgroups_list',
                deleteAction: ajaxurl+'?action=eme_manage_discountgroups&do_action=deleteDiscountGroups&eme_admin_nonce='+emediscounts.translate_adminnonce
            },
            fields: {
                id: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
                name: {
		    title: emediscounts.translate_name,
                },
                description: {
		    title: emediscounts.translate_description
                },
                maxdiscounts: {
		    title: emediscounts.translate_maxdiscounts
                }
            }
        });
 
        // Load list from server, but only if the container is there
        if ($('#DiscountsTableContainer').length) {
           $('#DiscountsTableContainer').jtable('load');
        }
        if ($('#DiscountGroupsTableContainer').length) {
           $('#DiscountGroupsTableContainer').jtable('load');
        }
 
        // Actions button
        $('#DiscountsActionsButton').on("click",function (e) {
	   e.preventDefault();
           var selectedRows = $('#DiscountsTableContainer').jtable('selectedRows');
           var do_action = $('#eme_admin_action').val();
           var action_ok=1;
           if (selectedRows.length > 0) {
              if ((do_action=='deleteDiscounts') && !confirm(emediscounts.translate_areyousuretodeleteselected)) {
                 action_ok=0;
              }
              if (action_ok==1) {
                 $('#DiscountsActionsButton').text(emediscounts.translate_pleasewait);
		 $('#DiscountsActionsButton').prop('disabled', true);
                 var ids = [];
                 selectedRows.each(function () {
                   ids.push($(this).data('record')['id']);
                 });

                 var idsjoined = ids.join(); //will be such a string '2,5,7'
                 var params = {
                        'id': idsjoined,
                        'action': 'eme_manage_discounts',
                        'do_action': do_action,
                        'addtogroup': $('#addtogroup').val(),
                        'removefromgroup': $('#removefromgroup').val(),
                        'new_validfrom': $('#new_validfrom').val(),
                        'new_validto': $('#new_validto').val(),
                        'eme_admin_nonce': emediscounts.translate_adminnonce
                 };

                 $.post(ajaxurl, params, function(data) {
			 $('#DiscountsTableContainer').jtable('reload');
			 $('#DiscountsActionsButton').text(emediscounts.translate_apply);
			 $('#DiscountsActionsButton').prop('disabled', false);
			 $('div#discounts-message').html(data.htmlmessage);
			 $('div#discounts-message').show();
			 $('div#discounts-message').delay(3000).fadeOut('slow');
                 }, 'json');
              }
           }
           // return false to make sure the real form doesn't submit
           return false;
        });
 
        // Actions button
        $('#DiscountGroupsActionsButton').on("click",function (e) {
	   e.preventDefault();
           var selectedRows = $('#DiscountGroupsTableContainer').jtable('selectedRows');
           var do_action = $('#eme_admin_action').val();
           var action_ok=1;
           if (selectedRows.length > 0) {
              if ((do_action=='deleteDiscountGroups') && !confirm(emediscounts.translate_areyousuretodeleteselected)) {
                 action_ok=0;
              }
              if (action_ok==1) {
                 $('#DiscountGroupsActionsButton').text(emediscounts.translate_pleasewait);
		 $('#DiscountHtoupsActionsButton').prop('disabled', true);
                 var ids = [];
                 selectedRows.each(function () {
                   ids.push($(this).data('record')['id']);
                 });

                 var idsjoined = ids.join(); //will be such a string '2,5,7'
                 $.post(ajaxurl, {'id': idsjoined, 'action': 'eme_manage_discountgroups', 'do_action': do_action, 'eme_admin_nonce': emediscounts.translate_adminnonce }, function(data) {
			 $('#DiscountGroupsTableContainer').jtable('reload');
			 $('#DiscountGroupsActionsButton').text(emediscounts.translate_apply);
			 $('#DiscountGroupsActionsButton').prop('disabled', false);
			 $('div#discountgroups-message').html(data.htmlmessage);
			 $('div#discountgroups-message').show();
			 $('div#discountgroups-message').delay(3000).fadeOut('slow');
                 }, 'json');
              }
           }
           // return false to make sure the real form doesn't submit
           return false;
        });

	function updateShowHideStuff () {
           var action=$('select#eme_admin_action').val();

           if (action == 'changeValidFrom') {
              $('span#span_newvalidfrom').show();
           } else {
              $('span#span_newvalidfrom').hide();
           }
           if (action == 'changeValidTo') {
              $('span#span_newvalidto').show();
           } else {
              $('span#span_newvalidto').hide();
           }
           if (action == 'addToGroup') {
              $('span#span_addtogroup').show();
           } else {
              $('span#span_addtogroup').hide();
           }
           if (action == 'removeFromGroup') {
              $('span#span_removefromgroup').show();
           } else {
              $('span#span_removefromgroup').hide();
           }
        }
        updateShowHideStuff();
        $('select#eme_admin_action').on("change",updateShowHideStuff);

});
