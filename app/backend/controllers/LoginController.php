<?php

namespace Multiple\Backend\Controllers;

class LoginController extends ControllerBase
{

        public function initialize()
        {
                $this->view->setMainView('admin');
//                parent::initialize();
                $this->tag->setTitle('友好');
        }

        public function indexAction()
        {
                
        }

        /**
         * 登录
         */
        public function loginAction()
        {
                
        }

        /**
         * 退出
         */
        public function loginOutAction()
        {
                $this->session->remove('laowang_user');
                header('location:/admin/login/login');
        }

        /**
         * 生成二维码
         */
        public function qRcodeAction()
        {
                $this->view->disable();
                $this->view->setMainView(null);
                $content = $this->request->get('content');
                include __DIR__ . '/../../plugins/phpqrcode.php';

                $errorCorrectionLevel = 'L'; //容错级别 
                $matrixPointSize = 5; //生成图片大小 
                //生成二维码图片 
                \QRcode::png($content, false, $errorCorrectionLevel, $matrixPointSize, 0);
        }

}
