<?php

namespace Meetingroom\Controllers;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        $this->view->setVar("param", 'HELLO WORLD');
        $this->view->pick("user/show");
    }

}