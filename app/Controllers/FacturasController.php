<?php

namespace App\Controllers;

 
use App\Models\AliadosMerkasFacturas;
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

		$this->validator = new Validator();

		$this->usuario = new UsuariosController();
	}

	#GET aliado_merkas_factura_id 
	public function anularFactura(Request $request , Response  $response , $id)
	{
		
		#consultamos los datos de la factura mas el id_usuario
		$this->aliadosMerkasFactura->selectRaw(
			"aliado_merkas_factura_id , 
			aliado_merkas_factura_estado , 
			aliado_merkas_factura_pago_bonificacion_merkash , 
			aliado_merkas_factura_pago_merkash,
			usuario_id,
			aliado_merkas_factura_puntos_repartidos
			"
		)->where(["aliado_merkas_factura_id" => $id])->get();
		#asignar variables
		foreach($aliadosMerkasFactura as $item)
		{
			$id_hijo 				=  $item->usuario_id;

			$estado 				= $item->aliado_merkas_factura_estado;

			$bonificacion_merkash 	= $item->aliado_merkas_factura_pago_bonificacion_merkash;

			$pagos_merkash 			= $item->aliado_merkas_factura_pago_merkash;

			$puntos_repartidos 		= $item->aliado_merkas_factura_puntos_repartidos;

		}
		if($estado == 0)
		{
			$responseMessage = "recibo ya se encuentra anulado";

			return $this->customeResponse->is400Response($response , $responseMessage);
		}
		#traemos hijo, padre, abuelo
		$getHijoPAdreAbuelo = $this->usuario->abueloPadreHijoFindById($id_hijo);
		foreach($getHijoPAdreAbuelo  as $key)
		{
			
			$hijo_merkash 	= $key->hijo_merkash;
			$padre_id  		= $key->padre_id;
			$padre_puntos 	= $key->padre_puntos;
			$padre_merkash 	= $key->padre_merkash;
		}

		if($bonificacion_merkash == 0)
		{
			
		}
		
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

	

 }

?>


							 
							
 