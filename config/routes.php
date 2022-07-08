<?php
 
 

$app->group("/auth",function() use ($app){

    $app->post("/login","AuthController:Login");
    $app->post("/register","AuthController:Register");
    $app->get("/validate/{jwt}" , "AuthController:Validate");
});

$app->group("/reportes" , function() use ($app){
    $app->post("/puntosxempresa" , "ReportesController:findByEmpresasPuntos");
});

$app->group("/aliados_merkas" , function() use ($app){
    $app->get("/all" , "ReportesController:getAll");
});
 
 