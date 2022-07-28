<?php


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

    protected  $customResponse;

    protected  $user;

    protected  $validator;

    public function  __construct()
    {
        $this->customResponse = new CustomResponse();

        $this->user = new Usuarios();

        $this->validator = new Validator(); 
    }

//ENDPOINT GET -> validar jwt
public function Validate(Request $request , Response $response , $jwt)
{   
    $getDecodeJwt = GenerateTokenController::decodeToken($jwt["jwt"]);

    $responseMessage = $jwt["jwt"];

    $getUsuario = $this->getUsuario($getDecodeJwt->jti);

    $this->customResponse->is200ResponseLogin($response , $responseMessage , $getUsuario);
}
 
//ENDP POINT POST -> login generación de toke, menu y datos de usuario

    public function Login(Request $request, Response $response)
    {
       $this->validator->validate($request,[
          "email"=>v::notEmpty()->email(),
          "password"=>v::notEmpty()
       ]);

       if($this->validator->failed())
       {
           $responseMessage = $this->validator->errors;

           return $this->customResponse->is400Response($response,$responseMessage);
       }
       //asigna email a variable $email
       $email = CustomRequestHandler::getParam($request,"email");
       //verificar credenciales 
       $verifyAccount = $this->verifyAccount(CustomRequestHandler::getParam($request,"password"), $email);

       if($verifyAccount==false)
       {
           $responseMessage ="Error de sus credenciales";

           return $this->customResponse->is400Response($response,$responseMessage);
       }
       $verifyActive    = $this->verifyActive($email);
       
       #validacion para ver si el cliente se encuentra activo
       if($verifyActive==false){

           $responseMessage = "usuario inactivo";

           return $this->customResponse->is400Response($response , $responseMessage);
       }

       #enviar información usuario
       $getUsuario = $this->getUsuario($email);
       #recuperar menu del logueado
       //$getMenu = $this->getMenu($email);
       #generación de token
       $responseMessage = GenerateTokenController::generateToken($email);

       return $this->customResponse->is200ResponseLogin($response,$responseMessage , $getUsuario);
    }

    #function informacion del usuario
    public function getUsuario($email)
    {
        $usuario = $this->user->selectRaw('usuario_id, usuario_nombre_completo,usuario_fecha_registro,usuario_estado,usuario_correo,usuario_ruta_img,usuario_rol_principal')
                                ->where(["usuario_correo"=>$email])->get();
        return $usuario;
    }
    #function menu del cliente por correo
    /**public function getMenu($email)
    {
      $menu = $this->rol->findSidebarByRol($email);

      return $menu;
    } */


    #validar si el usuario se encuetra activo
    public function verifyActive($email){

        $active = "";

        $user = $this->user->where(["usuario_correo"=>$email])->get();

        foreach($user as $key)
        {
            $active = $key->usuario_estado;
        }
        if($active==0)
        {
            return false;
        }
            return true;
        
    }

    #validar email y contraseña de cliente
    public function verifyAccount($password,$email)
    {
        $hashPassword ="";
        //valida si existe el correo
        $count = $this->user->where(["usuario_correo"=>$email])->count();
        //encuentra error retorna false //salta error en sus credenciales
        if($count==false)
        {
            return false;
        }
        //trae información del cliente 
        $user = $this->user->where(["usuario_correo"=>$email])->get();
        //asigna el resultado de la contraseña a $hashpassword
        foreach ($user as $users)
        {
            $hashPassword = $users->usuario_contrasena;
             
        }
        //validación de la contraseña
        $verify = $this->equals_password($password,$hashPassword);
        //retorna false para que salte error en credenciales
        if($verify==false)
        {
            return false;
        }

        return true;
    }

    //validar si es igual la contraseña
    public function equals_password($password , $hashPassword)
    {
        if(md5($password) == $hashPassword)
        {
            return true;
        }else{
            return false;
        }

    }

}

?>