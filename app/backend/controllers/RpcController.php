<?php
namespace Multiple\Backend\Controllers;
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

}
