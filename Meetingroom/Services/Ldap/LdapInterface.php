<?php
namespace Meetingroom\Services\Ldap;


interface LdapInterface{

    /**
     * Create connection to ldap server
     *
     * @param string $host
     * @param int $port
     *
     * @return resource|false connection resource
     */
    public function connect($host='localhost',$port=389);


    /**
     * Return established connection
     *
     * @return resource
     */
    public function getConnection();


    /**
     * Method check if user can access to ldap server
     *
     * @param resource $ldap_connection
     * @param string $nickname
     * @param string $password
     * @return boolean
     */
    public function checkAccess($ldap_connection,$nickname,$password);


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