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
    protected  $connection;

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
     * @throws Meetingroom\Service\LDAP\LDAPException
     * @return resource
     */
    public function getConnection()
    {
        if (!is_resource($this->connection)) {
            try {
                $this->connect('ldap.syneforge.com', 389);
            } catch (Exception\LDAPException $e) {
                throw $e;
            }
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
    public function checkAccess($nickname, $password)
    {
        return ldap_bind($this->getConnection(), "uid=" . $nickname . ",ou=people,dc=syneforge,dc=com", $password);
    }


    /**
     * Search user info by nickname
     *
     * @param string $nickname
     * @return array
     */
    public function searchByNickname($nickname)
    {

        $searchResult = ldap_search($this->getConnection(), "ou=people,dc=syneforge,dc=com", "uid=" . $nickname);
        $data = ldap_get_entries($this->getConnection(), $searchResult);

        return [
            'cn' => $data[0]["cn"][0],
            'email' => $data[0]["mail"][0],
            'position' => $data[0]["title"][0],
        ];
    }


    /**
     * Closing ldap connection
     *
     * @return boolean
     */
    public function close()
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
        try {

            $access = $this->checkAccess($nickname, $password);


            if (FALSE == $access) {
                return FALSE;
            }

            $data = $this->searchByNickname($nickname);

            return $data;

        } catch (Meetingroom\Service\LDAP\LDAPException $e) {
            throw $e;
        }
    }

}