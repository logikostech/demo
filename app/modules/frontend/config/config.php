<?php

date_default_timezone_set('US/Eastern');
setlocale(LC_ALL, "en_US.UTF-8");

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'phalcon_phalcon',
        'password' => 'z+qzIT,HO~{?',
        'dbname'   => 'phalcon_ltmultimod',
        'charset'  => 'utf8',
    ),
    'application' => array(
        'controllersDir' => __DIR__ . '/../controllers/',
        'modelsDir'      => __DIR__ . '/../models/',
        'migrationsDir'  => __DIR__ . '/../migrations/',
        'viewsDir'       => __DIR__ . '/../views/',
        'baseUri'        => '/ltmultimod/'
    )
));
