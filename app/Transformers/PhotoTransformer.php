<?php

namespace App\Transformers;

use App\Photo;
use League\Fractal\TransformerAbstract;

class PhotoTransformer extends TransformerAbstract
{

    /**
     * Related models that can be included
     * in this transformation.
     *
     * @var array
     */
    protected $availableIncludes = [
        'user',
        'comments'
    ];

    /**
     * Turn this item object into a generic array.
     *
     * @param Photo $photo
     * @return array
     */
    public function transform(Photo $photo)
    {
        return [
            // attributes
            'id'          => (int)$photo->getAttribute('id'),
            'thumbnail'   => $photo->getAttribute('url'),
            'full'        => $photo->getAttribute('url_full'),
            'title'       => $photo->getAttribute('name'),
            'description' => $photo->getAttribute('description'),
            'url'         => 'https://500px.com/' . $photo->getAttribute('link'),
            'created_at'  => $photo->getAttribute('posted_at'),
            'links'       => [
                [
                    'rel' => 'self',
                    'uri' => '/internal/photos/' . $photo->getAttribute('id'),
                ]
            ]
        ];
    }

    public function includeUser(Photo $photo)
    {
        return $this->item($photo->user, new UserTransformer());
    }

    public function includeComments(Photo $photo)
    {
        return $this->collection($photo->comments, new CommentTransformer());
    }
}
