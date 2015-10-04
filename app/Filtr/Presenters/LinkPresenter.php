<?php

namespace Filtr\Presenters;

use Illuminate\Support\Facades\Session;
use Laracasts\Presenter\Presenter;

class LinkPresenter extends Presenter
{
    public function getNextArticleForLoad($slugs)
    {
        $current_stream = Session::get('current_stream');

        foreach ($slugs as $slug) {
            
            if(! in_array($slug, $current_stream))
            {
                return ' data-next=' . $slug . ' data-load =true';
            }

        }

        return ' data-load =false';
    }

    public function getNextArticlePreview($related)
    {
        $slugs = array_keys($related);

        $current_stream = Session::get('current_stream');

        foreach ($slugs as $slug) {
            
            if(! in_array($slug, $current_stream))
            {
                return json_decode(json_encode([$related[$slug]['info']]), FALSE);
            }

        }

        return [];
    }
}
