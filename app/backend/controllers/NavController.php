<?php

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\Nav;
use Multiple\Backend\Models\BackNav;

class NavController extends ControllerBase
{

        public function initialize()
        {
                parent::initialize();
                $this->view->setMainView('admin');
                $this->tag->setTitle('友好');
        }

        public function indexAction()
        {
                
        }

        public function frontAction()
        {
                $data = $this->getTree(0, 1);
                $this->view->setVar('data', $data);
        }

        public function backAction()
        {
                $data = $this->getTree(0, 2);
                $this->view->setVar('data', $data);
        }

        public function addNavAction()
        {
                $model = $this->request->get('model');
                $id = $this->request->get('id'); //编辑
                $pid = $this->request->get('pid'); //添加
                if (isset($id) && $id)
                {
                        if ($model == 1)
                        {
                                $info = Nav::findFirstById($id)->toArray();
                        } else
                        {
                                $info = BackNav::findFirstById($id)->toArray();
                        }
                        $this->view->setVars(['data' => $info, 'id' => $id]);
                } else
                {
                        $pid = (isset($pid) && $pid) ? $pid : 0;
                        $this->view->setVar('pid', $pid);
                }
                $this->view->setVar('model', $model);
        }

}
