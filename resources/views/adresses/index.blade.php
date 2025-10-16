<x-app-layout>
  <x-slot name="header"><h2 class="text-2xl font-bold">Mes adresses</h2></x-slot>

  <div class="max-w-4xl mx-auto p-6 space-y-4">
    <a href="{{ route('adresses.create') }}" class="px-3 py-2 rounded-xl bg-gray-900 text-white">Ajouter</a>

    <div class="grid sm:grid-cols-2 gap-4">
      @foreach($adresses as $a)
        <div class="rounded-2xl border p-4 space-y-1">
          <div class="font-medium">{{ $a->prenom }} {{ $a->nom }}</div>
          <div>{{ $a->ligne1 }} {{ $a->ligne2 }}</div>
          <div>{{ $a->cp }} {{ $a->ville }}, {{ $a->pays }}</div>
          @if($a->tel)<div>{{ $a->tel }}</div>@endif

          <div class="pt-3 flex items-center gap-2">
            <a class="px-3 py-1 rounded-xl ring-1 ring-gray-300 hover:bg-gray-50"
               href="{{ route('adresses.edit', $a) }}">Modifier</a>
            <form method="POST" action="{{ route('adresses.destroy', $a) }}" onsubmit="return confirm('Supprimer ?')">
              @csrf @method('DELETE')
              <button class="px-3 py-1 rounded-xl bg-red-50 text-red-700 hover:bg-red-100">Supprimer</button>
            </form>
            {{-- Utiliser pour la commande en cours --}}
            <a class="ml-auto px-3 py-1 rounded-xl bg-gray-900 text-white"
               href="{{ route('checkout.review', $a) }}">Utiliser</a>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-4">{{ $adresses->links() }}</div>
  </div>
</x-app-layout>
