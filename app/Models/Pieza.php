<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pieza extends Model
{
    use HasFactory;

    protected $primaryKey = 'IdPieza';
    public $incrementing = false;
    protected $keyType = 'string';

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

    protected $casts = [
        'peso_teorico' => 'decimal:2',
        'peso_real' => 'decimal:2',
        'fecha_registro' => 'date',
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
