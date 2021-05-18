<!DOCTYPE html>
<html lang="">

    <head>
        <meta charset="utf-8" />
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- See https://github.com/h5bp/html5-boilerplate for details -->

        <!-- see https://github.com/h5bp/html5-boilerplate/blob/fd9f88e846a409088e81292b24ac74a53e86c693/src/doc/html.md#web-app-manifest -->
        <link rel="manifest" href="site.webmanifest" />
        <link rel="apple-touch-icon" href="icon.png" />

        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="css/bundle.css" />

        <!-- see https://github.com/h5bp/html5-boilerplate/blob/0ebf00b4c24ea4e27fdb63ff312740f3b6aa14fe/dist/doc/extend.md#theme-color -->
        <meta name="theme-color" content="#fafafa" />
    </head>

    <body>
        <div class="login">
            <form class="js-login__form login__form" action="{{ route('login') }}" method="POST">
                @csrf
                <h1 class="fw-light">Photos</h1>
                <input required placeholder="Email" name="email" class="mb-3 form-control form-control-lg" type="text">
                <input required placeholder="Password" name="password" class="mb-3 form-control form-control-lg"
                    type="password">
                <input type="hidden" name="remember" value="1" />
                <button type="submit" class="login__submit-btn btn btn-lg btn-primary">
                    Enter
                </button>
            </form>

        </div>

        <div aria-live="polite" aria-atomic="true" class="notification-area">
            <div class="js-notification-container notification-area__container toast-container">
                <!-- Put toasts here -->

                <div class="js-invalid-drop-note notification-area__toast toast align-items-center" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">Some of the file types you dropped aren't allowed</div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>

        <script src="js/login.js"></script>
    </body>

</html>
