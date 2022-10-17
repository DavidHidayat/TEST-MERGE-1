<?php session_start(); 
include $_SERVER['DOCUMENT_ROOT']."/EPS/db/PDOdb.php";
if(isset($_SESSION['sNPK']))
{       
    $sNPK           = $_SESSION['sNPK'];
    $sNama          = $_SESSION['sNama'];
    $sBunit         = $_SESSION['sBunit'];
    $sSeksi         = $_SESSION['sSeksi'];
    $sKdper         = $_SESSION['sKdper'];
    $sNmPer         = $_SESSION['sNmper'];
    $sKdPlant       = $_SESSION['sKDPL'];
    $sNmPlant       = $_SESSION['sNMPL'];
    $sRoleId        = $_SESSION['sRoleId'];
    $sInet          = $_SESSION['sinet'];
    $sNotes         = $_SESSION['snotes'];
    $sUserId        = $_SESSION['sUserId'];
    $sBuLogin       = $_SESSION['sBuLogin'];
    $sUserType      = $_SESSION['sUserType'];
    $sInvType       = $_SESSION['sInvType'];
    $sSpecialType   = $_SESSION['sSpecialType'];
    $sPrApproverBu  = $_SESSION['sPrApproverBu'];
    $prNo       	= $_GET['prNo'];
	
	$query_select_m_pr_special_approver = "select 
                                            NPK
                                           from
                                            EPS_M_PR_SPECIAL_APPROVER
                                           where 
                                            SPECIAL_APPROVER_CD = '001'";
    $sql_select_m_pr_special_approver = $conn->query($query_select_m_pr_special_approver);
    $row_select_m_pr_special_approver = $sql_select_m_pr_special_approver->fetch(PDO::FETCH_ASSOC);
    $npkISApprover = $row_select_m_pr_special_approver['NPK'];

    if($_SESSION['EPSAuthority'] != 'EPSApprovePrScreen')
    {       
    ?>
        <script language="javascript"> alert("Sorry, you are not authorized to access this page.");
	document.location="../db/Login/Logout.php"; </script>
    <?
    }
}else{	
?>
    <script language="javascript"> alert("Sorry, you are not authorized to access this page.");
    document.location="../db/Login/Logout.php"; </script>
<?
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>EPS</title>
        <!--  CSS -->
        <link rel="stylesheet" type="text/css" href="../extjs/resources/css/ext-all.css"></link>
        <link rel="stylesheet" type="text/css" href="../css/eps.css"></link>
        <!--  Ext Js library -->
        <script type="text/javascript" src="../extjs/bootstrap.js"></script>
        <script type="text/javascript" src="../js/Common.js"></script>
        <script type="text/javascript" src="../js/Store_Master.js"></script>
        <script>
        maximize();
        if (Ext.BLANK_IMAGE_URL.substr(0, 5) != 'data:') {
            Ext.BLANK_IMAGE_URL = '../extjs/resources/images/default/s.gif';
        }
        Ext.QuickTips.init();
        var mainLayout = function(){
            var toolbarTop = new Ext.Toolbar ({
                id: 'toolbar', 
                items: [{
                    xtype: 'buttongroup',
                    title: 'Miscellaneous',
                    items: [{
                        xtype: 'button',
                        text: 'Main Screen',
                        handler: function(){
                            Ext.Msg.alert('Message','Sorry, you cannot change menu during in Approval PR Screen. <br>Please choose action "Approve or Reject or Back".');
                        }   
                    }]
                },{
                    xtype: 'buttongroup',
                    title: 'PR',
                    items: [{
                        xtype: 'button',
                        text: 'PR List',
                        handler: function(){
                            Ext.Msg.alert('Message','Sorry, you cannot change menu during in Approval PR Screen. <br>Please choose action "Approve or Reject or Back".');
                        }
                    },{
                        xtype: 'button',
                        text: 'PR Waiting',
                        handler: function(){
                            Ext.Msg.alert('Message','Sorry, you cannot change menu during in Approval PR Screen. <br>Please choose action "Approve or Reject or Back".');
                        }
                    },{
                        xtype: 'button',
                        text: 'Create New PR',
                        handler: function(){
                            Ext.Msg.alert('Message','Sorry, you cannot change menu during in Approval PR Screen. <br>Please choose action "Approve or Reject or Back".');
                        }
                    },{
                        xtype: 'button',
                        text: 'Upload PR',
                        handler: function(){
                            Ext.Msg.alert('Message','Sorry, you cannot change menu during in Approval PR Screen. <br>Please choose action "Approve or Reject or Back".');
                        }
                    }]
                },{
                    xtype: 'buttongroup',
                    title: 'Search',
                    items: [{
                        xtype: 'button',
                        text: 'PR Search',
                        handler: function(){
                            Ext.Msg.alert('Message','Sorry, you cannot change menu during in Approval PR Screen. <br>Please choose action "Approve or Reject or Back".');
                        }
                    },{
                        xtype: 'button',
                        text: 'PO Search',
                        handler: function(){
                            Ext.Msg.alert('Message','Sorry, you cannot change menu during in Approval PR Screen. <br>Please choose action "Approve or Reject or Back".');
                        }
                    }]
                },'->',
                {
                    xtype: 'tbtext', //Logged is as:
                    text: '<h2>Welcome, <?php echo stripslashes(addslashes($sNama)); ?></h2>#USER ID: <?php echo $sUserId; ?> #BU: <?php echo $sBuLogin?>'
                },'-',{
                    xtype: 'button',
                    text: 'Logout',
                    handler:function(){  
                        Ext.Msg.alert('Message','Sorry, you cannot change menu during in Approval PR Screen. <br>Please choose action "Approve or Reject or Back".');
                    }
                }]
            });
            /** 
             * ========================================================================================================
             * **************************************** DEFINE VARIABLE ***********************************************
             * ========================================================================================================
             **/
            var myItem              = [];
            var myAttachment        = [];
            var myAttachmentTemp    = [];
            var myAttachmentItem    = [];
            var winPrDetail;
            var winRejectItemPr;
            var winRejectPr;
            var winPrAttachment;
            var itemNameGet;
            var urlPrHeader         = '<?php echo "../db/PR/READ_PR.php?prNo=".$prNo."&param=prHeader" ?>';
            var urlPrDetail         = '<?php echo "../db/PR/READ_PR.php?prNo=".$prNo."&param=prDetail" ?>';
            var urlPrApprover;
            var urlPrApproverSpecial = '<?php echo "../db/PR/READ_PR.php?prNo=".$prNo."&param=prApproverSpecial" ?>';
            var urlPrAttachment     = '<?php echo "../db/PR/READ_PR.php?prNo=".$prNo."&param=prAttachment&action=edit" ?>';
            var userId              = '<? echo $sNPK; ?>';
            var userName            = '<? echo $sNama; ?>';
            var isApprover          = '<? echo $npkISApprover;?>';
            var prApproverBu        = '<?php echo $sPrApproverBu;?>'; 
            var prSpecialType       = '<?php echo $sSpecialType?>';
            var invType             = "<?php echo "$sInvType";?>";
            
            if(Ext.String.trim(prApproverBu) != '3300' && Ext.String.trim(prApproverBu) != '3301' && Ext.String.trim(prApproverBu) != '3302' && Ext.String.trim(prApproverBu) != '3941' &&  prSpecialType == 'IT'){
				urlPrApprover       = '<?php echo "../db/PR/READ_PR.php?prNo=".$prNo."&param=prApproverDept" ?>'
            }else{
                urlPrApprover       = '<?php echo "../db/PR/READ_PR.php?prNo=".$prNo."&param=prApprover" ?>'
            }
            /** 
             * ========================================================================================================
             * **************************************** DEFINE INITIAL VALUE ******************************************
             * ========================================================================================================
             **/
            /** 
             * =======================================
             * Initial value PR Header
             * =======================================
             **/ 
            Ext.Ajax.request({
                url: urlPrHeader,
                success: function(response,opts,store){
                    var obj=Ext.decode(response.responseText);
                    var prNoVal         = obj.rows[0]['PR_NO'];
                    var issuedDateVal   = obj.rows[0]['ISSUED_DATE'];
                    var specialTypeVal  = obj.rows[0]['SPECIAL_TYPE_ID'];
                    var purposeVal      = Ext.String.trim(obj.rows[0]['PURPOSE']);
                    var requesterVal    = obj.rows[0]['REQUESTER'];
                    var requesterNameVal= obj.rows[0]['REQUESTER_NAME'];
                    var plantVal        = obj.rows[0]['PLANT_NAME'];
                    var plantCdVal      = obj.rows[0]['PLANT_CD'];
                    var companyVal      = obj.rows[0]['COMPANY_NAME'];
                    var companyCdVal    = obj.rows[0]['COMPANY_CD'];
                    var extNoVal        = Ext.String.trim(obj.rows[0]['EXT_NO']);
                    var buCdVal         = obj.rows[0]['BU_CD'];
                    var sectionCdVal    = obj.rows[0]['SECTION_CD'];
                    var prIssuerVal     = obj.rows[0]['REQ_BU_CD'];
                    var prChargedVal    = obj.rows[0]['CHARGED_BU_CD'];
                    
                    
                    prNo.setValue(prNoVal);
                    prDate.setValue(issuedDateVal);
                    specialType.setValue({specialType:specialTypeVal});
                    purpose.setValue(purposeVal);
                    requester.setValue(requesterVal);
                    requesterName.setValue(requesterNameVal);
                    plant.setValue(plantVal);
                    plantCd.setValue(plantCdVal);
                    company.setValue(companyVal);
                    companyCd.setValue(companyCdVal);
                    ext.setValue(extNoVal);
                    buCd.setValue(buCdVal);
                    sectionCd.setValue(sectionCdVal);
                    prIssuer.setValue(prIssuerVal);
                    prCharged.setValue(prChargedVal);
					
					if(Ext.String.trim(userId) == Ext.String.trim(isApprover) && specialTypeVal == 'IT')
                    {
                        Ext.getCmp('byPassButton').enable(true);
                    }
                    
                },
                failure: function(response, opts) {
                
                } 
            });
            /** 
             * =======================================
             * Initial value PR Attachment
             * =======================================
             **/ 
            Ext.Ajax.request({
                url: urlPrAttachment,
                success: function(response,opts,store){
                    var obj = Ext.decode(response.responseText);
                    var countAttachment = (obj.rows.length);
                    for(q = 0; q < countAttachment; q++){
                        var itemCd      = obj.rows[q]['ITEM_CD'];
                        var itemName    = obj.rows[q]['ITEM_NAME'];
                        var fileName    = obj.rows[q]['FILE_NAME'];
                        var fileType    = obj.rows[q]['FILE_TYPE'];
                        var fileSize    = obj.rows[q]['FILE_SIZE'];
                        myAttachment.push([itemCd,itemName,itemName,fileName,fileSize,fileType]);
                    }
                    dsPrAttachmentTemp.load();
                }
            });
            /** 
             * =======================================
             * Initial value PR Detail
             * =======================================
             **/ 
            Ext.Ajax.request({
                url: urlPrDetail,
                success: function(response,opts,store){
                    var obj = Ext.decode(response.responseText);
                    var countObj = (obj.rows.length);
                    for(q=0; q < countObj; q++){
                        var itemCd      = obj.rows[q]['ITEM_CD'];
                        var itemName    = obj.rows[q]['ITEM_NAME'];
                        var remark      = obj.rows[q]['REMARK'];
                        var deliveryDate= obj.rows[q]['DELIVERY_DATE'];
                        var itemType    = obj.rows[q]['ITEM_TYPE_CD'];
                        var rfiNo       = obj.rows[q]['RFI_NO'];
                        var accountNo   = obj.rows[q]['ACCOUNT_NO'];
                        var supplierCd  = obj.rows[q]['SUPPLIER_CD'];
                        var supplierName= obj.rows[q]['SUPPLIER_NAME'];
                        var unitCd      = obj.rows[q]['UNIT_CD'];
                        var qty         = obj.rows[q]['QTY'];
                        var itemPrice   = obj.rows[q]['ITEM_PRICE'];
                        //var amount      = obj.rows[q]['AMOUNT'];
                        var amount      = qty * itemPrice;
                        var currencyCd  = obj.rows[q]['CURRENCY_CD'];
                        var itemStatus  = obj.rows[q]['ITEM_STATUS'];
                        var reasonToReject  = obj.rows[q]['REASON_TO_REJECT_ITEM'];
                        var rejectItemBy    = obj.rows[q]['REJECT_ITEM_BY'];
                        var rejectItemName  = obj.rows[q]['REJECT_ITEM_NAME_BY'];
                        var stockInv  = obj.rows[q]['STOCK'];
                        var orderPoint  = obj.rows[q]['OP'];
                        var inOrder  = obj.rows[q]['IN_ORDER'];
                        if(itemCd == '99'){
                            itemCd = '';
                        }     
                        myItem.push([itemCd,itemName,remark,deliveryDate,itemType
                                    ,rfiNo,accountNo,currencyCd,supplierCd,supplierName
                                    ,unitCd,qty,itemPrice,amount
                                    ,itemStatus,reasonToReject,rejectItemBy,rejectItemName, inOrder, stockInv, orderPoint]);
                        prDetailGrid.store.load();
                    }
                }
            });
            /** 
             * ========================================================================================================
             * **************************************** DEFINE FUNCTION ***********************************************
             * ========================================================================================================
             **/
            /** 
             * =======================================
             * Push Attachment Temporary
             * =======================================
             **/
            function pushPrAttachmentTemp(itemNameGet){
                myAttachmentTemp.splice(0,myAttachmentTemp.length);
                dsPrAttachmentTemp.removeAll(true);
                dsPrAttachmentTemp.clearFilter();
                prAttachment.getView().refresh();
                    
                // Copy data dari array myFile ke myFileTemp
                for(var i=0;i<myAttachment.length;i++){
                    var itemCd=myAttachment[i][0];
                    var itemName=myAttachment[i][1];
                    var itemNameOld=myAttachment[i][2];
                    var fileName=myAttachment[i][3];
                    var fileSize=myAttachment[i][4];
                    var fileType=myAttachment[i][5];
                    if(itemNameOld==itemNameGet){
                        myAttachmentTemp.push([itemCd,itemName,itemNameOld,fileName,fileSize,fileType]);
                    }
                }
                dsPrAttachmentTemp.load();
            }
            /** 
             * =======================================
             * Clear Attachment Temporary
             * =======================================
             **/
            function clearPrAttachmentTemp(){
                myAttachmentTemp.splice(0, myAttachmentTemp.length);
                dsPrAttachmentTemp.removeAll(true);
                dsPrAttachmentTemp.clearFilter();
                dsPrAttachmentTemp.load();
                dsPrAttachment.load();
            }
            /** 
             * ========================================================================================================
             * **************************************** DEFINE WINDOW *************************************************
             * ========================================================================================================
             **/
            /** 
             * =======================================
             * Define Window Reject Item PR
             * =======================================
             **/
            var rejectDeliveryDate, rejectItemField;
            function showWinRejectPrDetail(prDetailGrid,rowIndex,colIndex){
                rejectDeliveryDate = new Ext.form.field.Date({
                    fieldLabel: 'Delivery Date',
                    format: 'd/m/Y',
                    hidden: true
                });
                rejectItemField = new Ext.form.field.TextArea({
                    fieldStyle: {
                        textTransform: "uppercase"
                    },
                    fieldLabel: 'Reason',
                    anchor: '100%',
                    height: 48,
                    msgTarget: 'side',
                    allowBlank: false,
                    regex: /[a-zA-Z0-9]+/,       // detect spasi value
                    maxLength : 300,
                    enforceMaxLength : 300,
                    enterIsSpecial : true,
                    listeners: {
                        specialkey: function(f,e){  
                            if(e.getKey()==e.ENTER){  
                                e.stopEvent();
                            }  
                        } 
                    }
                });
                var rejectItemForm = Ext.widget('form',{
                    frame: true,
                    border: false,
                    bodyPadding: 5,
                    height: 125,
                    items: [{
                                xtype: 'box',
                                cls: 'span_msg',
                                autoEl: {
                                    cn: '<span>* Rejection of PR item only can be done once time</span>'
                                }
                            },{
                                xtype: 'box',
                                cls: 'span_msg',
                                autoEl: {
                                    cn: '<br>'
                                }
                            },rejectItemField],
                    buttons: [{
                        text: 'Submit',
                        handler: function(){
                            if(rejectItemForm.getForm().isValid()) {
                                Ext.MessageBox.confirm('Message', 'Do you want to reject item PR ?', function(btn, text){
                                    if(btn=='yes'){
                                        rejectPrDetail(prDetailGrid,rowIndex,colIndex);
                                    }
                                });
                            }
                        }
                    },{
                        text: 'Cancel',
                        handler: function(){
                            winRejectItemPr.hide();
                        }
                    }]
                });
                winRejectItemPr = Ext.widget('window',{
                    closeAction: 'hide',
                    title: 'Reject Item',
                    width: 500,
                    height: 170,
                    bodyPadding: '5',
                    resizable: false,
                    closable: false,
                    modal: true,
                    items: [rejectItemForm]
                });
                winRejectItemPr.show();
            }
            /** 
             * =======================================
             * Define Window Reject PR
             * =======================================
             **/
            var reasonOfRejectField, rejectPrForm;
            function showWinPrReject(){
                reasonOfRejectField = new Ext.form.field.TextArea({
                    fieldStyle: {
                        textTransform: "uppercase"
                    },
                    fieldLabel: 'Reason',
                    anchor: '100%',
                    height: 48,
                    msgTarget: 'side',
                    allowBlank: false,
                    regex: /[a-zA-Z0-9]+/,       // detect spasi value
                    maxLength : 300,
                    enforceMaxLength : 300,
                    enterIsSpecial : true,
                    listeners: {
                        specialkey: function(f,e){  
                            if(e.getKey()==e.ENTER){  
                                e.stopEvent();
                            }  
                        } 
                    }
                });
                rejectPrForm = Ext.widget('form',{
                    frame: true,
                    border: false,
                    bodyPadding: 5,
                    height: 125,
                    items: [reasonOfRejectField],
                    buttons: [{
                        text: 'Submit',
                        handler: function(){
                            rejectPr();
                        }
                    },{
                        text: 'Cancel',
                        handler: function(){
                            winRejectPr.hide();
                        }
                    }]
                });
                winRejectPr = Ext.widget('window',{
                    closeAction: 'hide',
                    title: 'Reject PR',
                    width: 500,
                    height: 170,
                    bodyPadding: '5',
                    resizable: false,
                    closable: false,
                    modal: true,
                    items: [rejectPrForm]
                });
                winRejectPr.show();
            }
            /** 
             * =======================================
             * Define Window PR Detail
             * =======================================
             **/
            function showWinPrDetail(){
                if (!winPrDetail){	
					if(invType == 'N1000'){
                        itemTypeForm.setVisible(true);
                        itemTypeForm2.setVisible(false);
                        itemTypeForm3.setVisible(false);
                    }
                    else if(invType == 'N1001'){
                        itemTypeForm.setVisible(false);
                        itemTypeForm2.setVisible(false);
                        itemTypeForm3.setVisible(true);
                    }
                    else{
                        itemTypeForm.setVisible(false);
                        itemTypeForm2.setVisible(true);
                        itemTypeForm3.setVisible(false);
                    }
                    winPrDetail = Ext.widget('window',{
                        closeAction: 'hide',
                        width: 680,
                        height: 552,
                        bodyPadding: '1',
                        resizable: false,
                        closable: false,
                        modal: true,
                        items: [prDetail]
                    });
                }
                winPrDetail.show();
            }
            /** 
             * =======================================
             * Define Window Add PR Detail
             * =======================================
             **/
            function showWinAddPrDetail(){
                if(dsPrItem.getCount() < 40)
                {
                    resetPrDetail();
                    showWinPrDetail();
                    winPrDetail.setTitle('Add PR Item');
                    browseForm.disable();
                }
                else
                {
                    Ext.MessageBox.alert('Message','Sorry, maximun is 40 item.');
                }
            }
            /** 
             * =======================================
             * Define Window Edit PR Detail
             * =======================================
             **/
            function showWinEditPrDetail(prDetailGrid,rowIndex,colIndex){
                resetPrDetail();
                showWinPrDetail();
                winPrDetail.setTitle('Edit PR Item');
                var valItemCd       = dsPrItem.getAt(rowIndex).get('itemCd');
                itemNameGet         = dsPrItem.getAt(rowIndex).get('itemName');
                var valRemark       = dsPrItem.getAt(rowIndex).get('remark');
                var valDeliveryDate = dsPrItem.getAt(rowIndex).get('deliveryDate');
                var valItemType     = dsPrItem.getAt(rowIndex).get('itemType');
                var valAccountNo    = dsPrItem.getAt(rowIndex).get('accountNo');
                var valRfiNo        = dsPrItem.getAt(rowIndex).get('rfiNo');
                var valUnitCd       = dsPrItem.getAt(rowIndex).get('unitCd');
                var valQty          = dsPrItem.getAt(rowIndex).get('qty');
                var valInOrder      = dsPrItem.getAt(rowIndex).get('inOrder');
                var valstockInv    = dsPrItem.getAt(rowIndex).get('stockInv');
                var valOrderPoint    = dsPrItem.getAt(rowIndex).get('orderPoint');
                var valItemPrice    = dsPrItem.getAt(rowIndex).get('itemPrice');
                var valSupplierCd   = dsPrItem.getAt(rowIndex).get('supplierCd');
                var valSupplierName = dsPrItem.getAt(rowIndex).get('supplierName');
                
                if(invType == 'N1000'){
                    itemTypeForm.setValue({itemTypeForm:valItemType});
                }
                else if(invType == 'N1001'){
                    itemTypeForm3.setValue({itemTypeForm3:valItemType});
                }
                else{
                    itemTypeForm2.setValue({itemTypeForm2:valItemType});
                }
                  
				var valCompanyCd = Ext.String.trim(companyCd.getValue());
                      
                if(valItemType == "2")
                {
					if(valCompanyCd == "H")
					{
						rfiNoForm2.setValue(valRfiNo);
						
						// RFI No DNIA
						rfiNoForm.setVisible(false);
						rfiNoForm.allowBlank=true;
						rfiNoForm.reset();
						
						// RFI No HDI - Show
						rfiNoForm2.setVisible(true);
						rfiNoForm2.allowBlank=false;
					
					}
					else
					{
						rfiNoForm.setValue(valRfiNo);
						
						// RFI No DNIA - Show
						rfiNoForm.setVisible(true);
						rfiNoForm.allowBlank=false;
										
						// RFI No HDI
						rfiNoForm2.setVisible(false);
						rfiNoForm2.allowBlank=true;
						rfiNoForm2.reset();
					}
                }
                //itemTypeForm.setValue({itemTypeForm:valItemType});
                accountNoForm.setValue(valAccountNo);
                accountNoForm2.setValue(valAccountNo);
                accountNoForm3.setValue(valAccountNo);
                //rfiNoForm.setValue(valRfiNo);
                itemCdForm.setValue(valItemCd);
                itemNameForm.setValue(itemNameGet);
                itemNameOldForm.setValue(itemNameGet);
                unitCdForm.setValue(valUnitCd);
                qtyForm.setValue(valQty);
                inOrderForm.setValue(valInOrder);
                stockInvForm.setValue(valstockInv);
                orderPointForm.setValue(valOrderPoint);
                priceForm.setValue(valItemPrice);
                deliveryDateForm.setValue(valDeliveryDate);
                supplierCdForm.setValue(valSupplierCd);
                supplierNameForm.setValue(valSupplierName);
                remarkForm.setValue(valRemark);
                
                pushPrAttachmentTemp(itemNameGet);
            }
            /** 
             * =======================================
             * Define Window View PR Attachment
             * =======================================
             **/
            function showWinViewPrAttachment(prDetailGrid,rowIndex,colIndex){
                itemNameGet = dsPrItem.getAt(rowIndex).get('itemName');
                pushPrAttachmentTemp(itemNameGet);
                if (!winPrAttachment){	
                    winPrAttachment=Ext.widget('window',{
                        closeAction: 'hide',
                        title: 'PR Attachment',
                        width: 500,
                        height: 260,
                        bodyPadding: '1',
                        resizable: false,
                        closable: false,
                        modal: true,
                        items: [prAttachmentView],
                        buttons: [{
                            text: 'Close',
                            handler: function(){
                                winPrAttachment.hide();
                                clearPrAttachmentTemp();
                            }
                        }]
                    });
                }
                winPrAttachment.show();
            }
            /** 
             * ========================================================================================================
             * **************************************** DEFINE FUNCTION ***********************************************
             * ========================================================================================================
             **/
            /** 
             * =======================================
             * Define Function Reject Pr Item
             * =======================================
             **/
            function rejectPrDetail(prDetailGrid,rowIndex,colIndex) {
                var valItemCd       = dsPrItem.getAt(rowIndex).get('itemCd');
                itemNameGet         = dsPrItem.getAt(rowIndex).get('itemName');
                var valRemark       = dsPrItem.getAt(rowIndex).get('remark');
                var valDeliveryDate = dsPrItem.getAt(rowIndex).get('deliveryDate');
                var valItemType     = dsPrItem.getAt(rowIndex).get('itemType');
                var valAccountNo    = dsPrItem.getAt(rowIndex).get('accountNo');
                var valRfiNo        = dsPrItem.getAt(rowIndex).get('rfiNo');
                var valUnitCd       = dsPrItem.getAt(rowIndex).get('unitCd');
                var valQty          = dsPrItem.getAt(rowIndex).get('qty');
                var valItemPrice    = dsPrItem.getAt(rowIndex).get('itemPrice');
                var valAmount       = dsPrItem.getAt(rowIndex).get('amount');
                var valCurrencyCd   = dsPrItem.getAt(rowIndex).get('currencyCd');
                var valSupplierCd   = dsPrItem.getAt(rowIndex).get('supplierCd');
                var valSupplierName = dsPrItem.getAt(rowIndex).get('supplierName');
                var valItemStatus   = '1070';
                var valReasonReject = Ext.String.trim(rejectItemField.getValue());
                var valRejectBy     = userId;
                var valRejectNameBy = userName;
                rejectDeliveryDate.setValue(valDeliveryDate);
                valDeliveryDate     = rejectDeliveryDate.getRawValue();
                
                for(var i = 0; i< myItem.length; i++){
                    var index=dsPrItem.find('itemName',itemNameGet.toLowerCase());
                }
                myItem[index]=[valItemCd,itemNameGet,valRemark,valDeliveryDate,valItemType,valRfiNo,valAccountNo,valCurrencyCd,valSupplierCd,valSupplierName,valUnitCd,valQty,valItemPrice,valAmount,valItemStatus,valReasonReject,valRejectBy,valRejectNameBy];
                prDetailGrid.store.load();
                winRejectItemPr.hide();
            }
            /** 
             * =======================================
             * Define Function Delete Pr Item
             * =======================================
             **/
            function deletePrDetail(prDetailGrid,rowIndex,colIndex){
                var row = dsPrItem.getAt(rowIndex);
                var rowItemName = row.get('itemName');
                var index, fileNameRow;
                myAttachmentItem.splice(0,myAttachmentItem.length);
                for(var i=0;i<myAttachment.length;i++){
                    var itemNameArray = myAttachment[i][2];
                    if(rowItemName == itemNameArray){
                        index = i;
                        fileNameRow = myAttachment[i][3];
                        myAttachmentItem.push([fileNameRow, index]);
                    }
                }  
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Do you confirm to delete PR item ?',
                    icon: Ext.Msg.QUESTION,
                    buttons:Ext.MessageBox.YESNO,
                    fn: function(btn){
                        if(btn=='yes'){
                            Ext.Ajax.request({
                                method: 'POST',
                                url: '../db/PR/DELETE_PR_ATTACHMENT.php?action=attachmentItem',
                                params: {
                                    itemAttachment : Ext.encode(myAttachmentItem),
                                    prNo: prNo.getValue()
                                },
                                success: function(response){
                                    myItem.splice(rowIndex, 1);
                                    prDetailGrid.getStore().remove(row);
                                    for(var i = 0; i < myAttachmentItem.length; i++){
                                        var index = myAttachmentItem[i][1];
                                        myAttachment.splice(index, 1);
                                    }
                                }
                            }); 
                        }
                    }
                });
            }
            /** 
             * =======================================
             * Define Function Delete PR Item Attachment
             * =======================================
             **/
            function deletePrItemAttachment(prDetailGrid,rowIndex,e){
                var row = dsPrAttachmentTemp.getAt(rowIndex);
                var fileNameRow = row.get('fileName');
                var index, indexTemp;
                for(var i=0;i<myAttachmentTemp.length;i++){
                    var fileNameArrayTemp = myAttachmentTemp[i][3];
                    if(fileNameRow==fileNameArrayTemp){
                        indexTemp=i;
                        myAttachmentItem.push([fileNameRow, indexTemp]);
                        break;
                    }
                }
                for(var j=0;j<myAttachment.length;j++){
                    var fileNameArray = myAttachment[j][3];
                    if(fileNameRow==fileNameArray){
                        index=j;
                        break;
                    }
                }   
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Do you confirm to delete attachment ?',
                    icon: Ext.Msg.QUESTION,
                    buttons:Ext.MessageBox.YESNO,
                    fn: function(btn){
                        if(btn=='yes'){
                            Ext.Ajax.request({
                               method: 'POST',
                               url: '../db/PR/DELETE_PR_ATTACHMENT.php?action=attachmentItem',
                                    params: {
                                        itemAttachment : Ext.encode(myAttachmentItem),
                                        prNo: prNo.getValue()
                                    },
                                    success: function(response){
                                        myAttachment.splice(index, 1);
                                        myAttachmentTemp.splice(indexTemp, 1);
                                        prDetailGrid.getStore().remove(row);
                                    }
                            }); 
                        }
                    }
                });
            }
            /** 
             * =======================================
             * Define Reset PR Detail
             * =======================================
             **/
            function resetPrDetail(){
                itemTypeForm.reset();
                itemTypeForm2.reset();
                itemTypeForm3.reset();
                accountNoForm.reset();
                accountNoForm2.reset();
                accountNoForm3.reset();
                rfiNoForm.reset();
                rfiNoForm2.reset();
                itemCdForm.reset();
                itemNameOldForm.reset();
                itemNameForm.reset();
                unitCdForm.reset();
                qtyForm.reset();
                priceForm.reset();
                deliveryDateForm.reset();
                supplierCdForm.reset();
                supplierNameForm.reset();
                remarkForm.reset();
                accountNoForm.setVisible(false);
                accountNoForm2.setVisible(false);
                accountNoForm3.setVisible(false);
                rfiNoForm.setVisible(false);
                rfiNoForm2.setVisible(false);
                browseForm.disable();
                Ext.getCmp('tbAttachment').enable();
            }
            /** 
             * =======================================
             * Define Cancel PR Detail
             * =======================================
             **/
            function cancelPrDetail(){
                resetPrDetail();
                winPrDetail.hide();
                clearPrAttachmentTemp();
            }
            /** 
             * =======================================
             * Define Save PR Detail
             * =======================================
             **/
            function savePrDetail(){
                if(prDetail.getForm().isValid()) {
                    var itemCd      = itemCdForm.getValue();
                    var itemName    = Ext.String.trim(itemNameForm.getRawValue().toUpperCase());
                    var deliveryDate= deliveryDateForm.getRawValue();
                    var itemType    = itemTypeForm.items.get(0).getGroupValue();
                    var itemType2   = itemTypeForm2.items.get(0).getGroupValue();
                    var itemType3   = itemTypeForm3.items.get(0).getGroupValue();
                    var accountNo   = accountNoForm.getValue();
                    var accountNo2  = accountNoForm2.getValue();
                    var accountNo3  = accountNoForm3.getValue();
                    var rfiNo       = rfiNoForm.getValue();
                    var unitCd      = unitCdForm.getValue();
                    var qty         = qtyForm.getValue();
                    var itemPrice   = priceForm.getValue();
                    var amount      = qty * itemPrice;
                    var currencyCd  = currencyCdForm.getValue();
                    var supplierCd  = supplierCdForm.getValue();
                    var supplierName= Ext.String.trim(supplierNameForm.getRawValue().toUpperCase());
                    var remark      = Ext.String.trim(remarkForm.getValue().toUpperCase());
                    var itemStatus  = '1060';
                    var reasonToReject  = '';
                    var rejectItemBy    = '';
                    var rejectItemName  = '';
                    var inOrder  = inOrderForm.getValue();
                    var stockInv  = stockInvForm.getValue();
                    var orderPoint  = orderPointForm.getValue();
                    var oldItemName = prDetailGrid.getStore().findExact('itemName',itemNameGet);
                    var action = winPrDetail.title.substr(0,4);
                    var itemNameLow_1 = itemName.toLowerCase();
                    var itemNameLow_2;
                    var valCompanyCd = Ext.String.trim(companyCd.getValue());
                        
                    if(valCompanyCd == "H")
                    {
                        rfiNo       = rfiNoForm2.getValue();
                    }
					if(invType == 'N1000' && itemType == null){
                        Ext.MessageBox.alert('Message','Mandatory. Please select item type (** PTIC).');
                    }
                    else if(invType == 'N1001' && itemType3 == null){
                        Ext.MessageBox.alert('Message','Mandatory. Please select item type (** MARCOM).');
                    }
                    else if(invType != 'N1000' && invType != 'N1001' && itemType2 == null){
                        Ext.MessageBox.alert('Message','Mandatory. Please select item type.');
                    }
                    else if(itemName.length <= 0){
                        Ext.MessageBox.alert('Message', 'Mandatory. Please input item name, cannot be blank.');
                    }else{
						if(qty <= 0 || itemPrice <= 0 || amount <=0 ){
							Ext.MessageBox.alert('Message','Please enter value > 0');
						}else{
							if(invType != 'N1000'){
                                itemType = itemType2;
                            }
                            if(invType == 'N1001'){
                                itemType = itemType3;
                            }
                            if(invType == 'N1000' && itemType == '3' && buLogin.getValue() == '4420 ')
                            {
                                itemType = '5';
                            }
                            if((itemType == '3' || itemType == '5') && invType == 'N1000'){
                                accountNo = accountNo2;
                            }
                            if(itemType == '4' && invType == 'N1001'){
                                accountNo = accountNo3;
                            }
							if(action=='Edit'){
								var id='';
								if(itemNameGet==itemName){ // Check if update except itemName
									id='2';
								}else{
									dsPrItem.each(function (me) {
										itemNameLow_2= me.data.itemName.toLowerCase();
                                        if(itemNameLow_1.replace(/\s/g, "") == itemNameLow_2.replace(/\s/g, "")){
											Ext.MessageBox.alert('Message','Item name already exists in PR Item List.');
											id='1';
											return false;
										}else{
											id='2';
										}
									});
								}
								if(id=='2'){ // cek apakah value di grid == value di form
									myItem[oldItemName]=[itemCd,itemName,remark,deliveryDate,itemType,rfiNo,accountNo,currencyCd,supplierCd,supplierName,unitCd,qty,itemPrice,amount,itemStatus,reasonToReject,rejectItemBy,rejectItemName,inOrder, stockInv, orderPoint];
									
									var myIndex = [], indexFlag='', initialIndex;
									for(var j = 0; j < myAttachment.length; j++){
										var itemNameStore = myAttachment[j][1];
										if(itemNameStore == itemNameGet){
											if(indexFlag == ''){
												indexFlag = '1';
												initialIndex = j;
											}
											myIndex.push(j);
										}
									}
									if(myIndex.length > 0){
										myAttachment.splice(initialIndex,myIndex.length);
									}
									dsPrAttachment.clearFilter();
									dsPrAttachment.load();
									for(var i = 0; i < myAttachmentTemp.length; i++){
										var itemName_2=myAttachmentTemp[i][1];
										var fileName=myAttachmentTemp[i][3];
										var fileSize=myAttachmentTemp[i][4];
										var fileType=myAttachmentTemp[i][5];
										var index=dsPrAttachment.find('itemNameOld',itemNameGet);
										if(index==-1){
											myAttachment.push([itemCd,itemName,itemName,fileName,fileSize,fileType]);
										}
										else{
											myAttachment[index]=[itemCd,itemName,itemName,fileName,fileSize,fileType];
										}
									}
									myAttachmentTemp.splice(0, myAttachmentTemp.length);
									dsPrAttachmentTemp.removeAll(true);
									dsPrAttachmentTemp.clearFilter();
									prAttachment.getView().refresh();
									
									prDetailGrid.store.load();
									dsPrAttachment.load();
								}
							}else if(Ext.String.trim(action)=='Add'){
								var id='';
								if(dsPrItem.getCount()==0){
									id='2';
								}else{
									dsPrItem.each(function (me) {
										itemNameLow_2= me.data.itemName.toLowerCase();
                                        if(itemNameLow_1.replace(/\s/g, "") == itemNameLow_2.replace(/\s/g, "")){
											Ext.MessageBox.alert('Message','Item name already exists in PR Item List.');
											id='1';
											return false;
										}else{
											id='2';
										}
									});
								}
								if(id=='2'){
									myItem.push([itemCd,itemName,remark,deliveryDate,itemType,rfiNo,accountNo,currencyCd,supplierCd,supplierName,unitCd,qty,itemPrice,amount,itemStatus,reasonToReject,rejectItemBy,rejectItemName,inOrder, stockInv, orderPoint]);
									
									dsPrAttachment.clearFilter();
									for(var i = 0; i < myAttachmentTemp.length; i++){
										var itemName_2=myAttachmentTemp[i][1];
										var fileName=myAttachmentTemp[i][3];
										var fileSize=myAttachmentTemp[i][4];
										var fileType=myAttachmentTemp[i][5];
										var index=dsPrAttachment.find('itemName',itemName_2);
										if(index==-1){
											myAttachment.push([itemCd,itemName,itemName,fileName,fileSize,fileType]);
										}
										else{
											myAttachment[index]=[itemCd,itemName,itemName,fileName,fileSize,fileType];
										}
									}
									myAttachmentTemp.splice(0, myAttachmentTemp.length);
									dsPrAttachmentTemp.removeAll(true);
									dsPrAttachmentTemp.clearFilter();
									prAttachment.getView().refresh();
									
									prDetailGrid.store.load();
									dsPrAttachment.load();
								}
							}else{
								Ext.MessageBox.alert('Message','Please check action');
							}  
							
							if(id=='2'){
								// Reset Form and Hide Windows
								resetPrDetail();
								winPrDetail.hide();
							}
						}
					}
                    
                }else{
                    Ext.MessageBox.alert('Message','Mandatory. Please fill all the field.');
                }
            }
            /** 
             * =======================================
             * Define Reject PR
             * =======================================
             **/
            function rejectPr(me){
                if(rejectPrForm.getForm().isValid()){
                    var reason = Ext.String.trim(reasonOfRejectField.getValue());
                    var action = 'Reject PR';
                    Ext.Msg.confirm('Confirm', 'Are you sure to '+action+' ?', function(btn, text){
                        if (btn == 'yes'){
                            Ext.Ajax.request({
                                method: 'POST',
                                url: '../db/PR/UPDATE_PR.php?action=rejectPr',
                                params: {
									userIdLoginVal  : userIdLogin.getValue(),
                                    prNo        : prNo.getValue(),
                                    buCd        : buCd.getValue(),
                                    requester   : requester.getValue(),
                                    reason      : reason
                                },
                                success: function(response){
                                    winRejectPr.hide();
                                    dsPrApprover.load();
                                    Ext.Msg.alert({
                                        title   : 'Message',
                                        msg     : action+' succeed.',
                                        buttons : Ext.Msg.OK,
                                        closable: false,
                                        fn      : function(btn){
                                            if(btn=='ok'){
                                                //window.location='WEPR013.php';
												window.location='../epr_/WEPR013.php';
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            }
            /** 
             * =======================================
             * Define Update PR
             * =======================================
             **/
            function updatePr(me){
                var actionBtn = me.substr(0,1);
                var countReject = 0;
                var countItem = dsPrItem.getCount();
                if(countItem > 0){
                    dsPrItem.each(function (me) {
                        var itemStatusVal = me.data.itemStatus;
                        if(itemStatusVal=='1070'){
                            countReject++;
                        }
                    }); 
                    if(actionBtn=='A'){
                        if(countReject < countItem){
                             // Read array of PR 
                            var prItemData = Ext.encode(myItem);
                            var prAttachmentData = Ext.encode(myAttachment);
                            Ext.Msg.confirm('Confirm', 'Are you sure want to '+me+' ?', function(btn, text){
                                if (btn == 'yes'){
									Ext.getCmp('approve-btn').disable(true);
                                    Ext.getCmp('reject-btn').disable(true);
                                    Ext.getCmp('back-btn').disable(true);
                                    Ext.Ajax.request({
                                        method: 'POST',
                                        url: '../db/PR/UPDATE_PR.php?action=approvePr',
                                        params: {
                                            userIdLoginVal  : userIdLogin.getValue(),
                                            prNo            : prNo.getValue(),
                                            requester       : requester.getValue(),
                                            prChargedVal    : prCharged.getValue(),
                                            purposeVal      : Ext.String.trim(purpose.getValue()),
                                            prItemData      : prItemData,
                                            prAttachmentData: prAttachmentData
                                        },
                                        success: function(response){
                                            var msg = Ext.decode(response.responseText).msg.message;
                                            if(msg == 'update_success'){
                                                    dsPrApprover.load();
                                                    Ext.Msg.alert({
                                                        title   : 'Message',
                                                        msg     : me+' succeed.',
                                                        buttons : Ext.Msg.OK,
                                                        closable: false,
                                                        fn      : function(btn){
                                                            if(btn=='ok'){
                                                                window.location='../epr_/WEPR013.php';
                                                            }
                                                        }
                                                    });
                                                }
                                                else
                                                {
                                                    Ext.Msg.alert({
                                                        title   : 'Message',
                                                        msg     : 'PR already updated.',
                                                        buttons : Ext.Msg.OK,
                                                        closable: false,
                                                        fn      : function(btn){
                                                            if(btn=='ok'){
                                                                window.location='../epr_/WEPR013.php';
                                                            }
                                                        }
                                                    });
                                                }
											Ext.getCmp('approve-btn').disable(true);
											Ext.getCmp('reject-btn').disable(true);
											Ext.getCmp('back-btn').disable(true);
                                        }
                                    });
                                } 
                            });
                        }else{
                            Ext.MessageBox.alert('Message', 'Can not approve because all PR item already rejected.<br>Please choose action "Reject PR"');
                        }
                    }else{
                        if(actionBtn=='R'){
                            showWinPrReject(me);
                        }
                    }
                }else{
                    Ext.MessageBox.alert('Message', 'Mandatory. Please input PR item.');
                }
            }
            /** 
             * =======================================
             * Define Return PR
             * =======================================
             **/
            function returnPr(me){
                var actionBtn = me.substr(0,6);
                Ext.MessageBox.confirm('Message', 'Do you want to ' +me+' ?', function(btn, text){
                    if(btn=='yes'){
                        // Read array of PR // Read array of PR 
                        var prItemData = Ext.encode(myItem);
                        var prAttachmentData = Ext.encode(myAttachment);
                        Ext.Ajax.request({
                            method: 'POST',
                            url: '../db/PR/DELETE_PR_ATTACHMENT.php?action=attachmentPr',
                            params: {
                                prNo             : prNo.getValue(),
                                prItemData       : prItemData,
                                prAttachmentData : prAttachmentData,
                                actionBtnVal     : actionBtn
                            },
                            success: function(response){
                                Ext.Msg.alert({
                                    title   : 'Message',
                                    msg     : me+' succeed.',
                                    buttons : Ext.Msg.OK,
                                    closable: false,
                                    fn      : function(btn){
                                        if(btn == 'ok'){
											window.location='../epr_/WEPR013.php';
                                        }
                                    }
                                });
                            }
                        });
                    }
                });
            }
			function bypassITApprovalPr(me){
				var actionBtn = me.substr(0,6);
                Ext.MessageBox.confirm('Message', 'Do you want to ' +me+' ?', function(btn, text){
                    if(btn=='yes'){
                        Ext.getCmp('approve-btn').disable(true);
                        Ext.getCmp('reject-btn').disable(true);
                        Ext.getCmp('byPassButton').disable(true);
                        // Read array of PR // Read array of PR 
                        var prItemData = Ext.encode(myItem);
                        var prAttachmentData = Ext.encode(myAttachment);
                        Ext.Ajax.request({
                            method: 'POST',
                            url: '../db/PR/UPDATE_PR.php?action=bypassITApprovalPr',
                            params: {
                                userIdLoginVal  : userIdLogin.getValue(),
                                buLoginVal      : buLogin.getValue(),
                                prNo            : prNo.getValue(),
                                requester       : requester.getValue(),
                                prChargedVal    : prCharged.getValue(),
                                purposeVal      : Ext.String.trim(purpose.getValue()),
                                prItemData      : prItemData,
                                prAttachmentData: prAttachmentData
                            },
                            success: function(response){
                                Ext.Msg.alert({
                                    title   : 'Message',
                                    msg     : me+' succeed.',
                                    buttons : Ext.Msg.OK,
                                    closable: false,
                                    fn      : function(btn){
                                        if(btn == 'ok'){
                                            window.location='../epr_/WEPR013.php';
                                        }
                                    }
                                });
                            }
                        });
                    }
                });
            }
            /** 
             * ========================================================================================================
             * **************************************** DEFINE STORE **************************************************
             * ========================================================================================================
             **/
            var dsPrItem = Ext.create('Ext.data.ArrayStore',{
                fields: [
                    {name: 'itemCd'},
                    {name: 'itemName'},
                    {name: 'remark'},
                    {name: 'deliveryDate', type:'date', dateFormat: 'd/m/Y'},
                    {name: 'itemType'},
                    {name: 'rfiNo'},
                    {name: 'accountNo'},
                    {name: 'currencyCd'},
                    {name: 'supplierCd'},
                    {name: 'supplierName'},
                    {name: 'unitCd'},
                    {name: 'qty'},
                    {name: 'itemPrice'},
                    {name: 'amount'},
                    {name: 'itemStatus'},
                    {name: 'reasonToReject'},
                    {name: 'rejectItemBy'},
                    {name: 'rejectItemName'},
                    {name: 'inOrder'},
                    {name: 'stockInv'},
                    {name: 'orderPoint'},

                ],
                data: myItem 
            });
            var dsPrAttachment = Ext.create('Ext.data.ArrayStore',{
                fields: [
                    {name: 'itemCd'},
                    {name: 'itemName'},
                    {name: 'itemNameOld'},
                    {name: 'fileName'},
                    {name: 'fileSize'},
                    {name: 'fileType'}
                ],
                data: myAttachment
            });
            var dsPrAttachmentTemp = Ext.create('Ext.data.ArrayStore',{
                fields: [
                    {name: 'itemCd'},
                    {name: 'itemName'},
                    {name: 'itemNameOld'},
                    {name: 'fileName'},
                    {name: 'fileSize'},
                    {name: 'fileType'}
                ],
                data: myAttachmentTemp 
            });
            Ext.define('PrApprover',{
                extend: 'Ext.data.Model',
                fields:[
                    {name: 'APPROVER_NO'},
                    {name: 'APPROVER_NAME'},
                    {name: 'APPROVAL_STATUS_NAME'},
                    {name: 'APPROVAL_DATE', type: 'date', dateFormat: 'Y-m-d H:i:s'},
                    {name: 'APPROVAL_REMARK'},
                    {name: 'DATE_OF_BYPASS'}
                ]
            });
            var dsPrApprover = Ext.create('Ext.data.Store', {
                model: 'PrApprover',
                proxy:{
                    type: 'ajax',
                    url: urlPrApprover,
                    reader: {
                        type: 'json',
                        root: 'rows'
                    }
                },
                autoLoad: true
            }); 
			Ext.define('PrApproverSpecial',{
                extend: 'Ext.data.Model',
                fields:[
                    {name: 'APPROVER_NO'},
                    {name: 'APPROVER_NAME'},
                    {name: 'APPROVAL_STATUS_NAME'},
                    {name: 'APPROVAL_DATE', type: 'date', dateFormat: 'Y-m-d H:i:s'},
                    {name: 'APPROVAL_REMARK'},
                    {name: 'DATE_OF_BYPASS'}
                ]
            });
            var dsPrApproverSpecial = Ext.create('Ext.data.Store', {
                model: 'PrApproverSpecial',
                proxy:{
                    type: 'ajax',
                    url: urlPrApproverSpecial,
                    reader: {
                        type: 'json',
                        root: 'rows'
                    }
                },
                autoLoad: true
            });
            /** 
             * ========================================================================================================
             * **************************************** DEFINE FORM COMPONENT *****************************************
             * ========================================================================================================
             **/
            /** 
             * =======================================
             * Define PR Header Form Component
             * =======================================
             **/
            var userIdLogin =  new Ext.form.TextField({
                fieldLabel: 'User ID',
                name: 'userId',
                readOnly: true,
                value: '<?php echo $sUserId?>'
            });
            var buLogin = new Ext.form.TextField({
                fieldLabel: 'BU Login',
                name: 'buLogin',
                readOnly: true,
                value: '<?php echo $sBuLogin?>'
            });
            var prNo = new Ext.form.TextField({
                fieldLabel: 'PR Number',
                name: 'prNo',
                readOnly: true,
                flex: 2
            });
            var prDate = new Ext.form.TextField({
                fieldLabel: 'PR Date',
                name: 'prDate',
                readOnly: true,
                flex: 2
            });
            var specialType = Ext.create('Ext.form.RadioGroup',{
                fieldLabel: 'Category',
                id: 'specialType',
                name: 'specialType',
                allowBlank: false,
                readOnly: true,
                items: [
                    {boxLabel: 'IT Equipment', name: 'specialType', inputValue: 'IT', readOnly: true},
                    {boxLabel: 'Non IT Equipment', name: 'specialType', inputValue: 'NIT', readOnly: true}
                ],
                flex: 3
            });
            var purpose = new Ext.form.field.TextArea({
                fieldStyle: {
                    textTransform: "uppercase"
                },
                fieldLabel: 'Purpose',
                name: 'purpose',
                maxLength: 200,
                enforceMaxLength: 200,
                height: 35,
                enterIsSpecial : true,
                allowBlank: false,
                listeners: {
                    specialkey: function(f,e){  
                        if(e.getKey()==e.ENTER){  
                            e.stopEvent();
                        }  
                    } 
                    ,change: function(field, newValue, oldValue){
                        field.setValue(newValue.replace(new RegExp('\r?\n','g'), ' '));
                        
                    }
                },
                flex: 3
            });
            var requester = new Ext.form.TextField({
                fieldLabel: 'NPK',
                name: 'requester',
                readOnly: true,
                flex: 2
            });
            var requesterName = new Ext.form.TextField({
                fieldLabel: 'Name',
                name: 'requesterName',
                readOnly: true,
                flex: 3
            });
            var ext = new Ext.form.TextField({
                fieldLabel: 'Ext',
                name: 'ext',
                readOnly: true,
                flex: 2
            });
             var plant=new Ext.form.TextField({
                fieldLabel: 'Plant',
                name: 'plant',
                readOnly: true,
                flex: 2
            });
            var plantCd=new Ext.form.TextField({
                name: 'plantCd',
                hidden: true
            });
            var company=new Ext.form.TextField({
                fieldLabel: 'Company',
                name: 'company',
                readOnly: true,
                flex: 3
            });
            var companyCd=new Ext.form.TextField({
                name: 'companyCd',
                hidden: true
            });
            var buCd=new Ext.form.TextField({
                fieldLabel: 'BU Code',
                name: 'buCd',
                readOnly: true,
                flex: 2
            });
            var sectionCd=new Ext.form.TextField({
                name: 'sectionCd',
                hidden: true
            });
            var prIssuer = Ext.create('Ext.form.field.ComboBox', {
                fieldLabel: 'Issuer BU',
                name: 'prIssuer',
                store: dsBuCode,
                displayField: 'BU_CD_NAME',
                valueField: 'BU_CD',
                queryMode: 'local',
                editable: false,
                allowBlank: false,
                readOnly: true,
                flex: 2
            });
            var prCharged = Ext.create('Ext.form.field.ComboBox', {
                fieldLabel: 'Charged BU',
                name: 'prCharged',
                store: dsBuCode,
                displayField: 'BU_CD_NAME',
                valueField: 'BU_CD',
                queryMode: 'local',
                editable: false,
                allowBlank: false,
                flex: 2
            });
            /** 
             * =======================================
             * Define PR Detail Form Component
             * =======================================
             **/
            var itemTypeForm = Ext.create('Ext.form.RadioGroup',{
                fieldLabel: 'Item Type',
                //allowBlank: false,
                defaults: {xtype: 'radio',name: 'itemTypeForm'},
                items:[ {boxLabel:'Expense', inputValue:'1'},
                        {boxLabel:'Investment', inputValue:'2'},
                        {boxLabel:'Inventory (PTIC)', inputValue:'3'}],
                flex: 2,
                listeners: {
                    change: function(){
                        var valItemType = itemTypeForm.items.get(0).getGroupValue();
                        if(valItemType=='1'){
                            accountNoForm.setVisible(true);
                            accountNoForm.allowBlank=false;
                            
                            rfiNoForm.setVisible(false);
                            rfiNoForm.allowBlank=true;
                            rfiNoForm.reset();
                            
                            accountNoForm2.setVisible(false);
                            accountNoForm2.allowBlank=true;
                            accountNoForm2.reset();
                            
                            accountNoForm3.setVisible(false);
                            accountNoForm3.allowBlank=true;
                            accountNoForm3.reset();
                            
                        }else if(valItemType=='3' && invType == 'N1000'){
                            accountNoForm.setVisible(false);
                            accountNoForm.allowBlank=true;
                            accountNoForm.reset();
                            
                            rfiNoForm.setVisible(false);
                            rfiNoForm.allowBlank=true;
                            rfiNoForm.reset();   
                                
                            accountNoForm2.setVisible(true);
                            accountNoForm2.allowBlank=false;
                            
                            accountNoForm3.setVisible(false);
                            accountNoForm3.allowBlank=true;
                            accountNoForm3.reset();
                            
                        }else {
                            if(valItemType=='2'){
                                accountNoForm.setVisible(false);
                                accountNoForm.allowBlank=true;
                                accountNoForm.reset();
                                
                                rfiNoForm.setVisible(true);
                                rfiNoForm.allowBlank=false;
                                
                                accountNoForm2.setVisible(false);
                                accountNoForm2.allowBlank=true;
                                accountNoForm2.reset();
                                
                                accountNoForm3.setVisible(false);
                                accountNoForm3.allowBlank=true;
                                accountNoForm3.reset();
                            }
                        }
                    }
                }
            });
            var itemTypeForm2 = Ext.create('Ext.form.RadioGroup',{
                fieldLabel: 'Item Type',
                //allowBlank: false,
                defaults: {xtype: 'radio',name: 'itemTypeForm2'},
                items:[ {boxLabel:'Expense', inputValue:'1'},
                        {boxLabel:'Investment', inputValue:'2'}],
                flex: 2,
                listeners: {
                    change: function(){
                        var valItemType = itemTypeForm2.items.get(0).getGroupValue();
                        var valCompanyCd = Ext.String.trim(companyCd.getValue());
						
                        if(valItemType=='1'){
                            accountNoForm.setVisible(true);
                            accountNoForm.allowBlank=false;
                            
                            // RFI No DNIA
                            rfiNoForm.setVisible(false);
                            rfiNoForm.allowBlank=true;
                            rfiNoForm.reset();
                            
                            // RFI No HDI
                            rfiNoForm2.setVisible(false);
                            rfiNoForm2.allowBlank=true;
                            rfiNoForm2.reset();
                            
                            accountNoForm2.setVisible(false);
                            accountNoForm2.allowBlank=true;
                            accountNoForm2.reset();
                            
                            accountNoForm3.setVisible(false);
                            accountNoForm3.allowBlank=true;
                            accountNoForm3.reset();
                            
                        }else {
                            if(valItemType=='2')
                            {
                                accountNoForm.setVisible(false);
                                accountNoForm.allowBlank=true;
                                accountNoForm.reset();
                                
                                if(valCompanyCd == "H")
                                {
                                    rfiNoForm.setVisible(false);
                                    rfiNoForm.allowBlank=true;
                                    
                                    // RFI No HDI - Show
                                    rfiNoForm2.setVisible(true);
                                    rfiNoForm2.allowBlank=false;
                                }
                                else
                                {
                                    // RFI No DNIA - Show
                                    rfiNoForm.setVisible(true);
                                    rfiNoForm.allowBlank=false;
                                    
                                    rfiNoForm2.setVisible(false);
                                    rfiNoForm2.allowBlank=true;
                                }
                                
                                // Inventory PTIC
                                accountNoForm2.setVisible(false);
                                accountNoForm2.allowBlank=true;
                                accountNoForm2.reset();
                                
                                // Inventory MARCOM
                                accountNoForm3.setVisible(false);
                                accountNoForm3.allowBlank=true;
                                accountNoForm3.reset();
                            }
                        }
                    }
                }
            });
            var itemTypeForm3 = Ext.create('Ext.form.RadioGroup',{
                fieldLabel: 'Item Type',
                //allowBlank: false,
                defaults: {xtype: 'radio',name: 'itemTypeForm3'},
                items:[ {boxLabel:'Expense', inputValue:'1'},
                        {boxLabel:'Investment', inputValue:'2'},
                        {boxLabel:'Inventory (MARCOM)', inputValue:'4'}],
                flex: 2,
                listeners: {
                    change: function(){
                        var valItemType = itemTypeForm3.items.get(0).getGroupValue();
                        if(valItemType=='1'){
                            accountNoForm.setVisible(true);
                            accountNoForm.allowBlank=false;
                            
                            rfiNoForm.setVisible(false);
                            rfiNoForm.allowBlank=true;
                            rfiNoForm.reset();
                            
                            accountNoForm2.setVisible(false);
                            accountNoForm2.allowBlank=true;
                            accountNoForm2.reset();
                            
                            accountNoForm3.setVisible(false);
                            accountNoForm3.allowBlank=true;
                            accountNoForm3.reset();
                            
                        }else if(valItemType=='4' && invType == 'N1001'){
                            accountNoForm.setVisible(false);
                            accountNoForm.allowBlank=true;
                            accountNoForm.reset();
                            
                            rfiNoForm.setVisible(false);
                            rfiNoForm.allowBlank=true;
                            rfiNoForm.reset();   
                                
                            accountNoForm2.setVisible(false);
                            accountNoForm2.allowBlank=true;
                            accountNoForm2.reset();
                            
                            accountNoForm3.setVisible(true);
                            accountNoForm3.allowBlank=false;
                            
                        }else {
                            if(valItemType=='2'){
                                accountNoForm.setVisible(false);
                                accountNoForm.allowBlank=true;
                                accountNoForm.reset();
                                
                                rfiNoForm.setVisible(true);
                                rfiNoForm.allowBlank=false;
                                
                                accountNoForm2.setVisible(false);
                                accountNoForm2.allowBlank=true;
                                accountNoForm2.reset();
                                
                                accountNoForm3.setVisible(false);
                                accountNoForm3.allowBlank=true;
                                accountNoForm3.reset();
                                
                            }
                        }
                    }
                }
            });
            var accountNoForm = Ext.create('Ext.form.field.ComboBox', {
                fieldLabel: 'Expense',
                name: 'accountNo',
                store: dsAccount,
                valueField: 'ACCOUNT_NO',
                displayField: 'ACCOUNT_CD_NAME',
                queryMode: 'local',
                editable: true,
                typeAhead: true,
                forceSelection: true,
                hidden: true,
                flex: 2
            });
            var accountNoForm2 = Ext.create('Ext.form.field.ComboBox', {
                fieldLabel: 'Inventory',
                name: 'accountNo2',
                store: dsAccount2,
                valueField: 'ACCOUNT_NO',
                displayField: 'ACCOUNT_CD_NAME',
                queryMode: 'local',
                editable: true,
                typeAhead: true,
                forceSelection: true,
                hidden: true,
                flex: 2
            });
            var accountNoForm3 = Ext.create('Ext.form.field.ComboBox', {
                fieldLabel: 'Inventory',
                name: 'accountNo3',
                store: dsAccount3,
                valueField: 'ACCOUNT_NO',
                displayField: 'ACCOUNT_CD_NAME',
                queryMode: 'local',
                editable: true,
                typeAhead: true,
                forceSelection: true,
                hidden: true,
                flex: 2
            });
            var rfiNoForm=new Ext.form.TextField({
                fieldLabel: 'RFI',
                name: 'rfiNo',
                maxLength: 6,
                enforceMaxLength: 6,
                hidden: true,
                emptyText: 'xx-xxx',
                maskRe: /[\d\-]/,
                regex: /^\d{2}-\d{3}$/,
                regexText: 'Must be in the format xx-xxx',
                flex: 2
            });
            var rfiNoForm2=new Ext.form.TextField({
                fieldLabel: 'RFI (HDI)',
                name: 'rfiNo',
                maxLength: 6,
                enforceMaxLength: 6,
                hidden: true,
                emptyText: 'xxx-xx',
                maskRe: /[\d\-]/,
                regex: /^\d{3}-\d{2}$/,
                regexText: 'Must be in the format xxx-xx',
                flex: 2
            });
            var itemCdForm = new Ext.form.TextField({
                fieldLabel: 'Item Code',
                name: 'itemCd',
                hidden: true
            });
            var itemNameOldForm = new Ext.form.TextField({
                fieldStyle: {
                    textTransform: "uppercase"
                },
				fieldStyle: {
                    textTransform: "uppercase"
                },
				fieldLabel: 'Item Name Old',
                name: 'itemNameNew',
                hidden: true
            });
            var itemNameForm = Ext.create('Ext.form.field.ComboBox', {
                fieldStyle: {
                    textTransform: "uppercase"
                },
				fieldLabel: 'Name',
                name: 'itemName',
                maxLength: 100,
                enforceMaxLength: 100,
                store: dsItem,
                displayField: 'ITEM_NAME',
                valueField: 'ITEM_NAME',
                queryMode: 'local',
                editable: true,
                hideTrigger: true,
                allowBlank: false,
                typeAhead: true,
                flex: 2,
                listeners: {
                    change: function(combo, record, index){
                        var valItemName = Ext.String.trim(combo.getRawValue());
                        if(valItemName != ''){
                            Ext.Ajax.request({
                                method: 'GET',
                                url: '../db/Master_Data/EPS_M_ITEM.php?action=searchItemPrice',
                                params: {
                                    itemName: valItemName
                                },
                                success: function(response,action){
                                    var msg=Ext.decode(response.responseText).msg.message;
                                    var obj=Ext.decode(response.responseText);
                                    var action=Ext.String.trim(winPrDetail.title.substr(0,4));
                                    unitCdForm.setReadOnly(false);
                                    priceForm.setReadOnly(false);
                                    supplierNameForm.setReadOnly(false);
                                    if(msg=='Exist'){
                                        var valItemCd       = obj.rows[0]['itemCd'];
                                        var valItemName     = obj.rows[0]['itemName'];
                                        var valUnitCd       = obj.rows[0]['unitCd'];
                                        var valPrice        = obj.rows[0]['price'];
                                        var valSupplierCd   = obj.rows[0]['supplierCd'];
                                        var valSupplierName = obj.rows[0]['supplierName'];
                                        var valCurrencyCd   = obj.rows[0]['currencyCd'];

                                        itemCdForm.setValue(valItemCd);
                                        unitCdForm.setValue(valUnitCd);
                                        priceForm.setValue(valPrice);
                                        supplierCdForm.setValue(valSupplierCd);
                                        supplierNameForm.setValue(valSupplierName);
                                        currencyCdForm.setValue(valCurrencyCd);
                                        unitCdForm.setReadOnly(true);
                                        priceForm.setReadOnly(true);
                                        supplierNameForm.setReadOnly(true);
                                    }else{
                                        if(action=='Add'){
                                            itemCdForm.reset();
                                            unitCdForm.reset();
                                            priceForm.reset();
                                            supplierCdForm.reset();
                                            supplierNameForm.reset();
                                        }
                                    }
                                    if(action=='Add'){
                                        itemNameOldForm.setValue(valItemName);
                                    }else{
                                        itemNameOldForm.setValue(itemNameGet);
                                    }
                                }
                            });
                        }
                        itemCdForm.reset();
                        unitCdForm.reset();
                        priceForm.reset();
                        supplierCdForm.reset();
                        supplierNameForm.reset();
                        unitCdForm.setReadOnly(false);
                        priceForm.setReadOnly(false);
                        supplierNameForm.setReadOnly(false);
                    }
                }
            });
            var unitCdForm = Ext.create('Ext.form.field.ComboBox', {
                fieldLabel: 'U M',
                name: 'unitCd',
                store: dsUnit,
                valueField: 'UNIT_CD',
                displayField: 'UNIT_NAME',
                queryMode: 'local',
                editable: false,
                typeAhead: true,
                forceSelection: true,
                allowBlank: false,
                flex: 2
            });
            var qtyForm = new Ext.form.TextField({
                fieldLabel: 'Qty',
                name: 'qty',
                maxLength: 5,
                enforceMaxLength: 5,
                maskRe: /\d/,
                fieldStyle: 'text-align: right;',
                allowBlank: false,
                flex: 2
            });
            var inOrderForm = new Ext.form.TextField({
                fieldLabel: 'inOrder',
                readOnly : true,
                name: 'inOrder',
                maxLength: 5,
                enforceMaxLength: 5,
                maskRe: /\d/,
                fieldStyle: 'text-align: right;',
                allowBlank: true,
                flex: 2
            });
            var stockInvForm = new Ext.form.TextField({
                fieldLabel: 'stockInv',
                readOnly : true,
                name: 'stockInv',
                maxLength: 5,
                enforceMaxLength: 5,
                maskRe: /\d/,
                fieldStyle: 'text-align: right;',
                allowBlank: true,
                flex: 2
            });
            var orderPointForm = new Ext.form.TextField({
                fieldLabel: 'orderPoint',
                readOnly : true,
                name: 'orderPoint',
                maxLength: 5,
                enforceMaxLength: 5,
                maskRe: /\d/,
                fieldStyle: 'text-align: right;',
                allowBlank: true,
                flex: 2
            });
            var currencyCdForm = new Ext.form.TextField({
                fieldLabel: 'Currency',
                name: 'currency',
                readOnly: true,
                hidden: true,
                value: 'IDR'
            });
            var priceForm = Ext.create('Ext.form.NumberField',{
                fieldLabel: 'Estimate Price',
                name: 'price',
                maxLength: 16,
                enforceMaxLength: 16,
                maskRe: /\d/,
                fieldStyle: 'text-align: right;',
                allowBlank: false,
                hideTrigger:true,
                keyNavEnabled: false,
                mouseWheelEnabled: false,
                flex: 2
            });
            var deliveryDateForm = new Ext.form.field.Date({
                fieldLabel: 'Due Date',
                name: 'deliveryDate',
                disabledDays:[0,6],
                disabledDates: ["01/01","01/05","17/08","25/12"
                        ,"01/05/2017","11/05/2017","25/05/2017"
                        ,"01/06/2017","22/06/2017","23/06/2017","26/06/2017","27/06/2017","28/06/2017","29/06/2017","30/06/2017"
                        ,"01/09/2017","21/09/2017"
                        ,"01/12/2017","29/12/2017"
                        ,"01/01/2018"
                        ,"16/02/2018"
                        ,"30/03/2018"
                        ,"13/04/2018"
                        ,"01/05/2018","10/05/2018","29/05/2018"
                        ,"01/06/2018","15/06/2018"
                        ,"22/08/2018"
                        ,"12/09/2018"
                        ,"20/11/2018"
                        ,"24/12/2018"
                        ,"05/02/2019"
                        ,"07/03/2019"
                        ,"03/04/2019","19/04/2019"
                        ,"01/05/2019","30/05/2019"
                        ,"04/06/2019","05/06/2019"
						,"12/08/2019"],
				format: 'd/m/Y',
                minValue : Ext.Date.add(new Date(), Ext.Date.DAY, +15),
                maxValue : Ext.Date.add(new Date(), Ext.Date.DAY, +730),
                //value: Ext.Date.add(new Date(), Ext.Date.DAY, +15),
                allowBlank: false,
                flex: 2
            });
            var supplierCdForm = new Ext.form.TextField({
                fieldLabel: 'Kode Supplier',
                name: 'supplierCd',
                hidden: true
            });
			var supplierNameForm = Ext.create('Ext.form.field.ComboBox', {
                fieldStyle: {
                    textTransform: "uppercase"
                },
				fieldLabel: 'Estimate Supplier',
                name: 'supplierName',
                maxLength: 60,
                enforceMaxLength: 60,
                store: dsSupplier,
                displayField: 'SUPPLIER_NAME',
                valueField: 'SUPPLIER_NAME',
                queryMode: 'local',
                editable: true,
                hideTrigger: true,
                typeAhead: true,
                flex: 3,
                listeners: {
                    change: function(combo, record, index){
                        var valSupplierName = combo.getRawValue();
                        Ext.Ajax.request({
                            method: 'GET',
                            url: '../db/Master_Data/EPS_M_SUPPLIER.php?action=detail',
                            params: {
                                supplierName: valSupplierName
                            },
                            success: function(response,action){
                                var msg=Ext.decode(response.responseText).msg.message;
                                var obj=Ext.decode(response.responseText);
                                if(msg=='Exist'){
                                    var valSupplierCd=obj.rows[0]['supplierCd'];
                                    supplierCdForm.setValue(valSupplierCd);
                                 }else{
                                    supplierCdForm.reset();
                                }
                            }
                        });
                    }
                }
            });
            var remarkForm = new Ext.form.TextField({
                fieldStyle: {
                    textTransform: "uppercase"
                },
				fieldLabel: 'Remark',
                name: 'remark',
                maxLength: 250,
                enforceMaxLength: 250
            });
            /** 
             * =======================================
             * Define PR Attachment Form Component 
             * =======================================
             **/
            var browseForm = new Ext.form.field.File({
                fieldLabel: 'File',
                name: 'browseFile',
                disabled: true,     
                flex: 3,
                labelWidth: 40,
                listeners:{
                    change: function(){
                        if(dsPrAttachmentTemp.data.length==10){
                            Ext.MessageBox.alert('Message','Maximum upload 10 attachment for each item.');
                        }else{
                            this.up('form').getForm().submit({
                                method: 'POST',
                                url: '../db/PR/UPLOAD_PR_ATTACHMENT.php',
                                params: {
                                    prNo: prNo.getValue()
                                },
                                success: function(fp,o, response){
                                    var msg         = o.result.msg.message;
                                    var itemCd      = itemCdForm.getValue();
                                    var itemName    = Ext.String.trim(itemNameForm.getRawValue());
                                    var itemNameOld = Ext.String.trim(itemNameOldForm.getRawValue());
                                    var fileName    = Ext.util.Format.htmlDecode(o.result.fileName);
                                    var fileType    = o.result.fileType;
                                    var fileSize    = o.result.fileSize;
                                    dsPrAttachmentTemp.clearFilter();
                                    
                                    if(msg == 'Success'){
                                        myAttachmentTemp.push([itemCd,itemName,itemNameOld,fileName,fileSize,fileType]);
                                    }else if(msg == 'ErrorFileType'){
                                        Ext.MessageBox.alert('Message','There was an upload error. Make sure to upload a JPG, PNG, TIFF, PDF, XLS, DOC, PPT.');
                                    }else if(msg == 'ErrorFileSize'){
                                        Ext.MessageBox.alert('Message','File size be must less than 2 MB.');                                
                                    }else if(msg == 'ErrorDuplicate'){
                                        Ext.MessageBox.alert('Message','File name already exist.');
                                    }else {
                                        if(msg == 'ErrorLengthFileName'){
                                            Ext.MessageBox.alert('Message','File name must be less than 200 characters.');
                                        }
                                    }
                                    dsPrAttachmentTemp.load();
                                }
                            });
                        }
                    }
                }
            });
            /**
            * ===================================================================
            * ************************** IE problem *****************************
            * Handle double upload file on listeners change in button browse file
            * ===================================================================
            **/
            Ext.form.field.File.override({
                checkChange : function () {
                    if (!this.suspendCheckChange) {
                        var me = this,
                        newVal = me.getValue(),
                        oldVal = me.lastValue;

                        if (!me.isEqual(newVal, oldVal) && !me.isDestroyed && !Ext.isEmpty(newVal)) {
                            me.lastValue = newVal;
                            me.fireEvent('change', me, newVal, oldVal);
                            me.onChange(newVal, oldVal);
                        }
                    }
                }
            });
            /** 
             * ========================================================================================================
             * **************************************** DEFINE GRID ***************************************************
             * ========================================================================================================
             **/
            /** 
             * =======================================
             * Define PR Attachment Grid - View
             * =======================================
             **/
            var prAttachmentView = Ext.create('Ext.grid.Panel', {
                frame: false,
                border: true,
                autoScroll: true,
                columnLines: true,
                height: 195,
                store: dsPrAttachmentTemp,
                columns: [{
                    header: 'ITEM CODE',
                    width: 75,
                    align: 'center',
                    dataIndex: 'itemCd',
                    hidden: true,
                    hideable: false
                },{
                    header: 'ITEM NAME',
                    align: 'center',
                    dataIndex: 'itemName',
                    hidden: true,
                    hideable: false
                },{
                    header: 'ITEM NAME OLD',
                    align: 'center',
                    dataIndex: 'itemNameOld',
                    hidden: true,
                    hideable: false
                },{
                    header: 'FILE NAME',
                    align: 'center',
                    dataIndex: 'fileName',
                    flex: 1,
                    renderer: function (val, metaData, record){
                        var prNoVal = prNo.getValue();
                        return '<a href="../db/Attachment/Temporary/'+prNoVal+'-temp/'+val+'" target="_blank">'+val+'</a>';
                    }
                },{
                    header: 'SIZE',
                    width: 70,
                    align: 'center',
                    dataIndex: 'fileSize'
                },{
                    header: 'TYPE',
                    align: 'center',
                    dataIndex: 'fileType',
                    flex: 1
                }]
            });
            /** 
             * =======================================
             * Define PR Attachment Grid 
             * =======================================
             **/
            var prAttachment = Ext.create('Ext.grid.Panel', {
                frame: false,
                border: true,
                autoScroll: true,
                columnLines: true,
                height: 195,
                store: dsPrAttachmentTemp,
                columns: [{
                    header: 'ACTION',
                    xtype: 'actioncolumn',
                    width: 45,
                    align: 'center',
                    tooltip: 'Delete',
                    icon: '../images/delete16.png',
                    handler: function(prDetailGrid,rowIndex,e){
                        deletePrItemAttachment(prDetailGrid,rowIndex,e);
                    }
                },{
                    header: 'ITEM CODE',
                    width: 75,
                    align: 'center',
                    dataIndex: 'itemCd',
                    hidden: true,
                    hideable: false
                },{
                    header: 'ITEM NAME',
                    align: 'center',
                    dataIndex: 'itemName',
                    hidden: true,
                    hideable: false
                },{
                    header: 'ITEM NAME OLD',
                    align: 'center',
                    dataIndex: 'itemNameOld',
                    hidden: true,
                    hideable: false
                },{
                    header: 'FILE NAME',
                    align: 'center',
                    dataIndex: 'fileName',
                    flex: 1,
                    renderer: function (val, metaData, record){
                        var prNoVal = prNo.getValue();
                        return '<a href="../db/Attachment/Temporary/'+prNoVal+'-temp/'+val+'" target="_blank">'+val+'</a>';
                    }
                },{
                    header: 'SIZE',
                    width: 70,
                    align: 'center',
                    dataIndex: 'fileSize'
                },{
                    header: 'TYPE',
                    align: 'center',
                    dataIndex: 'fileType',
                    flex: 1
                }],
                dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [browseForm]
                }],
                tbar:[{ 
                    text: 'Attachment',
                    tooltip: 'Attachment',
                    id: 'tbAttachment',
                    handler: function(){
                        if(prDetail.getForm().isValid()){
                            browseForm.enable();
                            Ext.getCmp('tbAttachment').disable();
                        }else{
                            browseForm.disable();
                        }
                    }
                }]
            });
            /** 
             * =======================================
             * Define PR Detail Grid
             * =======================================
             **/
            var prDetailGrid = Ext.create ('Ext.grid.Panel',{
                columnLines: true,
                frame: true,
                height: 485,
                store: dsPrItem,
                region: 'center',
                title: 'Purchase Requisition Item',
                features: [{
                    ftype: 'summary'
                }],
                columns: [{
                    header: 'NO',
                    width: 25,
                    align: 'center',
                    xtype: 'rownumberer',
                    sortable: true
                },{
                    header: 'ACTION',
                    align: 'center',
                    columns: [{
                        header: 'EDIT',
                        width: 40,
                        align: 'center',
                        xtype: 'actioncolumn',
                        icon: '../images/edit16.png',
                        tooltip: 'Edit PR Item',
                        getClass: function(v,meta,rec){
                            if(rec.get('itemStatus')=='1070'){
                                return 'x-hide-display';
                            }
                        },
                        handler: function(prDetailGrid,rowIndex,colIndex){
                            showWinEditPrDetail(prDetailGrid,rowIndex,colIndex);
                        }
                    },{
                        header: 'DELETE',
                        width: 44,
                        align: 'center',
                        xtype: 'actioncolumn',
                        icon: '../images/delete16.png',
                        tooltip: 'Delete PR Item',
                        getClass: function(v,meta,rec){
                            if(rec.get('itemStatus')=='1070'){
                                return 'x-hide-display';
                            }
                        },
                        handler: function(prDetailGrid,rowIndex,colIndex){
                            deletePrDetail(prDetailGrid,rowIndex,colIndex);
                        }
                    },{
                        header: 'ATTACH',
                        width: 48,
                        align: 'center',
                        xtype: 'actioncolumn',
                        icon: '../images/attach16.png',
                        tooltip: 'View Attachment',
                        getClass: function(v,meta,rec){
                            var value = rec.get('itemName');
                            var countAttach=0, countAttachTemp=0;
                            
                            for(var i = 0; i < myAttachmentTemp.length; i++){
                                var itemName_1 = myAttachmentTemp[i][1];
                                if(value == itemName_1){
                                    countAttachTemp++;
                                }
                            }
                            for(var j = 0; j < myAttachment.length; j++){
                                var itemName_2 = myAttachment[j][1];
                                if(value == itemName_2){
                                    countAttach++;
                                }
                            }
                            if(countAttach == 0 && countAttachTemp == 0){
                                return 'x-hide-display';
                            }
                        },
                        handler: function(prDetailGrid,rowIndex,colIndex){
                            showWinViewPrAttachment(prDetailGrid,rowIndex,colIndex);
                        }
                    },{
                        header: 'REJECT',
                        width: 45,
                        align: 'center',
                        xtype: 'actioncolumn',
                        icon: '../images/reject16.png',
                        tooltip: 'Reject PR Item',
                        getClass: function(v,meta,rec){
                            if(rec.get('itemStatus')=='1070' || dsPrItem.totalCount == 1){
                                return 'x-hide-display';
                            }
                        },
                        handler: function(prDetailGrid,rowIndex,colIndex){
                            showWinRejectPrDetail(prDetailGrid,rowIndex,colIndex);
                        }
                    }]
                },{
                    header: 'ITEM STATUS',
                    width: 75,
                    align: 'center',
                    dataIndex: 'itemStatus',
                    hidden: true,
                    hideable: false
                },{
                    header: 'ITEM',
                    columns: [{
                        header: 'CODE',
                        width: 55,
                        align: 'center',
                        dataIndex: 'itemCd'
                    },{
                        header: 'ITEM NAME ( Maker, Part No, Spec, Size, Color etc )',
                        width: 300,
                        align: 'center',
                        dataIndex: 'itemName'
                    }]
                },{
                    header: 'QTY',
                    width: 50,
                    align: 'right',
                    dataIndex: 'qty'
                },{
                    header: 'IN ORDER',
                    width: 100,
                    align: 'center',
                    dataIndex: 'inOrder'
                },{
                    header: 'STOCK INV',
                    width: 90,
                    align: 'center',
                    dataIndex: 'stockInv'
                },{
                    header: 'ORDER POINT',
                    width: 90,
                    align: 'center',
                    dataIndex: 'orderPoint'
                },{
                    header: 'USER REFERENCE (ESTIMATE)',
                    columns: [{
                        header: 'CURRENCY',
                        align: 'center',
                        width: 70,
                        dataIndex: 'currencyCd'
                    },{
                        header: 'UNIT PRICE',
                        width: 100,
                        align: 'right',
                        dataIndex: 'itemPrice',
                        xtype: 'numbercolumn',
                        format: '0,000'
                    }, {
                        header: 'SUPPLIER',
                        width: 220,
                        align: 'center',
                        dataIndex: 'supplierName',
                        summaryType: 'count',
                        summaryRenderer: function(value, summaryData, dataIndex) {
                            return '<b>Total Amount</b>'
                        }
                    },{
                        header: 'SUPPLIER CODE',
                        align: 'center',
                        dataIndex: 'supplierCd',
                        hidden: true,
                        hideable: false
                    }]
                },{
                    header: 'AMOUNT',
                    align: 'right',
                    dataIndex: 'amount',
                    renderer: Ext.util.Format.numberRenderer('0,000/i'),
                    summaryType: function(records){
                        var total = 0, record;
                        for(var i=0; i<records.length;i++){
                            var record=records[i];
                            total += parseInt(record.get('amount'));
                        }
                        return Ext.util.Format.number(total,'0,000/i');
                    }
                },{
                    header: 'DUE DATE',
                    width: 72,
                    align: 'center',
                    dataIndex: 'deliveryDate',
                    renderer: Ext.util.Format.dateRenderer('d/m/Y')
                },{
                    header: 'TYPE',
                    width: 40,
                    align: 'center',
                    dataIndex: 'itemType',
                    renderer:  function jnsinvest(val){
                        if(val == '1'){
                            return 'EXP';
                        }else if(val == '2'){
                            return 'RFI';
                        }else if(val == '3' || val == '4'){
                            return 'INV';
                        }else{
                            return '';
                        }
                        return val;
                    }
                },{
                    header: 'RFI',
                    width: 55,
                    align: 'center',
                    dataIndex: 'rfiNo'
                },{
                    header: 'EXP',
                    width: 40,
                    align: 'center',
                    dataIndex: 'accountNo'
                },{
                    header: 'U M',
                    width: 40,
                    align: 'center',
                    dataIndex: 'unitCd'
                },{
                    header: 'REMARK',
                    width: 200,
                    align: 'center',
                    dataIndex: 'remark'
                },{
                    header: 'REJECT INFORMATION',
                    columns: [{
                        header: 'REASON',
                        width: 200,
                        align: 'center',
                        dataIndex: 'reasonToReject'
                    },{
                        header: 'REJECT BY',
                        width: 100,
                        align: 'center',
                        dataIndex: 'rejectItemBy',
                        hidden: true,
                        hideable: false
                    },{
                        header: 'REJECT BY',
                        width: 160,
                        align: 'center',
                        dataIndex: 'rejectItemName'
                    }]
                }
                
                ],
                tbar:[{ 
                    text: 'Add PR Item',
                    tooltip: 'Add PR Item',
                    handler: function(){
                        showWinAddPrDetail();
                    }
                }]
            });
            /** 
             * =======================================
             * Define PR Approver Grid
             * =======================================
             **/
            var prApprover = Ext.create ('Ext.grid.Panel',{
                columnLines: true,
                frame: true,
                height: 225,
                store: dsPrApprover,
                region: 'center',
                title: 'Approval Information',
                columns: [{
                    header: 'NO',
                    width: 40,
                    align: 'center',
                    dataIndex: 'APPROVER_NO'
                }, {
                    header: ' NAME',
                    width: 200,
                    dataIndex: 'APPROVER_NAME'
                },{
                    header: 'STATUS',
                    width: 130,
                    dataIndex: 'APPROVAL_STATUS_NAME',
                    renderer: approvalStatusVal
                },{
                    header: 'DATE',
                    columns: [{
                        header: 'APPROVAL DATE',
                        width: 150,
                        align: 'center',
                        renderer: Ext.util.Format.dateRenderer('m/d/Y H:i:s A'),
                        dataIndex: 'APPROVAL_DATE'
                    },{
                        header: 'DATE OF BY PASS',
                        width: 150,
                        align: 'center',
                        dataIndex: 'DATE_OF_BYPASS'
                    }]
                },{
                    header: 'REMARK',
                    width: 280,
                    dataIndex: 'APPROVAL_REMARK'
                }]
            });
			/** 
             * =======================================
             * Define PR Approver Special Grid
             * =======================================
             **/
            var prApproverSpecial = Ext.create ('Ext.grid.Panel',{
                columnLines: true,
                frame: true,
                hidden: true,
                height: 110,
                store: dsPrApproverSpecial,
                region: 'center',
                title: 'IS Approval Information',
                columns: [{
                    header: 'NO',
                    width: 25,
                    align: 'center',
                    xtype: 'rownumberer',
                    sortable: true
                },{
                    header: ' NAME',
                    width: 200,
                    dataIndex: 'APPROVER_NAME'
                },{
                    header: 'STATUS',
                    width: 130,
                    dataIndex: 'APPROVAL_STATUS_NAME',
                    renderer: approvalStatusVal
                },{
                    header: 'DATE',
                    columns: [{
                        header: 'APPROVAL DATE',
                        width: 150,
                        align: 'center',
                        renderer: Ext.util.Format.dateRenderer('m/d/Y H:i:s A'),
                        dataIndex: 'APPROVAL_DATE'
                    },{
                        header: 'DATE OF BY PASS',
                        width: 150,
                        align: 'center',
                        dataIndex: 'DATE_OF_BYPASS'
                    }]
                },{
                    header: 'REMARK',
                    width: 280,
                    dataIndex: 'APPROVAL_REMARK'
                }]
            });
            if(Ext.String.trim(prApproverBu) != '3300' && Ext.String.trim(prApproverBu) != '3301' && Ext.String.trim(prApproverBu) != '3302'  && Ext.String.trim(prApproverBu) != '3941' &&  prSpecialType == 'IT'){
                prApproverSpecial.hidden=false;
            }
            /** 
             * ========================================================================================================
             * **************************************** DEFINE FORM ***************************************************
             * ========================================================================================================
             **/
            /** 
             * =======================================
             * Define PR Detail Form 
             * =======================================
             **/
            var prDetail = Ext.widget('form',{
                border: false, 
                frame: true,
                bodyPadding: '2',
                height: 514,
                fieldDefaults: {
                    anchor: '100%',
                    labelWidth: 100,
                    msgTarget: 'side',
                    labelAlign: 'right'
                },
                items: [{
                    xtype: 'fieldset',
                    title: '<b>Description</b>',
                    height: 230,
                    items: [{
                        xtype: 'container',
                        layout: 'hbox',
                        items: [itemTypeForm,itemTypeForm2,itemTypeForm3]
                    },{
                        xtype: 'container',
                        layout: 'hbox',
                        items: [accountNoForm,accountNoForm2,accountNoForm3,rfiNoForm,rfiNoForm2]
                    },{
                        xtype: 'container',
                        layout: 'hbox',
                        items: [itemCdForm,itemNameOldForm,itemNameForm]
                    },{
                        xtype: 'container',
                        layout: 'hbox',
                        items: [unitCdForm,deliveryDateForm]
                    },{
                        xtype: 'container',
                        layout: 'hbox',
                        items: [priceForm,qtyForm ]
                        
                    },{
                        xtype: 'container',
                        layout: 'hbox',
                        items: [inOrderForm, stockInvForm, orderPointForm ]
                        
                    },{
                        xtype: 'container',
                        layout: 'hbox',
                        items: [supplierNameForm,supplierCdForm]
                    },remarkForm]
                },{
                    xtype: 'fieldset',
                    title: '<b>Attachment</b>',
                    height: 228,
                    items: [prAttachment]
                }]
                ,buttons: [{
                    text: 'Save',
                    name: 'save',
                    handler: function(){
                        savePrDetail();
                    }
                },{
                    text: 'Reset',
                    name: 'reset',
                    handler: function(){
                        resetPrDetail();
                    }
                },{
                    text: 'Cancel',
                    name: 'cancel',
                    handler: function(){
                        cancelPrDetail();
                    }
                }]
            });
            /** 
             * =======================================
             * Define PR Header Form 
             * =======================================
             **/
            var prHeader = Ext.create('Ext.form.Panel',{
                frame: true,
                bodyPadding: '5',
                margin: '1',
                height: 220,
                fieldDefaults: {
                    msgTarget: 'side',
                    anchor: '100%',
                    labelWidth: 145
                },
                items: [{
                    xtype: 'fieldset',
                    title: '<b>PR Information</b>',
                    height: 85,
                    items: [{
                        xtype: 'container',
                        layout: 'hbox',
                        items: [prNo,prDate,specialType]
                    },purpose]
                },{
                    xtype: 'fieldset',
                    title: '<b>Requester Information</b>',
                    height: 100,
                    items: [{
                        xtype: 'container',
                        layout: 'hbox',
                        items: [requester,requesterName,ext]
                    },{
                        xtype: 'container',
                        layout: 'hbox',
                        items: [plant,company,buCd]
                    },{
                        xtype: 'container',
                        layout: 'hbox',
                        items: [prIssuer,prCharged]
                    }]
                }]
            });
            /** 
             * ========================================================================================================
             * **************************************** DEFINE MISCELLANEOUS ******************************************
             * ========================================================================================================
             **/
            /** 
             * =======================================
             * Define Toolbar Button
             * =======================================
             **/
            var tb = [{
                xtype: 'toolbar',
                id: 'tb',
                //ui: 'light',
                dock: 'top',
                items: [{
                    text: 'Approve PR',
                    tooltip: 'Approve PR',
                    id: 'approve-btn',
                    iconCls: 'approve_button',
                    scale: 'medium',
                    handler: function(me){
                        updatePr(me.getText());
                    }
                },'-',{
                    text: 'Reject PR',
                    tooltip: 'Reject PR',
                    id: 'reject-btn',
                    iconCls: 'reject_button',
                    scale: 'medium',
                    handler: function(me){
                        updatePr(me.getText());
                    }
                },'-',{
                    text: 'Back',
                    tooltip: 'Back',
                    id: 'back-btn',
                    iconCls: 'cancel_button',
                    scale: 'medium',
                    handler: function(me){
                        returnPr(me.getText());
                    }
                },'-',{
                    text: 'Bypass IT Approval',
                    tooltip: 'Bypass IT Approval',
                    id: 'byPassButton',
                    iconCls: 'bypass_it_button',
                    scale: 'medium',
                    disabled: true,
                    handler: function(me){
                        bypassITApprovalPr(me.getText());
                    }
                }]
            }];
            /** 
             * =======================================
             * Define Content
             * =======================================
             **/
            var panelCenter = Ext.create('Ext.form.Panel', {
                border: false,
                frame: true,
                title: 'Approval Purchase Requisition',
                fieldDefaults: {
                    msgTarget: 'side',
                    labelAlign: 'right',
                    labelWidth: 145
                },
                //dockedItems: tb,
                items: [prHeader,prApprover,prApproverSpecial, tb, prDetailGrid]
            });
            /** 
             * =======================================
             * Define Layout
             * =======================================
             **/
            var mainView = new Ext.create('Ext.Viewport',{
                layout: 'border', 
                padding: '5',
                items: [{
                    region: 'north',
                    split:true, 
                    border:false, 
                    items: [toolbarTop]
                },{
                    region: 'center',
                    id: 'content', 
                    autoScroll: true,
                    items: [panelCenter]
                }],
                renderTo: Ext.getBody()
            });
        }
        Ext.onReady(mainLayout);
        </script>
    </head>
    <body>
    </body>
</html>