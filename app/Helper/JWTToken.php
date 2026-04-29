<?php
namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    static public function createToken($user_id, $user_name, $user_email, $user_phone, $user_role, $user_avatar, $expriy_days)
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'organichatt',
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24 * $expriy_days,
            'userId' => $user_id,
            'userName' => $user_name,
            'userEmail' => $user_email,
            'userPhone' => $user_phone,
            'userRole' => $user_role,
            'avatar' => $user_avatar,
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
    static public function ReadToken($token)
    {
        try {
            if ($token == null) {
                return "unauthorized";
            } else {
                $key = env('JWT_KEY');
                return JWT::decode($token, new Key($key, 'HS256'));
            }
        } catch (\Throwable $th) {
            return "unauthorized";
        }
    }
}