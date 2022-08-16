<?php

    require 'Login.php';
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $datos = json_decode(file_get_contents("php://input"),true);
        $respuesta = Registro::InsertarNuevoDato($datos["email"],$datos["pwd"],$datos["Nombre"],$datos["Apellido"],$datos["Descrip"],$datos["FDP"]);
        if($respuesta){
            echo "Se insertó correctamente";
        }else{
            echo "Ocurrio un error";
        }
    }

?>