<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    private static $key = 'mYeasyTask2024.';

    public static function generarToken($datos)
    {
        $payload = [
            'iat' => time(),
            'exp' => time() + (24 * 60 * 60), // Expira en 24 horas
            'data' => $datos
        ];

        return JWT::encode($payload, self::$key, 'HS256');
    }

    public static function verificarToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(self::$key, 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }
}