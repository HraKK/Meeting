<?php

namespace Meetingroom\Entity\User;

class UserFactory
{
    public function getUser($username)
    {
        $model = new \Meetingroom\Model\UserModel();
        $userId = $model->getIdByUsername($username);

        if ($userId === false) {
            $user = new \Meetingroom\Entity\User\Guest();
        } else {
            $user = new \Meetingroom\Entity\User($username);
        }

        return $user;
    }

}
