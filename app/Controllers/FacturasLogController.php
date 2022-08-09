<?php

namespace App\Controllers;

use App\Models\FacturasAnuladasLog;
use App\Requests\CustomRequestHandler;
use App\Response\CustomResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Respect\Validation\Validator as v;
use App\Validation\Validator;



class FacturasLogController
{
    protected $customResponse;

    protected $facturasAnuladasLog;

    protected $validator;

    public function __construct()
    {
        $this->customResponse = new CustomResponse();

        $this->facturasAnuladasLog = new FacturasAnuladasLog();


    }

    /*
    *ENPOINT POST save info log
    */
    public function save(Request $request , Response $response)
    {
        $this->validator->validate($request , [
            "aliado_merkas_factura_id" => v::notEmpty(),
            "factura_anulada_log_puntos" => v::notEmpty(),
            "factura_anulada_log_merkash" => v::notEmpty(),
            "usuario_id" => v::notEmpty(),
            "tipo" => v::notEmpty(),
            "factura_anulada_log_fecha" => date("Y-m-d")
        ]);

        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;

            return $this->customeResponse->is400Response($response , $responseMessage);
        }

        $responseMessage = "creado";

        $this->customResponse->is200Response($response , $responseMessage);
    }

/*
*ENDPOINT GET findbyid aliado_merkas_factura_id
*/
    public function findByFacturasId(Request $request , Response $response , $id)
    {
        $getFindByFacturasId =  new $this->facturasAnuladasLog->where(["aliado_merkas_factura_id" => $id])->get();

        $this->customResponse->is200Response($response , $getFindByFacturasId);
    }


 
 }

?>