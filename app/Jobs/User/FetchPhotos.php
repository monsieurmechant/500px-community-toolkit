<?php

namespace App\Jobs\User;

use App\Events\UserHasNewPhotos;
use App\User;
use App\Photo;
use Illuminate\Bus\Queueable;
use App\Exceptions\JobDoneException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Services\FiveHundredPxService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FetchPhotos implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int $userId */
    private $userId;

    /** @var int $newPhotos */
    private $newPhotos = 0;

    /**
     * Create a new job instance.
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->onQueue('photos');
    }

    /**
     * Execute the job.
     *
     * @param FiveHundredPxService $client
     */
    public function handle(FiveHundredPxService $client)
    {
        try {
            /** @var User $user */
            $user = User::findOrFail($this->userId);
        } catch (ModelNotFoundException $e) {
            return;
        }

        $client->authenticateClient($user);
        $pages = 1;
        try {
            for ($currentPage = 1; $currentPage <= $pages; $currentPage++) {
                $photos = $client->get(
                    'photos',
                    [
                        'query' =>
                            [
                                'feature'        => 'user',
                                'user_id'        => $user->getAttribute('id'),
                                'sort'           => 'created_at',
                                'sort_direction' => 'desc',
                                'rpp'            => 100,
                                'page'           => $currentPage,
                                'image_size'     => [3, 5],
                                'consumer_key'   => getenv('500PX_KEY'),
                            ]
                    ]
                );

                if ($currentPage === 1) {
                    $pages = $photos->total_pages;
                }
                $this->savePhotosPage($photos->photos);
            }
        } catch (JobDoneException $e) {
            if ($this->newPhotos > 0) {
                event(new UserHasNewPhotos($user->getAttribute('id')));
            }
            return;
        }
        if ($this->newPhotos > 0) {
            event(new UserHasNewPhotos($user->getAttribute('id')));
        }
    }

    /**
     * Persists all the new user photos to the database.
     * All the photos are returned most recent first.
     * Once we get to a photo that is already in
     * the database we throw and exceptions
     * and stop the whole job.
     *
     * @param array $photos
     * @return void
     * @throws JobDoneException
     */
    private function savePhotosPage(array $photos)
    {
        foreach ($photos as $photo) {
            try {
                /** @var Photo $account */
                Photo::findOrFail($photo->id);
            } catch (ModelNotFoundException $e) {
                $this->persistPhoto($photo);
                $this->newPhotos++;
                continue;
            }

            throw new JobDoneException();
        }
    }

    /**
     * Persist a photo to the database
     *
     * @param $photo
     */
    private function persistPhoto($photo)
    {
        $url = $urlFull = null;

        foreach ($photo->images as $image) {
            if ((int)$image->size === 3) {
                $url = $image->https_url;
            } elseif ((int)$image->size === 5) {
                $urlFull = $image->https_url;
            }
        }

        Photo::create([
            'id'          => $photo->id,
            'url'         => $url,
            'url_full'    => $urlFull,
            'link'        => $photo->url,
            'name'        => $photo->name ?? null,
            'description' => $photo->description ?? null,
            'privacy'     => (bool)$photo->privacy,
            'user_id'     => $this->userId,
            'posted_at'   => \Carbon\Carbon::createFromFormat(\DateTime::ATOM, $photo->created_at)
                ->setTimezone(config('app.timezone')),
        ]);
    }
}
