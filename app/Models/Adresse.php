<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    protected $table = 'adresses';
    protected $fillable = ['user_id','nom','prenom','ligne1','ligne2','cp','ville','pays','tel'];
    public function user(){ return $this->belongsTo(User::class); }
}

