{{-- resources/views/puzzles/show.blade.php --}}
@php
    use Illuminate\Support\Str;
    // Normalise la galerie: tableau d'URLs absolues (storage ou http)
    $gallery = collect($puzzle->gallery ?? [$puzzle->image])
        ->filter()
        ->map(fn($p) => Str::startsWith($p, ['http://','https://']) ? $p : asset('storage/'.$p))
        ->values()
        ->all();
@endphp

<x-app-layout :title="$puzzle->nom"
              :meta="Str::limit(strip_tags($puzzle->description ?? ''), 160)">

  {{-- Fil d’Ariane --}}
  <x-slot:header>
    <nav class="text-sm text-gray-500">
      <a href="{{ route('dashboard') }}" class="hover:underline">{{ __('Dashboard') }}</a>
      <span class="mx-2">/</span>
      <a href="{{ route('puzzles.index') }}" class="hover:underline">{{ __('Browse puzzles') }}</a>
      <span class="mx-2">/</span>
      <span class="text-gray-900">{{ $puzzle->nom }}</span>
    </nav>
  </x-slot:header>

  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8"
       x-data="{
          images: @js($gallery),
          current: 0,
          qty: 1,
          set(i){ this.current = i },
          inc(){ this.qty++ },
          dec(){ if(this.qty>1) this.qty-- },
          tab: 'info'
       }">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
      {{-- Colonne images --}}
      <div class="lg:col-span-7 grid grid-cols-1 md:grid-cols-[96px_1fr] gap-6 items-start">
        {{-- Vignettes verticales --}}
        <div class="order-2 md:order-1 flex md:flex-col gap-3 md:max-h-[520px] md:overflow-auto">
          <template x-for="(img, i) in images" :key="i">
            <button @click="set(i)"
                    class="aspect-square w-24 rounded-xl border hover:border-gray-900 transition"
                    :class="i===current ? 'ring-2 ring-gray-900' : 'border-gray-200'">
              <img :src="img" alt="Aperçu"
                   class="h-full w-full object-cover rounded-xl" />
            </button>
          </template>
        </div>

        {{-- Image principale --}}
        <div class="order-1 md:order-2 rounded-2xl bg-white p-4 border border-gray-200">
          <div class="aspect-square w-full overflow-hidden rounded-xl">
          <img :src="images[current]" alt="{{ $puzzle->nom }}" class="w-full h-full object-cover">
          </div>
        </div>
      </div>

      {{-- Panneau droit : infos produit --}}
      <div class="lg:col-span-5">
        <h1 class="text-3xl font-semibold tracking-tight uppercase">{{ $puzzle->nom }}</h1>

        <div class="mt-2 flex items-center gap-4">
          <div class="flex items-center text-yellow-500">
            @php $note = number_format($puzzle->note ?? 4.9, 1); @endphp
            @for ($i=1; $i<=5; $i++)
              <svg class="h-5 w-5 {{ $i <= floor($note) ? 'fill-yellow-500' : 'fill-gray-200' }}" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.49 6.91l6.561-.954L10 0l2.949 5.956 6.561.954-4.755 4.635 1.123 6.545z"/></svg>
            @endfor
          </div>
          <span class="text-sm text-gray-500">({{ $puzzle->reviews_count ?? 1 }} {{ Str::plural('commentaire', $puzzle->reviews_count ?? 1) }})</span>
        </div>

        <div class="mt-4">
          <div class="text-2xl font-semibold">
            €{{ number_format($puzzle->prix, 0, ',', ' ') }}
            @if(!empty($puzzle->prix_barre))
              <span class="ml-2 text-gray-400 line-through">€{{ number_format($puzzle->prix_barre, 0, ',', ' ') }}</span>
            @endif
          </div>
        </div>

        <p class="mt-4 text-gray-600 leading-relaxed">
          {{ Str::limit($puzzle->description, 220) }}
        </p>

        {{-- Quantité + bouton panier --}}
        <form action="{{ route('cart.add', $puzzle->id) }}" method="POST" class="mt-6 flex items-center gap-4">
          @csrf
          <div class="flex items-center rounded-xl border border-gray-200">
            <button type="button" @click="dec" class="px-3 py-2">−</button>
            <input type="number" name="quantity" x-model="qty" min="1"
                   class="w-14 text-center border-x border-gray-200 py-2" />
            <button type="button" @click="inc" class="px-3 py-2">+</button>
          </div>

          <button type="submit"
                  class="inline-flex items-center justify-center rounded-xl bg-black px-6 py-3 text-white hover:bg-gray-900 transition">
            {{ __('Add to cart') }}
          </button>
        </form>

        {{-- Onglets --}}
        <div class="mt-10">
          <div class="flex gap-4 border-b">
            <button @click="tab='info'"
                    :class="tab==='info' ? 'border-black text-black' : 'border-transparent text-gray-500'"
                    class="px-4 py-3 border-b-2 font-medium">{{ __('More info about the product') }}</button>
            <button @click="tab='comments'"
                    :class="tab==='comments' ? 'border-black text-black' : 'border-transparent text-gray-500'"
                    class="px-4 py-3 border-b-2 font-medium">{{ __('Comments') }} ({{ $puzzle->reviews_count ?? 1 }})</button>
          </div>

          <div class="pt-6">
            <div x-show="tab==='info'" class="text-gray-600 space-y-2">
              <p><strong>Matériau :</strong> Bois naturel découpé avec précision</p>
              <p><strong>Nombre de pièces :</strong> {{ $puzzle->pieces ?? '120–150' }}</p>
              <p><strong>Dimensions assemblé :</strong> {{ $puzzle->dimensions ?? '20 × 15 × 10 cm' }}</p>
              <p><strong>Niveau :</strong> Intermédiaire</p>
              <p><strong>Outils :</strong> Aucun outil nécessaire</p>
            </div>

            <div x-show="tab==='comments'">
              <p class="text-gray-500">{{ __('No reviews yet. Be the first!') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- DESCRIPTION pleine largeur --}}
    <section class="mt-12">
      <h2 class="text-lg font-semibold mb-4">{{ __('Description') }}</h2>
      <div class="prose max-w-none text-gray-700">
        {!! nl2br(e($puzzle->description_longue ?? $puzzle->description)) !!}
        <ul class="mt-4 list-disc pl-5">
          <li>Bois premium sélectionné</li>
          <li>Découpe laser précise</li>
          <li>Montage facile et rapide</li>
        </ul>
      </div>
    </section>

    {{-- Produits similaires --}}
    @if(!empty($related) && count($related))
      <section class="mt-12">
        <h2 class="text-lg font-semibold mb-6">{{ __('Produits Similaires') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          @foreach($related as $r)
            <a href="{{ route('puzzles.show', $r->id) }}" class="group block">
              <div class="rounded-2xl border border-gray-200 bg-white p-4 hover:shadow-sm transition">
                <div class="aspect-[4/3] w-full overflow-hidden rounded-xl bg-gray-50">
                  <img src="{{ $r->image_url ?? (Str::startsWith($r->image,'http') ? $r->image : asset('storage/'.$r->image)) }}"
                       alt="{{ $r->nom }}"
                       class="h-full w-full object-cover group-hover:scale-[1.03] transition" />
                </div>
                <div class="mt-3">
                  <div class="font-medium line-clamp-1">{{ $r->nom }}</div>
                  <div class="text-sm text-gray-600">€{{ number_format($r->prix, 0, ',', ' ') }}</div>
                </div>
              </div>
            </a>
          @endforeach
        </div>
      </section>
    @endif
  </div>

  @push('head')
    {{-- <meta property="og:image" content="{{ $gallery[0] ?? '' }}"> --}}
  @endpush
</x-app-layout>
