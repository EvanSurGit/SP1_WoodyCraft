<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('cart_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
        $table->foreignId('puzzle_id')->constrained()->cascadeOnDelete();
        $table->unsignedInteger('quantity')->default(1);
        $table->decimal('unit_price', 10, 2); // copie du prix au moment de l'ajout
        $table->timestamps();

        $table->unique(['cart_id', 'puzzle_id']); // un article par puzzle
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
