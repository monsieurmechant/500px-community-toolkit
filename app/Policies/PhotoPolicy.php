<?php

namespace App\Policies;

use App\User;
use App\Photo;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhotoPolicy
{

    use HandlesAuthorization;

    /**
     * Make sure that only the owner of the photo
     * can update the database record.
     *
     * @param User $user
     * @param Photo $photo
     * @return bool
     */
    public function update(User $user, Photo $photo)
    {
        return $user->getAttribute('id') == $photo->getAttribute('user_id');
    }

    /**
     * Make sure that only the owner of the photo
     * can delete the database record.
     *
     * @param User $user
     * @param Photo $photo
     * @return bool
     */
    public function delete(User $user, Photo $photo)
    {
        return $user->getAttribute('id') == $photo->getAttribute('user_id');
    }
}
