<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($title) ? $title :  config('app.name') }}</title>

        {{-- Scripts  --}}
        @routes
        <script src="{{ mix('js/vendor/pig.js') }}"></script>

        @if(!isset($public))
        <script src="{{ mix('js/manifest.js') }}" defer></script>
        <script src="{{ mix('js/vendor.js') }}" defer></script>
        <script src="{{ mix('js/app.js') }}" defer></script>
        @else
        <script src="/js/public.js" defer></script>
        @endif
    </head>



    <body class="is-uploading">

        @inertia
        <script src="/js/frontend/bundle.js"></script>
    </body>

</html>
