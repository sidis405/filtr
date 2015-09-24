<?php

namespace App\Events\Links;

use App\Events\Event;
use Filtr\Models\Links;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class LinkWasProcessed extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $link;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Links $link)
    {
        $this->link = $link;
        $this->data = array(
            'command'=> 'reload'
        );
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['link_' . $this->link->id];
    }
}