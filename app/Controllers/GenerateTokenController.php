<?php


namespace App\Controllers;



//use App\interfaces\SecretKeyInterface;
use \Firebase\JWT\JWT;

class GenerateTokenController //implements SecretKeyInterface
{

    public static function generateToken($email)
    {
        $now = time();
        $future = strtotime('+1 hour',$now);
        $secret = "82ed81b9bbd2099ced94fcdc2a6a62ec46d2694b";

        $payload = [
          "jti"=>$email,
          "iat"=>$now,
          "exp"=>$future
        ];

        return JWT::encode($payload,$secret,"HS256");
    }

    public static function decodeToken($token)
    {
      $secret = "82ed81b9bbd2099ced94fcdc2a6a62ec46d2694b";
      return JWT::decode($token , $secret , array("HS256"));
    }
}

?>