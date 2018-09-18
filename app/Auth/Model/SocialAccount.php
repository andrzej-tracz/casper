<?php

namespace App\Auth\Model;

use Illuminate\Database\Eloquent\Model;
use App\Casper\Model\User;

class SocialAccount extends Model
{
    protected $fillable = [
        'provider_user_id',
        'provider'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
