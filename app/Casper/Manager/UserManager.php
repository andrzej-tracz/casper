<?php

namespace App\Casper\Manager;

use App\Casper\Model\User;

class UserManager
{
    /**
     * Creates new user
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return User::create([
            'nickname' => $data['nickname'],
            'gender' => $data['gender'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
            'email' => $data['email'],
            'password' => \Hash::make($data['password']),
        ]);
    }
}
