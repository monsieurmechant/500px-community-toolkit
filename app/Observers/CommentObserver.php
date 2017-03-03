<?php


namespace App\Observers;


use Cache;
use App\Comment;

class CommentObserver
{
    /**
     * Listen to the Comment created event.
     *
     * @param  Comment  $comment
     * @return void
     */
    public function saved(Comment $comment)
    {
        $this->deleteFollowerCommentsFromCache($comment->getAttribute('follower_id'));
    }

    /**
     * Listen to the Comment deleting event.
     *
     * @param  Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        $this->deleteFollowerCommentsFromCache($comment->getAttribute('follower_id'));
    }

    private function deleteFollowerCommentsFromCache($follower_id) {
        Cache::tags('follower_comments_' . $follower_id)->flush();
    }
}