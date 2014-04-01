<?php

namespace Test\Entity;

use Meetingroom\Entity\User\UserEntity;

class TestUserEntity extends UserEntity
{
    protected $ldap;
    
    public function __construct()
    {
        
    }
    
    public function setLDAP($ldap)
    {
        $this->ldap = $ldap;
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
}
