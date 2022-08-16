<?php

	require 'Database.php';

	class Amigos{
        function __construct(){}
		
		public static function ObtenerTodosLosUsuarios($Table){
			//$consulta = "SELECT id,estado FROM $Table";
			$consulta = "SELECT tab.id, tab.estado, usuario.Nombre, usuario.Apellido FROM `$Table` tab,usuario WHERE tab.id = usuario.email ";
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute();

			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);

			return $tabla;
				
		}
		//devuelve tabla con nombre y apell de las personas con las que me mando
		//solicitudes 
		public static function ObtenerTodosLosUsuarios1($id){
			$consulta = "SELECT a.id,a.estado,usuario.Nombre, usuario.Apellido
                          FROM Amigos as a,usuario
                          WHERE (a.emisor = ? and a.receptor=usuario.email)
                               OR (a.receptor=? and a.emisor=usuario.email)";
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute(array($id,$id));

			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);

			return $tabla;
				
		}
		
	}
?>