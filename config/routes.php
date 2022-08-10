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

    $app->get("/referidos" , "ReportesController:referidos");
});

$app->group("/usuarios" , function() use ($app){
    $app->get("/list" , "UsuariosController:list");
    $app->get("/abueloPadreHijoFindById/{id}" , "UsuariosController:abueloPadreHijoFindById");
    $app->patch("/updateMerkashUsuario/{id}" , "UsuariosController:updateMerkashUsuario");
});

$app->group("/facturas" , function() use ($app){
    $app->get("/findById/{id}" , "FacturasController:findById");
    $app->patch("/anularFactura/{id}" , "FacturasController:anularFactura");
});

$app->group("/facturasLog" , function() use ($app){
    $app->get("/list/{id}" , "FacturasLogController:findByFacturasId");
});
 
 