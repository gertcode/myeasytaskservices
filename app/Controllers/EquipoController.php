<?php

namespace App\Controllers;

use App\Models\EquipoModel;
use App\Models\InvitacionModel;
use App\Models\MiembroEquipoModel;
use App\Helpers\JwtHelper;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Controllers\UtilController;

class EquipoController extends ResourceController
{
    protected $modelName = 'App\Models\EquipoModel';
    protected $format    = 'json';

    public function create()
    {
        // Obtener el token JWT del encabezado 'Authorization'
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = null;

        if (strpos($authHeader, 'Bearer ') !== false) {
            $token = str_replace('Bearer ', '', $authHeader);
        }

        if ($token) {
            // Verificar y decodificar el token
            $decoded = JwtHelper::verificarToken($token);

            if ($decoded) {
                // Extraer el 'usuario_id' del token
                $usuario_id = $decoded->data->usuario_id;

                // Obtener el 'nombre_equipo' del cuerpo de la solicitud
                $nombre_equipo = $this->request->getVar('nombre_equipo');

                // Validar el nombre del equipo
                if (empty($nombre_equipo)) {
                    return $this->failValidationErrors('El nombre del equipo es requerido.');
                }

                $data = [
                    'nombre_equipo' => $nombre_equipo,
                    'creado_por'    => $usuario_id 
                ];

                // Iniciar una transacción para asegurar la integridad de los datos
                $db = \Config\Database::connect();
                $db->transStart();

                // Insertar el nuevo equipo en la base de datos
                $equipo_id = $this->model->insert($data);

                if ($equipo_id) {
                    // Agregar al creador como miembro del equipo
                    $miembroModel = new MiembroEquipoModel();
                    $miembroData = [
                        'equipo_id'   => $equipo_id,
                        'usuario_id'  => $usuario_id,
                        'rol'         => 'administrador', // Puedes definir el rol como 'administrador' o según tu lógica
                        'fecha_union' => date('Y-m-d H:i:s')
                    ];
                    $miembroModel->insert($miembroData);
                } else {
                    // Si no se pudo crear el equipo, revertir la transacción y devolver un error
                    $db->transRollback();
                    return $this->fail('No se pudo crear el equipo.');
                }

                // Completar la transacción
                $db->transComplete();

                if ($db->transStatus() === FALSE) {
                    // Si hubo un error en la transacción, devolver un error
                    return $this->fail('Error al crear el equipo.');
                }

                return $this->respondCreated([
                    'message'   => 'Equipo creado exitosamente.',
                    'equipo_id' => $equipo_id
                ]);


            } else {
                return $this->failUnauthorized('Token inválido.');
            }
        } else {
            return $this->failUnauthorized('Token no proporcionado.');
        }
    }

    public function index()
    {
        $equipos = $this->model->findAll();
        return $this->respond($equipos);
    }

    public function show($id = null)
    {
        $equipo = $this->model->find($id);
        if ($equipo) {
            return $this->respond($equipo);
        } else {
            return $this->failNotFound('Equipo no encontrado.');
        }
    }

    public function generarInvitacion($equipo_id)
    {
        $invitacionModel = new InvitacionModel();
        $codigo = $invitacionModel->generarCodigo($equipo_id);

        return $this->respond(['codigo_invitacion' => $codigo]);
    }

    /*public function getMyTeams()
    {
        if (!$this->usuario_id) {
            return $this->failUnauthorized('Acceso no autorizado.');
        }

        $equipos = $this->model->where('creado_por', $this->usuario_id)->findAll();

        return $this->respond($equipos);
    }*/

    public function getTeamsByUser()
    {
        // Obtener el token JWT del encabezado 'Authorization'
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = null;

        if (strpos($authHeader, 'Bearer ') !== false) {
            $token = str_replace('Bearer ', '', $authHeader);
        }

        if ($token) {
            // Verificar y decodificar el token
            $decoded = JwtHelper::verificarToken($token);

            if ($decoded) {
                // Extraer el 'usuario_id' del token
                $usuario_id = $decoded->data->usuario_id;

                $miembroModel = new MiembroEquipoModel();

                // Obtener los equipos donde el usuario es miembro
                $equipos = $miembroModel->select('equipos.*')
                    ->join('equipos', 'equipos.equipo_id = miembros_equipo.equipo_id')
                    ->where('miembros_equipo.usuario_id', $usuario_id)
                    ->findAll();

                return $this->respond($equipos);
            
        } else {
                return $this->failUnauthorized('Token inválido.');
            }
        } else {
            return $this->failUnauthorized('Token no proporcionado.');
        }
    }
}