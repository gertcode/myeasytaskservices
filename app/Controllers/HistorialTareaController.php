<?php

namespace App\Controllers;

use App\Models\HistorialTareaModel;
use CodeIgniter\RESTful\ResourceController;

class HistorialTareaController extends ResourceController
{
    protected $modelName = 'App\Models\HistorialTareaModel';
    protected $format    = 'json';

    public function index($tarea_id)
    {
        $historial = $this->model->where('tarea_id', $tarea_id)->findAll();
        return $this->respond($historial);
    }
}