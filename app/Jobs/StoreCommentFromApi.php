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
     * @var \App\Photo $photo
     */
    private $photo;
    /**
     * @var \App\Comment $comment
     */
    private $comment;

    /**
     * Create a new job instance.
     *
     * @param $comment
     * @param int $photoId
     */
    public function __construct($comment, int $photoId)
    {
        $this->comment = $comment;
        $this->photo = \App\Photo::find($photoId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $fromSelf = (int) $this->comment->user->id === $this->photo->getAttribute('user_id');

        if ($fromSelf && $this->comment->parent_id !== null) {
            dispatch(new \App\Jobs\Comment\MarkAsRead($this->comment->parent_id));
        }

        Comment::create([
            'id'          => $this->comment->id,
            'body'        => $this->comment->body,
            'parent_id'   => $this->comment->parent_id ?? null,
            'follower_id' => $this->comment->user->id,
            'read'        => $fromSelf,
            'photo_id'    => $this->photo->getAttribute('id'),
            'posted_at'   => Carbon::createFromFormat(\DateTime::ATOM, $this->comment->created_at)
                ->setTimezone(config('app.timezone')),
        ]);
    }
}
