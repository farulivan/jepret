<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jepret - Main</title>
    <link rel="stylesheet" href="{{ asset('assets/css/pico.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flexboxgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
    <nav class="container-fluid">
        <ul>
            <li><strong>Jepret</strong></li>
        </ul>
        <ul>
            <li>
                <div class="action-button">
                    <img src="assets/icons/camera.svg" alt="Upload Image" id="btnSelectImage" style="cursor: pointer;">
                </div>
            </li>
            <li>
                <div class="action-button" id="btnLogout">
                    <a href="{{ route('logout') }}">
                        <img src="assets/icons/log-out.svg" alt="" srcset="">
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <main class="container" id="postListContainer">
        @if ($errors->has('message'))
            <p class="alert-error">{{ $errors->first('message') }}</p>
        @endif
        <div class="row">
            @forelse($posts as $post)
                <div class="col-md-4">
                    <article class="post-container">
                        <figure>
                            <img src="{{ $post->photo_url  }}" alt="" srcset="">
                        </figure>
                        <div class="caption-container">
                            <p class="caption">{{ $post->caption }}</p>
                            <p class="meta">{{ '@' . $post->author->handle }} - {{ \Carbon\Carbon::createFromTimestamp($post->created_at)->format('D, d F Y') }}</p>
                        </div>
                    </article>
                </div>
            @empty
                <h5>No posts found</h5>
            @endforelse
        </div>
    </main>
    <dialog id="modalCreatePost">
        <article>
            <header>
                <a href="#close" aria-label="Close" class="close" id="btnCancelCreatePost"></a>
                <p>Create Post</p>
            </header>
            <figure>
                <img src="" alt="" id="selectedImage">
            </figure>
            <div id="uploadProgress"></div>
            <form id="formCreatePost" method="post" action="{{ route('upload-post') }}" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <input type="text" name="caption" id="imageCaption" placeholder="Insert caption here..."
                    required>
                <input type="file" id="imageFileInput" accept="image/jpeg" name="image" style="display: none;">
                <button type="submit" class="contrast">Publish</button>
            </form>
        </article>
    </dialog>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
