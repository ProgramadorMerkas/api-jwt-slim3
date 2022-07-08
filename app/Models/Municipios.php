<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Municipios
{
    protected $table = "municipios";

    protected $fillable = [
        "municipio_id",
        "departamento_id",
        "municipio_codigo",
        "municipio_nombre"
    ];
}