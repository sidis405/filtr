<?php

namespace App\Handlers\Commands\Links;

use App\Commands\Links\CreateLinkCommand;
use App\Events\Links\LinkWasCreated;
use Event;
use Filtr\Models\Links;
use Filtr\Repositories\LinksRepo;
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


        // logger($command->url);

        if ( is_array($command->url)){

            $urls = $command->url;

            foreach ($urls as $url) {
                logger($url);
                $this->process($url);
            }
        } else {
            return $this->process($command->url);
        }

        
    }

    public function process($url)
    {
        $readability_data = $this->links->getReadability($url);

        $link_object = Links::make(
                $url, 
                $readability_data['title'], 
                null,
                null,
                null,
                $readability_data['content'],
                Auth::user()->id, 
                sluggifyUrl($url), 
                getDomainFromUrl($url),
                md5(sluggifyUrl($url)),
                round(str_word_count($readability_data['content'], 0)/130)
            );


        $link = $this->links->save($link_object);

        Event::fire(new LinkWasCreated($link, $readability_data));

        return $link;
    }

}
