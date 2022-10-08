<?php
/**CREATE TABLE "merkas_app"."settings" (
  "settings_id" INT NOT NULL AUTO_INCREMENT,
  "settings_tipo" VARCHAR(45) NULL,
  "settings_valor" VARCHAR(200) NULL,
  "settings_estado" VARCHAR(45) CHARACTER SET 'ascii' NULL,
  PRIMARY KEY ("settings_id"));
 */

/**
 * hannilsolutions
 * sistemas@hannilsolutions.com
 * 2022-09-23
 */
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $timestamps = false;
    
    protected $table = "settings";

    protected $fillable = [
        "settings_id"  ,
        "settings_tipo"  ,
        "settings_valor"  ,
        "settings_estado"  ,
    ];
}

?>