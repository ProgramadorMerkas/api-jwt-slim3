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

        $this->validator = new Validator();
    }

    /*
    *
    */
    public function save(Request $request , Response $response)
    {
        

        $this->facturasAnuladasLog->create([
        "aliado_merkas_factura_id" => CustomRequestHandler::getParam($request, "aliado_merkas_factura_id"),
        "tipo" => CustomRequestHandler::getParam($request , "tipo"),
        "factura_anulada_log_fecha" => date("Y-m-d"),
        "aliado_merkas_factura_total_merkas" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_total_merkas") ,
        "aliado_merkas_factura_puntos_repartidos" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_puntos_repartidos") ,
        "aliado_merkas_factura_total_con_iva" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_total_con_iva") ,
        "aliado_merkas_factura_total_sin_iva" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_total_sin_iva") ,
        "aliado_merkas_factura_pago_efectivo" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_pago_efectivo") ,
        "aliado_merkas_factura_numero"      =>  CustomRequestHandler::getParam($request , "aliado_merkas_factura_numero") ,
        "aliado_merkas_factura_pago_tarjeta" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_pago_tarjeta") ,
        "aliado_merkas_factura_pago_merkash" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_pago_merkash") ,
        "usuario_id" => CustomRequestHandler::getParam($request , "usuario_id"),
        ]);

         return true;
    }

/*
*ENDPOINT GET findbyid aliado_merkas_factura_id
*/
    public function findByFacturasId(Request $request , Response $response , $id)
    {
        $getFindByFacturasId =  $this->facturasAnuladasLog->where(["aliado_merkas_factura_id" => $id])->get();

        $this->customResponse->is200Response($response , $getFindByFacturasId);
    }


 
 }

?>