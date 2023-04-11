<?php
/**
 * hannilsolutions
 * sistemas@hannilsolutions.com
 * 2022-09-23
 */
namespace App\Controllers;

use App\Models\Usuarios;
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

    protected $user_recuperado_estado;

    public function __construct()
    {
        $this->customResponse = new CustomResponse;

        $this->user = new Usuarios();

        $this->validator = new Validator;

        $this->headersToken = new RequestHeadersController();
    }

    public function login(Request $request , Response $response)
    {
        $this->validator->validate($request , [
            "email" => v::notEmpty(),
            "password" => v::notEmpty()
        ]);

        if($this->validator->failed())
        {
            $responseMenssage = $this->validator->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);
        }

        //buscar correo
        $correo = CustomRequestHandler::getParam($request , "email");
        $password = CustomRequestHandler::getParam($request , "password");

        $verify_credentails = verify_credentails($correo , $password);

        if($verify_credentails == false)
        {
            $responseMenssage = "Error de sus credenciales";

            return $this->customResponse->is400Response($response , $responseMenssage);
        }

        //
        if($this->user_recuperado_estado != 1)
        {
            $responseMenssage = "Usuario Inactivo";

            return $this->customResponse->is400Response($response , $responseMenssage);
        }

        $responseMenssage = GenerateTokenController::generateToken($email);

        $this->customResponse->is200Response($response , $responseMenssage);


    }

     

    public function verify_credentails($email , $password)
    {
        $correo = $this->user->where("usuario_correo" , "=" , $email)->count();

        if($correo == false)
        {
            return false;
        }

        $contra = $this->user->where("usuario_correo" , "=" , $email)->get();

        $md5Pass = "";

        foreach($contra as $item)
        {
            $md5Pass = $item->usuario_contrasena;

            $this->user_recuperado_estado = $item->usuario_estado;
        }

        if(md5($password) != $md5Pass)
        {
            return false;
        }

        return true;
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
        $responseMenssage = GenerateTokenController::generateToken($email);

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