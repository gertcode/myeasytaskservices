<?php

namespace App\Models;

use CodeIgniter\Model;

class AsignacionTareaModel extends Model
{
    protected $table = 'asignaciones_tareas';
    protected $primaryKey = ['tarea_id', 'usuario_id'];
    protected $allowedFields = ['tarea_id', 'usuario_id', 'asignado_en'];
    protected $useTimestamps = false;
    protected $useAutoIncrement = false;
}