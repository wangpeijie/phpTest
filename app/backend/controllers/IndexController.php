<?php

namespace Multiple\Backend\Controllers;

class IndexController extends ControllerBase
{

        public function initialize()
        {
                parent::initialize();
                $this->view->setMainView('admin');
                $this->tag->setTitle('友好');
        }

        public function indexAction()
        {
//                $a = ['1,2,3','1,2,3'];
//                print_r(implode(',', $a));exit;
//                print_r(base64_encode('1324564'));
//                $str = '>nice</script>';
//                print_r($this->clean_xss($str));
        }

        function clean_xss($data, $strip_tags = 0)
        {
                $str = trim($data);  //清理空格
                if ($strip_tags)
                {
                        $str = strip_tags($str);   //过滤html标签
                }
                return addslashes(htmlspecialchars($str, ENT_QUOTES)); //将字符内容转化为html实体
        }

        public function testAction()
        {
        }

        public function loginAction()
        {
                
        }

        public function testTwoAction()
        {
                header('location:/admin/index/login');
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
