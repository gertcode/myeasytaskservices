<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'usuario_id';
    protected $allowedFields = ['nombre_usuario', 'correo', 'contrasena', 'fecha_creacion'];
    protected $useTimestamps = false;
}