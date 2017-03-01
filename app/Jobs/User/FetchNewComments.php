<?php

namespace App\Jobs\User;

use App\User;
use Illuminate\Bus\Queueable;
use App\Jobs\Photo\FetchComments;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Services\FiveHundredPxService;

class FetchNewComments implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var User
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Fetch the user's photo page from the api. For each photos
     * we compare the comments count returned with the amount
     * of comments we have in database. We then fetch the
     * new comments we returned number is higher.
     *
     * @param FiveHundredPxService $api
     */
    public function handle(FiveHundredPxService $api)
    {
        $api->authenticateClient($this->user);
        $pages = 1;
        $i=0;
        for ($currentPage = 1; $currentPage <= $pages; $currentPage++) {
            $photos = $api->get(
                'photos',
                [
                    'query' =>
                        [
                            'feature'        => 'user',
                            'user_id'        => $this->user->getAttribute('id'),
                            'sort'           => 'created_at',
                            'sort_direction' => 'desc',
                            'rpp'            => 100,
                            'page'           => $currentPage,
                            'consumer_key'   => getenv('500PX_KEY'),
                        ]
                ]
            );

            if ($currentPage === 1) {
                $pages = $photos->total_pages;
            }

            foreach ($photos->photos as $photo) {
                if (
                    \App\Photo::where('id', '=', $photo->id)->exists()
                    && $photo->comments_count > \App\Comment::where('photo_id', '=', $photo->id)->count()
                ) {
                    dispatch(new FetchComments($photo->id));
                }
            }
        }


    }
}
