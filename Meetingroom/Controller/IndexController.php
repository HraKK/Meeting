<?php

namespace Meetingroom\Controller;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        $this->view->setVar("param", 'HELLO WORLD');
        echo 123;
//        $this->view->pick("user/show");
    }

    public function showAction()
    {
        $this->view->setVar("param", 'HELLO WORLD');
        echo 234;
//        $this->view->render("user/show", []);
//        $this->view->pick("user/show");
    }

}
