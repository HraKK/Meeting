<?php

namespace Meetingroom\Controller;

use \Meetingroom\Service\LDAP\LDAP;
use \Meetingroom\Entity\User\UserFactory;
use \Meetingroom\Entity\User\UserManager;

class UserController extends \Phalcon\Mvc\Controller
{
    public function loginAction()
    {
        $username = $this->request->getPost("username", "string");
        $password = $this->request->getPost("password", "string");
        
        $ldapUser = $this->getLDAPUser($username, $password);
        
        if($ldapUser === false) {
            die('wrong credentials');
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
        
        $this->session->set('username', $username);
        $this->session->set('userId', $userId);
        
        die('logged in');
    }
    
    public function checkSessionAction()
    {
        $username = $this->session->get('username');
        die($username == null ? 'not authenticated' : 'authenticated');
    }
    
    public function logoutAction()
    {
        $this->session->destroy();
        die('logged out');
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
            die('user not created');
        }
        
        return $userId;
    }
}
