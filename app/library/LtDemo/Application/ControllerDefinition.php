<?php
namespace LtDemo\Application;

use Phalcon\Mvc\Controller;
use Logikos\Auth\Manager as AuthManager;

abstract class ControllerDefinition extends Controller {
  use \LtDemo\Di\ServiceTrait;
  
  public function onConstruct() {
  }
  
  protected function requestIsFromLocalhost() {
    return $_SERVER['REMOTE_ADDR'] === $_SERVER['SERVER_ADDR'];
  }
}
