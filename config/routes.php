<?php

#$app->post("/create-guest","GuestEntryController:createGuest");
 

$app->group("/auth",function() use ($app){

    $app->post("/login","AuthController:Login");
    $app->post("/register","AuthController:Register");
    $app->get("/validate/{jwt}" , "AuthController:Validate");
});

$app->group("/reportes" , function() use ($app){
    $app->post("/puntosxempresa" , "ReportesController:findByEmpresasPuntos");
    //$app->post("/puntosrepartidos" , "ReportesController:puntosRepartidos");
    $app->post("/puntosxusuario" , "ReportesController:findByUsuariosPuntosRepartidos");
});

$app->group("/usuarios" , function() use ($app){
    $app->get("/list" , "UsuariosController:list");
});
 
 