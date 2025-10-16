<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Creer un puzzle') }}
        </h2>
    </x-slot>

    @php
        // Fallback si le contrôleur n'a pas passé $categories
        // (à terme, l'idéal est de le passer depuis PuzzleController@create)
        $categories = $categories
            ?? \App\Models\Categorie::query()->orderBy('nom')->get();
    @endphp

    <x-puzzles-card>
        @if (session()->has('message'))
            <div class="mt-3 mb-4 list-disc list-inside text-sm text-green-600">
                {{ session('message') }}
            </div>
        @endif

        <form action="{{ route('puzzles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nom --}}
            <div>
                <x-input-label for="nom" :value="__('Nom')" />
                <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom" :value="old('nom')" required autofocus />
                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
            </div>

            {{-- Catégorie --}}
            <div class="mt-4">
                <x-input-label for="categorie_id" :value="__('Categorie')" />

                <select id="categorie_id" name="categorie_id" class="block mt-1 w-full border-gray-300 rounded" required>
                    @if($categories->isEmpty())
                        <option value="">{{ __('Aucune catégorie disponible') }}</option>
                    @else
                        <option value="">{{ __('-- Choisir une catégorie --') }}</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    @endif
                </select>

                <x-input-error :messages="$errors->get('categorie_id')" class="mt-2" />
                @if($categories->isEmpty() && auth()->user()?->is_admin)
                    <div class="text-sm text-gray-500 mt-2">
                        {{ __('Aucune catégorie.')}} <a href="{{ route('categories.create') }}" class="underline">{{ __('Créer une catégorie') }}</a>
                    </div>
                @endif
            </div>

            {{-- Description --}}
            <div class="mt-4">
                <x-input-label for="description" :value="__('Description')" />
                <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" required />
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            {{-- Note --}}
            <div class="mt-4">
                <x-input-label for="note" :value="__('Note')" />
                <x-text-input id="note" class="block mt-1 w-full" type="number" step="0.1" min="0" max="5" name="note" :value="old('note')" />
                <x-input-error :messages="$errors->get('note')" class="mt-2" />
            </div>

            {{-- Prix --}}
            <div class="mt-4">
                <x-input-label for="prix" :value="__('Prix')" />
                <x-text-input id="prix" class="block mt-1 w-full" type="number" step="0.01" min="0" name="prix" :value="old('prix')" required />
                <x-input-error :messages="$errors->get('prix')" class="mt-2" />
            </div>

            {{-- Image --}}
            <div class="mt-4">
                <x-input-label for="image" :value="__('Image')" />
                <input id="image" class="block mt-1 w-full border-gray-300 rounded" type="file" name="image" accept="image/*" required />
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-primary-button class="ml-3">
                    {{ __('Send') }}
                </x-primary-button>
            </div>
        </form>
    </x-puzzles-card>
</x-app-layout>
