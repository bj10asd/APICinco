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
            $token=$token_tabla["token"];
            $respuestaEnviarSolicitudEmisor = Solicitudes::ActualizarDatos($NameTableEmisor,$receptor,4,3);//2 el que emite
            $respuestaEnviarSolicitudReceptor = Solicitudes::ActualizarDatos($NameTableReceptor,$emisor,4,2);//Inserta solicitud en tabla receptor, 3 el que recibe

            if($respuestaEnviarSolicitudEmisor == -1){
                echo json_encode(array('resultado' => 'Error de solicitud'));
            }

            if($respuestaEnviarSolicitudReceptor == -1){
                echo json_encode(array('resultado' => 'Error de solicitud'));
            }
            if($respuestaEnviarSolicitudEmisor == 200 && $respuestaEnviarSolicitudReceptor == 200){
                Solicitudes::EnviarNotification($token,$emisor,'Ya puedes chatear con '.$emisor);
            }   
        }

    	
    }
?>