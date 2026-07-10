<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacante extends Model
{
    protected $fillable = [
        'titulo',
        'salario_id',
        'categoria_id',
        'empresa',
        'ultimo_dia',
        'descripcion',
        'imagen',
        'publicado',
        'user_id'
    ];

    //Relacion 1 a 1 inversa
    public function salario()
    {
        return $this->belongsTo(Salario::class);
    }

    //Relacion 1 a 1 inversa
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
