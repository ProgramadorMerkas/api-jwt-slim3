<?php

namespace App\Controllers; 

class RequestHeadersController
{
    public $errors = [];

    public  function apitoken( $request)
    {
        $apiToken = $request->getHeader('api-key');
        
        if(empty($apiToken))
        {
            $this->errors["error"] = "api-key not found";
 

        }else{
            
            
            if(!$this->validateApitoken($apiToken[0]))
            {
                $this->errors["api-key"] = "key incorrecto";

                 
            }
        }

        return $this;

 
    }

    private function validateApitoken($apitoken)
    {
        if($apitoken == API_TOKEN)
        {
            return true;
        }

        return false;
    }

    public function failed()
    {
        return !empty($this->errors);
    }
}


?>