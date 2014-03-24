<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\User\UserEntity;

class UserController extends AbstractController
{
    public function indexAction()
    {
        
    }
    
    public function loginAction()
    {
        if(!$this->isAllowed('user', 'login')) {
            $this->onDenied();
        }
        
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        
        $auth = $this->session->has('auth');

        if($this->request->isPost()) {
            $user = new UserEntity($this->getDI());
            $username = $this->request->getPost("username", "string");
            $password = $this->request->getPost("password", "string");
            
            if($user->isValidCredentials($username, $password) == false) {
                $this->flashSession->error('Wrong credentials');
                header('Location: /user/login');
                exit;
            }
            
            if($user->isUserExist() == false && $user->createUser() == false) {
                $this->flashSession->error('User not sugned up');
                header('Location: /user/login');
                exit;
            }
            
            $user->startSession();
            $this->response->redirect();
            
        } elseif($auth) {
            $this->response->redirect();
        }
    }
    
    
    public function logoutAction()
    {
        if(!$this->isAllowed('user', 'logout')) {
            $this->onDenied();
        }
        
        $this->session->destroy();
        $this->flashSession->error("logged out");
        $this->response->redirect('user/login');
    }
}
