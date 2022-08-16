<?php

	require 'Database.php';

	class Registro{
		function _construct(){
			
		}
		
		public static function ObtenerTodosLosUsuarios(){
			$consulta = "SELECT email, pwd, Nombre, Apellido, Descrip, FDP FROM usuario";

			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute();

			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);

			return $tabla;
				
		}
		public static function ObtenerDatosPorId($email){
			$consulta = "SELECT email,pwd, Nombre, Apellido, Descrip, FDP, activo, cont_recorrido FROM usuario WHERE email = ?";
			
            try{
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute(array($email));

			$tabla = $resultado->fetch(PDO::FETCH_ASSOC);

			return $tabla;
            }catch(PDOException $e){
                return false;
            }

		}
		
		public static function InsertarNuevoDato($email,$pwd,$name,$ape,$descrip,$fdp){
		    $consultar = "INSERT INTO usuario (email,pwd,Nombre,Apellido,Descrip,FDP) VALUES (?,?,?,?,?,?)";
		    try{
		    $resultado = Database::getInstance()->getDb()->prepare($consultar);
		    return $resultado->execute(array($email,$pwd,$name,$ape,$descrip,$fdp));
		    }catch(PDOExcepton $e){ return false; }
		}
		public static function ActualizarPassWord($email,$pwd){
		    if(self::ObtenerDatosPorId($email)){
    		    $consultar = "UPDATE usuario SET pwd = ? WHERE email = ? ";
    		    $resultado = Database::getInstance()->getDb()->prepare($consultar);
    		    return $resultado->execute(array($pwd,$email));
		    }else{
		        return false;
		    }
		}
	}
?>