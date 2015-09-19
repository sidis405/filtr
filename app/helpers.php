<?php


function sluggifyUrl($url)
{
    return str_slug(str_replace(['.', '/'] , '-', str_replace(['www', 'http://'], '', $url)));
}