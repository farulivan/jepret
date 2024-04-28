<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitPostRequest;
use App\Services\PostServices\PostServiceInterface;
use App\Services\UserServices\UserServiceInterface;

class PostController extends Controller
{
    public function __construct(
        protected PostServiceInterface $postService,
        protected UserServiceInterface $userService,
    ) {
    }

    /**
     * Discover posts available.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function discoverPosts(Request $request): JsonResponse
    {
        $posts = $this->postService->getPosts();
        return JsonResponseHelper::successDiscoverPosts($posts);
    }

    /**
     * Request a URL for uploading a photo.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function requestPhotoUrl(Request $request): JsonResponse
    {
        $photoUrl = $this->postService->generatePhotoUrl();
        return JsonResponseHelper::successRequestPhotoUrl($photoUrl);
    }

    /**
     * Submit a new post.
     *
     * @param SubmitPostRequest $request
     * @return JsonResponse
     */
    public function submitPost(SubmitPostRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $accessToken = $request->bearerToken();
        $post = $this->postService->store($accessToken, $validated);
        $userDetails = $this->userService->getById($post['author_id']);
        return JsonResponseHelper::successSubmitPost($post, $userDetails);
    }
}
