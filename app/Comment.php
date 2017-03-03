<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
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
        'body',
        'parent_id',
        'photo_id',
        'follower_id',
        'posted_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'          => 'integer',
        'photo_id'    => 'integer',
        'parent_id'   => 'integer',
        'follower_id' => 'integer',
        'read'        => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'posted_at',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function follower()
    {
        return $this->belongsTo(\App\Follower::class);
    }

    public function photo()
    {
        return $this->belongsTo(\App\Photo::class);
    }

    public function parent()
    {
        return $this->belongsTo(\App\Comment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(\App\Comment::class, 'parent_id');
    }
}
