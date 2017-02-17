<?php

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\BackNav;

class CarouselController extends ControllerBase
{

        private $res;
        private $point = 'carousel';

        public function initialize()
        {
                parent::initialize();
                $this->view->setMainView('admin');
                $this->tag->setTitle('友好');
                $this->res = array('code' => 1, 'errorMessage' => '上传失败');
        }

        public function indexAction()
        {
                $nav = BackNav::findFirstByPoint($this->point);
                $data = $this->getTree($nav->id, 2);
                $this->view->setVar('data', $data);
        }

        public function galleriaAction()
        {
                
        }

        public function nivoSliderAction()
        {
                
        }

        public function swiperAction()
        {
                
        }

        public function ckplayerAction()
        {
                
        }

        public function videoAction()
        {
                
        }

        public function liveAction()
        {
                $token = curlGet('http://live.videojj.com/api/users/1/token?platformId=574d758684a65443004f3946&secret=H1svNoFux');
                $data = json_decode($token, TRUE);
                $this->view->setVar('token', $data['result']['token']);
        }

}
