<?php

namespace Filtr\Utils\Media;

use Filtr\Models\Links;

/**
* Media Library Uploader Class
*/
class Media
{
    public function attach(Links $model, $file)
    {
	   if ( strlen($file) < 5){
                logger('Something is wrong. The funtion received as input: '  . $file);
                return '';
        }

        $stored_file = $this->getRemoteFile($file);

        $path = $model->addMedia($stored_file)->withCustomProperties(['original_url' => $file])->toCollection('images')->getUrl();
        
        return $path;
    }

    public function getRemoteFile($file)
    {

        $data = @file_get_contents($file);

        $new_name = '/fetched/'.time().'.jpg';

        file_put_contents(public_path() . $new_name, $data);

        return public_path() . $new_name;

    }
}
