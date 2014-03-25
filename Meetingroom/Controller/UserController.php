<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\User\UserEntity;
use \Meetingroom\View\Engine\HTMLTemplateEngine;
use \Meetingroom\View\Render;

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
        
        $auth = $this->session->has('auth');

        if($this->request->isPost()) {
            $user = new UserEntity($this->getDI());
            $username = $this->request->getPost("username", "string");
            $password = $this->request->getPost("password", "string");
            
            if($user->isValidCredentials($username, $password) == false) {
                $this->flashSession->error('Wrong credentials');
                return $this->response->redirect('/user/login');
            }
            
            if($user->isUserExist() == false && $user->createUser() == false) {
                $this->flashSession->error('User not sugned up');
                return $this->response->redirect('/user/login');
            }
            
            $user->startSession();
            return $this->response->redirect();
            
        } elseif($auth) {
            return $this->response->redirect();
        }
        
        $engine = new HTMLTemplateEngine();
        $engine->setLayer('user/login.php');
        $render = new Render();
        
        return $render->process($this->view, $engine);
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
