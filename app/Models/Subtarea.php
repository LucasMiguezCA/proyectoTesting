<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtarea extends Model
{
    use HasFactory;
    protected $fillable = [
        'tarea_id',
        'Contenido',
        'Completada'
    ];

    public function tarea()
    {
            return $this->belongsTo(tareas::class);

    }
}
