<?php

namespace Meetingroom\Entity\User;

use \Meetingroom\Service\LDAP\LDAP;

/**
 * Description of UserEntity
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class UserEntity
{
    protected $username;
    protected $userId;
    protected $ldap;
    protected $userManager;
    protected $userFactory;
    protected $di;
    
    protected $name;
    protected $position;
    protected $nickname;
    
    public function __construct(\Phalcon\DI $di)
    {
        $this->di = $di;
    }
    
    protected function getLDAP()
    {
        if($this->ldap !== null) {
            return $this->ldap;
        }
        
        $this->ldap = new LDAP($this->di);
        return $this->ldap;
    }
    
    protected function getUserManager()
    {
        return ($this->userManager = $this->userManager === null ? new UserManager($this->id) : $this->userManager);
    }
    
    protected function getUserFactory()
    {
        return ($this->userFactory = $this->userFactory === null ? new UserFactory() : $this->userFactory);
    }
    
    public function isValidCredentials($username, $password) 
    {
        if(empty($username) || empty($password)) {
            return false;
        }
        
        $ldap = $this->getLDAP();
        $ldapUser = $ldap->getUserInfo($username, $password);

        if($ldapUser === false) {
            return false;
        }
        
        $this->username = $username;
        $this->name = $ldapUser->getName();
        $this->position = $ldapUser->getPosition();
        $this->nickname = $ldapUser->getNickname();
        
        return true;
    }
    
    public function isUserExist()
    {
        $userManager = $this->getUserManager();
        $userId = $userManager->getIdByUsername($this->username);
        return ($userId === false) ? false : true;
    }

    public function startSession()
    {
        $session = $this->di->get('session');
        $session->set('auth', true);
        $session->set('username', $this->username);
        $session->set('userId', $this->userId);
    }
    
    protected function createUser()
    {
        $userFactory = $this->getUserFactory();
        $user = $userFactory->getUser($this->username); 

        $userId = $user->bind([
            'name' => $this->name,
            'phone' => 'phone',
            'position' => $this->position,
            'nickname' => $this->username
        ])->insert();
        
        if(!$userId) {
            return false;
        }
        
        $this->userId = $userId;
        return true;
    }
}
