<?php

namespace App\Transformers;

use App\Follower;
use League\Fractal\TransformerAbstract;

class FollowerTransformer extends TransformerAbstract
{

    /**
     * Related models to include in this transformation.
     *
     * @var array
     */
    protected $availableIncludes = [
        'user'
    ];

    /**
     * Turn this item object into a generic array.
     *
     * @param Follower $follower
     * @return array
     */
    public function transform(Follower $follower)
    {
        return [
            // attributes
            'id'              => (int)$follower->getAttribute('id'),
            'username'        => $follower->getAttribute('username'),
            'name'            => $follower->getAttribute('name'),
            'followers_count' => (int)$follower->getAttribute('followers'),
            'affection'       => (int)$follower->getAttribute('affection'),
            'url'             => 'https://500px.com/' . $follower->getAttribute('username'),
            'avatar'          => $follower->getAttribute('avatar'),
            'created_at'      => $follower->getAttribute('created_at'),
            'updated_at'      => $follower->getAttribute('updated_at'),
            'links'           => [
                [
                    'rel' => 'self',
                    'uri' => '/internal/followers/' . $follower->getAttribute('id'),
                ]
            ]
        ];
    }

    public function includeUser(Follower $follower)
    {
        return $this->item($follower->user, new UserTransformer());
    }
}
