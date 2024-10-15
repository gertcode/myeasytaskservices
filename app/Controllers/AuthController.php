<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Helpers\JwtHelper;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    public function login()
    {
        $rules = [
            'correo'      => 'required|valid_email',
            'contrasena'  => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $email = $this->request->getVar('correo');
        $password = $this->request->getVar('contrasena');

        $model = new UsuarioModel();
        $user = $model->where('correo', $email)->first();

        if ($user && password_verify($password, $user['contrasena'])) {
            $token = JwtHelper::generarToken(['usuario_id' => $user['usuario_id']]);
            return $this->respond(['token' => $token], 200);
        } else {
            return $this->failUnauthorized('Credenciales inválidas.');
        }
    }

    public function register()
    {
        $rules = [
            'nombre_usuario' => 'required|min_length[6]',
            'correo'  => 'required|valid_email|is_unique[usuarios.correo]',
            'contrasena'  => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'nombre_usuario' => $this->request->getVar('nombre_usuario'),
            'correo' => $this->request->getVar('correo'),
            'contrasena' => password_hash($this->request->getVar('contrasena'), PASSWORD_BCRYPT)
        ];

        $model = new UsuarioModel();
        $model->insert($data);

        return $this->respondCreated(['message' => 'Usuario registrado exitosamente.']);
    }

    public function recuperarContrasena()
{
    $email = $this->request->getVar('correo');
    $model = new UsuarioModel();
    $user = $model->where('correo', $email)->first();

    if ($user) {
        $token = bin2hex(random_bytes(50));
        // Guardar el token en una tabla de restablecimiento de contraseñas con una fecha de expiración

        // Enviar correo electrónico al usuario con el enlace de restablecimiento
        // Ejemplo: https://tusitio.com/reset?token=$token

        return $this->respond(['message' => 'Se ha enviado un correo para restablecer la contraseña.']);
    } else {
        return $this->failNotFound('Correo no encontrado.');
    }
}
}