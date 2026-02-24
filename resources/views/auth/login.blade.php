<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Koshouko</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #E6CFA1 0%, #F3E9D2 100%);
            color: #3B2A1A;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, #8B5A2B 0%, #B63A2B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-light {
            background: #FEF9F3;
            border: 1px solid rgba(139, 90, 43, 0.1);
            box-shadow: 0 4px 15px rgba(139, 90, 43, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #8B5A2B 0%, #B63A2B 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(139, 90, 43, 0.3);
        }

        .input-light {
            background: #E6CFA1;
            border: 1px solid #CFC7B8;
            color: #3B2A1A;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .input-light:focus {
            outline: none;
            border-color: #8B5A2B;
            box-shadow: 0 0 0 3px rgba(139, 90, 43, 0.1);
        }

        .input-light::placeholder {
            color: #6B4F3F;
        }

        .nav-bar {
            background: #F0E4C8;
            border-bottom: 1px solid #D4C9B0;
            box-shadow: 0 1px 3px rgba(139, 90, 43, 0.08);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease;
        }

        .glow {
            box-shadow: 0 0 20px rgba(139, 90, 43, 0.2);
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex flex-col">
        <!-- Navigation Bar -->
        <nav class="nav-bar sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="/logo_koshouko.jpeg" alt="Koshouko" class="w-10 h-10 rounded-full object-cover glow">
                    <div>
                        <h1 class="text-xl font-bold gradient-text">Koshouko</h1>
                        <p class="text-xs text-koshouko-text-muted">Digital Library System</p>
                    </div>
                </div>
                <div class="text-sm text-koshouko-text-muted">
                    Platform Perpustakaan Modern
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <div class="card-light rounded-2xl p-8 glow fade-in">
                    <!-- Header -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold mb-2 text-koshouko-text">Selamat Datang</h2>
                        <p class="text-koshouko-text-muted text-sm">Masuk ke akun Koshouko Anda</p>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            @foreach ($errors->all() as $error)
                                <p class="text-red-700 text-xs">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium mb-2 text-koshouko-text">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="input-light w-full" placeholder="Masukkan email Anda">
                            @error('email')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium mb-2 text-koshouko-text">Password</label>
                            <input type="password" name="password" required
                                class="input-light w-full" placeholder="Masukkan password">
                            @error('password')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember -->
                        <label class="flex items-center space-x-2 text-sm">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-koshouko-cream-light border-koshouko-border">
                            <span class="text-koshouko-text-muted">Ingat saya</span>
                        </label>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-primary w-full mt-6">
                            Masuk
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-koshouko-border"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-koshouko-text-muted">atau</span>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <p class="text-center text-sm text-koshouko-text-muted">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="gradient-text font-semibold hover:underline">
                            Daftar di sini
                        </a>
                    </p>


                </div>

                <!-- Footer -->
                <div class="mt-8 text-center text-xs text-koshouko-text-muted">
                    <p>© 2025 Koshouko. Sistem Manajemen Perpustakaan Digital</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Hide loading screen after page load
        window.addEventListener('load', function() {
            const loadingScreen = document.getElementById('loading-screen');
            if (loadingScreen) {
                setTimeout(() => {
                    loadingScreen.style.opacity = '0';
                    loadingScreen.style.pointerEvents = 'none';
                }, 500);
            }
        });
    </script>
</body>
</html>
