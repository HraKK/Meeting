<?php

namespace Meetingroom\Controllers;

class UserController extends \Phalcon\Mvc\Controller
{
    public function indexAction($page, $asd) 
    {
       print_r(func_get_args());
    }

    public function testAction()
    {
        echo "<h1>User2!</h1>";

        $user = new \Meetingroom\Models\User();
        $info = $user->getUserLDAPInfo('sysgstats','pgGZErgMkNXF');
        var_dump('<pre>',$info);
    }


}
