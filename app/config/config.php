<?php

$basedir = realpath(__DIR__.'/../..');
$appdir  = realpath(__DIR__.'/..');

return [
    'appdir'          => $appdir,
    'application'     => [
        'baseUri'  => '/ltdemo',
        'viewsDir' => $appdir . '/views'
    ],
    'auth'            => include 'auth.php',
    'autoload'        => include 'autoload.php',
    'basedir'         => $basedir,
    'database'        => include 'database.php',
    'defaultModule'   => 'frontend',
    'mailer'          => include 'mailer.php',
    'modulesDir'      => $appdir."/modules"
];