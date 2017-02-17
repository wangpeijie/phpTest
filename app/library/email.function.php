<?php

class send_mail
{

        protected $from; //使用发邮件的是什么邮箱，163，qq等
        protected $to; //发送对象
        protected $subject; //标题
        protected $body; //邮件内容
        protected $attachments = array(); //附件
        protected $Username = 'wjlwpj1990@163.com'; //发邮件的邮件(与 from 保持一致)
        protected $Password = 'fukso1033595489'; //独立密码
        protected $reply = '1033595489@qq.com'; //接收客户回复的邮箱
        protected $replyTitle = 'replyTitle'; //接收客户回复的收件人
        protected $From = 'wjlwpj1990@163.com'; //发邮件的邮件

        function __construct($from = '163')
        {
                $this->from = $from;
        }

        /**
         * 设置发件人
         * @param type $From
         * @param type $Password
         * @param type $reply
         * @param type $replyTitle
         * @param type $fromName
         */
        public function setFrom($From, $Password, $reply, $replyTitle, $fromName = '')
        {
                $this->Username = $From;
                $this->Password = $Password;
                $this->reply = $reply;
                $this->replyTitle = $replyTitle;
                $this->From = $From;
                $this->FromName = "老王";  //发件人姓名 
        }

        public function send($to, $subject = '', $body = '')
        {
                date_default_timezone_set('Asia/Shanghai'); //设定时区东八区
                include_once __DIR__ . '/../library/PHPMailer/class.phpmailer.php';
                include_once __DIR__ . '/../library/PHPMailer/class.smtp.php';

                $mail = new PHPMailer(); //new一个PHPMailer对象出来
                $mail->CharSet = "utf-8"; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
                $mail->IsSMTP(); // 设定使用SMTP服务
//                $mail->SMTPDebug = 2;                     // 启用SMTP调试功能
//                1 = errors and messages
//                2 = messages only
                $mail->SMTPAuth = true;                  // 启用 SMTP 验证功能
                $mail->SMTPSecure = "tls";                 // 安全协议，可以注释掉.ssl
//                SMTP服务器密码   
                switch ($this->from)
                {
                        case '163':
                                $mail->Port = 25; // SMTP服务器的端口号
                                $mail->Host = "smtp.163.com"; // SMTP server
                                break;
                        case 'qq':
                                $mail->Port = 25;
                                $mail->Host = "smtp.qq.com"; // SMTP server
                                break;
                        case 'exmail.qq':
                                $mail->Port = 25;
                                $mail->Host = "smtp.exmail.qq.com"; // SMTP server  
                        default:
                                break;
                }
                $mail->Username = $this->Username;     // SMTP server username
                $mail->Password = $this->Password;            // SMTP server password
                $mail->AddReplyTo($this->reply, $this->replyTitle);  //接收客户回复的邮箱，标题
                $mail->From = $this->From;
//                $mail->Username = "ddshou123@163.com";     // SMTP server username
//                $mail->Password = "ddshou123456";            // SMTP server password
//                $mail->AddReplyTo("ddshou123@163.com", "点点手订单");  //接收客户回复的邮箱，标题
//                $mail->From = "ddshou123@163.com";

                $mail->Subject = $subject;
                $mail->AltBody = strip_tags($body); //This is the body in plain text for non-HTML mail clients
                $mail->MsgHTML($body);
                $mail->ClearAddresses();
                $address = $to;
                $mail->AddAddress($address, '');
                if($this->attachments)
                {
//                      $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments(附件)
//                      $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                        $attachments = $this->attachments;
                        foreach ($attachments as $value)
                        {
                                $mail->addAttachment($value);
                        }
                }
                if (!$mail->Send())
                {
//                        return 'Mailer Error: ' . $mail->ErrorInfo;
                        return false;
                } else
                {
//                        return "Message sent!恭喜，邮件发送成功！";
                        return true;
                }
        }

}
