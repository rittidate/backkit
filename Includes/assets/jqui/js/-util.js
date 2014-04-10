function checkScrollBar() {
            var hContent = $("body").height(); // get the height of your content
            var hWindow = $(window).height(); // get the height of the visitor's browser window

            if(hContent>hWindow) { // if the height of your content is bigger than the height of the browser window, we have a scroll bar
                return true;    
            }

            return false;
    }
    
function numberWithCommas(x) {
    if(!isNaN(x))
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    else return x;
}

function removeCommas(v){
    console.log(v);
   return v.replace(/,/g , '');
}

function extractPart(data, val1, val2) {
    var startMarker = "<!--" + val1 + "-" + val2 + "-start-->";
    var endMarker = "<!--" + val1 + "-" + val2 + "-end-->";
    return data.substring(data.indexOf(startMarker) + startMarker.length, data.indexOf(endMarker));
}

function showComplete(msg){
    $("#message_response").html(msg);
    $(".message.localMessage").show();
    $("#errors").hide();
    //$(window).scrollTop($("#pnlAddEdit").offset().top);
    
    $('html, body').animate({
    scrollTop: $("#pnlAddEdit").offset().top
 }, 1000);
}

function clearMessage(){
     $(".message.localMessage").hide();
     $("#errors").hide();
}

function showError(msg){
    if(msg==undefined) return false;
    var startMarker = "<!--error-user-start-->";
    if(typeof msg === "string") {
        var pos = msg.indexOf(startMarker);
        if(pos > -1){
           var error_msg = extractPart(msg, 'error', 'user');
           $("#errors_msg").html(error_msg);
            $("#errors").show();
            $(".message.localMessage").hide();
            return true;
        }
    }
    return false;
}


function getFile(str)
{
	if("undefined"==typeof(str) )
		str=location.href;
return (str.replace(/.+[\/]([^\/]+)$/,'$1'))
}
/* with no arguments you get back the file name of the page that is currently loaded on the browser */


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

function showMessage(msg, is_error){
    var startMarker = "<!--error-user-start-->";
    var pos = msg.indexOf(startMarker);
    var is_valid = true;
    //alert(is_error)
    if(pos > -1 || is_error == true ){
        is_valid = false;
        msg = extractPart(msg, 'error', 'user');
        $(".message.localMessage .panel").removeClass('confirm');
        $(".message.localMessage .panel").addClass('error');
    }else{
        $(".message.localMessage .panel").removeClass('error');
        $(".message.localMessage .panel").addClass('confirm');
    }
    showComplete(msg);   
    return is_valid;
}

function htmlDecode(str){
    return $("<div />").html(str).text();
}

function messageDlg(msg, title, icon){
    // use inline
    if (typeof title == "undefined") {
        title = 'warning';
    }
    if (typeof icon == "undefined") {
        $('#icon_warning').show();
        $('#icon_success').hide();
    }else{
        $('#icon_warning').hide();
        $('#icon_success').show();
    }
    
    $('#messageDlg').dialog('option', 'title', title);
    $('#messageDlg').dialog('option', 'width', 400);
    $('#messageDlg').dialog('option', 'height', 'auto');
    $('#messageDlg').dialog('option', 'buttons',
        {"close": function() {$('#messageDlg').dialog("close");}}
    );
    $("#contain_content").html(msg);
    $('#messageDlg').dialog('open');    
}

function messageInValid(el, msg){
    $(el).validationEngine('showPrompt', msg, 'red', 'topRight', true);
}

function popIsError(msg){
    var startMarker = "<!--error-msg-start-->";
    var pos = msg.indexOf(startMarker);
    if(pos > -1){
       var error_msg = extractPart(msg, 'error', 'msg');
       showMessage(error_msg);
       return true;
    }else return false;
}

function confirmDlg(msg, title, task)
{
    $('#messageDlg').dialog('option', 'title', title);
    $('#messageDlg').dialog('option', 'width', 400);
    $('#messageDlg').dialog('option', 'height', 'auto');
    $('#messageDlg').dialog('option', 'resizable', false);

    if(language=='th'){
        $('#messageDlg').dialog('option', 'buttons',
            {"ตกลง": function() {executeFN(task);$('#messageDlg').dialog("close");},
              "ยกเลิก": function() {$('#messageDlg').dialog("close");} 
          }
        );

    }else{
        $('#messageDlg').dialog('option', 'buttons',
            {"OK": function(){executeFN(task);$('#messageDlg').dialog("close");},
                "Cancel": function() {$('#messageDlg').dialog("close");}
            }
        );
        //$.globalEval("executeFN(task);");
    }
    $("#contain_content").html(msg);
    $('#messageDlg').dialog('open');
}

function previewBanner(bannerid, bannername, width, height){
            var h = parseInt(height) + 64;
            var w = parseInt(width) + 64;
            var url = "ajax/get-preview-banner.php?bannerid="+bannerid;
            openWindow(url, '', 'status=no,scrollbars=no,resizable=no,width='+w+',height='+h);
}

function getURLParameter(name, url) {
    if(url=='')
        url = location.search;
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1]
    );
}

function numberOnly(event){

 if(event.ctrlKey) return true;
 var charCode = (event.which) ? event.which : event.keyCode
 if(charCode ==13)
    {
        event.preventDefault();
    }
 if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode!=46 && charCode!=37 && charCode!=39 )
     {
        return false;
     }
     return true;
 }
 
 function validate_email(email) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   if(reg.test(email) == false) {
      showMessage('Invalid Email Address', true);
      return false;
   }return true;
}

  blockStyleCSS = {message:   '<img style="border:0px none" src="./Includes/assets/images/loading-block.gif" />',
        css:{width:"18px",
         "border-width":"0",
         "border-style":"none",
         "top":"50px",
         "background-color":"transparent"},
        overlayCSS:{opacity:0.1}};
    
      blockStyleCSS2 = {message:   '',
        css:{width:"18px",
         "border-width":"0",
         "border-style":"none",
         "top":"50px",
         "background-color":"transparent"},
        overlayCSS:{opacity:0.1}};
    
function bindImgUrl(aData, img_fields){
    var top = -5;
    $.each(img_fields, function(index, field){
        $("#icons_"+field).remove();
        $("#aOther_"+field).remove();
                $("#i_"+field).hide();
                $("#fl_"+field).hide();
        if($.trim(aData[field].url)!='' && aData[field].w!=0 && aData[field].w!=null){
            
            var zoom_html = '<ul class="iconsimg ui-widget ui-helper-clearfix" id="icons_'+field+'">'+
                '<li title="Zoom Image" class="zoomin-img ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-zoomin"></span></li>'+
                '<li title="Delete Image" class="del-image ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-minus"></span></li></ul>';
            var ext = aData[field].url.split('.').pop().toLowerCase();
            if($.inArray(ext, ['swf']) != -1) {
                $("#i_"+field).before('<div id="fl_'+field+'"></div>');
                var ox_swf = new FlashObject(aData[field].url, field, aData[field].w, aData[field].h, '8');
                ox_swf.addVariable('clickTARGET', '_blank');
                ox_swf.addVariable('clickTAG', "");
                ox_swf.addParam('allowScriptAccess','always');
                ox_swf.addParam('wmode','opaque');
                ox_swf.write('fl_'+ field);
                $("#i_"+field).hide();
                $("#fl_"+field).show();
                $("#fl_"+field).after(zoom_html);;
                top += -25;
            }else{
                $("#i_"+field).attr("src", aData[field].url);
                $("#i_"+field).attr("width", aData[field].w);
                $("#i_"+field).attr("height", aData[field].h);
                $("#i_"+field).show('slow');
                $("#i_"+field).css('position','relative');
                $("#i_"+field).css('top','28px');
                $("#i_"+field).after(zoom_html);
                $("#i_"+field).show();
                $("#fl_"+field).hide();
            }

                $("#icons_"+field+' li').hover(
                    function() {$(this).addClass('ui-state-hover');},
                    function() {$(this).removeClass('ui-state-hover');}
                ).css('position','relative').css('top',top).css('left',aData[field].w);

                $("#icons_"+field+' li.zoomin-img').click(function(){
                    openWindow(aData[field].link_url, '', 'status=no,scrollbars=no,resizable=no,width='+ (parseInt(aData[field].width)+84) +',height='+(parseInt(aData[field].height)+64));
                });
                $("#icons_"+field+' li.del-image').click(function(){
                    delImage(field);
                });
            }else if($.trim(aData[field].url)!='' && aData[field].w==null){
                    $("#f_"+field).before('<a target="_blank" id="aOther_'+field+'" href="'+aData[field].url+'">'+aData[field].url+'</a>');
            }

    });
}

function initToggle(name, title, top){
    $("#rcl_"+name+' li').hover(
                    function() {$(this).addClass('ui-state-hover');},
                    function() {$(this).removeClass('ui-state-hover');}
                );
    $('#rcl_'+name).unbind('click');
    $('#rcl_'+name).css('width',35);
    $('#rcl_'+name).click(function(){        
        toggleRow(name, title, top);
    }).attr('title','manage '+ title);
    //$('.rcl_row_'+name).hide().css('top',0);
}

function toggleRow(name, title, top){   
    //alert($('.rcl_row_'+name).is(":visible") );
   if($('.rcl_row_'+name).is(":visible")){
       //alert('hide')
       $('#rcl_'+name +' li span').addClass('ui-icon-arrowthickstop-1-s');
       $('#rcl_'+name +' li span').removeClass('ui-icon-arrowthickstop-1-n').css('position','relative').css('top',0);
       $('#rcl_'+name).css('position','relative').css('top',0).attr('title','manage '+ title);
       $('.rcl_row_'+name).hide('slow');
   }else{
       $('#rcl_'+name +' li span').addClass('ui-icon-arrowthickstop-1-n');
       $('#rcl_'+name +' li span').removeClass('ui-icon-arrowthickstop-1-s');
       $('#rcl_'+name).css('position','relative').css('top',top).attr('title','hide manage '+ title);
       $('.rcl_row_'+name).show('slow');
        //alert('show')
   }
   //position: relative;top:40px;"

}

function delImage(field_img){
    confirmDlg(msg_delconfirm, msg_delcaption, field_img);
}

function setButtonUI(el){
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

function setButtonExport(el){
    $(el).addClass(
            //'ui-state-default ' +
            'icon-export-default'
            ).hover(
                function() {
                        $(this).css("cursor", "pointer");
                        $(this).css('opacity', 1);
                        $(this).css('border', 'none');
                },
                function() {
                        $(this).css("cursor", "default");
                        $(this).css('opacity', 0.5);
                }
            )
}

function getChk(){
    obj = {};
    $.each(chk_fields, function(index, field){
            eval("obj." + field + "='" + ($("#chk_" + field).attr('checked')?'Y':'N') + "'");
        });
        return obj;
}

function clearChk(){
    $.each(chk_fields, function(index, field){
        $("#chk_"+field).attr('checked', false);
    });
}

function bindChk(aData){
    $.each(chk_fields, function(index, field){
        $("#chk_"+field).attr('checked', (aData[field]=='Y'?true:false));
    });
}

function getTxt(){
    obj = {};
    $.each(txt_fields, function(index, field){
            eval("obj." + field + "='" + $.trim($("#txt_"+field).val()) + "'");
        });
        return obj;
}

function clearTxt(){
    $.each(txt_fields, function(index, field){
        $("#txt_"+field).val('');
    });
}

function bindTxt(aData){
    $.each(txt_fields, function(index, field){
        $("#txt_"+field).val(aData[field]);
    });
}

function checkboxChecked(selector) {
     var allVals = [];
     $(selector).each(function() {
       allVals.push($(this).val());
     });
     return allVals;
  }
  
function CreateComboExt(lst_id, ref_data_type, data_type_id){
        var thisClass = this;    
        this.ref_data_type = ref_data_type;
        this.lstName = lst_id;
        this.data_type_id = data_type_id;
        this.lstExt = $("#"+lst_id).multiselect({ selectedList:1,height:250,multiple: false }).multiselectfilter();

        this.getDataTypeName = function(){
            var url = "processajax.php?q=Define_Data_type&fileclass=page/define-data-type.php";
            $.post(url, { oper: 'getDataTypeName', response_type:'normal', 'ref_data_type': thisClass.ref_data_type, data_type_id:thisClass.data_type_id },
            function(response) {
                if(isExpire(response)) return;
                thisClass.refreshComboExt(response);
            });
        }
        
        this.refreshComboExt = function(response){
            $('#'+thisClass.lstName).find('option').remove().end().append(response);
            thisClass.lstExt.multiselect('refresh');

            $('#div_'+thisClass.lstName).remove();
            $('#'+thisClass.lstName).multiselect("widget").children('.ui-widget-header').after('<div id="div_'+thisClass.lstName+'" class="ui-pg-div" style="margin-left: 100px;width: 135px;margin-top:2px;"><div style="float: right; width: 40px;cursor:pointer"><span class="ui-icon ui-icon ui-icon-plus" style="float: right;"></span>Add</div><input type="text" class="txt_define_datatype" value="" style="border-radius:0; height: 12px;margin-bottom: 5px;width: 80px;"  /></div>');

            $('#div_'+thisClass.lstName).click(function(event){
                if(event.target.nodeName=='DIV' || event.target.nodeName=='SPAN'){
                    var txt_define_datatype = $(this).children('.txt_define_datatype').val();
                    if($.trim(txt_define_datatype)=='')return;

                    var url = "processajax.php?q=Define_Data_type&fileclass=page/define-data-type.php";

                    $.post(url, { 'ref_data_type':thisClass.ref_data_type, oper: 'add', response_type:'normal',
                        data_type_name:txt_define_datatype, 'is_active':'Y', 'is_delete':'N' },
                    function(response) {
                        if(isExpire(response)) return;
                        thisClass.getDataTypeName();
                    });
                }
            })
        }
        
        thisClass.getDataTypeName();
}
  
function GridBase(aInfo){
        var thisClass = this;
        this.hdnID = '';
        this.breadCrumbLabel = $('.noBreadcrumb .label').text();
        this.timeoutHnd;
        this.flAuto = true;
        this.ownerDocType;
        //operation linked outside
        this.refOper = 'default';
        
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
                $('#divAction'+aInfo.classname + ' [id^=oaSearch]').css('right','0px');
            }else{
                $('#divAction'+aInfo.classname + ' [id^=oaSearch]').css('right','110px');
            }
        }
        
        this.openPnlGrid = function (pnlGrdID){
            $('#'+pnlGrdID).show();
            $('#pnlAddEdit').hide();
            jQuery(window).resize();   
            
//            var pk_id =  this.hdnID;
//            var url = $(aInfo.grd).getGridParam('url');
//            $.post(url, { pk_id: pk_id, oper:'setCurrentPK'} );
        }
        
        this.closePnlGrid = function (pnlGrdID){
            $('#'+pnlGrdID).hide();
            $('#pnlAddEdit').show();
            jQuery(window).resize();
            clearMessage();
            
//            $('.pnlContainer').css('padding-right', '0px');
//            var pk_id =  this.hdnID;
//            var url = $(aInfo.grd).getGridParam('url');
//            $.post(url, { pk_id: pk_id, oper:'setCurrentPK'} );
        }
        
        this.slideToPanel = function (pnlID){
            var left = (-100 * $('#'+pnlID).position().left / $('.slider-viewport').width()) + '%';
            $('.slider-carriage').css('left', left);
//                $('.slider-carriage').stop(false, false).animate({
//                    left: (-100 * $('#'+pnlID).position().left / $('.slider-viewport').width()) + '%'
//                }, 1000);
//                $('.noBreadcrumb .label').text(this.breadCrumbLabel);
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
        
        this.clearInputFile = function(){
            $.each(aInfo.img_fields,function(index, field){
                $('#div_'+field).html($('#div_'+field).html());
                $("#i_"+field).hide();
                $("#icons_"+field).remove();
            })
        }
        
        this.delImage = function(field_img){
            thisClass.confirmDlg(msg_delconfirm, msg_delcaption, field_img);
        }
        
        this.bindImgUrl = function (aData){
            var top = -5;
            $.each(aInfo.img_fields, function(index, field){
                $("#icons_"+field).remove();
                $("#aOther_"+field).remove();
                        $("#i_"+field).hide();
                        $("#fl_"+field).hide();
                if($.trim(aData[field].url)!='' && aData[field].w!=0 && aData[field].w!=null){

                    var zoom_html = '<ul class="iconsimg ui-widget ui-helper-clearfix" id="icons_'+field+'">'+
                        '<li title="Zoom Image" class="zoomin-img ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-zoomin"></span></li>'+
                        '<li title="Delete Image" class="del-image ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-minus"></span></li></ul>';
                   
                   var ext = aData[field].url.split('.').pop().toLowerCase();
                    if($.inArray(ext, ['swf']) != -1) {
                        $("#i_"+field).before('<div id="fl_'+field+'"></div>');
                        var ox_swf = new FlashObject(aData[field].url, field, aData[field].w, aData[field].h, '8');
                        ox_swf.addVariable('clickTARGET', '_blank');
                        ox_swf.addVariable('clickTAG', "");
                        ox_swf.addParam('allowScriptAccess','always');
                        ox_swf.addParam('wmode','opaque');
                        ox_swf.write('fl_'+ field);
                        $("#i_"+field).hide();
                        $("#fl_"+field).show();
                        $("#fl_"+field).after(zoom_html);;
                        top += -25;
                    }else{
                        $("#i_"+field).attr("src", aData[field].url);
                        $("#i_"+field).attr("width", aData[field].w);
                        $("#i_"+field).attr("height", aData[field].h);
                        $("#i_"+field).show('slow');
                        $("#i_"+field).css('position','relative');
                        $("#i_"+field).css('top','28px');
                        $("#i_"+field).after(zoom_html);
                        $("#i_"+field).show();
                        $("#fl_"+field).hide();
                    }

                        $("#icons_"+field+' li').hover(
                            function() {$(this).addClass('ui-state-hover');},
                            function() {$(this).removeClass('ui-state-hover');}
                        ).css('position','relative').css('top',top).css('left',aData[field].w);

                        $("#icons_"+field+' li.zoomin-img').click(function(){
                            openWindow(aData[field].link_url, '', 'status=no,scrollbars=no,resizable=no,width='+ (parseInt(aData[field].width)+84) +',height='+(parseInt(aData[field].height)+64));
                        });
                        
                        $("#icons_"+field+' li.del-image').click(function(){
                            thisClass.delImage(field);
                        });
                        
                    }else if($.trim(aData[field].url)!='' && aData[field].w==null){
                            var del_html = '<ul class="iconsimgdel ui-widget ui-helper-clearfix" id="icons_'+field+'">'+
                           '<li title="Delete Image" class="del-image ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-minus"></span></li></ul>';

                            $("#f_"+field).before('<a target="_blank" id="aOther_'+field+'" href="'+aData[field].url+'">'+aData[field].url+'</a>');
                            
                            $("#aOther_"+field).before(del_html);
                                $("#icons_"+field+' li.del-image').click(function(){
                                thisClass.delImage(field);
                            });
                }

            });
        }
        
        this.checkExtUpload = function(){
            var result = true;
            $.each(aInfo.img_fields, function(index, fieldname){
                var ext = $('#f_' + fieldname).val().split('.').pop().toLowerCase();
                           
                var ext_allow = $('#f_'+fieldname).attr('alt');
                var a_ext_allow = ext_allow.split(",");
                if(a_ext_allow.length>0)
                   a_ext_allow.push(''); //arrow empty input
                
                if($.inArray(ext, a_ext_allow) == -1) {      
                    $('#f_'+fieldname).validationEngine('showPrompt', 'ต้องเป็นไฟล์ที่มีนามสกุลดังนี้' + "  " + ext_allow, 'error', true);
                    result = false;
                }                
            })
            return result;
        }
        
        /*
        * start upload file
        */

        this.ajaxFileUpload = function(fieldname, oper, optionData)
        {
                var url = $(aInfo.grd).getGridParam('url');
                var ext = $('#f_'+fieldname).val().split('.').pop().toLowerCase();
                if($.trim(ext)=='') {
                   return false;
                }    
                
                var oData = { 
                    oper: oper, 
                    pk_id: thisClass.hdnID, 
                    _file: 'f_'+fieldname, 
                    fieldname:fieldname
                };
                
                if(optionData!=undefined)
                    $.extend(oData, optionData);

                $.ajaxFileUpload
                (
                        {
                                url: url,
                                secureuri:false,
                                fileElementId:'f_'+fieldname,
                                dataType: 'json',
                                data: oData,
                                success: function (data, status)
                                { 
                                        if(isExpire(data.error)) return;
                                        
                                        if(typeof(data.error) != 'undefined')
                                        {
                                                if(data.error != ''){
                                                    showMessage(data.error);
                                                }else{
                                                    
                                                    thisClass.bindImgUrl(data);
                                                }
                                        }
                                },
                                error: function(response) {
                                    thisClass.hdnID = '';                                      
                                    if(isExpire(response.responseText)) return;
                                }
                        }
                )
                return false;
        }

        this.ajaxFileUploadDoc = function(fieldname, oper, obj, optionData)
        {
                var url = $(aInfo.grd).getGridParam('url');
                var ext = $('#f_'+fieldname).val().split('.').pop().toLowerCase();
                                    
//                if($.trim(ext)=='') {
//                   return false;
//                }    
                
                var oData = { 
                    oper: oper, 
                    pk_id: thisClass.hdnID, 
                    _file: 'f_'+fieldname, 
                    fieldname: fieldname,
                    
                    owner_doc_type: thisClass.ownerDocType
                };
                if(optionData!=undefined)
                    $.extend(oData, optionData);

                $.ajaxFileUpload
                (
                        {
                                url: url,
                                secureuri:false,
                                fileElementId:'f_'+fieldname,
                                dataType: 'json',
                                data: oData,
                                async: false,
                                success: function (data, status)
                                {
                                        if(isExpire(data.error)) return;
                                        
                                        if(typeof(data.error) != 'undefined')
                                        {
                                                if(data.error != ''){
                                                    showMessage(data.error);
                                                }else{                                                    
                                                    //thisClass.bindImgUrl(data);
                                                    
                                                    obj[fieldname].complete = true;
                                                    
                                                    var is_complete = true;
                                                    for (var base_element_name in obj) {
                                                            if(obj[base_element_name].complete===false){
                                                                is_complete = false;
                                                            }
                                                    }
                                                    if(is_complete===true){
                                                        thisClass.getDocument(thisClass.ownerDocType);
                                                    }
                                                }
                                        }
                                },
                                error: function(response) {
                                    //thisClass.hdnID = '';                                      
                                    if(isExpire(response.responseText)) return;
                                }
                        }
                )
                    
                return false;
        }
        
        this.uploadDoc = function(aDocName){
            for (var base_element_name in aDocName) {
                thisClass.ajaxFileUploadDoc(base_element_name, 'saveDoc', aDocName, { 'doctype':$('#doctype_'+base_element_name).val() })
            }
        }
        
        this.getDocument = function(){
            if(!$.isEmptyObject( aInfo.Document )){
                thisClass.ownerDocType = aInfo.Document.owner_doc_type;            
                var url = "ajax/get-document-list.php";
                $.post(url, { column_name: 'data_type_name', direction: 'asc', oper: 'search', 
                    owner_doc_type: aInfo.Document.owner_doc_type, 
                    ref_data_type: aInfo.Document.ref_data_type, 
                    ref_pk_id: thisClass.hdnID },
                    function(response) {
                        $(".tableWrapper").html(response);
                        $("#dbLoader").hide();
                     }
                );
            }
        }

        this.uploadImage = function(){
            $.each(aInfo.img_fields, function(index, value){
                thisClass.ajaxFileUpload(value, 'saveImage')
            })
        }
        
        this.gridReload = function(){
            this.dataSearch();
            $(aInfo.grd).setGridParam({ page:1 });
            $(aInfo.grd).setGridParam({ datatype:'json'});
            $(aInfo.grd).trigger("reloadGrid");
        }
        
        this.gridRefresh = function(){
            $(aInfo.grd).trigger("reloadGrid");
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

        this.editStatus = function(fieldname, pk_id){
            if(fieldname=='is_delete') {
                    var ids = [pk_id];
                    this.confirmDlg(msg_delconfirm, msg_delcaption, ids);
                    return;
            }
//            $(aInfo.grd).setPostDataItem( "oper", 'editStatus');
//            $(aInfo.grd).setPostDataItem( "fieldname", fieldname);
//            $(aInfo.grd).setPostDataItem( "pk_id", pk_id);
            $(aInfo.grd).jqGrid('setGridParam',{postData:{'oper':'editStatus', "fieldname":fieldname, 'pk_id':pk_id} })
            $(aInfo.grd).trigger("reloadGrid");
        }
        
        this.clearBlock = function(oper, error){
            if(error=="undefined")error='';
            if(oper=='edit')
                showMessage(msg_update_complete+error);
            else showMessage(msg_insert_complete+error);
            
            $("#pnlAddEdit").unblock();
        }
        
        this.executeFN = function (data){
            if($.isArray(data)) //delete row
            {
                $(aInfo.grd).jqGrid('setGridParam',{postData:{'oper':'del', 'ids':data} })
                $(aInfo.grd).trigger("reloadGrid");
            }else{ //delete image
                var url = $(aInfo.grd).getGridParam('url');
                var pk_id =  this.hdnID;
                $("#pnlAddEdit").block(blockStyleCSS);
                $.ajax({
                                type: "POST",
                                url: url,
                                dataType: "json",
                                data:{ pk_id:pk_id, field_img: data, oper:'delImage' },
                                success: function(response) {
                                    $("#icons_"+data).remove();
                                    $("#i_"+data).hide('slow');
                                    $('#div_'+data).delay(10000).html($('#div_'+data).html());
                                    $("#aOther_"+data).hide('slow');
                                    $('#pnlAddEdit').unblock();
                                },
                                error: function(response) {
                                    this.hdnID = '';
                                    $('#pnlAddEdit').unblock();
                                    $('#pnlAddEdit').pnlAddEdit("close");
                                    if(isExpire(response.responseText)) return;
                                }
                        });
            }
        }
        
        this.confirmDlg = function(msg, title, task)
        {
            $('#messageDlg').dialog('option', 'title', title);
            $('#messageDlg').dialog('option', 'width', 400);
            $('#messageDlg').dialog('option', 'height', 'auto');
            $('#messageDlg').dialog('option', 'resizable', false);

            if(language=='th'){
                $('#messageDlg').dialog('option', 'buttons',
                    { "ตกลง": function() { thisClass.executeFN(task); $('#messageDlg').dialog("close"); },
                    "ยกเลิก": function() { $('#messageDlg').dialog("close"); } 
                }
                );

            }else{
                $('#messageDlg').dialog('option', 'buttons',
                    { "OK": function(){ thisClass.executeFN(task);$('#messageDlg').dialog("close"); },
                        "Cancel": function() { $('#messageDlg').dialog("close"); }
                    }
                );
            }
            $("#contain_content").html(msg);
            $('#messageDlg').dialog('open');
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
        
        this.setGrdParam = function(){
            $params = 'processajax.php?q=' + aInfo.classname +'&nd='+new Date().getTime()+'&fileclass='+ aInfo.fileclass;
            if(!$.isEmptyObject(this.optSearch)){
                for(key in this.optSearch){
                    $params += '&' + key +'=' + this.optSearch[key]
                }
            }
            return $params;
        }
        
        this.setGrdAutoWidth = function(){
            jQuery(window).bind('resize', function() {
                if (grid = $('.ui-jqgrid-btable:visible')) {
                    grid.each(function(index) {
                        gridId = $(this).attr('id');
                        if( gridId!=aInfo.grd.replace(/#/g,"") ){
                            
                            gridParentWidth = $('#gbox_'+aInfo.grd.replace(/#/g,"")).width();
                            $('#' + gridId).setGridWidth(gridParentWidth-18);
                        }
                    });
                }
            }).trigger('resize');
        } 
        
        this.doSearch = function(ev){
                if(!thisClass.flAuto)
                        return;
        //	var elem = ev.target||ev.srcElement;
                if(thisClass.timeoutHnd)
                        clearTimeout(thisClass.timeoutHnd);
                thisClass.timeoutHnd = setTimeout(function(){thisClass.gridReload();},500);
        }      
        this.countObj = function (obj){
            var count = 0;
            for (i in obj) {
                if (obj.hasOwnProperty(i)) {
                    count++;
                }
            }
            return count;
       }
    }
  
  (function($) {
    $.fn.hasScrollBar = function() {
        return this.get(0).scrollHeight > this.height();
    }
    })(jQuery);
    


    jQuery(document).ready(function(){
            $(":button , :submit").addClass(
            'ui-state-default ' +
            'ui-corner-all'
            )
            .hover(
                function() {
                        $(this).addClass('ui-state-hover');
                        $(this).css("cursor", "pointer");
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
            
             $(".back")
            .hover(
                function() {
                        $(this).addClass('ui-state-hover');
                        $(this).css("cursor", "pointer");
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
            $("#messageDlg").dialog({
			bgiframe: true,
			resizable: true,
			modal: true,
                        autoOpen: false,
			overlay: {
				backgroundColor: '#000',
				opacity: 0.5
			}
		});
           $('.textarea').attr('maxlength', 2048);
           
           $(':text').attr('maxLength','1024').keypress(limitMe);

            function limitMe(e) {
                if (e.keyCode == 8) { return true; }
                return this.value.length < $(this).attr("maxLength");
            }

    });
    
    
   $.fn.digits = function(){ 
        return this.each(function(){ 
            $(this).val( $(this).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
        })
    }