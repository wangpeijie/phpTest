<?php

/**
 * 获取配置文件
 * @return type 
 */
function getConfig()
{
        $application = new \Phalcon\Mvc\Application();
        $config = $application->getDI()->getShared('config');
        return $config;
}

/**
 * 获取redis
 * @return type
 */
function getRedis()
{
        $application = new \Phalcon\Mvc\Application();
        $redis = $application->getDI()->getShared('redis');
        return $redis;
}

/**
 * curl get
 */
function curlGet($url)
{
//初始化
        $ch = curl_init();
//设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
//执行并获取HTML文档内容
        $output = curl_exec($ch);
//释放curl句柄
        curl_close($ch);

        return $output;
}

/**
 * curl post
 */
function curlPost($url, $post_data)
{
//$post_data = array("p1" => "1", "p2" => "2");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// post数据
        curl_setopt($ch, CURLOPT_POST, 1);
// post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
}

/**
 * 输出错误信息
 * @param type $message
 */
function echoErrorMessage($message, $code = 1)
{
        $res = array('code' => $code, 'errorMessage' => $message);
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
}

/**
 * 输出成功信息
 * @param type $data
 */
function echoSuccessMessage($data = '', $errorMessage = '')
{
        $res = array('code' => 0, 'errorMessage' => $errorMessage, 'data' => $data);
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
}

/**
 * rpc错误返回结果
 * @param type $msg
 * @return int
 */
function rpcCallbackMsg($msg)
{
        $res = array('code' => 1, 'errorMessage' => $msg);
        return $res;
}

/**
 * rpc正确返回结果
 * @param type $msg
 * @return int
 */
function rpcCallbackData($data)
{
        $res = array('code' => 0, 'data' => $data);
        return $res;
}

/**
 * 获取图片路径
 * @param type $pic
 * @return type
 */
function getImageSrc($pic)
{
        $config = getConfig();
        return $config->upload->url . $pic;
}

/**
 * 判断是否json
 * @param type $string
 * @return type
 */
function is_json($string)
{
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
}

/**
 * 获取数据库连接
 * @return type 
 */
function getDb()
{
        $application = new \Phalcon\Mvc\Application();
        $db = $application->getDI()->getShared('db');
        return $db;
}

/**
 * 写日志
 */
function writeLogs3($word, $name = 'api3')
{
        $_word = var_export($word, true);
        $content = "执行日期：" . date("Y-m-d H:i:s", time()) . PHP_EOL . $_word . PHP_EOL;
//        $logs = __DIR__ . '/../../public/logs.txt';
        $logs = __DIR__ . '/../logs/' . $name . '.log';
        error_log($content, 3, $logs);
}

/**
 * 生成随机订单号*
 */
function getRandomCode($length = 32, $mode = 1)
{
        switch ($mode)
        {
                case '1':
                        $str = '1234567890';
                        break;
                case '2':
                        $str = 'abcdefghijklmnopqrstuvwxyz';
                        break;
                case '3':
                        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                case '4':
                        $str = '1234567890abcdefghijklmnopqrstuvwxyz';
                        break;
                default:
                        $str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
        }

        $result = '';
        $l = strlen($str) - 1;
        $num = 0;

        for ($i = 0; $i < $length; $i ++)
        {
                $num = rand(0, $l);
                $a = $str[$num];
                $result = $result . $a;
        }
        return $result;
}

/**
 * 根据时间查询
 * @param type $para
 * @return string
 */
function getSqlByTime($para)
{
        $timeStr = '';
        date_default_timezone_set('PRC');
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $now = time();
        switch ($para['type'])
        {
                case 1://今天
                        //$time = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                        $timeStr = " and " . $para['col'] . ">=" . $todayStart;
                        break;
                case 2://最近7天
                        $time = date('Y-m-d', strtotime('-7 day'));
                        //$time = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
                        $timeStart = strtotime($time . " 00:00:00");
                        $timeStr = " and " . $para['col'] . " between " . $timeStart . " and " . $now;
                        break;
                case 3://最近1月
                        $time = date('Y-m-d', strtotime('-30 day'));
                        //$time = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
                        $timeStart = strtotime($time . " 00:00:00");
                        $timeStr = " and " . $para['col'] . " between " . $timeStart . " and " . $now;
                        break;
                case 4://时间段
                        $timeStart = strtotime($para['timeStart'] . " 00:00:00");
                        $timeEnd = strtotime($para['timeEnd'] . " 23:59:59");
                        $timeStr = " and " . $para['col'] . " between " . $timeStart . " and " . $timeEnd;
                        break;
                case 5://本周
                        $time = mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y"));
                        $timeStr = " and " . $para['col'] . ">=" . $time;
                        break;
                case 6://本月
                        $time = mktime(0, 0, 0, date("m"), 1, date("Y"));
                        $timeStr = " and " . $para['col'] . ">=" . $time;
                        break;
                default:
                        break;
        }
        return $timeStr;
}

/**
 * 获取支付方式名称
 * @param type $value
 * @return string
 */
function getPayName($value)
{
        switch ($value)
        {
                case 1:
                        $paytype = '支付宝';
                        break;
                case 2:
                        $paytype = '微信';
                        break;
                case 3:
                        $paytype = '银联';
                        break;
                default:
                        $paytype = '';
                        break;
        }
        return $paytype;
}

/**
 * 发送手机短信
 * @param type $mobile          手机号码
 * @param type $content         短信内容
 * @return boolean
 */
function send_mobile_message($mobile, $content)
{
        if (empty($mobile) || empty($content))
        {
                return false;
        } else if (is_array($mobile))
        {
                $mobile = implode(',', $mobile);
        }
        $url = 'http://web.cr6868.com/asmx/smsservice.aspx';
        $post_data = array(
                "name" => "18019389403",
                "pwd" => "8270A7DF2EA21B64E52FC03DAE19",
                "content" => $content,
                "mobile" => $mobile,
                "stime" => date('Y-m-d H:i:s'),
                "sign" => "",
                "type" => "pt",
                "extno" => ""
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $response = curl_exec($ch);
        curl_close($ch);

        $r = substr($response, 0, 1);
        return $r == 0 ? true : false;
}

/**
 *    说明：字符串截取函数（大写字母算一个，英文及其标点算半个）
 *
 *    ( STRING ) $sourcestr	// 要截取的字符串
 *    ( INT ) $start 		// 开始截取的位置
 *    ( INT ) $cutlength	// 截取长度
 *    ( BOOL | STRING ) $tail		// 是否在末尾加上尾巴（默认是省略号（3个点）
 *    ( BOOL) $stripTags		// 是否在是否在截取前过滤HTML标签
 */
function cutStr($sourcestr = '', $start = 0, $cutlength = 0, $tail = TRUE, $stripTags = TRUE)
{
        if ($stripTags)
        {
                $sourcestr = strip_tags($sourcestr);
        }
        $sourcestr = (string) trim($sourcestr);
        $sourcestr = strtr($sourcestr, array(
                //' ' => '',
                '&nbsp;' => ''
        ));
        $returnstr = '';
        $i = 0; // 已经读取的字节数
        $n = 0; // 已经保留的字符数
        $s = 0; // 开始位置前的字节数
        $l = 0; // 开始位置前的字符数
        $begin = FALSE; // 开始截取前的长度是否够
        $str_length = strlen($sourcestr); //字符串的总字节数
        // 处理接收的数据
        $start = (int) abs($start);
        $cutlength = (int) abs($cutlength);
        while ($n < $cutlength)
        { // 长度是否需要截？是否够长(开始位置前的长度+已截取的长度)？
                // 记下开始位置的字节数
                if (FALSE == $begin)
                {
                        if ($start == $l)
                        { // 是否到了该截取的开始位置
                                $begin = TRUE;
                                $s = $i; // 记下开始字节位置
                                $preStrLen = $s; // 记下开始截前的字节
                        }
                        $temp_str = substr($sourcestr, $i, 1); // UTF-8编码的字符可能由1~3个字节组成，具体数目可以由第一个字节判断出来。
                        $ascnum = Ord($temp_str); // 得到字符串中第$i位字符的ascii码
                        if ($ascnum >= 224)
                        { // 如果ASCII位高与224，
                                $i = $i + 3; // 实际Byte计为3
                                $l++; // 字符串长度加壹
                        } elseif ($ascnum >= 192)
                        { // 如果ASCII位高与192，
                                $i = $i + 2; // 实际Byte计为2
                                $l++;
                        } elseif ($ascnum >= 65 && $ascnum <= 90)
                        { // 如果是大写字母，
                                $i = $i + 1; // 实际的Byte数仍计1个
                                $l++;
                        } else
                        { // 其他情况下，包括小写字母和半角标点符号，
                                $i = $i + 1; // 实际的Byte数计1个
                                $l++;
                        }
                }
                // 开始截取，（条件：已到开始截取的位置，并且截取的字符长度还不够）
                while (TRUE == $begin && $cutlength > $n)
                {
                        $temp_str = substr($sourcestr, $s, 1);
                        $ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码
                        if ($ascnum >= 224)
                        { //如果ASCII位高与224，
                                $returnstr = $returnstr . substr($sourcestr, $s, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
                                $s = $s + 3; //实际Byte计为3
                                $n++; //字串长度计1
                        } else if ($ascnum >= 192)
                        { //如果ASCII位高与192，
                                $returnstr = $returnstr . substr($sourcestr, $s, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
                                $s = $s + 2; //实际Byte计为2
                                $n++; //字串长度计1
                        } else if ($ascnum >= 65 && $ascnum <= 90)
                        { //如果是大写字母，
                                $returnstr = $returnstr . substr($sourcestr, $s, 1);
                                $s = $s + 1; //实际的Byte数仍计1个
                                $n++; //但考虑整体美观，大写字母计成一个高位字符（增强版函数，不考虑美观，直接算一个）
                        } else
                        { //其他情况下，包括小写字母和半角标点符号，
                                $returnstr = $returnstr . substr($sourcestr, $s, 1);
                                $s = $s + 1; //实际的Byte数计1个
                                $n++; // 字母和半角标点也算一个字
                        }
                }
        }
        if (!empty($tail) && FALSE != $tail)
        { // 在末尾加上省略号
                if (TRUE === $tail)
                {
                        $tail = '...';
                } else
                {
                        $tail = (string) $tail;
                }
                $cutlength = strlen($returnstr) + $preStrLen; // 截取长度 = 截取部分的长度 + 开头文字的长度
                if (0 < $start)
                {
                        $preTail = $tail;
                        $returnstr = $preTail . $returnstr;
                }
                if ($cutlength < $str_length)
                {
                        $returnstr = $returnstr . $tail; //超过长度时在尾处加上省略号
                }
        }
        return $returnstr;
}

/**
 * 发送邮件
 * @param type $to  收件人地址
 * @param type $subject  邮件标题
 * @param type $body  邮件正文
 * @return boolean
 */
function postmail($to, $subject = '', $body = '')
{
        date_default_timezone_set('Asia/Shanghai'); //设定时区东八区
        include_once __DIR__ . '/../library/PHPMailer/class.phpmailer.php';
        include_once __DIR__ . '/../library/PHPMailer/class.smtp.php';

        $mail = new PHPMailer(); //new一个PHPMailer对象出来
        $mail->CharSet = "utf-8"; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        $mail->IsSMTP(); // 设定使用SMTP服务
//        $mail->SMTPDebug = 2;                     // 启用SMTP调试功能
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth = true;                  // 启用 SMTP 验证功能
        $mail->SMTPSecure = "tls";                 // 安全协议，可以注释掉.ssl
        // SMTP服务器密码         
        $mail->Port = 25;                   // SMTP服务器的端口号

        $mail->Host = "smtp.163.com"; // SMTP server
        $mail->Username = "ddshou123@163.com";     // SMTP server username
        $mail->Password = "ddshou123456";            // SMTP server password
        $mail->AddReplyTo("ddshou123@163.com", "点点手订单");
        $mail->From = "ddshou123@163.com";

        $mail->Subject = $subject;
        $mail->AltBody = strip_tags($body); //This is the body in plain text for non-HTML mail clients
        $mail->MsgHTML($body);
        $mail->ClearAddresses();
        $address = $to;
        $mail->AddAddress($address, '');
//        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments(附件)
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        if (!$mail->Send())
        {
//                return 'Mailer Error: ' . $mail->ErrorInfo;
                return false;
        } else
        {
//                return "Message sent!恭喜，邮件发送成功！";
                return true;
        }
}

/**
 * 验证sign
 * @param type $params
 * @return type
 */
function getSign($params)
{
        $config = \Phalcon\DI::getDefault()->get('config');
        $method = $params['method'];
        unset($params['_url'], $params['sign'], $params['method']);
        ksort($params);
        $authkey = $params['platform'] == 'pc' ? $config->authkey->pc : $config->authkey->app;
        if ($method == 'sha1')
        {
                $sign = sha1(http_build_query($params) . $authkey);
        } else
        {
                $sign = md5(http_build_query($params) . $authkey);
        }
        return $sign;
}

function clean_xss($data, $strip_tags = 0)
{
        $str = trim($data);  //清理空格
        if ($strip_tags)
        {
                $str = strip_tags($str);   //过滤html标签
        }
        return addslashes(htmlspecialchars($str, ENT_QUOTES)); //将字符内容转化为html实体
}

/**
 * 导出
 * @param type $list 二维数组：要导出的数据
 * @param type $data  $data['examTitle']：文件名，$data['param']：字段代表的意义（一维数组）
 * $path eg: __DIR__ . '/../../public/web/excel/test.xls'
 */
function exportExcel($list, $data, $path = null)
{
        include_once __DIR__ . '/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        // Set properties    
        $objPHPExcel->getProperties()->setCreator("ctos")
                ->setLastModifiedBy("ctos")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
//                $this->writeLogs3('1111', '123');
        $biao = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $count = count($list[0]);
        // set width    
        for ($k = 0; $k < $count; $k++)
        {
                $objPHPExcel->getActiveSheet()->getColumnDimension($biao[$k])->setWidth(20);
        }
        // 设置行高度    
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
        // 字体和样式  
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A2:' . $biao[$count - 1] . '2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A2:' . $biao[$count - 1] . '2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:' . $biao[$count - 1] . '2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        // 设置水平居中    
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        for ($x = 0; $x < $count; $x++)
        {
                $objPHPExcel->getActiveSheet()->getStyle($biao[$x])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        //  合并  
        $objPHPExcel->getActiveSheet()->mergeCells('A1:' . $biao[$count] . '1');
        // 表头  
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $data['examTitle']);
        for ($y = 0; $y < $count; $y++)
        {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($biao[$y] . '2', $data['param'][$y]);
        }
        // 内容  
        for ($i = 0, $len = count($list); $i < $len; $i++)
        {
                $keys = array_keys($list[$i]);
                for ($j = 0; $j < $count; $j++)
                {
                        $objPHPExcel->getActiveSheet(0)->setCellValue($biao[$j] . ($i + 3), $list[$i][$keys[$j]]);
                }
                $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':' . $biao[$count - 1] . ($i + 3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':' . $biao[$count - 1] . ($i + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getRowDimension($i + 3)->setRowHeight(16);
        }

        // Rename sheet    
        $objPHPExcel->getActiveSheet()->setTitle($data['examTitle']);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet    
        $objPHPExcel->setActiveSheetIndex(0);

        if ($path)
        {
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save($path);
                return $path;
        }
        // 输出  
        ob_end_clean(); //清除缓冲区,避免乱码
        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
//                header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $data['examTitle'] . '.xls"');
        header('Cache-Control: max-age=0');


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
}

function exportZip($datalist, $name)
{
        $filename = __DIR__ . '/../web/upload/excel/' . $name; //最终生成的文件名（含路径）   
        if (!file_exists($filename))
        {
                //重新生成文件   
                $zip = new ZipArchive(); //使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释   
                if ($zip->open($filename, ZIPARCHIVE::CREATE) !== TRUE)
                {
                        exit('无法打开文件，或者文件创建失败');
                }
                foreach ($datalist as $val)
                {
                        if (file_exists($val))
                        {
                                $zip->addFile($val, basename($val)); //第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下   
                        }
                }
                $zip->close(); //关闭   
        }
        if (!file_exists($filename))
        {
                exit("无法找到文件"); //即使创建，仍有可能失败。。。。   
        }
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename=' . basename($filename)); //文件名   
        header("Content-Type: application/zip"); //zip格式的   
        header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件    
        header('Content-Length: ' . filesize($filename)); //告诉浏览器，文件大小                
        @readfile($filename);
        unlink($filename);
        foreach ($datalist as $val2)
        {
                unlink($val2);
        }
}

function dl_file($file)
{
        //First, see if the file exists  
        if (!is_file($file))
        {
                die("<b>404 File not found!</b>");
        }
        //Gather relevent info about file  
        $len = filesize($file);
        $filename = basename($file); //basename() 函数返回路径中的文件名部分。
        $file_extension = strtolower(substr(strrchr($filename, "."), 1)); //strtolower() 函数把字符串转换为小写。
        //This will set the Content-Type to the appropriate setting for the file  
        switch ($file_extension)
        {
                case "pdf":
                        $ctype = "application/pdf";
                        break;
                case "exe":
                        $ctype = "application/octet-stream";
                        break;
                case "zip":
                        $ctype = "application/zip";
                        break;
                case "doc":
                        $ctype = "application/msword";
                        break;
                case "xls":
                        $ctype = "application/vnd.ms-excel";
                        break;
                case "ppt":
                        $ctype = "application/vnd.ms-powerpoint";
                        break;
                case "gif":
                        $ctype = "image/gif";
                        break;
                case "png":
                        $ctype = "image/png";
                        break;
                case "jpeg":
                case "jpg":
                        $ctype = "image/jpg";
                        break;
                case "mp3":
                        $ctype = "audio/mpeg";
                        break;
                case "wav": $ctype = "audio/x-wav";
                        break;
                case "mpeg":
                case "mpg":
                case "mpe":
                        $ctype = "video/mpeg";
                        break;
                case "mov":
                        $ctype = "video/quicktime";
                        break;
                case "avi":
                        $ctype = "video/x-msvideo";
                        break;

                //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)  
                case "php":
                case "htm":
                case "html":
                case "txt":
                        die("<b>Cannot be used for " . $file_extension . " files!</b>");
                        break;
                default:
                        $ctype = "application/force-download";
        }

        //Begin writing headers  
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");

        //Use the switch-generated Content-Type  
        header("Content-Type: $ctype");

        //Force the download  
        $header = "Content-Disposition: attachment; filename=" . $filename . ";";
        header($header);
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . $len);
        @readfile($file);
        exit;
}
