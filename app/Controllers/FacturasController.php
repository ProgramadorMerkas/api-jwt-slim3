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



class FacturasController
{

	protected $customResponse;

	protected $aliadosMerkasFactura;

	protected $validator;

	public function __construct()
	{
		$this->customResponse = new CustomResponse();

		$this->aliadosMerkasFactura = new AliadosMerkasFacturas();

		$this->facturasAnuladasLog = new FacturasAnuladasLog();

		$this->validator = new Validator();

		$this->usuario = new UsuariosController();
	}

	 
	/**
	 * ENDPOINT GET buscar factura por id
	 * */
	public function findById(Request $request , Response $response , $id)
	{

		$getFindById = $this->aliadosMerkasFactura->selectRaw(
						   "aliados_merkas_facturas.aliado_merkas_factura_id , 
							aliados_merkas_facturas.usuario_id,
							aliados_merkas_facturas.aliado_merkas_factura_estado,
							aliados_merkas_facturas.aliado_merkas_factura_fecha_registro,
							aliados_merkas_facturas.aliado_merkas_factura_puntos_repartidos,
							aliados_merkas_facturas.aliado_merkas_factura_pago_efectivo,
							aliados_merkas_facturas.aliado_merkas_factura_pago_tarjeta,
							aliados_merkas_facturas.aliado_merkas_factura_pago_merkash,
							aliados_merkas_facturas.aliado_merkas_factura_total_merkas,
							aliados_merkas_facturas.aliado_merkas_factura_total_con_iva,
							aliados_merkas_facturas.aliado_merkas_factura_total_sin_iva,
							aliados_merkas_facturas.aliado_merkas_factura_numero,							
							usuarios.usuario_nombre_completo,  
							usuarios.usuario_estado,
							usuarios.usuario_telefono,
							usuarios.usuario_correo,
							aliados_merkas.aliado_merkas_nombre,
							aliados_merkas.aliado_merkas_nit,
							aliados_merkas.aliado_merkas_estado,
							aliados_merkas_sucursales.aliado_merkas_sucursal_direccion,
							aliados_merkas_empleados.aliado_merkas_empleado_estado,
							aliados_merkas_empleados.aliado_merkas_empleado_tipo,
							empleado.usuario_nombre_completo as aliado_merkas_empleado_nombre
							")->leftjoin("usuarios" , "aliados_merkas_facturas.usuario_id" , "=" , "usuarios.usuario_id")
								->leftjoin("aliados_merkas_empleados" , "aliados_merkas_empleados.aliado_merkas_empleado_id" , "=" , "aliados_merkas_facturas.aliado_merkas_empleado_id" )
								->leftjoin("usuarios as empleado" , "aliados_merkas_empleados.usuario_id" , "=" , "empleado.usuario_id")
								->leftjoin("aliados_merkas_sucursales" , "aliados_merkas_sucursales.aliado_merkas_sucursal_id" , "=" , "aliados_merkas_facturas.aliado_merkas_sucursal_id")
								->leftjoin("aliados_merkas" , "aliados_merkas.aliado_merkas_id" , "=" , "aliados_merkas_sucursales.aliado_merkas_id")
								->where(["aliados_merkas_facturas.aliado_merkas_factura_id" => $id])
								->get();
		$this->customResponse->is200Response($response , $getFindById);
	}

	/*
	*ENDPOINT PATCH update
	*/
	public function anularFactura(Request $request , Response $response , $id)
	{
		$this->validator->validate($request , [ 
        "aliado_merkas_factura_id" => v::notEmpty(),
        "tipo" => v::notEmpty() , 
        "aliado_merkas_factura_total_merkas" => v::notEmpty() ,
        "aliado_merkas_factura_puntos_repartidos" => v::notEmpty(),
        "aliado_merkas_factura_total_con_iva" => v::notEmpty(),
        "aliado_merkas_factura_total_sin_iva" => v::notEmpty(),
        "aliado_merkas_factura_pago_efectivo" => v::notEmpty(),
        "aliado_merkas_factura_numero" => v::notEmpty(),
        "aliado_merkas_factura_pago_tarjeta" => v::notEmpty(),
        "aliado_merkas_factura_pago_merkash" => v::notEmpty(),
		]);

		if($this->validator->failed())
		{
			$responseMessage = $this->validator->errors;

			return $this->customResponse->is400Response($response , $responseMessage);
		}

		$this->aliadosMerkasFactura->where(["aliado_merkas_factura_id" => $id])->update([
		"aliado_merkas_factura_total_merkas" => 0 ,
        "aliado_merkas_factura_puntos_repartidos" => 0,
        "aliado_merkas_factura_total_con_iva" => 0,
        "aliado_merkas_factura_total_sin_iva" => 0,
        "aliado_merkas_factura_pago_efectivo" => 0,
        "aliado_merkas_factura_numero" => 0,
        "aliado_merkas_factura_pago_tarjeta" => 0,
        "aliado_merkas_factura_pago_merkash" => 0,
		]);
		#Tipo => ["factura anulada" , "eliminando puntos abuelo" , "eliminando puntos padre" , "eliminando puntos hijo" , "devolviendo merkash hijo" , 
		# ]
		$this->facturasAnuladasLog->create($request , [
		"aliado_merkas_factura_id" => CustomRequestHandler::getParam($request, "aliado_merkas_factura_id"),
		"tipo" => CustomRequestHandler::getParam($request , "tipo"),
		"factura_anulada_log_fecha" => date("Y-m-d"),
		"aliado_merkas_factura_total_merkas" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_total_merkas") ,
        "aliado_merkas_factura_puntos_repartidos" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_puntos_repartidos") ,
        "aliado_merkas_factura_total_con_iva" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_total_con_iva") ,
        "aliado_merkas_factura_total_sin_iva" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_total_sin_iva") ,
        "aliado_merkas_factura_pago_efectivo" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_total_merkas") ,
        "aliado_merkas_factura_numero" 		=>	CustomRequestHandler::getParam($request , "aliado_merkas_factura_numero") ,
        "aliado_merkas_factura_pago_tarjeta" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_pago_tarjeta") ,
        "aliado_merkas_factura_pago_merkash" => CustomRequestHandler::getParam($request , "aliado_merkas_factura_pago_merkash") ,
		]);

		$responseMessage = "factura anulada";

		$this->customResponse->is200Response($response , $responseMessage);
	}
	

 }

?>


							 
							
 