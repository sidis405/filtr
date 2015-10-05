<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

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

        exec("ps -A | grep -i $processName | grep -v grep", $pids);

        if (count($pids) > 0) {
            $exists = true;
        }
        return $exists;
    }

    
}
