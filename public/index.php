<?php
// Composer autoloader
require_once realpath(__DIR__.'/../vendor/autoload.php');

// Bootstrap class
require_once realpath(__DIR__.'/../app/library/LtDemo/Application/Bootstrap.php');

new LtDemo\Application\Bootstrap();