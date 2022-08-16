<?php
	
	require 'Database.php';

	class Registro{
		function _construct(){
		}

		public static function InsertarNuevoDato($id,$pwd,$Nombre,$apellido,$descrip,$estado,$telef){
		    $consultar = "INSERT INTO usuario (email,pwd,Nombre,Apellido,Descrip,Telef,activo) VALUES (?,?,?,?,?,?,?)";
		    try{
		    $resultado = Database::getInstance()->getDb()->prepare($consultar);
		    return $resultado->execute(array($id,$pwd,$Nombre,$apellido,$descrip,$telef,$estado));
		    }catch(PDOException $e){
		        return false;
		    }
		}
		public static function CreateTableMensajes($id){
			$NameTable = "Mensajes_" .$id;
            $consultar = "CREATE TABLE `$NameTable` (
            	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user VARCHAR (80) NOT NULL ,
                code_mensaje VARCHAR(80) NOT NULL,
                mensaje VARCHAR(500) NOT NULL,
                tipo_mensaje VARCHAR(10) NOT NULL,
                hora_del_mensaje VARCHAR(50) NOT NULL )";
            try{
                $respuesta = Database::getInstance()->getDb()->prepare($consultar);
                $respuesta -> execute(array());
                return 200;
            }catch (PDOException $e){
                return -1;
            }
        }
        public static function CreateTableAmigos($id){
        	$NameTable = "Amigos_".$id;
			try{
				$consultar = "CREATE TABLE `$NameTable`(
					id VARCHAR(50) PRIMARY KEY,
					estado VARCHAR(10) NOT NULL)";
				$respuesta = Database::getInstance()->getDb()->prepare($consultar);
				$respuesta->execute(array());
				return 200;
			}catch(PDOException $e){
				return -1;
			}
		}
		public static function UserActivado($id,$nro){
			try{
                $consultar = "UPDATE usuario SET activo = ? WHERE email = ?";
                $resultado = Database::getInstance()->getDb()->prepare($consultar);
                $resultado->execute(array($nro,$id));
                return 200;
            }catch(PDOException $e){
                return -1;
            }
		}
	}
?>
