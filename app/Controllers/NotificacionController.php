<?php

namespace App\Controllers;

use App\Models\NotificacionModel;
use CodeIgniter\RESTful\ResourceController;

class NotificacionController extends ResourceController
{
    protected $modelName = 'App\Models\NotificacionModel';
    protected $format    = 'json';

    public function index()
    {
        $usuario_id = $this->request->getVar('usuario_id');
        $notificaciones = $this->model->where('usuario_id', $usuario_id)->findAll();

        return $this->respond($notificaciones);
    }

    public function marcarComoLeido($id)
    {
        $this->model->update($id, ['leido' => 1]);

        return $this->respond(['message' => 'Notificación marcada como leída.']);
    }
}