<?php
namespace LtDemo\Di;

use Logikos\Application\Bootstrap      as LtBootstrap;
use Logikos\Auth\Manager               as AuthManager;
use Logikos\Forms\SelectOptions;
use LtDemo\Application\Bootstrap;
use LtDemo\Di\Injectable;
use Phalcon\Config;
use Phalcon\Di\FactoryDefault          as Di;
use Phalcon\DiInterface;
use Phalcon\Events\Manager             as EventsManager;
use Phalcon\Flash\Direct               as Flash;
use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Model\MetaData\Apc     as ModelsMetaData;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url                    as UrlResolver;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php        as PhpViewEngine;
use Phalcon\Mvc\View\Engine\Volt       as VoltEngine;
use Phalcon\Session\Adapter\Files      as SessionAdapter;
use Logikos\Http\Request;
use Logikos\Http\Response;

/**
 * NOTICE: as you add/change services please be sure
 *         to modify the @property statments in
 *         LtDemo\Di\Injectable !
 */
class Services extends Injectable {
  
  protected function __construct() {
    $this->setDi(new Di());
    $this->loadServices();
    Di::setDefault($this->getDI());
  }
  public static function getInstance() {
    static $cache;
    return $cache ?: $cache = new Services;
  }
  /**
   * @param string $item
   * @param string $default
   * @return Config
   */
  public function getConfig($item=null, $default=null) {
    $conf = Bootstrap::getConfig();
    return is_null($item) ? $conf : $conf->get($item, $default);
  }
  
  /**
   * Registers a service in the services container
   *
   * @param string $name 
   * @param mixed $definition 
   * @param boolean $shared 
   * @return \Phalcon\Di\ServiceInterface 
   */
  public function set($name, $definition, $shared = false) {
    return $this->getDI()->set($name, $definition, $shared);
  }
  /**
   * Registers an "always shared" service in the services container
   *
   * @param string $name 
   * @param mixed $definition 
   * @return \Phalcon\Di\ServiceInterface 
   */
  public function setShared($name, $definition) {
    $this->getDI()->setShared($name, $definition);
  }
  
  protected function loadServices() {
    $this->set("request",  Request::class,  true);
    $this->set("response", Response::class, true);
    $this->authService();
    $this->modelsMetadataService();
    $this->viewService();
    $this->selectOptionsService();
    $this->urlService();
    $this->sessionService();
    $this->flashService();
    $this->loadDbServices();
  }

  protected function authService() {
    $di = $this->getDI();
    $this->set('auth', function() use ($di) {
      $auth = new AuthManager($di->get('config')->auth->options->toArray());
      return $auth;
    },true);
  }
  
  protected function modelsMetadataService() {
    // https://docs.phalconphp.com/en/latest/reference/models-metadata.html
    $this->set('modelsMetadata', function() {
      $production = getenv('APP_ENV') == LtBootstrap::ENV_PRODUCTION;
      $metaData   = new ModelsMetaData([
          "lifetime" => ($production
              ? (24 * 60 * 60) // one  day
              : (60 * 5)       // five min
          ),
          "prefix"   => self::getBaseNs().getenv('APP_ENV')
      ]);
      return $metaData;
    }, true);
  }
  
  protected function viewService() {
    $di = $this->getDI();
    $this->set('view',function() use($di) {
      $viewdir = $di->get('config')->application->viewsDir;
      $view = new View();
      $view->setViewsDir($viewdir);
      $view->registerEngines([
          ".phtml" => PhpViewEngine::class,
          ".php"   => PhpViewEngine::class
      ]);
      return $view;
    }, true);
  }
  
  protected function selectOptionsService() {
    $this->set('selectOptions',function($modelname,$options=null) {
      $e = new EventsManager;
      $s = new SelectOptions($modelname,$options);
      $s->setEventsManager($e);
      return $s;
    });
  }
  
  protected function urlService() {
    $di = $this->getDI();
    $this->set('url',function() use($di) {
      $baseuri = $di->get('config')->application->baseUri;
      $url = new \Phalcon\Mvc\Url;
      $url->setBaseUri($baseuri);
      return $url;
    });
  }
  
  protected function sessionService() {
    $this->set('session', function () {
      $session = new SessionAdapter();
      $session->start();
      return $session;
    }, true);
  }
  
  /**
   * Register the session flash service with the Twitter Bootstrap classes
   */
  protected function flashService() {
    $this->set('flash', function () {
      return new Flash(array(
          'error'   => 'alert alert-danger',
          'success' => 'alert alert-success',
          'notice'  => 'alert alert-info',
          'warning' => 'alert alert-warning'
      ));
    });
  }
  
  protected function loadDbServices() {
    $databaseConfig = $this->getConfig('database',[]);
    foreach ($databaseConfig as $name => $dbconf) {
      $this->set($name, function() use ($dbconf) {
        $conf = $dbconf->toArray();
        unset($conf['adapter']);
        $class = "\Phalcon\Db\Adapter\Pdo\\{$dbconf->adapter}";
        $db = new $class($conf);
        /* @var $db \Phalcon\Db\Adapter\Pdo\Mysql */

        return $db;
      });
    }
  }
  
  public static function getBaseNs() {
    return explode(chr(92),static::class)[0];
  }
}
