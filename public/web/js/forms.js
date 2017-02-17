/*
 *表单相关处理方法
 */

/**
 *rgb转16进制颜色
 */

;
function RGBToHex(rgb) {
        var regexp = /[0-9]{0,3}/g;
        var re = rgb.match(regexp);//利用正则表达式去掉多余的部分，将rgb中的数字提取
        var hexColor = "#";
        var hex = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];
        for (var i = 0; i < re.length; i++) {
                var r = null, c = re[i], l = c;
                var hexAr = [];
                while (c > 16) {
                        r = c % 16;
                        c = (c / 16) >> 0;
                        hexAr.push(hex[r]);
                }
                hexAr.push(hex[c]);
                if (l < 16 && l != "") {
                        hexAr.push(0)
                }
                hexColor += hexAr.reverse().join('');
        }
        //alert(hexColor)  
        return hexColor;
}
;

/**
 *获取字符串长度
 */
 
;function getLength(s) { 
	  var l = 0; 
	  var a = s.split(""); 
	  for (var i=0;i<a.length;i++) { 
	  if (a[i].charCodeAt(0)<299) { 
	  l++; 
	  } else { 
	  l+=2; 
	  } 
	  } 
	  return l; 
};

/*
 *验证价格
 */
 
;function checkPrice(el){
	var value=el.val(),
		newValue='';
	for(i=0;i<value.length;i++){
		if((value.charAt(i)=='.'&&i>0)||!isNaN(value.charAt(i))){
			newValue+=value.charAt(i);										
		}
	};
	el.val(newValue);
};

/**
 *验证本站页面和站外页面链接
 */

;function checkURL(value,title,noEmpty){
	var msg='';
	if(!value&&noEmpty){
		msg='链接不能为空';
	}else if(value){
		if(value.indexOf('/')!=0&&value.indexOf('://')<0){
			  msg='站外链接需加://开头，站内链接需以/开头';
		  }else if(value.indexOf('://')>=0&&value.indexOf('.')<0){
			  msg='请输入正确的站外链接地址';
		  }else if(value.indexOf('/')==0&&value!='/'&&value.indexOf('.html')<0){
			  msg='请输入正确的站内链接地址';
		  };
	};
	if(msg&&title){
		msg=title+msg;
	};
   return msg;
};

/*
 *验证链接
 */

;function isURL(value) {
	var strRegex = /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
	var re = new RegExp(strRegex);
	return re.test(value);

};

/*
 *验证email
 */

;function isEmail(value) {
	var strRegex = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
	var re = new RegExp(strRegex);
	return re.test(value);

};

/*
 *验证Tel
 */

;function isPhone(value) {
	var strRegex = /^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/;
	var re = new RegExp(strRegex);
	return re.test(value);

};

/*
 *验证字母和数字
 */

;function isLetterNumber(str){
	 var strRegex = /^[0-9a-zA-Z]+$/;
	var re = new RegExp(strRegex);
	return re.test(str);
};

/**
 *设置表单元素默认值
 */
 
$.fn.placeholder=function(){
		if($.browser&&$.browser.msie&&$.browser.version<10){
			 return this.each(function(){
				var value=this.value;
				var placeholder=$(this).attr('placeholder');
				var input=this;
				if($(this).data('placeholder')){
					return;
				};
				if(value==''&&placeholder){
					$(this).data('placeholder',placeholder);
					this.value=placeholder;
					if($(this).attr('type')=='password'){
	                    $(this).hide();
						var el=$('<input />');
						$(el).attr({'type':'text','value':placeholder,'class':this.className}).insertAfter($(this));
						$(el).focus(function(){
								$(this).hide();	
								if(this.value==placeholder){
							input.value='';
						};
								$(input).show().focus();
						});
						$(this).blur(function(){
							if(this.value==''){
								$(this).hide();
								el.value=placeholder;
								$(el).show();
							};
							
						});	
					}else{
						$(this).focus(function(){
						if(this.value==placeholder){
							this.value='';
						}
					});								
					$(this).blur(function(){
							if(this.value==''){
								this.value=placeholder;
							};
							
						});	
					}
				};
										
			});	
		}
};

/*
 *验证全部表单
 */
;function formVerifications(verifications,values,callback){
	var msg='';
	if(typeof verifications=='function'){
		for(name in values){
			msg=verifications(name,values[name]);
			if(msg)return msg;
		};
		if(!msg){
			fireHandler(callback);
		};
	};
	return msg;
};

/**
 *获取表单值
 flag为true时，则过滤空值
 */
 
;function getFormValue(form,flag){
	var data={},
		vals=$(form).serializeArray();
	$.each(vals,function(i,item){
			var name=item['name'],
				value=enToString(item['value']);
			if(!flag||(flag&&value!='')){
				if(!data[name]){
					data[name]=value;
				}else if(typeof data[name]=='string'){
					data[name]=[data[name]];
					data[name].push(value);
				}else{
					data[name].push(value);
				};
			};
	});
	$('input[role="switch"]',form).each(function(){
		var name=$(this).attr('name'),
		    value=0;
		if($(this).prop('checked')){
			value=$(this).val()||1;
		};
		data[name]=value;
	});
	return data;
	
}; 


/**
 *设置表单项值
 */

;
function setInputValue(name, value, container) {

        container = container || $('body');

        if (!$(':input[name="' + name + '"]', container).length)
                return;
		
		if(typeof value=='object'){
			value=$.toJSON(value);
		};
		
        var label = $(':input[name="' + name + '"]', container)[0].tagName.toLowerCase(),
            type = label == 'input' ? $('input[name="' + name + '"]:first', container).attr('type') : label;
		
		if(('textarea,radio,checkbox,select').indexOf(type)<0){
			 $('input[name="' + name + '"]', container).val(value);
			 return;
		};
		
			
        switch (type) {
                
				case 'text':
                        $('input[name="' + name + '"]', container).val(value);
                        break;

                case 'textarea':
                        $('textarea[name="' + name + '"]', container).val(value);
                        break;

                case 'radio':
                        $('input[name="' + name + '"][value="' + value + '"]', container).prop('checked', true);
                        break;

                case 'checkbox':
						if(typeof value=='number'){
							value=[value];
						};
						if(typeof value=='string'){
							value=value.split(',');
						};
						if(value.length){
							$.each(value, function(i, item) {
									$('input[name="' + name + '"][value="' + item + '"]', container).prop('checked', true);
							});
						};
                        break;

                case 'select':

                        $('select[name="' + name + '"] option[value="' + value + '"]', container).attr('selected', true);
                        break;

        }
        ;


}
;


/**
 *设置表单值
 */

;$.fn.setFormValue=function(values,opts){
	
	return this.each(function(){
		
		var $self=$(this);

		//为表单设置值
		for(name in values){
			var value=values[name],
				input=$(':input[name="'+name+'"]:first',$self);
			if(input.length){
				if(value==null||value==undefined){
					value='';
				};
				setInputValue(name,value,$self);
				if(values[name+'_name']){
					input.data('_namespace',values[name+'_name']);
				}
			};
		}
	});		
	
};


/**
 *设置表单效果
 */

;$.fn.setFormViews=function(opts){
	
	var options=$.extend(true,{
				verifications:'',//验证表单事件
				onCancel:$.noop,//点击取消时的事件
				onConfirm:$.noop,//点击确定时的事件
				requestUrl:'',//保存时的请求地址，onSubmit未重设时有效
				requestAction:'',//保存时的请求地址，onSubmit未重设时有效
				selector:'.formList[role]',
				onSuccessMsg:SiteText.setSuccess,//提交成功后的提示文字
				onSuccess:function(){
					if(appSys!='web'||isPhoneState){
						 if(window.closeCurrentTab){
						 	 closeCurrentTab('back');
						 };
					  }else{
						  Tips('success',options.onSuccessMsg);
					  };
				},//提交成功时的事件
				onSubmit:function(submitValues){
					if(!options.requestUrl)return;
					rpcJSON(options.requestAction,submitValues,function(backData){
						options.onSuccess(backData);
					},function(){
						Tips('error','提交失败');
					},options.requestUrl);
				},
				submitBtn:'a[role="submit"]'//保存表单的按钮
			},opts);
	
	return this.each(function(){
		var $self=$(this);
		
		//为表单设置效果
		$(options.selector,$self).each(function(){
			var $this=$(this),
				input=$(':input:first',$this);
			switch($this.attr('role')){
				
				//图标
				case 'formIcon':
					input.setImgInput();
				break;
				case 'selectIcon':
					input.setIconInput();
				break;
				
				//颜色
				case 'color':
					input.setColorInput();
				break;
				
				//图片列表
				case 'imglist':
					input.setImgListInput();
				break;
				
				case 'expandSelect':
					input.inputExpandSelect();
				break;
				
				case 'datepicker':
				     datepicker(input);
				break;
				
				case 'timepicker':
				     timepicker(input);
				break;
				
				case 'editor':
				     textareaToEditor(input);
				break;
			}
		});
		
		$('textarea',$self).autoHeightTextarea();
		
		
		$self.delegate(options.submitBtn,click,function(e){
			var values=getFormValue($('form',$self));
				
			if($.isFunction(options.verifications)){
				//验证所有表单
				formVerifications(options.verifications,values,function(){
					options.onSubmit(values);
					
				});
			}else{
				options.onSubmit(values);
			}
			e.preventDefault();
		});
		
		//如果是宽屏电脑中，插入提交按钮，回车提交表单
		if(appSys=='web'&&!isPhoneState){
			$('<input type="submit" style="display:none;"/>').appendTo($('form',$self));
			$self.delegate('form','submit',function(e){
				e.preventDefault();
				$self.find(options.submitBtn).trigger(click);
			});
		}
	});		
	
};



/**
 *设置下拉列表值
 */
 
;$.fn.setSelect = function(data,selected){
	if(!data)return;
	return this.each(function(i) { 
		var selectValue=selected?selected:$(this).val()?$(this).val():0,
			selectHtml='';
		$.each(data,function(i,item){
			selectHtml+='<option value="'+item.id+'" ';
			if(item.id==selectValue){
				selectHtml+=' selected';
			};
			selectHtml+='>'+item.name+'</option>';				 
		});
		$(this).html(selectHtml);
		
		if($(this).width()<50){
			$(this).css('width','auto');
		};
	});
};

/**
 *设置选项列表
 *selectbox//是否显示选择框，默认为true，显示
 *selectmode//选择方式，1为全部可选择，2为最后一级可选择,
 *relevance//相关选择性，selectmode为1时有效，1为上级选中时，下级全部选中，下级未全选时，上级不选中。2为上下级可同时选中，
 *count//可选数量，1为单选，0为不限制，其他数量为最多可选几项
 data:[{
	 isFolder:是否还有下级,
	 children:[]下级数据，ajax动态请求时，无须设置
	 unselectable:禁止选择
	 id:数值,
	 name:显示名称,
	 icon：图标
	 }]可选数据，数组
 requestUrl//ajax请求地址
 requestData//ajax请求数据，选择下级时，requestData扩展parentId，请求该parentId的下级
 
 defaultValues:已选id数组
 defaultData:已选数据
 backTitle:'返回上级'显示的文字,
 onReady:$.noop,//第一次数据插入后的事件
 onSelected:$.noop//每次选择或取消选择时的事件，返回selectedData,selectedValues,names。
 joinpathname:符号，将多层级名称根据符号拼接返回。
 inDialog:false,//是否在弹窗里面，在弹窗里面则隐藏返回上级的按钮
 */

;$.fn.setSelectList=function(opts){
	var options=$.extend(true,{
			selectbox:true,//是否显示选择框
			selectmode:1,//选择方式，1为全部可选择，2为最后一级可选择
			relevance:1,//关联选择，
			count:1,
			icon:true,
			data:'',
			requestUrl:'',
			requestAction:'',
			requestData:'',
			defaultValues:'',
			defaultData:'',
			backTitle:'返回',
			joinpathname:'',
			inDialog:false,//是否在弹窗里面，
			onReady:$.noop,
			checktype:'',
			onSelected:$.noop,
			onLoadData:$.noop
		},opts);
	return this.each(function(){
		var selectlist=$(this),
			$listContainer=$('<div class="selectListContainer" />').appendTo(selectlist),
			$container=$('<div class="selectContent"></div>').appendTo($listContainer),
			backId=-1,
			parentId=0,
			count=options.count,
			selectmode=options.selectmode||1,
			relevance=options.relevance||1,
			selectedValues=options.defaultValues||[],
			selectedData=options.defaultData||'',
			joinpathname=options.joinpathname||'',
			checktype=options.checktype||'',
			selectedBar='',
			selectedCountEl='',
			selectedContainer='',
			setData=function(data,el){
				if(!data.length)return;				
				var pathName=el.data('pathName'),
					pathId=el.data('pathId');
				$.each(data,function(i,item){
					if(!item.id&&item._id){
						item.id=item._id;
					};
					if(!item.name&&item.title){
						item.name=item.title;
					};
					if(!item.id&&item.key){
						item.id=item.key;
					};
					item.id=item.id.toString();
					
					var tpName=pathName?pathName.slice(0):[];
						tpName.push(item.name);
					item.pathName=tpName;
					var tpId=pathId?pathId.slice(0):[];
						tpId.push(item.id);
					item.pathId=tpId;
					
					if(selectedData[item.id]&&!selectedData[item.id]['name']){
						selectedData[item.id]=item;
					};
					
					var childCls='';
					if(item.type=='2'&&checktype){
						childCls='';
					}else if((item.children&&item.children.length)||item.isFolder){
						childCls='child';
					};
					var	activeCls=($.inArray(item.id,selectedValues)>-1)?'active':'',
						selectableCls=(item.unselectable||(selectmode=='2'&&childCls))?'unselectable':'',
						selectBox=count!=1?'<div class="hookLabel"><span class="hookBox"><i class="xzicon-yes hook"></i></span></div>':'<div class="circleLabel"><span class="circle"></span></div>';
						li=$('<li class="selectItem '+childCls+' '+activeCls+' '+selectableCls+'" data-id="'+item.id+'">'+
							'<a href="javascript:;" class="setRow selectLink">'+
								'<div class="setRowL">'+
									(((selectmode=='2'&&childCls)||!options.selectbox)?'':selectBox)+
									(item.icon&&options.icon?'<span class="picBox" data-crop="1"><img data-src="'+item.icon+'"></span>':'')+
									'<span>'+item.name+'</span>'+
								'</div>'+
								(childCls?'<div class="setRowR" role="openchild"><i class="icon-right"></i></div>':'')+
							'</a>'+
						'</li>');
					li.appendTo(el).data('data',item);	
					if(item.icon){
						$('img',li).setThumbImageView();	
					};
				});
			},
			getData=function(el,data,callback){
				if(!options.requestUrl)return;
				var requestData=options.requestData||{};
				var	reData=$.extend(true,requestData,data),
					loadings=$(getContentLoading());
				el.append(loadings);
				
				rpcJSON(options.requestAction,reData,function(backData){
						loadings.remove();
						if($.isFunction(callback)){
							callback(backData);
						}
					},function(msg){
						loadings.remove();
						el.append(msg);
					},options.requestUrl);
			},
			loadData=function(el,tData,isBack){
				backId=parentId;
				parentId=tData.id;
				var ul,
					oul=$('ul.show',$container);
				if(count!=1){
					$('a[role="selcetedAll"]',selectedBar).hide();
				};
				var checkSelectBox=function(){
					if(count!=1&&$('li:not(.unselectable)',ul).length){
						$('a[role="selcetedAll"]',selectedBar).show();
					};
				};
				
				if($('ul[data-id="'+parentId+'"]',$container).length){
					
					ul=$('ul[data-id="'+parentId+'"]',$container).addClass('show');	
					checkSelectBox();
				}else if(tData.children&&tData.children.length){
					
					ul=getNewUl(tData);
					setData(tData.children,ul);
					checkSelectBox();
				}else{
					ul=getNewUl(tData);
					
					getData(ul,{parentId:parentId},function(backData){
						if(backData.length){
							setData(backData,ul);
							checkSelectBox();
						}else{
							ul.append(getEmptyContent());
						};
					});
				};
				
				if(oul.length){
					if(isBack){
						oul.animate({left:'100%'},'normal','easeOut',function(){
							oul.removeClass('show');
						});
						ul.addClass('show').css({left:'-100%'}).animate({left:0},'normal','easeOut');
					}else{
						oul.animate({left:'-100%'},'normal','easeOut',function(){
							oul.removeClass('show');
						});
						ul.css({left:'100%'}).animate({left:0},'normal','easeOut');
					}
				
				};
				options.onLoadData(parentId,tData);
			},
	
			getSelectedData=function(){
				var datas={
						ids:[],
						name:[]
					};
				for(key in selectedData){
					datas.ids.push(selectedData[key].id);
					var names=joinpathname?selectedData[key].pathName.join(joinpathname):(selectedData[key].name||selectedData[key].id);
					
					datas.name.push(names);
				};
				
				return datas;
			},
			selected=function(){
				var names;
				if(count==1){
					selectedValues=[selectedData.id];
					names=joinpathname?selectedData.pathName.join(joinpathname):selectedData.name;
					
				}else{
					var data=getSelectedData();
					selectedValues=data.ids;
					names=data.name;
					selectedCountEl.text(selectedValues.length);
				};
				
				options.onSelected(selectedData,selectedValues,names);
			},
			getNewUl=function(tData){
				if(!tData){
					tData={};
				};
				
				if(!tData.pathName){
					tData.pathName=[];
				};
				
				if(!tData.pathId){
					tData.pathId=[];
				};
				
				var parentName='';
				if(tData.pathName.length>1){
					parentName=tData.pathName[tData.pathName.length-2];
				};
				
				var ul=$('<ul data-id="'+parentId+'"></ul>').appendTo($container);
				$('ul.show',$container).removeClass('show').addClass('hide');
				$('ul[data-id="'+parentId+'"]',$container).addClass('show');
				if(backId!=-1){
					$('<li class="selectItem back" data-backid="'+backId+'" '+(options.inDialog?'style="display:none;"':'')+'>'+
						'<a href="javascript:;" class="setRow selectLink">'+
							'<div class="setRowL">'+
								'<span>'+options.backTitle+'</span>'+
							'</div>'+
						'</a>'+
					'</li>').data('parentName',parentName).appendTo(ul);
					
				};
				ul.data(tData);
				return ul;
			},
			showSelectedContainer=function(){
				if(!selectedContainer){
					selectedContainer=$('<div class="selectContent selectedContainer"><ul class="show"></ul><div class="selectedHeadBar clearfix"><p class="title fl">已选(<span role="selectedCount"></span>)</p><a href="javascript:;" class="button b1 fr" role="closeSelected">关闭</a></div></div>').insertAfter(selectedBar);
					selectedContainer.delegate('div[role="remove"]',click,function(){
						var li=$(this).parents('li:first'),
							id=li.data('id');
						li.hideRemove();
						var seLi=$('li[data-id="'+id+'"]',$container);
						seLi.removeClass('active');
						delete selectedData[id];
						selected();
						selectedContainer.find('span[role="selectedCount"]').text(selectedValues.length);
						if(!selectedValues.length){
							selectedContainer.removeClass('show');
						}
					}).delegate('a[role="closeSelected"]',click,function(e){
						e.stopPropagation();
						selectedContainer.removeClass('show');
					});
				};
				var html='';
				for(key in selectedData){
					var sData=selectedData[key],
						sName=sData.name;
					if(!sName){
						var sli=$('li.selectItem[data-id="'+sData.id+'"]',$container);
						if(sli.length){
							sData=sli.data('data');
							sName=sData.name;
						}else{
							sName=sData.id;
						};
						
					};
					html+='<li class="selectItem" data-id="'+sData.id+'">'+
							'<a href="javascript:;" class="setRow selectLink">'+
								'<div class="setRowL">'+
									(sData.icon?'<span class="picBox" data-crop="1"><img data-src="'+sData.icon+'"></span>':'')+
									'<span>'+sName+'</span>'+
								'</div>'+
								'<div class="setRowR" role="remove"><i class="xzicon-close"></i></div>'+
							'</a>'+
						'</li>';
				};
				selectedContainer.find('ul').html(html);
				selectedContainer.find('span[role="selectedCount"]').text(selectedValues.length);
				if(sData.icon){
					$('img',selectedContainer).setThumbImageView();	
				};
				setTimeout(function(){
					selectedContainer.addClass('show');
				},10);
				
			};
			
			if(selectedValues&&typeof selectedValues=='string'){
				selectedValues=selectedValues.split(',');
			};
			
			//设置已选择的数据
			if(!selectedData){
				if(selectedValues.length){
					 selectedData={};
					 $.each(selectedValues,function(i,item){
						selectedData[item]={
							id:item
						};
					});
				}else{
					selectedData={};
				}
			};
			//如果是多选，则设置已选项
			if(count!=1){
				selectedBar=$('<div class="selectFootBar clearfix"><a href="javascript:;" class="button b1 fl" role="showSelected">已选(<span role="selectedCount">'+selectedValues.length+'</span>)</a><a href="javascript:;" class="button b1 fr" role="selcetedAll">全选</a></div>').insertAfter($container);
				selectedBar.delegate('a[role="selcetedAll"]',click,function(e){
				   $('ul.show >li:not(.active,.unselectable,.back)',$container).trigger(click);
				   e.stopPropagation();
				   e.preventDefault();
			    }).delegate('a[role="showSelected"]',click,function(){
					if(selectedValues.length){
						showSelectedContainer();
					};
				});
				selectedCountEl=$('span[role="selectedCount"]',selectedBar);
			};
			$container.delegate('li',click,function(e){
				e.preventDefault();
				e.stopPropagation();
				if(!$(this).hasClass('unselectable')){
					if($(this).hasClass('back')){
						loadData($container,{id:$(this).data('backid'),name:$(this).data('parentName')},true);
						return;
					};
					var $parent=$(this);
					if(count==1){
						$('li.active',$container).removeClass('active');
						$parent.addClass('active');
						selectedData=$parent.data('data');
						selected();
					}else{
						if(!$parent.hasClass('active')){
							if(count!=0&&selectedValues.length>=count){
								Tips('error','最多可以选'+count+'项');
								return;
							}
						};
						$parent.toggleClass('active');
						var pData=$parent.data('data');
						if($parent.hasClass('active')){
							selectedData[pData.id]=pData;
						}else{
							delete selectedData[pData.id];
						};
						selected();
					};
				}else{
					if($(this).find('div[role="openchild"]').length){
						$(this).find('div[role="openchild"]').trigger(click);
					}
				}
				
			}).delegate('div[role="openchild"]',click,function(e){
				var $li=$(this).parents('li:first');
				loadData($container,$li.data('data'));		
				
				e.preventDefault();
				e.stopPropagation();
		   });
		   
		this.resetSelectData=function(data){
			 selectedData=data;
			 selectedValues=getSelectedData().ids;
			 $('li.active.selectItem',$container).each(function(){
				  var id=$(this).data('id');
				  if(!selectedData[id]){
					$(this).removeClass('active');
				  }
			  });
			
			$.each(selectedValues,function(i,item){
				 $('li.selectItem[data-id="'+item+'"]',$container).addClass('active');
			});  
			
			if(selectedCountEl){
				selectedCountEl.text(selectedValues.length);
			};
			if(selectedContainer){
				selectedContainer.removeClass('show');
			}
			  
		};
		
		this.goBack=function(){
			if(backId!=-1){
				$container.find('ul.show li.back').trigger(click);
			};
		};
		
		if(options.data&&options.data.length){
			var ul=getNewUl();
			setData(options.data,ul);
			options.onReady(options.data);
		}else if(options.requestUrl){
			var ul=getNewUl();
			getData(ul,{},function(backData){
				setData(backData,ul);
				options.onReady(backData);
			});
		};
		
	});
};

/**
 *设置多级选择对话框
 ｛
 onSelected：function(selectedData,selectedValues,showName){
	 
 },
 selectOpts:同setSelectList参数
 ｝
 */

;function expandSelectDialog(opts){
	var dialog,
		count=opts.selectOpts.count==undefined?1:opts.selectOpts.count,
		defaultValues=opts.selectOpts.defaultValues||[],
		checktype=opts.checktype||'',
		defaultName=opts.selectOpts.defaultName||[],
		defaultData=opts.selectOpts.defaultData||'',
		selectedValues,
		selectedData,
		selectedName='',
		selectList,                                                                             
		currentId=0,
		setData=function(){
			var showName;
			if(count==1){
				showName=selectedData.name||selectedName;
				
			}else{
				showName=SiteText.selectedText+selectedValues.length+'('+selectedName+')';
				
			};
			options.onSelected(selectedData,selectedValues,showName);
		},
		options=$.extend(true,{
			selectType:1,
			onSelected:function(data){
				  
			},
			dialogOpts:{
				  
				  cls:'SelectDialog '+(count!=1?'multi-selected':'sole-selected')+' '+(opts.selectType!='2'?'':''),
				  confirmText:SiteText.completeText,
				  closeText:'x',
				  title:opts.title,
				  maxHeight:400,
				  height:400,
				  hideToolsBar:count!=1?true:false,
				  onCancel:function(){
					  selectedValues=defaultValues;
					  selectedData=$.extend({},defaultData);
					  selectedName=defaultName;
					  setData();
					  //selectList.resetSelectData(selectedData);
				  }/*,
				  onBeforeClose:function(){
					  if(dialog.isHide)return;
					  defaultValues=selectedValues;
					  defaultData=$.extend({},selectedData);
					  dialog.hide();
				  },
				  onClose:function(){
					  dialog=null;
				  }*/
			},
			selectOpts:{
				 inDialog:true,
				 onSelected:function(data,id,name){
					 selectedValues=id;
					 selectedData=data;
					 selectedName=name;
					 setData();
					 if(count==1){
						 dialog.close();
					 };
				 },
				 onReady:function(){
					 
					 selectList.resetSelectData=selectList[0].resetSelectData;
					 
				 },
				 onLoadData:function(parentId,tData){
					 currentId=parentId;
					 if(currentId!=0){
						 dialog.setCloseText('<-');
						 dialog.setTitle(tData.name);
					 }else{
						 dialog.setCloseText('x');
						 dialog.setTitle(opts.title);
					 };
				 }
			}
		},opts),
		createDialog=function(){
			if(!(options.selectOpts.data||options.selectOpts.requestUrl))return;
			selectList=$('<div class="dialogSelectList"></div>').appendTo('body');
			
			if(options.selectType=='1'){
				selectList.setSelectList(options.selectOpts);
			}else{
				selectList.setGroupSelectList(options.selectOpts);
			}
			
		},
		showDialog=function(){
			$('div.SelectDialog').hide();
			dialog.show();
		},
		hideDialog=function(){
			dialog.hide();
		};
	
  
	if(defaultValues&&typeof defaultValues=='string'){
			defaultValues=defaultValues.split(',');
		};
		
	//设置已选择的数据
	if(!defaultData){
		defaultData={};
		//设置已选择的数据
		if(defaultValues.length){
			 $.each(defaultValues,function(i,item){
				defaultData[item]={
					id:item
				};
			});
		};
	};
	
	selectedValues=defaultValues;
	selectedData=$.extend({},defaultData);
	
	createDialog();
	dialog=Dialog(selectList,options.dialogOpts);
	
	dialog.find('a[role="dialogCloseBtn"]').on(click,function(e){
		if(currentId!=0){
			selectList[0].goBack();
			e.stopPropagation();
		};
	 });
	
	return dialog;
};


$.fn.btnSelectDialog=function(opts){
	return this.each(function(){
		if(!opts.selectOpts){
			opts.selectOpts={};
		};
		var btn=$(this),
			count=opts.selectOpts.count==undefined?1:opts.selectOpts.count,
			input=opts.input?opts.input:btn,
			checktype=opts.checktype||'',
			defaultName,
			defaultValues,
			defaultData,
			selectedValues,
			selectedData,
			selectedName='',
			dialog,
			selectList,
			currentId=0,
			setData=function(){
				var showName;
				if(count==1){
					showName=selectedName;
				}else{
					showName=SiteText.selectedText+selectedValues.length+'('+selectedName+')';
					
				};
				input.data({
					      selectedValues:selectedValues,
						  selectedData:selectedData,
						  showName:showName
						});
				options.onSelected(selectedData,selectedValues,showName);
				if(count==1&&dialog){
				   dialog.close();
			    };
			},
			setDialog=function(tData){
				if(dialog){
					 if(currentId!=0){
						 dialog.setCloseText('<-');
						 dialog.setTitle(tData.name);
					 }else{
						 dialog.setCloseText('x');
						 dialog.setTitle(opts.title);
					 };
				};
			},
			options=$.extend(true,{
				input:'',
				selectType:1,
				onSelected:function(data){
					  
				},
				dialogOpts:{
					  container:isPhoneState?'body':btn.parent(),
					  overlay:isPhoneState?0.5:0,
					  width:btn.outerWidth(),
					  maxHeight:400,
					  cls:'SelectDialog '+(count!=1?'multi-selected':'sole-selected')+' '+(opts.selectType!='2'?'':''),
					  confirmText:count!=1?SiteText.completeText:'',
					  closeText:opts.title?'x':'',
					  title:opts.title,
					  //maskToolsBar:false,
					  hideToolsBar:true,
					  onCancel:function(){
						  selectedName=defaultName;
						  selectedValues=defaultValues;
						  selectedData=$.extend({},defaultData);
						  setData();
						  //selectList.resetSelectData(selectedData);
					  },
					 /* onBeforeClose:function(fn){
						  
						  if(dialog.isHide)return;
						  defaultValues=selectedValues;
						  defaultData=$.extend({},selectedData);
						  dialog.hide();
						  
					  },*/
					  onClose:function(){
						  dialog=null;
					  },
					  position:{
							top:isPhoneState?'':btn.outerHeight(),
							left:btn.position().left,
							position:'absolute'
					  }
				},
				selectOpts:{
					 checktype:checktype,
					 inDialog:true,
					 onSelected:function(data,id,name){
						 selectedValues=id;
						 selectedData=data;
						 selectedName=name;
						 setData();
						
					 },
					 onReady:function(data){
						dialogReady(data);
						 
					 },
					 onLoadData:function(parentId,tData){
						 currentId=parentId;
						 setDialog(tData);
					 }
				}
			},opts),
			createDialog=function(){
				if(!(options.selectOpts.data||options.selectOpts.requestUrl))return;
				selectList=$('<div class="dialogSelectList"></div>').appendTo('body');
				
				if(options.selectType=='1'){
					selectList.setSelectList(options.selectOpts);
				}else{
					selectList.setGroupSelectList(options.selectOpts);
				}
				
			},
			dialogReady=function(data){
				 currentId=0;
				 var bh=Math.max($(window).height()-(btn.offset().top+btn.outerHeight())-20,200);
					   maxHeight=Math.min(options.dialogOpts.maxHeight,bh);
				   options.dialogOpts.maxHeight=maxHeight;
				   
				   if(!options.dialogOpts.height&&selectList.height()>20){
					   options.dialogOpts.height=selectList.height();
				   };
				   dialog=Dialog(selectList,options.dialogOpts);
				   dialog.find('a[role="dialogCloseBtn"]').on(click,function(e){
					  if(currentId!=0){
						  selectList[0].goBack();
						  e.stopPropagation();
					  };
				   });
				   
				   selectList.resetSelectData=selectList[0].resetSelectData;
				  
				   if(data){
					   input.data('data',data);
					};
					
			},
			showDialog=function(){
				$('div.SelectDialog').hide();
				dialog.show();
			},
			hideDialog=function(){
				dialog.hide();
			};
		
		
		//重设默认数据；
		
		var resetDatas=function(first){
				defaultName=input.data('defaultName')||[];
				defaultValues=input.data('selectedValues')||[];
				defaultData=input.data('selectedData')||'';
				
				if(defaultValues&&typeof defaultValues=='string'){
						defaultValues=defaultValues.split(',');
					};
				
				if(!defaultData){
					defaultData={};
					//设置已选择的数据
					if(defaultValues.length){
						 $.each(defaultValues,function(i,item){
							defaultData[item]={
								id:item
							};
						});
					};
				};
				
				selectedValues=defaultValues;
				selectedData=$.extend({},defaultData);
				
				options.selectOpts.defaultValues=defaultValues;
				options.selectOpts.defaultData=defaultData;
				if(!first||(first&&!options.selectOpts.data&&input.data('data'))){
					options.selectOpts.data=input.data('data');
				};
				
				
		};
		
		if(input.val()&&options.selectOpts.data&&options.selectOpts.data.length){
			var vl=input.val();
			$.each(options.selectOpts.data,function(i,item){
				if(vl==item.id){
					btn.text(item.name);
				};
			});
		};
		
		
		btn.on(click,function(e){
			
			
			if(dialog){
				dialog.close();
				/*if(dialog.isHide){
					showDialog();
				}else{
					hideDialog();
				}*/
				return;
			}else{
				resetDatas(true);
				createDialog();
				
			};
			
			
		});		
			
	});
};

/**
 *表单转多级选择对话框
 selectType为1是多级选择，2是两级连选
 <input name="industry" class="textInput" type="hidden" title="选择行业" role="expandSelect" data-selectbox="1" data-selectmode="1"  data-count="1" data-requesturl="/common/getIndustry" data-selecttype="1">
 如果input设置了data-joinpathname=1，则返回拼接的显示名
 */

;$.fn.inputExpandSelect=function(opts){
	return this.each(function(){
		var input=$(this),
			count=(input.data('count')!=undefined)?input.data('count'):1,
			title=input.attr('title')||SiteText.selectText,
			selectmode=input.data('selectmode'),
			selectedName=input.data('_namespace')||title,
			selectType=input.data('selecttype')||1,
			checktype=input.data('checktype')||'',
			showlist=count==1?0:(input.data('showlist')!=undefined)?input.data('showlist'):0,
			btn=$(showlist?'<a role="addBtn" class="addFormRow" href="javascript:;">\
					<span class="xzicon-add addIcon"></span>\
					<span class="addText">'+title+'</span>\
				  </a>':'<a href="javascript:;" class="formBtn formText">'+selectedName+'</a>').insertAfter(input),
			defaultValues=input.val()?input.val().split(','):[],
			defaultData=input.data('selectedData')||'',
			listContainer=showlist?$('<ul />').insertBefore(btn):'',
			selectedData='',
			options=$.extend(true,{
					input:input,
					selectType:selectType,
					title:title,
					checktype:checktype,
					onSelected:function(data,id,name){
						var showName=name||selectedName,
							joinpathname=input.data('joinpathname');
						if(count==1){
							
							input.val(id[0]);
						}else{
							input.val(id.join(','));
							
						};
						selectedData=data;
						if(!showlist){
							input.data('selectedData',selectedData).data('showName',showName).trigger('change');
						};
					},
					dialogOpts:{
						onCancel:function(){
							
						},
						onConfirm:function(){
							if(showlist&&selectedData){
								for(id in selectedData){
									if(!$('li[data-id="'+id+'"]',listContainer).length){
											insertList(selectedData[id],true);
										};
								};
							};
						}
					},
					selectOpts:{
						count:count,
						icon:input.data('icon'),
						selectmode:selectmode,
						joinpathname:input.data('joinpathname'),
						/*defaultValues:defaultValues,
						defaultData:defaultData,*/
						data:input.data('data'),
						requestUrl:input.data('requesturl'),
						requestAction:input.data('requestaction'),
						requestData:input.data('requestdata'),
						selectbox:input.data('selectbox')==undefined?1:input.data('selectbox'),
						relevance:input.data('relevance')==undefined?1:input.data('relevance')
					}
				
				},opts),
				insertList=function(data,change,noJoin){
					if(noJoin){
						var name=data.name;
					}else{
						var joinpathname=input.data('joinpathname'),
							name=joinpathname?data.pathName.join(joinpathname):data.name;
					};
					var li=$('<li class="addItem" data-id="'+data.id+'">\
								<a href="javascript:;" role="addBtn" class="addText">' +name+ '</span>\
								<a class="button addDelete" href="javascript:;" role="delBtn"><i class="xzicon-delete"></i></a>\
							  </li>').data('data',data).appendTo(listContainer);
					li.find('a[role="delBtn"]').on(click,function(){
						li.hideRemove(function(){
							getValues();
						});
						
					});
					if(change){
						getValues();
					};
				},
				getValues=function(){
					var datas=[],values=[],names=[],pathNames=[];
					var joinpathname=input.data('joinpathname');
					$('li',listContainer).each(function(){
						var data=$(this).data('data'),
							id=data.id,
							name=data.name;
						datas.push(data);
						values.push(id);
						names.push(name);
						
						if(joinpathname){
							pathNames.push(data.pathName.join(joinpathname));
						};
						
					});
					input.val(values).data({
						selectedData:datas,
						showName:names,
						showPathNames:pathNames
						}).trigger('change');
				};
		
		
	    //设置已选择的数据
	    if(!defaultData){
			defaultData={};
			//设置已选择的数据
			if(defaultValues.length>1){
				 $.each(defaultValues,function(i,item){
					defaultData[item]={
						id:item
					};
				});
			}else if(defaultValues.length==1){
				defaultData[defaultValues[0]]={id:defaultValues[0],name:selectedName};
			};
		};
		
		input.data({
				selectedValues:defaultValues,
				selectedData:defaultData,
				showName:selectedName
			  }).on('change',function(){
				  if(!showlist){
						btn.text(input.data('showName'));  
				  };
			  });
		
		if(showlist){
			if(input.val()){
					var values=input.val().split(','),
						names=input.data('_namespace');
					if(typeof names=='string'){
						names=names.split(',');
					};
					$.each(values,function(i,item){
						insertList({id:item,name:names[i]?names[i]:item},'',true);
					});
					
				};
			btn.on(click,function(){
				if(input.data('topdialog')){
					top.expandSelectDialog(options);
				}else{
					expandSelectDialog(options);
				};
				
			});
		}else{
			btn.btnSelectDialog(options);
		};
		
	});
};


/**
 *清除已选择数据
 */

;$.fn.clearSelectedData=function(){
	return this.each(function(){
		$(this).val('').data({
				selectedValues:'',
				selectedData:[],
				showName:$(this).attr('title')||SiteText.selectText,
			}).trigger('change');
	});
};


/**
 *设置日期选择器
 */

;function  datepicker(input,opt){
	input.attr('readonly',true);
	
	if(!$.fn.datepicker){
		getScripts(['/web/js/plugins/datepicker/datepicker.css','/web/js/plugins/datepicker/datepicker.js'],function(){
			datepicker(input,opt);
		});
		return ;
	};
		
	var container=$('<div />'),
		dialog,
		defaultValue=input.val()||getNowDate(),
		options=$.extend(true,{
			date:defaultValue,
			showDefaultValue:true,
			autohide:true,
			inline:true,
			container:container,
			autoshow:true,
			pick:function(type){
				if(options.autohide&&type.view=='day'){
					dialog.hide();
				}
			}
		},opt);
		
	if(!input.val()&&options.showDefaultValue){
		input.val(getNowDate());
	};
	
	input.datepicker(options);
	
	var dialogOpts={
					  container:isPhoneState?'body':input.parent(),
					  overlay:isPhoneState?0.5:0,
					  width:input.data('width')||input.outerWidth(),
					  maxHeight:650,
					  height:350,
					  scroll:true,
					  cls:'picker SelectDialog',
					  closeText:isPhoneState?'x':'',
					  title:isPhoneState?SiteText.selectDate:'',
					  onCancel:function(){
						  input.val(defaultValue);
						  input.trigger('change');
					  },
					  onBeforeClose:function(){
						  if(dialog.isHide)return;
						  dialog.hide();
					  },
					  onClose:function(){
						  //dialog.hide();
					  },
					  position:{
							top:input.outerHeight(),
							left:input.position().left,
							position:'absolute'
					  }
				};
	  input.on(click,function(e){
		  if(dialog){
			  if(dialog.isHide){
				 defaultValue=input.val();
				 dialog.show(); 
			  }else{
				  dialog.hide();
			  };
		  }else{
			 dialog=Dialog(container,dialogOpts);  
		  };
		  e.stopPropagation();
		  if(isPhoneState){
			 input.trigger('blur'); 
		  };
	  });
};

/**
 *设置日期选择器
 */


;function timepicker(input,opt){
	
	input.attr('readonly',true);
	
	
	
	var container=$('<div class="timepicker" />'),
		dialog,
		defaultValue=input.val()||getNowDate('time'),
		selectedH,
		selectedM,
		selectedS,
		options=$.extend(true,{
			showDefaultValue:true
		},opt),
		getLi=function(type,title){
			var num=type=='h'?24:60,
				html='<dl class="'+(type=='h'?'hoursList':'minutesList')+'"><dt>'+selectedH+':'+selectedM+':'+selectedS+'</dt><dd><ul class="clearfix">';
			for(i=0;i<num;i++){
				var text=i<=9?'0'+i:i;
				html+='<li data-num="'+text+'" data-type="'+type+'" class="'+(type=='h'?text==selectedH?'active':'':type=='m'?text==selectedM?'active':'':text==selectedS?'active':'')+'">'+text+'</li>';
			};
			html+='</ul></dd></dt>';
			return html;
		},
		getStart=function(){
			selectedH=defaultValue.split(':')[0]||'00';
		    selectedM=defaultValue.split(':')[1]||'00';
		    selectedS=defaultValue.split(':')[2]||'00';
			container.html(getLi('h',SiteText.selectHour));
		};
		
	
	if(!input.val()&&options.showDefaultValue){
		input.val(getNowDate('time'));
	};
	
	container.delegate('li',click,function(){
		var type=$(this).data('type'),
			num=$(this).data('num');
		if(type=='h'){
			selectedH=num;
			container.html(getLi('m',SiteText.selectMinute));
		}else if(type=='m'){
			selectedM=num;
			container.html(getLi('s',SiteText.selectMinute));
		}else{
			selectedS=num;
			dialog.hide();
		};
		input.val(selectedH+':'+selectedM+':'+selectedS).trigger('change');
	});
	
	var dialogOpts={
					  container:isPhoneState?'body':input.parent(),
					  overlay:isPhoneState?0.5:0,
					  width:input.data('width')||input.outerWidth(),
					  maxHeight:650,
					  height:350,
					  scroll:true,
					   cls:'picker SelectDialog',
					  closeText:isPhoneState?'x':'',
					  title:isPhoneState?SiteText.selectDate:'',
					  onCancel:function(){
						  input.val(defaultValue);
						  input.trigger('change');
					  },
					  onBeforeClose:function(){
						  if(dialog.isHide)return;
						  dialog.hide();
					  },
					  onClose:function(){
						  //dialog.hide();
					  },
					  position:{
							top:input.outerHeight(),
							left:input.position().left,
							position:'absolute'
					  }
				};
	  input.on(click,function(e){
		
		  if(dialog){
			 if(dialog.isHide){ 
				 defaultValue=input.val();
				 getStart(); 
				 dialog.show(); 
			 }else{
				dialog.hide(); 
			 };
		  }else{
			 getStart(); 
			 dialog=Dialog(container,dialogOpts);  
		  };
		  e.stopPropagation();
		  if(isPhoneState){
			 input.trigger('blur'); 
		  };
	  });
};

;function resetImageHtml(html){
	var newHtml=$('<div>'+html+'</div>');
	newHtml.find('img').each(function(){
		var $this=$(this),
			src=$this.attr('src');
		if(src){	
			src=src.split('?')[0];	
		}else{
			src=errorImgSrc;
		};
		$this.css({height:'auto',
				   width:'auto'}).attr('data-src',src).attr('data-autoheight',1).attr('src','');
		if(!$this.hasClass('responsiveImage')){
			$this.addClass('responsiveImage');
		};
	});
	var html=newHtml.html();
	newHtml.remove();
	return html;
};

/**
 *设置编辑器
 opt={
	 uploadOpts：{}//参考xzUpload的opts
 }
 
 */

;function textareaToEditor(el,opt,hidePhone){
	var el=$(el);
	
	if(hidePhone&&isPhoneState){
		el.data('editor',{
			getData:function(){
				return el.val();
			},
			html:function(value){
				el.val(value);
			}
		});
		return;
	};
	
	el.css('border','none');
		
	if(!isPhoneState){
		if(typeof CKEDITOR=='undefined'){
			getScripts([staticPath+'/plugins/ckeditor/samples/sample.css',staticPath+'/plugins/ckeditor/ckeditor.js'],function(){
				
				textareaToEditor(el,opt);
			});
			return false;
		};
	}else {
		if(!$.fn.froalaEditor){
			var froalaEditorCssPath=staticPath+'/plugins/froala_editor/css/',
				froalaEditorJsPath=staticPath+'/plugins/froala_editor/js/',
				froalaEditorSources=[
						staticPath+'/js/ColorPickerItems.js',
						froalaEditorCssPath+'font-awesome.min.css',
						froalaEditorCssPath+'froala_editor.min.css',
						froalaEditorCssPath+'froala_style.min.css',
						froalaEditorJsPath+'froala_editor.min.js',
						froalaEditorJsPath+'/languages/zh_cn.js',
						froalaEditorCssPath+'plugins/colors.min.css',
						froalaEditorJsPath+'plugins/colors.min.js',
						froalaEditorJsPath+'plugins/paragraph_format.min.js'
						
						/*,
						froalaEditorCssPath+'plugins/image.css',
						froalaEditorJsPath+'plugins/image.min.js',
						froalaEditorJsPath+'plugins/align.min.js'*/
						]
			getScripts(froalaEditorSources,function(){
				textareaToEditor(el,opt);
			});
			$('<style>div.fr-wrapper+div{display:none !important;}\
			  </style>').appendTo('body');
			return false;
		};
	};
	
	
	var vid=getNewId(),
		tDiv=$('<div />').html(el.val());
	
	tDiv.find('img.responsiveImage[data-src]').each(function(){
		
		$(this).attr('src',getWidthImageView($(this).data('src'),el.parent().width()-50));
	});
	
	el.val(tDiv.html());
	
	tDiv.remove();
	
	if(!el.attr('id')){
		el.attr('id',vid);
	}else{
		vid=el.attr('id');
	};
	
	if(!isPhoneState){
		var opts=$.extend(true,{
			extraPlugins:'image,imagelist,table,horizontalrule,format,font,colorbutton,colordialog,maximize,resize,justify,contextmenu,pastefromword',
				toolbarGroups:[
					//{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
					
					{ name: 'editing',     groups: [ 'undo','find', 'selection', 'spellchecker' ] },
					{ name: 'forms' },
					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
					{ name: 'paragraph', groups: [ 'list',  'align'] },
					{ name: 'links' },
					{ name: 'insert' , groups: [ 'image','imagelist','table','horizontalrule']},
					{ name: 'clipboard' },
					{ name: 'styles' },
					{ name: 'colors' },
					{ name: 'tools' },
					{ name: 'others'},
				],
			removeButtons:'Cut,Copy,Paste,Anchor,Subscript,Superscript',
			resize_enabled:true,
			on:{
				loaded:function(){
					if($.isFunction(resizeScroll)){
						resizeScroll();
					}
				}/*,
				change:function(){
					el.val(resetImageHtml(editor.getData()));
				},
				blur:function(){
					el.val(resetImageHtml(editor.getData()));
					tests(1);
				}*/
			}
		},opt);
	}else{
		var colors=[];
		$.each(ColorPickerItems,function(i,item){
			colors.push(item.color);
		});
		colors.push('REMOVE');
		el.froalaEditor({
			language:'zh_cn',
			charCounterCount:false,
			toolbarButtonsXS: ['undo', 'redo','bold', 'italic', 'paragraphFormat','color',  'selectAll'],
			colorsBackground:colors,
			colorsStep: 6,
			colorsText: colors
			});
		el.data('editor',{
			html:function(value){
				el.froalaEditor('html.set',value);
			},
			getData:function(){
				return el.froalaEditor('html.get',true);
			}
		});
		return false;
	};
	
	
	CKEDITOR.replace(vid,opts);	
	
	var editor=CKEDITOR.instances[vid];
	
	if(opts.uploadOpts){
		editor.uploadOpts=opts.uploadOpts;
	};
	
	editor.html=function(value){
		editor.setData(value);
	};
	
	editor.remove=function(){
		editor.destroy();
	};
	
	el.data('editor',editor);
	
	return editor;
	
};

$.fn.getEditorData=function(){
	return this.each(function(){
		if($(this).data('editor')){
			$(this).val(resetImageHtml($(this).data('editor').getData()));
		};
	});
};





