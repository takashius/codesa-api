<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recarga extends Model
{
    use HasFactory;

    protected $fillable = [
        'tarjeta_id',
        'user_id',
        'importe',
        'fecha',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
