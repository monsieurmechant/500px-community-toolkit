<?php


namespace App\Transformers;


use App\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{

    /**
     * Related models to include in this transformation.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'follower',
        'children'
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
            'parent_id'  => $comment->getAttribute('parent_id') ?? null,
            'created_at' => $comment->getAttribute('posted_at'),
            'links'      => [
                [
                    'rel' => 'self',
                    'uri' => '/internal/comments/' . $comment->getAttribute('id'),
                ]
            ]
        ];
    }

    /**
     * @param Comment $comment
     * @return \League\Fractal\Resource\Item
     */
    public function includeFollower(Comment $comment)
    {
        return $this->item($comment->follower, new FollowerTransformer());
    }

    /**
     * @param Comment $comment
     * @return \League\Fractal\Resource\Collection
     */
    public function includeChildren(Comment $comment)
    {
        return $this->collection($comment->children, new CommentTransformer());
    }

    /**
     * @param Comment $comment
     * @return \League\Fractal\Resource\Item
     */
    public function includeParent(Comment $comment)
    {
        return $this->item($comment->parent, new CommentTransformer());
    }

    /**
     * @param Comment $comment
     * @return \League\Fractal\Resource\Item
     */
    public function includePhoto(Comment $comment)
    {
        return $this->item($comment->photo, new CommentTransformer());
    }
}