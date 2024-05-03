<?php

namespace App\Http\Controllers;

use App\Models\Post;

class MainController extends Controller
{
    public function showMain()
    {
        $posts = Post::latest()->get();
        return view('main', ['posts' => $posts]);
    }
}
