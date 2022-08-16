<?php
    require 'Registro.php';
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $datos = json_decode(file_get_contents("php://input"),true);
        $respuesta = Registro::InsertarNuevoDato($datos["email"],$datos["pwd"],$datos["Nombre"],$datos["Apellido"],"Bienvenido a 5inco!",$datos["num_estado"],$datos["telef"]);
        
        if($respuesta){
        	//Registro::CreateTableMensajes($datos["email"]);
        	//Registro::CreateTableAmigos($datos["email"]);
            echo json_encode(array('resultado' => 'El usuario se registro correctamente'));
        }else{
            echo json_encode(array('resultado' => 'El usuario ya existe'));
        }
    }
?>