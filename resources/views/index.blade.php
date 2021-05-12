<!DOCTYPE html>
<html lang="">

    <head>
        <meta charset="utf-8" />
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="manifest" href="site.webmanifest" />
        <link rel="apple-touch-icon" href="icon.png" />

        {{-- <!-- Place favicon.ico in the root directory --> --}}

        <link rel="stylesheet" href="css/bundle.css" />
        <meta name="theme-color" content="#fafafa" />
        <link rel="stylesheet" href="css/bundle.css" />
        <script src="js/vendor/pig.min.js"></script>
        <script src="js/bundle.js"></script>
        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    @inertia

</html>
