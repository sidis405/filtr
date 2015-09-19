<?php

namespace App\Commands\Links;

use App\Commands\Command;

class CreateLinkCommand extends Command
{
    public $url;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        //
        $this->url = $url;
    }
}
