<?php

namespace Multiple\Frontend;

use Phalcon\Loader,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\View,
    Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{

        /**
         * Register a specific autoloader for the module
         */
        public function registerAutoloaders(\Phalcon\DiInterface $di = null)
        {
                $loader = new Loader();
                $loader->registerNamespaces(
                        array(
                                'Multiple\frontend\Controllers' => __DIR__ . '/controllers/',
                                'Multiple\frontend\Models' => __DIR__ . '/models/',
                                'Multiple\Models' => __DIR__ . '/../models/', //前后台共用model时的配置
                        )
                );
                $loader->register();
        }

        /**
         * Register specific services for the module
         */
        public function registerServices(\Phalcon\DiInterface $di)
        {

                //Registering a dispatcher
                $di->set('dispatcher', function()
                {
                        $dispatcher = new Dispatcher();
                        $dispatcher->setDefaultNamespace("Multiple\frontend\Controllers");
                        return $dispatcher;
                });

                //Registering the view component
                $di->set('view', function()
                {
                        $view = new View();
                        $view->setViewsDir(__DIR__ . '/views/');
                        return $view;
                });
        }

}
