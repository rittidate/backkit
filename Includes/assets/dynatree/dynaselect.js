/**
 * @author Rittidate
 */
/* =========================================================
 * bootstrap-slider.js v2.0.0
 * http://www.eyecon.ro/bootstrap-slider
 * =========================================================
 * Copyright 2012 Stefan Petre
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */
 !function( $ ) {

	var Dynaselect = function(element, options) {
		var objContentCat = this;
		this.isDown = false;
        this.isClick = false;
        this.isClose = false;
        this.obj = [];
        this.objSelect;
    	this.valSelect;
        
        this.options = options;
		this.divElement = $(element);
		
		this.documentary = $(document);
		
		this.dataTree = options.children;
		
		this.element = $('<button id="btn_'+$(element).attr('id')+'" type="button" aria-haspopup="true" tabindex="0">'+
          '<span class="ui-icon ui-icon-triangle-2-n-s"></span>' +
          '<spann class="msgContentCategory">Any</span>' +
          '</button>');
        this.divElement.append(this.element);
        
		this.area = $('<div id="dsa_'+$(element).attr('id')+'" class="dynaSelectArea"></div>');
                $("body").append(this.area);
                this.area.css({"width": "300"});
		this.area.addClass("ui-multiselect-menu ui-widget ui-widget-content ui-corner-all");
		
		this.treeArea = $('<ul class="ui-multiselect-checkboxes ui-helper-reset" style="height: '+options.height+'px;">'+
                    	'<div class="treedynaselect"></div></ul>');
		
		if(options.filter){
			this.elementFilter = $('<div class="ui-widget-header ui-corner-all ui-multiselect-header ui-helper-clearfix ui-multiselect-hasfilter">'+
									'<div class="ui-multiselect-filter">Filter:<input class="dynKeywords" placeholder="Enter keywords"></div>'+
									'<ul class="ui-helper-reset">' +
									'<li><a class="ui-multiselect-all dynCheckAll" href="#">' +
									'<span class="ui-icon ui-icon-check"></span>' +
									'<span>All</span></a></li>' +
									'<li><a class="ui-multiselect-none dynUncheckAll" href="#">'+
									'<span class="ui-icon ui-icon-closethick"></span>'+
									'<span>None</span></a></li>'+
									'<li class="ui-multiselect-close">'+
									'<a class="ui-multiselect-close" href="#">'+
									'<span class="ui-icon ui-icon-circle-close dynClose"></span></a></li></ul>'+
									'</div>');
			this.area.append(this.elementFilter);
			
			this.elementFilter.find(".dynClose").on({
				click: $.proxy(this.filterClose, this)
			});
			
			this.elementFilter.find(".dynKeywords").change(function(){
            		objContentCat.searchDynaSelect();
            });

                if(!options.checkAll){
                    this.elementFilter.find(".dynCheckAll").hide();
                    this.elementFilter.find(".dynUncheckAll").hide();
                }
            this.elementFilter.find(".dynCheckAll").on({
				click: $.proxy(this.filterCheckAll, this)
			});
			
            this.elementFilter.find(".dynUncheckAll").on({
				click: $.proxy(this.filterUnCheckAll, this)
			});
                
		}

		
		this.area.append(this.treeArea);
		
        this.treeArea.find(".treedynaselect").dynatree({
            checkbox: options.checkbox,
            selectMode: options.selectMode,
            children: options.children,
            autoCollapse: options.autoCollapse,
            onDblClick: function(node, event) {
                node.toggleExpand();
            },
            onSelect: function(select, node) {

                // Display list of selected nodes
                objContentCat.objSelect = [];
                objContentCat.valSelect = [];
                var selNodes = node.tree.getSelectedNodes();
                
                var selKeys = $.map(selNodes, function(node){
                    objContentCat.valSelect.push(node.data.key);
               });
               options.getSelectedNodes = [];
               options.getSelectedNodes = objContentCat.valSelect;

               // Get a list of all selected TOP nodes
                var selRootNodes = node.tree.getSelectedNodes(true);
                // ... and convert to a key array:
                var selRootKeys = $.map(selRootNodes, function(node){
                  return node.data.key;
                });

                objContentCat.msgSelectRoot = objContentCat.msgSelectRootNode(selRootNodes);
                if (selNodes.length > 0)
                        objContentCat.objSelect.push(selNodes);

                objContentCat.msgSelectTree(objContentCat.options.maxSelect);
            },
            onKeydown: function(node, event) {
                if (event.which == 32) {
                node.toggleSelect();
                        return false;
                }
            }
        });
        
        this.treeArea.find('.dynatree-container').css({ 'border' : 'none' });
        
        this.element.addClass("ui-widget ui-multiselect ui-state-default ui-corner-all");

        this.element.css("width", options.width);


        
        this.msgSelectTree();
        
		this.element.on({
			click: $.proxy(this.click, this),
			mousedown: $.proxy(this.mousedown, this)
		});

		this.documentary.on({
			click: $.proxy(this.mousedownDocumentary, this)
		});
		
		this.area.on({
			mousedown: $.proxy(this.mousedownPicker, this)
		});
		
		
		this.iniDynatree = function(data){
        	this.treeArea.find(".treedynaselect").dynatree({
	            children: data
	     
	        });
	        
	        this.treeArea.find(".treedynaselect").dynatree("getTree").reload();
	        
	        this.treeArea.find('.dynatree-container').css({ 'border' : 'none' });
        	
        };
        
        this.msgSelectRootNode = function(selNodes){
                var msgContentCat = '';
                    $.each(selNodes, function(i, v){
                    if (v.data.title != undefined){
                    if (i == 0){
                    msgContentCat += v.data.title.split("_").pop().split(">").pop();
                            //console.log(msgContentCat);
                    } else{
                        msgContentCat += ", " + v.data.title.split("_").pop().split(">").pop();
                    }
                    if (i > 5){
                        msgContentCat = (i + 1) + " Selected";
                    }
                    }
                    
                    });
                    if (msgContentCat == ''){
                        msgContentCat = 'ALL';
                    }
                    //console.log(msgContentCat)
                    return msgContentCat;
        };
		
		this.searchJSON = function(jsonData, keyword){
                $.each(jsonData, function(i, v) {
                if (v.title.search(new RegExp(keyword, 'i')) != - 1) {
                    var tree = objContentCat.treeArea.find(".treedynaselect").dynatree("getTree");
                            var node = tree.getNodeByKey(v.key);
                            objContentCat.obj.push(node);
                    }
                    if(v.children !== undefined)
                    	objContentCat.searchJSON(v.children, keyword);
                });
       };
       
       this.searchTitle = function(v){
            if (v !== null){
                if (v.data.key != 'root'){
                    if (v.parent !== null){
                        if (v.parent.data.key == 'root'){
                            return v.data.title + objContentCat.searchTitle(v.parent);
                        } else
                        if (objContentCat.searchTitle(v.parent) !== ''){
                            return objContentCat.searchTitle(v.parent) + '>' + v.data.title;
                        } else return v.data.title;
                    } else return '';
                } else return '';
            }
        };
		
        this.clearJson = function(jsonData){
            if (jsonData != undefined && jsonData.length > 0){
                $.each(jsonData, function(i, v){
                    if (v.children != undefined && v.children.length > 0){
                        v.children = objContentCat.clearJson(v.children);
                    } else{
                        if (v != undefined && v.select){
                            v.select = false;
                        }
                    }
                });
            }
            return jsonData;
        };
        
        
        
        
        this.searchDynaSelect = function(){
        	var keyword = this.elementFilter.find(".dynKeywords").val();
            this.clearJson(this.dataTree);
            
           objContentCat.iniDynatree(this.dataTree);
            //this.iniDynatree(idTree, objContentCat.jsonData, true);
            var objTree = this.treeArea.find(".treedynaselect").dynatree("getTree");
            
            this.treeArea.find(".treedynaselect").dynatree("getRoot").sortChildren(null, true);
            
            if (objContentCat.objSelect !== undefined && objContentCat.objSelect.length > 0){
                $.each(objContentCat.objSelect[0], function(i, node){
                    objTree.selectKey(node.data.key);
                });
            }
            
            if (keyword.length > 0){
                var aTree = [];
                var sumTree = [];
                objContentCat.obj = [];
                var nodes = this.treeArea.find(".treedynaselect").dynatree("getSelectedNodes");
                if (nodes.length > 0){
                    $.each(nodes, function(i, node){
                    objContentCat.obj.push(node);
                    });
                }
                
                objContentCat.searchJSON(this.dataTree, keyword);
                $.each(objContentCat.obj, function(i, v){
                    var title = '';
                    title = objContentCat.searchTitle(v);
                    if (v !== null){
                    if (objContentCat.objSelect !== undefined){
                    if (objContentCat.objSelect.length > 0 && i < objContentCat.objSelect[0].length){
                    aTree.push({ title: title, key: v.data.key, icon: false, select: true });
                    } else{
                    aTree.push({ title: title, key: v.data.key, icon: false });
                    }
                    } else{
                    aTree.push({ title: title, key: v.data.key, icon: false });
                    }
                    }
                });
                
                if(objContentCat.objSelect !== undefined){
	                if (objContentCat.objSelect.length > 0){
	            		for (i = objContentCat.objSelect[0].length; i < aTree.length; i++){
	            			$.each(objContentCat.objSelect[0], function(int, val){
	            				if (aTree[i] != undefined){
	            					if (val.data.key == aTree[i].key){
	            						delete aTree[i];
	            					}
	            				}
	        	            });
	            		}
	           		}
           		}

				$.each(aTree, function(i, v){
	                if (v != undefined){
	                    sumTree.push(v);
	                }
	            });
	            
	            objContentCat.iniDynatree(sumTree);
            }
	            
        };
        
        
		
	};
	Dynaselect.prototype = {
		constructor: Dynaselect,
		click: function(ev){
            if (!this.isClick){
                this.element.toggleClass('ui-state-active');
                this.area.css({ 'top': this.element.offset().top + this.element.height() + 6, 'left': this.element.offset().left });
                this.area.toggle();
                //$("#{$variable_el}blockContent").toggle();
	            if (!this.element.hasClass('ui-state-active')){
					this.options.onHide();
					
					this.msgSelectTree(this.options.maxSelect);
	            }
            }
		},
		mousedown: function(ev){
            if (this.element.hasClass('ui-state-active')){
                this.isDown = false;
            } else{
                this.isDown = true;
            }
		},
		mousedownDocumentary : function(ev){
        	if (!this.isDown){
                if (this.element.hasClass("ui-state-active")){
                    this.element.removeClass("ui-state-active");
                    this.area.hide();
                    //this.isClick = false;
                    //thisClass.dynatreeValue = dyn.valSelect;
                    //thisClass.dynatreeSelectRoot = dyn.msgSelectRoot;
                    //thisClass.gridReload();
                    //thisClass.pageSelect = 1;
                    //thisClass.getProduct();
                    this.options.onHide();
                    this.msgSelectTree(this.options.maxSelect);
                }

            } else{
                    this.isClick = false;
                    this.isDown = false;
            }
		},
		mousedownPicker: function(ev){
            this.isDown = true;
            if (this.isClose){
                this.isDown = false;
                this.isClose = false;
            }
		},
		filterClose: function(){
			this.element.removeClass('ui-state-active');
            this.area.hide();                        
            isClose = true;
            this.options.onHide();
            this.msgSelectTree(this.options.maxSelect);
            return false;
		},
		filterCheckAll: function(){
	        this.treeArea.find(".treedynaselect").dynatree("getRoot").visit(function(node){
	          node.select(true);
	        });
	        //return false;
		},
		filterUnCheckAll: function(){
	        this.treeArea.find(".treedynaselect").dynatree("getRoot").visit(function(node){
              node.select(false);
            });
            //return false;
		},
		msgSelectTree : function(maxSelectNode){
			var selNodes = this.treeArea.find(".treedynaselect").dynatree("getSelectedNodes");
            var msgContentCat = '';
            if(selNodes !== undefined){
                    $.each(selNodes, function(i, v){
                    if (v.data.title != undefined){
                    if (i == 0){
                    msgContentCat += v.data.title.split("_").pop().split(">").pop();
                    } else{
                    msgContentCat += ", " + v.data.title.split("_").pop().split(">").pop();
                    }
                    }

                    if (i > maxSelectNode - 1){
                    	msgContentCat = (i + 1) + " Selected";
                    }
   
                    });
            }
            if (msgContentCat == ''){
        		msgContentCat = this.options.message;
            }
            this.element.find(".msgContentCategory").text(msgContentCat);
        },

	};
	
	$.fn.dynaselect = function ( option, val ) {
                return this.each(function () {
			var $this = $(this),
				data = $this.data('dynaselect'),
				options = typeof option === 'object' && option;
			if (!data)  {
				$this.data('dynaselect', (data = new Dynaselect(this, $.extend({}, $.fn.dynaselect.defaults,options))));
			}
			if (typeof option == 'string') {
				data[option](val);
			}
		});
	};


	$.fn.dynaselect.defaults = {
		min: 0,
		max: 10,
		step: 1,
                selectMode: 3,
                autoCollapse: false,
		filter: false,
                checkAll: true,
		handle: 'round',
		width: '300',
		height: '280',
		maxSelect: 3,
		message: "Plase Select",
                onHide: function(){}
	};
	
	$.fn.dynaselect.Constructor = Dynaselect;

}( window.jQuery );