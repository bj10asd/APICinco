<?php
    require 'Database.php';

    class Solicitudes{
        function __construct(){}

        public static function CreateTable($NameTable){
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
        public static function obtenerUltimoMensaje($emisor,$receptor){
                $nameTableEmisor = "Mensajes_" . $emisor;            
                $consultar = "SELECT * FROM `$nameTableEmisor`
                                WHERE email = (SELECT max(email)
                                                FROM `$nameTableEmisor` M2
                                                WHERE M2.user = ?";
                
                $respuesta = Database::getInstance()->getDb()->prepare($consultar);
                $respuesta->execute(array($receptor));
                $tabla = $respuesta -> fetch(PDO::FETCH_ASSOC);
                return $tabla;
        }
        /*public static function EnviarSolicitud($TableName,$id,$estado){
            
                $consultar = "INSERT INTO `$TableName` (id,estado) VALUES (?,?)";
                try{
                $respuesta = Database::getInstance()->getDb()->prepare($consultar);
                $respuesta->execute(array($id,$estado));
                return 200;
            }catch(PDOException $e){ return -1;}
        }*/
        public static function EnviarSolicitud2($emisor,$receptor,$estado,$hora_del_mensaje){
            
                $consultar = "INSERT INTO Amigos (emisor,receptor,estado,hora_del_mensaje) VALUES (?,?,?,?)";
                try{
                $respuesta = Database::getInstance()->getDb()->prepare($consultar);
                $respuesta->execute(array($emisor,$receptor,$estado,$hora_del_mensaje));
                return 200;
            }catch(PDOException $e){ return -1;}
        }
        
        
        public static function ActualizarDatos($TableName,$id,$estado,$estado_var){
            try{
                $consultar = "UPDATE `$TableName` SET estado = ? WHERE id = ? AND estado=?";
                $resultado = Database::getInstance()->getDb()->prepare($consultar);
                $resultado->execute(array($estado,$id,$estado_var));
                return 200;
            }catch(PDOException $e){
                return -1;
            }
        }
        public static function ActualizarDatos2($id,$nuevo_estado,$hora_del_mensaje){
            try{
                $consultar = "UPDATE Amigos SET estado = ?, hora_del_mensaje = ? WHERE id = ?";
                $resultado = Database::getInstance()->getDb()->prepare($consultar);
                $resultado->execute(array($nuevo_estado,$hora_del_mensaje,$id));
                return 200;
            }catch(PDOException $e){
                return -1;
            }
        }
        public static function Preguntar($TableName,$receptor){
            try{
                $consultar = "SELECT D.email, F.estado, D.Nombre, D.Apellido, D.FDP
                              FROM usuario D 
                              LEFT JOIN `$TableName` F ON F.id = D.email
                              WHERE D.email = ?";
                $resultado = Database::getInstance()->getDb()->prepare($consultar);
                $resultado->execute(array($receptor));
                $tabla = $resultado->fetch(PDO::FETCH_ASSOC);
                return $tabla;
            }catch(PDOException $e){
                return false;
            }
        }
        
        public static function Preguntar1($emisor,$receptor){
            try{
                $consultar = "SELECT F.id,D.email, F.estado, D.Nombre, D.Apellido, D.FDP,
                case when F.emisor=? then 1 end as rta
                    FROM usuario D,Amigos F 
                    WHERE ((F.emisor = ? and F.receptor = ?)
                    	and D.email = F.receptor)
                    UNION ALL
                    SELECT F.id,D.email, F.estado, D.Nombre, D.Apellido, D.FDP,
                        case when F.receptor=? then 2 end as rta
                    FROM usuario D,Amigos F 
                    WHERE (F.emisor = ? and F.receptor = ?)
                    	and D.email = F.emisor";
                $resultado = Database::getInstance()->getDb()->prepare($consultar);
                $resultado->execute(array($emisor,$emisor,$receptor,$emisor,$receptor,$emisor));
                $tabla = $resultado->fetch(PDO::FETCH_ASSOC);
                return $tabla;
            }catch(PDOException $e){
                return false;
            }
        }
        
        public static function MisDatos($emisor){
            try{
                $consultar = "SELECT * FROM usuario where email = ?";
                $resultado = Database::getInstance()->getDb()->prepare($consultar);
                $resultado->execute(array($emisor));
                $tabla = $resultado->fetch(PDO::FETCH_ASSOC);
                return $tabla;
            }catch(PDOException $e){
                return false;
            }
        }
        
        
        public static function EliminarSolicitud($TableName,$id){
            try{
                $consultar = "DELETE FROM `$TableName` WHERE id = ?";
                $resultado = Database::getInstance()->getDb()->prepare($consultar);
                $resultado->execute(array($id));
                return 200;
            }catch(PDOException $e){
                return -1;
            }
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
        public static function EnviarNotificationImag($token,$usuario_emisor,$cuerpo,$type,$imagen){
            ignore_user_abort();
            ob_start();
            
            $url = 'https://fcm.googleapis.com/fcm/send';
            
            $fields = array('to' => $token ,
               'data' => array('type' => $type,'user_envio_solicitud' => $usuario_emisor, 'cabezera'=> 'Una persona cerca','cuerpo' => $cuerpo,'FDP' => $imagen));
            
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
        public static function EnviarNotification($token,$usuario_emisor,$cuerpo,$type){
            ignore_user_abort();
            ob_start();
            
            $url = 'https://fcm.googleapis.com/fcm/send';
            
            $fields = array('to' => $token ,
               'data' => array('type' => $type,'user_envio_solicitud' => $usuario_emisor, 'cabezera'=> 'Una persona cerca','cuerpo' => $cuerpo));
            
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
    }
?>