<?php
namespace Meetingroom\Service\LDAP;


interface LDAPInterface
{

    /**
     * Method search LDAP user and return full info
     *
     * @param string $nickname ldap nickname
     * @param string $password
     *
     * @throws \Exception if ldap connection problem
     * @return array|false user info
     */
    public function getUserInfo($nickname, $password);


}