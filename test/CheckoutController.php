<?php

namespace App\Http\Controllers;

use App\Models\{Cart, Adresse, Commande, CommandeItem};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CheckoutController extends Controller
{
    protected function currentCart(Request $request): Cart
    {
        return Cart::query()
            ->where('status','draft')
            ->when(Auth::check(),
                fn($q)=>$q->where('user_id', Auth::id()),
                fn($q)=>$q->where('token', $request->cookie('cart_token'))
            )
            ->with('items.puzzle')
            ->firstOrFail();
    }

    // Décide où aller depuis "Passer la commande"
    public function start(Request $request)
    {
        $adresse = Auth::check()
            ? Adresse::where('user_id', Auth::id())->latest()->first()
            : null;

        if ($adresse) {
            return redirect()->route('checkout.review', $adresse);
        }
        return redirect()->route('checkout.address');
    }

    // Étape 1 — Formulaire adresse
    public function address(Request $request)
    {
        $cart = $this->currentCart($request);
        $prefill = Auth::check()
            ? Adresse::where('user_id', Auth::id())->latest()->first()
            : null;

        return view('checkout.address', ['cart'=>$cart, 'adresse'=>$prefill]);
    }

    public function addressStore(Request $request)
    {
        $data = $request->validate([
            'nom'    => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'ligne1' => 'required|string|max:255',
            'ligne2' => 'nullable|string|max:255',
            'cp'     => 'required|string|max:20',
            'ville'  => 'required|string|max:120',
            'pays'   => 'required|string|max:120',
            'tel'    => 'nullable|string|max:30',
        ]);
        $data['user_id'] = Auth::id();
        $adresse = Adresse::create($data);

        return redirect()->route('checkout.review', $adresse);
    }

    // Étape 2 — Récap + choix paiement
    public function review(Request $request, Adresse $adresse)
    {
        $cart  = $this->currentCart($request);
        $total = $cart->items->sum(fn($i) => $i->quantity * $i->unit_price);
        return view('checkout.review', compact('cart','adresse','total'));
    }

    // Création commande + redirection selon provider
    public function place(Request $request, Adresse $adresse)
    {
        $request->validate(['provider' => 'required|in:cheque,paypal']);

        $cart = $this->currentCart($request);
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.show')->with('message','Panier vide.');
        }
        $total = $cart->items->sum(fn($i) => $i->quantity * $i->unit_price);

        $commande = Commande::create([
            'user_id'    => Auth::id(),
            'adresse_id' => $adresse->id,
            'provider'   => $request->provider,
            'status'     => $request->provider === 'cheque' ? 'awaiting_cheque' : 'pending',
            'total_ttc'  => $total,
        ]);

        foreach ($cart->items as $it) {
            CommandeItem::create([
                'commande_id' => $commande->id,
                'puzzle_id'   => $it->puzzle_id,
                'quantity'    => $it->quantity,
                'unit_price'  => $it->unit_price,
            ]);
        }

        // on verrouille le panier
        $cart->update(['status' => 'ordered']);

        return $request->provider === 'cheque'
            ? redirect()->route('checkout.cheque', $commande)
            : redirect()->route('checkout.paypal', $commande);
    }

    // Paiement par chèque (instructions) — UNIQUE méthode
    public function cheque(Commande $commande)
    {
        $commande->load('items.puzzle','adresse');
        return view('checkout.cheque', compact('commande'));
    }

    // PayPal simulé (page de redirection + bouton "succès")
    public function paypal(Commande $commande)
    {
        $commande->load('adresse');
        return view('checkout.paypal', compact('commande'));
    }

    // Succès générique
    public function success(Commande $commande)
    {
        $commande->update(['status' => 'paid']);
        return view('checkout.success', compact('commande'));
    }

    // Génération PDF pour le chèque (DomPDF)
    public function chequePdf(Commande $commande)
    {
        // Sécurité de base : si la commande est liée à un user différent, on bloque
        if (!is_null($commande->user_id) && auth()->check() && $commande->user_id !== auth()->id()) {
            abort(403);
        }

        $commande->load('items.puzzle','adresse');

        $logoPath = public_path('images/logo.png'); // optionnel

        $pdf = Pdf::loadView('pdf.facture', [
            'commande'  => $commande,
            'logoPath'  => file_exists($logoPath) ? $logoPath : null,
            'now'       => now(),
            'cheque_to' => [
                'dest'  => 'WoodyCraft – Service Paiement',
                'addr1' => '12 rue des Tipis',
                'addr2' => '42000 Saint-Étienne',
                'pays'  => 'France',
                'ordre' => 'WoodyCraft',
            ],
        ])->setPaper('a4');

        return $pdf->download('facture-commande-'.$commande->id.'.pdf');
        // ou :
        // return $pdf->stream('facture-commande-'.$commande->id.'.pdf');
    }
}
