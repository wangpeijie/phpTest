<?php

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\BackNav;

class EditorController extends ControllerBase
{

        private $res;
        private $point = 'editor';

        public function initialize()
        {
                $this->view->setMainView('admin');
        }

        public function indexAction()
        {
                $this->tag->setTitle('友好');
                $nav = BackNav::findFirstByPoint($this->point);
                $data = $this->getTree($nav->id, 2);
                $this->view->setVar('data', $data);
        }

        public function kindEditorAction()
        {
                
        }

        public function ueditorAction()
        {
                
        }
        public function codemirrorAction()
        {
                
        }

}
