<?php

    require 'Database.php';
    
    class Locations{
        function __construct(){}
        
        //creo que no la uso
        public static function CreateTable($NameTable){
            $consultar = "CREATE TABLE `$NameTable` (
                Per_id INTEGER PRIMARY KEY AUTO_INCREMENT,
                Per_usuario_id VARCHAR(80) NOT NULL,
                Per_tipo INTEGER NOT NULL,
                Per_direccion VARCHAR(60) NOT NULL,
                Per_fecha VARCHAR(50) NOT NULL )";
            try{
                $respuesta = Database::getInstance()->getDb()->prepare($consultar);
                $respuesta -> execute(array());
                return 200;
            }catch (PDOException $e){
                return -1;
            }
        }
        public static function SubirLoc($lat,$lng,$id,$fecha){
		    $consultar = "INSERT INTO Locations (Loc_Lat,Loc_Lng,Loc_usuario_id,Loc_fecha) VALUES (?,?,?,?)";
		    try{
		    $resultado = Database::getInstance()->getDb()->prepare($consultar);
		    return $resultado->execute(array($lat,$lng,$id,$fecha));
		    }catch(PDOExcepton $e){ return false; }
		}
		 public static function SubirLoc1($lat,$lng,$id,$fecha,$id_recorrido){
		    $consultar = "INSERT INTO Locations (Loc_Lat,Loc_Lng,Loc_usuario_id,Loc_fecha,Loc_id_recorrido) VALUES (?,?,?,?,?)";
		    try{
		    $resultado = Database::getInstance()->getDb()->prepare($consultar);
		    return $resultado->execute(array($lat,$lng,$id,$fecha,$id_recorrido));
		    }catch(PDOExcepton $e){ return false; }
		}
		public static function ObtenerTodosLosUsuarios($lat,$lng,$fechamenos,$fechamas,$user){
		    
			$consulta = 
			"SELECT Loc_id,Loc_usuario_id, u.Nombre,u.Apellido,u.FDP ,Loc_fecha, Loc_Lat, Loc_Lng, ( 6371000 * acos( cos( radians(?) ) *cos( radians( Loc_Lat ) ) *cos( radians(Loc_Lng) - radians(?)) +sin(radians(?)) * sin( radians(Loc_Lat)))) AS distance 
			FROM Locations 
			INNER JOIN usuario u ON Loc_usuario_id = u.email
			WHERE (Loc_fecha BETWEEN ? AND ?) 
			    AND (Loc_usuario_id != ?) 
			HAVING distance < 50 
			ORDER BY distance";
            try{
			$resultado = Database::getInstance()->getDb()->prepare($consulta);
			$resultado->execute(array($lat,$lng,$lat,$fechamenos,$fechamas,$user));
			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return $tabla;
            }catch(PDOException $e){
                return false;
            }

		}
		public static function ObtenerDatosPorId($email){
			$consulta = "SELECT email, Nombre, Apellido, Descrip, FDP FROM usuario WHERE email = ?";
			
            try{
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute(array($email));

			$tabla = $resultado->fetch(PDO::FETCH_ASSOC);

			return $tabla;
            }catch(PDOException $e){
                return false;
            }

		}
		public static function InsertarEnPersonas($my_user,$user_visto,$id){
			$consulta = "INSERT INTO Personas (Per_user,Per_usuario_visto,Loc_id) VALUES (?,?,?)";
			
            try{
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute(array($my_user,$user_visto,$id));

			$tabla = $resultado->fetch(PDO::FETCH_ASSOC);

			return $tabla;
            }catch(PDOException $e){
                return false;
            }

		}
		//devuelve1 si ya existe la vista, 0 si aun no se vieron
		public static function PreguntaPorUsuario($user,$user_visto){
			$consulta = "SELECT count(*) as C FROM Personas WHERE (Per_user = ? and Per_usuario_visto = ?) OR (Per_user = ? and Per_usuario_visto = ?)";
			
            try{
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute(array($user,$user_visto,$user_visto,$user));

			$tabla = $resultado->fetch(PDO::FETCH_ASSOC);

			return $tabla;
            }catch(PDOException $e){
                return false;
            }

		}
		public static function ObtenerUltimaRuta($id_ruta,$id_user){
		    
			$consulta = "SELECT * FROM `Locations` WHERE Loc_id_recorrido = ? AND Loc_usuario_id = ? ";
            try{
			$resultado = Database::getInstance()->getDb()->prepare($consulta);
			$resultado->execute(array($id_ruta,$id_user));
			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return $tabla;
            }catch(PDOException $e){
                return false;
            }

		}
		
		public static function DevolverEstado($user,$user_visto){
			$consulta = "SELECT estado FROM Amigos WHERE emisor = ? and receptor = ?";
            try{
			$resultado = Database::getInstance()->getDb()->prepare($consulta);
			$resultado->execute(array($user,$user_visto));
			$tabla = $resultado->fetch(PDO::FETCH_ASSOC);
			return $tabla;
            }catch(PDOException $e){
                return false;
            }

		}
    }

?>