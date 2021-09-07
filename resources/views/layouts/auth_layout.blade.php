<!DOCTYPE html>
<html lang="">

    <head>
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#000000">
        <meta charset="utf-8" />
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- See https://github.com/h5bp/html5-boilerplate for details -->

        <!-- see https://github.com/h5bp/html5-boilerplate/blob/fd9f88e846a409088e81292b24ac74a53e86c693/src/doc/html.md#web-app-manifest -->
        {{-- <link rel="manifest" href="site.webmanifest" /> --}}
        <link rel="apple-touch-icon" href="icon.png" />

        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="/css/bundle.css" />

        <!-- see https://github.com/h5bp/html5-boilerplate/blob/0ebf00b4c24ea4e27fdb63ff312740f3b6aa14fe/dist/doc/extend.md#theme-color -->
        <meta name="theme-color" content="#fafafa" />
        @yield('styles')
    </head>

    <body>

        @yield('content')
        @yield('scripts')
    </body>

</html>
