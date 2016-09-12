<?php

namespace Ltmultimod\Ltadmin\Controllers;

class TestController extends ControllerBase
{
  public function initialize() {
    parent::initialize();
  }

    public function indexAction()
    {
      $this->flash->success('this is a test, success btw');
      var_dump(__METHOD__.__LINE__);
      var_dump(func_get_args());
      $this->flash->success('this is a test, success btw');
    }
    public function fooAction() {
      $this->flash->success('this is a test, success btw1');
      var_dump(__METHOD__.__LINE__);
      var_dump(func_get_args());
      $this->flash->success('this is a test, success btw2');
    }
}

