<?php

namespace App\Jobs\Comment;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MarkAsRead implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var int $commentId
     */
    private $commentId;


    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @param int $commentId
     */
    public function __construct(int $commentId)
    {

        $this->commentId = $commentId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            \App\Comment::findOrFail($this->commentId)
                ->setAttribute('read', 1)
                ->save();
        } catch(ModelNotFoundException $e) {
            $this->fail();
        }

    }
}
