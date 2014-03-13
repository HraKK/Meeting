<?php

namespace Meetingroom\Controllers;

class UserController extends \Phalcon\Mvc\Controller
{
    public function indexAction($page, $asd) 
    {
        print_r(func_get_args());
    }
    
    public function loadAction($username) 
    {
        $userFactory = new \Meetingroom\Entities\UserFactory();
        $user = $userFactory->loadUser($username);
        var_dump($user->getId());
    }


    public function testAction()
    {
        echo "<h1>User2!</h1>";

        $user = new \Meetingroom\Models\UserModel();
        $info = $user->getUserLDAPInfo('sysgstats','pgGZErgMkNXF');
        var_dump('<pre>',$info);


        $acl= $this->getDI()->get('acl');

        //test
        if ($acl->isAllowed("Guest", "User", "test")) {
            echo "Access granted!";
        } else {
            echo "Access denied :(";
        }
    }


}
