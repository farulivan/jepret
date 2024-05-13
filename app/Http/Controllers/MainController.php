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
        // validate incoming request
        $request->validate([
            'image' => 'required|file|mimes:jpeg,jpg|max:1024', // max 1MB
            'caption' => 'required'
        ]);

        // store the file to s3 using s3 storage library, notice that in the
        // `config/filesystem.php` we already set it to throw exception when
        // any errors occur during uploading to S3 bucket just like suggested
        // by @fatkulnurk
        $file = $request->file('image');
        $fileName = now()->timestamp . '_' . $file->getClientOriginalName();
        $filePath = $file->storePubliclyAs('uploads', $fileName, 's3');

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
