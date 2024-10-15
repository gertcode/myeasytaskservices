<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificacionModel extends Model
{
    protected $table = 'notificaciones';
    protected $primaryKey = 'notificacion_id';
    protected $allowedFields = ['usuario_id', 'mensaje', 'leido', 'fecha_creacion'];
    protected $useTimestamps = false;
}