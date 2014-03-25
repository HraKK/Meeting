<?php
namespace Test\Entity;


class EventTest extends \PHPUnit_Framework_TestCase
{

    public function testAbstractModelConstructException()
    {
        $this->setExpectedException('\Phalcon\Exception');
        $event = new \Meetingroom\Entity\Event\EventEntity(1);
    }
    
    public function testAbstractModelConstruct()
    {
        $stub = $this->getMock('\Meetingroom\Entity\Event\EventEntity');
        
        $stub->expects($this->any())
             ->method('doSomething')
             ->will($this->returnValue('foo'));
 
        $this->assertEquals('foo', $stub->doSomething());
//        $event = new \Meetingroom\Entity\Event\EventEntity(1);
//        $this->assertNotEmpty($event->getProperties());
//        $this->assertEmpty($event->getDTO(), "");
    }
}
 