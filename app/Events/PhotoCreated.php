<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

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
}
