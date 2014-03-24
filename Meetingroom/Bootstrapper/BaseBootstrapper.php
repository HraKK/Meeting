<?php
namespace Meetingroom\Bootstrapper;

use \Meetingroom\Entity\User\UserFactory;

/**
 * Class BaseBootstrapper
 * @package Meetingroom\Bootstrapper
 * @author Denis Maximovskikh <denkin.syneforge.com>
 */
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
        $this->initRequest();
        $this->initFlashMessage();
        $this->initUser();
    }


    protected function initConfig()
    {
        $this->di->setShared(
            'config',
            function () {
                return new \Phalcon\Config\Adapter\Ini("../Meetingroom/Config/config.ini");
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
            'db',
            function () use ($di) {
                $params = (array) $di->get('config')->db;
                
                $params['options'] = [
                    \PDO::ATTR_CASE => \PDO::CASE_LOWER, 
                    \PDO::ATTR_PERSISTENT => true,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
                ];
                
                return new \Phalcon\Db\Adapter\Pdo\Postgresql($params);
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
                
                $roleUser = new \Phalcon\Acl\Role("ROLE_USER");
                $roleGuest = new \Phalcon\Acl\Role("ROLE_GUEST");
                $roleOwner = new \Phalcon\Acl\Role("ROLE_OWNER");
                
                $acl->addRole($roleGuest);
                $acl->addRole($roleUser, $roleGuest);
                $acl->addRole($roleOwner, $roleUser);
                
                $userResource = new \Phalcon\Acl\Resource("user");
                $eventResource = new \Phalcon\Acl\Resource("event");
                $indexResource = new \Phalcon\Acl\Resource("index");
                $roomResource = new \Phalcon\Acl\Resource("room");
                
                $acl->addResource($userResource, ['login', 'logout']);
                $acl->addResource($eventResource, ['create', 'update', 'delete']);
                $acl->addResource($indexResource, ['index']);
                $acl->addResource($roomResource, ['read']);

                $acl->allow("ROLE_GUEST", "user", "login");
                
                $acl->allow("ROLE_USER", "user", "logout");
                $acl->allow("ROLE_USER", "event", "create");
                $acl->allow("ROLE_USER", "index", "index");
                $acl->allow("ROLE_USER", "room", "read");
                
                $acl->allow("ROLE_OWNER", "event", "delete");
                $acl->allow("ROLE_OWNER", "event", "update");

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
    
    protected function initRequest()
    {
        $this->di->setShared(
            'request',
            function() {
                return new \Phalcon\Http\Request();
            }
        );
    }
    
    protected function initFlashMessage()
    {
        $this->di->set('flash', function() {
            return new \Phalcon\Flash\Direct();
        });
    }
    
    protected  function initUser()
    {
        $di = $this->di;
        $di->set('user', function() use ($di) {
            $session = $di->get('session');
            $username = $session->get('username');
            return (new UserFactory())->getUser($username);
        });
    }
}