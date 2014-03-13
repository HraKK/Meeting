<?php
//print_r($_SERVER['REQUEST_URI'] ); exit;
try {
    require_once '../Meetingroom/config.php';
    
    $loader = new \Phalcon\Loader();
    
    $loader->registerNamespaces(
        array(
           'Meetingroom\Controllers'    => "../Meetingroom/Controllers/",
           'Meetingroom\Models'    => "../Meetingroom/Models/",
        )
    );
    
    $loader->registerDirs(array(
        '../Meetingroom/',
    ));
    
    $loader->register();

    //Create a DI
    $di = new Phalcon\DI\FactoryDefault();

    $di->set('mydb_con', function() use ($config){
        return new \Phalcon\Db\Adapter\Pdo\Postgresql($config);
    });
    
    $di->set('router', function () {

        $router = new \Phalcon\Mvc\Router();
        $router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);
        $router->add("/", array(
            'namespace' => 'Meetingroom\Controllers',
            'controller' => 'Index',
            'action'     => 'index',
        ));
        
        $router->add("/:controller/:action/:params",
            array(
                "controller" => 1,
                "action"     => 2,
                "params"     => 3,
            ));
        
        $router->setDefaults(array(
            'namespace' => 'Meetingroom\Controllers',
            'controller' => 'index',
            'action' => 'index'
        ));
        
        return $router;
    });
    
    //Setup the view component
    $di->set('view', function(){
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir('../Meetingroom/Views/');
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