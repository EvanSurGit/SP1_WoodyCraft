<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
  *{ box-sizing: border-box; }
  body{ font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size:12px; color:#111827; margin:0; }
  .wrap{ padding:28px; }
  .row{ display:flex; gap:24px; }
  .col{ flex:1; }
  .muted{ color:#6b7280; }
  h1{ font-size:20px; margin:0 0 6px 0; }
  h2{ font-size:14px; margin:0 0 6px 0; }
  .card{ border:1px solid #e5e7eb; border-radius:10px; padding:16px; }
  table{ width:100%; border-collapse: collapse; margin-top:10px; }
  th,td{ border:1px solid #e5e7eb; padding:8px; vertical-align: top; }
  th{ background:#f9fafb; text-align:left; }
  .right{ text-align:right; }
  .total{ font-weight:bold; font-size:14px; }
  .mb-2{ margin-bottom:8px; }
  .mb-3{ margin-bottom:12px; }
  .mb-4{ margin-bottom:16px; }
  .small{ font-size:11px; }
  .logo{ height:42px; }
  .badge{ display:inline-block; padding:2px 8px; border:1px solid #e5e7eb; border-radius:999px; font-size:10px; }
</style>
</head>
<body>
  <div class="wrap">
    {{-- En-tête --}}
    <div class="row mb-4">
      <div class="col">
        @if(!empty($logoPath))
          <img src="{{ $logoPath }}" class="logo" alt="Logo">
        @else
          <h1>WoodyCraft</h1>
        @endif
        <div class="small muted">woodycraftweb.com · contact@woodycraft.fr</div>
      </div>
      <div class="col" style="text-align:right">
        <h2>Facture / Invoice</h2>
        <div>N° : <strong>#{{ $commande->id }}</strong></div>
        <div>Date : <strong>{{ $now->format('d/m/Y') }}</strong></div>
        <div class="badge">Paiement : Chèque</div>
      </div>
    </div>

    {{-- Coordonnées --}}
    <div class="row mb-3">
      <div class="col card">
        <h2>Vendu à</h2>
        <div><strong>{{ $commande->adresse->prenom }} {{ $commande->adresse->nom }}</strong></div>
        <div>{{ $commande->adresse->ligne1 }} {{ $commande->adresse->ligne2 }}</div>
        <div>{{ $commande->adresse->cp }} {{ $commande->adresse->ville }}, {{ $commande->adresse->pays }}</div>
        @if($commande->adresse->tel)
          <div>Tél. : {{ $commande->adresse->tel }}</div>
        @endif
      </div>
      <div class="col card">
        <h2>Envoyer le chèque à</h2>
        <div><strong>{{ $cheque_to['dest'] }}</strong></div>
        <div>À l’ordre de : <strong>{{ $cheque_to['ordre'] }}</strong></div>
        <div>{{ $cheque_to['addr1'] }}</div>
        <div>{{ $cheque_to['addr2'] }}</div>
        <div>{{ $cheque_to['pays'] }}</div>
      </div>
    </div>

    {{-- Lignes --}}
    <table>
      <thead>
        <tr>
          <th style="width:55%">Produit</th>
          <th style="width:10%" class="right">Qté</th>
          <th style="width:15%" class="right">PU (€)</th>
          <th style="width:20%" class="right">Sous-total (€)</th>
        </tr>
      </thead>
      <tbody>
        @foreach($commande->items as $it)
          <tr>
            <td>{{ $it->puzzle->nom }}</td>
            <td class="right">{{ $it->quantity }}</td>
            <td class="right">{{ number_format($it->unit_price, 2, ',', ' ') }}</td>
            <td class="right">{{ number_format($it->quantity * $it->unit_price, 2, ',', ' ') }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="right total">Total TTC</td>
          <td class="right total">{{ number_format($commande->total_ttc, 2, ',', ' ') }}</td>
        </tr>
      </tfoot>
    </table>

    {{-- Notes --}}
    <div class="row" style="margin-top:14px;">
      <div class="col small muted">
        Merci pour votre commande. Votre colis sera expédié à réception du chèque.  
        Indiquez le n° de facture <strong>#{{ $commande->id }}</strong> au dos du chèque.
      </div>
      <div class="col small muted" style="text-align:right">
        TVA : non applicable — art. 293B CGI
      </div>
    </div>
  </div>
</body>
</html>
