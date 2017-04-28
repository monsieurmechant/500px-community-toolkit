<?php

namespace App\Jobs\User;

use App\User;
use App\Follower;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Jobs\StoreFollowerFromApi;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Services\FiveHundredPxService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FetchFollowers implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int $userId */
    private $userId;
    /** @var array collectedId */
    private $collectedIds = [];

    /**
     * Create a new job instance.
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->onQueue('followers');
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
        $this->collectedIds = [];
        for ($currentPage = 1; $currentPage <= $pages; $currentPage++) {
            $follows = $client->get(
                'users/' . $user->id . '/followers',
                ['query' => ['rpp' => 100, 'page' => $currentPage]]
            );

            if ($currentPage === 1) {
                $pages = $follows->followers_pages;
            }

            $this->saveFollowersPage($follows->followers);
        }
        $user->followers()->sync($this->collectedIds);

    }

    /**
     * Persists all the followers to the Database
     *
     * @param array $followers
     * @return void
     */
    private function saveFollowersPage(array $followers)
    {
        foreach ($followers as $follower) {
            $this->collectedIds[] = (int)$follower->id;
            try {
                /** @var Follower $account */
                $account = Follower::findOrFail($follower->id);
                if (!Carbon::now()->isSameDay($account->getAttribute('updated_at'))) {
                    $account->fill([
                        'name'      => $follower->fullname ?? $account->getAttribute('name'),
                        'avatar'    => $follower->userpic_url ?? $account->getAttribute('avatar'),
                        'cover'     => $follower->cover_url ?? $account->getAttribute('cover'),
                        'followers' => $follower->followers_count,
                        'affection' => $follower->affection,
                    ]);
                    $account->save();
                }
            } catch (ModelNotFoundException $e) {
                dispatch((new StoreFollowerFromApi($follower))->onConnection('sync'));
            }
        }
    }
}
