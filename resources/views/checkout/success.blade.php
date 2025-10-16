<x-app-layout>
  <x-slot name="header"><h2 class="text-2xl font-bold">Merci !</h2></x-slot>
  <div class="max-w-xl mx-auto p-6 space-y-4">
    <p>Votre paiement a été validé. Commande #{{ $commande->id }}.</p>
    <a href="{{ route('puzzles.index') }}" class="underline">Continuer vos achats</a>
  </div>
</x-app-layout>
