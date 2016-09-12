<?php

namespace LtDemo\Application;
  
use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Logikos\Application\ModuleDefinitionTrait;

abstract class ModuleDefinition {
  use ModuleDefinitionTrait;
  
  protected $_localconf;

  /**
   * This method will be called by Logikos\Application\Bootstrap\Modules
   * so that each module can define its own routes.
   * 
   * Take care not to acidently intercept routes defined by other modules!
   * @param DiInterface $di
   */
  public static function defineRoutes(DiInterface $di) {
    /* @var $router \Phalcon\Mvc\Router */
    $router    = $di->get('router');
    $uriprefix = static::getRouteUriPrefix($di);
    
    // get a list of real controller names within this module
    // it will route to the controller if it exists,
    // else to the correct Action in indexController
    $ctrlmatch   = implode('|',static::getControllerList());
    
    $router->add("{$uriprefix}/:params",static::routeconf([
        'params' => 1
    ]));
    $router->add("{$uriprefix}/:action/:params",static::routeconf([
        'action' => 1,
        'params' => 2
    ]));
    $router->add("{$uriprefix}/({$ctrlmatch})/:params",static::routeconf([
        'controller' => 1,
        'params'     => 2
    ]));
    $router->add("{$uriprefix}/({$ctrlmatch})/:action/:params",static::routeconf([
        'controller' => 1,
        'action'     => 2,
        'params'     => 3
    ]));
    if (method_exists(static::class,'defineAdditionalRoutes')) {
      static::defineAdditionalRoutes($di);
    }
  }
  
  public function getLocalConfig() {
    if (!$this->_localconf) {
      $configFile = static::getModuleDir()."/config/config.php";
      $config = file_exists($configFile)
          ? include static::getModuleDir()."/config/config.php"
          : [];
      $this->_localconf = new Config($config);
    }
    return $this->_localconf;
  }
  
  public function debugRoute(Router $router) {
    echo __METHOD__;var_dump([
        $router->getMatchedRoute(),
        $router->getRoutes(),
        $router->getModuleName(),
        $router->getControllerName(),
        $router->getActionName(),
        $router->getParams()
    ]);exit;
  }
  /**
   * Registers an autoloader related to the module
   *
   * @param DiInterface $di
   */
  public function registerAutoloaders(DiInterface $di = null) {
    /* @var $router \Phalcon\Mvc\Router */
    $router    = $di->get('router');
//$this->debugRoute($router);
    $loader = new Loader();
    $loader->registerNamespaces(array(
        static::getControllerNamespace() => static::getModuleDir() . '/controllers/',
        'Kona\Models'        => static::getModuleDir() . '/models/',
    ));
    $loader->register();
  }

  /**
   * Registers services related to the module
   *
   * @param DiInterface $di
   */
  public function registerServices(DiInterface $di) {
    $this->viewService($di);
    $this->dispatcherService($di);
  }
  
  protected function viewService(DiInterface $di) {
    $dir   = rtrim(static::getModuleDir(),'/') . '/views/';
    $level = View::LEVEL_ACTION_VIEW;
    if ($di->has('view')) {
      $view = $di->get('view');
      $view->setViewsDir($dir);
      $view->setRenderLevel($level);
    }
    else {
      $di['view'] = function () {
        $view = new View();
        $view->setViewsDir($dir);
        $view->setRenderLevel($level);
        return $view;
      };
    }
  }
  protected function dispatcherService(DiInterface $di) {
  
    if ($di->has('dispatcher')) {
      $di->get('dispatcher')->setDefaultNamespace(static::getControllerNamespace());
    }
    else {
      $di->setShared('dispatcher', function() use ($di) {
          $dispatcher = new Phalcon\Mvc\Dispatcher();
          $dispatcher->setDefaultNamespace(static::getControllerNamespace());
          return $dispatcher;
      });
    }
  }
}