<?php
use Phalcon\Db\Adapter\Pdo\Mysql;

/**
 * @return array [
 *     'di_service_name' => [
 *         'adapter'  => 'Phalcon\Db\Adapter\Pdo\Mysql',
 *         'host'     => 'localhost',
 *         'username' => 'dbuser',
 *         'password' => 'mYP@$s',
 *         'dbname'   => 'database_name'
 *     ],
 *     'another_di_service_name' => []
 * ]
 */
return [
    'db' => [
      'adapter'  => Mysql::class,
      'host'     => getenv('DB_HOST'),
      'username' => getenv('DB_USER'),
      'password' => getenv('DB_PASS'),
      'dbname'   => getenv('DB_NAME'),
      'charset'  => 'utf8'
    ]
];