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

    /**
     * Seach Users by provided search string
     *
     * @param null $phrase
     * @return mixed
     */
    public function searchUsers($phrase = null)
    {
        return $this->query()
            ->where('nickname', 'like', "%{$phrase}%")
            ->orderBy('nickname')
            ->take(10)
            ->get();
    }
}
