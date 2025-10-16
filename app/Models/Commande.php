<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table = 'commandes';
    protected $fillable = ['user_id','adresse_id','provider','status','total_ttc'];

    public function items(){ return $this->hasMany(CommandeItem::class, 'commande_id'); }
    public function adresse(){ return $this->belongsTo(Adresse::class); }
}
