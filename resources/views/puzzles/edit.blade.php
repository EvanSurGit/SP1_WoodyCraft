<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editer un puzzle') }}
        </h2>
    </x-slot>

    <x-puzzles-card>
        @if (session()->has('message'))
            <div class="mt-3 mb-4 list-disc list-inside text-sm text-green-600">
                {{ session('message') }}
            </div>
        @endif

        <form action="{{ route('puzzles.update', $puzzle->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')

            <div>
                <x-input-label for="nom" :value="__('Nom')" />
                <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom" :value="old('nom', $puzzle->nom)" required autofocus />
                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="prix" :value="__('Prix')" />
                <x-text-input id="prix" class="block mt-1 w-full" type="number" step="0.01" name="prix" :value="old('prix', $puzzle->prix)" required />
                <x-input-error :messages="$errors->get('prix')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="categorie_id" :value="__('Categorie')" />
                <select name="categorie_id" id="categorie_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="">Sélectionnez une catégorie</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('categorie_id', $puzzle->categorie_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nom }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('categorie_id')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="description" :value="__('Description')" />
                <x-textarea class="block mt-1 w-full" id="description" name="description" required>{{ old('description', $puzzle->description) }}</x-textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="note" :value="__('Note')" />
                <x-text-input id="note" class="block mt-1 w-full" type="number" step="0.1" name="note" :value="old('note', $puzzle->note)" />
                <x-input-error :messages="$errors->get('note')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="image" :value="__('Image (Laisser vide pour conserver l\'actuelle)')" />
                @if($puzzle->image)
                    <div class="mb-2">
                        <img src="{{ asset($puzzle->image) }}" alt="Image actuelle" class="h-20 object-cover rounded">
                    </div>
                @endif
                <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" />
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    {{ __('Enregistrer') }}
                </x-primary-button>
            </div>
        </form>
    </x-puzzles-card>
</x-app-layout>