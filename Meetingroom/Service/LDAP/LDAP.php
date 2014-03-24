<?php
namespace Meetingroom\Service\LDAP;

/**
 * Class LDAP
 *
 * @author Denis Maximovskikh <denkin@syneforge.com.ua>
 * @package Meetingroom\Service
 */
class LDAP implements LDAPInterface
{


    /**
     * @var connection to LDAP
     */
    protected $connection;
    protected $filter_str = 'ou=people,dc=syneforge,dc=com';
    protected $_di;

    /**
     * @param \Phalcon\DI\FactoryDefault $di
     */
    public function __construct($di)
    {
        $this->_di = $di;
    }


    /**
     * Create connection to LDAP server
     *
     * @param string $host
     * @param int $port
     * @throws Exception\LDAPException
     * @return resource|false connection resource
     */
    protected function connect($host, $port)
    {
        $this->connection = ldap_connect($host, $port);
        ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        if (false == $this->connection) {
            throw new Exception\LDAPException('Can\'t connect to LDAP server');
        }
    }


    /**
     * Return established connection
     *
     * @throws Exception\LDAPException
     * @return resource
     */
    protected function getConnection()
    {

        if (null == $this->connection) {
            $config = $this->_di->get('config')->LDAP;
            $this->connect($config->host, $config->port);
        }

        return $this->connection;
    }


    /**
     * Method check if user can access to ldap server
     *
     * @param string $nickname
     * @param string $password
     * @return boolean
     */
    protected function checkAccess($nickname, $password)
    {
        return @ldap_bind($this->getConnection(), "uid=" . $nickname . "," . $this->filter_str, $password);
    } 
        

    /**
     * Search user info by nickname
     *
     * @param string $nickname
     * @return array
     */
    protected function searchByNickname($nickname)
    {

        $searchResult = ldap_search($this->getConnection(), $this->filter_str, "uid=" . $nickname);
        $data = ldap_get_entries($this->getConnection(), $searchResult);

        $name = (isset($data[0]["cn"][0])) ? $data[0]["cn"][0] : '';
        $email = (isset($data[0]["mail"][0])) ? $data[0]["mail"][0] : '';
        $position = (isset($data[0]["title"][0])) ? $data[0]["title"][0] : '';

        return new LDAPUser($nickname, $name, $email, $position);
    }


    /**
     * Closing ldap connection
     *
     * @return boolean
     */
    protected function close()
    {
        return ldap_close($this->getConnection());
    }


    /**
     * Method search LDAP user and return full info
     *
     * @param string $nickname ldap nickname
     * @param string $password
     *
     * @throws \Exception if ldap connection problem
     * @return array|false user info
     */
    public function getUserInfo($nickname, $password)
    {
        if (!$nickname || !$password) {
            return false;
        }
        $access = $this->checkAccess($nickname, $password);

        if (false == $access) {
            return false;
        }

        $data = $this->searchByNickname($nickname);
        $this->close();

        return $data;
    }

}