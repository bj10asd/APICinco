<?php
	require 'Solicitudes.php';

    if($_SERVER['REQUEST_METHOD']=='POST'){
    	$datos = json_decode(file_get_contents("php://input"),true);

    	$emisor = $datos["emisor"];
    	$receptor = $datos["receptor"];

    	$NameTableEmisor = "Amigos_" . $emisor;
    	$NameTableReceptor = "Amigos_" . $receptor;

        $token_tabla = Solicitudes::getTokenUser($receptor);

        if($token_tabla){

            $token = $token_tabla["token"];

            $respuestaEnviarSolicitudEmisor = Solicitudes::EnviarSolicitud($NameTableEmisor,$receptor,2);
            $respuestaEnviarSolicitudReceptor = Solicitudes::EnviarSolicitud($NameTableReceptor,$emisor,3);//Inserta solicitud en tabla receptor

            if($respuestaEnviarSolicitudEmisor == -1){
                Solicitudes::CreateTable($NameTableEmisor);
                $respuestaEnviarSolicitudEmisor = Solicitudes::EnviarSolicitud($NameTableEmisor,$receptor,2);
            }

            if($respuestaEnviarSolicitudReceptor == -1){
                Solicitudes::CreateTable($NameTableReceptor);
                $respuestaEnviarSolicitudReceptor = Solicitudes::EnviarSolicitud($NameTableReceptor,$emisor,3);
            }
            if($respuestaEnviarSolicitudEmisor == 200 && $respuestaEnviarSolicitudReceptor == 200){
                Solicitudes::EnviarNotification($token,$emisor,'¡Alguien quiere conocerte!');
            }
        }
    }
?>