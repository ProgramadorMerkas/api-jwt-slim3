<?php
/**
 * hannilsolutions
 * sistemas@hannilsolutions.com
 * 2022-09-23
 */
namespace App\Controllers;

use App\Models\Uploads;
use App\Models\Settings;
use App\Models\Usuario;
use App\Requests\CustomRequestHandler;
use App\Response\CustomResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Respect\Validation\Validator as v;
use App\Validation\Validator;
//use Psr\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadsController
{
    protected $customResponse;

    protected $uploads;

    protected $validator;

    protected $settings;

    protected $headersToken;

    protected $documento;

    protected $usuario;

    protected $errors;

    protected $resultado;

    public function __construct()
    {
        $this->customResponse = new CustomResponse();

        $this->uploads = new Uploads();

        $this->validator = new Validator();

        $this->settings = new Settings();

        $this->headersToken = new RequestHeadersController();

        $this->usuario = new Usuario();

        $this->errors = [];

        $this->resultado = [];


    }

    public function prueba(Request $request , Response $response)
    {
        /**
         * validation de del api-key
         */
        $this->headersToken->apitoken($request);

        if($this->headersToken->failed())
        {
            $responseMenssage = $this->headersToken->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);
        }

        $path = __DIR__;

        $this->customResponse->is200Response($response , $path);
    }

    /**
     * ENDPOINT POST uploadsFiles
     */
   public function uploads(Request $request , Response $response)
    {
        /**
         * validation de del api-key
         */
    $this->headersToken->apitoken($request);

        if($this->headersToken->failed())
        {
            $responseMenssage = $this->headersToken->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);
        }

        /**
         * validation de campos
         */
    $this->validator->validate($request , [ 
            "usuario" => v::notEmpty(),
            "categoria" => v::notEmpty()
        ]);
        
        if($this->validator->failed())
        {
            $responseMenssage = $this->validator->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);
        }

        /**
         * inicia carga de archivo
         */
        $destino = CustomRequestHandler::getParam($request , "categoria");

        $usuario = CustomRequestHandler::getParam($request , "usuario");

        //$responseMenssage = "error de carga";

        $uploadedFiles = $request->getUploadedFiles();

        $uploadedFile = $uploadedFiles['file_uploads'];

        if($uploadedFile->getError() === UPLOAD_ERR_OK)
        {
            $filename = $this->moveUploadedFile($destino,  $uploadedFile , $usuario);

            $responseMenssage = $filename;

        }

        $this->customResponse->is200Response($response , $responseMenssage);

        
    }

    /**
     * Move to files
     */

   public function moveUploadedFile($destino , $uploadedFile , $usuario)
     {

        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php

        $filename = sprintf('%s.%0.8s', $basename, $extension);


        $path      = $this->getPath($destino , $usuario);

        if (!is_dir($path)) {

            mkdir($path, 0777, true);
        }

        $uploadedFile->moveTo($path."/".$filename);

        $this->uploads->create([
            "upload_categoria" => $destino , 
            "upload_estado" => "cargado",
            "upload_url" => $path,
            "upload_fecha" => date("Y-m-d"),
            "upload_nombre" => $filename,
            "upload_tipo" => $extension,
            "referencia_id" => $usuario
        ]);

        return $filename;

     }

     /**
      * find url settings, updated con referencia_id
      */
  public function getPath($destino , $usuario)
     {
        $getPath = $this->settings->where("settings_tipo" , "=" , $destino)
                                    ->where("settings_estado" , "=" , "activo")->get();
        foreach($getPath as $item)
        {
            $path = $item->settings_valor.$usuario."/referidos";
        }

        return $path;
     }

     /**
      * ENDPOINT POST findByCategoria*/

     public function findByCategoria(Request $request , Response  $response)
     {
        /**
         * validation de del api-key
         */
     $this->headersToken->apitoken($request);

        if($this->headersToken->failed())
        {
            $responseMenssage = $this->headersToken->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);
        }

        /**
         * validation de campos
         */
     $this->validator->validate($request , [ 
            "upload_categoria" => v::notEmpty(),
            "referencia_id" => v::notEmpty()
        ]);
        
        if($this->validator->failed())
        {
            $responseMenssage = $this->validator->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);
        }

        $categoria = CustomRequestHandler::getParam($request , "upload_categoria");

        $referencia = CustomRequestHandler::getParam($request , "referencia_id");

        $getFindByCategoria = $this->uploads->where("upload_categoria" , "=" , $categoria)
                                                ->where("referencia_id" , "=" , $referencia)
                                                ->get();
        $this->customResponse->is200Response($response , $getFindByCategoria);


     }

     /**
      * ENDPOINT Procesar datos cargados*/

 public function referidosCargue(Request $request , Response $response)
     {

        /**
         * validation de del api-key
         */
        $this->headersToken->apitoken($request);

        if($this->headersToken->failed())
        {
            $responseMenssage = $this->headersToken->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);
        }
              

        try{

            $this->recorrerArchivo($request);

            if($this->failed())
            {
                $responseMenssage = $this->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);

            }

            $this->uploads->where("upload_id" , "=" , CustomRequestHandler::getParam($request , "upload_id"))->update([
            "upload_estado" => "procesado"]);

            $responseMenssage = "cargado";

            $this->customResponse->is200Response($response , $responseMenssage);

        }catch(\Illuminate\Database\QueryException $exception){

            $this->errors["updated_file"] = $exception->errorInfo;

            if($this->failed())
            {
                $responseMenssage = $this->errors;

            return $this->customResponse->is400Response($response , $responseMenssage);
            }
        }
     }
     /**
      * function para recoerreg el archivo*/

     public function recorrerArchivo($request){


        try {
            /**
             * url /home/internet/etc*/
          $url = CustomRequestHandler::getParam($request , "upload_url");
            //name archivo 09j1oiniubca9s8.xlsx
            $filename = CustomRequestHandler::getParam($request , "upload_nombre");
            //se concatena path archivo
            $path = $url."/".$filename;
            //carga de clase IOFactory
            $this->documento = IOFactory::load($path);
            //inicializa hoja
            $totalHojas = 1;    
            //inicializa validacion
            $validacion = 1;
            //inicializa temp
            $temp = array();
            //inicializada j
            $j = 1;
            //loop para recorrer hojas
            for ($i=0; $i < $totalHojas; $i++) { 
                //como solo es una se agrega a 1
                //hoja seleccionada 1
                $hojaActual = $this->documento->getSheet($i);

                //recorremos Fila de la hoja seleccionada
              foreach($hojaActual->getRowIterator() as $fila)
                {
                    #celdaxcelda
                    //recorremos celdas de la fila inicial
                     
                    foreach ($fila->getCellIterator() as $celda) {
                        #asignamos el valor a la variable $valor
                        $valor = $celda->getValue();
                        
                        #si el valor es nul, continuarl con la siguiente fila
                        if($valor == NULL){
                            break;
                        }else if($valor == 'Celular')
                        {
                            break;
                        }else{

                             $temp[$j] = $valor;

                            $j++;
                        }
                       
                       
                    }
                    #salvar
                 
                   $save = $this->saveReferido($temp , $request);
                    //var_dump($temp);
                
                
                    $j = 1 ;
                }  
            }

 

        }catch(Exception $e)
        {
            $this->errors['travel_file'] = $e->getMessage();
        } 
    }

     /**
      * function para almacenar en usuarios*/
   public function saveReferido($temp , $request)
     {
          
          if(!empty($temp))
         { 
            $validateExist = $this->usuario->where("usuario_telefono" , "=" , $temp[1])->count();

            if($validateExist > 0)
            {
                $path = CustomRequestHandler::getParam($request , "upload_url");

                $file = fopen($path."/resultado.txt" , "a");

                fwrite($file , $temp[1]." telefóno ya registrado".PHP_EOL); 

                fclose($file);

                return false;
            }

            
        
        #guardar
       try{

        $this->usuario->create([
            "usuario_codigo" => $this->GenerarCodigo(10),
            "usuario_telefono" => $temp[1],
            "municipio_id" => $temp[2],
            "usuario_estado" => 1,
            "usuario_id_padre" => CustomRequestHandler::getParam($request , "referencia_id"),
            "usuario_rol_principal" => "CONSUMIDOR",
            "usuario_ruta_img" => "assets/media/users/default.jpg",
            "usuario_correo"    => " ",
            "usuario_fecha_registro" => date("Y-m-d"),
            "usuario_status" => "ROOKIE",
            "usuario_puntos" => 0,
            "usuario_merkash" => 0,
            "usuario_contrasena" => md5("merkas2020")


        ]); 

       }catch(\Illuminate\Database\QueryException $exception)
       {
         $this->errors['dataBase'] = $exception->errorInfo;
       }

        
    }

     }
     /**
      * Generar codigo de cliente*/

    public function GenerarCodigo($longitud) {
     $key = '';
     $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
     $max = strlen($pattern)-1;
     for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
     return $key;
    } 

    /**
     * recuperar error*/
    public function failed()
    {
        return !empty($this->errors);
    }

}

?>