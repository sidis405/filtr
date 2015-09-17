<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Filtr\Utils\Readability;
use League\CommonMark\CommonMarkConverter;
use Illuminate\Http\Request;
use League\HTMLToMarkdown\HtmlConverter;

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        header('Content-Type: text/plain; charset=utf-8');

        // get latest Medialens alert 
        // (change this URL to whatever you'd like to test)
        $url = 'http://www.wired.co.uk/magazine/archive/2015/03/start/scaling-dublin-summit';
        $html = file_get_contents($url);

        // Note: PHP Readability expects UTF-8 encoded content.
        // If your content is not UTF-8 encoded, convert it 
        // first before passing it to PHP Readability. 
        // Both iconv() and mb_convert_encoding() can do this.

        // If we've got Tidy, let's clean up input.
        // This step is highly recommended - PHP's default HTML parser
        // often doesn't do a great job and results in strange output.
        if (function_exists('tidy_parse_string')) {
            $tidy = tidy_parse_string($html, array(), 'UTF8');
            $tidy->cleanRepair();
            $html = $tidy->value;
        }

        // give it to Readability
        $readability = new Readability($html, $url);
        // print debug output? 
        // useful to compare against Arc90's original JS version - 
        // simply click the bookmarklet with FireBug's console window open
        $readability->debug = false;
        // convert links to footnotes?
        $readability->convertLinksToFootnotes = true;
        // process it
        $result = $readability->init();

        $out = '';
        // does it look like we found what we wanted?
        if ($result) {
            // $out .= "== Title =====================================\n";
            $out .= "<h2>".$readability->getTitle()->textContent . "</h2>". "\n\n";
            // $out .= "== Body ======================================\n";
            $content = $readability->getContent()->innerHTML;
            // if we've got Tidy, let's clean it up for output
            if (function_exists('tidy_parse_string')) {
                $tidy = tidy_parse_string($content, array('indent'=>true, 'show-body-only' => true), 'UTF8');
                $tidy->cleanRepair();
                $content = $tidy->value;
            }
            $out .= $content;
        } else {
            $out .= 'Looks like we couldn\'t find the content. :(';
        }

        // $html2mark = new HtmlConverter();

        // $mark2html = new CommonMarkConverter();

        // $out = $mark2html->convertToHtml($html2mark->convert($out));

        $data = $out;

        return view('index', compact('data'));

        

    }

}
