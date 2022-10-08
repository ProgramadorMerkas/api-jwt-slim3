<?php
/**
 * hannilsolutions
 * sistemas@hannilsolutions.com
 * 2022-09-30
 */
namespace App\Controllers;

 
use App\Models\Usuarios;
use App\Requests\CustomRequestHandler;
use App\Response\CustomResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Respect\Validation\Validator as v;
use App\Validation\Validator;



class UsuariosController
{   
    protected $usuario;

    protected $validator;

    protected $customResponse;

    protected $headersToken;

    public function __construct()
    {
        $this->usuario = new Usuarios();

        $this->customResponse = new CustomResponse();

        $this->validator =  new Validator();

        $this->headersToken = new RequestHeadersController();
    }

    /**
     * ENDPOINT GET usuarios
     */
    public function list(Request $request , Response $response )
    {
        $getUsuarios = $this->usuario->get();

        $this->customResponse->is200Response($response , $getUsuarios);
    }


    /*select 
    hijo.usuario_id as hijo_id, hijo.usuario_nombre_completo as hijo_nombre, 
    padre.usuario_id as padre_id, padre.usuario_nombre_completo as padre_nombre,
    abuelo.usuario_id as abuelo_id , abuelo.usuario_nombre_completo as abuelo_nombre
    from usuarios as hijo
    left join usuarios as padre on hijo.usuario_id_padre = padre.usuario_id
    left join usuarios as abuelo on padre.usuario_id_padre = abuelo.usuario_id

    where hijo.usuario_id = 928 632 894 2144 */

    /*
    *ENDPOINT GET
    */

    public function abueloPadreHijoFindById(Request $request , Response $response , $id)
    {
        $getAbueloPadreHijoFindById = $this->usuario->selectRaw("
            usuarios.usuario_id  as hijo_id,
            usuarios.usuario_nombre_completo as hijo_nombre,
            usuarios.usuario_puntos as hijo_puntos,
            usuarios.usuario_merkash as hijo_merkash,
            usuarios.usuario_ruta_img as hijo_img,
            padre.usuario_id as padre_id,
            padre.usuario_nombre_completo as padre_nombre,
            padre.usuario_puntos as padre_puntos,
            padre.usuario_merkash as padre_merkash,
            padre.usuario_ruta_img as padre_img,
            abuelo.usuario_id as abuelo_id,
            abuelo.usuario_nombre_completo as abuelo_nombre,
            abuelo.usuario_puntos as abuelo_puntos,
            abuelo.usuario_merkash as abuelo_merkash ,
            abuelo.usuario_ruta_img as abuelo_img
            ")->leftjoin("usuarios as padre" , "padre.usuario_id" , "=" , "usuarios.usuario_id_padre")
                ->leftjoin("usuarios as abuelo" , "abuelo.usuario_id" , "=" , "padre.usuario_id_padre")
        ->where(["usuarios.usuario_id" =>  $id])->get();

         $this->customResponse->is200Response($response , $getAbueloPadreHijoFindById);

    }

    /*
    *PATCH update merkash usuario
    */
    public function updateMerkashUsuario(Request $request , Response $response , $id)
    {
        $this->validator->validate($request , [
            "usuario_merkash" => v::notEmpty()
        ]);

        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;

            return $this->customResponse->is400Response($response , $responseMessage);
        }

        $setFacturaAnuladaLog = $this->facturasAnuladasLogController->save($request , $response );

        if (!$setFacturaAnuladaLog) {
            
            $responseMessage = "error creando log";

            return $this->customResponse->is400Response($response , $responseMessage);
        }
        #enviamos los nuevos merkash
        $this->usuario->where(["usuario_id" => $id])->update([
            "usuario_merkash" => CustomRequestHandler::getParam($request , "usuario_merkash"),
        ]);

        $responseMessage = "merkash Actualizado";

        $this->customResponse->is200Response($response , $responseMessage);
    }

    /*
    *PATCH updated contraseña
    */
    public function resetearPassword(Request $request , Response $response , $id)
    {
         $passwordDefault = md5("Merkas2022");

         $this->usuario->where(["usuario_id" => $id])->update(["usuario_contrasena" => $passwordDefault]);

         $responseMessage = "actualizado";

         $this->customResponse->is200Response($response , $responseMessage);
    }

    /** 
     * 
     * 
     * 
     * 
    */
    /**
     * ENDPOINT GET list
     * */
    public function findByCodigo(Request $request , Response $response , $id)
    {
         /**
         * validation de del api-key
         */ 
        $this->headersToken->apitoken($request);

        if($this->headersToken->failed())
        {
            $responseMenssage = $this->headersToken->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);
        }
 

        $getFindByCodigo = $this->usuario->where(["usuario_id_padre" => $id])->get();

       $this->customResponse->is200Response($response , $getFindByCodigo);
    }

    /*
    *ENDPOINT POST buscar por rol e id_padre
    **/
    public function findByRolAndIdPadre(Request $request , Response $response)
    {
         /**
         * validation de del api-key
         */ 
       $this->headersToken->apitoken($request);

        if($this->headersToken->failed())
        {
            $responseMenssage = $this->headersToken->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);
        }

        $this->validator->validate($request , [
            "id_padre" => v::notEmpty(),
            "rol"      => v::notEmpty()
        ]);

       if ($this->validator->failed()) {
            
            $responseMenssage = $this->validator->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);
        }

        $id_padre = CustomRequestHandler::getParam($request , "id_padre");

        $rol = CustomRequestHandler::getParam($request , "rol");

       $getCount = $this->usuario->where("usuario_id_padre" , "=" , $id_padre)->where("usuario_rol_principal" , "=" , $rol)->count();

        $this->customResponse->is200Response($response , $getCount);
 
    }


}


?>