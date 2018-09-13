<?php

namespace App\Casper\Repository;
use App\Casper\Model\User;
use App\Eloquent\Repository\AbstractEloquentRepository;

class UserRepository extends AbstractEloquentRepository
{
    /**
     * Returns the Model class of repository
     *
     * @return string
     */
    protected function getModelClass()
    {
        return User::class;
    }

    public function searchUsers()
    {
        //
    }
}
