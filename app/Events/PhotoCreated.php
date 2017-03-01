<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PhotoCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var \App\Photo
     */
    protected $photo;

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
     * @return \App\Photo
     */
    public function getPhoto(): \App\Photo
    {
        return $this->photo;
    }

//    /**
//     * Get the channels the event should broadcast on.
//     *
//     * @return Channel|array
//     */
//    public function broadcastOn()
//    {
//        return new PrivateChannel('channel-name');
//    }
}
