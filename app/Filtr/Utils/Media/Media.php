<?php

namespace Filtr\Utils\Media;

use Vinkla\Hashids\Facades\Hashids;

/**
* Media Library Uploader Class
*/
class Media
{
    public function attach($model, $file)
    {
        
        $stored_file = $this->getRemoteFile($file);

        $path = $model->addMedia($stored_file)->withCustomProperties(['original_url' => $file])->toCollection('images')->getUrl();
        
        return $path;
    }

    public function getRemoteFile($file)
    {
        $data = file_get_contents($file);

        $new_name = '/fetched/'.time().'.jpg';

        file_put_contents(public_path() . $new_name, $data);

        return public_path() . $new_name;

    }
}
