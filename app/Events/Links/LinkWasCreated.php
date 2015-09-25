<?php

namespace App\Events\Links;

use App\Events\Event;
use Filtr\Models\Links;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class LinkWasCreated extends Event
{
    use SerializesModels;

    public $link;
    public $readability;

    /**
     * Constructor
     * @param Links  $link        Link modesl
     * @param array $readability Readability data
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
