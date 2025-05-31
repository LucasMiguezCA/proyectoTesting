<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subtarea extends Model
{
    use HasFactory;
    protected $fillable = [
        'tarea_id',
        'contenido',
        'completada_subtarea',
    ];

    public function tareas(): BelongsTo
    {
        return $this->belongsTo(tareas::class ,'tareas_id');
    }
}
