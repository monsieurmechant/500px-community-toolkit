<?php

namespace App\Jobs;

use App\User;
use App\Account;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Services\FiveHundredPxService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FetchAccountFollowers implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int $accountId */
    private $userId;
    /** @var array $accountId */
    private $collectedIds = [];

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
            $this->collectedIds[] = $follower->id;
            try {
                /** @var Account $account */
                $account = Account::findOrFail($follower->id);
                if (!Carbon::now()->isSameDay($account->getAttribute('updated_at'))) {
                    $account->fill([
                        'name'      => $follower->fullname,
                        'avatar'    => $follower->userpic_url,
                        'followers' => $follower->followers_count,
                        'affection' => $follower->affection,
                    ]);
                    $account->save();
                }
            } catch (ModelNotFoundException $e) {
                $this->persistFollower($follower);
            }
        }
    }

    /**
     * Persist a followed user to the database
     *
     * @param $follower
     */
    private function persistFollower($follower)
    {
        Account::create([
            'id'        => $follower->id,
            'username'  => $follower->username ?? null,
            'name'      => $follower->fullname ?? null,
            'avatar'    => $follower->userpic_url ?? null,
            'followers' => (int)$follower->followers_count >= 0 ? (int)$follower->followers_count : 0,
            'affection' => (int)$follower->affection >= 0 ? (int)$follower->affection : 0,
        ]);
    }
}
