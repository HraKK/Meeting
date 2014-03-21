<?php
namespace Test\Entity;


class EventTest extends \PHPUnit_Framework_TestCase
{

    public function testEvent()
    {
        //var_dump($this->di);
        //$dto = new \Meetingroom\DTO\Event\EventDTO([]);
        $event = new \Meetingroom\Entity\Event\EventEntity(1);

        //var_dump();

        $this->assertNotEmpty($event->getProperties());

        $this->assertEmpty($event->getDTO(), "");
        //var_dump($this->di);
    }
}
 