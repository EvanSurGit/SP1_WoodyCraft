<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Cart extends Model
{
    protected $fillable = ['user_id','token','status'];

    public function items() { return $this->hasMany(CartItem::class); }
    public function user()  { return $this->belongsTo(User::class);  }

    // total calculé
    public function getTotalAttribute()
    {
        return $this->items->sum(fn($i) => $i->quantity * $i->unit_price);
    }
}