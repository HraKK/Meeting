<?php
namespace Meetingroom\Bootstrapper;

class BaseBootstrapper implements BootstrapperInterface
{

    protected $application;
    protected $di;

    public function bootstrap(\Phalcon\Mvc\Application $application)
    {
        $this->application = $application;
        $this->di = $this->application->getDI();

        $this->initConfig();
        $this->initRouter();
        $this->initDB();
        $this->initView();
        $this->initURL();
        $this->initACL();
        $this->initSession();
    }


    protected function initConfig()
    {
        $this->di->setShared(
            'config',
            function () {
                return new \Phalcon\Config\Adapter\Ini("../Meetingroom/config.ini");
            }
        );
    }


    protected function initRouter()
    {
        $this->di->set(
            'router',
            function () {

                $router = new \Phalcon\Mvc\Router();
                $router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);
                $router->add(
                    "/",
                    array(
                        'namespace' => 'Meetingroom\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    )
                );

                $router->add(
                    "/:controller/:action/:params",
                    array(
                        "controller" => 1,
                        "action" => 2,
                        "params" => 3,
                    )
                );

                $router->setDefaults(
                    array(
                        'namespace' => 'Meetingroom\Controller',
                        'controller' => 'index',
                        'action' => 'index'
                    )
                );

                return $router;
            }
        );
    }

    protected function initDB()
    {
        $di = $this->di;
        $this->di->set(
            'mydb_con',
            function () use ($di) {
                return new \Phalcon\Db\Adapter\Pdo\Postgresql((array)$di->get('config')->db);
            }
        );

    }

    protected function initView()
    {
        $this->di->set(
            'view',
            function () {
                $view = new \Phalcon\Mvc\View();
                $view->setViewsDir('../Meetingroom/View/');
                return $view;
            }
        );
    }

    protected function initURL()
    {
        $this->di->set(
            'url',
            function () {
                $url = new \Phalcon\Mvc\Url();
                $url->setBaseUri('/');
                return $url;
            }
        );
    }

    protected function initACL()
    {
        $this->di->setShared(
            'acl',
            function () {
                $acl = new \Phalcon\Acl\Adapter\Memory();
                $acl->setDefaultAction(\Phalcon\Acl::DENY);
                $roleUsers = new \Phalcon\Acl\Role("Users");
                $roleGuests = new \Phalcon\Acl\Role("Guests");
                $acl->addRole($roleGuests);
                $acl->addRole($roleUsers);
                $userResource = new \Phalcon\Acl\Resource("User");
                $acl->addResource($userResource, ['index', 'test']);

                $acl->allow("Guests", "User", "index");
                $acl->allow("Users", "User", "test");
                return $acl;
            }
        );
    }

    protected function initSession()
    {
        $this->di->setShared(
            'session',
            function () {
                $session = new \Phalcon\Session\Adapter\Files();
                $session->start();
                return $session;
            }
        );
    }
}