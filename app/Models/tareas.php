<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tareas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'completada',
        'importante',
        'categoria',
        'fecha_vencimiento',
    ];

    public function subtareas()
    {
        return $this->hasMany(Subtarea::class, 'tarea_id');
    }
}
