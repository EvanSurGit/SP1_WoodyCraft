{{-- resources/views/adresses/edit.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold tracking-tight">Modifier l’adresse</h2>
        <p class="text-sm text-gray-500">Mettez à jour vos infos de livraison. L’aperçu se met à jour en direct ➜</p>
      </div>
      <a href="{{ url()->previous() }}"
         class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-xl ring-1 ring-gray-300 hover:bg-gray-50 transition">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
        Retour
      </a>
    </div>
  </x-slot>

  <div
    x-data="{
      nom:      @js(old('nom', $adresse->nom)),
      prenom:   @js(old('prenom', $adresse->prenom)),
      ligne1:   @js(old('ligne1', $adresse->ligne1)),
      ligne2:   @js(old('ligne2', $adresse->ligne2)),
      cp:       @js(old('cp', $adresse->cp)),
      ville:    @js(old('ville', $adresse->ville)),
      pays:     @js(old('pays', $adresse->pays ?? 'France')),
      tel:      @js(old('tel', $adresse->tel)),
    }"
    class="max-w-6xl mx-auto p-6"
  >
    <div class="grid lg:grid-cols-3 gap-6">
      {{-- Carte preview dynamique --}}
      <div class="lg:col-span-1">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-900 to-gray-700 text-white shadow-lg">
          <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full bg-white/10 blur-2xl"></div>
          <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>

          <div class="p-6">
            <div class="flex items-center gap-3 pb-5 border-b border-white/10">
              <div class="grid place-content-center w-10 h-10 rounded-xl bg-white/10 backdrop-blur">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                        d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7zm0 9a2 2 0 110-4 2 2 0 010 4z"/>
                </svg>
              </div>
              <div>
                <div class="text-sm text-white/70">Adresse de livraison</div>
                <div class="font-semibold">Aperçu en direct</div>
              </div>
            </div>

            <div class="mt-5 space-y-1">
              <div class="text-lg font-semibold" x-text="`${prenom || 'Prénom'} ${nom || 'NOM'}`"></div>
              <div x-text="ligne1 || 'Adresse (ligne 1)'"></div>
              <div class="text-white/80" x-show="ligne2" x-text="ligne2"></div>
              <div x-text="`${cp || '00000'} ${ville || 'Ville'}, ${pays || 'France'}`"></div>
              <div class="text-white/70" x-show="tel" x-text="tel"></div>
            </div>

            <div class="mt-6 text-xs text-white/60">
              Astuce : les champs du formulaire à droite mettent à jour cette carte automatiquement ✨
            </div>
          </div>
        </div>
      </div>

      {{-- Formulaire --}}
      <div class="lg:col-span-2">
        <form method="POST" action="{{ route('adresses.update', $adresse) }}"
              class="rounded-3xl border border-gray-200 bg-white shadow-sm overflow-hidden">
          @csrf @method('PUT')
          <input type="hidden" name="from_checkout" value="{{ request()->boolean('from_checkout') ? '1' : '0' }}"/>

          <div class="p-6 md:p-8 space-y-6">

            <div class="grid sm:grid-cols-2 gap-4">
              <div>
                <x-input-label for="prenom" value="Prénom" />
                <x-text-input id="prenom" name="prenom" class="mt-1 block w-full"
                              x-model="prenom" value="{{ old('prenom', $adresse->prenom) }}" />
                <x-input-error :messages="$errors->get('prenom')" class="mt-1" />
              </div>
              <div>
                <x-input-label for="nom" value="Nom" />
                <x-text-input id="nom" name="nom" class="mt-1 block w-full"
                              x-model="nom" value="{{ old('nom', $adresse->nom) }}" />
                <x-input-error :messages="$errors->get('nom')" class="mt-1" />
              </div>
            </div>

            <div>
              <x-input-label for="ligne1" value="Adresse (ligne 1)" />
              <x-text-input id="ligne1" name="ligne1" class="mt-1 block w-full"
                            x-model="ligne1" value="{{ old('ligne1', $adresse->ligne1) }}" />
              <x-input-error :messages="$errors->get('ligne1')" class="mt-1" />
            </div>

            <div>
              <x-input-label for="ligne2" value="Complément d’adresse (optionnel)" />
              <x-text-input id="ligne2" name="ligne2" class="mt-1 block w-full"
                            x-model="ligne2" value="{{ old('ligne2', $adresse->ligne2) }}" />
              <x-input-error :messages="$errors->get('ligne2')" class="mt-1" />
            </div>

            <div class="grid sm:grid-cols-3 gap-4">
              <div>
                <x-input-label for="cp" value="Code postal" />
                <x-text-input id="cp" name="cp" class="mt-1 block w-full"
                              x-model="cp" value="{{ old('cp', $adresse->cp) }}" />
                <x-input-error :messages="$errors->get('cp')" class="mt-1" />
              </div>
              <div>
                <x-input-label for="ville" value="Ville" />
                <x-text-input id="ville" name="ville" class="mt-1 block w-full"
                              x-model="ville" value="{{ old('ville', $adresse->ville) }}" />
                <x-input-error :messages="$errors->get('ville')" class="mt-1" />
              </div>
              <div>
                <x-input-label for="pays" value="Pays" />
                <x-text-input id="pays" name="pays" class="mt-1 block w-full"
                              x-model="pays" value="{{ old('pays', $adresse->pays ?? 'France') }}" />
                <x-input-error :messages="$errors->get('pays')" class="mt-1" />
              </div>
            </div>

            <div>
              <x-input-label for="tel" value="Téléphone (optionnel)" />
              <x-text-input id="tel" name="tel" class="mt-1 block w-full"
                            x-model="tel" value="{{ old('tel', $adresse->tel) }}" />
              <x-input-error :messages="$errors->get('tel')" class="mt-1" />
            </div>
          </div>

          <div class="bg-gray-50 p-4 md:p-6 flex items-center justify-between">
            <div class="text-sm text-gray-500">
              <span class="inline-flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Vos changements seront appliqués à la commande en cours.
              </span>
            </div>
            <x-primary-button class="px-5 py-2.5 rounded-2xl">
              Enregistrer
            </x-primary-button>
          </div>
        </form>

        {{-- Lien retour checkout si on vient du récap --}}
        @if(request()->boolean('from_checkout'))
          <div class="mt-4">
            <a href="{{ route('checkout.review', $adresse) }}"
               class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
              Retour au récapitulatif
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</x-app-layout>
