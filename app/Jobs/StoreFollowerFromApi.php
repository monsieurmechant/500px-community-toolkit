<?php

namespace App\Jobs;

use App\Follower;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StoreFollowerFromApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var
     */
    private $follower;

    /**
     * Create a new job instance.
     *
     * @param $follower
     */
    public function __construct($follower)
    {
        $this->follower = $follower;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Follower::create([
            'id'        => $this->follower->id,
            'username'  => $this->follower->username ?? null,
            'name'      => $this->follower->fullname ?? null,
            'avatar'    => $this->follower->userpic_url ?? null,
            'followers' => (int)$this->follower->followers_count >= 0 ? (int)$this->follower->followers_count : 0,
            'affection' => (int)$this->follower->affection >= 0 ? (int)$this->follower->affection : 0,
        ]);
    }
}
