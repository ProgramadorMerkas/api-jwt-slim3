<?php


namespace App\Controllers;



use App\interfaces\SecretKeyInterface;
use \Firebase\JWT\JWT;

class GenerateTokenController implements SecretKeyInterface
{

    public static function generateToken($email)
    {
        $now = time();
        $future = strtotime('+1 hour',$now);
        $secret = SecretKeyInterface::JWT_SECRET_KEY;

        $payload = [
          "jti"=>$email,
          "iat"=>$now,
          "exp"=>$future
        ];

        return JWT::encode($payload,$secret,"HS256");
    }

    public static function decodeToken($token)
    {
      $secret = JWT_SECRET_KEY;
      return JWT::decode($token , $secret , array("HS256"));
    }
}

?>