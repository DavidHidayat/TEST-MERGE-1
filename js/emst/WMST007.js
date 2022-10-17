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
    
    /**
     * =========================================================================================================
     * BUTTON FUNCTION
     * =========================================================================================================
     **/
    $("#btn-search").click(function() {
        var plantCdVal      = $.trim($("#plantCd").val());
        var buCdVal         = $.trim($("#buCd").val());
        var buNameVal       = $.trim($("#buName").val());
        var npkInChargeVal  = $.trim($("#npkInCharge").val());
        
        if(plantCdVal != '' || buCdVal != '' || buNameVal != '' || npkInChargeVal != '')
        {
            $("div#mandatory-msg-1.alert").css('display','none');
            $("div#mandatory-msg-2.alert").css('display','none');
            $("#WMST007Form").attr('action', 'WMST007.php');
            $("#WMST007Form").submit();
        }
        else
        {
            $("div#mandatory-msg-1.alert").css('display','block');
            $("div#mandatory-msg-2.alert").css('display','none');
        }
    });
    
    $("#btn-reset").click(function() {
        $("#plantCd").val('');
        $("#buCd").val('');
        $("#buName").val('');
        $("#npkInCharge").val('');
    });
});
