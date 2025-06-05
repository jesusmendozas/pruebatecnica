<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pieza extends Model
{
    use HasFactory;


    protected $fillable = [
        'IdPieza',
        'pieza',
        'peso_teorico',
        'peso_real',
        'estado', 
        'IDBloque',
        'fecha_registro',
        'registrado_por',
    ];

    public function bloque()
    {
        return $this->belongsTo(Bloque::class, 'IDBloque', 'IDBloque');
    }

    public function registrador()
    {
        return $this->belongsTo(User::class, 'registrado_por', 'usuario'); 
    }
}