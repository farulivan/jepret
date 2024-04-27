<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PostServices\PostServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct(
        protected PostServiceInterface $postService,
    )
    {
    }

    public function discoverPost(Request $request)
    {
        try {
            $posts = $this->postService->getPosts();
        } catch (\Exception $exception) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_BAD_REQUEST',
                'msg' => $exception->getMessage(),
            ], 400);
        }

        return response()->json([
            'ok' => true,
            'data' => [
                'posts' => $posts,
            ],
        ]);
    }

    public function requestPhotoUrl(Request $request)
    {
        try {
            $data = $this->postService->generatePhotoUrl();
        } catch (\Exception $exception) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_BAD_REQUEST',
                'msg' => $exception->getMessage(),
            ], 400);
        }

        return response()->json([
            'ok' => true,
            'data' => ['photo_url' => $data],
        ]);
    }

    public function storePost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo_url' => ['required', 'string', 'max:60000'],
            'caption' => ['required', 'string', 'max:60000'],
        ]);

        try {
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $data = $this->postService->store(
                $request->user(),
                $validator->valid()
            );
        } catch (\Exception $exception) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_BAD_REQUEST',
                'msg' => $exception->getMessage(),
            ], 400);
        }

        return response()->json([
            'ok' => true,
            'data' => $data,
        ]);
    }
}
