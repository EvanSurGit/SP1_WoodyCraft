{{-- resources/views/checkout/cheque.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <div class="space-y-1">
      <h2 class="text-2xl font-bold tracking-tight">Paiement par chèque</h2>
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
        <span>›</span>
        <span class="inline-flex items-center gap-1">
          <span class="w-5 h-5 grid place-content-center rounded-full bg-emerald-600 text-white text-[11px]">4</span> Chèque
        </span>
      </div>
    </div>
  </x-slot>

  <div
    x-data="{
      ordre: 'WoodyCraft',
      dest: 'WoodyCraft – Service Paiement',
      addr1: '12 rue des Tipis',
      addr2: '42000 Saint-Étienne',
      pays: 'France',
      montant: '{{ number_format($commande->total_ttc, 2, ',', ' ') }} €',
      toast: null,
      copy(text) {
        navigator.clipboard.writeText(text).then(() => {
          this.toast = 'Copié dans le presse-papiers';
          setTimeout(()=> this.toast=null, 1600);
        });
      },
      printPage(){ window.print(); }
    }"
    class="max-w-7xl mx-auto p-6"
  >
    {{-- Toast "Copié" --}}
    <template x-if="toast">
      <div class="fixed left-1/2 -translate-x-1/2 top-4 z-50 px-4 py-2 rounded-xl bg-gray-900 text-white shadow-lg">
        <span x-text="toast"></span>
      </div>
    </template>

    <div class="grid lg:grid-cols-3 gap-6">
      {{-- Colonne gauche : carte + instructions --}}
      <div class="lg:col-span-2 space-y-6">
        {{-- Carte héro dégradée --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-600 via-emerald-700 to-slate-900 text-white shadow-lg">
          <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full bg-white/10 blur-2xl"></div>
          <div class="absolute -bottom-12 -left-12 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>

          <div class="p-6 md:p-8">
            <div class="flex items-center justify-between gap-4">
              <div class="flex items-center gap-3">
                <div class="w-11 h-11 grid place-content-center rounded-2xl bg-white/15 backdrop-blur">
                  <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M3 6h18v12H3zM7 10h10M7 14h6"/>
                  </svg>
                </div>
                <div>
                  <div class="text-sm text-white/80">Commande #{{ $commande->id }}</div>
                  <div class="text-xl font-bold">Paiement par chèque</div>
                </div>
              </div>
              <div class="text-right">
                <div class="text-sm text-white/80">Montant à régler</div>
                <div class="text-3xl font-extrabold">{{ number_format($commande->total_ttc,2,',',' ') }} €</div>
              </div>
            </div>

            {{-- Chèque stylisé (aperçu) --}}
            <div class="mt-6 rounded-2xl bg-white text-gray-900 ring-1 ring-white/10 shadow-md overflow-hidden">
              <div class="p-5 grid md:grid-cols-3 gap-4">
                <div class="md:col-span-2 space-y-2">
                  <div class="text-xs text-gray-500">À l’ordre de</div>
                  <div class="flex items-center gap-2">
                    <div class="font-semibold" x-text="ordre"></div>
                    <button type="button" @click="copy(ordre)"
                            class="text-xs px-2 py-1 rounded-lg bg-gray-100 hover:bg-gray-200">Copier</button>
                  </div>

                  <div class="pt-3 text-xs text-gray-500">Envoyer à</div>
                  <div class="space-y-0.5">
                    <div class="font-medium" x-text="dest"></div>
                    <div x-text="addr1"></div>
                    <div x-text="addr2"></div>
                    <div x-text="pays"></div>
                  </div>
                </div>
                <div class="flex flex-col justify-between">
                  <div>
                    <div class="text-xs text-gray-500">Montant</div>
                    <div class="text-2xl font-bold" x-text="montant"></div>
                  </div>
                  <div class="mt-4">
                    <div class="text-xs text-gray-500">Référence</div>
                    <div class="font-medium select-all">#{{ $commande->id }}</div>
                  </div>
                </div>
              </div>
              <div class="px-5 py-3 bg-gray-50 text-xs text-gray-600">
                Indique la référence <span class="font-semibold">#{{ $commande->id }}</span> au dos du chèque.
              </div>
            </div>
          </div>
        </div>

        {{-- Instructions & actions --}}
        <div class="rounded-3xl border border-gray-200 bg-white shadow-sm">
          <div class="px-6 py-4 bg-gray-50 font-semibold">Instructions de paiement</div>
          <div class="p-6 space-y-4">
            <ol class="list-decimal pl-5 space-y-2 text-gray-700">
              <li>Établis un chèque à l’ordre de <strong x-text="ordre"></strong>.</li>
              <li>Inscris la référence <strong>#{{ $commande->id }}</strong> au dos du chèque.</li>
              <li>Envoie-le à l’adresse :
                <div class="mt-1 pl-5 text-gray-600">
                  <div class="font-medium" x-text="dest"></div>
                  <div x-text="addr1"></div>
                  <div x-text="addr2"></div>
                  <div x-text="pays"></div>
                </div>
              </li>
            </ol>

            <div class="flex flex-wrap items-center gap-3 pt-2">
              <a href="{{ route('checkout.cheque.pdf', $commande) }}"
                 class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gray-900 text-white hover:bg-gray-800 transition shadow-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16"/>
                </svg>
                Télécharger la facture (PDF)
              </a>

              <button type="button" @click="printPage()"
                 class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl ring-1 ring-gray-300 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 13H4a2 2 0 00-2 2v3h4m10 0h4v-3a2 2 0 00-2-2h-2M6 22h12v-6H6v6z"/>
                </svg>
                Imprimer les instructions
              </button>

              <a href="{{ route('puzzles.index') }}"
                 class="ms-auto text-sm text-gray-600 hover:text-gray-900 underline underline-offset-4">
                Retour à la boutique
              </a>
            </div>

            <p class="text-xs text-gray-500">
              Ta commande passera au statut <em>payée</em> dès réception et validation du chèque.
            </p>
          </div>
        </div>
      </div>

      {{-- Colonne droite : résumé sticky --}}
      <div class="lg:col-span-1">
        <div class="lg:sticky lg:top-6 space-y-6">
          <div class="rounded-3xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 font-semibold">Résumé commande</div>
            <div class="p-6 space-y-4">
              <div class="flex items-center justify-between">
                <span class="text-gray-600">Total TTC</span>
                <span class="text-xl font-bold">{{ number_format($commande->total_ttc,2,',',' ') }} €</span>
              </div>
              <div class="text-sm text-gray-500">
                Adresse de livraison :
                <div class="mt-1 text-gray-700">
                  <div class="font-medium">
                    {{ $commande->adresse->prenom }} {{ $commande->adresse->nom }}
                  </div>
                  <div>{{ $commande->adresse->ligne1 }} {{ $commande->adresse->ligne2 }}</div>
                  <div>{{ $commande->adresse->cp }} {{ $commande->adresse->ville }}, {{ $commande->adresse->pays }}</div>
                  @if($commande->adresse->tel)
                    <div class="text-gray-500 mt-1">{{ $commande->adresse->tel }}</div>
                  @endif
                </div>
              </div>
            </div>
          </div>

          {{-- Timeline statut (visuel) --}}
          <div class="rounded-3xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-4 bg-gray-50 font-semibold">Statut</div>
            <ol class="p-6 space-y-4 text-sm">
              <li class="flex items-start gap-3">
                <span class="mt-0.5 w-5 h-5 grid place-content-center rounded-full bg-emerald-600 text-white">1</span>
                <div><div class="font-medium">Commande créée</div><div class="text-gray-500">Référence #{{ $commande->id }}</div></div>
              </li>
              <li class="flex items-start gap-3">
                <span class="mt-0.5 w-5 h-5 grid place-content-center rounded-full bg-amber-500 text-white">2</span>
                <div><div class="font-medium">En attente du chèque</div><div class="text-gray-500">Nous validerons dès réception.</div></div>
              </li>
              <li class="flex items-start gap-3 opacity-70">
                <span class="mt-0.5 w-5 h-5 grid place-content-center rounded-full bg-gray-300 text-white">3</span>
                <div><div class="font-medium">Expédition</div><div class="text-gray-500">Après validation du paiement.</div></div>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
