<?php
namespace App_citations\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

define('JWT_SECRET', $_ENV['SECRET_KEY']);

class Auth {
    public static function generateJWT($userId, $email) {
        $payload = [
            'iss' => "yourdomain.com",
            'iat' => time(),
            'exp' => time() + 3600, // Expire après 1h
            'user_id' => $userId,
            'email' => $email
        ];
        return JWT::encode($payload, JWT_SECRET, 'HS256');
    }

    public static function verifyJWT($token) {
        try {
            return JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }
}
