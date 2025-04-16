<?php

namespace App_citations\Middlewares;

use App_citations\Core\Auth;

class AuthMiddleware
{
    public static function verify($expectedUserId = null)
    {
        $headers = apache_request_headers();
        $authHeader = $headers['Authorization'] ?? '';

        if (!str_starts_with($authHeader, 'Bearer ')) {
            http_response_code(401);
            echo json_encode(['message' => 'Token manquant ou invalide']);
            exit;
        }

        $jwt = substr($authHeader, 7);

        $payload = Auth::verifyJWT($jwt);

        if (!$payload) {
            http_response_code(401);
            echo json_encode(["message" => "Token invalide ou expiré."]);
            exit;
        }

        if ($expectedUserId !== null && $payload->user_id != $expectedUserId) {
            http_response_code(403);
            echo json_encode(["message" => "Accès interdit à cette ressource."]);
            exit;
        }

        return $payload;
    }
}
