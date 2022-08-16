<?php
	
	require 'Database.php';

	class Imagen{
		function _construct(){
		}

		public static function ActualizarFoto($id,$imagen){
			if(self::ObtenerDatosPorId($id)){
				$consultar = "UPDATE usuario SET FDP = ? WHERE email = ?";
				$resultado = Database::getInstance()->getDb()->prepare($consultar);
				return $resultado->execute(array($imagen,$id));
			}else {
				return false;
			}
		}

		public static function ObtenerDatosPorId($id){
			$consultar = "SELECT email FROM usuario WHERE email = ?";
			$resultado = Database::getInstance()->getDb()->prepare($consultar);
			$resultado->execute(array($id));
			$tabla = $resultado->fetch(PDO::FETCH_ASSOC);
			return $tabla;
		}
		public static function ObtenerFDP($id){
			$consultar = "SELECT email,FDP FROM usuario WHERE email = ?";
			$resultado = Database::getInstance()->getDb()->prepare($consultar);
			$resultado->execute(array($id));
			$tabla = $resultado->fetch(PDO::FETCH_ASSOC);
			return $tabla;
		}

	}

?>