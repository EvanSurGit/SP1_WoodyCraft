@php use Illuminate\Support\Str 

@endphp

<x-app-layout>
  <x-slot name="header">
    <h2 class="text-2xl font-bold">Liste des puzzles</h2>
  </x-slot>

  <div class="max-w-7xl mx-auto p-6">
    @if(session('message'))
      <div class="mb-4 rounded-xl bg-green-50 text-green-800 px-4 py-3">
        {{ session('message') }}
      </div>
    @endif

    {{-- Actions admin éventuelles --}}
    @if(auth()->user()?->is_admin)
      <div class="mb-6 flex items-center gap-2">
        <a href="{{ route('puzzles.create') }}" class="text-sm px-3 py-2 rounded-xl ring-1 ring-gray-300 hover:bg-gray-50">+ Nouveau puzzle</a>
        <a href="{{ route('cart.show') }}" class="text-sm px-3 py-2 rounded-xl bg-gray-900 text-white hover:bg-gray-800">Voir le panier</a>
      </div>
    @else
      <div class="mb-6">
        <a href="{{ route('cart.show') }}" class="text-sm px-3 py-2 rounded-xl bg-gray-900 text-white hover:bg-gray-800">Voir le panier</a>
      </div>
    @endif

    {{-- OFFRE DU MOMENT — fond distinct + cadre gris-marron, visuel sans carte blanche --}}
    @if(!empty($offer))
      @php
          $img = $offer->image ?? null;
          $offerImg = $img
              ? (\Illuminate\Support\Str::startsWith($img, ['http','/storage']) ? $img : asset('storage/'.$img))
              : null;
      @endphp

      <section class="mb-12">
        <!-- Fond beige/gris doux + cadre gris-marron -->
        <div class="relative overflow-hidden rounded-[28px] bg-[#F5F3EF] ring-1 ring-[#C8B7A6]">
          <div class="grid grid-cols-1 md:grid-cols-12 items-center min-h-[380px]">

            {{-- Colonne texte --}}
            <div class="md:col-span-5 px-8 md:px-10 py-10">
              <p class="text-[11px] tracking-[0.35em] uppercase text-stone-500">
                {{ __('Puzzle du moment') }}
              </p>
              <h2 class="mt-3 text-[26px] md:text-[28px] font-semibold tracking-tight text-gray-900">
                {{ $offer->nom }}
              </h2>
              <p class="mt-3 text-[13px] leading-6 text-stone-600/90 max-w-md">
                {{ \Illuminate\Support\Str::limit($offer->description, 140) }}
              </p>

              <div class="mt-6 flex items-center gap-6">
                <a href="{{ route('puzzles.show', $offer) }}"
                  class="text-[12px] tracking-widest uppercase text-gray-900 hover:text-black underline decoration-transparent hover:decoration-gray-900 underline-offset-4 transition">
                  {{ __('En savoir +') }}
                </a>
                <span class="text-sm text-stone-400">|</span>
                <form action="{{ route('cart.add', $offer) }}" method="POST" class="inline-flex">
                  @csrf
                  <input type="hidden" name="quantity" value="1">
                  <button class="text-[12px] tracking-widest uppercase hover:text-gray-900 transition">
                    {{ __('Ajouter au panier') }}
                  </button>
                </form>
              </div>
            </div>

            {{-- Colonne visuel (sans carte blanche) --}}
            <div class="md:col-span-7 relative pr-4 md:pr-8 py-8">
              <div class="relative mx-auto max-w-2xl w-full aspect-[4/3]">
                @if($offerImg)
                <img src="{{ $offerImg }}" alt="{{ $offer->nom }}"
                    class="w-full h-full object-contain rounded-2xl drop-shadow-[0_30px_50px_rgba(0,0,0,0.15)] transition-transform duration-500" />
                @else
                  <div class="w-full h-full grid place-content-center text-stone-400 text-sm">Image indisponible</div>
                @endif

                {{-- léger halo radial derrière le produit (pas un carré) --}}
                <div class="pointer-events-none absolute inset-0 -z-10
                            bg-[radial-gradient(60%_60%_at_70%_40%,rgba(255,255,255,0.55),transparent_65%)]"></div>
              </div>

              {{-- halos décoratifs très doux (gris-marron) --}}
              <div class="pointer-events-none absolute -right-24 -top-16 h-64 w-64 rounded-full bg-[#E7DBCF]/50 blur-3xl"></div>
              <div class="pointer-events-none absolute right-10 -bottom-24 h-72 w-72 rounded-full bg-[#D6C7B9]/40 blur-3xl"></div>
            </div>
          </div>
        </div>
      </section>
    @endif



    {{-- Filtres catégories (style maquette) --}}
    @php $active = request('category'); @endphp
    @isset($categories)
      <div class="mb-8 flex items-center justify-between">
        <div class="flex flex-wrap items-center gap-x-5 gap-y-2">
          <a href="{{ route('puzzles.index') }}"
            class="text-[11px] uppercase tracking-[0.25em]
                    {{ $active ? 'text-gray-400 hover:text-gray-900' : 'text-gray-900' }}">
            {{ __('Tous') }}
          </a>
          @foreach($categories as $cat)
            @php $catKey = $cat->slug ?? $cat->id; @endphp
            <a href="{{ route('puzzles.index', ['category' => $catKey]) }}"
              class="text-[11px] uppercase tracking-[0.25em]
                      {{ (string)$active === (string)$catKey ? 'text-gray-900' : 'text-gray-400 hover:text-gray-900' }}">
              {{ $cat->nom }}
            </a>
          @endforeach
        </div>
      </div>
    @endisset


    @if($puzzles->isEmpty())
      <p class="text-gray-600">Aucun puzzle pour le moment.</p>
    @else
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($puzzles as $puzzle)
          <div class="group rounded-3xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition overflow-hidden">
            {{-- Image --}}
            <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
              @php
                $img = $puzzle->image;
                if ($img) {
                  if (\Illuminate\Support\Str::startsWith($img, ['http://','https://','/storage/','storage/'])) {
                    $imgUrl = \Illuminate\Support\Str::startsWith($img, '/') ? $img : '/'.$img;
                  } else {
                    $imgUrl = asset('storage/'.$img);
                  }
                }
              @endphp

              @if(!empty($imgUrl))
                <img src="{{ $imgUrl }}" class="w-full h-full object-cover" loading="lazy" alt="{{ $puzzle->nom }}">
              @else
                <div class="w-full h-full grid place-content-center text-gray-400 text-sm">
                  Image indisponible
                </div>
              @endif
            </div>

            <div class="p-5 space-y-3">
              <div class="flex items-start justify-between gap-3">
                <h3 class="font-semibold text-lg leading-tight">{{ $puzzle->nom }}</h3>

                @if($puzzle->categorie?->nom)
                  @php $catKey = $puzzle->categorie->slug ?? $puzzle->categorie_id; @endphp
                  <a href="{{ route('puzzles.index', ['category' => $catKey]) }}"
                     class="shrink-0 text-xs px-2 py-1 rounded-full bg-gray-100 ring-1 ring-gray-200 hover:bg-gray-50">
                    {{ $puzzle->categorie->nom }}
                  </a>
                @endif
              </div>

              <div class="text-sm text-gray-500 line-clamp-2">
                {{ Str::limit($puzzle->description, 120) }}
              </div>

              <div class="flex items-center justify-between">
                <div class="text-xl font-bold">
                  {{ number_format($puzzle->prix, 2, ',', ' ') }} €
                </div>
                <div class="text-xs text-gray-500">
                  #{{ $puzzle->id }}
                </div>
              </div>

              <div class="flex items-center gap-3 pt-2">
                <form method="POST" action="{{ route('cart.add', $puzzle) }}" class="flex items-center gap-3">
                  @csrf
                  <x-ui.qty name="quantity" :value="1" />
                  <x-ui.btn type="submit">Ajouter au panier</x-ui.btn>
                </form>
              </div>

              {{-- Liens admin discrets --}}
              @if(auth()->user()?->is_admin)
                <div class="pt-3 flex items-center gap-2 text-sm">
                  <a href="{{ route('puzzles.show', $puzzle) }}" class="px-3 py-1 rounded-xl ring-1 ring-gray-200 hover:bg-gray-50">Afficher</a>
                  <a href="{{ route('puzzles.edit', $puzzle) }}" class="px-3 py-1 rounded-xl ring-1 ring-gray-200 hover:bg-gray-50">Éditer</a>
                  <form method="POST" action="{{ route('puzzles.destroy', $puzzle) }}" onsubmit="return confirm('Supprimer ?')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1 rounded-xl bg-red-50 text-red-700 hover:bg-red-100">Supprimer</button>
                  </form>
                </div>
              @else
                <div class="pt-3">
                  <a href="{{ route('puzzles.show', $puzzle) }}" class="px-3 py-1 rounded-xl ring-1 ring-gray-200 hover:bg-gray-50 text-sm">Afficher</a>
                </div>
              @endif

            </div>
          </div>
        @endforeach
      </div>

      <div class="mt-8">
        {{ $puzzles->withQueryString()->links() }}
      </div>
    @endif
  </div>
</x-app-layout>
