<?php

namespace Meetingroom\Controller;

abstract class AbstractController extends \Phalcon\Mvc\Controller
{
    abstract public function indexAction();
    public function initialize()
    {
        if ($this->session->has("user")) {
            $this->dispatcher->forward(array('controller' => 'user', 'action' => 'login'));
        }
        $this->view->setTemplateAfter('common');
    }

    public function lastAction()
    {
        $this->flash->notice("These are the latest posts");
    }

}
