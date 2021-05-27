<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"> --}}

        <!-- Scripts -->
        <script src="/js/vendor/pig.min.js"></script>

        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>



    <body class="is-uploading">

        @inertia
        <script src="/js/frontend/bundle.js"></script>
    </body>

</html>
