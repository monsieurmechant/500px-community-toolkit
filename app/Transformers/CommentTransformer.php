<?php


namespace App\Transformers;


use App\Comment;

class CommentTransformer
{

    /**
     * Related models to include in this transformation.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'follower', 'children'
    ];

    protected $availableIncludes = [
        'photo',
        'parent'
    ];

    /**
     * Turn this item object into a generic array.
     *
     * @param Comment $comment
     * @return array
     */
    public function transform(Comment $comment)
    {
        return [
            // attributes
            'id'         => $comment->getAttribute('id'),
            'body'       => $comment->getAttribute('body'),
            'read'       => $comment->getAttribute('read'),
            'created_at' => $comment->getAttribute('posted_at'),
            'links'      => [
                [
                    'rel' => 'self',
                    'uri' => '/internal/comments/' . $comment->getAttribute('id'),
                ]
            ]
        ];
    }

    public function includeFollower(Photo $comment)
    {
        return $this->item($comment->follower, new FollowerTransformer());
    }

    public function includeChildren(Photo $comment)
    {
        return $this->collection($comment->children, new CommentTransformer());
    }

    public function includeParent(Photo $comment)
    {
        return $this->item($comment->parent, new CommentTransformer());
    }

    public function includePhoto(Photo $comment)
    {
        return $this->item($comment->photo, new CommentTransformer());
    }
}