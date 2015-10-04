<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Filtr\Repositories\EntitiesRepo;
use Illuminate\Http\Request;

class EntitiesController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($slug, EntitiesRepo $entities)
    {
        $entity = $entities->getBySlug($slug);

        return $entity;
    }

    public function updateScreenshots(EntitiesRepo $entities)
    {
        return $entities->makeScreenShots();
    }
}
