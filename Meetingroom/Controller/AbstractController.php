<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Role\RoleFactory;
use \Meetingroom\Entity\Role\Group;

abstract class AbstractController extends \Phalcon\Mvc\Controller
{
    protected $roleFactory = null;
    
    abstract public function indexAction();
    
    public function initialize()
    {
        $this->view->setTemplateAfter('common');
    }
    
    public function permitOrDie($resource, $action, $role = null)
    {
        if($role === null) {
            $role = $this->getRoleFactory()->getRole($this->user);
        }

        $allow = $this->acl->isAllowed($role, $resource, $action);
        if(!$allow && $role == Group::GUEST && $resource!='user' && $action!='login') {
            $this->dispatcher->forward(array('controller' => 'user', 'action' => 'login'));
        } elseif(!$allow) {
            die('Not permitted');
        }
    }
    
    protected function getRoleFactory()
    {
        return $this->roleFactory === null ? new RoleFactory() : $this->roleFactory;
    }
}
