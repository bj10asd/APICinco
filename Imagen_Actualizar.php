<?php 
	require 'Imagen.php';
 
 	$NOMBRE_ARCHIVO_KEY = 'file';
 	$CARPETA = "/5imagenes/";
 	$URL = "https://josemariaterrazas.000webhostapp.com";

 	if($_SERVER['REQUEST_METHOD']=='POST'){
 		if(isset($_FILES[$NOMBRE_ARCHIVO_KEY])){
 			$id = $_POST['id'];
 			//Archivo 
	 		$file = $_FILES[$NOMBRE_ARCHIVO_KEY];

	 		//Propiedades del archivo
	 		$file_name = $file['name'];
	 		$file_tmp = $file['tmp_name'];
	 		$file_size = $file['size'];
	 		$file_error = $file['error'];

	 		//Extencion del archivo
	 		$file_ext = explode('.',$file_name);
			$file_ext = strtolower(end($file_ext));

			//Extenciones permitidas
			$permitir = array('png','jpg','jpeg');
			
			$vieja_url = Imagen::ObtenerFDP($id);
            $borrar= $vieja_url["FDP"];
			if(in_array($file_ext,$permitir)){//verificar si el archivo esta en las extenciones permitidas
				if($file_error===0){//Si el archivo no tiene errores
					if($file_size<=5767168){//maximo 5.5 mbs
						$file_name_new = uniqid('',true).'.'.$file_ext;
						$file_destino = $_SERVER['DOCUMENT_ROOT'].$CARPETA.$file_name_new;
						try{
							if(move_uploaded_file($file_tmp,$file_destino)){
								$URL_FOTO = $URL.$CARPETA.$file_name_new;
								Imagen::ActualizarFoto($id,$URL_FOTO);
								//le saque eso al array, así es mas facil agrrar la url
								//'respuesta'=>"200",'estado'=>"Se subio la imagen correctamente",'url'=>$URL_FOTO
								echo json_encode(array($URL_FOTO));
								
                                if(true){
                                    unlink("/storage/ssd1/413/6989413/public_html/" . substr($borrar, 43, strlen($borrar)-1));
                                }
							}else{
								echo json_encode(array('respuesta'=>"-1",'estado'=>"Error al subir la imagen"));
							}
						}catch(PDOException $e){
							echo json_encode(array('respuesta'=>"-1",'estado'=>"Error: "+$e->getMessage()));
						}
					}else{
						echo json_encode(array('respuesta'=>"-1",'estado'=>"Error: Supera el limite maximo de memoria permitida"));
					}
				}else{
					echo json_encode(array('respuesta'=>"-1",'estado'=>"Error: El archivo es invalido"));
				}
			}else{
				echo json_encode(array('respuesta'=>"-1",'estado'=>"Error: El archivo no tiene compatibilidad de extenciones"));
			}

	 	}
 	}

?>