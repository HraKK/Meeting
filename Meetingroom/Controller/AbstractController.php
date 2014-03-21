<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Role\RoleFactory;
use \Meetingroom\Entity\Role\Group;

abstract class AbstractController extends \Phalcon\Mvc\Controller
{
    protected $roleFactory = null;
    /**
     * @var Phalcon\Validation
     */
    protected $validator = null;

    abstract public function indexAction();
    
    public function initialize()
    {
        $this->view->setTemplateAfter('common');
    }


    /**
     * @param array $fields
     * @return array|\Phalcon\Validation\Message\Group
     */
    public function getFormData(array $fields = [])
    {
        $array = $this->validator->validate($_REQUEST);
        $fields = (empty($fields)) ? array_keys($_REQUEST) : $fields;

        if (count($array)) {
            $this->validator->getMessages();
        }

        $return = [];
        foreach ($fields as $key => $value) {
            $return[$value] = $this->validator->getValue($value);
        }
        return $return;
    }

    public function onDenied()
    {
        $role = $this->getRoleFactory()->getRole($this->user);

        if ($role == Group::GUEST) {
            $this->dispatcher->forward(array('controller' => 'user', 'action' => 'login'));
        } elseif ($role == Group::USER) {
            $response = $this->getDI()->getShared('response');
            $response->resetHeaders()
                ->setStatusCode(403, null)
                ->setContent('Denied')
                ->send();
        }
    }

    public function isAllowed($resource, $action, $role = null)
    {
        if ($role === null) {
            $role = $this->getRoleFactory()->getRole($this->user);
        }

        return $this->acl->isAllowed($role, $resource, $action);
    }

    protected function getRoleFactory()
    {
        return $this->roleFactory === null ? new RoleFactory() : $this->roleFactory;
    }

    protected function sendError($msg)
    {
        $this->sendOutput(['msg' => $msg, 'success' => false]);
    }

    protected function sendOutput(array $content)
    {
        echo json_encode($content);
        exit;
    }
}
