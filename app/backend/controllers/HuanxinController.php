<?php

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\BackNav;

class HuanxinController extends ControllerBase
{

        private $res;
        private $point = 'huanxin';

        public function initialize()
        {
                parent::initialize();
                $this->view->setMainView('admin');
                $this->tag->setTitle('友好');
                $this->res = array('code' => 1, 'errorMessage' => '上传失败');
        }

        public function indexAction()
        {
                $this->view->setMainView('admin');
                $this->tag->setTitle('友好');
                $nav = BackNav::findFirstByPoint($this->point);
                $data = $this->getTree($nav->id, 2);
                $this->view->setVar('data', $data);
        }

}
