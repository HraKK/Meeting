<?php
namespace Meetingroom\Services\Ldap;

/**
 * Class Ldap
 *
 * @author Denis Maximovskikh <denkin@syneforge.com.ua>
 * @package Meetingroom\Services
 */
class Ldap implements LdapInterface
{


    /**
     * @var connection to ldap
     */
    private $connection;

    /**
     * Create connection to ldap server
     *
     * @param string $host
     * @param int $port
     * @throws Meetingroom\Services\Ldap\LDAPException
     * @return resource|false connection resource
     */
    public function connect($host = "ldap.syneforge.com", $port = 389)
    {
        $this->connection = ldap_connect($host,$port);
        ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        if(false==$this->connection){
            throw new Meetingroom\Services\Ldap\LDAPException('Can\'t connect to LDAP server');
        }
    }


    /**
     * Return established connection
     *
     * @throws Meetingroom\Services\Ldap\LDAPException
     * @return resource
     */
    public function getConnection()
    {
        if(!is_resource($this->connection)){
            try{
                $this->connect();
            }catch (Meetingroom\Services\Ldap\LDAPException $e){
                throw $e;
            }
        }
        return $this->connection;
    }


    /**
     * Method check if user can access to ldap server
     *
     * @param resource $ldap_connection
     * @param string $nickname
     * @param string $password
     * @return boolean
     */
    public function checkAccess($ldap_connection, $nickname, $password)
    {
        return ldap_bind($ldap_connection,"uid=" . $nickname . ",ou=people,dc=syneforge,dc=com",$password);
    }


    /**
     * Search user info by nickname
     *
     * @param string $nickname
     * @return array
     */
    public function searchByNickname($nickname)
    {

        $searchResult=ldap_search($this->getConnection(), "ou=people,dc=syneforge,dc=com", "uid=" . $nickname);
        $data = ldap_get_entries($this->getConnection(), $searchResult);

        return [
            'cn'=>$data[0]["cn"][0],
            'email'=>$data[0]["mail"][0],
            'position'=>$data[0]["title"][0],
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
    public function getUserInfo($nickname,$password)
    {
        if(!$nickname || !$password){
            return false;
        }
        try{
            $authorization=ldap_bind($this->getConnection(),"uid=" . $nickname . ",ou=people,dc=syneforge,dc=com",$password);

            if(FALSE==$authorization){
                return FALSE;
            }

            $data = $this->searchByNickname($nickname);
            $this->close();

            return $data;

        }catch (Meetingroom\Services\Ldap\LDAPException $e){
            throw $e;
        }
    }

}