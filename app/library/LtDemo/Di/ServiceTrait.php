<?php
namespace LtDemo\Di;

use LtDemo\Services;

trait ServiceTrait {
  
  /**
   * @var \Kona\Services
   */
  public static function service() {
    return Services::getInstance();
  }
}