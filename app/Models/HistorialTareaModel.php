<?php

namespace App\Models;

use CodeIgniter\Model;

class HistorialTareaModel extends Model
{
    protected $table = 'historial_tareas';
    protected $primaryKey = 'historial_id';
    protected $allowedFields = ['tarea_id', 'cambiado_por', 'descripcion_cambio', 'fecha_cambio'];
    protected $useTimestamps = false;
}