<?php

	require 'Datos.php';

	if($_SERVER['REQUEST_METHOD']=='POST'){
	    $datos = json_decode(file_get_contents("php://input"),true);
	    $user = $datos["Usuario"];
			$respuesta = Datos::LlenarRVPersonas($user);
			
			$contenedor = array();
			
			
			if($respuesta){
                $contenedor["resultado"] = "CC";
                $contenedor["datos"]= $respuesta;
			    echo json_encode($contenedor);
			}else{
			    echo json_encode(array('resultado' => 'vacio'));
			}
		}else{
		    echo json_encode(array('resultado' => 'Falta el identificador'));
		}


?>
