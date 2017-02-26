<?php

namespace App\Jobs;

use App\Account;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Exceptions\JobDoneException;
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
        $collectedIds = [];
        for ($currentPage = 1; $currentPage <= $pages; $currentPage++) {
            $follows = $client->get(
                'users/' . $user->id . '/followers',
                ['query' => ['rpp' => 100, 'page' => $currentPage]]
            );

            if ($currentPage === 1) {
                $pages = $follows->followers_pages;
            }

            $collectedIds = array_merge($collectedIds, $this->saveFollowersPage($follows->followers));
        }

        $user->followers()->sync($collectedIds);

    }

    /**
     * Persists all the followers to the Database
     *
     * @param array $followers
     * @return array An array of IDs
     */
    private function saveFollowersPage(array $followers)
    {
        $collectedIds = [];
        foreach ($followers as $follower) {
            $collectedIds[] = $follower->id;
            try {
                /** @var Account $account */
                $account = Account::findOrFail($follower->id);
                if (!Carbon::now()->isSameDay($account->getAttribute('updated_at'))) {
                    $account->setAttribute('followers', $follower->followers);
                    $account->setAttribute('affection', $follower->affection);
                    $account->save();
                }
            } catch (ModelNotFoundException $e) {
                $this->persistFollower($follower, $account);
            }
        }
        return $collectedIds;
    }

    /**
     * Persist a followed user to the database
     *
     * @param $follower
     */
    private function persistFollower($follower)
    {
        Account::create([
            'id'=> $follower->id,
            'nickname'=> $follower->nickname,
            'name'=> $follower->fullname,
            'avatar'=> $follower->userpic_url,
            'followers'=> $follower->followers,
            'affection'=> $follower->affection,
        ]);
    }
}
