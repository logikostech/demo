<?php
namespace LtDemo\Application;

use Logikos\Application\Bootstrap as LtBootstrap;
use LtDemo\Di\Services;
use Phalcon\Config;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use Phalcon\Loader;

class Bootstrap extends LtBootstrap {
  public static $debug = false;
  
  public function __construct(array $userOptions = null, $config = null, DiInterface $di = null) {
    parent::__construct($userOptions, $config, $di);

    if (getenv('APP_ENV') !== self::ENV_PRODUCTION) {
      $this->debug();
    }
    try {
      $this->initalize($userOptions, $config, $di);
    }
    catch (\Exception $e) {
      http_response_code(400);
      if (self::$debug) {
        throw $e;
      }
      die('Bootstrap failed: '.$e->getMessage()); //TODO log or prettify message for user
    }
  }
  protected function initalize($userOptions, $config, $di) {
    $this->debugRoute();
    $this->run($this->services()->getDi());
    $this->sendResponce();
  }
  public function getDi() {
    return $this->services()->getDi();
  }
  public function services() {
    return Services::getInstance();
  }
  protected function debug() {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    putenv('APP_ENV='.static::ENV_DEBUG);
    (new \Phalcon\Debug())->listen();
  }
  protected function debugRoute() {
    if (isset($_GET['debugroute'])) {
      $this->run($this->services()->getDi());
      echo __METHOD__.__LINE__;
      $router = $this->services()->router;
      $router->handle();
      var_dump([
          $router->getMatchedRoute(),
          $router->getModuleName(),
          $router->getControllerName(),
          $router->getActionName(),
          $router->getParams()
      ]);
      var_dump($router->getroutes());
      exit;
    }
  }
  protected function sendResponce() {
    echo $this->getContent();
  }
}