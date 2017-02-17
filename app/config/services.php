<?php

use Phalcon\DI\FactoryDefault,
    Phalcon\Mvc\View,
    Phalcon\Crypt,
    Phalcon\Mvc\Url as UrlResolver,
    Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
    Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter,
    Phalcon\Session\Adapter\Files as SessionAdapter;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function() use ($config)
{
        $url = new UrlResolver();
        $url->setBaseUri($config->application->baseUri);
        return $url;
}, true);

/**
 * 初始化数据库链接
 */
$di->set('db', function() use ($config)
{
        return new DbAdapter(array(
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname,
                'options' => array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
                )
        ));
});

$di->set('router', function ()
{

        $router = new Phalcon\Mvc\Router();
//        $router = new Router();

        $router->setDefaultModule("frontend");

        $router->setDefaults(array(
                "namespace" => 'Multiple\frontend\Controllers',
                "controller" => "index",
                "action" => "index"
        ));
        //frontend
        $router->add("/:controller", array(
                'module' => 'frontend',
                'controller' => 1,
        ));
        $router->add("/:controller/:action", array(
                'module' => 'frontend',
                'controller' => 1,
                'action' => 2,
        ));
        $router->add("/:controller/:action/:param", array(
                'module' => 'frontend',
                'controller' => 1,
                'action' => 2,
                'param' => 3,
        ));
        //backend
        $router->add("/admin/:controller", array(
                'module' => 'backend',
                'namespace' => 'Multiple\Backend\Controllers', //　一定要加上，要不然找不到后台
                'controller' => 1,
        ));
        $router->add("/admin/:controller/:action", array(
                'module' => 'backend',
                'namespace' => 'Multiple\Backend\Controllers',
                'controller' => 1,
                'action' => 2,
        ));
        $router->add("/admin/:controller/:action/:param", array(
                'module' => 'backend',
                'namespace' => 'Multiple\Backend\Controllers',
                'controller' => 1,
                'action' => 2,
                'param' => 3,
        ));

        return $router;
});

/**
 * Registering the collectionManager service
 */
$di->set('collectionManager', function()
{
        $eventsManager = new Phalcon\Events\Manager();
        // Setting a default EventsManager
        $modelsManager = new Phalcon\Mvc\Collection\Manager();
        $modelsManager->setEventsManager($eventsManager);
        return $modelsManager;
}
);

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function()
{
        return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function()
{
        $session = new SessionAdapter();
        $session->start();
        return $session;
});

//Registering a Http\Request
$di->set('request', function()
{
        return new \Phalcon\Http\Request();
});

/**
 * Start redis
 */
$di->set('redis', function() use ($config)
{
        $redis = new Redis();
        $redis->connect($config->redis->host, $config->redis->port);
        $redis->auth('ff09e41b07ac4ab2:ddshouGMI2016');
        return $redis;
});

/**
 * 获取配置文件
 */
$di->set('config', function() use ($config)
{
        return $config;
});

/**
 * 加密
 */
$di->set('crypt', function ()
{
        $crypt = new Crypt();
        // 设置全局加密密钥
        $crypt->setKey('yh#$%#@');
//        $crypt->setCipher('blowfish');
        return $crypt;
}, true);

/**
 * Start cookie
 */
$di->set('cookies', function() {
        $cookies = new Phalcon\Http\Response\Cookies();
        $cookies->useEncryption(false); //禁用加密
        return $cookies;
});