<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Puzzle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    // Récupère (ou crée) le panier courant selon le contexte (user connecté ou invité)
    protected function currentCart(Request $request): Cart
    {
        if (Auth::check()) {
            // Fusion panier invité + utilisateur connecté
            $token = $request->cookie('cart_token');
            $userCart = Cart::firstOrCreate(['user_id' => Auth::id(), 'status' => 'draft']);

            if ($token) {
                $guestCart = Cart::where('token', $token)->where('status', 'draft')->first();
                if ($guestCart && $guestCart->id !== $userCart->id) {
                    foreach ($guestCart->items as $it) {
                        $existing = $userCart->items()->where('puzzle_id', $it->puzzle_id)->first();
                        if ($existing) {
                            $existing->increment('quantity', $it->quantity);
                        } else {
                            $userCart->items()->create($it->only('puzzle_id', 'quantity', 'unit_price'));
                        }
                    }
                    $guestCart->delete();
                }
            }

            return $userCart;
        }

        // Invité : panier basé sur un token cookie
        $token = $request->cookie('cart_token');

        if (!$token) {
            $token = Str::uuid()->toString();
            Cookie::queue('cart_token', $token, 60 * 24 * 30); // 30 jours
        }

        return Cart::firstOrCreate(['token' => $token, 'status' => 'draft']);
    }

    // 👇 Affiche le panier (réservé aux utilisateurs connectés)
    public function show(Request $request)
    {
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('message', 'Veuillez vous connecter pour accéder à votre panier.');
        }

        $cart = $this->currentCart($request)->load('items.puzzle');
        return view('cart.show', compact('cart'));
    }

    // 👇 Ajoute un article (même invité)
    public function add(Request $request, Puzzle $puzzle)
    {
        $request->validate(['quantity' => ['nullable', 'integer', 'min:1']]);
        $qty = $request->integer('quantity', 1);

        $cart = $this->currentCart($request);
        $item = $cart->items()->firstOrNew(['puzzle_id' => $puzzle->id]);

        if (!$item->exists) {
            $item->unit_price = $puzzle->prix;
            $item->quantity = $qty;
            $item->save();
        } else {
            $item->increment('quantity', $qty);
        }

        return redirect()->route('cart.show')->with('message', 'Article ajouté au panier.');
    }

    // 👇 Mise à jour d’un article — réservé aux connectés
    public function updateItem(Request $request, CartItem $item)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Veuillez vous connecter pour modifier votre panier.');
        }

        $request->validate(['quantity' => ['required', 'integer', 'min:1']]);
        $item->update(['quantity' => $request->integer('quantity')]);

        return back()->with('message', 'Quantité mise à jour.');
    }

    // 👇 Supprime un article — réservé aux connectés
    public function removeItem(CartItem $item)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Veuillez vous connecter pour modifier votre panier.');
        }

        $item->delete();
        return back()->with('message', 'Article supprimé.');
    }

    // 👇 Vide le panier — réservé aux connectés
    public function clear(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Veuillez vous connecter pour vider votre panier.');
        }

        $cart = $this->currentCart($request);
        $cart->items()->delete();

        return back()->with('message', 'Panier vidé.');
    }
}
