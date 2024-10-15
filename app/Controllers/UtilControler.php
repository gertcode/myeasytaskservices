<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Helpers\JwtHelper;

class UtilController extends ResourceController
{
    protected $usuario_id;

    public function __construct()
    {
        $request = service('request');
        $authHeader = $request->getHeaderLine('Authorization');
        $token = null;

        if (strpos($authHeader, 'Bearer ') !== false) {
            $token = str_replace('Bearer ', '', $authHeader);
        }

        if ($token) {
            $decoded = JwtHelper::verificarToken($token);
            if ($decoded) {
                $this->usuario_id = $decoded->data->usuario_id;
            }
        }
    }
}