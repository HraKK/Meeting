<?php
namespace Test\Controller;

class UnitTest extends \UnitTestCase
{
    public function testTestCase()
    {
        $c = new \Meetingroom\Controller\UserController();
        $c->indexAction();
    }
}
 