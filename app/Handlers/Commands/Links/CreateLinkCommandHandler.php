<?php

namespace App\Handlers\Commands\Links;

use App\Commands\Links\CreateLinkCommand;
use App\Events\Links\LinkWasCreated;
use Event;
use Filtr\Models\Links;
use Filtr\Models\ExternalLinks;
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
        if ( is_array($command->url)){

            $urls = $command->url;

            foreach ($urls as $url) {
                $this->process($url, $command->parent_link, $command->isAutomated);
            }
        } else {
            return $this->process($command->url, $command->parent_link, $command->isAutomated);
        }

        
    }

    public function process($url, $parent, $isAutomated)
    {

        $existing = $this->links->getBySlug(sluggifyUrl($url));

        if ( ! $existing ) {

            $readability_data = $this->links->getReadability($url);

            $user = ($isAutomated) ? 2: Auth::user()->id;

            $link_object = Links::make(
                    $url, 
                    $readability_data['title'], 
                    null,
                    null,
                    null,
                    $readability_data['content'],
                    $user,
                    sluggifyUrl($url), 
                    getDomainFromUrl($url),
                    md5(sluggifyUrl($url)),
                    round(str_word_count($readability_data['content'], 0)/130)
                );


            $link = $this->links->save($link_object);

            if($isAutomated){
                $this->replaceReferenceOnParent($link, $parent);                
            }

            // if($parent) $this->replaceReferenceOnParent($link, $parent);

            // dd('create handler ' . $url);


            Event::fire(new LinkWasCreated($link, $readability_data, $isAutomated));

            return $link;

        }

    }

    public function replaceReferenceOnParent($link, $parent)
    {
        $parent_item = Links::find($parent);
        $parent_item->update(['content' => str_replace($link->url, '/' . $link->slug, $parent_item->content), 'indexed_link' => 1]);
        $external_link = ExternalLinks::find($link->id);
        if($external_link){
            $external_link->update(['processed' => 1]);
        }
    }

}
