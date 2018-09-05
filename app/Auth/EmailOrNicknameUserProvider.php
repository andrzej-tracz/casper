<?php

namespace App\Auth;

use http\Exception\InvalidArgumentException;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider as UserProviderContract;

class EmailOrNicknameUserProvider extends EloquentUserProvider implements UserProviderContract
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return;
        }

        $query = $this->createModel()->newQuery();
        $emailOrNickname = $credentials['email'] ?? $credentials['nickname'] ?? null;

        if (!$emailOrNickname) {
            throw new InvalidArgumentException("Email or nickname is required and not provided.");
        }

        $query->where(function ($q) use ($emailOrNickname) {
            $q->where('email', $emailOrNickname)->orWhere('nickname', $emailOrNickname);
        });

        return $query->first();
    }
}
