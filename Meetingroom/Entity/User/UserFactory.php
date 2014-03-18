<?php

namespace Meetingroom\Entity\User;

class UserFactory
{
    public function getUser($username)
    {
        $model = new \Meetingroom\Model\User\UserModel();
        $userId = $model->getIdByUsername($username);

        if ($userId === false) {
            $user = new GuestEntity();
        } else {
            $user = new AuthorizedEntity();
            $user->load($username);
        }

        return $user;
    }

}
