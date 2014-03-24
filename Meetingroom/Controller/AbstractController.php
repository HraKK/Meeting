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
    protected $formData = null;
    /**
     * @var null|\Phalcon\Validation\Message\Group
     */
    protected $formErrors = null;

    abstract public function indexAction();

    public function initialize()
    {
        $this->view->setTemplateAfter('common');
    }

    /**
     * @param string $field
     * @return string|null
     */
    public function getData($field)
    {
        return (isset($this->formData->$field)) ? $this->formData->$field : null;
    }

    /**
     * @param bool $obj true for return object
     * @param array $fields
     * @return array|stdClass
     */
    public function getFormData($obj = false, array $fields = [])
    {
        $array = $this->validator->validate($_REQUEST);
        $fields = (empty($fields)) ? array_keys($_REQUEST) : $fields;

        if (count($array)) {
            $this->formErrors = $array;
        }

        $returnObj = new \stdClass();
        $return = [];
        foreach ($fields as $key => $value) {
            $return[$value] = $this->validator->getValue($value);
            $returnObj->$value = $return[$value];
        }
        return ($obj) ? $returnObj : $return;
    }

    /**
     * @return array
     */
    public function getFormErrors()
    {
        $return = [];
        if (count($this->formErrors)) {
            foreach ($this->formErrors as $message) {
                $return = new \Meetingroom\DTO\Errors\InputDataDTO([
                    'field' => $message->getField(),
                    'message' => $message->getMessage()
                ]);
            }
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
