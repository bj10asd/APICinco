<?php

	require 'Loc.php';

	if($_SERVER['REQUEST_METHOD']=='POST'){
	    $datos = json_decode(file_get_contents("php://input"),true);
	    $id_ruta=$datos["id_ruta"];
	    $user = $datos["Usuario"];
			$respuesta = Locations::ObtenerUltimaRuta($id_ruta,$user);
			
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