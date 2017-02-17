<?php

namespace Multiple\frontend\Controllers;

class LoginController extends ControllerBase
{

        public function initialize()
        {
                
        }

        public function indexAction()
        {
                
        }

        //生成图片验证码
        public function vcodeAction()
        {
                $this->view->disable();
                Header("Content-type: image/gif");
                /*                 * *
                 * 初始化
                 */
                $border = 0; //是否要边框 1要:0不要
                $how = 4; //验证码位数
                $w = $how * 15; //图片宽度
                $h = 30; //图片高度
                $fontsize = 10; //字体大小
                $alpha = "abcdefghjkmnopqrstuvwxyz"; //验证码内容1:字母
                $number = "23456789"; //验证码内容2:数字
                $randcode = ""; //验证码字符串初始化
                srand((double) microtime() * 1000000); //初始化随机数种子
                $im = ImageCreate($w, $h); //创建验证图片
                /*                 * *
                 * 绘制基本框架
                 */
                $bgcolor = ImageColorAllocate($im, 255, 255, 255); //设置背景颜色
                ImageFill($im, 0, 0, $bgcolor); //填充背景色
                if ($border)
                {
                        $black = ImageColorAllocate($im, 0, 0, 0); //设置边框颜色
                        ImageRectangle($im, 0, 0, $w - 1, $h - 1, $black); //绘制边框
                }
                /*                 * *
                 * 逐位产生随机字符
                 */
                for ($i = 0; $i < $how; $i++)
                {
                        $alpha_or_number = mt_rand(0, 1); //字母还是数字
                        $str = $alpha_or_number ? $alpha : $number;
                        $which = mt_rand(0, strlen($str) - 1); //取哪个字符
                        $code = substr($str, $which, 1); //取字符
                        $j = !$i ? 4 : $j + 15; //绘字符位置
                        $color3 = ImageColorAllocate($im, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100)); //字符随即颜色
                        ImageChar($im, $fontsize, $j, 6, $code, $color3); //绘字符
                        $randcode .= $code; //逐位加入验证码字符串
                }
                /*                 * *
                 * 添加干扰
                 */
                for ($i = 0; $i < 5; $i++)//绘背景干扰线
                {
                        $color1 = ImageColorAllocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)); //干扰线颜色
                        ImageArc($im, mt_rand(-5, $w), mt_rand(-5, $h), mt_rand(20, 300), mt_rand(20, 200), 55, 44, $color1); //干扰线
                }

                $this->session->set('randcode', $randcode);

                /*                 * * 绘图结束 */
                Imagegif($im);
                ImageDestroy($im);
                /*                 * * 绘图结束 */
        }

        /**
         * 微信回调
         */
        public function wxCallbackAction()
        {
                $para = $this->request->get();
                if (!$para['code'])
                {
                        exit('错误的请求');
                }
                $state = explode('|', $para['state']);
                $appid = $this->config->weixinOpen->appID;
                $appSecret = $this->config->weixinOpen->appSecret;
                $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $appSecret . '&code=' . $para['code'] . '&grant_type=authorization_code';
                $json = curlGet($url);
                $arr = json_decode($json, true);
                //得到 access_token 与 openid
                $openId = $arr['openid'];
                $sql = "select uid,type,openid from dds_social where openid=:openid and type=1";
                $social = $this->db->fetchOne($sql, Phalcon\Db::FETCH_ASSOC, array('openid' => $openId));
                if ($social)
                {
                        $this->socialSuccess($social['uid'], TRUE);
                } else
                {
                        //获取个人资料
                        $urlInfo = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $arr['access_token'] . '&openid=' . $openId . '&lang=zh_CN';
                        $jsonInfo = curlGet($urlInfo);
                        $arrInfo = json_decode($jsonInfo, true);
                        if (!isset($arrInfo['openid']))
                        {
                                exit('操作失败，请重新扫描二维码');
                        }
                        $this->socialError([
                                'code' => 0,
                                'type' => 'wx',
                                'openid' => $openId,
                                'wxMark' => $state[0],
                                'nickname' => $arrInfo['nickname'],
                                'face' => $arrInfo['headimgurl']
                        ]);
                }
        }

        /**
         * 微博回调
         */
        public function wbCallbackAction()
        {
                $para = $this->request->get();
                if (!isset($para['code']))
                {
                        exit('授权失败');
                }

                include_once __DIR__ . '/../library/libweibo/saetv2.ex.class.php';
                $o = new SaeTOAuthV2($this->config->weibo->WB_AKEY, $this->config->weibo->WB_SKEY);
                $keys = array();
                $keys['code'] = $para['code'];
                $keys['redirect_uri'] = $this->config->weibo->WB_CALLBACK_URL;

                $token = $o->getAccessToken('code', $keys);

                $c = new SaeTClientV2($this->config->weibo->WB_AKEY, $this->config->weibo->WB_SKEY, $token['access_token']);
                $uid_get = $c->get_uid();
                $uid = $uid_get['uid'];
                $user_message = $c->show_user_by_id($uid); //根据ID获取用户等基本信息

                $openId = $user_message['id'];
                $sql = "select uid,type,openid from dds_social where openid=:openid and type=1";
                $social = $this->db->fetchOne($sql, Phalcon\Db::FETCH_ASSOC, array('openid' => $openId));
                if ($social)
                {
                        $this->socialSuccess($social['uid']);
                } else
                {
                        $this->socialError([
                                'code' => 0,
                                'type' => 'weibo',
                                'openid' => $user_message['id'],
                                'nickname' => $user_message['name'],
                                'face' => $user_message['avatar_large']
                        ]);
                }
        }

        /**
         * QQ回调
         */
        public function qqCallbackAction()
        {
                $qqCode = $this->request->get();
                if (!($qqCode['code']))
                {
                        exit('qq授权失败');
                }
                //获取access_token
                $getQQToken = array(
                        'code' => $qqCode['code'],
                        'redirect_uri' => $this->config->qq->callback,
                        'client_id' => $this->config->qq->appID,
                        'client_secret' => $this->config->qq->appsecret,
                        'grant_type' => 'authorization_code'
                );
                $token_url = 'https://graph.qq.com/oauth2.0/token?' . http_build_query($getQQToken);
                $token = [];
                parse_str(curlGet($token_url), $token);
                //获取openid
                $str = curlGet('https://graph.qq.com/oauth2.0/me?access_token=' . $token['access_token']);
                if (strpos($str, "callback") !== false)
                {
                        $lpos = strpos($str, "(");
                        $rpos = strrpos($str, ")");
                        $str = substr($str, $lpos + 1, $rpos - $lpos - 1);
                }
                $arr = json_decode($str, TRUE);

                $openId = $arr['openid'];
                $sql = "select uid,type,openid from dds_social where openid=:openid and type=1";
                $social = $this->db->fetchOne($sql, Phalcon\Db::FETCH_ASSOC, array('openid' => $openId));
                if ($social)
                {
                        $this->socialSuccess($social['uid']);
                } else
                {
                        //获取用户资料                        
                        $userInfoUrl = 'https://graph.qq.com/user/get_user_info?access_token=' . $token['access_token'] . '&oauth_consumer_key=' . $this->config->qq->appID . '&openid=' . $openId['openid'] . '&format=json';
                        $info = json_decode(curlGet($userInfoUrl), TRUE);
                        if (!isset($arrInfo['openid']))
                        {
                                exit('操作失败，请重新扫描二维码');
                        }
                        $this->socialError([
                                'code' => 0,
                                'type' => 'qq',
                                'openid' => $openId,
                                'nickname' => $info['nickname'],
                                'face' => $info['figureurl_qq_2'],
                        ]);
                }
        }

        /**
         * 第三方不是第一次登录
         * @param type $param
         * @return type
         */
        public function socialSuccess($uid, $is_wx = FALSE, $state = '')
        {
                $user = User::findFirstById($uid);
                $user->save(['lastlogin' => time()]);

                $this->session->set('yh_user', $user->id);
                if ($is_wx)
                {
                        $this->redis->setex('yh_user_wxLogin:' . $state, 300, $user->id);
                }
                Header('Location:/my/index');
                exit;
        }

        public function socialError($data)
        {
                $user = new YhUser();
                $para = [
                        'account' => '',
                        'pwd' => '',
                        'face' => $data['face'],
                        'name' => $data['nickname'],
                        'addtime' => time()
                ];
                $user->save($para);
                $social = new Social();
                $para2 = [
                        'uid' => $user->id,
                        'openid' => $data['openid'],
                        'type' => $data['type']
                ];
                $social->save($para2);
                header('location:/my/index');
                exit;
        }

}
