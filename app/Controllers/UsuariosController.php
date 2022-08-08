<?php

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
    protected $customResponse;

    protected $usuario;

    protected $validator;

    public function __construct()
    {
        $this->customResponse = new CustomResponse();

        $this->usuario = new Usuarios();

        $this->validator = new Validator();
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
}


?>