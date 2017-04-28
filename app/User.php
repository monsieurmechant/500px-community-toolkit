<?php

namespace App;

use App\Events\UserCreated;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use Notifiable;
    use EncryptsFields;

    /**
     * Since we are using the 500px ID as primary.
     * The 'id' field should not increment.
     *
     * @var bool $incrementing
     */
    public $incrementing = false;

    /**
     * The attributes that should be encrypted on the fly.
     *
     * @var array
     */
    protected $encryptable = [
        'access_token_secret'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'username',
        'email',
        'avatar',
        'access_token',
        'followers_count',
        'quick_replies',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
        'access_token',
        'access_token_secret',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'integer',
        'quick_replies' => 'array'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $events = [
        'created' => UserCreated::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(\App\Follower::class);
    }

    public function photos()
    {
        return $this->hasMany(\App\Photo::class);
    }

    public function comments()
    {
        return $this->hasManyThrough(\App\Comment::class, \App\Photo::class);
    }

    /**
     * Adds a new quick reply to the
     * quick replies array.
     *
     * @param string $reply
     * @return $this
     */
    public function addQuickReply(string $reply)
    {
        $qReplies = $this->getAttribute('quick_replies');
        $qReplies[] = $reply;
        $this->setAttribute('quick_replies', $qReplies);

        return $this;
    }

    /**
     * Removes a quick reply from the
     * quick replies array.
     *
     * @param string $reply
     * @return $this
     */
    public function removeQuickReply(string $reply)
    {
        $qReplies = $this->getAttribute('quick_replies');

        $key = array_search($reply, $qReplies);
        if (!$key) {
            return $this;
        }

        array_splice($qReplies, $key, 1);
        $this->setAttribute('quick_replies', $qReplies);

        return $this;
    }
}
