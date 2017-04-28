<?php

namespace App\Jobs\Photo;

use App\Photo;
use App\Comment;
use App\Follower;
use Illuminate\Bus\Queueable;
use App\Jobs\StoreCommentFromApi;
use App\Jobs\StoreFollowerFromApi;
use App\Events\PhotoHasNewComments;
use App\Exceptions\JobDoneException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Services\FiveHundredPxService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FetchComments implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int $mediaId */
    private $mediaId;

    /** @var int $newComments */
    private $newComments = 0;

    /**
     * Create a new job instance.
     * @param int $mediaId
     */
    public function __construct(int $mediaId)
    {
        $this->mediaId = $mediaId;
        $this->onQueue('comments');
    }

    /**
     * Execute the job.
     *
     * @param FiveHundredPxService $client
     */
    public function handle(FiveHundredPxService $client)
    {
        try {
            /** @var Photo $photo */
            $photo = Photo::with('user')->findOrFail($this->mediaId);
        } catch (ModelNotFoundException $e) {
            return;
        }

        $client->authenticateClient($photo->user);
        $pages = 1;
        try {
            for ($currentPage = 1; $currentPage <= $pages; $currentPage++) {
                $comments = $client->get(
                    'photos/' . $this->mediaId . '/comments',
                    [
                        'query' =>
                            [
                                'page'           => $currentPage,
                                'sort'           => 'created_at',
                                'sort_direction' => 'desc',
                                'consumer_key'   => getenv('500PX_KEY'),
                            ]
                    ]
                );

                if ($currentPage === 1) {
                    $pages = $comments->total_pages;
                }
                $this->saveCommentsPage($comments->comments);
            }
        } catch (JobDoneException $e) {
            if ($this->newComments > 0) {
                event(new PhotoHasNewComments($photo));
            }
            return;
        }
        if ($this->newComments > 0) {
            event(new PhotoHasNewComments($photo));
        }

    }

    /**
     * Persists all the followers to the Database
     *
     * @param array $comments
     * @return void
     * @throws JobDoneException
     */
    private function saveCommentsPage(array $comments)
    {
        foreach ($comments as $comment) {
            try {
                Follower::findOrFail($comment->user->id);
            } catch (ModelNotFoundException $e) {
                dispatch((new StoreFollowerFromApi($comment->user))->onConnection('sync'));
            }
            try {
                Comment::findOrFail($comment->id);
            } catch (ModelNotFoundException $e) {
                dispatch((new StoreCommentFromApi($comment, (int)$this->mediaId))->onConnection('sync'));
                $this->newComments++;
                continue;
            }
            throw new JobDoneException();
        }
    }
}
