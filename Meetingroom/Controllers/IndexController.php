<?php

namespace Meetingroom\Controllers;

class IndexController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        $user = new \Meetingroom\Models\User();
        $user->test();
    }

}