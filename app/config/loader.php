<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
        array(
                'frontend' => array(
                        'className' => 'Multiple\Frontend\Module',
                        'path' => __DIR__ . '/../app/frontend/Module.php',
                ),
                'backend' => array(
                        'className' => 'Multiple\Backend\Module',
                        'path' => '../app/backend/Module.php',
                )
        )
)->register();
