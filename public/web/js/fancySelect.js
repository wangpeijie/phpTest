;
(function($) {
	$.fn.fancySelect = function(opts) {
		var optionS = $.extend({}, $.fn.fancySelect.defaultSettings, opts),
			documentEl = document.documentElement ? document.documentElement : document.body;
		return this.each(function() {
			var $this = this,
				el = $(this),
				parent = el.parent(),
				targetEl = $('<span class="fancySelect ' + optionS.fancyClass + '"></span>'),
				zIndex = targetEl.css('zIndex'),
				titleEl = $('<div class="fancySelectBtn">' + el.find('option:selected').html() + '<span class="icon-down fr"></span></div>'),
				containerEl = $('<div class="fancySelectMain"><ul></ul></div>'),
				baseHeight, state = 0,
				width=el.css('width'),
				ww=$(window).width(),
				show = function() {
					if (state) return;
					targetEl.css('zIndex', 111111);
					containerEl.css({
						display: 'block',
						'height': 'auto'
					});
					baseHeight = containerEl.height();
					var height = baseHeight;
					if (baseHeight + containerEl.offset().top > documentEl.scrollTop + documentEl.clientHeight) {
						height = documentEl.scrollTop + documentEl.clientHeight - containerEl.offset().top - 5;
						if (height < 50) {
							height = 50
						}
					};
					
					if(ww<768){
						containerEl.css({'height':0,opacity:0}).animate({
							'height': height+30,
							opacity:1
						}, optionS.speed,function(){
							state = 1;
						});
						//$('html,body').addClass('noScroll');
					}else{
						containerEl.css('height', 0).animate({
							'height': height,
							opacity:1
						}, optionS.speed, 'swing', function() {
							if (height < baseHeight) {
								containerEl.css({
									'height': height
								})
							};
							state = 1;
						});
					};
					el.trigger('focus');
					
				},
				hide = function() {
					if (!state) return;
					containerEl.animate({
						height: 0,
						opacity:0
					}, optionS.speed, 'swing', function() {
						containerEl.css({
							display: 'none',
							'zIndex': 222221
						});
						targetEl.css('zIndex', zIndex);
						state = 0;
					}).off('touchdrag');
					el.trigger('blur');
					//$('html,body').removeClass('noScroll');
				},
				htmls = '',
				selectHead='';
			if (optionS.selectHead) {
				selectHead='<div class="fancySelectHead"><a href="javascript:;" class="fancySelectReturn">'+SiteText.cancelText+'</a><p class="fancySelectTitle">'+ optionS.selectTitle +'</p></div>';
			}
			this.select = function(num) {
				var selectEl = $(targetEl.find('li')[num]),
					value = selectEl.data('value');
				selectEl.addClass('selected').siblings().removeClass('selected');
				titleEl.html(selectEl.html() + '<span class="icon-down fr"></span>');
				el[0].selectedIndex=num;
				el.trigger('change');
				if ($.isFunction(optionS.onChange)) {
					optionS.onChange({
						index: num,
						value: value,
						text: selectEl.html(),
						target: $this
					})
				}
			};
			this.update = function() {
				var num = el.find('option').index(el.find('option:selected'));
				this.select(num);
			};
			el.on('change', function() {
				var value = el.val(),
					selectEl = $(targetEl.find('li [data-value="' + value + '"]'));
				selectEl.addClass('selected').siblings().removeClass('selected')
			});
			$.each(el.find('option'), function() {
				htmls += '<li data-value="' + $(this).attr("value") + '"';
				if ($(this).attr('selected') == 'selected') htmls += ' class="selected"';
				htmls += '>' + $(this).html() + '</li>'
			});
			targetEl.append(titleEl, containerEl).css({
				display: 'block',
				'width':width
			}).insertAfter(el);
			titleEl.css({
				width: '100%'
			});
			containerEl.find('ul').append($(htmls));
			baseHeight = containerEl.height();
			containerEl.css({
				height: 0,
				display: 'none'
			});
			if(ww<768){
				containerEl.css({
					'bottom':0,
					'top':'auto',
					'position':'fixed'
				});
			};
			containerEl.append($(selectHead));
			
			/*if(optionS.search){
				var searchBox=$('<div class="headSearch"></div>').appendTo($('div.fancySelectHead',containerEl))
				searchBox.setSearchBox();
			};*/
			
			el.css({
				display: 'none'
			});
			targetEl.find('li').mouseenter(function(e) {
				$(this).addClass('over')
			}).mouseleave(function() {
				$(this).removeClass('over')
			}).click(function(e) {
				$this.select(targetEl.find('li').index($(this)));
				if (!optionS.selectHide) {
					e.preventDefault();
					e.stopPropagation()
				}
			});
			targetEl.on(optionS.showEvent, show).mouseenter(function(e) {
				targetEl.addClass('over')
			}).mouseleave(function() {
				targetEl.removeClass('over')
			});
			targetEl.find('li').click(hide);//new
			$(document).click(hide);
			el.data('fancyEl', this);
			
			
		})
	};
	$.fn.fancySelect.defaultSettings = {
		fancyClass: '',
		showEvent: 'click',
		selectHide: true,
		speed: 300,
		zIndex: 1001,
		onChange: $.noop,
		search:false,
		selectHead:true,
		selectTitle:SiteText.selectText
	}
})(jQuery);