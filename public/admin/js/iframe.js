
/*右侧打开iframe*/
function openIframe(opts) {
    var options = $.extend('', {
        target: '',
        title: '',
        url: '',
        ConfirmText: '保存',
        width: '',
        outerClose: true, //点击弹出框外围是否自动关闭
    }, opts);
    var html = $('<div><div class="openIframe"><div class="openIframeContainer">' +
            '<div class="openIframeBox">' +
            '<div class="openIframe_head">' +
            '<p class=""><a href="javascript:;" role="close"><i class="icon-close"></i></a><span class="title">' + options.title + '</span></p>' +
            '</div>' +
            '<div class="openIframe_body">' +
            '<iframe class="openIframe_body" src="' + options.url + '"></iframe></div>' +
            '</div>' +
            '</div></div></div>');
    var parent = options.target || $('body'),
            openIframeContainer = $('.openIframeContainer', html);
    parent.append(html);
    options.open = function () {
        openIframeContainer.animate({
            left: options.width || '60%'
        });
    };
    options.close = function () {
        openIframeContainer.animate({
            left: 100 + '%'
        }, '', function () {
            html.remove();
        });
    };
    $('iframe', html).load(function () {
        if ($('iframe', html).contents().find('a[role="submit"]')) {
//            $('iframe', html).contents().find('a[role="submit"]').attr('class', 'openIframeBtn mainBtn').text(options.ConfirmText);
//            $('iframe', html).contents().find('a[role="submit"]').parent().attr('class', 'openIframeBtn_box');
        }
    });
    options.open();
    html.delegate('a[role]', 'click', function (e) {
        e.stopPropagation();
        var $this = $(this),
                role = $this.attr('role');
        switch (role) {
            case 'close':
                options.close();
                break;
        }
    }).delegate('.openIframe', 'click', function () {
        if (options.outerClose) {
            options.close();
        }
    });
}