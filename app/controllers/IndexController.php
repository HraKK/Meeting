<?php

class IndexController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $user = new User();
        $user->test();
    }

}