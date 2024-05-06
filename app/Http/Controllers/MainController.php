<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MainController extends Controller
{
    public function showMain()
    {
        $posts = Post::latest()->get();
        return view('main', ['posts' => $posts]);
    }

    public function uploadPost(Request $request) {
        $request->validate([
            'image' => 'required|file|mimes:jpeg,jpg|max:1024', // max 1MB
            'caption' => 'required'
        ]);

        // upload image to s3, here we are using s3 client from AWS SDK PHP
        // instead storage library provided by Laravel, the reason is that
        // the Laravel storage library doesn't throw any exception when error
        // is happening during upload process to S3, this makes debugging
        // quite difficult, but s3 client from AWS SDK PHP will throw exception
        // when something not right occurred and this is what we want.
        $file = $request->file('image');
        $fileName = now()->timestamp . '_' . $file->getClientOriginalName();
        $filePath = $file->storePubliclyAs('uploads', $fileName, 's3');
        dd($filePath);
        // get url & adjust it to localhost because the image will
        // be displayed on browser
        $photoUrl = Str::replace(
            'localstack',
            'localhost',
            Storage::disk('s3')->url($filePath),
        );

        // create new post
        $request->user()->posts()->create([
            'photo_url' => $photoUrl,
            'caption' => $request->get('caption'),
            'created_at' => now()->timestamp,
        ]);

        // redirect back to main page
        return redirect(route('main-show'));
    }
}
