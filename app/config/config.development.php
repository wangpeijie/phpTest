<?php

return new \Phalcon\Config(
        array(
        'database' => array(
                'adapter' => 'Mysql',
                'host' => '127.0.0.1',
                'username' => 'root',
                'password' => 'root',
                'dbname' => 'club',
        ),
//        'database' => array(
//                'adapter' => 'Mysql',
//                'host' => '192.168.1.113',
//                'username' => 'root',
//                'password' => '123456',
//                'dbname' => 'youhao',
//        ),
        'redis' => array(
                'host' => '192.168.1.113',
                'port' => 6379,
                'timeout' => 30,
        ),
        'pay' => array(
                'alipay' => array(
                        'id' => 1,
                        'name' => '支付宝',
                        'pic' => '/web/images/zhifubao.png',
                ),
                'weixin' => array(
                        'id' => 2,
                        'name' => '微信',
                        'pic' => '/web/images/weixinzhifu.png',
                ),
                'unionpay' => array(
                        'id' => 3,
                        'name' => '银联',
                        'pic' => '/web/images/yinlian.png',
                ),
        ),
        'name' => '友好',
        'authkey' => array(
                'pc' => 'e384261b82e45f692946c7b3f666d226',
                'app' => 'e384261b82e45f692946c7b3f666d226',
        ),
        'url' => 'http://youhao.cn',
        'upload' => array(
                'accessKey' => 'dhs2_XZd2fJQ8dj5N-CJTkuVT5_TqpwOj-IdzsVr',
                'secretKey' => 'mkV_3fKLWUdJ6LymyxmZqQMQf-zyKYpigqBOhdxZ',
                'bucket' => 'laowang',
                'url' => 'http://oj13vjytx.bkt.clouddn.com/'
        ),
        'fileext' => array(
                'pic' => ['jpg', 'gif', 'jpeg', 'png', 'swf', 'pdf', 'ppt', 'xlsx', 'xls', 'doc','apk'],
                'resources' => ['zip', 'rar', 'apk'],
        ),
        'weixinOpen' => array(
                'appID' => 'wxbbdcc9c1b677a544',
                'appSecret' => '817a53fdbf4fffc8c02087dbf211e8c2',
                'callback' => 'http://youhao.cn/login/wxCallback',
        ),
        'weibo' => array(
                'WB_AKEY' => '3111926630',
                'WB_SKEY' => 'b82b3cee5544f2dd285a43557a40ce7e',
                'WB_CALLBACK_URL' => 'http://youhao.cn/login/wbRegCallback',
                'WB_CALLBACK_URL_LOGIN' => 'http://youhao.cn/login/wbCallback',
        ),
        'qq' => array(
                'app_id' => '101300972',
                'app_secret' => '4612fe8eacd144d83543dbb944d94639',
                'redirect' => 'http://youhao.cn/login/qqRegCallback',
                'redirect_login' => 'http://youhao.cn/login/qqCallback',
        ),
        'uploadSize' => 1024 * 1024 * 1024, //1G
        'application' => array(
//                'controllersDir' => __DIR__ . '/../controllers/',
//                'modelsDir' => __DIR__ . '/../models/',
//                'viewsDir' => __DIR__ . '/../views/',
                'pluginsDir' => __DIR__ . '/../plugins/',
                'libraryDir' => __DIR__ . '/../library/',
                'rpcDir' => __DIR__ . '/../rpc/',
                'baseUri' => '/',
                'imageUri' => __DIR__ . '/../../public/web/images/',
        )
        )
);
