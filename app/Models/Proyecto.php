<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDproyecto';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'IDproyecto',
        'nombre',
    ];

    public function bloques()
    {
        return $this->hasMany(Bloque::class, 'IDproyecto', 'IDproyecto');
    }

}
