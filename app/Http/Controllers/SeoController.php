<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function sitemap()
    {
        return response()->view('seo.sitemap')->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        return response()->view('seo.robots')->header('Content-Type', 'text/plain');
    }

    public function meta($page = null)
    {
        return view('seo.meta', compact('page'));
    }
}