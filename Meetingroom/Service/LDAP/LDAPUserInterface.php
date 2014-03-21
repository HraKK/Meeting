<?php
namespace Meetingroom\Service\LDAP;


/**
 * Interface LDAPUserInterface
 * @author Denis Maximovskikh <denkin@syneforge.com.ua>
 * @package Meetingroom\Service\LDAP
 */
interface LDAPUserInterface
{

    /**
     * Return name
     *
     * @return string
     */
    public function getName();

    /**
     * Return nickname
     *
     * @return string
     */
    public function getNickname();

    /**
     * Return email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Return position
     *
     * @return string
     */
    public function getPosition();

}