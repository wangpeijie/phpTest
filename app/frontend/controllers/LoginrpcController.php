<?php
namespace Multiple\frontend\Controllers;
require dirname(__DIR__) . '/../library/phprpc/phprpc_server.php';

class LoginrpcController extends ControllerBase
{

        private $notAllowMethods = ['initialize', 'indexAction', '__construct', 'setDI', 'getDI', 'setEventsManager', 'getEventsManager', '__get'];
        private $res;

        public function initialize()
        {
                $this->view->disable();
                $methods = get_class_methods($this);
                $allMethods = array_diff($methods, $this->notAllowMethods);
                $phprpc_server = new \PHPRPC_Server();
                $phprpc_server->add($allMethods, $this);
                $phprpc_server->setEnableGZIP(true);
                $phprpc_server->start();
                $this->res = array('code' => 1, 'errorMessage' => '请求失败');
        }

        public function indexAction()
        {
                
        }

        /**
         * 登录
         * @param type $n
         * @return type
         */
        public function login($n)
        {
                $params = json_decode($n, TRUE);
                if (empty($params['account']))
                {
                        return echoErrorMessage('帐号不能为空');
                }
                $user = \Multiple\Models\User::findFirstByAccount($params['account']);
                if (empty($user))
                {
                        return echoErrorMessage('该帐号不存在');
                } 
                return echoSuccessMessage();
        }

        /**
         * 忘记密码发送手机验证码
         */
        public function fgPwd($n)
        {
                $params = json_decode($n, TRUE);
                if (empty($params['mobile']))
                {
                        return echoErrorMessage('请填写手机号');
                }
                $user = YhUser::findFirstByAccount($params['mobile']);
                if (empty($user->toArray()))
                {
                        return echoErrorMessage('该帐号不存在');
                }
                $code = $this->redis->get('forgetPwd:' . $params['mobile']);
                if (empty($code))
                {
                        $code = getRandomCode(6);
                        $this->redis->setex('forgetPwd:' . $params['mobile'], 300, $code);
                }
                $content = '验证码是：' . $code;
                $res = TRUE;
//                $res = send_mobile_message($params['mobile'], $content);
                return $res ? echoSuccessMessage($code) : echoErrorMessage('获取验证码失败');
        }

        /**
         * 忘记密码
         */
        public function checkFgPwd($n)
        {
                return echoErrorMessage('132');
                $params = json_decode($n, TRUE);
                if (empty($params['mobile']))
                {
                        return echoErrorMessage('请填写手机号');
                } elseif (empty($params['code']))
                {
                        return echoErrorMessage('请填写验证码');
                } elseif ($params['password1'] != $params['password2'])
                {
                        return echoErrorMessage('密码不一致');
                }
                $user = YhUser::findFirstByAccount($params['mobile']);
                if (empty($user))
                {
                        return echoErrorMessage('该帐号不存在');
                }
                $code = $this->redis->get('forgetPwd:' . $params['mobile']);
                if (empty($code))
                {
                        return echoErrorMessage('请先获取验证码');
                } elseif ($code != $params['code'])
                {
                        return echoErrorMessage('验证码错误');
                }
                $user->password = password_hash($params['password1'], PASSWORD_DEFAULT);
                $res = $user->save();
                //设置session和redis
                $this->session->set('yh_user', $user->id);
                return $res ? echoSuccessMessage() : echoErrorMessage('操作失败');
        }

        /**
         * 获取第三方登录地址
         */
        public function getLoginUrl()
        {
                include_once __DIR__ . '/../library/libweibo/saetv2.ex.class.php';
                $o = new SaeTOAuthV2($this->config->weibo->WB_AKEY, $this->config->weibo->WB_SKEY);
                $weiboUrl = $o->getAuthorizeURL($this->config->weibo->WB_CALLBACK_URL_LOGIN);

                $wxMark = time() . rand(10000, 99999);

                $appid = $this->config->weixinOpen->appID;
                $callback = urlencode($this->config->weixinOpen->callback); //回调地址
                $state = $wxMark . '|login'; //wxRandomAccount:2130088296415948
                $wxUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . $callback . '&response_type=code&scope=snsapi_login&state=' . $state . '#wechat_redirect';

                $qqUrl = 'https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=' . $this->config->qq->app_id . '&redirect_uri=' . $this->config->qq->redirect_login;
                return echoSuccessMessage(['weiboUrl' => $weiboUrl, 'qqUrl' => $qqUrl, 'wxUrl' => $wxUrl, 'wxMark' => $wxMark]);
        }
        
        /**
         * 检测微信是否登录
         */
        public function checkWxLogin($n)
        {
                $params = json_decode($n, true);
                $uid = $this->redis->get('yh_user_wxLogin:' . $params['wxMark']);
                if ($uid)
                {
                        $this->session->set('yh_user', $uid);
                        return echoSuccessMessage('');
                }
                return echoErrorMessage('登录失败');
        }

}
