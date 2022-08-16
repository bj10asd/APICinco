<?php

	require 'Loc.php';

	if($_SERVER['REQUEST_METHOD']=='POST'){
	    $datos = json_decode(file_get_contents("php://input"),true);
		$identif = $datos["user"];
		$yo = $datos["myuser"];
		$respuesta = locations::ObtenerDatosPorId($identif);
		$contenedor = array();
		$rtaEstado = locations::DevolverEstado($yo,$identif);
		if($respuesta){
            $contenedor["resultado"] = "CC";
            $contenedor["datos"]= $respuesta;
            //if($rtaEstado)
                $contenedor["estado"] = $rtaEstado;
            //else $contenedor["estado"] ="" ."0";
		    echo json_encode($contenedor);
		}else{
		    echo json_encode(array('resultado' => 'PHP:El usuario no existe, void query'));
		}
	}else{
	    echo json_encode(array('resultado' => 'PHP:Falta el id'));
	}

?>