<?php

namespace Filtr\Presenters;

use Illuminate\Support\Facades\Session;
use Laracasts\Presenter\Presenter;

class UserPresenter extends Presenter
{
    public function doesUserFollowThis($entity_id)
    {

            $followed = array_pluck($this->entities, 'id');

            if(in_array($entity_id, $followed)) return true;

            return false;


    }

    public function entityFollowButton($entity_id, $entity_slug, $entity_text)
    {
        $follows = $this->doesUserFollowThis($entity_id);

        if($follows)
        {
            return '<a class="btn btn-success entity-follow entity-followtrue" data-id="'. $entity_id .'" data-slug="'. $entity_slug .'" data-text="'. $entity_text .'" data-follows="' . $follows . '">Unfollow</a>';
        }

        return '<a class="btn btn-success entity-follow entity-followfalse" data-id="'. $entity_id .'" data-slug="'. $entity_slug .'" data-text="'. $entity_text .'" data-follows="' . $follows . '">Follow</a>';
    }
}
