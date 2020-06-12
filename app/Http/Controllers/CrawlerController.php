<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPHtmlParser\Dom;
use Illuminate\Support\Str;

class CrawlerController extends Controller
{
    public function crawl(Request $request)
    {
        if (!$request->query('url')) {
            die('url not provided!');
        }

        try {
            $dom = new Dom;
            $dom->loadFromUrl($request->query('url'));
            $texts = strip_tags($dom->find('body')->innerHtml);
            // filter text only alpha & whitespace
            $filteredTexts = preg_replace("/[^a-zA-Z ]+/", "", $texts);
            $counted = Str::of($filteredTexts)
                ->explode(' ')
                ->filter(function($v){
                    if (Str::of($v)->isEmpty()) return false;
                    if (is_numeric($v)) return false;
                    if ($v=="0") return false;
                    return true;
                })
                ->map(function($v){
                    return Str::lower($v);
                })
                ->countBy()
                ->sortDesc();
            return response()->json($counted->all());
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
