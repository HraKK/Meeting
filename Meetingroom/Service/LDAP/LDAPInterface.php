<?php
namespace Meetingroom\Service\LDAP;


interface LDAPInterface{

    /**
     * Return established connection
     *
     * @return resource
     */
    public function getConnection();


    /**
     * Method check if user can access to ldap server
     *
     * @param string $nickname
     * @param string $password
     * @return boolean
     */
    public function checkAccess($nickname, $password);


    /**
     * Search user info by nickname
     *
     * @param string $nickname
     * @return array
     */
    public function searchByNickname($nickname);


    /**
     * Closing ldap connection
     *
     * @return mixed
     */
    public function close();







} 