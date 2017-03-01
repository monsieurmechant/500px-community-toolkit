<?php

namespace App\Listeners\Photos;

use App\Events\PhotoCreated;
use App\Http\Services\FiveHundredPxService;
use App\Jobs\FetchMediaComments;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchComments implements ShouldQueue
{

    /**
     * @var FiveHundredPxService
     */
    private $api;

    /**
     * Create the event listener.
     *
     * @param FiveHundredPxService $api
     */
    public function __construct(FiveHundredPxService $api)
    {
        $this->api = $api;
    }

    /**
     * Requests a photo from the API and saves
     * the Hi-Res URL to the Database
     *
     * @param PhotoCreated $event
     * @return void
     */
    public function handle(PhotoCreated $event)
    {
        dispatch(
            new FetchMediaComments($event->getPhoto()->getAttribute('id'))
        );
    }
}
