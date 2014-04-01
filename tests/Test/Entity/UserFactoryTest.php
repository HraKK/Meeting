<?php

namespace Test\Entity;

use Meetingroom\Entity\User\UserFactory;

class TestUserFactory extends UserFactory
{
    public $guestEntity;
    public $userModel;
    public $authorizedEntity;
    
    public function getGuestEntity()
    {
        return $this->guestEntity;
    }

    public function getUserModel()
    {
        return $this->userModel;
    }
    
    public function getAuthorizedEntity()
    {
        return $this->authorizedEntity;
    }
}

/**
 * Description of UserfactoryTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class UserFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getGuestProvider
     */
    public function testGuest($username, $modelReturn)
    {
        $model = $this->getMockBuilder('\Meetingroom\Model\User\UserModel')
            ->disableOriginalConstructor()
            ->setMethods(['getIdByUsername'])
            ->getMock();
        
        $model->expects($this->once())
            ->method('getIdByUsername')
            ->with($username)
            ->will($this->returnValue($modelReturn));
        
        $guest = $this->getMockBuilder('\Meetingroom\Entity\User\GuestEntity')
            ->disableOriginalConstructor()
            ->getMock();
        
        $factory = new TestUserFactory();
        $factory->userModel = $model;
        $factory->guestEntity = $guest;
        $this->assertInstanceOf('\Meetingroom\Entity\User\GuestEntity', $factory->getUser($username));
    }
    
    public function getGuestProvider()
    {
        return [
            ['guest', false]
       ];
    }
    
    /**
     * @dataProvider getUserProvider
     */
    public function testUser($username, $modelReturn)
    {
        $model = $this->getMockBuilder('\Meetingroom\Model\User\UserModel')
            ->disableOriginalConstructor()
            ->setMethods(['getIdByUsername'])
            ->getMock();
        
        $model->expects($this->once())
            ->method('getIdByUsername')
            ->with($username)
            ->will($this->returnValue($modelReturn));
        
        $authorizedEntity = $this->getMockBuilder('\Meetingroom\Entity\User\AuthorizedEntity')
            ->disableOriginalConstructor()
            ->setMethods(['loadByUsername'])
            ->getMock();
        
        $authorizedEntity->expects($this->once())
            ->method('loadByUsername')
            ->with($username)
            ->will($this->returnValue(true));
        
        $factory = new TestUserFactory();
        $factory->userModel = $model;
        $factory->authorizedEntity = $authorizedEntity;
        $this->assertInstanceOf('\Meetingroom\Entity\User\AuthorizedEntity', $factory->getUser($username));
    }
    
    public function getUserProvider()
    {
        return [
            ['user', true]
       ];
    }
    
    public function testObjects()
    {
        $factory = new UserFactory();
        $guest = $factory->getGuestEntity();
        $this->assertInstanceOf('\Meetingroom\Entity\User\GuestEntity', $guest);
        $this->assertNull($guest->getId());
        $this->assertInstanceOf('\Meetingroom\Entity\User\AuthorizedEntity', $factory->getAuthorizedEntity());
        $this->assertInstanceOf('\Meetingroom\Model\User\UserModel', $factory->getUserModel());
    }
}
