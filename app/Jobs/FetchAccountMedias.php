<?php

namespace App\Jobs;

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

class FetchAccountMedias implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int $userId */
    private $userId;

    /**
     * Create a new job instance.
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
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
            return;
        }

    }

    /**
     * Persists all the followers to the Database
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
                continue;
            }

            throw new JobDoneException();
        }
    }

    /**
     * Persist a followed user to the database
     *
     * @param $photo
     */
    private function persistPhoto($photo)
    {
        $url = $urlFull = null;

        foreach ($photo->images as $image) {
            if ((int) $image->size === 3) {
                $url = $image->https_url;
            } elseif ((int) $image->size === 5) {
                $urlFull = $image->https_url;
            }
        }

        Photo::create([
            'id'          => $photo->id,
            'url'         => $url,
            'url_full'    => $urlFull,
            'name'        => $photo->name ?? null,
            'description' => $photo->description ?? null,
            'privacy'     => (bool)$photo->privacy,
            'user_id'     => $this->userId,
        ]);
    }
}
