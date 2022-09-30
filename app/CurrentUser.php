<?php

namespace app;


class CurrentUser{

    public $userId;

    public $token;

    public $username;

    public $truename;

    public $avatar;

    public $email;

    public function setCurrentUser($user){
        $this->userId = $user['id'];
        $this->username = $user['username'];
        $this->truename = $user['truename'];
        $this->avatar = $user['avatar'];
        $this->email = $user['email'];
    }
}