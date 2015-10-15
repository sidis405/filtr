<?php

namespace App\Commands\Links;

use App\Commands\Command;

class CreateLinkCommand extends Command
{
    public $url;
    public $parent_link;
    public $isAutomated;

    /**
     * Create a new link instance
     * @param string $url the url to fetch
     */
    public function __construct($url, $parent_link = 0, $isAutomated = false)
    { 
        $this->url = $url;
        $this->parent_link = $parent_link;
        $this->isAutomated = $isAutomated;
    }
}
