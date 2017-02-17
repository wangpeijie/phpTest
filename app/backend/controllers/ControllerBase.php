<?php

namespace Multiple\Backend\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

        protected function initialize()
        {
                $this->tag->prependTitle('laowang-');
                $user = $this->checkLogin();
                if (empty($user) && $this->request->get('_url') !== '/admin/login/login')
                {
                        header('location:/admin/login/login');
                }
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
        public function checkLogin($param = '')
        {
                if ($param && !is_json($param))
                {
                        exit('错误的请求');
                }
                $user = $this->session->get('laowang_user');
                return $user? : FALSE;
        }

        /**
         * 输出错误
         * @param type $error(json格式)
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

        /**
         * 跳转div
         */
        public function urlDiv($tip, $url, $n = 3, $title = '标题')
        {
                echo '<link href="/admin/css/url.css" type="text/css" rel="stylesheet">
                        <script src="/admin/js/jquery-1.9.1.js" type="text/javascript"></script>
                        <div class="dialog">
                                <div class="content">
                                        <div class="head">' . $title . '</div>
                                        <div class="body">
                                                <div class="tip">' . $tip . '</div>
                                                <div class="second"><span>' . $n . '</span>秒灵敏</div>
                                        </div>
                                </div>
                        </div>
                        <script src="/admin/js/url.js" type="text/javascript"></script>';
                exit;
        }

        protected function getTree($parentId, $type)
        {
                if ($type == 1)
                {
                        $data = \Multiple\Backend\Models\Nav::findByParent_id($parentId)->toArray();
                } else
                {
                        $data = \Multiple\Backend\Models\BackNav::findByParent_id($parentId)->toArray();
                }
                foreach ($data as $key => $value)
                {
                        $data[$key]['child'] = $this->getTree($value['id'], $type);
                }
                return $data;
        }

}
