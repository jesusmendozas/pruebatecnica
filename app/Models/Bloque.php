<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloque extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'IDBloque',
        'nombre_bloque',
        'IDproyecto',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'IDproyecto', 'IDproyecto');
    }

    public function piezas()
    {
        return $this->hasMany(Pieza::class, 'IdPieza', 'IDBloque');
    }
}
