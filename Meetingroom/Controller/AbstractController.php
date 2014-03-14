<?php

namespace Meetingroom\Controller;

abstract class AbstractController extends \Phalcon\Mvc\Controller
{
    abstract public function indexAction();
    public function initialize()
    {
        $this->view->setTemplateAfter('common');
    }

    public function lastAction()
    {
        $this->flash->notice("These are the latest posts");
    }

}
