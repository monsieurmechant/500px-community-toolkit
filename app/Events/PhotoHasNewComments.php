<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PhotoHasNewComments implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /** @var \App\Photo $photo */
    public $photo;

    /**
     * Create a new event instance.
     *
     * @param \App\Photo $photo
     */
    public function __construct(\App\Photo $photo)
    {
        $this->photo = $photo;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->photo->user->getAttribute('id'));
    }
}
