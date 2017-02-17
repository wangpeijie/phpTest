

/*
 *公共数据
 */	 
var click='click',//默认点击事件
	process=false,//过程状态，过程进行中，禁止操作。
	$preventEvent=false,//禁止触摸事件
	webViewCall=$.noop,
	targetOrigin = '*',
	thisFrameId='',
	supportTouch=isTouch="ontouchend" in document,
	maxImgSize=9999,
	topIndex=11000,
	windowHeight=$(window).height(),
	windowWidth=$(window).width(),
	IEV=0,
	videoExtensions='mp4,mov,flv,f4v,mpe,vob,wmv,mpg,mlv,mpeg,avi,3gp,ra,rm,rmvb,ram',
	imageExtensions='jpg,jpeg,png,gif',
	isPhoneState=windowWidth<=768,//手机状态
	devicePixelRatio=Math.min($.type(window.devicePixelRatio) !== 'undefined'?window.devicePixelRatio:1,2),//屏幕倍数
	isMac=navigator.userAgent.indexOf("Mac OS X")>-1,
	isIos=!!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
	isAndroid=navigator.userAgent.indexOf('Android') > -1 || navigator.userAgent.indexOf('Linux') > -1, //android终端或uc浏览器
	isIphone=navigator.userAgent.indexOf('iPhone') > -1, //是否为iPhone或者QQHD浏览器
	isIpad=navigator.userAgent.indexOf('iPad') > -1,//是否iPad
	isWeixin=navigator.userAgent.toLowerCase().match(/MicroMessenger/i)=="micromessenger";//是否微信

var windowURL = window.location.href;
var phprpcURL = '/rpc/index/';
var rpcAction = 'rpcIndex';

$.toJSON=function(obj){
	if(typeof obj=='object'){
		return JSON.stringify(obj);
	}else{
		return obj;
	};
};

/**
 *低级浏览器提示
 */
 
var lowBrowserHtml='有部分功能您的浏览器不支持，请更换高级浏览器，建议使用<a href="http://www.firefox.com" target="_blank" title="下载firefox浏览器">firefox</a>,<a href="https://www.google.com/intl/en/chrome/browser/" target="_blank" title="下载chrome浏览器">chrome</a>或<a href="http://windows.microsoft.com/zh-cn/internet-explorer/download-ie" target="_blank" title="下载IE10浏览器">IE10及以上</a>';
 
;function lowBrowser(callBack){
	Alert(lowBrowserHtml,callBack);
};

;function getHost(){
	return  windowURL.split('://').length>1?windowURL.split('://')[0]+'://'+windowURL.split('://')[1].split('/')[0]:'';
};

;function getDomain(){
	var host=windowURL.split('://').length>1?windowURL.split('://')[1].split('/')[0]:'';
	var domains=host.split('.');
	var domain=domains[domains.length-2]+'.'+domains[domains.length-1];
	return domain;
	
};

;function getQrcode(data){
	if(data){
		data=data.toString();
	};
	if(data.indexOf('://')>-1){
		data=encodeURIComponent(data);
	};
	return 'http://'+domain+'/api/qrcode/?data='+data;
};


function getIeVersion(){
	
    var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
	var isOpera = userAgent.indexOf("Opera") > -1; //判断是否Opera浏览器
    var isIE = userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1 && !isOpera; //判断是否IE浏览器
    var fIEVersion=0;
    if (isIE) {
        var reIE = new RegExp("MSIE (\\d+\\.\\d+);");
        reIE.test(userAgent);
        fIEVersion = parseFloat(RegExp["$1"]);
        
    };
	return fIEVersion;
};

IEV=getIeVersion();
/**
 *输出测试数据
 */
;function test(smg,plus){
	var testContainer=$('#testContainer');
	if(!testContainer.length){
		testContainer=$('<div id="testContainer" style="position:fixed; z-index:'+getTopIndex()+'; top:0; left:50px;padding:20px;background:rgba(0,0,0,.5);color:#fff; max-height:100%;overflow:auto;"></div>').appendTo('body');
	};

	if(!(typeof smg=='array')){
		smg=[smg];
	};
	
	var html=plus&&testContainer.html()?testContainer.html()+'<br>':'';
	$.each(smg,function(i,item){
		if(i>0){
			html+='/';
		};
		html+=item;				
	});
	
	testContainer.html(html);
};

;function tests(smg){
	test(smg,1);
};

;function objectLength(obj){
	var i=0;
	if(typeof obj=='object'){
		for(key in obj){
			i++;
		}
	};
	return i;
};

/**
 *过滤script标签
 */
 
;function stripScript(s) {
	return s.replace(/<script.*?>.*?<\/script>/ig, '');
};
 
/**
 *图片的加载事件
 */
;$.fn.loadImg=function(callback){
	return this.each(function(){
		if(!$.isFunction(callback))return;
		var img=$(this)[0],
			onLoads; 
		
		if($(this).data('onLoads')){
			onLoads=$(this).data('onLoads');
			onLoads.push(callback);
		}else{
			onLoads=[callback];
		};
		$(this).data('onLoads',onLoads);
		
		if (document.all) { //如果是IE
 
				 var timer = setInterval(function() {
						if (img.complete) {
							$.each(onLoads,function(i,item){
								item(img);
							}); 
							clearInterval(timer);
						}
					}, 50);
	 
		  }
	 
		  else {
	
				 img.onload=function(){
					$.each(onLoads,function(i,item){
						item(img);
					}); 
				 };
	 
		   };
	});
};

/**
 *动态加载js和css
 */

;function getScript(src,callback){
	if(!src)return;
    var type=src.split('.')[src.split('.').length-1],
		lscript=type=='css'?$('link[href="'+src+'"]'):$('script[src="'+src+'"]'),
		callback=$.isFunction(callback)?callback:$.noop;
    if(lscript.length){
		lscript=lscript[0];
		
		if(lscript.loadeds){
			if(lscript.loaded=='yes'){
				callback(lscript);
			}else{
				lscript.loadeds.push(callback);
			}
		}else{
			callback(lscript);
		};
	}else{
		var newscript;
		
		if(type!='css'){
			newscript = document.createElement("script");
			newscript.src=src;
		}else{
			newscript = document.createElement("link");
			newscript.href=src;
			newscript.rel="stylesheet";
			newscript.type="text/css";
		};
		
		newscript.loadeds=[callback];
		
		var jsonload=function(){
					newscript.loaded='yes';
					$.each(newscript.loadeds,function(i,item){
						item(newscript);
					});
				};	
		
		if (document.all) { //如果是IE
 
				newscript.onreadystatechange = function () {
	 
						if (newscript.readyState == 'loaded' || newscript.readyState == 'complete') {
	
						  jsonload();
	 
					  }
	 
			   }
	 
		  }
	 
		  else {
	 
				 newscript.onload=jsonload;
	 
		   };
			
		document.body.appendChild(newscript);
	}
	
};

;function getScripts(srcs,callback){
	var i=0,
		callback=callback||$.noop,
		loads=function(){
			getScript(srcs[i],function(){
				i++;
				if(i<srcs.length){
					loads();
				}else{
					callback();
				};
			});
		};
	    loads();
};

/**
 *检测并执行方法
 */
 
;function fireHandler(fn){
	if($.isFunction(fn)){
		fn();
	};
};



/*
 *获取当前时间
 */
 
function getNowDate(type) {
	var date = new Date();
	var seperator1 = "/";
	var seperator2 = ":";
	var year = date.getFullYear();
	var month = date.getMonth() + 1;
	var strDate = date.getDate();
	var hours=date.getHours();
	var minutes=date.getMinutes();
	var seconds=date.getSeconds();
	if (month >= 1 && month <= 9) {
		month = "0" + month;
	};
	if (strDate >= 0 && strDate <= 9) {
		strDate = "0" + strDate;
	};
	
	var currentdate = year + seperator1 + month + seperator1 + strDate;
	var currentTime=(hours<=9?'0'+hours:hours) + seperator2 + (minutes<=9?'0'+minutes:minutes);
			
	return type=='all'?currentdate+' '+currentTime:type=='time'?currentTime:currentdate;
};

/**
 *计算一个日期字符串与当前日期相差值
 *输入的参数形式如：2012/12/12 12:12:12或2012-12-12 12:12:12
 *返回相差值的字符串
 */
function getDateDiff(datetime,short){
	if(!datetime)return '';
	// 可以将2012 - 12 - 12 12 : 12 : 12字符串转为JS中的时期对象,
	// 因为默认情况下只把持2000 / 05 / 05这样形式的字符串转为时间对象
	var dateBegin = new Date(datetime.replace(/-/g, "/"));
	var dateEnd = new Date();
	var beginDay = dateBegin.getDate();
	var endDay = dateEnd.getDate();
	var beginMonth = dateBegin.getMonth();
	var endMonth = dateEnd.getMonth();
	var beginYear = dateBegin.getFullYear();
	var endYear = dateEnd.getFullYear();
	var dateDiff = dateEnd.getTime() - dateBegin.getTime();
	var yearDiff=endYear-beginYear;
	// 计算相差的天数
	var dayDiff = endDay-beginDay+yearDiff*365+(endMonth-beginMonth)*31;
	var parttime = datetime.substring(11);
	var returnstr = "";
	
	if(dayDiff > 2) //前天以前就直接返回时间字符串
	{
		var date=datetime.substr(0,10);
		if(short&&endYear==beginYear){
			date=date.substr(5,10);
		};
		return short?date:datetime;
	}
	else //前天以来的处理
	{
		
		switch (dayDiff)
		{
			case 2 :
				returnstr += (short?SiteText.beforeYesterday:SiteText.beforeYesterday+' ' + parttime);
			break;
			case 1 :
				returnstr += (short?SiteText.yesterday:SiteText.yesterday+' ' + parttime);
			break;
			default : //对今天的操作
				var minuteleft = Math.floor(dateDiff / (60 * 1000)); //计算相差的分钟数
				if(minuteleft > 30)
				{
					returnstr += parttime;
				}
				else if (minuteleft < 1)
				{
					returnstr += SiteText.justNow;
				}
				else
				{
					returnstr += minuteleft + SiteText.beforeMinutes;
				}
		};
	
		return returnstr;
	}
};

$.fn.dateDiff=function(opts){
	return this.each(function(){
		var options=$.extend(true,{
				short:true
			},opts)
		var dateTime=$(this).data('time');
		if(!dateTime){
			dateTime=$(this).text();
			$(this).data('time',dateTime);
		};
		var diff=getDateDiff(dateTime,options.short);
		$(this).text(diff);
	});
};
/*
 *解析url
 */
function urlToJson(str) 
{
   str=decodeURIComponent(str);	
   var data={},
   	   name=null,
   	   value=null,
	   num=str.indexOf("?");
	   
   str=str.substr(num+1);
   var arr=str.split("&");
   for(var i=0;i < arr.length;i++){ 
    num=arr[i].indexOf("="); 
    if(num>0){ 
     name=arr[i].substring(0,num);
     value=arr[i].substr(num+1);
     data[name]=value;
     } 
    };
	return data;
};

/**
 *字符替换为图标
 */

;function stringToIcon(str){
	
	var str;
	if(str=='x'){
		str='<span class="icon-close"></span>';
	}else if(str=='<'){
		str='<span class="icon-left"></span>';
	}else if(str=='>'){
		str='<span class="icon-right"></span>';
	}else if(str=='...'){
		str='<span class="icon-more"></span>';
	}else if(str=='y'){
		str='<span class="icon-success"></span>';
	}else if(str=='i'){
		str='<span class="icon-about"></span>';
	}else if(str=='<-'){
		str='<span class="icon-left"></span>';
	};
	return str;
};

/**
 *获取随机数
 */
;
function getRandom(length) {
        var charactors = "ab1cd2ef3gh4ij5kl6mn7opq8rst9uvw0xyz",
                value = '',
                i;
        length = length || 4;
        for (j = 1; j <= length; j++) {
                i = parseInt(35 * Math.random());

                value += charactors.charAt(i);
        }
        ;
        return value;
}
;

/**
 *获取时间加随机数
 */
function getNowRandom(){
	var j=Math.ceil(Math.random()*10000).toString(),
		m=j.length;
	if(m<4){
		for(q=0;q<4-m;q++){
			j+='0';
		};
	};
	return $.now()+j;
};

/**
 *获取新的唯一id
 */
var getNewTempId = 0;
;
function getNewId() {
        getNewTempId++;
        return $.now() + getRandom() + getNewTempId;
}
;


/**
 *获取最高层级
 */

;function getTopIndex(){
	return topIndex++;
};

/**
 *设置遮罩
 */
 
;function setMask(flag){
	if(flag==0){
		$('#bodyMask').remove();
	}else if(!$('#bodyMask').length){
		$('<div id="bodyMask" style="position:absolute;left:0;top:0;width:100%;height:100%;background:#fff;opacity:0.01;z-index:'+getTopIndex()+'"></div>').appendTo('body');
	}
};


/**
 *渐隐并删除元素
 */
 
$.fn.hideRemove = function(opts){
	if($.isFunction(opts)){
		opts={
			onRemove:opts
			}
	};
	var options=$.extend({},{easing:'linear',speed:'fast',height:0,onRemove:$.noop},opts),
		height=opts==1?true:false;
	return this.each(function(i) { 
		$(this).animate({opacity:0,height:height?$(this).height:options.height},options.speed,options.easing,function(){
																								$(this).remove();
																								options.onRemove();
																								});
	});
};

/**
 *简短提示信息
 */
 
;function Tips(type,msg,timer,callback){
	if(this!=top&&top.Tips){
		return top.Tips(type,msg,timer,callback);
	};
	
	if(type&&!msg){
		var msg=type;
		type='error';
	};
	
	var html,
		message,
		type=type||'default',
		timer=timer||1000;
	switch(type){
		case 'loading':
			message=msg||SiteText.loading;
			html='<div class="tipsLoading tips"><div class="tipsPad"><i class="icon-load"></i>'+message+'</div></div>';
		break;
		
		case 'success':
			message=msg||SiteText.success;
			html='<div class="tipsSuccess tips"><div class="tipsPad"><i class="icon-sure"></i>'+message+'</div></div>';
		break;
		
		case 'error':
			message=msg||SiteText.operationFailure;
			html='<div class="tipsError tips"><div class="tipsPad"><i class="icon-close"></i>'+message+'</div></div>';
		break;
		
		case 'warning':
			message=msg||SiteText.attention;
			html='<div class="tipsWarning tips"><div class="tipsPad"><i class="xzicon-warn"></i>'+message+'</div></div>';
		break;
		
		case 'news':
			message=msg||SiteText.newMessage;
			html='<div class="tipsNews tips"><div class="tipsPad"><i class="xzicon-about"></i>'+message+'</div></div>';
		break;
		
	};
	
	process=true;
	//setMask();
	
	var msgEl=$(html).appendTo('body');
	msgEl.css({
			   zIndex:getTopIndex(),
			   left:($('body').width()-msgEl.outerWidth())/2
			   }).hide().fadeIn();
	
	//msgEl.toCenter().hide().fadeIn();
	
	msgEl.close=function(){
		if(!msgEl.length)return;
		msgEl.fadeOut(function(){
				  msgEl.remove(); 
				  process=false;
				//  setMask(0);
				  fireHandler(callback);
			   });
	};
	msgEl.on(click,msgEl.close);
	
	
	if(timer>-1){
		setTimeout(msgEl.close,timer);
	};
	
	return msgEl;
};

/**
 *提示遮罩层
 */

;function setOverlay(opacity,bgColor,container,zIndex){
	var overlay=jQuery('<div style="position:fixed;display:none;left:0;top:0;width:100%;height:100%;opacity:'+(opacity||0.01)+';background:'+(bgColor||'#000')+';z-index:'+(zIndex||getTopIndex())+'"></div>'),
		container=container||'body';
	return overlay.appendTo(container).fadeIn().on({
			'touchmove':function(e){e.preventDefault();e.stopPropagation();}
			});
};





/**
 *字符串转换为数字
 */
 
;function stringToNumber(str){
	if(str&&!isNaN(str)){
		str=parseInt(str);
	};
	return str;
};


 
/*
 *转换英文符号为代码
 */

;function enToString(value){
	if(typeof value=='string'){
		//return value.replace(/\'/g,"&#8217;").replace(/\"/g,"&#8221;");
		return value.replace(/\'/g,"&#8217;").replace(/\‘/g,"&#8217;").replace(/\’/g,"&#8217;");
	}else{
		return value;
	}
};




/**
 *批量操作
 */
;function checkAll(el){
	var el=$(el);
	el.delegate(':checkbox','change',function(e){
		var name=$(this).attr('name'),
			btn=el.find(':checkbox[name="checkAll"]'),
			checkbox=el.find(':checkbox:not([name="checkAll"],[disabled="disabled"])');
			
		if(name=='checkAll'){
			if($(this).prop('checked')){
				checkbox.prop('checked','checked');
				el.find('[role="item"]').addClass('checked');
			}else{
				checkbox.prop('checked',false);
				el.find('[role="item"]').removeClass('checked');
			}
		}else{
			var check=true;
			checkbox.each(function(){
				if(!$(this).prop('checked')){
					check=false;
					return;
				}
			});
			if(check){
				btn.prop('checked','checked');
			}else{
				btn.prop('checked',false);
			};
			if($(this).prop('checked')){
				if($(this).parents('[role="item"]').length){
					$(this).parents('[role="item"]').addClass('checked');
				}
			}else{
				if($(this).parents('[role="item"]').length){
					$(this).parents('[role="item"]').removeClass('checked');
				}
			}
		};
		e.stopPropagation();
	});

};

/**
 *获取批量数据
 */		
 
;function getAllCheck(el){
	var items=[],
		value=[];
		
	$(el).find('input[type="checkbox"]').each(function(){
		if($(this).prop('checked')){
			items.push($(this));
			value.push($(this).attr('value'));
		};
	});
	return {items:items,value:value};
};

/**
 *设置批量删除数据
 */	

;function setDelAll(listEl,action,url){
	if(!(listEl.length&&action))return;
	
	//设置全选按钮
	;checkAll(listEl);
	

	//删除数据
	var del=function(checks){
		Confirm(SiteText.removeSelected,function(){
			rpcJSON(action,{ids:checks.value},function(backData){
				Tips('success',SiteText.removeSuccess);	
				checks.items.parents('[role="item"]').hideRemove();
			},'',url);							
		});
	};
	
	//批量删除
	var delAll=function(){
		var checks=getAllCheck(listEl);
		if(!checks.value.length){
			Alert(SiteText.noSelected);	
		}else{
			del(checks);
		}
	};
	
	//删除按钮
	listEl.delegate('a[role]',click,function(e){
		e.preventDefault();	
		switch($(this).attr('role')){
			case 'delAllBtn':
				delAll();
			break;
			case 'deleteBtn':
				var checks=$(this).parents('[role="item"]:first').find('input[type="checkbox"]');
				del({
						items:checks,
						value:[checks.val()]
					});
			break;
		};
	});
};




;
function rpc(action, data, callBack, errorBack, dataType, url) {

        var url = url || (typeof phprpcURL == 'string' ? phprpcURL : '');

        if (!(action && url))
                return;

        process = true;
        //setMask();

        var loading = null,
                loadingEl = null,
                delayLoading = setTimeout(function () {
                        loading = true;
						
                        loadingEl = Tips('loading', '', -1);
                }, 1200),
                hideLoading = function () {
                        if (loading) {
                                loadingEl.close();
                        } else {
                                clearTimeout(delayLoading);
                        }
                };

        data = data || {};
        var fn = function () {
                var client = new PHPRPC_Client(url, [action]);
                client[action]($.toJSON(data), function (result, args, output, warning) {

                        process = false;
                        //setMask(0);

                        hideLoading();
                        var outData = output,
                                success = function () {
                                        if ($.isFunction(callBack)) {
                                                callBack(outData);
                                        }
                                        ;
                                },
                                error = function (msg) {
                                        if ($.isFunction(errorBack)) {
                                                errorBack(msg, result, args, output, warning);
                                        } else {
                                                var message = msg || '操作失败！';
                                                Tips('error', message, 1000);
                                        }
                                        ;
                                };
                        if (output != undefined) {
                                if (dataType == 'json') {
                                        try {
                                                outData = $.parseJSON(output);
                                                if (outData.code == '0') {
                                                        outData = outData.data;
                                                        success();
                                                } else {
                                                        error(outData.errorMessage);
                                                }
                                                ;
                                        }
                                        catch (e) {
                                                error();
                                        }
                                        ;
                                } else {
                                        success();
                                }
                                ;
                        } else {
                                error();
                        }
                }, true);
        };
        if (typeof PHPRPC_Client == 'undefined') {
                $.getScript(rpcJS, fn);
        } else {
                fn();
        }
}
;

;
function rpcJSON(action, data, callBack, errorBack, url) {
        rpc(action, data, callBack, errorBack, 'json', url);
}
;

/**
 *元素居中效果
 */
 
;$.fn.toCenter = function(inner){
	return this.each(function(i) { 
		var parent=inner?$(this).parent():$(window),
			position=$(this).css('position'),
			pos={
		　　　　　　top:( parent.height() - 　$(this).height() ) / 2 + (position=='fixed'?0:parent.scrollTop()) + 'px',
		　　　　　　left:( parent.width() - 　$(this).width() ) / 2 + parent.scrollLeft() + 'px'
		　　　　};
		if(!position){
			pos.position='absolute';
		};　
		$(this).css(pos);
	});
};


/**
 *元素激活效果
 */
 
;$.fn.toActive = function(callBack){
	return this.each(function(i) { 
		if(!$(this).hasClass('active')){
			$(this).addClass('active').siblings().removeClass('active');
			if($.isFunction(callBack)){
				callBack($(this));
			};
		}
	});
};


/**
 *删除数组中的值
 */
 
function removeArray(arr,val){
	if(!(arr&&val))return;
	
	var narr=[];
	
	if(typeof val=='object'){
		val=$.toJSON(val);
	};
	$.each(arr,function(i,item){
		var nval=typeof item == 'object'?$.toJSON(item):item;
		if(nval!=val){
			narr.push(item);
		}
	});
	return narr;
};


/**
 *获取网页尺寸
 */
 
;function getPageSize() {


    var xScroll, yScroll;

    if (window.innerHeight && window.scrollMaxY) {

        xScroll = window.innerWidth + window.scrollMaxX;

        yScroll = window.innerHeight + window.scrollMaxY;

    } else {

        if (document.body.scrollHeight > document.body.offsetHeight) { 

            xScroll = document.body.scrollWidth;

            yScroll = document.body.scrollHeight;

        } else {

            xScroll = document.body.offsetWidth;

            yScroll = document.body.offsetHeight;

        }

    };

    var windowWidth, windowHeight;

    if (self.innerHeight) {  

        if (document.documentElement.clientWidth) {

            windowWidth = document.documentElement.clientWidth;

        } else {

            windowWidth = self.innerWidth;

        };

        windowHeight = self.innerHeight;

    } else {

        if (document.documentElement && document.documentElement.clientHeight) {

            windowWidth = document.documentElement.clientWidth;

            windowHeight = document.documentElement.clientHeight;

        } else {

            if (document.body) { 

                windowWidth = document.body.clientWidth;

                windowHeight = document.body.clientHeight;

            }

        }

    };      

    if (yScroll < windowHeight) {

        pageHeight = windowHeight;

    } else {

        pageHeight = yScroll;

    }; 

    if (xScroll < windowWidth) {

        pageWidth = xScroll;

    } else {

        pageWidth = windowWidth;

    };

    arrayPageSize = new Array(pageWidth, pageHeight, windowWidth, windowHeight);

    return ({
			pageWidth:pageWidth,
			pageHeight:pageHeight,
			windowWidth:windowWidth,
			windowHeight:windowHeight
			});

};

/**
 *表格高亮
 */
 
;highlight = function(el){
	
	$(el).delegate('tbody tr',{
		mouseenter:function(){
			$(this).addClass('over');	
		},
		mouseleave:function(){
			$(this).removeClass('over');	
		}
	});
};


/**
 *jQuery操作cookie
 */
 
;$.cookie=function(name, value, options,wc) {
	  if (typeof value != 'undefined') {
				if(typeof options == 'number'){
					options={expires:options};
				};
				options = options || {};
				if (value === null) {
						  value = '';
						  options = $.extend({}, options);
						  options.expires = -1;
				};
				var expires = '';
				if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
						  var date;
						  if (typeof options.expires == 'number') {
									date = new Date();
									date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
						  } else {
									date = options.expires;
						  };
						  expires = '; expires=' + date.toUTCString();
				};
				var path = options.path ? '; path=' + (options.path) : '';
				var domain = options.domain ? '; domain=' + (options.domain) : '';
				var secure = options.secure ? '; secure' : '';
				document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
	  } else {
		    var cookieValue = null;
		  	if (document.cookie && document.cookie != '') {
					  var cookies = document.cookie.split(';');
					  for (var i = 0; i < cookies.length; i++) {
								var cookie = jQuery.trim(cookies[i]);
								if (cookie.substring(0, name.length + 1) == (name + '=')) {
										  cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
										  break;
								};
					  };
			};
			return cookieValue;
	  };
};

/**
 *获取loading代码
 */

;function getContentLoading(align,msg,style){
	var message=msg||'',
		text_align=align||'center';
	return '<div style="text-align:'+text_align+'; '+(style?style:'padding:10px 0')+';" class="loading"><span class="contentLoading">'+message+'</span></div>';
};


/**
 *获取空数据
 */

;
function getEmptyContent(str) {
        return '<div class="noData empty"><i class="xzicon-nodata noDataIcon"></i><p class="noDataTitle">'+(str?str:SiteText.noData)+'</p></div>';
};


$.fn.scrollLoad=function(opts){
	return this.each(function(){
		if($(this).data('scrollLoad'))return;
		var target=$(this),
			contentContainer=target.children(':first'),
			options=$.extend(true,{
					target:target,
					contentContainer:contentContainer,
					contentLoading:opts.contentLoading||$('<div />').appendTo(target)
				},opts);
		
		scrollLoad(options);
		target.data('scrollLoad',1);	
			
	});
};



/**
 *清除元素内所有弹出提示
 */
;
function clearTips(el) {
        $('*[showtipsel]', el).hideTips();
};



/**
 *页面滚动到
 */

;function pageScrollTo(num){
	var container=$('#pageScrollWrapper').length?$('#pageScrollWrapper'):$(window);
	container.scrollTop(num);
};

/**
 *自动滚屏
 */
 	
$.fn.scrollToBottom=function(){
	return this.each(function(){
		var el=$(this);
		el.scrollTop(el.children().outerHeight()-el.height());
	});
};


/**
 *获取元素的margin值
 */

$.fn.getMarginNumber=function(){
		return Number($(this).css('marginLeft').replace('auto',0).replace('px',''))+Number($(this).css('marginRight').replace('auto',0).replace('px',''));
};

/**
 *获取元素的完整宽度，包括margin值
 */
 	
$.fn.fullWidth=function(){
	return $(this).outerWidth()+$(this).getMarginNumber();
};

/**
 *设置数字分页
 */

$.fn.setPageList=function(opts){
	var options=$.extend(true,{
			onChange:$.noop,
			size:10,
			page:1,
			pageSize:5,
			pageSelect:[10,20,30,50,100],
			showPageSelect:true,
			showSize:true,
			showPrev:true,
			showNext:true,
			showFirst:true,
			showLast:true,
			showGo:true,
			units:'',
			goText:'GO'
		},opts);
	return this.each(function(){
		var $self=$(this),
			pageNum=options.page;
					
		var	html='<div class="tableCount">';
			
			if(options.showSize){
				html+=SiteText.totally+'<span role="count"></span>'+options.units+'&nbsp;&nbsp;';
			};
			
			if(options.showPageSelect){
				html+=SiteText.pageSize+
					'&nbsp;<span class="smallPopupBar">'+
						'<a class="smallPopupBtn" href="javascript:;"><span role="pageSizeNum"></span></a>'+
						'<div class="smallPopup" role="pageSize">';
				
				$.each(options.pageSelect,function(i,item){
					html+='<a class="popupItem '+(item==options.size?'active':'')+'" href="javascript:;">'+item+'</a>';
				});
	
				html+='</div>'+
					'</span>&nbsp;'+options.units;
				
			};
			
			html+='</div><div class="tablePaging" >'+
				'<span class="pagingBox">'+
					(options.showFirst?'<a href="javascript:;" role="first"><i class="xzicon-first"></i></a>':'')+
					(options.showPrev?'<a href="javascript:;" role="prev"><i class="icon-left"></i></a>':'')+
					'<span>'+
					'</span>'+
					(options.showNext?'<a href="javascript:;" role="next"><i class="icon-right"></i></a>':'')+
					(options.showLast?'<a href="javascript:;" role="last"><i class="xzicon-last"></i></a>':'')+
				'</span>';
			if(options.showGo){	
				html+='<span class="pagingInput">'+
					SiteText.pageTo+'<input class="inputText" type="text" name="pageInput" role="number">'+SiteText.pageUnits
					'<a href="javascript:;" role="goPage">'+options.goText+'</a>'+
				'</span>';
			};
			html+='</div>';
		$self.html(html).data('pageSize',options.pageSize);	
		var tablePaging=$self.find('div.tablePaging');
		//页码操作
		tablePaging.delegate('.pagingBox>a:not(.disable)',click,function(){
			pageNum=$self.data('page');
			switch($(this).attr('role')){
			
			case 'first':
				pageNum=1;
				options.onChange(pageNum);
			break;
			
			case 'prev':
				pageNum--;
				options.onChange(pageNum);
			break;
			
			case 'next':
				pageNum++;
				options.onChange(pageNum);
			break;
			
			case 'last':
				pageNum=$self.data('pageCount');
				options.onChange(pageNum);
			break;
				
			}
		}).delegate('.pagingBox>span>a',click,function(){
			if($(this).hasClass('active')){
				if(isPhoneState){
					var items=[];
					for(i=1;i<=$self.data('pageCount');i++){
						items.push({value:i,name:SiteText.theText+i+SiteText.pageUnits});
					};
					var dialog=MenuDialog(items);
					dialog.find('li').on(click,function(){
						var value=$(this).find('a').data('value');
						pageNum=Number(value);
						options.onChange(pageNum);
						dialog.close();
					});
				}
			}else{
				var text=$(this).text();
				pageNum=Number(text);
				options.onChange(pageNum);
			};
		});
		$('div[role="pageSize"] a').on(click,function(){
			var size=$(this).text(),
				pageNum=1;
			$(this).toActive();	
			options.onChange(pageNum,size);
			
		});
		
		$('a[role="goPage"]').on(click,function(){
			var val=Math.round($('input[name="pageInput"]').val());
			if(val&&val<=$self.data('pageCount')){
				pageNum=val;
				options.onChange(pageNum);
			}else{
				Tips('error',SiteText.rightPageNum);
			}
		});
		
		if(options.showPageSelect){
			$self.find('a.smallPopupBtn').setDropContainer();
		};
	});
};

/**
 *重设数字分页页码
 */
 	
$.fn.resetPageList=function(page,size,count){
	return this.each(function(){
		  var  $self=$(this), 
			   tablePaging=$self.find('div.tablePaging');
		  
		  if(!tablePaging.length){
			  return;
		  };
		  
		  $self.find('span[role="count"]').text(count);
		  $self.find('span[role="pageSizeNum"]').text(size);
		  
		  var pageCount=Math.ceil(count / size),
			  pageSize=$self.data('pageSize');
		  
		  $self.data({pageCount:pageCount,
					  page:page	
					  });
		  
		 
		  if(pageCount<2){
			  $self.hide();
			  return;
		  };
		  $self.show();
		  
		  var pageStart=1,
			  pageEnd=pageCount;
			  
			if(pageCount>pageSize){
				if(page<=Math.floor(pageSize/2)){
					pageEnd=pageSize;
					
				}else{
					if(page+Math.floor(pageSize/2)<pageCount){
						pageEnd=page+Math.floor(pageSize/2);
					};
					pageStart=pageEnd-pageSize+1;
				};
			};
			
			
			$('.pagingBox>span',tablePaging).empty();
			for (var i=pageStart;i<=pageEnd;i++){
				$('.pagingBox>span',tablePaging).append('<a href="javascript:;" class="'+(i==page?'active':'')+'">'+ i +'</a>');
			};
			
			$('.pagingBox>a',tablePaging).removeClass('disable');
			if(page==1){
				$('.pagingBox>a[role="first"]',tablePaging).addClass('disable');
				$('.pagingBox>a[role="prev"]',tablePaging).addClass('disable');
			};
			if(page==pageCount){
				$('.pagingBox>a[role="next"]',tablePaging).addClass('disable');
				$('.pagingBox>a[role="last"]',tablePaging).addClass('disable');
			};
	});
};



/**
 *分享
 */
;function share(data){
	if(!window.shareDialog){
		getScript(staticPath+'/js/share.js',function(){
			shareDialog(data);
		});
	}else{
		shareDialog(data);
	};
};


//去除空格
function Trim(str,is_global){

            var result;

            result = str.replace(/(^\s+)|(\s+$)/g,"");

            if(is_global&&is_global.toLowerCase()=="g")

            {

                result = result.replace(/\s/g,"");

             };

            return result;

};

//获取完整地址
function getFullUrl(href){
	if(href.indexOf('://')<0&&windowURL.indexOf('://')>=0){
			  if(windowURL){
				  href=windowURL.split('/')[0]+'//'+windowURL.split('/')[2]+href;
			  };
		  };
	return href;	  
};

/*
 *添加默认事件
 */

$(document).ready(function(){
	
	 $('body').delegate('a',click,function(e){
	
		 var tab=$(this).attr('tab'),
			 href=$(this).attr('href')||'javascript:;';
		 
		 
	}).delegate('[data-tips]',{
		mouseenter:function(e){
			if(!isTouch){
				var $this=$(this);
				$this.showTips();
			};
		},
		mouseleave:function(e){
			if(!isTouch){
				var $this=$(this);
				$this.hideTips();
			};
		},
		click:function(e){
			if(!isTouch){
				$(this).hideTips();
			};
		}
	}).delegate('input[type="text"],input[type="password"],input[type="tel"],input[type="email"],textarea',{
		focus:function(){
			$(this).removeClass('error success').addClass('focus');
		},
		blur:function(){
			var value=$(this).val();
			
			if(!$(this).data('script')){
				value=stripScript(value);
			};
			if($(this).data('clearspace')||$(this).attr('type')=='email'||$(this).attr('type')=='tel'||$(this).attr('type')=='password'){
				value=Trim(value,'g');
			}else{
				value=Trim(value);
			};
			if(value!==''&&Trim(value,'g')==''){
				$(this).val('');
			}else{
				$(this).val(value);
			};
			$(this).removeClass('focus');
		}
	}).delegate('input[role="number"]',{
		keyup:function(){
			this.value=this.value.replace(/\D/g,'');
		},
		afterpaste:function(){
			this.value=this.value.replace(/\D/g,'');	
		}
	}).delegate('input[role="price"]',{
		keyup:function(){
			checkPrice($(this));
		},
		afterpaste:function(){
			checkPrice($(this));
		}
	});
	
	
	//接收iframe信息，设置frameid
	if(!IEV||IEV>9){
		window.addEventListener("message", function(e){
			
			if(e.data=='toparTitleClick'){
				toparTitleClick();
				
			}else if(e.data.indexOf('thisFrameId')==0){
				thisFrameId=e.data.replace('thisFrameId=','');
			};
		}, true);
	};
		 
}).ajaxStart()
   .ajaxError(function(){Tips('error')})
   .ajaxStop(function(){})
   .ajaxSuccess(function(){});
$(window).resize(function(){
	windowHeight=$(window).height();
	windowWidth=$(window).width();
	isPhoneState=windowWidth<=768;
});

	//下拉框
	$.fn.optionsBox = function (container) {
			return this.each(function () {
					var btn = $(this);
	
					btn.on('click', function (e) {
							btn.siblings('div:first').fadeToggle(200);
							e.stopPropagation();
							//btn.parent().siblings().find(container).addClass('hide');
					});
					btn.siblings('div:first').find('li').on('click', function () {
							btn.text($(this).text());
							btn.attr('name', $(this).find('a').attr("name"));
							container.fadeOut(200);
					});
					$(document).on('click', function () {
							container.fadeOut(200);
					});
			});
	};
	
	//下拉框
    $('.screenBtn a').optionsBox($('.screenBox'));

//	$('.option_more a').optionsBox($(".option_moreBox"));
