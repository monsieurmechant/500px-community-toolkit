<?php

namespace App\Policies;

use App\User;
use App\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{

    use HandlesAuthorization;

    /**
     * Make sure that only the owner of the photo,
     * where the comment was posted, is allowed
     * to delete the database record.
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function update(User $user, Comment $comment)
    {
        return $user->getAttribute('id') == $comment->photo->getAttribute('user_id');
    }

    /**
     * Make sure that only the owner of the photo,
     * where the comment was posted, is allowed
     * to delete the database record.
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function delete(User $user, Comment $comment)
    {
        return $user->getAttribute('id') == $comment->photo->getAttribute('user_id');
    }
}
