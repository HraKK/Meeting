<?php

namespace Meetingroom\Entity\User;

class UserFactory
{
    public function getUser($username)
    {
        $model = new \Meetingroom\Model\UserModel();
        $userId = $model->getIdByUsername($username);

        if ($userId === false) {
            $user = new Guest();
        } else {
            $user = new User();
            $user->load($username);
        }

        return $user;
    }

}
