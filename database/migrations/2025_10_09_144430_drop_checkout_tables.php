<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('commande_items');
        Schema::dropIfExists('paiements');
        Schema::dropIfExists('commandes');
        Schema::dropIfExists('adresses');
    }
    public function down(): void
    {
        // rien (ou recréer si tu veux)
    }
};