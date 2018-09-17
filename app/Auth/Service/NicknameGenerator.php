<?php

namespace App\Auth\Service;

use App\Casper\Repository\UserRepository;

class NicknameGenerator
{
    /**
     * @var UserRepository
     */
    protected $users;

    public function __construct(UserRepository $repository)
    {
        $this->users = $repository;
    }

    /**
     * Generates unique nickname form provided email
     *
     * @param $email
     * @return string
     */
    public function generateUsernameFromEmail($email)
    {
        $parts = explode("@", $email);
        $username = $parts[0];
        $count = $this->users->where('nickname', $username)->count();

        if (0 == $count) {
            return $username;
        }

        $counter = 1;
        do {
            $generated = sprintf("%s_%s", $username, $counter);
            $counter++;
        } while ($this->users->where('nickname', $generated)->exists());

        return $generated;
    }
}
