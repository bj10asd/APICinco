<?php
    require 'Imagen.php';
    $vieja_url = Imagen::ObtenerFDP("josemaria.terrazas@gmail.com");
    $borrar= $vieja_url["FDP"];
    if(true){
          unlink("/storage/ssd1/413/6989413/public_html/" . substr($borrar, 43, strlen($borrar)-1));
    }
    //$url = "https://josemariaterrazas.000webhostapp.com/5imagenes/qqqq.jpg";
    //echo substr($url, 43, strlen($url)-1);
    //if(true){
    //    unlink("/storage/ssd1/413/6989413/public_html/".substr($url, 43, strlen($url)-1)) ;
    //    echo "se borro normal";
    //} 
?>