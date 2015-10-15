<?php

namespace App\Listeners\Links;

use App\Events\Links\LinkWasCreated;
use App\Events\Links\LinkWasProcessed;
use App\Jobs\Links\FetchExternalLinks;
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
     * Handle link creation post processing
     */
    public function onLinkProcessing(LinkWasProcessed $event) {

        $config = \Config::get('filtr');

        if($config['fetch_external_links']){
            if(! $event->isAutomated){
                foreach($event->link->validExternals as $external){

                    $this->dispatch(new FetchExternalLinks(new \Illuminate\Http\Request, $external, $event->link->id));

                }
            }
        }
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

        $events->listen(
            'App\Events\Links\LinkWasProcessed',
            'App\Listeners\Links\LinksEventListener@onLinkProcessing'
        );
    }

}
