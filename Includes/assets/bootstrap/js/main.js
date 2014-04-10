/*global positions:true*/
$(document).ready(function() {

    // Render table
    var grid = $('#positions-table').jqGrid({
        'hoverrows':true,
        'viewrecords':true,
        'gridview':false,
        'loadonce':true,
        'scroll':1,
        'rowNum': 10, /* this is a hack to workaround jqGrid bug */
        'height':300,
        'colNames':['Security','Symbol', 'Quantity', 'Last Trade','Market Value','Price Paid'],
        'colModel':[
            {name:'security',index:'security', width:127},
            {name:'symbol',index:'symbol', width:52,classes:'notimp'},
            {name:'quantity',index:'quantity', width:61,formatter:'integer',classes:'notimp number'},
            {name:'lastTrade',index:'lastTrade', width:61, align:'right',formatter:'currency',formatoptions:{prefix: '$'},classes:'notimp currency'},
            {name:'marketValue',index:'marketValue', width:71, align:'right',formatter:'currency',formatoptions:{prefix: '$'},classes:'currency'},
            {name:'pricePaid',index:'pricePaid', width:131,align:'right',formatter:'currency',formatoptions:{prefix: '$'},classes:'notimp currency'}
        ],
        url:'ajax/server.php',
        datatype: "json",
        onSelectRow: function(id){
            $('#selected-position').html($('tr#'+id+' td:first-child').html());
        },
        afterInsertRow:function(rowid,rowdata) {
            if(rowdata.gain < 0) {
                $('#'+rowid+' td:nth-child(8)').addClass('negative');
            }
            if(rowdata.gainPercent < 0) {
                $('#'+rowid+' td:nth-child(9)').addClass('negative');
            }
        }
    });

    // Display window size on resize events
    function displayWindowSize() {
        var win = $(window);
        $('.window-size').html('(' + win.width() + ', ' + win.height() + ')');
    }
    $(window).resize(displayWindowSize);

    // Show selection on click events
    // Fit table on resize events
    var headerHeight = $('#positions-header').outerHeight(true);
    var postionsSectionPadding = 30;
    var selectionInfoHeight = $('#selected-position').outerHeight(true);
    var layoutInfoHeight = $('.layout-info').outerHeight(true);
    var fudgeFactor = 25; // don't know why this is needed!
    var fixedSectionsHeight =
        headerHeight + postionsSectionPadding + selectionInfoHeight + layoutInfoHeight + fudgeFactor;

    function fitTable(){
        var win = $("#positions");
        console.log($("#positions").width());
        if(win.width() <= 500) {
            grid.hideCol(['totalCost','gain','security','pricePaid','gainPercent']);
        }
        else if (win.width() <= 900) {
            grid.showCol(['security','pricePaid','gainPercent']);
            grid.hideCol(['totalCost','gain']);
        }
        else {
            grid.showCol(['totalCost','gain','security','pricePaid','gainPercent']);
        }
        grid.setGridWidth(win.width());
        grid.setGridHeight(win.height() - fixedSectionsHeight);
    }
    $(window).resize(fitTable);

    // Perform initial setup
    displayWindowSize();
    fitTable();
});
