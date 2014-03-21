<?php
use Phalcon\DI,
    Phalcon\DI\FactoryDefault;

ini_set('date.timezone', 'Europe/Kiev');
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../vendor/autoload.php";
//
//$di = new FactoryDefault();
//
//$application = new \Phalcon\Mvc\Application($di);
//$bootstrapper = new \Meetingroom\Bootstrapper\BaseBootstrapper();
//$bootstrapper->bootstrap($application);
//
//DI::reset();
//
//DI::setDefault($di);