<?php

namespace Test\Entity;

/**
 * Description of AbstractEntityTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class AbstractEntityTest extends \PHPUnit_Framework_TestCase
{

    public function testAbstractEntity()
    {
//        $mock = $this->getMockBuilder('\Meetingroom\Entity\AbstractEntity')
//            ->setMethods(['ownerId', 'getNumber', 'getString'])
//            ->disableOriginalConstructor()
//            ->getMock();
//         $mock->expects($this->any())
//            ->method('ownerId')
//            ->will($this->returnValue(123));
//         $mock->expects($this->any())
//            ->method('getNumber')
//            ->will($this->returnValue(1));
//         $mock->expects($this->any())
//            ->method('getString')
//            ->will($this->returnValue('string'));
//         $mock->getString();
//         var_dump($mock->ownerId());
//         exit;
        
        $mock = $this->getMockBuilder('\Meetingroom\Model\Event\EventModel')
            ->setMethods(['read'])
            ->disableOriginalConstructor()
            ->getMock();
                
        $mock->expects($this->any())
            ->method('read')
            ->will($this->returnValue([]));

        $mock->read();
//        $this->setExpectedException('\Phalcon\Exception');
//        $event = new \Meetingroom\Entity\Event\EventEntity(1);
//        $this->assertCount($this->equalTo(0), $event->getProperties());
//        $this->assertEmpty($event->getDTO(), "");
    }
}
