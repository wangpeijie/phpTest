<?php

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\BackNav;

class CityController extends ControllerBase
{

        private $res;
        private $point = 'city';

        public function initialize()
        {
                $this->view->setMainView('admin');
        }

        public function indexAction()
        {
                $this->view->setMainView('admin');
                $this->tag->setTitle('友好');
                $nav = BackNav::findFirstByPoint($this->point);
                $data = $this->getTree($nav->id, 2);
                $this->view->setVar('data', $data);
        }

        public function test1Action()
        {
                
        }

        public function baiduAction()
        {
                
        }

        public function gaodeAction()
        {
                
        }

        public function googleAction()
        {
                
        }

}
