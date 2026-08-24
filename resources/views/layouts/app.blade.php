<!DOCTYPE html>
<html lang="en" data-theme="{{ session('theme', 'dark') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ManjaAlunso Metalworks | Precision Steel Fabrication')</title>
    <meta name="description" content="@yield('description', 'Professional welding, custom steel fabrication, and structural engineering services. Clean, precise, and built to last.')">
    <meta name="theme-color" content="#121212">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Custom CSS -->
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    @include('partials.navbar')

    <!-- Main content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Back to top button -->
    <button class="back-to-top" id="backToTop" aria-label="Back to top"><i class="ri-arrow-up-line"></i></button>

    <!-- Scripts -->
    <script src="{{ asset('js/script.js') }}" defer></script>
    @stack('scripts')
</body>
</html>