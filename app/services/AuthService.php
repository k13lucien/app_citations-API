<?php

namespace App_citations\Services;

use App_citations\Middlewares\JwtMiddleware;

class AuthService
{
    public static function getUserId(): ?int
    {
        $payload = JwtMiddleware::handle();
        return $payload->user_id;
    }
}
