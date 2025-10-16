{{-- resources/views/checkout/review.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <div class="space-y-1">
      <h2 class="text-2xl font-bold tracking-tight">Récapitulatif & Paiement</h2>
      <div class="flex items-center gap-2 text-sm text-gray-500">
        <span class="inline-flex items-center gap-1">
          <span class="w-5 h-5 grid place-content-center rounded-full bg-gray-900 text-white text-[11px]">1</span> Panier
        </span>
        <span>›</span>
        <span class="inline-flex items-center gap-1">
          <span class="w-5 h-5 grid place-content-center rounded-full bg-gray-900 text-white text-[11px]">2</span> Adresse
        </span>
        <span>›</span>
        <span class="inline-flex items-center gap-1">
          <span class="w-5 h-5 grid place-content-center rounded-full bg-gray-900 text-white text-[11px]">3</span> Récap & Paiement
        </span>
      </div>
    </div>
  </x-slot>

  <div
    x-data="{ provider: 'cheque' }"
    class="max-w-7xl mx-auto p-6"
  >
    @if(session('message'))
      <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3">
        {{ session('message') }}
      </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">

      {{-- Colonne gauche : Adresse + Lignes panier --}}
      <div class="lg:col-span-2 space-y-6">

        {{-- Carte adresse --}}
        <div class="rounded-3xl border border-gray-200 bg-white shadow-sm overflow-hidden">
          <div class="flex items-center justify-between px-6 py-4 bg-gray-50">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 grid place-content-center rounded-xl bg-gray-900 text-white">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7zm0 9a2 2 0 110-4 2 2 0 010 4z"/></svg>
              </div>
              <div>
                <div class="text-sm text-gray-500">Adresse de livraison</div>
                <div class="font-semibold">{{ $adresse->prenom }} {{ $adresse->nom }}</div>
              </div>
            </div>
            <a href="{{ route('adresses.edit', [$adresse, 'from_checkout' => 1]) }}"
               class="text-sm px-3 py-1.5 rounded-xl ring-1 ring-gray-300 hover:bg-gray-50 transition">
              Modifier
            </a>
          </div>
          <div class="px-6 py-5">
            <div>{{ $adresse->ligne1 }} {{ $adresse->ligne2 }}</div>
            <div>{{ $adresse->cp }} {{ $adresse->ville }}, {{ $adresse->pays }}</div>
            @if($adresse->tel)<div class="text-gray-500 text-sm mt-1">{{ $adresse->tel }}</div>@endif
          </div>
        </div>

        {{-- Lignes du panier --}}
        <div class="rounded-3xl border border-gray-200 bg-white shadow-sm overflow-hidden">
          <div class="px-6 py-4 bg-gray-50 font-semibold">Vos articles</div>

          <ul class="divide-y divide-gray-100">
            @foreach($cart->items as $it)
              <li class="p-4 sm:p-5">
                <div class="flex items-center gap-4">
                  {{-- Image produit (si dispo) --}}
                  <div class="w-20 h-16 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                    @php $img = $it->puzzle->image_url ?? null; @endphp
                    @if($img)
                      <img src="{{ $img }}" alt="{{ $it->puzzle->nom }}" class="w-full h-full object-cover" loading="lazy">
                    @else
                      <div class="w-full h-full grid place-content-center text-xs text-gray-400">Image</div>
                    @endif
                  </div>

                  <div class="flex-1 min-w-0">
                    <div class="font-medium truncate">{{ $it->puzzle->nom }}</div>
                    <div class="text-sm text-gray-500">Qté : {{ $it->quantity }}</div>
                  </div>

                  <div class="text-right">
                    <div class="text-sm text-gray-500">{{ number_format($it->unit_price,2,',',' ') }} €</div>
                    <div class="font-semibold">
                      {{ number_format($it->quantity * $it->unit_price, 2, ',', ' ') }} €
                    </div>
                  </div>
                </div>
              </li>
            @endforeach
          </ul>

          <div class="px-6 py-4 bg-gray-50">
            <div class="flex items-center justify-between text-sm">
              <span class="text-gray-600">Livraison</span>
              <span class="font-medium">Gratuite</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Colonne droite : Résumé + Paiement --}}
      <div class="lg:col-span-1">
        <div class="lg:sticky lg:top-6 rounded-3xl border border-gray-200 bg-white shadow-sm overflow-hidden">
          <div class="px-6 py-4 bg-gray-50 font-semibold">Résumé</div>

          <div class="p-6 space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-gray-600">Sous-total</span>
              <span class="font-medium">{{ number_format($total,2,',',' ') }} €</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-600">Livraison</span>
              <span class="font-medium">0,00 €</span>
            </div>
            <div class="border-t pt-4 flex items-center justify-between">
              <span class="font-semibold">Total TTC</span>
              <span class="text-xl font-bold">{{ number_format($total,2,',',' ') }} €</span>
            </div>
          </div>

          {{-- Choix paiement + CTA --}}
          <form method="POST" action="{{ route('checkout.place', $adresse) }}" class="p-6 pt-0 space-y-4">
            @csrf
            <fieldset class="space-y-2">
              <legend class="text-sm text-gray-500">Mode de paiement</legend>

              <label class="flex items-center gap-3 p-3 rounded-2xl ring-1 ring-gray-200 hover:bg-gray-50 cursor-pointer">
                <input type="radio" name="provider" value="cheque" class="accent-gray-900"
                       x-model="provider" checked>
                <div class="flex items-center gap-2">
                  <svg class="w-5 h-5 text-gray-800" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="M3 6h18v12H3zM7 10h10M7 14h6"/>
                  </svg>
                  <span>Chèque</span>
                </div>
              </label>

              <label class="flex items-center gap-3 p-3 rounded-2xl ring-1 ring-gray-200 hover:bg-gray-50 cursor-pointer">
                <input type="radio" name="provider" value="paypal" class="accent-gray-900"
                       x-model="provider">
                <div class="flex items-center gap-2">
                  <svg class="w-5 h-5 text-gray-800" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 118 8h-3l-1 3h-2l1-3H6l1-4h4a4 4 0 000-8H7"/>
                  </svg>
                  <span>PayPal</span>
                </div>
              </label>
            </fieldset>

            <x-primary-button class="w-full justify-center px-5 py-2.5 rounded-2xl">
              <span x-show="provider === 'cheque'">Confirmer et afficher les instructions</span>
              <span x-show="provider === 'paypal'">Payer avec PayPal</span>
            </x-primary-button>

            <p class="text-xs text-gray-500 text-center">
              En confirmant, vous acceptez nos CGV. Paiement sécurisé.
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
