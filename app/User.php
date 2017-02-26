<?php

namespace App;

use App\Events\UserCreated;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use Notifiable;
    use EncryptsFields;

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
        'nickname',
        'email',
        'avatar',
        'access_token',
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
     * The event map for the model.
     *
     * @var array
     */
    protected $events = [
        'created' => UserCreated::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function followers()
    {
        return $this->hasMany(\App\Account::class);
    }
}
