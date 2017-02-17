<?php

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\BackNav;

class CommunicationController extends ControllerBase
{

        private $res;
        private $point = 'communication';

        public function initialize()
        {
                parent::initialize();
                $this->view->setMainView('admin');
                $this->tag->setTitle('友好');
        }

        public function indexAction()
        {
                $this->view->setMainView('admin');
                $this->tag->setTitle('友好');
                $nav = BackNav::findFirstByPoint($this->point);
                $data = $this->getTree($nav->id, 2);
                $this->view->setVar('data', $data);
        }

        public function socketsAction()
        {
                //服务器端代码 
                //确保在连接客户端时不会超时  
                set_time_limit(0);
                //设置IP和端口号  
                $address = "127.0.0.1";
                $port = 2046; //调试的时候，可以多换端口来测试程序！  
                /**
                 * 创建一个SOCKET  
                 * AF_INET=是ipv4 如果用ipv6，则参数为 AF_INET6 
                 * SOCK_STREAM为socket的tcp类型，如果是UDP则使用SOCK_DGRAM 
                 */
                $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("socket_create() 失败的原因是:" . socket_strerror(socket_last_error()) . "/n");
                //阻塞模式  
                socket_set_block($sock) or die("socket_set_block() 失败的原因是:" . socket_strerror(socket_last_error()) . "/n");
                //绑定到socket端口  
                $result = socket_bind($sock, $address, $port) or die("socket_bind() 失败的原因是:" . socket_strerror(socket_last_error()) . "/n");
                //开始监听  
                $result = socket_listen($sock, 4) or die("socket_listen() 失败的原因是:" . socket_strerror(socket_last_error()) . "/n");
                echo "OK\nBinding the socket on $address:$port ... ";
                echo "OK\nNow ready to accept connections.\nListening on the socket ... \n";
                do
                { // never stop the daemon  
                        //它接收连接请求并调用一个子连接Socket来处理客户端和服务器间的信息  
                        $msgsock = socket_accept($sock) or die("socket_accept() failed: reason: " . socket_strerror(socket_last_error()) . "/n");

                        //读取客户端数据  
                        echo "Read client data \n";
                        //socket_read函数会一直读取客户端数据,直到遇见\n,\t或者\0字符.PHP脚本把这写字符看做是输入的结束符.  
                        $buf = socket_read($msgsock, 8192);
                        echo "Received msg: $buf   \n";

                        //数据传送 向客户端写入返回结果  
                        $msg = "welcome \n";
                        socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) . "/n");
                        //一旦输出被返回到客户端,父/子socket都应通过socket_close($msgsock)函数来终止  
                        socket_close($msgsock);
                } while (true);
                socket_close($sock);
        }

        public function socketAction()
        {
                // 客户端代码 
                set_time_limit(0);
                $host = "127.0.0.1";
                $port = 2046;
                $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create  socket\n"); // 创建一个Socket  
                socket_set_nonblock($socket) or die("Unable to set nonblock on socket\n");
                while (!@socket_connect($socket, $host, $port))
                {
                        $err = socket_last_error($socket);
                        if ($err == 115 || $err == 114)
                        {
                                if ((time() - $time) >= $timeout)
                                {
                                        socket_close($socket);
                                        die("Connection timed out.\n");
                                }
                                sleep(1);
                                continue;
                        }
                        print_r(socket_strerror($err));
                }    //  连接  
                exit;
                socket_write($socket, "hello socket") or die("Write failed\n"); // 数据传送 向服务器发送消息  
                while ($buff = socket_read($socket, 1024, PHP_NORMAL_READ))
                {
                        echo("Response was:" . $buff . "\n");
                }
                socket_close($socket);



//                $timeout = 15;  //timeout in seconds
//                $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Unable to create socket\n");
//                socket_set_nonblock($socket) or die("Unable to set nonblock on socket\n");
//                $time = time();
//                while (!@socket_connect($socket, $host, $port))
//                {
//                        $err = socket_last_error($socket);
//                        if ($err == 115 || $err == 114)
//                        {
//                                if ((time() - $time) >= $timeout)
//                                {
//                                        socket_close($socket);
//                                        die("Connection timed out.\n");
//                                }
//                                sleep(1);
//                                continue;
//                        }
//                        die(socket_strerror($err) . "\n");
//                }
//
//                socket_set_block($this->socket) or die("Unable to set block on socket\n");
        }

}
