<?php

namespace App\Http\Controllers;

use App\Models\Adresse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdresseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // redirige vers /login si non connecté (pas de page blanche)
    }

    public function edit(Adresse $adresse)
    {
        $this->authorizeAddress($adresse);
        return view('adresses.edit', compact('adresse'));
    }

    public function update(Request $request, Adresse $adresse)
    {
        $this->authorizeAddress($adresse);

        $validated = $this->validateData($request);
        $adresse->update($validated);

        if ($request->boolean('from_checkout')) {
            return redirect()->route('checkout.review', $adresse)
                ->with('message', 'Adresse mise à jour.');
        }
        return redirect()->route('adresses.index')->with('message', 'Adresse mise à jour.');
    }

    private function authorizeAddress(Adresse $adresse): void
    {
        if (is_null($adresse->user_id)) {         // “claim” si orpheline
            $adresse->user_id = Auth::id();
            $adresse->save();
            return;
        }
        if ($adresse->user_id !== Auth::id()) {   // sinon interdit
            abort(403);
        }
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nom'    => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'ligne1' => 'required|string|max:255',
            'ligne2' => 'nullable|string|max:255',
            'cp'     => 'required|string|max:20',
            'ville'  => 'required|string|max:120',
            'pays'   => 'required|string|max:120',
            'tel'    => 'nullable|string|max:30',
        ]);
    }
}
