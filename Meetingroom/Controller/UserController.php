<?php

namespace Meetingroom\Controller;

use \Meetingroom\Service\LDAP\LDAP;
use \Meetingroom\Entity\User\UserFactory;
use \Meetingroom\Entity\User\UserManager;

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
            $this->checkCredentials();
        } elseif($auth) {
            $this->response->redirect();
        }
    }
    
    protected function checkCredentials() 
    {
        $username = $this->request->getPost("username", "string");
        $password = $this->request->getPost("password", "string");

        if(empty($username) || empty($password)) {
            return $this->flashSession->error("username and password SHOULD NOT be empty");
        }
        
        $ldapUser = $this->getLDAPUser($username, $password);
        
        if($ldapUser === false) {
            return $this->flashSession->error("wrong credentials");
        }
        
        $userManager = new UserManager();
        $userId = $userManager->getIdByUsername($username);
        
        if ($userId === false) {
            $userId = $this->createUser(
                $ldapUser->getName(),
                'phone',
                $ldapUser->getPosition(),
                $ldapUser->getNickname()
            );
        }
        
        $this->session->set('auth', true);
        $this->session->set('username', $username);
        $this->session->set('userId', $userId);
        
        $this->response->redirect();
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
    
    protected function getLDAPUser($username, $password)
    {
        $di = $this->getDI();
        $ldap = new LDAP($di);
        return $ldap->getUserInfo($username, $password);
    }
    
    protected function createUser($name, $phone, $position, $username)
    {
        $userFactory = new UserFactory();
        $user = $userFactory->getUser($username); 

        $userId = $user->bind([
            'name' => $name,
            'phone' => $phone,
            'position' => $position,
            'nickname' => $username
        ])->insert();
        
        if(!$userId) {
            $this->flashSession->error("user not signed up");
            $this->response->redirect('user/login');
        }
        
        return $userId;
    }
}
