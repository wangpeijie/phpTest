<?php

namespace Multiple\Backend\Controllers;

header("Access-Control-Allow-Origin: *");
require __DIR__ . '/../../library/qiniu/vendor/autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
use Multiple\Backend\Models\BackNav;

class UploadController extends ControllerBase
{

        private $res;
        private $point = 'upload';

        public function initialize()
        {
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

        public function upAction()
        {
                $this->view->setMainView('admin');
                $type = $this->request->get('type');
                $this->view->setVar('type', $type);
        }

        public function fileAction()
        {
                $this->view->disable();
                $this->response->setContentType('application/json')->sendHeaders();
                if (!isset($_FILES['file']) || empty($_FILES['file']))
                {
                        $this->res['errorMessage'] = '上传文件不能为空';
                        echo json_encode($this->res, JSON_UNESCAPED_UNICODE);
                        exit;
                }
                $ext = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));
                if (!in_array($ext, (array) $this->config->fileext->pic))
                {
                        $this->res['errorMessage'] = '上传文件格式错误';
                        echo json_encode($this->res, JSON_UNESCAPED_UNICODE);
                        exit;
                } 
                if ($_FILES['file']['size'] > $this->config->uploadSize)
                {
                        $this->res['errorMessage'] = '单个文件不能大于1G';
                        echo json_encode($this->res, JSON_UNESCAPED_UNICODE);
                        exit;
                }

                $file = fopen($_FILES['file']['tmp_name'], 'rb');
                $data = fread($file, $_FILES['file']['size']);
                fclose($file);

                $auth = new Auth($this->config->upload->accessKey, $this->config->upload->secretKey);
                $token = $auth->uploadToken($this->config->upload->bucket);
                $uploadMgr = New UploadManager();
                $key = time() . rand(11111, 99999) . '.' . $ext;
                list($ret, $err) = $uploadMgr->put($token, $key, $data, array());
                if ($err !== null)
                {
                        $this->res['errorMessage'] = '文件上传失败';
                } else
                {
                        $this->res['code'] = 0;
                        $this->res['errorMessage'] = '';
                        $this->res['data'] = array(
                                'file' => $key,
                                'path' => $this->config->upload->url . $key,
                                'size' => $_FILES['file']['size'],
                                'ret' => $ret,
                                'err' => $err
                        );
                }
                echo json_encode($this->res, JSON_UNESCAPED_UNICODE);
        }

        public function resourcesAction()
        {
                $this->view->disable();
                $this->response->setContentType('application/json')->sendHeaders();
//                if (!isset($_FILES['file']) || empty($_FILES['file']))
//                {
//                        $this->res['errorMessage'] = '上传文件不能为空';
//                        echo json_encode($this->res, JSON_UNESCAPED_UNICODE);
//                        exit;
//                }
//                $ext = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));
//                if (!in_array($ext, (array) $this->config->fileext->resources))
//                {
//                        $this->res['errorMessage'] = '上传文件格式错误';
//                        echo json_encode($this->res, JSON_UNESCAPED_UNICODE);
//                        exit;
//                } else if ($_FILES['file']['size'] > $this->config->uploadSize * 10)
//                {
//                        $this->res['errorMessage'] = '单个文件不能大于50MB';
//                        echo json_encode($this->res, JSON_UNESCAPED_UNICODE);
//                        exit;
//                }

                $file = fopen($_FILES['file']['tmp_name'], 'rb');
                $data = fread($file, $_FILES['file']['size']);
                fclose($file);

                $auth = new Auth($this->config->upload->accessKey, $this->config->upload->secretKey);
                $token = $auth->uploadToken($this->config->upload->bucket);
                $uploadMgr = New UploadManager();
//                $key = time() . rand(11111, 99999) . '.' . $ext;
                list($ret, $err) = $uploadMgr->put($token, null, $data, array());
                if ($err !== null)
                {
                        $this->res['errorMessage'] = '文件上传失败';
                } else
                {
                        $this->res['code'] = 0;
                        $this->res['errorMessage'] = '';
                        $this->res['data'] = array(
                                'file' => $key,
                                'path' => $this->config->upload->url . $key,
                                'size' => $_FILES['file']['size']
                        );
                }
                echo json_encode($this->res, JSON_UNESCAPED_UNICODE);
        }

        /**
         * 上传到本地
         */
        public function localAction()
        {
                $this->view->disable();
                $this->response->setContentType('application/json')->sendHeaders();
                if (!empty($_FILES['file']['name']))
                {
                        $tmp_file = $_FILES['file']['tmp_name'];
                        if (!file_exists($tmp_file))
                        {
                                return echoErrorMessage('请选择上传文件');
                        }
                        $file_types = explode(".", $_FILES['file']['name']);
                        $file_type = strtolower($file_types[count($file_types) - 1]);

//                        if (!in_array($file_type, ['xls', 'xlsx', 'csv']))
//                        {
//                                return echoErrorMessage('不是Excel文件，重新上传');
//                        }
                        $url = $this->request->get('url');
                        $savePath = (isset($url) && $url) ? $url : 'web/excel/';

                        $str = date('Ymdhis') . rand(1000, 9999);
                        $file_name = $str . "." . $file_type;

                        if (!copy($tmp_file, $savePath . $file_name))
                        {
                                return echoErrorMessage('上传失败');
                        }
                }
                return echoSuccessMessage($savePath . $file_name);
        }

        public function getAllFileAction()
        {
                $this->view->setMainView('admin');
                $auth = new Auth($this->config->upload->accessKey, $this->config->upload->secretKey);
                $bucketMgr = new BucketManager($auth);
                $prefix = ''; //前缀
                $marker = '';
                $limit = 10000;
                list($iterms, $marker, $err) = $bucketMgr->listFiles($this->config->upload->bucket, $prefix, $marker, $limit);
                if ($err !== null)
                {
                        echo "\n====> list file err: \n";
                        var_dump($err);
                } else
                {
                        $this->view->setVars(['data' => $iterms]);
                }
        }
        
        /**
         * 获取上传token
         */
        public function uptokenAction()
        {
                $this->view->disable();
                $this->response->setContentType('application/json')->sendHeaders();
                $bucket = $this->config->upload->bucket;
                $auth = new Auth($this->config->upload->accessKey, $this->config->upload->secretKey);
                $token = $auth->uploadToken($bucket, null, 3600 * 24, []);
                echo json_encode(array('uptoken' => $token));
        }

}
