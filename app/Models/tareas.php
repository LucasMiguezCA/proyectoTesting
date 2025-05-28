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
        'estado'
    ];

    public function subtareas()
    {
         return $this->hasMany(Subtarea::class); // Ya no necesitas especificar claves        
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
