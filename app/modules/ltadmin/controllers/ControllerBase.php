<?php

namespace Ltmultimod\Ltadmin\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
  public function initialize() {
    $this->tag->setTitle("LT-Admin");
  }
}
