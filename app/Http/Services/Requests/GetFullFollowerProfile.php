<?php

namespace App\Http\Services\Requests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Http\Services\FiveHundredPxService;

class GetFullFollowerProfile
{

    /**
     * @var FiveHundredPxService
     */
    private $api;

    /**
     * Create a new job instance.
     *
     * @param FiveHundredPxService $api
     */
    public function __construct(FiveHundredPxService $api)
    {
        $this->api = $api;
    }

    /**
     * Execute the job.
     *
     * @param int $followerId
     * @param \App\User $user
     * @return void
     */
    public function handle(int $followerId, \App\User $user)
    {
        return Cache::remember(
            'followerProfile' . $followerId,
            Carbon::now()->addWeek(),
            function () use ($followerId, $user) {
                return $this->api->authenticateClient($user)
                    ->get(
                        'users/show',
                        [
                            'query' => [
                                'id'           => $followerId,
                                'consumer_key' => getenv('500PX_KEY'),
                            ]
                        ]
                    );
            }
        );
    }
}
