<x-app-layout>
  <x-slot name="header">
    <h2 class="text-2xl font-bold">Mon panier</h2>
  </x-slot>

  <div class="max-w-5xl mx-auto p-6">
    @if (session('message'))
      <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3">{{ session('message') }}</div>
    @endif

    @if ($cart->items->isEmpty())
      <div class="rounded-3xl border border-dashed border-gray-300 p-10 text-center text-gray-500">
        Votre panier est vide.
        <div class="mt-4">
          <a href="{{ route('puzzles.index') }}" class="px-4 py-2 rounded-2xl bg-gray-900 text-white hover:bg-gray-800">Découvrir les puzzles</a>
        </div>
      </div>
    @else
      <div class="rounded-3xl border border-gray-200 overflow-hidden bg-white shadow-sm">
        <table class="w-full">
          <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
            <tr>
              <th class="text-left p-4">Puzzle</th>
              <th class="text-left p-4">Prix</th>
              <th class="text-left p-4">Qté</th>
              <th class="text-left p-4">Sous-total</th>
              <th class="p-4"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach ($cart->items as $item)
              <tr>
                <td class="p-4">
                  <div class="font-medium">{{ $item->puzzle->nom }}</div>
                  <div class="text-sm text-gray-500">#{{ $item->puzzle->id }}</div>
                </td>
                <td class="p-4">{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
                <td class="p-4">
                  <form method="POST" action="{{ route('cart.item.update', $item) }}" class="flex items-center gap-2">
                    @csrf @method('PATCH')
                    <x-ui.qty name="quantity" :value="$item->quantity" />
                    <button class="text-sm px-3 py-2 rounded-xl ring-1 ring-gray-300 hover:bg-gray-50">Mettre à jour</button>
                  </form>
                </td>
                <td class="p-4 font-semibold">
                  {{ number_format($item->quantity * $item->unit_price, 2, ',', ' ') }} €
                </td>
                <td class="p-4">
                  <form method="POST" action="{{ route('cart.item.remove', $item) }}">
                    @csrf @method('DELETE')
                    <button class="px-3 py-2 rounded-xl bg-red-50 text-red-700 hover:bg-red-100">Supprimer</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot class="bg-gray-50">
            <tr>
              <td colspan="3" class="p-4 text-right font-semibold">Total :</td>
              <td class="p-4 font-bold text-lg">
                {{ number_format($cart->total, 2, ',', ' ') }} €
              </td>
              <td class="p-4"></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <div class="mt-6 flex items-center justify-between">
        <form method="POST" action="{{ route('cart.clear') }}">
          @csrf @method('DELETE')
          <button class="px-4 py-2 rounded-2xl ring-1 ring-gray-300 hover:bg-gray-50">Vider le panier</button>
        </form>
      </div>
      <a href="{{ route('checkout.start') }}" class="px-5 py-2.5 rounded-2xl bg-gray-900 text-white hover:bg-gray-800">
         Passer la commande
      </a>

    @endif
  </div>
</x-app-layout>
