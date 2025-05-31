<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'estado',
        'user_id'
    ];

    public function subtareas(): HasMany
    {
        return $this->hasMany(Subtarea::class);
    }

    public function subtareasQuerySql()
    {
        return $this->subtareas()->toSql();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
