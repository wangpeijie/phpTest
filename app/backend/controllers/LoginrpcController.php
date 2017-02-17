<?php

namespace Multiple\Backend\Controllers;

require dirname(__DIR__) . '/../library/phprpc/phprpc_server.php';

class LoginrpcController extends ControllerBase
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
         * 登录
         */
        public function login($param)
        {
                $n = json_decode($param, TRUE);
                $info = \Multiple\Backend\Models\Manager::findFirstByAccount($n['account']);
                if (empty($info))
                {
                        return echoErrorMessage('该帐号不存在');
                } elseif (!password_verify($n['password'], $info->password))
                {
                        return echoErrorMessage('密码不正确');
                }
                $this->session->set('laowang_user', $info->id);
                return echoSuccessMessage();
        }

}
