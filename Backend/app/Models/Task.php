<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tareas';        // Nombre de tu tabla
    protected $primaryKey = 'id_tarea'; // Tu llave primaria
    protected $guarded = [];            // Permite guardar cualquier campo
    public $timestamps = true; 
}