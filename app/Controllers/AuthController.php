<?php
/**
 * hannilsolutions
 * sistemas@hannilsolutions.com
 * 2022-09-23
 */
namespace App\Controllers;

use App\Models\Usuario;
use App\Requests\CustomRequestHandler;
use App\Response\CustomResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Respect\Validation\Validator as v;
use App\Validation\Validator;

class AuthController
{
    protected $customResponse;

    protected $user;

    protected $validator;

    public function __construct()
    {
        $this->customResponse = new CustomResponse;

        $this->user = new Usuario();

        $this->validator = new Validator;

        $this->headersToken = new RequestHeadersController();
    }

    /**
     * ENDPOINT POST ->findByMail for generate Jwtcode
     */
    public function validate(Request $request , Response $response)
    {
        $this->headersToken->apitoken($request);

        if($this->headersToken->failed())
        {
            $responseMenssage = $this->headersToken->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);
        }

        $this->validator->validate($request , [
            "email" => v::notEmpty()
        ]);
        
        if($this->validator->failed())
        {
            $responseMenssage = $this->validator->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);
        }

        $email = CustomRequestHandler::getParam($request , "email");

        if(!$this->verify_exist($email))
        {
            $responseMenssage = "error credenciales";

            return $this->customResponse->is400Response($response , $responseMenssage);
        }
        /**en caso de existir generar el jwt */
        $responseMenssage = GenerateJWTController::generateToken($email);

        $this->customResponse->is200Response($response , $responseMenssage);
    }

    public function verify_exist($email)
    {
        $getMail = $this->user->where("usuario_correo" , "=" , $email)->count();

        if($getMail > 0)
        {
            return true;
        }

        return false;
    }
}

?>