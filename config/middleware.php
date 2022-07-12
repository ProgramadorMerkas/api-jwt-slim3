<?php 

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

$logger = new Logger("slim");
$rotating = new RotatingFileHandler(__DIR__ . "/logs/slim.log", 0, Logger::DEBUG);
$logger->pushHandler($rotating);

return function ($app)
{
    $app->add(new Tuupola\Middleware\JwtAuthentication([
        "ignore"=>["/auth/login"],
        "secret"=> JWT_SECRET_KEY,
        "logger"=> $logger,
        "error"=>function ($response,$arguments)
        {
            $data["success"]= false;
            $data["response"]=$arguments["message"];
            $data["status_code"] = "401";

            return $response->withHeader("Content-type","application/json")
                ->getBody()->write(json_encode($data,JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    ]));


    $app->add(function ($req,$res,$next){
       $response = $next($req,$res);
      return $response->withHeader("Access-Control-Allow-Origin","*")
           ->withHeader("Access-Control-Allow-Headers","X-Requested-With,Content-Type,Accept,Origin,Authorization")
           ->withHeader("Access-Control-Allow-Methods","GET,POST,PUT,PATCH,OPTIONS,DELETE")
           ->withHeader("Access-Control-Allow-Credentials","true");
    });
};