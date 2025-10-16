{{-- resources/views/checkout/paypal.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold tracking-tight">Paiement PayPal</h2>
        <p class="text-sm text-gray-500">Redirection sécurisée vers PayPal dans quelques secondes…</p>
      </div>
      <a href="{{ route('checkout.review', $commande->adresse) }}"
         class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-xl ring-1 ring-gray-300 hover:bg-gray-50 transition">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
        </svg>
        Retour
      </a>
    </div>
  </x-slot>

  <div
    x-data="{
      url: 'https://paypal.me/EvanEtheve',
      seconds: 5,
      opened: false,
      start() {
        const total = this.seconds;
        const t = setInterval(() => {
          if (this.seconds > 0) this.seconds--;
          if (this.seconds === 0 && !this.opened) {
            this.opened = true;
            window.open(this.url, '_blank'); // auto-open
            clearInterval(t);
          }
        }, 1000);
      }
    }"
    x-init="start()"
    class="max-w-5xl mx-auto p-6"
  >
    {{-- Bandeau bleu PayPal --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#003087] via-[#0047A5] to-[#0070BA] text-white shadow-lg">
      <div class="absolute -top-16 -left-16 w-56 h-56 bg-white/10 rounded-full blur-2xl"></div>
      <div class="absolute -bottom-12 -right-12 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>

      <div class="p-6 md:p-8">
        <div class="flex items-center gap-3">
          <div class="w-11 h-11 grid place-content-center rounded-2xl bg-white/15 backdrop-blur">
            {{-- icône paiement générique --}}
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M3 6h18v12H3zM7 10h10M7 14h6"/>
            </svg>
          </div>
          <div>
            <div class="text-sm/5 text-white/80">Redirection vers</div>
            <div class="text-xl font-bold tracking-wide">PayPal</div>
          </div>
        </div>

        <div class="mt-6 grid md:grid-cols-3 gap-6">
          <div class="md:col-span-2">
            <div class="text-sm text-white/80">Commande #{{ $commande->id }}</div>
            <div class="mt-1 text-3xl font-extrabold">
              {{ number_format($commande->total_ttc, 2, ',', ' ') }} €
            </div>

            {{-- Progression + compte à rebours --}}
            <div class="mt-5">
              <div class="h-2 rounded-full bg-white/20 overflow-hidden">
                <div class="h-full bg-white/90 transition-all"
                     :style="`width: ${((5 - seconds) / 5) * 100}%`"></div>
              </div>
              <div class="mt-2 text-sm text-white/80">
                Redirection automatique dans <span class="font-semibold" x-text="seconds"></span>s…
                <span x-show="opened" class="ml-2 inline-flex items-center gap-1">
                  <svg class="w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" stroke-width="1.5" class="opacity-30"></circle>
                    <path d="M12 2a10 10 0 0 1 10 10" stroke-width="1.8"></path>
                  </svg>
                  onglet PayPal ouvert
                </span>
              </div>
            </div>

            {{-- Boutons d’action --}}
            <div class="mt-6 flex flex-wrap items-center gap-3">
              <a :href="url" target="_blank" rel="noopener"
                 class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl
                        bg-white text-[#003087] hover:bg-blue-50 font-semibold transition shadow-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.24 2.01c4.36 0 6.8 2.56 6.08 6.13-.7 3.4-3.45 5.2-6.79 5.2H9.2l-.6 3.58H5.7L7.7 4.1h3.74c.3 0 .6 0 .9-.01z"/></svg>
                Payer sur PayPal
              </a>

              <a href="{{ route('checkout.success', $commande) }}"
                 class="inline-flex items-center justify-center px-5 py-2.5 rounded-2xl
                        bg-white/10 hover:bg-white/15 text-white font-semibold ring-1 ring-white/20 transition">
                J’ai payé, continuer
              </a>
            </div>

            <p class="mt-3 text-xs text-white/75">
              Si la redirection ne s’ouvre pas (bloqueur de popup), cliquez sur “Payer sur PayPal”.
            </p>
          </div>

          {{-- Encadré résumé adresse --}}
          <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/15">
            <div class="text-sm text-white/80 mb-2">Adresse de facturation</div>
            <div class="font-medium">{{ $commande->adresse->prenom }} {{ $commande->adresse->nom }}</div>
            <div class="text-white/90">{{ $commande->adresse->ligne1 }} {{ $commande->adresse->ligne2 }}</div>
            <div class="text-white/90">{{ $commande->adresse->cp }} {{ $commande->adresse->ville }}, {{ $commande->adresse->pays }}</div>
            @if($commande->adresse->tel)
              <div class="text-white/80 mt-1">{{ $commande->adresse->tel }}</div>
            @endif
            <div class="mt-4 text-xs text-white/70">
              <span class="inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 17a2 2 0 100-4 2 2 0 000 4z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M19.4 15A8 8 0 104.6 15L12 21l7.4-6z"/>
                </svg>
                Paiement sécurisé via PayPal
              </span>
            </div>
          </div>
        </div>

      </div>
    </div>

    {{-- Carte blanche en dessous (tips, support) --}}
    <div class="mt-6 grid md:grid-cols-3 gap-4">
      <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-4 text-blue-900">
        <div class="font-semibold mb-1">Conseil</div>
        Utilise ton compte PayPal pour un paiement rapide et protégé. Aucun frais côté acheteur.
      </div>
      <div class="rounded-2xl border border-gray-200 bg-white p-4">
        <div class="font-semibold mb-1">Montant</div>
        {{ number_format($commande->total_ttc, 2, ',', ' ') }} €
      </div>
      <div class="rounded-2xl border border-gray-200 bg-white p-4">
        <div class="font-semibold mb-1">Besoin d’aide ?</div>
        Contacte-nous et indique <span class="font-mono">#{{ $commande->id }}</span>.
      </div>
    </div>
  </div>
</x-app-layout>
