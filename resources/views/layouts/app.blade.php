<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- NiceAdmin CSS -->
    <link rel="stylesheet" href="{{ asset('niceadmin/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('niceadmin/vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('niceadmin/css/style.css') }}">
</head>
<body>

    @include('layouts.header') <!-- Include Header -->
    @include('layouts.sidebar') <!-- Include Sidebar -->

    <main id="main" class="main">
        @yield('content') <!-- Dynamic Page Content -->
    </main>
    @include('layouts.footer') <!-- Include footer -->

    <!-- NiceAdmin JS -->
    <script src="{{ asset('niceadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('niceadmin/js/main.js') }}"></script>

</body>
</html>
