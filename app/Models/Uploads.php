<?php
/**CREATE TABLE "merkas_app"."uploads" (
  "upload_id" INT NOT NULL AUTO_INCREMENT,
  "upload_categoria" VARCHAR(45) NULL,
  "upload_estado" VARCHAR(45) NULL,
  "upload_url" VARCHAR(45) NULL,
  "upload_fecha" DATETIME NULL,
  "upload_nombre" VARCHAR(45) NULL,
  "upload_tipo" VARCHAR(45) CHARACTER SET 'ascii' COLLATE 'ascii_general_ci' NULL,
  "referencia_id" INT NULL,
  PRIMARY KEY ("upload_id"));
   */
/**
 * hannilsolutions
 * sistemas@hannilsolutions.com
 * 2022-09-23
 */
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Uploads extends Model
{
    public $timestamps = false;
    
    protected $table = "uploads";

    protected $fillable = [
    "upload_id" ,
  "upload_categoria" ,
  "upload_estado" ,
  "upload_url" ,
  "upload_fecha" ,
  "upload_nombre" ,
  "upload_tipo" ,
  "referencia_id"
    ];
}

?>