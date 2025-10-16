@props(['adresse', 'action', 'method' => 'POST', 'fromCheckout' => false])

<form method="POST" action="{{ $action }}" class="space-y-4">
    @csrf
    @if (in_array($method, ['PUT','PATCH']))
        @method($method)
    @endif

    {{-- astuce: indique si on vient du checkout pour rediriger au récap --}}
    <input type="hidden" name="from_checkout" value="{{ $fromCheckout ? '1' : '0' }}"/>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <x-input-label for="nom" value="Nom" />
            <x-text-input id="nom" name="nom" class="w-full"
                value="{{ old('nom', $adresse->nom) }}" />
            <x-input-error :messages="$errors->get('nom')" class="mt-1" />
        </div>
        <div>
            <x-input-label for="prenom" value="Prénom" />
            <x-text-input id="prenom" name="prenom" class="w-full"
                value="{{ old('prenom', $adresse->prenom) }}" />
            <x-input-error :messages="$errors->get('prenom')" class="mt-1" />
        </div>
    </div>

    <div>
        <x-input-label for="ligne1" value="Adresse" />
        <x-text-input id="ligne1" name="ligne1" class="w-full"
            value="{{ old('ligne1', $adresse->ligne1) }}" />
        <x-input-error :messages="$errors->get('ligne1')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="ligne2" value="Complément" />
        <x-text-input id="ligne2" name="ligne2" class="w-full"
            value="{{ old('ligne2', $adresse->ligne2) }}" />
        <x-input-error :messages="$errors->get('ligne2')" class="mt-1" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <x-input-label for="cp" value="Code postal" />
            <x-text-input id="cp" name="cp" class="w-full"
                value="{{ old('cp', $adresse->cp) }}" />
            <x-input-error :messages="$errors->get('cp')" class="mt-1" />
        </div>
        <div>
            <x-input-label for="ville" value="Ville" />
            <x-text-input id="ville" name="ville" class="w-full"
                value="{{ old('ville', $adresse->ville) }}" />
            <x-input-error :messages="$errors->get('ville')" class="mt-1" />
        </div>
        <div>
            <x-input-label for="pays" value="Pays" />
            <x-text-input id="pays" name="pays" class="w-full"
                value="{{ old('pays', $adresse->pays ?? 'France') }}" />
            <x-input-error :messages="$errors->get('pays')" class="mt-1" />
        </div>
    </div>

    <div>
        <x-input-label for="tel" value="Téléphone" />
        <x-text-input id="tel" name="tel" class="w-full"
            value="{{ old('tel', $adresse->tel) }}" />
        <x-input-error :messages="$errors->get('tel')" class="mt-1" />
    </div>

    <div class="pt-4">
        <x-primary-button>Enregistrer</x-primary-button>
    </div>
</form>
