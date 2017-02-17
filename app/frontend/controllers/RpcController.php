<?php
namespace Multiple\frontend\Controllers;
require dirname(__DIR__) . '/../library/phprpc/phprpc_server.php';

class RpcController extends ControllerBase
{

        private $notAllowMethods = ['initialize', 'indexAction', '__construct', 'setDI', 'getDI', 'setEventsManager', 'getEventsManager', '__get'];
        private $res;

        public function initialize()
        {
                $this->view->disable();
                $methods = get_class_methods($this);
                $allMethods = array_diff($methods, $this->notAllowMethods);
                $phprpc_server = new \PHPRPC_Server();
                $phprpc_server->add($allMethods, $this);
                $phprpc_server->setEnableGZIP(true);
                $phprpc_server->start();
                $this->res = array('code' => 1, 'errorMessage' => '请求失败');
        }

        public function indexAction()
        {
                
        }

        /**
         * 加密
         */
        public function encrypt($n)
        {
                $params = json_decode($n, TRUE);
                $password = $this->crypt->encryptBase64($params['password']);
                return echoSuccessMessage($password);
        }

        /**
         * 解密
         */
        public function decrypt($n)
        {
                $params = json_decode($n, TRUE);
                $password = $this->crypt->decryptBase64($params['password']);
                return echoSuccessMessage($password);
        }

        /**
         * 支付方式
         */
        public function getPayType()
        {
                $type = ['alipay' => array(
                                'id' => 1,
                                'name' => '支付宝',
                                'pic' => '/web/images/zhifubao.jpg',
                        ),
                        'weixin' => array(
                                'id' => 2,
                                'name' => '微信',
                                'pic' => '/web/images/weixinzhifu.jpg',
                        ),
//                        'unionpay' => array(
//                                'id' => 3,
//                                'name' => '银联',
//                                'pic' => '/web/images/yinlian.png',
//                        )
                ];
                return echoSuccessMessage($type);
        }

        /**
         * 产生订单号
         */
        public function createOrdernum()
        {
                $order = new YhOrder();
                $ordernum = $order->createOrder();
                return echoSuccessMessage($ordernum);
        }

        /**
         * 好友列表
         */
        public function friends()
        {
                $uid = $this->checkLogin();
                if (empty($uid))
                {
                        return echoErrorMessage('请先登录');
                }
                $sql = "select a.from_uid as id,b.name from yh_follow as a left join yh_user as b on a.from_uid=b.id where a.uid=:uid";
                $data = $this->db->fetchAll($sql, Phalcon\Db::FETCH_ASSOC, ['uid' => $uid]);
                return echoSuccessMessage($data);
        }

        /**
         * 判断是否支付成功
         */
        public function checkPay($n)
        {
                $uid = $this->checkLogin();
                if (empty($uid))
                {
                        return echoErrorMessage('请先登录');
                }
                $params = json_decode($n, TRUE);
                if ($params['type'] != 1)//活动和供求
                {
                        $info = YhOrder::findFirst([
                                        'columns' => 'status',
                                        'conditions' => 'orderNum=?1 and uid=?2 and aid=?3 and type=?4',
                                        'bind' => [1 => $params['orderNum'], 2 => $uid, 3 => $params['aid'], 4 => $params['type']]
                        ]);
                } else//vip
                {
                        $info = YhOrder::findFirst([
                                        'columns' => 'status',
                                        'conditions' => 'orderNum=?1 and uid=?2 and type=?4',
                                        'bind' => [1 => $params['orderNum'], 2 => $uid, 4 => $params['type']]
                        ]);
                }
                if ($info && $info->status == 0)
                {
                        return echoSuccessMessage(1);
                } else
                {
                        return echoSuccessMessage(2);
                }
        }

}
