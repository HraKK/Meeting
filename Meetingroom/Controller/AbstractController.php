<?php

namespace Meetingroom\Controller;

abstract class AbstractController extends \Phalcon\Mvc\Controller
{
    abstract public function indexAction();
    public function initialize()
    {
        if (!$this->session->has("auth")) {
            $this->session->set('role', 'ROLE_GUEST');
            $this->dispatcher->forward(array('controller' => 'user', 'action' => 'login'));
        }
        $this->view->setTemplateAfter('common');
    }
}
