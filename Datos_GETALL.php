<?php

	require 'Datos.php';

	if($_SERVER['REQUEST_METHOD']=='GET'){

		if(isset($_GET['user'])){
			$identif = $_GET['user'];
            //$respuesta = Datos::ObtenerTodosLosUsuarios($identif);
			//$respuesta = Datos::ObtenerTodosLosUsuarios1($identif);
			//$respuesta = Datos::ObtenerTodosLosUsuarios2($identif);
			//$respuesta = Datos::ObtenerTodosLosUsuarios3($identif);
			//$respuesta = Datos::ObtenerTodosLosUsuarios4($identif);
			$respuesta = Datos::ObtenerTodosLosUsuarios5($identif);
			//$totalVistos = Datos::ObtenerTotalVistos($identif);
			$vistos = Datos::ObtenerVistos($identif);
			
			$contenedor = array();
			
			
			if($respuesta){
                $contenedor["resultado"] = "CC";
                $contenedor["datos"]= $respuesta;
                //$contenedor["vistos"]=$totalVistos;
                $contenedor["vistos"]=$vistos;
			    echo json_encode($contenedor);
			}else{
			    echo json_encode(array('resultado' => 'El usuario no existe'));
			}
		}else{
		    echo json_encode(array('resultado' => 'Falta el identificador'));
		}

	}

?>