<?php

	require 'Database.php';

	class Token{
		function _construct(){
			
		}
		
		public static function ObtenerTodosLosUsuarios(){
			$consulta = "SELECT * FROM Token";

			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute();

			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);

			return $tabla;
				
		}
		public static function ObtenerDatosPorId($id){
			$consulta = "SELECT id,token FROM Token WHERE id = ?";
			
            try{
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute(array($id));

			$tabla = $resultado->fetch(PDO::FETCH_ASSOC);

			return $tabla;
            }catch(PDOException $e){
                return false;
            }

		}
		
		public static function InsertarNuevoDato($id,$token){
		    $consultar = "INSERT INTO Token (id,token) VALUES (?,?)";
		    try{
		    $resultado = Database::getInstance()->getDb()->prepare($consultar);
		    return $resultado->execute(array($id,$token));
		    }catch(PDOException $e){ return false; }
		}
		public static function ActualizarDatos($id,$token){
		    if(self::ObtenerDatosPorId($id)){
    		    $consultar = "UPDATE Token SET token = ? WHERE id = ? ";
    		    $resultado = Database::getInstance()->getDb()->prepare($consultar);
    		    return $resultado->execute(array($token,$id));
		    }else{
		        return false;
		    }
		}
	}
?>