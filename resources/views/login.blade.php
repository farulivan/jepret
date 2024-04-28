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
                    <form onsubmit="btnLogin(); return false;">
                        <input type="email" placeholder="Email">
                        <input type="password" placeholder="Password">
                        <button class="contrast" type="submit">Login</button>
                    </form>
                    </form>
                </article>
            </div>
        </div>
    </main>
    <script src="{{ asset('assets/js/session.js') }}"></script>
    <script defer>
        function btnLogin() {
            // prevent default form submission
            event.preventDefault();
            var email = document.querySelector('#loginFormHolder input[type="email"]').value;
            var password = document.querySelector('#loginFormHolder input[type="password"]').value;
            console.log([email, password])
            login(email, password);
        }
    </script>
</body>

</html>
