<?php

namespace App;

use App\Events\PhotoCreated;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
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
        'url',
        'url_full',
        'link',
        'name',
        'description',
        'privacy',
        'user_id',
        'posted_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'privacy' => 'boolean',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $events = [
        'created' => PhotoCreated::class,
    ];

    /**
     * Eager loads the comments and their children.
     *
     * @param $query
     * @return mixed
     */
    public function scopeWithComments($query)
    {
        return $query->with([
            'comments' => function ($query) {
                $query->whereNull('parent_id');
            },
            'comments.children',
            'comment.follower',
        ]);
    }

    /**
     * Returns only the photos that have had new comments
     *
     * @param $query
     * @return mixed
     */
    public function scopeOnlyWithNewComments($query)
    {
        return $query->whereHas('comments', function ($query) {
            $query->where('read', '=', 0);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function comments()
    {
        return $this->hasMany(\App\Comment::class);
    }
}
