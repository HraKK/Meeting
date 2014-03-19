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
        $userManager = new UserManager();
        $userId = $userManager->getIdByUsername($username);
        
        if ($userId === false) {
            $userId = $this->createUser($username);
        }
        
        $this->session->set('username', $username);
        var_dump($userId);
        exit;
    }
    
    protected function createUser($username)
    {
        $userFactory = new UserFactory();
        $user = $userFactory->getUser($username); 
        $di = $this->getDI();
        $ldap = new LDAP($di);
        
        $password = $this->request->getPost("password", "string");
        
        $LDAPUser = $ldap->getUserInfo($username, $password);
        if($LDAPUser === false) {
            die('wrong credentials');
        }
        
        $userId = $user->bind([
            'name' => $LDAPUser->getName(),
            'phone' => '',
            'position' => $LDAPUser->getPosition(),
            'nickname' => $LDAPUser->getNickname()
        ])->save();
        
        if(!$userId) {
            die('user not created');
        }
        
        return $userId;
    }
}
