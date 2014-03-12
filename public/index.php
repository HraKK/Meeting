<?php
//print_r($_SERVER['REQUEST_URI'] ); exit;
try {
    require_once '../app/config.php';
    
    //Register an autoloader
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(array(
        '../app/controllers/',
        '../app/models/'
    ))->register();

    //Create a DI
    $di = new Phalcon\DI\FactoryDefault();

    $di->set('mydb_con', function() use ($config){
        return new \Phalcon\Db\Adapter\Pdo\Postgresql($config);
    });
    
    $di->set('router', function () {

        $router = new \Phalcon\Mvc\Router();
        $router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);
        $router->add("/test", array(
            'controller' => 'User',
            'action'     => 'test',
        ));

        return $router;
    });
    
    //Setup the view component
    $di->set('view', function(){
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir('../app/views/');
        return $view;
    });

    //Setup a base URI so that all generated URIs include the "tutorial" folder
    $di->set('url', function(){
        $url = new \Phalcon\Mvc\Url();
        $url->setBaseUri('/');
        return $url;
    });

    //Handle the request
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

} catch(\Phalcon\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}