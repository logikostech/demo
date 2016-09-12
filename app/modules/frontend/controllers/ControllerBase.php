<?php

namespace Frontend\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller {
  use \LtDemo\Di\ServiceTrait;
  
  public function onConstruct() {
  }
  
  protected function requestIsFromLocalhost() {
    return $_SERVER['REMOTE_ADDR'] === $_SERVER['SERVER_ADDR'];
  }
}
