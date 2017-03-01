<?php

namespace App\Jobs;

use App\Comment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StoreCommentFromApi implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var
     */
    private $comment;
    /**
     * @var int
     */
    private $photoId;

    /**
     * Create a new job instance.
     *
     * @param $comment
     * @param int $photoId
     */
    public function __construct($comment, int $photoId)
    {
        $this->comment = $comment;
        $this->photoId = $photoId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Comment::create([
            'id'          => $this->comment->id,
            'body'        => $this->comment->body,
            'parent_id'   => $this->comment->parent_id ?? null,
            'follower_id' => $this->comment->user->id,
            'photo_id'    => $this->photoId,
            'posted_at'   => Carbon::createFromFormat(\DateTime::ATOM, $this->comment->created_at)
                ->setTimezone(config('app.timezone')),
        ]);
    }
}
