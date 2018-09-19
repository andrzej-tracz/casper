<?php

namespace App\Casper\Repository;

use App\Casper\Model\User;
use App\Eloquent\Repository\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Builder;
use App\Casper\Model\Event;
use Laravel\Socialite\Contracts\User as SocialUser;

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
        return $this->createSearchQuery($phrase)
            ->orderBy('nickname')
            ->take(10)
            ->get();
    }

    /**
     * Search possible users for events invitations
     *
     * @param Event|null $event
     * @param null $phrase
     * @param array $excludeIds
     * @param int $limit
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function searchForInvitations(Event $event = null, $phrase = null, $excludeIds = [], $limit = 10)
    {
        $query = $this->createSearchQuery($phrase);

        if ($event) {
            $query->whereDoesntHave('eventInvitations', function ($q) use ($event) {
                $q->where('event_id', $event->id);
            });
        }

        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        return $query
            ->orderBy('nickname')
            ->take($limit)
            ->get();
    }

    /**
     * Returns internal user instance by provided social user
     *
     * @param SocialUser $user
     * @return mixed
     */
    public function findBySocialUser(SocialUser $user)
    {
        return $this->where('email', $user->getEmail())->first();
    }

    /**
     * Creates initial search query
     *
     * @param null $phrase
     *
     * @return Builder
     */
    protected function createSearchQuery($phrase = null)
    {
        $query = $this->query();

        if ($phrase) {
            $query->where('nickname', 'like', "%{$phrase}%");
        }

        return $query;
    }
}
