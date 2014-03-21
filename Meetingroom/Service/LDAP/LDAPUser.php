<?php
namespace Meetingroom\Service\LDAP;

/**
 * Class LDAPUser
 * @author Denis Maximovskikh <denkin@syneforge.com.ua>
 * @package Meetingroom\Service\LDAP
 */
class LDAPUser implements LDAPUserInterface
{

    protected $name;
    protected $nickname;
    protected $email;
    protected $position;

    /**
     *
     * @param $nickname
     * @param $name
     * @param $email
     * @param $position
     *
     * @return \Meetingroom\Service\LDAP\LDAPUser
     */
    public function __construct($nickname, $name, $email, $position)
    {
        $this->nickname = $nickname;
        $this->name = $name;
        $this->email = $email;
        $this->position = $position;
    }

    /**
     * Return name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Return nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }


    /**
     * Return email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Return position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }


}