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
     * Construct
     * @param Links $link Link model
     */
    public function __construct(Links $link, $isAutomated)
    {
        $this->link = $link;
        $this->isAutomated = $isAutomated;
        $this->data = array(
            'command'=> 'reload'
        );
    }

    /**
     * Get the channels the event should be broadcast on.
     * @property string $this->link->id Id of the link being processed
     * @return array
     */
    public function broadcastOn()
    {
        return ['link_' . $this->link->id];
    }
}
