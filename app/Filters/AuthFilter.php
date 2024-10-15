<?php

namespace App\Filters;
use Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Helpers\JwtHelper;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');
        $token = null;

        if (strpos($authHeader, 'Bearer ') !== false) {
            $token = str_replace('Bearer ', '', $authHeader);
        }

        if ($token) {
            $isValid = JwtHelper::verificarToken($token);
            if ($isValid) {
                return;
            }
        }

        return Services::response()
            ->setJSON(['message' => 'Acceso no autorizado'])
            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacer nada
    }
}