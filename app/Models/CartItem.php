<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id','puzzle_id','quantity','unit_price'];

    public function cart()   { return $this->belongsTo(Cart::class); }
    public function puzzle() { return $this->belongsTo(Puzzle::class); }
}
