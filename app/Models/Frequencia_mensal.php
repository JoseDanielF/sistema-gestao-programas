<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frequencia_mensal extends Model
{
    use HasFactory;

    protected $fillable = [
        'mes',
        'frequencia',
        'tempo_total',
        'vinculo_id'
    ];

    public function vinculo()
    {
        return $this->belongsTo(Vinculo::class);
    }
}
