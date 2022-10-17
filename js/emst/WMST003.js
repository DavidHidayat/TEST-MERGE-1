$(document).ready(function() {
    /**
     * =========================================================================================================
     * INITIAL VALUE
     * =========================================================================================================
     **/
    
    /**
     * =========================================================================================================
     * INPUT FUNCTION
     * =========================================================================================================
     **/
    
    /**
     * =========================================================================================================
     * DEFINE DIALOG
     * =========================================================================================================
     **/
    /** Dialog form */
    $("#dialog-form").dialog({
        autoOpen    : false,
        closeOnEscape   : false,
	height      : 270,
	width       : 550,
        position    : { my: "center", at: "top", of: $("body"), within: $("body") },
	modal       : true,
        open        : function() {                         // open event handler
            $(this)                                // the element being dialogged
                .parent()                          // get the dialog widget element
                .find(".ui-dialog-titlebar-close") // find the close button for this dialog
                .hide();                           // hide it
        },
        buttons     : {
            "Save"  : function(){
                var edata;
                var itemGroupCdVal   = $.trim($("#itemGroupCd-dialog").val());
                var itemGroupNameVal = $.trim($("#itemGroupName-dialog").val());
                var itemGroupTestVal = $.trim($("#itemGroupTest-dialog").val());
                var action           = $.trim($("#dialog-form").dialog("option","title").substr(0,4));
                
                edata = "action="+encodeURIComponent(action)+"&itemGroupCdPrm="+encodeURIComponent(itemGroupCdVal)+"&itemGroupNamePrm="+encodeURIComponent(itemGroupNameVal)+"&itemGroupTestPrm="+encodeURIComponent(itemGroupTestVal);
                
                $.ajax({
                    type: 'GET',
                    url: '../db/MASTER/EPS_M_ITEM_GROUP.php',
                    data: edata,
                    success: function(data){
                        $("div#dialog-mandatory-msg-1.alert").css('display','none');
                        $("div#dialog-duplicate-msg.alert").css('display','none');
                        $("div#dialog-notexist-msg.alert").css('display','none');
                        $("div#dialog-undefined-msg.alert").css('display','none');
                        if($.trim(data) == 'Success')
                        {
                            $("#dialog-form").dialog("close");
                            window.location.reload();
                            $("#itemGroupCd").val(itemGroupCdVal);
                            $("#WMST003Form").attr('action', 'WMST003.php');
                            $("#WMST003Form").submit();
                        }
                        else if($.trim(data) == 'Mandatory_1')
                        {
                            $("div#dialog-mandatory-msg-1.alert").css('display','block');
                            $("div#dialog-duplicate-msg.alert").css('display','none');
                            $("div#dialog-notexist-msg.alert").css('display','none');
                            $("div#dialog-undefined-msg.alert").css('display','none');
                        }
                        else if($.trim(data) == 'Duplicate')
                        {
                            $("div#dialog-mandatory-msg-1.alert").css('display','none');
                            $("div#dialog-duplicate-msg.alert").css('display','block');
                            $("div#dialog-notexist-msg.alert").css('display','none');
                            $("div#dialog-undefined-msg.alert").css('display','none');
                        }
                        else if($.trim(data) == 'NotExist')
                        {
                            $("div#dialog-mandatory-msg-1.alert").css('display','none');
                            $("div#dialog-duplicate-msg.alert").css('display','block');
                            $("div#dialog-notexist-msg.alert").css('display','none');
                            $("div#dialog-undefined-msg.alert").css('display','none');
                        }
                        else if($.trim(data) == 'SessionExpired')
                        {
                            $("#dialog-confirm-session").dialog('open');
                        }
                        else
                        {
                            $("div#dialog-mandatory-msg-1.alert").css('display','none');
                            $("div#dialog-duplicate-msg.alert").css('display','none');
                            $("div#dialog-notexist-msg.alert").css('display','none');
                            $("div#dialog-undefined-msg.alert").css('display','block');
                        }  
                    }
                });
            },
            "Cancel": function(){
                $(this).dialog("close");
            }
        }
    });
    /**
     * =========================================================================================================
     * BUTTON FUNCTION
     * =========================================================================================================
     **/
    $("#btn-search").click(function() {
        var itemGroupCdVal  = $.trim($("#itemGroupCd").val());
        var itemGroupNameVal  = $.trim($("#itemGroupName").val());
        
        if(itemGroupCdVal != '' || itemGroupNameVal != '')
        {
            $("div#mandatory-msg-1.alert").css('display','none');
            $("div#mandatory-msg-2.alert").css('display','none');
            $("#WMST003Form").attr('action', 'WMST003.php');
            $("#WMST003Form").submit();
        }
        else
        {
            $("div#mandatory-msg-1.alert").css('display','block');
            $("div#mandatory-msg-2.alert").css('display','none');
        }
    });
    
    $("#btn-reset").click(function() {
        $("#itemGroupCd").val('');
        $("#itemGroupName").val('');
        
        $("div#dialog-mandatory-msg-1.alert").css('display','none');
        $("div#dialog-duplicate-msg.alert").css('display','none');
        $("div#dialog-notexist-msg.alert").css('display','none');
        $("div#dialog-undefined-msg.alert").css('display','none');
    });
    
    $("a#link-register.news-item-title").click(function() {
        $("#itemGroupCd-dialog").val('');
        $("#itemGroupName-dialog").val('');
        $("#itemGroupCd-dialog").attr('readonly', false);
        $("#dialog-form").dialog("open");
    });
    
    $("table.table.table-striped.table-bordered tbody tr td a").click(function() {
        var itemGroupCd        = $.trim($(this).closest('tr').find('td:eq(1)').text());
        var itemGroupName      = $.trim($(this).closest('tr').find('td:eq(2)').text());
        var itemGroupTest      = $.trim($(this).closest('tr').find('td:eq(3)').text());
        
        $("div#dialog-mandatory-msg-1.alert").css('display','none');
        $("div#dialog-duplicate-msg.alert").css('display','none');
        $("div#dialog-undefined-msg.alert").css('display','none');
                        
        $('#dialog-form').dialog('option', 'title', 'Edit Item Group');
        
        $("#itemGroupCd-dialog").val(itemGroupCd);
        $("#itemGroupName-dialog").val(itemGroupName);
        $("#itemGroupTest-dialog").val(itemGroupTest);
        $("#itemGroupCd-dialog").attr('readonly', true);
        
        $("#dialog-form").dialog("open");
    });
});
