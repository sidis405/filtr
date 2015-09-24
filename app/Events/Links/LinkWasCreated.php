<?php

namespace App\Events\Links;

use App\Events\Event;
use Filtr\Models\Links;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class LinkWasCreated extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Links $link, $readability)
    {
        $this->link         = $link;
        $this->readability  = $readability;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}