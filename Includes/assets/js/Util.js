function wanrMessage(msg){
         var $msgDlg = '<div class="alert alert-block alert-error fade in" style="display: none">';
             $msgDlg += '<button data-dismiss="alert" class="close" type="button">×</button>';
             $msgDlg += '<h4 class="alert-heading">'+msg+'</h4>';
             $msgDlg += '</div>';
             $("#messageDialog").html($msgDlg);
             $('.alert').show();
}

function clearMessage(){
    $("#messageDialog").html('');
}

function showMessage(msg, is_error){
    var startMarker = "<!--error-user-start-->";
    var pos = msg.indexOf(startMarker);
    var is_valid = true;
    var $msgDlg;
             

    //alert(is_error)
    if(pos > -1 || is_error == true ){
        is_valid = false;
        msg = extractPart(msg, 'error', 'user');
        $msgDlg = '<div class="alert alert-block alert-error fade in" style="display: none">';
    }else{
        $msgDlg = '<div class="alert alert-block alert-success fade in" style="display: none">';
    }
     $msgDlg += '<button data-dismiss="alert" class="close" type="button">×</button>';
     $msgDlg += '<h4 class="alert-heading">'+msg+'</h4>';
     $msgDlg += '</div>';
    showComplete($msgDlg);
    return is_valid;
}

function showComplete($msgDlg){
    $("#messageDialog").html($msgDlg);
    $('.alert').show();
    /*
    $("#message_response").html(msg);
    $(".message.localMessage").show();
    $("#errors").hide();
    //$(window).scrollTop($("#pnlAddEdit").offset().top);
    */
    $('html, body').animate({
    scrollTop: $("#pnlAddEdit").offset().top
 }, 1000);
}

function isExpire(msg){
    if(msg==undefined) return false;

    var startMarker = "<!--expire-session-->";
    if(typeof msg === "string") {

        var pos = msg.indexOf(startMarker);
        if(pos > -1){
            window.location = window.location;
            return true;
        }
    }
    return false;
}

function checkboxChecked(selector) {
    var allVals = [];
    $(selector).each(function() {
        allVals.push($(this).val());
    });
    return allVals;
}

function GridBase(aInfo){
    var thisClass = this;
    this.hdnID = '';
    this.submitForm = false;
	
    if(aInfo!=undefined)
    {
        this.editID = aInfo.editID;
        this.refOper = aInfo.refOper;
        this.isDetail = aInfo.isDetail;
        if(aInfo.role_edit==='None'){
            $('#btnSave'+aInfo.classname).hide();
            $('#btnSaveBack'+aInfo.classname).hide();
            $('#btnDelete'+aInfo.classname).hide();
            $('#btnAdd'+aInfo.classname).hide();
        }
    }

    this.setGrdParam = function(){
            $params = 'processajax.php?q=' + aInfo.classname +'&nd='+new Date().getTime()+'&fileclass='+ aInfo.fileclass;
            if(!$.isEmptyObject(this.optSearch)){
                for(key in this.optSearch){
                    $params += '&' + key +'=' + this.optSearch[key]
                }
            }
            return $params;
    }

    this.setInputSize = function(){
            $(".ui-pg-input").addClass('input-mini');
            $(".ui-pg-selbox").addClass('span1');
    }
    
    this.getChk = function(){
        obj = {};
        $.each(aInfo.chk_fields, function(index, field){
                eval("obj." + field + "='" + ($("#chk_" + field).attr('checked')?'Y':'N') + "'");
            } );
            return obj;
    }

    this.clearChk = function(){
        $.each(aInfo.chk_fields, function(index, field){
            $("#chk_"+field).attr('checked', false);
        });
    }

    this.bindChk = function(aData){
        
        $.each(aInfo.chk_fields, function(index, field){
            $("#chk_"+field).attr('checked', (aData[field]=='Y'?true:false));
        });
    }

    this.clearImg = function(){
        $.each(aInfo.img_fields, function(index, field){
            $("#img_"+field).attr('src', aInfo.img_prefix+"no_image.jpg");
        });
    }

    this.bindImg = function(aData){
        $.each(aInfo.img_fields, function(index, field){
            $("#img_"+field).attr('src', aInfo.img_s_prefix+aData[field]);
            if(aData[field] == null){
                $("#img_"+field).attr('src', aInfo.img_prefix+"no_image.jpg");
            }
        });
    }
    this.getTxt = function(){
        obj = {};
        $.each(aInfo.txt_fields, function(index, field){
                if($.trim($("#txt_" + field).val())!='' && typeof $("#txt_" + field).val() == 'string' ){
                    eval("obj." + field + "='" + $("#txt_" + field).val().replace(/\n\r?/g, '[<br>]') + "'");
                }else{
                    eval("obj." + field + "='" + $("#txt_" + field).val() + "'");
                }
            });
            return obj;
    }

    this.getSelect = function(){
        obj = {};
        $.each(aInfo.select_fields, function(index, field){
                if($.trim($("#select_" + field).val())!='' && typeof $("#select_" + field).val() == 'string' ){
                    eval("obj." + field + "='" + $("#select_" + field).val().replace(/\n\r?/g, '[<br>]') + "'");
                }else{
                    eval("obj." + field + "='" + $("#select_" + field).val() + "'");
                }
            });
            return obj;
    }

    this.clearTxt = function(){
        $.each(aInfo.txt_fields, function(index, field){
            $("#txt_"+field).val('');
        });
    }

    this.bindTxt = function(aData){
        $.each(aInfo.txt_fields, function(index, field){
            var _v = aData[field];
            if($.trim(_v)!='' && typeof _v == 'string' ){
                _v = _v.replace(/\[<br>\]/g, "\n")
            }
            $("#txt_"+field).val( _v );
        });
    }

    this.bindSelect = function(aData){
        $.each(aInfo.select_fields, function(index, field){
            var _v = aData[field];
            if($.trim(_v)!='' && typeof _v == 'string' ){
                _v = _v.replace(/\[<br>\]/g, "\n")
            }
            $("#select_"+field).val( _v );            
        });
    }

    this.clearInputFile = function(){
        $.each(aInfo.img_fields,function(index, field){
            $('#div_'+field).html($('#div_'+field).html());
            $("#i_"+field).hide();
            $("#icons_"+field).remove();
        })
    }

    this.initialControl = function(){
        this.dataSearch();
        //$(aInfo.grd).setPostDataItem( "oper", "search");
        $(aInfo.grd).jqGrid('setGridParam',{postData:{'oper':'search'} });

        if(aInfo.role_edit==='None'){
            $(aInfo.grd).hideCol("operation");
        }else{
            $(aInfo.grd).showCol("operation");
        }
    }

    this.blockUI = function(){
        $.blockUI({ css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        } });
    }

    this.unblockUI = function(){
        $.unblockUI();
    }

    this.gridReload = function(){
        this.dataSearch();
        $(aInfo.grd).setGridParam({ page:1 });
        $(aInfo.grd).setGridParam({ datatype:'json'});
        $(aInfo.grd).trigger("reloadGrid");
    }

    this.clearBlock = function(oper, error){
        if(error=="undefined")error='';
        if(oper=='edit')
            showMessage(msg_update_complete+error);
        else showMessage(msg_insert_complete+error);

       // $("#pnlAddEdit").unblock();
    }
    
    this.editStatus = function(fieldname, pk_id){
        if(fieldname=='is_delete') {
                var ids = [pk_id];
                this.confirmDlg(msg_delconfirm, msg_delcaption, ids);
                return;
        }

        $(aInfo.grd).jqGrid('setGridParam',{postData:{'oper':'editStatus', "fieldname":fieldname, 'pk_id':pk_id} })
        $(aInfo.grd).trigger("reloadGrid");
    }

    this.setButtonUI = function(el){
        $(el).addClass(
                //'ui-state-default ' +
                'ui-corner-all'
                ).hover(
                function() {
                        $(this).addClass('ui-state-hover');
                        $(this).css("cursor", "pointer");
                            $(this).css('background', 'none');
                            $(this).css('border', 'none');
                },
                function() {
                        $(this).removeClass('ui-state-hover');
                        $(this).css("cursor", "default");
                }
                )
                .focus(function() {
                    $(this).addClass('ui-state-focus');
                })
                .blur(function() {
                    $(this).removeClass('ui-state-focus');
                });

    }

    this.openPnlGrid = function (pnlGrdID){
        clearMessage();
        thisClass.submitForm = false;
        thisClass.editData = false;
        $('#'+pnlGrdID).show();
        $('#pnlAddEdit').hide();
        jQuery(window).resize();   
    }

    this.closePnlGrid = function (pnlGrdID){
        $('#'+pnlGrdID).hide();
        $('#pnlAddEdit').show();
        jQuery(window).resize();
        clearMessage();
    }

    this.fitTable = function(hideMid, hideLarge){
        var win = $("#divContent");
        var grd = thisClass.grid;
        var arrAll = thisClass.hideMid.concat(thisClass.hideLarge);
        if(win.width() <= 500) {
            grd.hideCol(arrAll);
        }
        else if (win.width() <= 700) {
            grd.showCol(thisClass.hideLarge);
            grd.hideCol(thisClass.hideMid);
        }
        else {
            grd.showCol(arrAll);
        }
        
        grd.setGridWidth(win.width());
    }
    
    this.setFontButton = function(ele){
    	$(ele).css('cursor', 'pointer');
    	$(ele).hover(function(){
        	$(this).css('font-weight','bold');
        },function(){
        	$(this).css('font-weight','');
        });
    }
    
    this.confirmDlg = function(msg, title, task)
    {
     $( "#dialog-confirm" ).dialog({
                    title : title,
                            resizable: false,
                            width: 400,
                            height:'auto',
                            modal: true,
                            buttons: {
                                    "OK": function() {
                                            thisClass.excuteFN(task);
                                            $( this ).dialog( "close" );
                                    },
                                    "Cancel": function() {
                                            $( this ).dialog( "close" );
                                    }
                            }
                    });


    $('#messageDlg').dialog('option', 'buttons',
        {"Cancel": function() { $('#messageDlg').dialog("close"); },
            "OK": function(){ executeFN(task); $('#messageDlg').dialog("close");}}
    );

        $("#contain_content").html(msg);
        //$('#messageDlg').dialog('open');
    }

    this.initValidateForm = function(formId, rules, messages){
    	if(messages == undefined)
    		messages = {};
        $(formId).validate({
            rules: rules,
            messages: messages,
            invalidHandler: function(event, validator) {
                // 'this' refers to the form
                thisClass.submitForm = false;
            }
        });
    }

    this.resetValidatForm = function(formId){
            var validator = $(formId).validate();
            validator.resetForm();
            $(".error").removeClass('error');
    }
    this.initValidateFormEvent = function(formId){
        thisClass.submitForm = true;
        if($(formId).length > 0){
            $(formId).validate().form();
        }
    }

    this.ctrlSave = function(formId, divClose){
       $(document).keypress(function(event) {
            if ((event.which == 115 && (event.ctrlKey||event.metaKey)|| (event.which == 19))) {
                event.preventDefault();
                // do stuff
                thisClass.initValidateFormEvent(formId);
                if(thisClass.editData){
                    if (thisClass.submitForm){
                        if (thisClass.saveData()){
                            thisClass.openPnlGrid(divClose);
                            thisClass.submitForm = false;
                        }
                    }
                }
                return false;
            }
            return true;
        });
    }
}

