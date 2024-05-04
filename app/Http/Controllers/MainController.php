<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Illuminate\Support\Str;

class MainController extends Controller
{
    protected S3Client $s3;

    public function __construct(S3Client $s3) {
        $this->s3 = $s3;
    }

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
        $filePath = $file->getPathname();

        $result = $this->s3->putObject([
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key'    => "uploads/{$fileName}",
            'SourceFile' => $filePath,
            'ACL'    => 'public-read'
        ]);

        // get url & adjust it to localhost because the image will
        // be displayed on browser
        $photoUrl = Str::replace(
            'localstack',
            'localhost',
            $result->get('ObjectURL'),
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
