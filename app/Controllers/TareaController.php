<?php

namespace App\Controllers;

use App\Models\TareaModel;
use App\Models\AsignacionTareaModel;
use App\Models\HistorialTareaModel;
use CodeIgniter\RESTful\ResourceController;

class TareaController extends ResourceController
{
    protected $modelName = 'App\Models\TareaModel';
    protected $format    = 'json';

    public function create()
    {
        $data = [
            'equipo_id' => $this->request->getVar('equipo_id'),
            'nombre_tarea' => $this->request->getVar('nombre_tarea'),
            'descripcion' => $this->request->getVar('descripcion'),
            'estatus' => $this->request->getVar('estatus'),
            'porcentaje_avance' => $this->request->getVar('porcentaje_avance')
        ];

        $tarea_id = $this->model->insert($data);

        // Registrar en el historial
        $historialModel = new HistorialTareaModel();
        $historialModel->insert([
            'tarea_id' => $tarea_id,
            'cambiado_por' => $this->request->getVar('usuario_id'),
            'descripcion_cambio' => 'Creación de la tarea.'
        ]);

        return $this->respondCreated(['message' => 'Tarea creada exitosamente.', 'tarea_id' => $tarea_id]);
    }

    public function index()
    {
        $tareas = $this->model->findAll();
        return $this->respond($tareas);
    }

    public function show($id = null)
    {
        $tarea = $this->model->find($id);
        if ($tarea) {
            return $this->respond($tarea);
        } else {
            return $this->failNotFound('Tarea no encontrada.');
        }
    }

    public function update($id = null)
    {
        $data = $this->request->getRawInput();
        $this->model->update($id, $data);

        // Registrar en el historial
        $historialModel = new HistorialTareaModel();
        $historialModel->insert([
            'tarea_id' => $id,
            'cambiado_por' => $this->request->getVar('usuario_id'),
            'descripcion_cambio' => 'Actualización de la tarea.'
        ]);

        return $this->respond(['message' => 'Tarea actualizada exitosamente.']);
    }

    public function delete($id = null)
    {
        $this->model->delete($id);
        return $this->respondDeleted(['message' => 'Tarea eliminada exitosamente.']);
    }

    public function asignarUsuarios($tarea_id)
    {
        $usuarios = $this->request->getVar('usuarios'); // Array de usuario_id
        $asignacionModel = new AsignacionTareaModel();

        foreach ($usuarios as $usuario_id) {
            $asignacionModel->insert([
                'tarea_id' => $tarea_id,
                'usuario_id' => $usuario_id
            ]);

            // Enviar notificación al usuario
            $notificacionModel = new \App\Models\NotificacionModel();
            $notificacionModel->insert([
                'usuario_id' => $usuario_id,
                'mensaje' => 'Se te ha asignado la tarea ID: ' . $tarea_id
            ]);
        }

        return $this->respond(['message' => 'Usuarios asignados a la tarea.']);
    }
}