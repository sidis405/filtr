<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Filtr\Repositories\ExternalLinksRepo;
use Illuminate\Http\Request;

class SystemController extends Controller
{

    public function index()
    {
        $processes = [
            [
                'name' => 'beanstalkd',
                'label' => 'Beanstalkd',
                'error_label' => 'Analysis of shared articles articles will not work correctly',
                'error_severity' => 'Very High'
            ],
            [
                'name' => 'elasticsearch',
                'label' => 'ElasticSearch',
                'error_label' => 'Search and sharing articles will not work correctly',
                'error_severity' => 'High'
            ],
            [
                'name' => 'node',
                'label' => 'NodeJs',
                'error_label' => 'Real-time notifications will not work correctly',
                'error_severity' => 'Medium'
            ],
            [
                'name' => 'redis',
                'label' => 'Redis',
                'error_label' => 'Real-time notifications will not work correctly',
                'error_severity' => 'Medium'
            ],
            [
                'name' => 'supervisord',
                'label' => 'Supervisord',
                'error_label' => 'Analysis and search of shared articles articles will not work correctly',
                'error_severity' => 'Critical'
            ]

        ];

        $services = ['failing' => 0];



        foreach ($processes as $process) {

            $status = $this->processExists($process['name']);

            $services[$process['name']] = array_add($process, 'running', $status);

            if ( ! $status ) $services['failing']++;
        }

        // return $services;

        return view('system.index', compact('services'));
    }

    public function processExists($processName)
    {
        $exists= false;

        exec("ps -ef | grep -i $processName | grep -v grep", $pids);

        if (count($pids) > 0) {
            $exists = true;
        }
        return $exists;
    }

    public function checkIfContainsBlackListItem($url, $blacklist)
    {
        foreach($blacklist as $black){
            if(strpos($url, $black->url) > 0){
                return true;
            }
        }

        return false;
    }

    public function externals(ExternalLinksRepo $el)
    {
        $links = $el->getAll(true);

        $blacklist = $el->getBlackList();

        foreach ($links as $link) {

            $parsed_url = parse_url($link->url);

           if( $this->checkIfContainsBlackListItem($link->url, $blacklist) || (isset($parsed_url['path']) && strlen($parsed_url['path']) < 2) ||
                        !filter_var($link->url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED) || 
                        !filter_var($link->url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) || 
                        strpos($link->url, 'ailto:') || 
                        strpos($link->url, 'keywords/') || 
                        strpos($link->url, 'entities/')
                        ){
                $link->update(['valid' => 0, 'processed' => 1]);
           }
        }

        $externals = $el->getAll(true);

        return view('system.externals', compact('externals'));


    }

    
}
