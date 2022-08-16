<?php

	require 'Database.php';

	class Usuarios{
		function _construct(){
			
		}
		public static function ObtenerTodosLosUsuarios(){
			$consulta = "SELECT email, pwd, Nombre, Apellido, Descrip, FDP FROM usuario WHERE email IN (SELECT id FROM Token)";


			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute();

			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);
			
			return $tabla;
		}
		public static function UpdRecorrido($id,$nro){
			try{
                $consultar = "UPDATE usuario SET cont_recorrido = ? WHERE email = ?";
                $resultado = Database::getInstance()->getDb()->prepare($consultar);
                $resultado->execute(array($nro,$id));
                return 200;
            }catch(PDOException $e){
                return -1;
            }
		}
	}
?>