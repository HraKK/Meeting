<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Role\RoleFactory;
use \Meetingroom\Entity\Role\Group;
use \Meetingroom\Render\Engine\JSONEngine;
use \Meetingroom\Render\Engine\EnginableInterface;
use \Meetingroom\Render\Render;
use Phalcon\Http\Client\Exception;

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

    /**
     * @var null|\Meetingroom\Render\Engine\EnginableInterface
     */
    protected $engine = null;

    /**
     * @var null|\Meetingroom\Render\Render
     */
    protected $render = null;

    abstract public function indexAction();
    public function initialize()
    {
        $this->engine = new JSONEngine();
        $this->render = new Render();
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
        $rawData = json_decode(file_get_contents("php://input"), true);
        $array = $this->validator->validate($rawData);
        $fields = (empty($fields)) ? array_keys($rawData) : $fields;

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
                $return[] = new \Meetingroom\DTO\Errors\InputDataDTO([
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
            return $this->dispatcher->forward(array('controller' => 'user', 'action' => 'login'));
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

    /**
     * @param \Phalcon\Mvc\Model\Message $error
     */
    protected function sendError(\Phalcon\Mvc\Model\Message $error)
    {
        $this->sendErrors([$error]);
    }

    /**
     * @param array $errors of \Phalcon\Mvc\Model\Message
     * @throws \Phalcon\Http\Client\Exception
     */
    protected function sendErrors(array $errors)
    {
        $errorsDTO = [];

        foreach ($errors as $error) {
            if (!($error instanceof \Phalcon\Mvc\Model\Message)) {
                throw new Exception('message must be istanceof \Phalcon\Mvc\Model\Message');
            }

            $errorsDTO[] = new \Meetingroom\DTO\Errors\InputDataDTO([
                'message' => $error->getMessage(),
                'field' => $error->getField()
            ]);
        }

        $this->view->success = false;
        $this->view->errors = $errorsDTO;
        return $this->render();
    }

    protected function sendOutput(array $content)
    {
        echo json_encode($content);
        exit;
    }

    public function render(EnginableInterface $engine = null)
    {
        if ($engine == null) {
            $engine = $this->engine;
        }

        return $this->render->process($this->view, $engine);
    }

}
