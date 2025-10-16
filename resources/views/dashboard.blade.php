<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>WoodyCraft</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Tailwind / App CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gradient-to-br from-green-50 via-white to-green-100 min-h-screen flex items-center justify-center">

    <div class="text-center">
        <!-- Bloc principal -->
        <h1 class="text-5xl font-extrabold text-gray-900 mb-6">
            Bienvenue sur <span class="text-green-600">WoodyCraft</span>
        </h1>

        <p class="text-lg text-gray-600 mb-10">
            Votre boutique en ligne de <span class="font-semibold">puzzles en bois 🍀</span>
        </p>

        <!-- Boutons -->
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('puzzles.index') }}"
               class="px-8 py-3 rounded-lg bg-green-600 text-white text-lg font-semibold shadow-lg hover:bg-green-700 transition transform hover:scale-105">
                Voir les puzzles
            </a>

            @if (Route::has('login'))
                @auth
                    <a href="{{ route('cart.show') }}" 
                       class="px-8 py-3 rounded-lg bg-gray-200 text-gray-700 text-lg font-semibold shadow hover:bg-gray-300 transition transform hover:scale-105">
                        Espace personnel
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="px-8 py-3 rounded-lg bg-gray-200 text-gray-700 text-lg font-semibold shadow hover:bg-gray-300 transition transform hover:scale-105">
                        Connexion
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" 
                           class="px-8 py-3 rounded-lg bg-green-600 text-white text-lg font-semibold shadow-lg hover:bg-green-700 transition transform hover:scale-105">
                            Inscription
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

</body>
</html>
