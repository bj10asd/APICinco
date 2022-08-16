<?php
    require 'Registro.php';
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $datos = json_decode(file_get_contents("php://input"),true);
        $respuesta = Registro::UserActivado($datos["email"],"1");
        if($respuesta){
            echo json_encode(array('resultado' => 'Cuenta activada!'));
        }else{
            echo json_encode(array('resultado' => 'p. Error activando cuenta'));
        }
    }
?>