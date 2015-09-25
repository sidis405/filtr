<?php

namespace App\Handlers\Commands\Links;

use App\Commands\Links\CreateLinkCommand;
use App\Events\Links\LinkWasCreated;
use Event;
use Filtr\Models\Links;
use Filtr\Repositories\LinksRepo;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;



class CreateLinkCommandHandler
{
    public $links;

    /**
     * Create the command handler.
     */
    public function __construct(LinksRepo $links)
    {
        $this->links = $links;
    }

    /**
     * Handle the command.
     *
     * @param  CreateLinkCommand  $command
     * @return void
     */
    public function handle(CreateLinkCommand $command)
    {

        $readability_data = $this->links->getReadability($command->url);

        $link_object = Links::make(
                $command->url, 
                $readability_data['title'], 
                null,
                null,
                null,
                $readability_data['content'],
                Auth::user()->id, 
                sluggifyUrl($command->url), 
                getDomainFromUrl($command->url),
                md5(sluggifyUrl($command->url))
            );

        $link = $this->links->save($link_object);

        Event::fire(new LinkWasCreated($link, $readability_data));

        return $link;
    }

}
