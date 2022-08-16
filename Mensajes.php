<?php

    require 'Database.php';
    
    class Mensajes{
        function __construct(){}
        
        public static function CreateTable($NameTable){
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
        /*public static function EnviarMensaje($TableName,$user,$code_mensaje,$mensaje,$tipo_mensaje,$hora_del_mensaje){
            
                $consultar = "INSERT INTO `$TableName` (user,code_mensaje,mensaje,tipo_mensaje,hora_del_mensaje) VALUES (?,?,?,?,?)";
                try{
                $respuesta = Database::getInstance()->getDb()->prepare($consultar);
                $respuesta->execute(array($user,$code_mensaje,$mensaje,$tipo_mensaje,$hora_del_mensaje));
                return 200;
            }catch(PDOException $e){ return -1;}
        }*/
        
        public static function EnviarMensaje($emisor,$receptor,$code_mensaje,$mensaje,$tipo_mensaje,$hora_del_mensaje){
            
                $consultar = "INSERT INTO Mensajes (emisor,receptor,code_mensaje,mensaje,tipo_mensaje,hora_del_mensaje) VALUES (?,?,?,?,?,?)";
                try{
                $respuesta = Database::getInstance()->getDb()->prepare($consultar);
                $respuesta->execute(array($emisor,$receptor,$code_mensaje,$mensaje,$tipo_mensaje,$hora_del_mensaje));
                return 200;
            }catch(PDOException $e){ return -1;}
        }
        
        public static function EnviarNotification($Mensaje,$hora,$token,$emisor_del_mensaje,$receptor_del_mensaje,$emisor_del_mensaje_id){
            ignore_user_abort();
            ob_start();
            
            $url = 'https://fcm.googleapis.com/fcm/send';
            
            $fields = array('to' => $token ,
               'data' => array('type' => 'mensaje','mensaje' => $Mensaje,'hora' => $hora,'cabezera' => $emisor_del_mensaje.' te envio un nuevo mensaje','cuerpo' => $Mensaje,'receptor'=>$receptor_del_mensaje, 'emisor'=>$emisor_del_mensaje_id));
            
            define('GOOGLE_API_KEY', 'AIzaSyD1tqktY5IA5lWvGRdE5nMxoWORUhzdX_0');
            
            $headers = array(
                      'Authorization:key='.GOOGLE_API_KEY,
                      'Content-Type: application/json'
                            );      
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            
            $result = curl_exec($ch);
            if($result === false)
              die('Curl failed ' . curl_error());
            curl_close($ch);
            return $result;
        }
        public static function getTokenUser($id){
            
			$consulta = "SELECT id,token FROM Token WHERE id = ?";
			try{
			$resultado = Database::getInstance()->getDb()->prepare($consulta);
			$resultado->execute(array($id));
			$tabla = $resultado->fetch(PDO::FETCH_ASSOC);
			return $tabla;
            }catch(PDOException $e){return false;}
		}
		public static function ObtenerDatosPorId($email){
			$consulta = "SELECT email,pwd, Nombre, Apellido, Descrip, FDP FROM usuario WHERE email = ?";
			
            try{
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute(array($email));

			$tabla = $resultado->fetch(PDO::FETCH_ASSOC);

			return $tabla;
            }catch(PDOException $e){
                return false;
            }

		}
        /*public static function solicitarMensajesUsuario($NameTable,$receptor){
            $consultar = "SELECT * FROM `$NameTable` WHERE user = ?";
            //ORDER BY id DESC
                try{
                $respuesta = Database::getInstance()->getDb()->prepare($consultar);
                $respuesta->execute(array($receptor));
                $tabla = $respuesta->fetchAll(PDO::FETCH_ASSOC);
                return $tabla;
            }catch(PDOException $e){ return false;}
        }
        }*/
        public static function solicitarMensajesUsuario($emisor,$receptor){
            $consultar = "SELECT
                        	id,
                            emisor,
                            receptor,
                            code_mensaje,
                            mensaje,
                            case when (emisor = ? and receptor =?) then 1
                                 when (emisor = ? and receptor =?) then 2 end as tipo_mensaje,
                            hora_del_mensaje
                        FROM `Mensajes` 
                        WHERE (emisor = ? and receptor = ?)
                               OR (emisor = ? and receptor =? )";
            
                try{
                $respuesta = Database::getInstance()->getDb()->prepare($consultar);
                $respuesta->execute(array($emisor,$receptor,$receptor,$emisor,$emisor,$receptor,$receptor,$emisor));
                $tabla = $respuesta->fetchAll(PDO::FETCH_ASSOC);
                return $tabla;
            }catch(PDOException $e){ return false;}
        }
        
        
    }

?>