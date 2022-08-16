<?php

    require 'Login.php';
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $datos = json_decode(file_get_contents("php://input"),true);
        $respuesta = Registro::ActualizarPassWord($datos["email"],$datos["pwd"]);
        if($respuesta){
            echo "Se actualizó correctamente";
        }else{
            echo "El usuario no existe";
        }
    }

?>