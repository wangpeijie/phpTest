<?php

namespace Multiple\frontend\Controllers;

class IndexController extends ControllerBase
{

        public function initialize()
        {
                $this->tag->setTitle('友好');
        }

        public function indexAction()
        {
                
        }

        public function testAction()
        {
                //确保在连接客户端时不会超时
                set_time_limit(0);

                $ip = '127.0.0.1';
                $port = 1935;

                /**
                 * +-------------------------------
                 *    @socket通信整个过程
                 * +-------------------------------
                 *    @socket_create
                 *    @socket_bind
                 *    @socket_listen
                 *    @socket_accept
                 *    @socket_read
                 *    @socket_write
                 *    @socket_close
                 * +--------------------------------
                 */
                /* ----------------    以下操作都是手册上的    ------------------- */
                if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0)
                {
                        echo "socket_create() 失败的原因是:" . socket_strerror($sock) . "\n";
                }
                if (($ret = socket_bind($sock, $ip, $port)) < 0)
                {
                        echo "socket_bind() 失败的原因是:" . socket_strerror($ret) . "\n";
                }
                if (($ret = socket_listen($sock, 4)) < 0)
                {
                        echo "socket_listen() 失败的原因是:" . socket_strerror($ret) . "\n";
                }
                $count = 0;
                do
                {
                        if (($msgsock = socket_accept($sock)) < 0)
                        {
                                echo "socket_accept() failed: reason: " . socket_strerror($msgsock) . "\n";
                                break;
                        } else
                        {

                                //发到客户端
                                $msg = "测试成功！\n";
                                socket_write($msgsock, $msg, strlen($msg));

                                echo "测试成功了啊\n";
                                $buf = socket_read($msgsock, 8192);

                                $talkback = "收到的信息:$buf\n";
                                echo $talkback;

                                if (++$count >= 5)
                                {
                                        break;
                                }
                        }
                        echo $buf;
                        socket_close($msgsock);
                } while (true);

                socket_close($sock);
                exit;
        }

        public function test2Action()
        {
                $this->view->disable();
                $this->response->setContentType('application/json')->sendHeaders();
                require_once($this->config->application->pluginsDir . 'PHPExcel.php');
                if (!empty($_FILES['file']['name']))
                {
                        $tmp_file = $_FILES['file']['tmp_name'];
                        $file_types = explode(".", $_FILES['file']['name']);
                        $file_type = strtolower($file_types[count($file_types) - 1]);

//                        if (!in_array($file_type, ['xls', 'xlsx', 'csv']))
//                        {
//                                exit('不是Excel文件，重新上传');
//                        }

                        $savePath = 'web/excel/';

                        $str = date('Ymdhis') . rand(1000, 9999);
                        $file_name = $str . "." . $file_type;

//                        if(file_exists($tmp_file))
//                        {
//                                echo 'yes';
//                        }
                        if (!copy($tmp_file, $savePath . $file_name))
                        {
                                exit('上传失败1');
                        }
                }
                return echoSuccessMessage($savePath . $file_name);
        }

        public function qRcodeAction()
        {
                $this->view->disable();
                $this->view->setMainView(null);
                $content = $this->request->get('content');
                include __DIR__ . '/../plugins/phpqrcode.php';

                $errorCorrectionLevel = 'L'; //容错级别 
                $matrixPointSize = 5; //生成图片大小 
//生成二维码图片 
                QRcode::png($content, false, $errorCorrectionLevel, $matrixPointSize, 0);
        }

}
