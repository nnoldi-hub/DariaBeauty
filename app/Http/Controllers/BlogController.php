<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        return view('blog.index');
    }

    public function show($slug)
    {
        return view('blog.show', compact('slug'));
    }

    public function create()
    {
        return view('blog.create');
    }

    public function store(Request $request)
    {
        // Logica pentru salvarea articolului
        return redirect()->route('blog.index');
    }
}