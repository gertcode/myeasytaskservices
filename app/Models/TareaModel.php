<?php

namespace App\Models;

use CodeIgniter\Model;

class TareaModel extends Model
{
    protected $table = 'tareas';
    protected $primaryKey = 'tarea_id';
    protected $allowedFields = [
        'equipo_id',
        'nombre_tarea',
        'descripcion',
        'estatus',
        'porcentaje_avance',
        'fecha_creacion',
        'fecha_actualizacion'
    ];
    protected $useTimestamps = false;
}