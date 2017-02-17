<?php
date_default_timezone_set("PRC");
header("Content-Type: text/html; charset=UTF-8");


ini_set('display_errors',0);//关闭警告等提示信息 1或注释：开启
error_reporting(E_ALL);

//production 生产环境
//development 开发环境
define('CONFIGSTATUS', 'development');
//use Phalcon\Logger;
//use Phalcon\Logger\Adapter\File as FileAdapter;

try
{
        //$logger = new FileAdapter(__DIR__ . "/../public/logs/test.log");

        /**
         * Read the configuration
         */
        $config = include __DIR__ . "/../app/config/config." . CONFIGSTATUS . ".php";

        /**
         * Read auto-loader
         */
        include __DIR__ . "/../app/config/loader.php";

        /**
         * Read services
         */
        include __DIR__ . "/../app/config/services.php";
        /**
         * Handle the request
         */
        include __DIR__ . "/../app/library/app.function.php";

        $application = new \Phalcon\Mvc\Application($di);
        $application->registerModules(
                array(
                        'frontend' => array(
                                'className' => 'Multiple\Frontend\Module',
                                'path' => __DIR__ . '/../app/frontend/Module.php',
                        ),
                        'backend' => array(
                                'className' => 'Multiple\Backend\Module',
                                'path' => __DIR__ . '/../app/backend/Module.php',
                        )
                )
        );

        $application->setDI($di);
        echo $application->handle()->getContent();
} catch (Phalcon\Exception $e)
{
        echo $e->getMessage();
} catch (PDOException $e)
{
        echo $e->getMessage();
}