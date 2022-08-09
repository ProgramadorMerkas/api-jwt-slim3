<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class FacturasAnuladasLog extends  Model
{

    protected $table="facturas_anuladas_log";
    
    protected $fillable = [ 
        "factura_anulada_log_id",
        "aliado_merkas_factura_id",
        "factura_anulada_log_puntos",
        "factura_anulada_log_merkash",
        "usuario_id",
        "tipo",
        "factura_anulada_log_fecha",
        "aliado_merkas_factura_total_merkas",
        "aliado_merkas_factura_puntos_repartidos",
        "aliado_merkas_factura_total_con_iva",
        "aliado_merkas_factura_total_sin_iva",
        "aliado_merkas_factura_pago_efectivo",
        "aliado_merkas_factura_numero",
        "aliado_merkas_factura_pago_tarjeta",
        "aliado_merkas_factura_pago_merkash",
        "created_at",
        "updated_at"
    ];

}

?>