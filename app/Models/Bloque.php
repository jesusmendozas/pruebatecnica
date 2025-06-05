<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloque extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDBloque';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

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
        return $this->hasMany(Pieza::class, 'IDBloque', 'IDBloque');
    }
}
