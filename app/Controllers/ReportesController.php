<?php

namespace App\Controllers;

use App\Models\AliadosMerkas;
use App\Models\AliadosMerkasFacturas;
use App\Requests\CustomRequestHandler;
use App\Response\CustomResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Respect\Validation\Validator as v;
use App\Validation\Validator;



class ReportesController
{
    protected $customResponse;

    protected $aliadoMerkas;

    protected $validator;

    public function __construct()
    {
        $this->customResponse = new CustomResponse();

        $this->aliadoMerkas = new AliadosMerkas();

        $this->aliadosMerkasFacturas = new AliadosMerkasFacturas();

        $this->validator = new Validator();
    }

    #POST PUNTOS REPARTIDOS
    #ID, NIT, ALIADO, DIRECCION, MUNICIPIO, DEPARTAMENTO, VALOR REGISTRADO, PORCENTAJE DE NEGOCIACIÓN, FECHA O PERIODO.
    public function findByEmpresasPuntos(Request $request , Response $response)
    {
        $this->validator->validate($request , [

            "valor1" => v::notEmpty(),
            "valor2" => v::notEmpty()
        ]);

        if($this->validator->failed())
        {
            $responseMenssage = $this->validator->errors;

            return $this->customResponse->is200Response($response , $responseMenssage);
        }

        $getFindByEmpresaPuntos = $this->aliadoMerkas->selectRaw(
            "aliados_merkas.aliado_merkas_id,
            aliados_merkas.aliado_merkas_nit,
            aliados_merkas.aliado_merkas_dv,
            aliados_merkas.aliado_merkas_estado,
            aliados_merkas.aliado_merkas_nombre,
            aliados_merkas.aliado_merkas_regimen_contributivo,
            aliados_merkas.aliado_merkas_tipo,
            aliados_merkas.aliado_merkas_rep_legal_nombre,
            aliados_merkas.aliado_merkas_rep_legal_apellido,
            aliados_merkas.aliado_merkas_rep_legal_numero_documento,
            aliados_merkas.aliado_merkas_rep_legal_correo,
            aliados_merkas.aliado_merkas_rep_legal_telefono,
            aliados_merkas_sucursales.aliado_merkas_sucursal_principal,
            aliados_merkas_sucursales.aliado_merkas_sucursal_direccion,
            aliados_merkas.aliado_merkas_rango_credito,
            aliados_merkas.aliado_merkas_rango_efectivo,
            municipios.municipio_nombre,
            departamentos.departamento_nombre,
            sum(aliados_merkas_facturas.aliado_merkas_factura_puntos_repartidos) as puntos
            ")->leftjoin("aliados_merkas_sucursales" , "aliados_merkas_sucursales.aliado_merkas_id" , "=" , "aliados_merkas.aliado_merkas_id")
            ->leftjoin("aliados_merkas_facturas"  , "aliados_merkas_facturas.aliado_merkas_sucursal_id", "=" , "aliados_merkas_sucursales.aliado_merkas_sucursal_id" )
            ->leftjoin("municipios" , "municipios.municipio_id" , "=" , "aliados_merkas_sucursales.municipio_id" )
            ->leftjoin("departamentos" , "departamentos.departamento_id" , "=" , "municipios.departamento_id")
            ->where('aliados_merkas_facturas.aliado_merkas_factura_estado' , '=' , '1')
            ->whereBetween('aliados_merkas_facturas.aliado_merkas_factura_fecha_registro' ,[CustomRequestHandler::getParam($request , "valor1") , CustomRequestHandler::getParam($request , "valor2")])
            ->groupBy("aliados_merkas_facturas.aliado_merkas_sucursal_id")
            ->get();

            $this->customResponse->is200Response($response , $getFindByEmpresaPuntos);
    }

    public function getAll(Request $request , Response $response)
    {
        $getAll = $this->aliadoMerkas->get();

        $this->customResponse->is200Response($response , $getAll);
    }

    
    public function puntosRepartidos(Request $request , Response $response)
    {
        $this->validator->validate($request , [
            "valor1" => v::notEmpty(),
            "valor2" => v::notEmpty(),
        ]);

        if($this->validator->failed())
        {
            $responseMenssage = $this->validator->errors;

            return $this->customeResponse->is400Response($response , $responseMenssage);
        }

        $getPuntosRepartidos = $this->aliadosMerkasFacturas->selectRaw("
                    aliados_merkas_facturas.aliado_merkas_sucursal_id,
                    aliados_merkas_facturas.aliado_merkas_factura_total_merkas,
                    sum(aliados_merkas_facturas.aliado_merkas_factura_puntos_repartidos) as puntos
                    aliados_merkas_sucursales.aliado_merkas_sucursal_telefono,
                    aliados_merkas_sucursales.aliado_merkas_sucursal_direccion,
                    aliados_merkas_sucursales.aliado_merkas_sucursal_principal,
                    aliados_merkas.aliado_merkas_nombre,
                    aliados_merkas.aliado_merkas_nit,
                    aliados_merkas.aliado_merkas_dv,
                    aliados_merkas.aliado_merkas_estado,
                    aliados_merkas.aliado_merkas_regimen_contributivo,
                    aliados_merkas.aliado_merkas_rep_legal_nombre,
                    aliados_merkas.aliado_merkas_rep_legal_apellido,
                    aliados_merkas.aliado_merkas_rep_legal_numero_documento,
                    aliados_merkas.aliado_merkas_rep_legal_correo,
                    aliados_merkas.aliado_merkas_rango_credito,
                    aliados_merkas.aliado_merkas_rango_efectivo,
                    municipios.municipio_nombre
                        ")->leftjoin("aliados_merkas_sucursales" ,"aliados_merkas_sucursales.aliado_merkas_sucursal_id" ,"=" ,"aliados_merkas_facturas.aliado_merkas_sucursal_id")
                        ->leftjoin("aliados_merkas" , "aliados_merkas.aliado_merkas_id" , "=" , "aliados_merkas_sucursales.aliado_merkas_id")
                        ->leftjoin("munipicios" , "municipios.municipio_id" , "=" , "aliados_merkas_sucursales.municipio_id")
                        ->where('aliados_merkas_facturas.aliado_merkas_factura_estado' , '=' , '1')
                        ->whereBetween('aliados_merkas_facturas.aliado_merkas_factura_fecha_registro' ,[CustomRequestHandler::getParam($request , "valor1") , CustomRequestHandler::getParam($request , "valor2")])
                         
                        ->get();

        $this->customResponse->is200Response($response , $getPuntosRepartidos);
    }
}

?>