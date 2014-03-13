<?php

namespace Meetingroom\Models;

class UserModel extends AbstractModel
{
    public function test() 
    {
        $this->beforeExecuteRoute();
        $result = $this->db->query("SELECT * FROM users ORDER BY name");
        $result->setFetchMode(\Phalcon\Db::FETCH_OBJ);
        while ($robot = $result->fetch()) {
            echo $robot->name;
        }
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
    public function getUserLDAPInfo($nickname,$password)
    {
        if(!$nickname || !$password) return FALSE;

        $ds=ldap_connect("ldap.syneforge.com");
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

        if ($ds) {
            $r=ldap_bind($ds,"uid=".$nickname.",ou=people,dc=syneforge,dc=com",$password);

            if(FALSE===$r){
                return FALSE;
            }

            $sr=ldap_search($ds, "ou=people,dc=syneforge,dc=com", "uid=".$nickname);

            if(ldap_count_entries($ds, $sr)<1){
                throw new \Exception('User data not found');
            }

            $info = ldap_get_entries($ds, $sr);
            ldap_close($ds);

            return [
                'cn'=>$info[0]["cn"][0],
                'email'=>$info[0]["mail"][0],
                'position'=>$info[0]["title"][0],

            ];
        } else {
            throw new \Exception('Can\'t connect to LDAP server');
        }


    }
    
}
