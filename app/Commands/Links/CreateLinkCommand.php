<?php

namespace App\Commands\Links;

use App\Commands\Command;

class CreateLinkCommand extends Command
{
    public $url;

    /**
     * Create a new link instance
     * @param string $url the url to fetch
     */
    public function __construct($url)
    {
        $this->url = $url;
    }
}
