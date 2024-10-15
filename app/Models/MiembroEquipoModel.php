<?php

namespace App\Models;

use CodeIgniter\Model;

class MiembroEquipoModel extends Model
{
    protected $table      = 'miembros_equipo';
    protected $primaryKey = false; // Si no tienes una clave primaria única

    protected $allowedFields = ['equipo_id', 'usuario_id', 'rol', 'fecha_union'];
    protected $useTimestamps = false;
    protected $returnType    = 'array';
}