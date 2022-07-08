<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class AliadosMerkasSucursales extends Model{

    protected $table = "aliados_merkas_sucursales";

    protected $fillable = [
        "aliado_merkas_sucursal_id",
        "aliado_merkas_id",
        "aliado_merkas_sucursal_fecha_registro",
        "aliado_merkas_sucursal_principal",
        "aliado_merkas_sucursal_correo",
        "aliado_merkas_sucursal_direccion",
        "municipio_id",
        "aliado_merkas_sucursal_latitud",
        "aliado_merkas_sucursal_longitud",
        "aliado_merkas_sucursal_horario_lunes_inicio",
        "aliado_merkas_sucursal_horario_lunes_fin",
        "aliado_merkas_sucursal_horario_sabado_inicio",
        "aliado_merkas_sucursal_horario_sabado_fin",
        "aliado_merkas_sucursal_horario_festivos_inicio",
        "aliado_merkas_sucursal_horario_festivos_fin",
        "aliado_merkas_sucursal_telefono",
        "aliado_merkas_sucursal_whatssap",
        "aliado_merkas_sucursal_domicilio"
    ];
}


?>