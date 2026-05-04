<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puzzle extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'prix', 'categorie_id', 'fournisseur_id', 'description', 'note', 'image'];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function scopeSimilarTo($query, Puzzle $puzzle)
    {
        return $query->where('id', '!=', $puzzle->id)
            ->when(isset($puzzle->categorie_id), fn($q) =>
                $q->where('categorie_id', $puzzle->categorie_id)
            );
    }
}