<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            'SocialiteProviders\FiveHundredPixel\FiveHundredPixelExtendSocialite@handle',
        ],
        \App\Events\UserCreated::class => [
            \App\Listeners\Users\FetchFollowers::class,
            \App\Listeners\Users\FetchMedias::class,
        ],
        \App\Events\PhotoCreated::class => [
            \App\Listeners\Photos\FetchComments::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
