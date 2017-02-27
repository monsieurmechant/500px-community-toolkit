<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    /**
     * Related models to include in this transformation.
     *
     * @var array
     */
    protected $availableIncludes = [
        'followers'
    ];

    /**
     * Turn this item object into a generic array.
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            // attributes
            'id'              => (int)$user->getAttribute('id'),
            'username'        => $user->getAttribute('username'),
            'name'            => $user->getAttribute('name'),
            'email'           => $user->getAttribute('email'),
            'followers_count' => (int)$user->getAttribute('followers_count'),
            'avatar'          => $user->getAttribute('avatar'),
            'url'             => 'https://500px.com/' . $user->getAttribute('username'),
            'created_at'      => $user->getAttribute('created_at'),
            'updated_at'      => $user->getAttribute('updated_at'),
            'links'           => [
                [
                    'rel' => 'self',
                    'uri' => '/internal/users/' . $user->getAttribute('id'),
                ]
            ]
        ];
    }

    public function includeFollowers(User $user)
    {
        return $this->collection($user->followers, new FollowerTransformer());
    }
}
