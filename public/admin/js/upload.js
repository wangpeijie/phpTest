;function upload(opt){
        var options = $.extend(true,{
		max_file_size:'5mb',
		url:'/admin/upload/file',
                browse_button: 'upload',
		extensions:'jpg,png,jpeg,gif,apk',
		title:'上传文件'
	},opt),newDialog, newDialog2;
	var speedHtml = $('<div class="speedContainer">\
                                <div class="speedDiv">\
                                        <span class="speedSpan"></span>\
                                </div>\
                                <p class="tac mt10 p18" role="num"></p>\
                        </div>\
                        <p style="color:#666;">正在上传<span class="fileName"></span></p>');
        var uploader = new plupload.Uploader({
//        var uploader = new plupload.Uploader({
                runtimes: 'html5,flash,silverlight,html4',
                url: options.url,
                browse_button: options.browse_button,
                max_file_size: options.max_file_size,
                flash_swf_url: '/web/js/plugins/plupload/Moxie.swf',
                silverlight_xap_url: '/web/js/plugins/plupload/Moxie.xap',
                filters: options.extensions ? [{title: options.title, extensions: options.extensions}] : '',
                chunk_size: '4mb',                  // 分块上传时，每块的体积
                autostart: true,
                multi_selection: true,
                init: {
                        FilesAdded: function (up, files) {//添加地址
                                
//                                plupload.each(files, function(file) {
                                    uploader.start();
                                    // 文件添加进队列后，处理相关的事情
//                                });
                        },
                        StateChanged: function () {
                        },
                        UploadProgress: function (up, file) {
//                                $('#uploadmsg').html('上传已完成' + file.percent + '%').show();
                                    var fileBar=$('.speedContainer');
						fileBar.find('.speedSpan').css('width', file.percent + "%");
						fileBar.find('[role="num"]').text(file.percent + "%");
						if(file.percent==100){
//							setTimeout(function(){
								newDialog.close();
//							},1000);
						};
                        },
                        FileUploaded: function (up, file, response) {//文件上传成功
                                if (!response.response) {
                                    Tips('error', '上传失败');
                                    return false;
                                }
                                var data = $.parseJSON(response.response);
                                if (data.code == 0) {
                                    $('#photpsrc').attr('src', '/' + data.data);
                                } else {
                                    Tips('error', '上传失败');
                                }
                        },
                        Error: function (up, err) {//失败
                                var msg = '';
                                switch (err.message) {
                                        case'File extension error.':
                                            msg = '文件格式不正确';
                                            break;
                                        case'File size error.':
                                            msg = '文件大小不正确';
                                            break;
                                        default:
                                            msg = '上传失败';
                                }
                                Tips('error', msg);
                        },
                        UploadComplete: function (uploader, files) {
                                $('.fileName',speedHtml).text(files.name);
                                newDialog2 = Dialog(speedHtml,{
                                        title:'文件上传成功',
                                        outerClose:false,
                                });
                                setTimeout(function(){
                                        newDialog2.close();
                                },1000);
                        },
                        UploadFile:function(uploader,files){//当上传队列中某一个文件开始上传后触发 uploader为当前的plupload实例对象，file为触发此事件的文件对象
                                $('.fileName',speedHtml).text(files.name);
                                newDialog  = Dialog(speedHtml,{
                                        title:'文件开始上传',
                                        outerClose:false
                                });
                        }
                    }
            });
            uploader.init();
        }
;function qiniuUpload(){
        var uploader = Qiniu.uploader({
                                runtimes: 'html5,flash,silverlight,html4',    //上传模式,依次退化
                                browse_button: 'upload',       //上传选择的点选按钮，**必需**
                                uptoken_url: '/admin/upload/uptoken',            //Ajax请求upToken的Url，**强烈建议设置**（服务端提供）
//                                 uptoken : '<Your upload token>', //若未指定uptoken_url,则必须指定 uptoken ,uptoken由其他程序生成
                                // unique_names: true, // 默认 false，key为文件名。若开启该选项，SDK为自动生成上传成功后的key（文件名）。
                                // save_key: true,   // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK会忽略对key的处理
                                domain: 'http://oj13vjytx.bkt.clouddn.com/',   //bucket 域名，下载资源时用到，**必需**
                                get_new_uptoken: false,  //设置上传文件的时候是否每次都重新获取新的token
                                container: 'wraper',           //上传区域DOM ID，默认是browser_button的父元素，
                                max_file_size: '1000mb',           //最大文件体积限制
                                flash_swf_url: '/admin/js/plugins/plupload/Moxie.swf',  //引入flash,相对路径
                                silverlight_xap_url: '/admin/js/plugins/plupload/Moxie.xap',
                                max_retries: 3,                   //上传失败最大重试次数
                                dragdrop: true,                   //开启可拖曳上传
                                drop_element: 'wraper',        //拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
                                chunk_size: '4mb',                //分块上传时，每片的体积
                                auto_start: true,                 //选择文件后自动上传，若关闭需要自己绑定事件触发上传
                                init: {
                                        FilesAdded: function(up, files) {
                                                plupload.each(files, function(file) {
                                                    // 文件添加进队列后,处理相关的事情
                                                });
                                        },
                                        BeforeUpload: function(up, file) {
                                               // 每个文件上传前,处理相关的事情
                                        },
                                        UploadProgress: function(up, file) {
                                               // 每个文件上传时,处理相关的事情
                                        },
                                        FileUploaded: function(up, file, info) {
                                               // 每个文件上传成功后,处理相关的事情
                                               // 其中 info 是文件上传成功后，服务端返回的json，形式如
                                               // {
                                               //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                                               //    "key": "gogopher.jpg"
                                               //  }
                                               // 参考http://developer.qiniu.com/docs/v6/api/overview/up/response/simple-response.html

                                               // var domain = up.getOption('domain');
                                               // var res = parseJSON(info);
                                               // var sourceLink = domain + res.key; 获取上传成功后的文件的Url
                                        },
                                        Error: function(up, err, errTip) {
                                               //上传出错时,处理相关的事情
                                               alert(err.message);
                                        },
                                        UploadComplete: function() {
                                               //队列文件处理完毕后,处理相关的事情
                                               alert('seccuess');
                                        },
                                        Key: function(up, file) {
                                                // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                                                // 该配置必须要在 unique_names: false , save_key: false 时才生效
                                                var fileType=file.name.split('.')[file.name.split('.').length - 1];
                                                var key = getNowRandom()+'.'+fileType;
                                                return key;
//                                                var key = "";
//                                                // do something with key here
//                                                return key;
                                        }
                                }
                        });
        }