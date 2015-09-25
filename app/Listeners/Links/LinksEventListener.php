<?php

namespace App\Listeners\Links;

use App\Events\Links\LinkWasCreated;
use App\Jobs\Links\PostProcessLink;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Bus\DispatchesJobs;

class LinksEventListener
{
    use DispatchesJobs;
    /**
     * Handle link creation post processing
     */
    public function onLinkCreation(LinkWasCreated $event) {


        $this->dispatch(new PostProcessLink($event));

    }


    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'App\Events\Links\LinkWasCreated',
            'App\Listeners\Links\LinksEventListener@onLinkCreation'
        );
    }

}
