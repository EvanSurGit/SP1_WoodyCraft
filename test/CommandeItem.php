<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CommandeItem extends Model
{
    protected $table = 'commande_items';
    protected $fillable = ['commande_id','puzzle_id','quantity','unit_price'];

    public function commande(){ return $this->belongsTo(Commande::class); }
    public function puzzle(){ return $this->belongsTo(Puzzle::class); }
}