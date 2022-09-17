<?php

namespace App\Controllers;

  
use App\Models\Usuarios;
use App\Requests\CustomRequestHandler;
use App\Response\CustomResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Respect\Validation\Validator as v;
use App\Validation\Validator;



class ExternalController
{

	protected $usuario;

	protected $customeResponse;

	protected $validator;

	public function __construct()
	{
		$this->usuario = new Usuarios();

		$this->customeResponse = new CustomResponse();

		$this->validator = new Validator();

	}

	/**
	 * ENDPOINT POST findByReferido
	 * */
	public function findReferidoByCell(Request $request , Response $response)
	{
		$this->validator->validate($request , [
			"token" => v::notEmpty(),
			"celular" => v::notEmpty(),
		]);

		if ($this->validator->failed()) {

			$responseMessage = $this->validator->errors;

			return $this->customeResponse->is400Response($response , $responseMessage);
		}

		if(!$this->validateToken(CustomRequestHandler::getParam($request , "token")))
		{
			$responseMessage = "token no valido";

			return $this->customeResponse->is400Response($response , $responseMessage);
		}
 		
 		$celular = CustomRequestHandler::getParam($request , "celular");

		$getFindReferido = $this->usuario->where("usuario_telefono" , "like" , "%$celular%")->get();

		$this->customeResponse->is200Response($response , $getFindReferido);
	}

	/**
	 * ENDPOINT POST findReferidoByMail*/
	public function findReferidoByMail(Request $request , Response $response)
	{
		$this->validator->validate($request , [
			"token" => v::notEmpty(),
			"correo" => v::notEmpty(),
		]);

		if ($this->validator->failed()) {

			$responseMessage = $this->validator->errors;

			return $this->customeResponse->is400Response($response , $responseMessage);
		}

		if(!$this->validateToken(CustomRequestHandler::getParam($request , "token")))
		{
			$responseMessage = "token no valido";

			return $this->customeResponse->is400Response($response , $responseMessage);
		}

		$correo = CustomRequestHandler::getParam($request , "correo");

		$getFindReferido = $this->usuario->where("usuario_correo" , "like" , "%$correo%")->get();

		$this->customeResponse->is200Response($response , $getFindReferido);
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