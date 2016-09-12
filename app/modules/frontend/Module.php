<?php
namespace Frontend;

use LtDemo\Application\ModuleDefinition;
use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\Router;

class Module extends ModuleDefinition implements ModuleDefinitionInterface {
  /**
   * static::defineRoutes() is called by Logikos\Application\Bootstrap\Modules
   * which then checks for defineAdditionalRoutes() and calls this
   *
   * Take care not to acidently intercept routes defined by other modules!
   * @param DiInterface $di
   */
  public static function defineAdditionalRoutes(DiInterface $di) {
    /* @var $router \Phalcon\Mvc\Router */
    $router    = $di->get('router');
    
  }

}
