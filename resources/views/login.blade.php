<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jepret - Login</title>
    <link rel="stylesheet" href="{{ asset('assets/css/pico.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flexboxgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
    <main class="row is-fullheight">
        <div class="col-md-12">
            <div id="loginFormLayout">
                <article id="loginFormHolder">
                    <hgroup>
                        <h1>Jepret</h1>
                        <h2>Mudah berbagi foto-fotomu!</h2>
                    </hgroup>
                    <form method="post" action="{{ route('login-show')  }}">
                        @csrf
                        <input type="email" placeholder="Email" name="email" required>
                        <input type="password" placeholder="Password" name="password" required>
                        <button class="contrast" type="submit">Login</button>
                    </form>
                    @if ($errors->has('email'))
                        <p class="alert-error">{{ $errors->first('email') }}</p>
                    @endif
                </article>
            </div>
        </div>
    </main>
</body>

</html>
