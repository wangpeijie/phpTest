<?php

require dirname(__DIR__) . '/library/phprpc/phprpc_server.php';

class ViprpcController extends ControllerBase
{

        private $notAllowMethods = ['initialize', 'indexAction', '__construct', 'setDI', 'getDI', 'setEventsManager', 'getEventsManager', '__get'];
        private $res;

        public function initialize()
        {
                //$this->checkLogin();
                $this->view->disable();
                $methods = get_class_methods($this);
                $allMethods = array_diff($methods, $this->notAllowMethods);
                $phprpc_server = new PHPRPC_Server();
                $phprpc_server->add($allMethods, $this);
                $phprpc_server->setEnableGZIP(true);
                $phprpc_server->start();
                $this->res = array('code' => 1, 'errorMessage' => '请求失败');
        }

        public function indexAction()
        {
                
        }

        /**
         * 获取微信支付链接
         * @return type
         */
        public function vip($n)
        {
                $uid = $this->checkLogin($n);
                if (empty($uid))
                {
                        return echoErrorMessage('请先登录');
                }
                $params = json_decode($n, TRUE);
                $params['from'] = (isset($params['from']) && $params['from']) ? $params['from'] : 'pc';
                $params['paytype'] = (isset($params['paytype']) && $params['paytype']) ? $params['paytype'] : 2; //2 = 微信
                $ordernum = $params['orderNum'];
                //是否存在该分类
                $time = time();
                $id = intval(['cid']);
                if (empty($id))
                {
                        return echoErrorMessage('请选择时间');
                }
                $class = YhVipClass::findFirstById($id);
                if (empty($class))
                {
                        return echoErrorMessage('操作失败');
                }
                $order = YhOrder::findFirst([
                                'conditions' => 'orderNum = :ordernum: and uid=:uid: and type=1',
                                'bind' => ['ordernum' => $ordernum, 'uid' => $uid]
                ]);
                //是否已付款
                if ($order && $order->status == 1)
                {
                        return echoErrorMessage('订单已付款');
                } elseif (!$order)
                {
                        $order = new YhOrder();
                }

                $userInfo = YhUser::findFirstById($uid);
                if ($userInfo->is_vip == 1 && $userInfo->expiration >= $time)
                {
                        $params['expiration'] = mktime(0, 0, 0, date('m', $userInfo->expiration) + $class->month, date('d', $userInfo->expiration), date('Y', $userInfo->expiration));
                } else
                {
                        $params['expiration'] = mktime(0, 0, 0, date('m') + $class->month, date('d'), date('Y'));
                }
                $para = array(
                        'uid' => $uid,
                        'orderNum' => $ordernum,
                        'expiration' => $params['expiration'],
                        'messages_num' => $class->messages_num,
                        'secretary_num' => $class->secretary_num,
                        'amount' => $class->amount,
                        'paytype' => (int) $params['paytype'], //1 = 支付宝\2 = 微信\3 = 银联\其他未定
                        'status' => 0, //0:未成功；1：成功
                        'addtime' => $time,
                        'type' => 1,
                        'vip_classid' => $id,
                );
                $result = $order->save($para);
                if (!$result)
                {
                        return echoErrorMessage('操作失败');
                }
                $subject = '支付：' . $ordernum; //标题
                $body = $subject; //描述
                $ordernumNew = $ordernum . rand(10000, 99999);
                $params['amount'] = $params['reamount'] = 0.01;

                $dataPara = array(
                        'uid' => $uid,
                        'from' => $params['from'],
                        'title' => $subject,
                        'total' => $params['reamount'],
                        'amount' => $params['amount'],
                        'ordernum' => $ordernum,
                        'type' => 'vip'
                );

                $this->redis->setex('yhPaymentOrder:' . $ordernumNew, 3600 * 4, json_encode($dataPara));
                if ($params['from'] != 'pc')
                {
                        return echoSuccessMessage();
                }
                $res = $this->getPayUrl($params, $ordernumNew, $subject, $body);
                return $res ? echoSuccessMessage($res) : echoErrorMessage('操作失败');
        }

        /**
         * 短信和小秘书充值
         */
        public function smsSecAction()
        {
                $uid = $this->checkLogin($n);
                if (empty($uid))
                {
                        return echoErrorMessage('请先登录');
                }
                $params = json_decode($n, TRUE);

                $num = intval($params['num']);
                if ($num <= 0)
                {
                        return echoErrorMessage('数量不能小于0');
                }
                $ordernum = $params['orderNum'];
                $type = $params['type']; //1:短信，2：小秘书

                $params['from'] = isset($params['from']) ? $params['from'] : 'pc';
                $params['paytype'] = isset($params['paytype']) ? $params['paytype'] : 2; //1 = 支付宝,2 = 微信,3 = 银联

                $time = time();
                $userInfo = YhUser::findFirstById($uid);
                if ($userInfo->is_vip == 0 || ($userInfo->is_vip == 1 && $userInfo->expiration <= $time))
                {
                        return echoErrorMessage('请先充值会员');
                }

                $sysSet = YhSystemSet::findFirst();
                $amount = $type == 1 ? $sysSet->sms_price * $num : $sysSet->secretary_price * $num;
                $order = YhOrder::findFirst([
                                'conditions' => 'orderNum = :ordernum: and uid=:uid: and type=1',
                                'bind' => ['ordernum' => $ordernum, 'uid' => $uid]
                ]);

                //是否已付款
                if ($order && $order->status == 1)
                {
                        return echoErrorMessage('订单已付款');
                } elseif (!$order)
                {
                        $order = new YhOrder();
                }
                $para = array(
                        'uid' => $uid,
                        'orderNum' => $ordernum,
                        'amount' => $amount,
                        'paytype' => (int) $params['paytype'], //1 = 支付宝\2 = 微信\3 = 银联\其他未定
                        'status' => 0, //0:未成功；1：成功
                        'addtime' => $time,
                        'type' => $type == 1 ? 5 : 6,
                );
                if ($type == 1)
                {
                        $para['messages_num'] = $userInfo->messages_num + $num;
                        $para['secretary_num'] = 0;
                } else
                {
                        $para['messages_num'] = 0;
                        $para['secretary_num'] = $userInfo->secretary_num + $num;
                }
                $result = $order->save($para);
                if (!$result)
                {
                        return echoErrorMessage('操作失败');
                }
                $subject = '支付：' . $ordernum; //标题
                $body = $subject; //描述
                $ordernumNew = $ordernum . rand(10000, 99999);
                $params['amount'] = $params['reamount'] = 0.01;

                $dataPara = array(
                        'uid' => $uid,
                        'from' => $params['from'],
                        'title' => $subject,
                        'total' => $params['reamount'],
                        'amount' => $params['amount'],
                        'ordernum' => $ordernum,
                        'type' => $type == 1 ? 'sms' : 'secretary'
                );

                $this->redis->setex('yhPaymentOrder:' . $ordernumNew, 3600 * 4, json_encode($dataPara));
                if ($params['from'] != 'pc')
                {
                        return echoSuccessMessage();
                }
                $res = $this->getPayUrl($params, $ordernumNew, $subject, $body);
                return $res ? echoSuccessMessage($res) : echoErrorMessage('操作失败');
        }

        /**
         * 活动支付
         */
        public function activity($n)
        {
                $uid = $this->checkLogin($n);
                if (empty($uid))
                {
                        return echoErrorMessage('请先登录');
                }
                $params = json_decode($n, TRUE);
                $this->pay($params, 'activity', $uid);
        }

        /**
         * 供求支付
         */
        public function supply($n)
        {
                $uid = $this->checkLogin($n);
                if (empty($uid))
                {
                        return echoErrorMessage('请先登录');
                }
                $params = json_decode($n, TRUE);
                $this->pay($params, 'supply', $uid);
        }

        /**
         * 支付
         */
        private function pay($params, $type, $uid)
        {
                $ordernum = $params['orderNum'];

                $params['from'] = isset($params['from']) ? $params['from'] : 'pc';
                $params['paytype'] = isset($params['paytype']) ? $params['paytype'] : 2; //1 = 支付宝,2 = 微信,3 = 银联

                $time = time();

                $order = YhOrder::findFirst([
                                'conditions' => 'orderNum = :ordernum: and uid=:uid: and type=:type:',
                                'bind' => ['ordernum' => $ordernum, 'uid' => $uid, 'type' => $type == 'activity' ? 2 : 3]
                ]);
                //是否已付款
                if ($order && $order->status == 1)
                {
                        return echoErrorMessage('该订单已付款');
                } elseif (!$order)
                {
                        $order = new YhOrder();
                }

                if ($type == 'activity')
                {
                        $info = YhActivity::findFirstById($params['aid']);
                } else
                {
                        $info = YhSupply::findFirstById($params['aid']);
                }

                if (empty($info->area))
                {
                        return echoErrorMessage('您尚未设置置顶位置');
                }
                $area = json_decode($info->area, TRUE);
                if ($area[0]['province'] == 0)
                {
                        $areaids = YhAreas::find([
                                        'conditions' => 'type=2 and parent_id!=35'
                                ])->toArray();
                        $ids = array_column($areaids, 'id');
                } else
                {
                        $provinceId = [];
                        $cityId = [];
                        $cityIds = [];
                        foreach ($area as $value)
                        {
                                if ($value['city'] == 0)
                                {
                                        $provinceId[] = $value['province'];
                                } else
                                {
                                        $cityId[] = $value['city'];
                                }
                        }
                        if ($provinceId)
                        {
                                $areaids = YhAreas::find([
                                                'conditions' => 'parent_id in(' . implode(',', $provinceId) . ')'
                                        ])->toArray();
                                $cityIds = array_column($areaids, 'id');
                        }
                        $ids = array_merge($cityId, $cityIds);
                }
                if (empty($ids))
                {
                        return echoErrorMessage('置顶位置必须精确到市');
                }
                $sql2 = 'select count(id) as count from yh_user where area_city in(' . implode(',', $ids) . ')';
                $count = $this->db->fetchOne($sql2, Phalcon\Db::FETCH_ASSOC, array('ordernum' => $ordernum, 'uid' => $uid));
                $personNum = $count['count'];
                //人数为0时直接插入置顶表
                if (($personNum > 0) == FALSE)
                {
                        $top = new YhTop();
                        $para = [
                                'uid' => $uid,
                                'aid' => $info->id,
                                'type' => $type == 'activity' ? 1 : 2,
                                'start_time' => $time,
                                'end_time' => $time,
                                'area_ids' => implode(',', $ids),
                                'amount' => 0.00,
                                'addtime' => $time
                        ];
                        $res = $top->save($para);
                        $res2 = $info->save(['is_top' => 1]);
                        if ($res && $res2)
                        {
                                return echoSuccessMessage();
                        }
                        return echoErrorMessage('交易失败');
                }
                //计算金额
                $priceInfo = YhAdPrice::findFirstByType('top');
                $price = $priceInfo->amount; //单价
                $params['amount'] = $price * $info->top_time * $personNum;
                $para = array(
                        'uid' => $uid,
                        'aid' => $params['aid'],
                        'type' => $type == 'activity' ? 2 : 3,
                        'orderNum' => $ordernum,
                        'amount' => $params['amount'],
                        'paytype' => (int) $params['paytype'], //1 = 支付宝\2 = 微信\3 = 银联\其他未定
                        'status' => 0, //0:未成功；1：成功
                        'addtime' => $time,
                );
                $result = $order->save($para);
                if (!$result)
                {
                        return echoErrorMessage('网络请求失败');
                }
                $subject = '支付：' . $ordernum; //标题
                $body = $subject; //描述
                $ordernumNew = $ordernum . rand(10000, 99999);
                $params['amount'] = $params['reamount'] = 0.01;

                $dataPara = array(
                        'uid' => $uid,
                        'from' => $params['from'],
                        'ordernum' => $ordernum,
                        'type' => $type//activity or supply
                );

                $this->redis->setex('yhPaymentOrder:' . $ordernumNew, 3600 * 4, json_encode($dataPara));
                if ($params['from'] != 'pc')
                {
                        return echoSuccessMessage();
                }
                $res = $this->getPayUrl($params, $ordernumNew, $subject, $body);
                return $res ? echoSuccessMessage() : echoErrorMessage('操作失败');
        }

        /**
         * 广告支付
         */
        public function adAction()
        {
                $ordernum = $this->request->get('orderNum', 'string');
                $params['paytype'] = $this->request->get('paytype');
                $params['isPhone'] = $this->request->get('isPhone');

                $params['from'] = isset($params['from']) ? $params['from'] : 'pc';
                $params['paytype'] = isset($params['paytype']) ? $params['paytype'] : 2; //1 = 支付宝,2 = 微信,3 = 银联
                //是否登录
                $uid = $this->checkLogin();
                if (!$uid)
                {
                        return $this->gotoLogin();
                }
                //是否存在该分类
                $time = time();
                $order = YhOrder::findFirst([
                                'conditions' => 'orderNum = :ordernum: and uid=:uid: and type=4',
                                'bind' => ['ordernum' => $ordernum, 'uid' => $uid]
                ]);

                //是否已付款
                if ($order && $order->status == 1)
                {
                        echo '该订单已付款';
                        sleep(3);
                        header('location:/ad/index');
                        exit;
                } elseif (!$order)
                {
                        $order = new YhOrder();
                }
                $para = array(
                        'uid' => $uid,
                        'type' => 4,
                        'orderNum' => $ordernum,
                        'amount' => $params['amount'],
                        'paytype' => (int) $params['paytype'], //1 = 支付宝\2 = 微信\3 = 银联\其他未定
                        'status' => 0, //0:未成功；1：成功
                        'addtime' => $time,
                );
                $result = $order->save($para);
                if (!$result)
                {
                        exit('网络请求失败');
                }
                $subject = '支付：' . $ordernum; //标题
                $body = $subject; //描述
                $ordernumNew = $ordernum . rand(10000, 99999);
                $params['amount'] = $params['reamount'] = 0.01;
                $dataPara = array(
                        'uid' => $uid,
                        'from' => $params['from'],
                        'ordernum' => $ordernum,
                        'type' => 'ad'
                );
                $this->redis->setex('yhPaymentOrder:' . $ordernumNew, 3600 * 4, json_encode($dataPara));
                $this->getPayUrl($params, $ordernumNew, $subject, $body);
        }

        /**
         * 支付链接
         * @param type $params
         * @param type $ordernumNew
         * @param type $subject
         * @param type $body
         */
        private function getPayUrl($params, $ordernumNew, $subject, $body)
        {
                include __DIR__ . '/../plugins/pay.class.php';
                $pay = new Pay();
                switch ($params['paytype'])
                {
                        case 1:
                                //支付宝充值
                                $list = $pay->alipay($params, $ordernumNew, $subject, $body);
                                break;
                        case 2:
                                //微信支付
                                if (isset($params['isWx']) && $params['isWx'] == 1)
                                {
                                        $params['type'] = 'JSAPI';
                                        $result = $pay->wxpay($params, $ordernumNew, $body);
                                        if (!isset($result['link']['jsApiParameters']) || empty($result['link']['jsApiParameters']))
                                        {
                                                return FALSE;
                                        }
                                        $jsApiParameters = $result['link']['jsApiParameters'];
                                        $editAddress = $result['link']['editAddress'];
                                        $list = ['total_fee' => $params['reamount'], 'jsApiParameters' => $jsApiParameters, 'editAddress' => $editAddress, 'redirectUrl' => 'person/rechargeRecord'];
                                } else
                                {
                                        $params['type'] = $params['from'] == 'pc' ? 'NATIVE' : 'APP';
                                        $list2 = $pay->wxpay($params, $ordernumNew, $body);
                                        $list = $list2['link'];
                                }
                                break;
                        case 3:
                                //银联充值
                                $list = $pay->unionpay($params, $ordernumNew);
                        default:
                                break;
                }
                return $list;
        }

}
