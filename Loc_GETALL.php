<?php

	require 'Loc.php';
	
	setlocale(LC_TIME, 'es_AR.UTF-8');
    date_default_timezone_set('America/Argentina/Buenos_Aires');

	if($_SERVER['REQUEST_METHOD']=='POST'){
	    $datos = json_decode(file_get_contents("php://input"),true);
	    $lat = $datos["Lat"];
	    $lng = $datos["Lng"];
	    $user = $datos["Usuario"];
	    //$fecha = $datos["Fecha"]; //para probar una fecha en especial, descomento esta y comento line14
	    $fecha = date('Y-m-d H:i:s');
	    $fecha1 = new DateTime($fecha);
		$fechamas = $fecha1->modify('+3 second');
		$fechamas=$fechamas->format('Y-m-d H:i:s');
		$fechamenos = $fecha1->modify('-6 second');
		$fechamenos=$fechamenos->format('Y-m-d H:i:s');
        try{
    	    $Respuesta = Locations::ObtenerTodosLosUsuarios($lat,$lng,$fechamenos,$fechamas,$user);
    	    
    	    //-24.72121965 -65.4124725 2019-12-23 12:12:42
    		$contenedor = array();
			if($Respuesta){
                $contenedor["resultado"] = "CC";
                $contenedor["datos"]= $Respuesta;
                //echo $Respuesta[0]["Loc_usuario_id"];
                //echo "rta:".$Respuesta;
                foreach($Respuesta as $item){
                    //echo 'hola?'.$item["Loc_usuario_id"];
                    //echo 'hola?'.$item["Loc_id"];
                    $n_rta = Locations::PreguntaPorUsuario($user,$item["Loc_usuario_id"]);
                    //echo $n_rta;
                    //echo $n_rta['C'];
                    //me va a traer siempre a una persona que AUN NO VI
                    if($n_rta["C"]==0)
                        $respuesta = Locations::InsertarEnPersonas($user,$item["Loc_usuario_id"],$item["Loc_id"]);
                }
			    echo json_encode($contenedor);
			}else{
			    echo json_encode(array('resultado' => 'vacio'));
			}
    	    }catch(PDOException $e){
    	        echo json_encode(array('resultado' => 'PHP: Ocurrio un error en try, intenta mas tarde'));
    	    }
	}
	
?>