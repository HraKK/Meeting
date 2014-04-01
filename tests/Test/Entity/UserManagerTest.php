<?php

namespace Test\Entity;

use Meetingroom\Entity\User\UserManager;

class TestUserManager extends UserManager
{
    protected $userModel;
    
    public function setUserModel($userModel)
    {
        $this->userModel = $userModel;
    }
}
/**
 * Description of UserManagerTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class UserManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testUserModel()
    {
        $userManager = new UserManager();
        $this->assertInstanceOf('\Meetingroom\Model\User\UserModel', $userManager->getUserModel());
    }
    
    public function testIdByUsername($username = 'barif')
    {
        $userManager = new TestUserManager();
        
        $userModel = $this->getMockBuilder('\Meetingroom\Model\User\UserModel')
            ->disableOriginalConstructor()
            ->setMethods(['getIdByUsername'])
            ->getMock();
        
        $userModel->expects($this->once())
            ->method('getIdByUsername')
            ->with($username)
            ->will($this->returnValue($username));
        
        $userManager->setUserModel($userModel);
        $this->assertEquals($username, $userManager->getIdByUsername($username));
    }
}
