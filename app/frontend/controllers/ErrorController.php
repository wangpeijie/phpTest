<?php
namespace Multiple\frontend\Controllers;
class ErrorController extends ControllerBase
{

        public function initialize()
        {
                echo '123';
//                parent::initialize();
        }

        public function indexAction()
        {
                $this->view->pick('/error/index');
        }

        /**
         * 输出错误
         * @param type $error
         */
        public function displayErrorAction($params)
        {
//                $params = json_decode($params, true);
//                $this->view->setParamToView('error', $params);
        }

}
