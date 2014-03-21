<?php
namespace Test\Controller;

class AbstractController extends \UnitTestCase
{
    public function testDepencyInjection()
    {
       $this->assertNotNull($this->di);
    }
    
    public function testIndexAction()
    {
        $controller = new \Meetingroom\Controller\EventController();
        $this->assertEmpty($controller->indexAction());
    }
    
    public function testCreateAction()
    {
        $controller = new \Meetingroom\Controller\EventController();
        $this->assertEmpty($controller->createAction());
        
    }
    
    public function testUpdateAction()
    {
    }
    
    public function testDeleteAction()
    {
    }
    
}
