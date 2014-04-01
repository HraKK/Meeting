<?php

namespace Test\Entity;

use Meetingroom\Entity\Role\Group;
/**
 * Description of RoleFactoryTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class RoleFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider roleInEventProvider
     */
    public function testRoleInEvent($userId, $ownerId, $expect)
    {
        $user = $this->getMockBuilder('\Meetingroom\Entity\User\AuthorizedEntity')
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();
        
        $user->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($userId));
        
        $event = $this->getMockBuilder('\Meetingroom\Entity\Event\EventEntity')
            ->disableOriginalConstructor()
            ->setMethods(['getOwnerId'])
            ->getMock();
        
        $event->expects($this->any())
            ->method('getOwnerId')
            ->will($this->returnValue($ownerId));
        
        $factory = new \Meetingroom\Entity\Role\RoleFactory();
        $this->assertEquals($expect, $factory->getRoleInEvent($user, $event));
    }
    
    public function roleInEventProvider()
    {
        return [
            [ 0, 0, Group::GUEST],
            [ null, 0, Group::GUEST],
            [ 1, 2, Group::USER],
            [ 1, 1, Group::OWNER]
        ];
    }
    
    /**
     * @dataProvider getRoleProvider
     */
    public function testGetRole($userId, $expect)
    {
        $user = $this->getMockBuilder('\Meetingroom\Entity\User\AuthorizedEntity')
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();
        
        $user->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($userId));
        
        $factory = new \Meetingroom\Entity\Role\RoleFactory();
        $this->assertEquals($expect, $factory->getRole($user));
    }
    
    public function getRoleProvider()
    {
        return [
            [ 0, Group::GUEST],
            [ null, Group::GUEST],
            [ false, Group::GUEST],
            [ 1, Group::USER],
        ];
    }
}
