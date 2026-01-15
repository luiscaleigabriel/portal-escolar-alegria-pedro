<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <title>Alegria Pedro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    @include('layouts.sidebar')
    @include('layouts.header')

    <main class="main-wrapper">
        @yield('content')
    </main>

</body>

</html>
