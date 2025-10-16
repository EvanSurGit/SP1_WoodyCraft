<x-app-layout>
  <x-slot name="header"><h2 class="text-2xl font-bold">Adresse de livraison</h2></x-slot>

  <div class="max-w-3xl mx-auto p-6">
    <form method="POST" action="{{ route('checkout.address.store') }}" class="space-y-4">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div><x-input-label for="nom" value="Nom"/><x-text-input name="nom" id="nom" class="w-full" value="{{ old('nom', $adresse->nom ?? '') }}"/></div>
        <div><x-input-label for="prenom" value="Prénom"/><x-text-input name="prenom" id="prenom" class="w-full" value="{{ old('prenom', $adresse->prenom ?? '') }}"/></div>
      </div>
      <div><x-input-label for="ligne1" value="Adresse"/><x-text-input name="ligne1" id="ligne1" class="w-full" value="{{ old('ligne1', $adresse->ligne1 ?? '') }}"/></div>
      <div><x-input-label for="ligne2" value="Complément"/><x-text-input name="ligne2" id="ligne2" class="w-full" value="{{ old('ligne2', $adresse->ligne2 ?? '') }}"/></div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div><x-input-label for="cp" value="Code postal"/><x-text-input name="cp" id="cp" class="w-full" value="{{ old('cp', $adresse->cp ?? '') }}"/></div>
        <div><x-input-label for="ville" value="Ville"/><x-text-input name="ville" id="ville" class="w-full" value="{{ old('ville', $adresse->ville ?? '') }}"/></div>
        <div><x-input-label for="pays" value="Pays"/><x-text-input name="pays" id="pays" class="w-full" value="{{ old('pays', $adresse->pays ?? 'France') }}"/></div>
      </div>

      <div><x-input-label for="tel" value="Téléphone"/><x-text-input name="tel" id="tel" class="w-full" value="{{ old('tel', $adresse->tel ?? '') }}"/></div>

      <div class="pt-4"><x-primary-button>Continuer</x-primary-button></div>
    </form>
  </div>
</x-app-layout>
