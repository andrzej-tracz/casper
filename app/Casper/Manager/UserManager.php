<?php

namespace App\Casper\Manager;

use App\Casper\Model\User;
use Illuminate\Contracts\Hashing\Hasher;

class UserManager
{
    /**
     * @var Hasher
     */
    protected $hasher;

    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

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
            'password' => $this->hasher->make($data['password']),
        ]);
    }
}
