<?php
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
     

};