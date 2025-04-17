<?php

namespace App_citations\Middlewares;

use App_citations\Middlewares\JwtMiddleware;

class AuthMiddleware
{
    public static function ensureUser($expectedUserId)
    {
        $payload = JwtMiddleware::handle();

        if ($payload->user_id != $expectedUserId) {
            http_response_code(403);
            echo json_encode(["message" => "Accès interdit"]);
            exit;
        }

        return $payload;
    }
}
