<?php

namespace Multiple\Backend\Controllers;

header("Access-Control-Allow-Origin: *");
require __DIR__ . '/../../library/qiniu/vendor/autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
use Multiple\Backend\Models\BackNav;

class TimeController extends ControllerBase
{

        private $res;
        private $point = 'time';

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

        public function glDatePickerAction()
        {
                
        }

        public function datepickerAction()
        {
                
        }
        public function datetimepickerAction()
        {
                
        }

}
