<?php

namespace App\Models;

use CodeIgniter\Model;

class InvitacionModel extends Model
{
    protected $table = 'invitaciones';
    protected $primaryKey = 'invitacion_id';
    protected $allowedFields = ['equipo_id', 'codigo', 'fecha_creacion', 'usado'];
    protected $useTimestamps = false;

    // Generar código de invitación único
    public function generarCodigo($equipo_id)
    {
        $codigo = bin2hex(random_bytes(16));
        $this->insert([
            'equipo_id' => $equipo_id,
            'codigo' => $codigo,
            'usado' => 0
        ]);
        return $codigo;
    }
}