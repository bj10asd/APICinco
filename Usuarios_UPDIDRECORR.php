<?php
    require 'Usuarios.php';
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $datos = json_decode(file_get_contents("php://input"),true);
        $respuesta = Usuarios::UpdRecorrido($datos["usuario"],$datos["nro"]);
        if($respuesta){
            echo json_encode(array('resultado' => 'valor actualizado'));
        }else{
            echo json_encode(array('resultado' => 'p. Error activando cuenta'));
        }
    }
?>