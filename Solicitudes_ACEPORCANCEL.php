<?php
	require 'Solicitudes.php';
	setlocale(LC_TIME, 'es_AR.UTF-8');
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    if($_SERVER['REQUEST_METHOD']=='POST'){
    	$datos = json_decode(file_get_contents("php://input"),true);

    	$emisor = $datos["emisor"];
    	$receptor = $datos["receptor"];

    	//$NameTableEmisor = "Amigos_" . $emisor;
    	//$NameTableReceptor = "Amigos_" . $receptor;

        $token_tabla = Solicitudes::getTokenUser($receptor);

        if($token_tabla){
            $token=$token_tabla["token"];
            $fecha_hora_actual = date('Y-m-d H:i:s');

            $tablaEstado = Solicitudes::Preguntar1($emisor,$receptor);
            //$nombre = $tablaEstado["Apellido"]. " " . $tablaEstado["Nombre"];
            $contenedor = array();
            //$tabla = Solicitudes::Preguntar($NameTableEmisor,$emisor);
            $id_t=$tablaEstado["id"];
            $datosDelEmisor=Solicitudes::MisDatos($emisor);
            $namerecep = $datosDelEmisor["Apellido"]. " " . $datosDelEmisor["Nombre"];
            $imagenEMISOR = $datosDelEmisor["FDP"];
            
            //Caso 1
            if($tablaEstado["estado"] == '3' && $tablaEstado["rta"] ==1){
                //nueva logica para el nuevo der
                //if($tablaEstado["rta"] == '1')//la solicitud es enviada por la misma persona
                //{
                    $contenedor["estado"] = "2";
	                $contenedor["resultado"]= "Solicitud de chat ya enviada.";
                }else
                if($tablaEstado["estado"] == '3' && $tablaEstado["rta"] == 2)//la solicitud es "aceptada"
                {
                    
                    $respuestaEnviarSolicitud = Solicitudes::ActualizarDatos2($id_t,4,$fecha_hora_actual);
				    if($respuestaEnviarSolicitud == 200){
                    	$contenedor["estado"] = "3";
                    	$contenedor["resultado"]= "Solicitud aceptada";
                    	$contenedor["FDP"] = $imagenEMISOR;
                    	echo json_encode($contenedor);
                    	//jose envia soli a estela
                    	//estela responde a esta soli -> $emisor:jose // $namerecep Estela Herrera
                    	Solicitudes::EnviarNotificationImag($token,$emisor,'Ya puedes chatear con '.$namerecep,'solicitudAceptada',$imagenEMISOR);
                	}else{
                		$contenedor["estado"] = "0";
                    	$contenedor["resultado"]= "PHP: Error en tablas e = 3";
                    	echo json_encode($contenedor);
                    	
                	}
                    
                }
                /*
            	//2 el que emite
				//$respuestaEnviarSolicitudEmisor = Solicitudes::ActualizarDatos($NameTableEmisor,$receptor,4,3);
				//Inserta solicitud en tabla receptor, 3el que recibe
            	$respuestaEnviarSolicitud = Solicitudes::ActualizarDatos2($receptor,$emisor,4,3);
				if($respuestaEnviarSolicitudEmisor == 200 && $respuestaEnviarSolicitudReceptor == 200){
                	$contenedor["estado"] = "3";
                	$contenedor["resultado"]= "Solicitud aceptada";
                	$contenedor["FDP"] = $imagenEMISOR;
                	echo json_encode($contenedor);
                	//jose envia soli a estela
                	//estela responde a esta soli -> $emisor:jose // $namerecep Estela Herrera
                	Solicitudes::EnviarNotificationImag($token,$emisor,'Ya puedes chatear con '.$namerecep,'solicitudAceptada',$imagenEmisor);
            	}else{
            		$contenedor["estado"] = "0";
                	$contenedor["resultado"]= "PHP: Error en tablas e = 3";
                	echo json_encode($contenedor);
                	
            	}*/
//            }/*else if($tablaEstado["estado"] == '2'){
	            	//	$contenedor["estado"] = "2";
	                //	$contenedor["resultado"]= "Solicitud de chat ya enviada.";
	                //	echo json_encode($contenedor);
            	  //}
            	  elseif($tablaEstado["estado"] == '4'){
            	  		    $contenedor["estado"] = "4";
	                		$contenedor["resultado"]= "Ya son amigos.";
	                		echo json_encode($contenedor);
            	        }else{
            	        	//como no puedo captar el estado si es null
            	        	//descartamos, que sea 2 3 o 4 ENTONCES ES NULL
            	        	//$respuestaEnviarSolicitudEmisor = Solicitudes::EnviarSolicitud($NameTableEmisor,$receptor,2);
            	        	$respuestaEnviarSolicitud = Solicitudes::EnviarSolicitud2($emisor,$receptor,3,$fecha_hora_actual);
			            	//$respuestaEnviarSolicitudReceptor = Solicitudes::EnviarSolicitud($NameTableReceptor,$emisor,3);
			            	//$respuestaEnviarSolicitudReceptor = Solicitudes::EnviarSolicitud($receptor,$emisor,3);
			            	//if($respuestaEnviarSolicitudEmisor == 200 && $respuestaEnviarSolicitudReceptor == 200){
			            	if($respuestaEnviarSolicitud == 200 ){
			                	$contenedor["estado"] = "1";
	                			$contenedor["resultado"]= "Solicitud enviada";
	                			echo json_encode($contenedor);
	                			Solicitudes::EnviarNotification($token,$receptor,'¡Alguien quiere conocerte!','solicitud');
							}else{
								$contenedor["estado"] = "0";
	                			$contenedor["resultado"]= "error pL94";
	                			echo json_encode($contenedor);
							}
			            }
        }
    }
?>
