<?php

namespace Meetingroom\Entities\User;

class UserFactory
{
    public function getUser($username) 
    {
        $model = new \Meetingroom\Models\UserModel();
        $userId = $model->getId($username);
        
        if($userId === false) {
            $user = new \Meetingroom\Entities\User\Guest();
        } else {
            $user = new \Meetingroom\Entities\User();
        }
        
        return $user->init($username);
    }
}
