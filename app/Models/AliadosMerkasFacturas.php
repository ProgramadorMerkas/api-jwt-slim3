<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class AliadosMerkasFacturas extends Model
{

    protected $table = "aliados_merkas_facturas";

    protected $fillable = [
        "aliado_merkas_factura_id",
        "usuario_id",
        "aliado_merkas_empleado_id",
        "aliado_merkas_sucursal_id",
        "aliado_merkas_factura_fecha_registro",
        "aliado_merkas_factura_estado",
        "aliado_merkas_factura_numero",
        "aliado_merkas_factura_pago_efectivo",
        "aliado_merkas_factura_pago_tarjeta",
        "aliado_merkas_factura_total_sin_iva",
        "aliado_merkas_factura_total_con_iva",
        "aliado_merkas_factura_puntos_repartidos",
        "aliado_merkas_factura_total_merkas",
        "metas_facturacion_merkas_id",
        "aliado_merkas_factura_pago_merkas",
        "aliado_merkas_factura_pago_bonificacion_merkash"


    ];
}

?>