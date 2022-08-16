<?php

	require 'Amigos.php';

	if($_SERVER['REQUEST_METHOD']=='GET'){
	    if(isset($_GET["id"])){
    	    try{
    	        $identif = $_GET["id"];
    		    //$Respuesta = Amigos::ObtenerTodosLosUsuarios('Amigos_'.$identif);
    		    $Respuesta = Amigos::ObtenerTodosLosUsuarios1($identif);
    		    echo json_encode(array('resultado'=>$Respuesta));
    	    }catch(PDOException $e){
    	        echo json_encode(array('resultado' => 'Ocurrio un error, intenta mas tarde'));
    	    }
	    }else{echo json_encode(array('resultado'=> 'Falta identif'));}
	}
	
?>