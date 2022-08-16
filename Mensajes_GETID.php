<?php

    require 'Mensajes.php';
    
    setlocale(LC_TIME, 'es_AR.UTF-8');
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $datos = json_decode(file_get_contents("php://input"),true);
        $emisor = $datos["emisor"];
        $receptor = $datos["receptor"];
        //$NameTableEmisor = "Mensajes_" . $emisor;
        
        $respuesta = Mensajes::solicitarMensajesUsuario($emisor,$receptor);
        if($respuesta){
        	echo json_encode(array("resultado"=>$respuesta));
        }else{
        	echo json_encode(array("resultado"=>"PHP:Error obteniendo mensajes"));
        }
    }

?>