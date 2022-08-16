<?php

    require 'Loc.php';
    
    setlocale(LC_TIME, 'es_AR.UTF-8');
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $datos = json_decode(file_get_contents("php://input"),true);
        $lat = $datos["Lat"];
        $lng = $datos["Lng"];
        $usuario = $datos["Usuario"];
        $id_recorrido = $datos["Loc_id_recorrido"];
        //{"Lat":"-24.7212700","Lng":"-65.4124440","Fecha":"2019-05-30 04:36:00"}
        //{"Lat":"-24.7212700","Lng":"-65.4124440","Usuario":"estelaevelia.herrera@gmail.com"}
        //{"Lat":"-24.721214","Lng":"-65.41239455","Usuario":"andy92_26@gmail.com"}
        
    	$fecha_hora_actual = date('Y-m-d H:i:s');
    	
    	//$respuesta = Locations::SubirLoc($lat,$lng,$usuario,$fecha_hora_actual);
    	$respuesta = Locations::SubirLoc1($lat,$lng,$usuario,$fecha_hora_actual,$id_recorrido);
        if($respuesta){
            echo json_encode(array('resultado' => 'PHP:Se subio correctamente la ubic act'));
            
        }else{
            echo json_encode(array('resultado' => 'PHP:no se pudo subir loc'));
        }
    	
    }

?>