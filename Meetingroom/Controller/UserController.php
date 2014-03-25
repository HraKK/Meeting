<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\User\UserEntity;
use \Meetingroom\Render\Engine\HTMLTemplateEngine;
use \Meetingroom\Render\View\TemplateView;
use \Phalcon\Mvc\Model\Message;

class UserController extends AbstractController
{
    public function indexAction()
    {
        
    }

    public function loginAction()
    {
        if (!$this->isAllowed('user', 'login')) {
            return $this->onDenied();
        }

        $auth = $this->session->has('auth');

        if ($this->request->isPost()) {
            $user = new UserEntity($this->getDI());
            $username = $this->request->getPost("username", "string");
            $password = $this->request->getPost("password", "string");

            if ($user->isValidCredentials($username, $password) == false) {
                $this->flashSession->error('Wrong credentials');
                return $this->response->redirect('/user/login');
            }

            if ($user->isUserExist() == false && $user->createUser() == false) {
                $this->flashSession->error('User not sugned up');
                return $this->response->redirect('/user/login');
            }

            $user->startSession();
            return $this->response->redirect();
        } elseif ($auth ) {
            if($this->request->isAjax() == false) {
                return $this->response->redirect();
            } else {
                $this->view->success = false;
                $this->view->auth = false;
                return $this->render();
            }
        }

        $engine = new HTMLTemplateEngine();
        $view = new TemplateView($this->view);
        $view->setLayout('user/login');

        return $this->render->process($view, $engine);
    }

    public function loginAjaxAction()
    {
        if ($this->request->isAjax() == false) {
            return $this->dispatcher->forward(array('controller' => 'user', 'action' => 'login'));
        }

        $auth = $this->session->has('auth');
        
        if($auth == true) {
            $this->view->success = true;
            $this->view->auth = true;
            return $this->render();
        }

        $user = new UserEntity($this->getDI());
        $username = $this->request->getPost("username", "string");
        $password = $this->request->getPost("password", "string");

        if ($user->isValidCredentials($username, $password) == false) {
            return $this->sendError(new Message('Wrong credentials'));
        }

        if ($user->isUserExist() == false && $user->createUser() == false) {
            return $this->sendError(new Message('User not sugned up'));
        }

        $user->startSession();
        
        $this->view->success = true;
        $this->view->auth = true;
        return $this->render();
    }
    
    public function logoutAction()
    {
        $this->session->destroy();
        if ($this->request->isAjax() == true) {
            $this->view->success = true;
            $this->view->auth = false;
            return $this->render();
        } else {
            return $this->response->redirect('user/login');
        }
    }

}
