<?php

namespace Multiple\Backend\Controllers;

class CommenController extends ControllerBase
{

        public function initialize()
        {
                parent::initialize();
                $this->tag->setTitle('友好');
        }
        
//        public function headerAction()
//        {
//                $this->view->setVar('data', 'data');
//        }

}
