<?php
ini_set('date.timezone', 'Europe/Kiev');
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {

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

    //Create a DI
    $di = new Phalcon\DI\FactoryDefault();


    $application = new \Phalcon\Mvc\Application($di);
    $bootstrapper = new \Meetingroom\Bootstrapper\BaseBootstrapper();
    $bootstrapper->bootstrap($application);
    $application->useImplicitView(false);

    echo $application->handle()->getContent();
} catch (\Phalcon\Exception $e) {
    echo "PhalconException: ", $e->getMessage();
}
