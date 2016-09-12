<?php
namespace LtDemo\Di;

use Phalcon\Di\Injectable as PhInjectable;

/**
 * The only purpose this class servs is for the @property statments bellow
 * allowing our IDE's to access phalcon dependency injector services easily
 * as we add new services add a property line to this comment and the IDE will pick it up.
 * 
 * @property \Logikos\Auth\Manager        $auth
 * @property \Logikos\Http\Request        $request
 * @property \Logikos\Http\Response       $response
 * @property \Logikos\Forms\selectOptions $selectoptions
 */
class Injectable extends PhInjectable {
  
}