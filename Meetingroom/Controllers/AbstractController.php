<?php

namespace Meetingroom\Controllers;

abstract class AbstractController extends \Phalcon\Mvc\Controller
{
    abstract public function indexAction();
}