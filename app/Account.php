<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    /**
     * Since we are using the 500px ID as primary.
     * The 'id' field should not increment.
     *
     * @var bool $incrementing
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'username',
        'name',
        'email',
        'avatar',
        'followers',
        'affection',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'        => 'integer',
        'followers' => 'integer',
        'affection' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function followers()
    {
        return $this->belongsToMany(\App\Account::class);
    }
}
