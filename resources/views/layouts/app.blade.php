<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="http://unpkg.com/turbolinks" defer></script>
</head>

<body class="font-sans antialiased min-h-screen bg-gray-100">

    <div class="shadow-sm">
        @include('layouts.navigation')
    </div>

    <section class="max-w-7xl mx-auto sm-px:6 lg:px-8">
        <div class="flex flex-col lg:flex-row w-full gap-3">

            <div class="w-full">
                <!-- Page Heading -->
                <header class="mx-auto py-6 px-4">
                    {{ $header }}
                </header>

                <!-- Page Content -->
                <main class="mx-auto">
                    {{ $slot }}
                </main>
            </div>

            <aside class="{{ isset($sidebar) ? 'lg:w-1/3' : '' }}">
                {{ $sidebar ?? '' }}
            </aside>

        </div>
    </section>

</body>

</html>