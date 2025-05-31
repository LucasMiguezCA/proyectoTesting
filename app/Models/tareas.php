<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tareas extends Model
{
    protected $with = ['subtareas'];
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

    public function subtareas()
    {
         return $this->hasMany(Subtarea::class, 'tarea_id'); // Ya no necesitas especificar claves        
    }

    

    public function user(){
        return $this->belongsTo(User::class);
    }

}
