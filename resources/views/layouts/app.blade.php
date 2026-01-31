<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Koshouko')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #D4C09A;
            --secondary: #8B7355;
            --accent: #A0826D;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100" id="app">
    <!-- Loading Screen -->
    <div id="loading-screen" class="fixed inset-0 bg-gray-900 z-50 flex items-center justify-center opacity-100 transition-opacity duration-500" style="transition: opacity 0.5s ease">
        <div class="text-center">
            <div class="relative w-20 h-20 mx-auto mb-6">
                <div class="absolute inset-0 rounded-full border-4 border-gray-700"></div>
                <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-primary border-r-primary animate-spin"></div>
            </div>
            <div class="text-lg font-semibold text-white mb-2">Perpustakaan Digital</div>
            <div class="text-sm text-gray-400">Sedang memuat...</div>
        </div>
    </div>

    @yield('content')

    <script>
        // Hide loading screen when page is ready
        window.addEventListener('load', function() {
            const loadingScreen = document.getElementById('loading-screen');
            if (loadingScreen) {
                loadingScreen.style.opacity = '0';
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 500);
            }
        });
    </script>
</body>
</html>
