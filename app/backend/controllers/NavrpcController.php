<?php

namespace Multiple\Backend\Controllers;

require dirname(__DIR__) . '/../library/phprpc/phprpc_server.php';

use Multiple\Backend\Models\Nav;
use Multiple\Backend\Models\BackNav;

class NavrpcController extends ControllerBase
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
         * 菜单
         */
        public function nav($param)
        {
                $uid = $this->checkLogin($param);
                if (empty($uid))
                {
                        return echoErrorMessage('请先登录');
                }
                $n = json_decode($param, TRUE);
                if ($n['model'] == 1)
                {
                        if ($n['id'])
                        {
                                $info = Nav::findFirstById($n['id']);
                                unset($n['id']);
                        } else
                        {
                                $info = new Nav();
                        }
                        $n['taix'] = Nav::getMaxTaix();
                } else
                {
                        if ($n['id'])
                        {
                                $info = BackNav::findFirstById($n['id']);
                                unset($n['id']);
                        } else
                        {
                                $info = new BackNav();
                        }
                        $n['taix'] = BackNav::getMaxTaix();
                }
                $res = $info->save($n);
                return $res ? echoSuccessMessage() : echoErrorMessage('操作失败');
        }

        /**
         * 删除
         */
        public function delNav($param)
        {
                $uid = $this->checkLogin($param);
                if (empty($uid))
                {
                        return echoErrorMessage('请先登录');
                }
                $n = json_decode($param, TRUE);
                $ids = is_array($n['id']) ? $n['id'] : explode(',', $n['id']);
                if ($n['model'] == 1)
                {
                        $res = Nav::find(['conditions' => 'id in(' . implode(',', $ids) . ')'])->delete();
                } else
                {
                        $res = BackNav::find(['conditions' => 'id in(' . implode(',', $ids) . ')'])->delete();
                }
                return $res ? echoSuccessMessage() : echoErrorMessage('操作失败');
        }

}
