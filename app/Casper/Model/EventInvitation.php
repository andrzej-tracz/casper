<?php

namespace App\Casper\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventInvitation extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    public static function boot()
    {
        parent::boot();

        static::creating(function (EventInvitation $invitation) {
            $invitation->status = static::STATUS_NEW;

            do {
                $invitation->token = Str::random(80);
            } while (static::where('token', $invitation->token)->exists());
        });
    }

    protected $hidden = [
        'token'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invited()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determine if invitation is new
     *
     * @return bool
     */
    public function isNew()
    {
        return $this->status === static::STATUS_NEW;
    }

    /**
     * Determine if invitation is accepted
     *
     * @return bool
     */
    public function isAccepted()
    {
        return $this->status === static::STATUS_ACCEPTED;
    }

    /**
     * Determine if invitation is rejected
     *
     * @return bool
     */
    public function isRejected()
    {
        return $this->status === static::STATUS_REJECTED;
    }
}
