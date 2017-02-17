<?php

class Pay
{

        /**
         * 银联支付
         * @param type $params
         * @param type $ordernumNew
         * @return type
         */
        public function unionpay($params, $ordernumNew)
        {
                header('Content-type:text/html;charset=utf-8');
                require __DIR__ . '/../plugins/unionpay/sdk/acp_service.php';

                $para = array(
                        //以下信息非特殊情况不需要改动
                        'version' => '5.0.0', //版本号
                        'encoding' => 'utf-8', //编码方式
                        'txnType' => '01', //交易类型
                        'txnSubType' => '01', //交易子类
                        'bizType' => '000201', //业务类型
                        'frontUrl' => SDK_FRONT_NOTIFY_URL, //前台通知地址
                        'backUrl' => SDK_BACK_NOTIFY_URL, //后台通知地址
                        'signMethod' => '01', //签名方法
                        'channelType' => '07', //渠道类型，07-PC，08-手机
                        'accessType' => '0', //接入类型
                        'currencyCode' => '156', //交易币种，境内商户固定156
                        //TODO 以下信息需要填写 
                        'merId' => SDK_MERCHANT_CODE, //商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
                        'orderId' => $ordernumNew, //商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
                        'txnTime' => date('YmdHis'), //订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
                        'txnAmt' => $params['reamount'] * 100, //交易金额，单位分，此处默认取demo演示页面传递的参数
//                        'reqReserved' => '透传信息', //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
//                        TODO 其他特殊用法请查看 special_use_purchase.php
                );
                AcpService::sign($para);
                if ($params['from'] == 'app')
                {
                        $url = SDK_App_Request_Url;
                        $result_arr = AcpService::post($para, $url);
                        if (count($result_arr) <= 0)
                        {       //没收到200应答的情况
                                return '没收到200应答';
                        }
                        if (!AcpService::validate($result_arr))
                        {
//                                echo "应答报文验签失败<br>\n";
                                return '应答报文验签失败';
                        }
                        if ($result_arr["respCode"] == "00")
                        {
                                //成功
                                return $result_arr["tn"];
                        } else
                        {
                                //其他应答码做以失败处理
                                return "失败：" . $result_arr["respMsg"] . "。<br>\n";
                        }
                } else
                {
                        $uri = SDK_FRONT_TRANS_URL;
                        return $params['from'] == 'app' ? AcpService::createAutoFormHtmlPayOrder($para, $uri) : AcpService::createAutoFormHtml($para, $uri);
                }
        }

        public function unionpay2($params, $ordernumNew)
        {
                header('Content-type:text/html;charset=utf-8');
                require __DIR__ . '/../plugins/unionpay/sdk/acp_service.php';

                $accNo = $params['accNo'];
                $customerInfo = array(
                        'phoneNo' => $params['phoneNo'], //手机号
                        'customerNm' => $params['customerNm'], //姓名
//                        'certifTp' => '01', //证件类型，01-身份证
//                        'certifId' => '510265790128303', //证件号，15位身份证不校验尾号，18位会校验尾号，请务必在前端写好校验代码
//                        'cvn2' => '248', //cvn2
//                        'expired' => '1912', //有效期，YYMM格式，持卡人卡面印的是MMYY的，请注意代码设置倒一下
                );

                writeLogs3($accNo, 'api516');
                writeLogs3(json_encode($customerInfo), 'api516');

                $para = array(
                        //以下信息非特殊情况不需要改动
                        'version' => '5.0.0', //版本号
                        'encoding' => 'utf-8', //编码方式
                        'signMethod' => '01', //签名方法
                        'txnType' => '01', //交易类型
                        'txnSubType' => '01', //交易子类
                        'bizType' => '000301', //业务类型
                        'accessType' => '0', //接入类型
                        'channelType' => '07', //渠道类型，07-PC，08-手机
                        'currencyCode' => '156', //交易币种，境内商户勿改
//                        'encryptCertId' => AcpService::getEncryptCertId(), //验签证书序列号
                        'frontUrl' => SDK_FRONT_NOTIFY_URL, //前台通知地址
                        'backUrl' => SDK_BACK_NOTIFY_URL, //后台通知地址	
                        //TODO 以下信息需要填写
                        'merId' => SDK_MERCHANT_CODE, //商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
                        'orderId' => $ordernumNew, //商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
                        'txnTime' => date('YmdHis'), //订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
                        'txnAmt' => $params['reamount'] * 100, //交易金额，单位分，此处默认取demo演示页面传递的参数
//                        'reqReserved' => '透传信息', //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
//                        'reserved' => '{customPage=true}', //如果开通并支付页面需要使用嵌入页面的话，请上送此用法
                        'accNo' => $accNo, //卡号，旧规范请按此方式填写
//                        'customerInfo' => AcpService::getCustomerInfo($customerInfo), //持卡人身份信息，旧规范请按此方式填写
//                        'accNo' => AcpService::encryptData($accNo), //卡号，新规范请按此方式填写
                        'customerInfo' => AcpService::getCustomerInfoWithEncrypt($customerInfo), //持卡人身份信息，新规范请按此方式填写
                );
                AcpService::sign($para);
                writeLogs3(json_encode($para), 'api516');
                $uri = SDK_FRONT_TRANS_URL;
                return AcpService::createAutoFormHtml($para, $uri);
        }

        /**
         * 微信支付
         * @param type $params //参数
         * @param type $ordernum //订单号
         * @param type $body //商品描述
         * @return type
         */
        public function wxpay($params, $ordernum, $body)
        {
                $config = getConfig();
                //微信支付
                if ($params['from'] == 'pc')
                {
                        require_once __DIR__ . '/../plugins/wxpay/lib/WxPay.ConfigWeb.php';
                } else
                {
                        require_once __DIR__ . '/../plugins/wxpay/lib/WxPay.Config.php';
                }
                require_once __DIR__ . '/../plugins/wxpay/lib/WxPay.Api.php';
                require_once __DIR__ . '/../plugins/wxpay/lib/WxPay.JsApiPay.php';
                $input = new WxPayUnifiedOrder();
                //$params['type']:JSAPI是微信浏览器，APP是APP，NATIVE是PC端
                if ($params['from'] == 'app')
                {
                        $tradeType = 'APP';
                }
                if ($params['from'] == 'wap')//手机浏览器
                {
                        $tradeType = 'WAP';
                }
                if (isset($params['type']) && $params['type'] == 'JSAPI')//微信浏览器
                {
                        $tradeType = 'JSAPI';
                        //获取用户openid
                        $tools = new JsApiPay();
                        $openId = $tools->GetOpenid();
                        if (empty($openId))
                        {
                                return FALSE;
//                                exit('操作失败，请重新扫描二维码');
                        }
                        $input->SetOpenid($openId);
                }
                if (isset($params['type']) && $params['type'] == 'NATIVE')
                {
                        $tradeType = 'NATIVE';
                }
                $input->SetBody($body); //商品描述
//                        $input->SetAttach("test");//附加数据
                $input->SetOut_trade_no($ordernum); //商户订单号
                $input->SetTotal_fee($params['reamount'] * 100); //总金额，单位：分
                $input->SetTime_start(date("YmdHis")); //订单生成时间
//                        $input->SetTime_expire(date("YmdHis", time() + 600)); //订单失效时间
//                        $input->SetGoods_tag("test");//商品标记
                $input->SetNotify_url($config->url . '/rechargeback/wxNotify'); //异步通知回调地址
                $input->SetTrade_type($tradeType); //交易类型
                $input->SetProduct_id($ordernum); //商品ID,trade_type=NATIVE，此参数必传。此id为二维码中包含的商品ID，商户自行定义。
                $result = WxPayApi::unifiedOrder($input);
                if (isset($params['type']) && $params['type'] == 'JSAPI')
                {
                        //JSAPI
                        $jsApiParameters = $tools->GetJsApiParameters($result);
                        //获取共享收货地址js函数参数
                        $editAddress = $tools->GetEditAddressParameters();
                        $link = array(
                                'jsApiParameters' => $jsApiParameters,
                                'editAddress' => $editAddress
                        );
                } else if (isset($params['type']) && $params['type'] == 'NATIVE')
                {
                        //NATIVE
                        $link = $result['code_url'];
                } else if (isset($params['type']) && $params['type'] == 'APP')
                {
                        //APP
                        $link = array(
                                'appid' => WxPayConfig::APPID,
                                'prepayid' => $result['prepay_id'],
                                'partnerid' => WxPayConfig::MCHID,
                                'package' => 'Sign=WXPay',
                                'noncestr' => $result['nonce_str'],
                                'timestamp' => time(),
                        );
                        //第二次签名
                        $creat = new WxPayDataBase();
                        $creat->SetValues($link);
                        $sign2 = $creat->MakeSign();
                        $link['sign'] = $sign2;
                } else
                {
                        $link = $result;
                }
                return $link;
        }

        /**
         * 支付宝支付
         * @param type $params
         * @param type $ordernum
         * @param type $subject
         * @param type $body
         * @return string
         */
        public function alipay($params, $ordernum, $subject, $body)
        {
                $config = getConfig();
                //支付宝充值
                require __DIR__ . '/../plugins/alipaydirect/alipay.config.php';
                require __DIR__ . '/../plugins/alipaydirect/lib/alipay_submit.class.php';
                //支付类型
                $payment_type = "1"; //必填，不能修改                                                
                $notify_url = $config->url . '/rechargeback/alipayNotify'; //服务器异步通知页面路径，需http://格式的完整路径，不能加?id=123这类自定义参数
                $return_url = $config->url . '/vip/index'; //页面跳转同步通知页面路径，需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
                $out_trade_no = $ordernum; //商户订单号，商户网站订单系统中唯一订单号，必填
                $total_fee = $params['reamount']; //付款金额，必填
                $show_url = ''; //商品展示地址，需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html
                $anti_phishing_key = ""; //防钓鱼时间戳，若要使用请调用类文件submit中的query_timestamp函数
                $exter_invoke_ip = ""; //客户端的IP地址，非局域网的外网IP地址，如：221.0.0.1
                //构造要请求的参数数组，无需改动
                $parameter = array(
                        'partner' => trim($alipay_config['partner']),
                        '_input_charset' => trim(strtolower($alipay_config['input_charset'])),
                        'notify_url' => $notify_url,
                        'out_trade_no' => $out_trade_no,
                        'subject' => $subject, //订单名称，必填
                        'payment_type' => $payment_type,
                        'total_fee' => $total_fee,
                        'body' => $body//订单描述
                );
                if ($params['from'] == 'app')
                {
                        //app里付款需要的参数
                        $parameter['service'] = 'mobile.securitypay.pay';
                        $parameter['sign_type'] = 'RSA';
                        $parameter['seller_id'] = trim($alipay_config['seller_id']);
                } else
                {
                        if (isset($params['isPhone']) && $params['isPhone'] == 1)
                        {
                                //手机网站支付
                                $parameter['service'] = 'alipay.wap.create.direct.pay.by.user';
                                $parameter['seller_id'] = trim($alipay_config['seller_id']);
                        } else
                        {
                                //即时到帐
                                $parameter['service'] = 'create_direct_pay_by_user';
                                $parameter['seller_email'] = trim($alipay_config['seller_email']);
                        }
                        $parameter['return_url'] = $return_url;
                        $parameter['show_url'] = $show_url;
                        $parameter['anti_phishing_key'] = $anti_phishing_key;
                        $parameter['exter_invoke_ip'] = $exter_invoke_ip;
                        $parameter['sign_type'] = $alipay_config['sign_type'];
                }

                //建立请求
                $alipaySubmit = new AlipaySubmit($alipay_config);

                if (isset($params['isPhone']) && $params['isPhone'] == 1)
                {
                        return $alipaySubmit->buildRequestForm($parameter, "get", "确认");
                }

                $link = $alipaySubmit->buildRequestParaToString($parameter);

                if ($params['from'] != 'app')
                {
                        $link = 'https://mapi.alipay.com/gateway.do?' . $link;
                }
                return $link;
        }

}
