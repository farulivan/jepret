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
                    <input type="file" id="imageFileInput" accept="image/*" style="display: none;">
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
            <form id="formCreatePost" action="#" autocomplete="off">
                <input type="text" name="imageCaption" id="imageCaption" placeholder="Insert caption here..."
                    required>
                <button type="submit" class="contrast">Publish</button>
            </form>
        </article>
    </dialog>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/session.js') }}"></script>
    <script src="{{ asset('assets/js/posts.js') }}"></script>
    <script defer>
        document.getElementById('btnSelectImage').addEventListener('click', function() {
            document.getElementById('imageFileInput').click();
        });

        document.getElementById('imageFileInput').addEventListener('change', function() {
            if (this.files.length > 0) {
                // Assuming the uploadPhoto function in posts.js handles the upload and opens the modal
                uploadPhoto(this.files[0]); // Pass the file directly to the upload function
            }
        });

        startRefreshingToken();
        getPosts();
    </script>
</body>

</html>
