{{-- resources/views/components/app-layout.blade.php --}}
@props([
    'title' => config('app.name'),
    'meta' => null,
])

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title }}</title>

  @if($meta)
    <meta name="description" content="{{ $meta }}">
  @endif

  @vite(['resources/css/app.css','resources/js/app.js'])
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  {{-- Permet d’injecter du <head> depuis une page avec @push('head') --}}
  @stack('head')
</head>
<body class="bg-gray-50 text-gray-900">
  {{-- Header global (optionnel) --}}
  @includeIf('partials.header')

  {{-- Barre optionnelle fournie par la page via <x-slot:header> --}}
  @isset($header)
    <header class="border-b bg-white">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
        {{ $header }}
      </div>
    </header>
  @endisset

  {{-- Contenu principal --}}
  <main class="min-h-screen">
    {{ $slot }}
  </main>

  {{-- Footer global (optionnel) --}}
  @includeIf('partials.footer')

  {{-- Scripts de page via @push('scripts') --}}
  @stack('scripts')
</body>
</html>
