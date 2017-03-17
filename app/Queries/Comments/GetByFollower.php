<?php


namespace App\Queries\Comments;


use App\Follower;
use App\User;

class GetByFollower
{

    /**
     * @param Follower $follower
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(Follower $follower, User $user = null)
    {
        return $user->load([
            'comments' => function ($query) use ($follower) {
                $query->where('follower_id', '=', $follower->getAttribute('id'))
                    ->orderBy('posted_at', 'DSC')->take(50);
            },
            'comments.follower',
            'comments.photo'
        ])->comments;
    }
}