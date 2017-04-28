<?php

namespace App\Listeners\Users;

use Redis;
use App\Jobs\User as J;
use App\Events\UserRequestedPhotos;

class RefreshData
{

    /**
     * Handle the event.
     *
     * @param  UserRequestedPhotos  $event
     * @return void
     */
    public function handle(UserRequestedPhotos $event)
    {
        if (!Redis::get('photos.refreshed.recently.' . $event->getUser()->getAttribute('id'))) {
            dispatch((new J\FetchPhotos($event->getUser()->getAttribute('id')))->onQueue('photos-high'));
            dispatch((new J\FetchNewComments($event->getUser()))->onQueue('comments-high'));
            Redis::setex('photos.refreshed.recently.' .  $event->getUser()->getAttribute('id'), 15 * 60, true);
        }
    }
}
