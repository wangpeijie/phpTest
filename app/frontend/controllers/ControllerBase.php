<?php
namespace Multiple\frontend\Controllers;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

        protected function initialize()
        {

        }

        protected function getUserInfo($login = TRUE)
        {
                $uid = $this->checkLogin();
                if (!$uid && $login)
                {
                        header('location:/login/index');
                        return false;
                }

                $userInfo = YhUser::findFirst([
                                "columns" => "id,name,face,account,mobile,open,status",
                                "conditions" => "id = ?1",
                                "bind" => [1 => $uid],
                ]);
                if ($userInfo->status != 1)
                {
                        $this->session->remove('yh_user');
                        $this->gotoLogin();
                        exit;
                }
                return $userInfo ? $userInfo->toArray() : [];
        }

        /**
         * 验证数据登录
         * @param type $params
         * @return type
         */
        public function checkLogin($params = '')
        {
                if (isset($_COOKIE['uid']) && $_COOKIE['uid'] && isset($_COOKIE['timestamp']) && $_COOKIE['timestamp'] && isset($_COOKIE['token']) && $_COOKIE['token'])
                {
                        //app端
                        $cookie = ['uid' => $_COOKIE['uid'], 'timestamp' => $_COOKIE['timestamp']];
                        $token = getSign($cookie);
                        if ($_COOKIE['token'] != $token)
                        {
                                return FALSE;
                        }
                        return $_COOKIE['uid'];
                } else
                {
                        if ($params && !is_json($params))
                        {
                                exit('错误的请求');
                        }
                        $user = $this->session->get('yh_user');
                        return $user? : FALSE;
                }
        }

        /**
         * 输出错误
         * @param type $error
         * @return boolean
         */
        public function displayError($error)
        {
                $this->dispatcher->forward([
                        'controller' => 'error',
                        'action' => 'displayError',
                        'params' => [$error]
                ]);
                return false;
        }

        /**
         * 跳转到登录
         * @return boolean
         */
        public function gotoLogin()
        {
                $this->dispatcher->forward(array(
                        'controller' => 'index',
                        'action' => 'index'
                ));
                return false;
        }

}
