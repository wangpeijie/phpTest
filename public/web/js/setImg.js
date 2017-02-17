// setImg.js   设置图片

var maxImgSize=9999,
	thumbnailQuality = 90,
	devicePixelRatio=$.type(window.devicePixelRatio) !== 'undefined'?window.devicePixelRatio:1,//屏幕倍数
	filePath='http://7xsgvq.com1.z0.glb.clouddn.com/',
	videoCodeImgSrc=filePath+'videoCode.png',
	errorImgSrc=filePath+'145984256588091.png';


/**
 *获取限定宽高的图片
 */
;function getThumbImageView(file,w,h){
	if(!file){
		file=errorImgSrc;
	};
	if(file!=errorImgSrc&&file.indexOf('/')<0){
		file=filePath+file;
	};
	if(file.indexOf('ico')>-1||file.indexOf('icon')>-1){
		return file;
	};
	var _w=w*devicePixelRatio,
		_h=h*devicePixelRatio;
	if(_w>maxImgSize){
		_h=Math.floor(maxImgSize/w*_h);
		_w=maxImgSize;
	};
	if(_h>maxImgSize){
		_w=Math.floor(maxImgSize/_h*_w);
		_h=maxImgSize;
	};
	file+='?imageMogr2/thumbnail/'+(_w)+'x'+(_h)+'%3E/quality/'+thumbnailQuality+'!';
	return file;
};




/**
 *获取限定宽高裁切后的图片
 */
;function getCropImageView(file,w,h){
	if(!file){
		file=errorImgSrc;
	};
	if(file!=errorImgSrc&&file.indexOf('/')<0){
		file=filePath+file;
	};
	if(file.indexOf('ico')>-1||file.indexOf('icon')>-1){
		return file;
	};
	var _w=w*devicePixelRatio,
		_h=h*devicePixelRatio;
	if(_w>maxImgSize){
		_h=Math.floor(maxImgSize/w*_h);
		_w=maxImgSize;
	};
	if(_h>maxImgSize){
		_w=Math.floor(maxImgSize/_h*_w);
		_h=maxImgSize;
	};
	
	file+='?imageMogr2/thumbnail/!'+(_w)+'x'+(_h)+'r/gravity/Center/crop/'+(_w)+'x'+(_h)+'/quality/'+thumbnailQuality+'!';
	return file;
};

/**
 *图片根据容器宽高获取只能大小
 */
;$.fn.setThumbImageView=function(autoHeight){
	return this.each(function(){
		var $this=$(this).hide(),
			AH=maxImgSize/devicePixelRatio,
			src=$this.data('src')?$this.data('src'):$this.data('video')?videoCodeImgSrc:errorImgSrc,
			parent=$this.parents(':not(a):first'),
			autoHeight=autoHeight?autoHeight:$this.data('autoheight')?$this.data('autoheight'):'',
			w=$this.data('iw')?$this.data('iw'):parent.width(),
			h=autoHeight?AH:parent.height(),
			crop=parent.data('crop')||$this.data('crop'),
			ratio=parent.data('ratio')||$this.data('ratio');
			
		if(!w){
			var resize=function(){
					if(!$this.data('ready')){
						$this.setThumbImageView();
					}else{
						$(window).off('resize',resize);
					};
				};
			$(window).resize(resize);
			return;	
		};
		if(ratio){
			h=Math.floor(w*ratio);
		}else{
			if(!h){
				if(crop){
					h=w;
				}else{
					h=AH;
				};
			};
		};
		
		var nSrc=crop?getCropImageView(src,w,h):getThumbImageView(src,w,h);
		
		$this.data('ready',1);
		$this.show().css({'max-height':'100%','max-width':'100%'}).attr('src',nSrc);
		
		this.onerror=function(){
			var eSrc=crop?getCropImageView(errorImgSrc,w,h):getThumbImageView(errorImgSrc,w,h);
			$this.attr('src',eSrc);
		};
	});
};




