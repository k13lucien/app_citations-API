<?php

namespace App_citations\Middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{
    public static function verify(): ?int
    {
        $headers = apache_request_headers();
        $authHeader = $headers['Authorization'] ?? '';

        if (!str_starts_with($authHeader, 'Bearer ')) {
            http_response_code(401);
            echo json_encode(['message' => 'Token manquant ou invalide']);
            exit;
        }

        $jwt = substr($authHeader, 7);

        try {
            $decoded = JWT::decode($jwt, new Key($_ENV['SECRET_KEY'], 'HS256'));
            return $decoded->sub;
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['message' => 'Token invalide : ' . $e->getMessage()]);
            exit;
        }
    }
}
