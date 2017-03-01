<?php

namespace App\Listeners\Photos;

use App\Events\PhotoCreated;
use App\Http\Services\FiveHundredPxService;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchFullSize implements ShouldQueue
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
        $photo = $this->api->authenticateClient($event->getPhoto()->user)
            ->get('photos/' . $event->getPhoto()->getAttribute('id'),
                [
                    'query' =>
                        [
                            'image_size' => 4,
                            'consumer_key' => getenv('500PX_KEY'),
                        ]
                ]);

        $event->getPhoto()->setAttribute('url_full', $photo->photo->image_url)->save();
    }
}
