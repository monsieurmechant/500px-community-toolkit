<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'nickname',
        'name',
        'email',
        'avatar',
        'followers',
        'affection',
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'followed_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'followers' => 'integer',
        'affection' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function followers()
    {
        return $this->hasMany(\App\Account::class);
    }
}
