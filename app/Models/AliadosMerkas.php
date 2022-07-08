<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class AliadosMerkas extends  Model
{

    protected $table="aliados_merkas";
    
    protected $fillable = [
        "aliado_merkas_id" ,
        "usuario_id",
        "desarrollador_id",
        "aliado_merkas_id_padre",
        "aliado_merkas_rango_id",
        "aliado_merkas_rango_efectivo",
        "aliado_merkas_rango_credito",
        "aliado_merkas_fecha_registro",
        "aliado_merkas_nit",
        "aliado_merkas_dv",
        "aliado_merkas_estado",
        "aliado_merkas_nombre",
        "aliado_merkas_descripcion",
        "aliado_merkas_website",
        "aliado_merkas_facebook",
        "aliado_merkas_twitter",
        "aliado_merkas_youtube",
        "aliado_merkas_instagram",
        "aliado_merkas_regimen_contributivo",
        "aliado_merkas_tipo",
        "aliado_merkas_rep_legal_nombre",
        "aliado_merkas_rep_legal_apellido",
        "aliado_merkas_rep_legal_genero",
        "aliado_merkas_rep_legal_tipo_documento",
        "aliado_merkas_rep_legal_numero_documento",
        "aliado_merkas_rep_legal_correo",
        "aliado_merkas_rep_legal_telefono",
        "aliado_merkas_rep_legal_cargo",
        "aliado_merkas_rep_legal_direccion",
        "municipio_id",
        "aliado_merkas_ruta_img_portada"
    ];

}

?>