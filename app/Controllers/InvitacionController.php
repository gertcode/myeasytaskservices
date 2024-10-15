<?php

namespace App\Controllers;

use App\Models\InvitacionModel;
use App\Models\MiembroEquipoModel;
use CodeIgniter\RESTful\ResourceController;

class InvitacionController extends ResourceController
{
    protected $modelName = 'App\Models\InvitacionModel';
    protected $format    = 'json';

    public function usarCodigo()
    {
        $codigo = $this->request->getVar('codigo');
        $usuario_id = $this->request->getVar('usuario_id');

        $invitacion = $this->model->where('codigo', $codigo)->where('usado', 0)->first();

        if ($invitacion) {
            // Agregar al usuario al equipo
            $miembroModel = new MiembroEquipoModel();
            $miembroModel->insert([
                'equipo_id' => $invitacion['equipo_id'],
                'usuario_id' => $usuario_id,
                'rol' => 'miembro'
            ]);

            // Marcar invitación como usada
            $this->model->update($invitacion['invitacion_id'], ['usado' => 1]);

            return $this->respond(['message' => 'Unido al equipo exitosamente.']);
        } else {
            return $this->failNotFound('Código de invitación inválido o ya usado.');
        }
    }
}