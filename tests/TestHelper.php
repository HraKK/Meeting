<?php
use Phalcon\DI,
    Phalcon\DI\FactoryDefault;

ini_set('date.timezone', 'Europe/Kiev');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// требуется для phalcon/incubator
include __DIR__ . "/../vendor/autoload.php";

// Используем автозагрузчик приложений для автозагрузки классов.
// Автозагрузка зависимостей, найденных в composer.
$loader = new \Phalcon\Loader();

$loader->registerNamespaces(
    array(
        'Meetingroom' => "../Meetingroom/"
    )
);

$loader->registerDirs(
    array(
        '../Meetingroom/',
    )
);

$loader->register();

$di = new FactoryDefault();

$application = new \Phalcon\Mvc\Application($di);
$bootstrapper = new \Meetingroom\Bootstrapper\BaseBootstrapper();
$bootstrapper->bootstrap($application);

DI::reset();

DI::setDefault($di);