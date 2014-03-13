<?php

namespace Meetingroom\Controllers;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        $user = new \Meetingroom\Models\UserModel();
        $user->test();
    }

}