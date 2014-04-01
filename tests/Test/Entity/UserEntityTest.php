<?php

namespace Test\Entity;

use Meetingroom\Entity\User\UserEntity;

class TestUserEntity extends UserEntity
{
    protected $ldap;
    protected $userManager;
    protected $userFactory;
    
    public function __construct()
    {
        
    }
    
    public function setLDAP($ldap)
    {
        $this->ldap = $ldap;
    }
    
    public function setUserManager($userManager)
    {
        $this->userManager = $userManager;
    }
    
    public function setUserFactory($userFactory)
    {
        $this->userFactory = $userFactory;
    }
}

/**
 * Description of UserEntityTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class UserEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider isValidCredentials1Provider
     */
    public function testIsValidCredentials1($username, $password)
    {
        $ldap = $this->getMockBuilder('\Meetingroom\Service\LDAP\LDAP')
            ->disableOriginalConstructor()
            ->setMethods(['getUserInfo'])
            ->getMock();
        
        $ldap->expects($this->once())
            ->method('getUserInfo')
            ->with($username, $password)
            ->will($this->returnValue(false));
        
        $user = new TestUserEntity();
        $user->setLDAP($ldap);
        $this->assertFalse($user->isValidCredentials($username, $password));
    }
    
    public function isValidCredentials1Provider()
    {
        return [
            ['barif', 'pass']
       ];
    }
    
    /**
     * @dataProvider isValidCredentials2Provider
     */
    public function testIsValidCredentials2($username, $password)
    {
        $ldap = $this->getMockBuilder('\Meetingroom\Service\LDAP\LDAP')
            ->disableOriginalConstructor()
            ->setMethods(['getUserInfo'])
            ->getMock();
        
        $ldap->expects($this->never())
            ->method('getUserInfo');
        
        $user = new TestUserEntity();
        $user->setLDAP($ldap);
        $this->assertFalse($user->isValidCredentials($username, $password));
    }
    
    public function isValidCredentials2Provider()
    {
        return [
            ['', 0],
            [null, false],
            [0.0, []],
            ['0', ''],
       ];
    }
    
    /**
     * @dataProvider isValidCredentials3Provider
     */
    public function testIsValidCredentials3($username, $password, $name, $position, $nickname)
    {
        $ldap = $this->getMockBuilder('\Meetingroom\Service\LDAP\LDAP')
            ->disableOriginalConstructor()
            ->setMethods(['getUserInfo'])
            ->getMock();
        
        $ldapUSER = $this->getMockBuilder('\Meetingroom\Service\LDAP\LDAPUser')
            ->disableOriginalConstructor()
            ->setMethods(['getName', 'getPosition', 'getNickname'])
            ->getMock();
        
        $ldapUSER->expects($this->once())
            ->method('getName')
            ->will($this->returnValue($name));
        
        $ldapUSER->expects($this->once())
            ->method('getPosition')
            ->will($this->returnValue($position));
        
        $ldapUSER->expects($this->once())
            ->method('getNickname')
            ->will($this->returnValue($nickname));
        
        $ldap->expects($this->once())
            ->method('getUserInfo')
            ->with($username, $password)
            ->will($this->returnValue($ldapUSER));
        
        $user = new TestUserEntity();
        $user->setLDAP($ldap);
        $this->assertTrue($user->isValidCredentials($username, $password));
        $this->assertEquals($name, $user->getName());
        $this->assertEquals($position, $user->getPosition());
        $this->assertEquals($nickname, $user->getNickname());
        $this->assertEquals($username, $user->getUsername());
    }
    
    public function isValidCredentials3Provider()
    {
        return [
            ['barif', 'pass', 'name', 'position', 'nickname']
       ];
    }
    
    public function testConstruct()
    {
        $di = $this->getMockBuilder('\Phalcon\DI')
            ->disableOriginalConstructor()
            ->getMock();
        
        $user = new UserEntity($di);
        $this->assertInstanceOf('\Meetingroom\Entity\User\UserManager', $user->getUserManager());
        $this->assertInstanceOf('\Meetingroom\Service\LDAP\LDAP', $user->getLDAP());
        $this->assertInstanceOf('\Meetingroom\Entity\User\UserFactory', $user->getUserFactory());
    }
    
    /**
     * @dataProvider isUserExistProvider
     */
    public function testisUserExist($userId, $result)
    {
        $userManager = $this->getMockBuilder('\Meetingroom\Service\LDAP\LDAP')
            ->disableOriginalConstructor()
            ->setMethods(['getIdByUsername'])
            ->getMock();
        
        $userManager->expects($this->once())
            ->method('getIdByUsername')
            ->with(null)
            ->will($this->returnValue($userId));
        
        $user = new TestUserEntity();
        $user->setUserManager($userManager);
        $this->assertEquals($result, $user->isUserExist());
    }
    
    public function isUserExistProvider()
    {
        return [
            [false, false],
            [1, true],
            ['barif', true],
            
       ];
    }
    
    public function testStartSession()
    {
        $di = $this->getMockBuilder('\Phalcon\DI')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        
        $session = $this->getMockBuilder('\Phalcon\DI')
            ->disableOriginalConstructor()
            ->setMethods(['set'])
            ->getMock();
        
        $session->expects($this->exactly(3))
            ->method('set');
                
        $di->expects($this->once())
            ->method('get')
            ->with('session')
            ->will($this->returnValue($session));
        
        $user = new UserEntity($di);
        $user->startSession();
    }
    
    /**
     * @dataProvider createUser1Provider
     */
    public function testCreateUser1($expectData, $userId, $result)
    {
        $userFactory = $this->getMockBuilder('\Meetingroom\Entity\User\UserFactory')
            ->disableOriginalConstructor()
            ->setMethods(['getUser'])
            ->getMock();
        
        $user = $this->getMockBuilder('\Meetingroom\Entity\User\AuthorizedEntity')
            ->disableOriginalConstructor()
            ->setMethods(['bind', 'insert'])
            ->getMock();
        
        $userFactory->expects($this->once())
            ->method('getUser')
            ->with(null)
            ->will($this->returnValue($user));
        
        $user->expects($this->once())
            ->method('bind')
            ->with($expectData)
            ->will($this->returnSelf());        

        $user->expects($this->once())
            ->method('insert')
            ->will($this->returnValue($userId));        
        
        $userEntity = new TestUserEntity();
        $userEntity->setUserFactory($userFactory);
        $this->assertEquals($result, $userEntity->createUser());
    }
    
    public function createUser1Provider()
    {
        return [
            [[
                'name' => null,
                'phone' => 'phone',
                'position' => null,
                'nickname' => null
            ], false, false],
            [[
                'name' => null,
                'phone' => 'phone',
                'position' => null,
                'nickname' => null
            ], 123, true],
       ];
    }
    
}
