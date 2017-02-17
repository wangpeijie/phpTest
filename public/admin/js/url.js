var sec = $('.second').children('span');
var time = setInterval(delay,1000);
function delay()
{
        var t = sec.text()-1;
        sec.text(t);
        if(parseInt(t)<=0)
        {
                clearInterval(time);
                alert('url');
//                window.location.href = 'http://www.baidu.com';
        }
};