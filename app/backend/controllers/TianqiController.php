<?php

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\BackNav;

class TianqiController extends ControllerBase
{

        private $res;
        private $point = 'tianqi';

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

        public function tianqiAction()
        {
                header("Content-Type:text/javascript;charset=utf-8");
                $city = $this->getCity($this->getIP()); //这里获取的$city为城市名，例如：郑州市
                //由于中国天气网提供的数据城市名称不带'市'这个字。所以去掉 这里是基于utf-8编码 一个汉字三个字符
                $city = substr($city, 0, -3);
                //此时的$city 以郑州市为例，则显示为：郑州
                /**
                  这里 需要连接数据库
                  根据上面的$city查询城市ID号 这里省略
                  如：查询'郑州'对应的城市id号 并且返回 城市ID号 $areaid
                 * */
                //假设查询的是郑州的城市id号
                $areaid = 101180101;
                //指数index 实况observe  预警alarm 常规预报（24小时）forecastld  四个参数根据需要选择
                $type = observe;
                $weatherInfo = $this->getWeather($areaid, $type);
                echo $weatherInfo;
        }

        /**
         * 获取用户真实 IP
         * */
        private function getIP()
        {
                static $realip;
                if (isset($_SERVER))
                {
                        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                        {
                                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                        } else if (isset($_SERVER["HTTP_CLIENT_IP"]))
                        {
                                $realip = $_SERVER["HTTP_CLIENT_IP"];
                        } else
                        {
                                $realip = $_SERVER["REMOTE_ADDR"];
                        }
                } else
                {
                        if (getenv("HTTP_X_FORWARDED_FOR"))
                        {
                                $realip = getenv("HTTP_X_FORWARDED_FOR");
                        } else if (getenv("HTTP_CLIENT_IP"))
                        {
                                $realip = getenv("HTTP_CLIENT_IP");
                        } else
                        {
                                $realip = getenv("REMOTE_ADDR");
                        }
                }
                return $realip;
        }

        /**
         * 获取 IP  地理位置
         * 基于淘宝IP接口获取地理位置
         * @Return: array
         * */
        private function getCity($ip)
        {
                $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
                $ip = json_decode(file_get_contents($url));
                if ((string) $ip->code == '1')
                {
                        return false;
                }
                $data = $ip->data->city;
                return $data;
        }

        private function getWeather($areaid, $type)
        {

                $app_id = '自己的appid';
                //privatekey
                $privatekey = '自己的privatekey';

                if (isset($areaid) && isset($type))
                {
                        //格式化当前时间yyyyMMddHHmm
                        $date = date('YmdHi', time());
                        //api请求固定部分
                        $api_head = 'http://open.weather.com.cn/data/?areaid=' . $areaid . '&type=' . $type . '&date=' . $date;
                        //拼接publickey
                        $publickey = $api_head . '&appid=' . $app_id;
                        //生成key
                        $sign_key = base64_encode(hash_hmac('sha1', $publickey, $privatekey, true));
                        //截取appid前6位
                        $api_url_appid = substr($app_id, 0, 6);
                        //拼接和urlencode处理最终url
                        $api_url = $api_head . '&appid=' . $api_url_appid . '&key=' . urlencode($sign_key);
                        //直接执行 生成的url 访问数据
                        //echo file_get_contents($api_url);  
                        return $weather;
                } else
                {
                        echo 'error';
                }
        }

}
