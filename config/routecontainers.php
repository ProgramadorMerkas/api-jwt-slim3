<?php
/**
 * Hannilsolutions
 * sistemas@hannilsolutions.com
 * 2022-09-23
 */
return function($container)
{
    $container["GuestEntryController"] = function()
    {
        return new \App\Controllers\GuestEntryController;
    };

    $container["AuthController"] = function()
    {
      return new \App\Controllers\AuthController;
    };

    $container["ReportesController"] = function()
    {
      return new \App\Controllers\ReportesController;
    };

    $container["UsuariosController"] = function()
    {
      return new \App\Controllers\UsuariosController;
    };
    
    $container["FacturasController"] = function()
    {
      return new \App\Controllers\FacturasController;
    };

    $container["FacturasLogController"] = function()
    {
      return new \App\Controllers\FacturasLogController;
    };

    $container["ExternalController"] = function ()
    {
      return new \App\Controllers\ExternalController;
    };

    $container["UploadsController"] = function()
    {
      return new \App\Controllers\UploadsController;
    };

};