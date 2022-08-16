<?php

    require 'Mensajes.php';
    
    setlocale(LC_TIME, 'es_AR.UTF-8');
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $datos = json_decode(file_get_contents("php://input"),true);
        $emisor = $datos["emisor"];
        $receptor = $datos["receptor"];
        $mensaje = $datos["mensaje"];
        //$NameTableEmisor = "Mensajes_" . $emisor;
        //$NameTableReceptor = "Mensajes_" . $receptor;
        
        $rdo = Mensajes::ObtenerDatosPorId($emisor);//obtenemos los datos perso del emisor
        $nombre = $rdo["Nombre"] . " " .$rdo["Apellido"];
        
        $token_tabla = Mensajes::getTokenUser($receptor);
        if($token_tabla){
            
            $token = $token_tabla["token"];
        
        	$fechaActual = getdate();
        	$segundos = $fechaActual['seconds'];
        	$minutos = $fechaActual['minutes'];
        	$hora = $fechaActual['hours'];
        	$dia = $fechaActual['mday'];
        	$mes = $fechaActual['mon'];
        	$year = $fechaActual['year'];
        
        	$miliseconds = DateTime::createFromFormat('U.u',microtime(true));
        
        	$id_user_emisor = $emisor . "_" . $hora . $minutos . $segundos . $miliseconds->format("u");
        	$id_user_receptor = $receptor . "_" . $hora . $minutos . $segundos . $miliseconds->format("u");
        	
        	$hora_del_mensaje = strftime("%H:%M , %a, %d de %B de %Y"); //kfelix
        	
        	//$hora_del_mensaje = date('Y-m-d H:i:s.u');
        	//$hora1 = date('D, d M Y H:i:s');
        	//$hora2 = strftime("%H:%M , %a, %d de %B de %Y");
        	
        	$MEE = false;
        	$MER = false;
        	
        	//$respuestaEnviarMensajeEmisor = Mensajes::EnviarMensaje($NameTableEmisor,$receptor,$id_user_receptor,$mensaje,1,$hora_del_mensaje);
        	$respuestaEnviarMensajeEmisor = Mensajes::EnviarMensaje($emisor,$receptor,$id_user_receptor,$mensaje,1,$hora_del_mensaje);
        	if($respuestaEnviarMensajeEmisor == 200){
        	    $MEE = true;
        	    echo json_encode(array('resultado' => 'El mensaje fue enviado correctamente', 'hora_del_mensaje' => $hora_del_mensaje));
        	    Mensajes::EnviarNotification($mensaje,$hora_del_mensaje,$token,$nombre,$receptor,$emisor);
        	}else{
        	    echo "php:No se pudo enviar el mensaje1 ";
        	}
        	
        	//$respuestaEnviarMensajeReceptor = Mensajes::EnviarMensaje($NameTableReceptor,$emisor,$id_user_emisor,$mensaje,2,$hora_del_mensaje);
        	/*$respuestaEnviarMensajeReceptor = Mensajes::EnviarMensaje($receptor,$emisor,$id_user_emisor,$mensaje,2,$hora_del_mensaje);
        	if($respuestaEnviarMensajeReceptor == 200){
        	    $MER = true;
        	}else{
        	    echo "php:No se pudo enviar el mensaje ";
        	}
        	if($MEE && $MER){
        	    echo json_encode(array('resultado' => 'El mensaje fue enviado correctamente'));
        	    Mensajes::EnviarNotification($mensaje,$hora_del_mensaje,$token,$nombre,$receptor,$emisor);
        	}*/
        	
        }else{
            echo json_encode(array('resultado' => 'el usuario receptor no existe'));
        }
    	
    	
    }

?>