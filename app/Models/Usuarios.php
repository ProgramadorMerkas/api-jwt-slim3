<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = "usuarios";

    protected $fillable = [
        "usuario_id",
        "usuario_codigo",
        "usuario_id_padre",
        "usuario_fecha_registro",
        "usuario_rol_principal",
        "usuario_nombre_completo",
        "usuario_nombre",
        "usuario_apellido",
        "usuario_genero",
        "usuario_tipo_documento",
        "usuario_numero_documento",
        "usuario_correo",
        "usuario_telefono",
        "usuario_whatssap",
        "usuario_direccion",
        "municipio_id",
        "usuario_estado",
        "usuario_status",
        "usuario_puntos",
        "usuario_merkash",
        "usuario_contrasena",
        "usuario_terminos",
        "usuario_ruta_img",
        "usuario_token_contrasena",
        "usuario_token_fecha",
        "usuario_token_merkash",
        "usuario_token_merkash_fecha",
        "usuario_bienvenida",
        "usuario_latitud",
        "usuario_longitud"
    ];
}


?>