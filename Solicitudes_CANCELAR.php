<?php
	require 'Solicitudes.php';

    if($_SERVER['REQUEST_METHOD']=='POST'){
    	$datos = json_decode(file_get_contents("php://input"),true);

    	$emisor = $datos["emisor"];
    	$receptor = $datos["receptor"];

    	$NameTableEmisor = "Amigos_" . $emisor;
    	$NameTableReceptor = "Amigos_" . $receptor;

    	$respuestaEnviarSolicitudEmisor = Solicitudes::EliminarSolicitud($NameTableEmisor,$receptor);
    	$respuestaEnviarSolicitudReceptor = Solicitudes::EliminarSolicitud($NameTableReceptor,$emisor);//Inserta solicitud en tabla receptor

    	if($respuestaEnviarSolicitudEmisor == -1){
    		echo json_encode(array('resultado' => 'Error de solicitud'));
    	}

    	if($respuestaEnviarSolicitudReceptor == -1){
    		echo json_encode(array('resultado' => 'Error de solicitud'));
    	}

    	if($respuestaEnviarSolicitudEmisor == 200 && $respuestaEnviarSolicitudReceptor == 200){
    		echo json_encode(array('resultado' => 'Se cancelo la solicitud correctamente'));
    	}
    	
    }
?>