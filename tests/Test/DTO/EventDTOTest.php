<?php

namespace Test\DTO;

/**
 * Description of EventDTOTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class EventDTOTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dtoProvider
     */
    public function testConstructor(array $fields)
    {
        $event = $this->getMockBuilder('\Meetingroom\Entity\Event\EventEntity')
            ->disableOriginalConstructor()
            ->setMethods(['getFields'])
            ->getMock();
        
        $event->expects($this->once())
            ->method('getFields')
            ->will($this->returnValue($fields));

        $dto = new \Meetingroom\DTO\Event\EventDTO($event);
    }
    
    public function dtoProvider()
    {
        return [
            [[]
            ]
        ];
    }
}
