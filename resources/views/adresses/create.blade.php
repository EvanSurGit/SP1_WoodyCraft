<x-app-layout>
  <x-slot name="header"><h2 class="text-2xl font-bold">Nouvelle adresse</h2></x-slot>
  <div class="max-w-3xl mx-auto p-6">
    @include('adresses._form', [
        'adresse' => $adresse,
        'action' => route('adresses.store'),
        'method' => 'POST',
        'fromCheckout' => request()->boolean('from_checkout'),
    ])
  </div>
</x-app-layout>
