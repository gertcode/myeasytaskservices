<?php

namespace App\Models;

use CodeIgniter\Model;

class EquipoModel extends Model
{
    protected $table = 'equipos';
    protected $primaryKey = 'equipo_id';
    protected $allowedFields = ['nombre_equipo', 'creado_por', 'fecha_creacion'];
    protected $useTimestamps = false;

    // Relaciones y métodos adicionales pueden agregarse aquí
}