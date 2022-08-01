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

        $this->customResponse($response , $getUsuarios);
    }
}


?>