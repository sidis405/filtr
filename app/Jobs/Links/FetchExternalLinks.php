<?php

namespace App\Jobs\Links;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchExternalLinks extends Job implements SelfHandling, ShouldQueue
{
    public $link;
    public $parent_id;
    public $request;

    use InteractsWithQueue, SerializesModels, DispatchesJobs;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Request $request, $link, $parent_id)
    {
        $this->link = $link;
        $this->request = $request;
        $this->parent_id = $parent_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    { 
        $this->dispatchFrom('App\Commands\Links\CreateLinkCommand', $this->request, ['url' => $this->link->url, 'parent_link' => $this->parent_id, 'isAutomated' => true]);
    }
}
