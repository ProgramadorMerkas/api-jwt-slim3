<?php

namespace App\Controllers;

 
use App\Models\AliadosMerkasFacturas;
use App\Models\FacturasAnuladasLog;
use App\Requests\CustomRequestHandler;
use App\Response\CustomResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Respect\Validation\Validator as v;
use App\Validation\Validator;



class ExternalController
{

	protected usuario;

	protected customeResponse;

	protected validator;

	public function __construct()
	{
		$this->usuario = new Usuarios();

		$this->customeResponse = new CustomResponse();

		$this->validator = new Validator();

	}

	/**
	 * ENDPOINT POST findByReferido
	 * */
	public function findByReferido(Request $request , Response $response)
	{
		$this->validator->validate($request , [
			"token" => v::notEmpty()
		]);

		if ($this->validator->failed()) {

			$responseMessage = $this->validator->errors;

			return $this->customeResponse->is400Response($response , $responseMessage);
		}

		if($this->validateToken(CustomRequestHandler::getParam($request , "token")))
		{
			$responseMessage = "token no valido";

			return $this->customeResponse->is400Response($response , $responseMessage);
		}

		$correo = CustomRequestHandler::getParam($request , "correo");

		$celular  = CustomRequestHandler::getParam($request , "celular");
		$getFindReferido = $this->usuario->orWhere('usuario_telefono' , 'like' , "%$celular%")
										->orWhere('usuario_correo' , 'like' , "%$correo%")
										->get();

		$this->customResponse->is200Response($response , $getFindReferido);
	}

	public function validateToken($token)
	{
		if($token == "46fa959d22ac949e35bfeeaed5ff7b77")
		{
			return true;
		}
		return false;
	}

}

?>