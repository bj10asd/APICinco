<?php

	require 'Database.php';

	class Datos{
        function __construct(){}
		
		public static function ObtenerTodosLosUsuarios($id){
			$TableAmigos= "Amigos_".$id;
			$TableMensajes= "Mensajes_".$id;
			$consulta = "SELECT D.email,D.Nombre,D.Apellido, F.estado, M.mensaje, M.hora_del_mensaje, M.tipo_mensaje, D.FDP
						 FROM usuario D 
						 LEFT JOIN `$TableAmigos` F ON F.id = D.email
						 LEFT JOIN `$TableMensajes` M ON M.user = D.email
						 AND M.id = (SELECT MAX(M2.id) 
						 			   FROM `$TableMensajes` M2
						 			   WHERE M2.user = M.user)";
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute();

			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);

			return $tabla;
				
		}
		//es necesario que me devuelva null en los que no soy amigo o no tengo solicitud ?
		//averiguar
		//me trae solo el ultimo mensaje
		public static function ObtenerTodosLosUsuarios1($id){
			$consulta = "SELECT 
                        	U.email,
                            U.Nombre,
                            U.Apellido, 
                            A.estado, 
                            M.mensaje, 
                            M.hora_del_mensaje,
                            M.tipo_mensaje,
                            U.FDP
                        FROM Mensajes M
                        RIGHT join usuario U ON U.email = M.receptor
                        LEFT join Amigos A ON U.email=A.receptor
                        WHERE (M.emisor = ? 
                               and M.id = (SELECT MAX(M2.id) 
                                             FROM Mensajes M2 
                                             WHERE M2.receptor = M.receptor)
                               and U.email = M.receptor)
                               OR (M.receptor = ? 
                               and M.id = (SELECT MAX(M2.id) 
                                             FROM Mensajes M2 
                                             WHERE M2.emisor = M.emisor)
                               and U.email = M.emisor)
                               or M.mensaje is null
                               and A.estado = 4
                        ORDER BY M.id DESC";
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute(array($id,$id));

			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);

			return $tabla;
				
		}
		public static function ObtenerTodosLosUsuarios2($id){
		    //por alguna duda le saco el max z.id, iria entre uapellido y a.estado
			$consulta = "select *
from (select Z.email,U.Nombre,U.Apellido,A.estado,Z.mensaje,Z.hora_del_mensaje,Z.tipo_mensaje,U.FDP
from Amigos A,((select ma.emisor as email,ma.mensaje,ma.id,ma.hora_del_mensaje,ma.tipo_mensaje
        from Mensajes ma
        where ma.receptor = ?
            and id=(select max(me.id) from Mensajes me where me.emisor=ma.emisor and me.emisor=?
                   or me.receptor=?))
        UNION 
        (select mo.receptor as email,mo.mensaje,mo.id,mo.hora_del_mensaje,mo.tipo_mensaje
        from Mensajes mo
        where mo.emisor = ?
            and id=(select max(mi.id) from Mensajes mi where mi.receptor=mo.receptor))
      ) as Z
left join usuario U on (Z.email=U.email)
where Z.email=A.emisor or Z.email=A.receptor and A.estado=4
group by Z.email
UNION(
SELECT 
   Us.email,
   Us.Nombre,
   Us.Apellido, 
   Av.estado, 
   Mv.mensaje, 
   Mv.hora_del_mensaje,
   Mv.tipo_mensaje,
   Us.FDP
FROM Mensajes Mv
                        RIGHT join usuario Us ON Us.email = Mv.emisor or Us.email = Mv.receptor
                        LEFT join Amigos Av ON Us.email=Av.receptor or Us.email = Av.emisor
                        WHERE  Mv.mensaje is null
                        ORDER BY Mv.id DESC)
)as F
Group by F.email";
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute(array($id,$id,$id,$id));

			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);

			return $tabla;
		}
		/*
		  
		  OR msj.receptor= 'josemaria.terrazas@gmail.com' and msj.emisor=us.email 
      and msj.id = (SELECT MAX(M3.id) 
                    FROM Mensajes M3 
                    inner join M3 ON msj.emisor=M3.emisor)
                    
                    
        SELECT 
	U.email,
    U.Nombre,
    U.Apellido, 
    A.estado, 
    M.mensaje, 
    M.hora_del_mensaje,
    M.tipo_mensaje,
    U.FDP
FROM Mensajes M
left join usuario U ON U.email = M.receptor
left join Amigos A ON U.email=A.receptor
WHERE (M.emisor = 'josemaria.terrazas@gmail.com' 
       and M.id = (SELECT MAX(M2.id) 
                     FROM Mensajes M2 
                     WHERE M2.receptor = M.receptor)
       and U.email = M.receptor)
       OR (M.receptor = 'josemaria.terrazas@gmail.com' 
       and M.id = (SELECT MAX(M2.id) 
                     FROM Mensajes M2 
                     WHERE M2.emisor = M.emisor)
       and U.email = M.emisor)
       
       OTRO 
       
                   SELECT 
            	U.email,
                U.Nombre,
                U.Apellido, 
                A.estado, 
                M.mensaje, 
                M.hora_del_mensaje,
                M.tipo_mensaje,
                U.FDP
            FROM Mensajes M
            RIGHT join usuario U ON U.email = M.receptor
            RIGHT join Amigos A ON U.email=A.receptor
            WHERE (M.emisor = 'josemaria.terrazas@gmail.com' 
                   and M.id = (SELECT MAX(M2.id) 
                                 FROM Mensajes M2 
                                 WHERE M2.receptor = M.receptor)
                   and U.email = M.receptor)
                   OR (M.receptor = 'josemaria.terrazas@gmail.com' 
                   and M.id = (SELECT MAX(M2.id) 
                                 FROM Mensajes M2 
                                 WHERE M2.emisor = M.emisor)
                   and U.email = M.emisor)
                   or M.mensaje is null
                   
LA ULTIMA VALIDA

SELECT 
	U.email,
    U.Nombre,
    U.Apellido, 
    A.estado, 
    M.mensaje, 
    M.hora_del_mensaje,
    M.tipo_mensaje,
    U.FDP
FROM Mensajes M
RIGHT join usuario U ON U.email = M.receptor
RIGHT join Amigos A ON U.email=A.receptor
WHERE (M.emisor = ? 
       and M.id = (SELECT MAX(M2.id) 
                     FROM Mensajes M2 
                     WHERE M2.receptor = M.receptor)
       and U.email = M.receptor)
       OR (M.receptor = ? 
       and M.id = (SELECT MAX(M2.id) 
                     FROM Mensajes M2 
                     WHERE M2.emisor = M.emisor)
       and U.email = M.emisor)
       or M.mensaje is null
                   
		*/
	public static function ObtenerTodosLosUsuarios3($id){
		    //por alguna duda le saco el max z.id, iria entre uapellido y a.estado
			$consulta = "select * from ((select state.emisor as email,U.Nombre,U.Apellido,U.FDP,4 as estado,M.id,M.mensaje,M.hora_del_mensaje,M.tipo_mensaje from (SELECT * From Amigos
where estado = 4 and (emisor = ? or receptor = ?)) as state
inner join usuario U on (U.email = state.emisor)
left join Mensajes M on (M.emisor=state.emisor)
where state.emisor <> ? and M.id = (SELECT MAX(M2.id) 
                                             FROM Mensajes M2 
                                             WHERE M2.emisor = M.emisor)
                               or M.mensaje is null)
UNION
(select state.receptor as email,U.Nombre,U.Apellido,U.FDP,4 as estado,M.id,M.mensaje,M.hora_del_mensaje,M.tipo_mensaje from (SELECT * From Amigos 
where estado = 4 and (emisor = ? or receptor = ?)) as state
inner join usuario U on (U.email = state.receptor)
left join Mensajes M on (M.receptor=state.receptor)
where state.receptor <> ? and M.id = (SELECT MAX(M2.id) 
                                             FROM Mensajes M2 
                                             WHERE M2.receptor = M.receptor)
                               or M.mensaje is null)) as T ORDER by T.id desc";
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute(array($id,$id,$id,$id,$id,$id));

			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);

			return $tabla;
		}
	public static function ObtenerTodosLosUsuarios4($id){
		    //por alguna duda le saco el max z.id, iria entre uapellido y a.estado
			$consulta = "select * from (select J.email, U.Nombre, U.Apellido, U.FDP, 4 as estado, J.id, J.mensaje,J.hora_del_mensaje,J.tipo_mensaje from (select * from
(SELECT
	id,
    receptor as email,
    mensaje,
 	hora_del_mensaje,
 	tipo_mensaje
FROM 
	Mensajes
WHERE 
	emisor = ?

UNION 
SELECT
	id,
    emisor as email,
    mensaje,
 	hora_del_mensaje,
 	tipo_mensaje
FROM 
	Mensajes
WHERE 
	receptor = ?
order by id desc
) as T
Group by email) as J
inner join usuario U on (J.email = U.email)
union
select E.*,U.Nombre, U.Apellido, U.FDP,4 as estado, null,null,null,null from (SELECT CASE when emisor = ? then receptor when receptor = ? then emisor end as email From Amigos where estado = 4 and (emisor = ? or receptor =?)) as E
inner join usuario U on (U.email = E.email)) as F
group by F.email";
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute(array($id,$id,$id,$id,$id,$id));

			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);

			return $tabla;
		}
		public static function ObtenerTodosLosUsuarios5($id){
		    //por alguna duda le saco el max z.id, iria entre uapellido y a.estado
			$consulta = "select * 
            from (select J.email, U.Nombre, U.Apellido, U.FDP, 4 as estado, J.id, J.mensaje,J.hora_del_mensaje,J.tipo_mensaje 
                  from (select * from
                        (
                            ( SELECT
                                 id,
                                 receptor as email,
                                 mensaje,
                                 hora_del_mensaje,
                                 tipo_mensaje
                             FROM 
                                 Mensajes
                             WHERE 
                                 emisor = ?
            
                            )
                        UNION 
                            SELECT
                                id,
                                emisor as email,
                                mensaje,
                                hora_del_mensaje,
                                tipo_mensaje
                            FROM 
                                Mensajes
                            WHERE 
                                receptor = ?
                            order by id desc
                        ) as T
                 Group by email) as J
            inner join usuario U on (J.email = U.email)
                  
            union
                  
            select E.email,U.Nombre, U.Apellido, U.FDP,4 as estado, null,null,E.hora_del_mensaje,null from 
                  (SELECT 
                   CASE when emisor = ? then receptor 
                           when receptor = ? then emisor end as email,hora_del_mensaje 
                   From Amigos where estado = 4 and (emisor = ? or receptor =?)
                  ) as E
            inner join usuario U on (U.email = E.email)) as F
            group by F.email
            order by 6 desc";
            //order by F.hora_del_mensaje desc"; voy a tener problemas con el id de los que no tienen mensajes
            
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute(array($id,$id,$id,$id,$id,$id));

			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);

			return $tabla;
		}
		
		/*
select * from 
((select pe.Per_usuario_visto as email, lo.Loc_Lat, lo.Loc_Lng, lo.Loc_fecha, u.Nombre, u.Apellido, u.FDP,a.estado
from Personas pe
INNER JOIN Locations lo on (pe.Loc_id = lo.Loc_id)
INNER JOIN usuario u on (u.email = lo.Loc_usuario_id)
INNER JOIN Amigos a on (a.emisor=pe.Per_user)
WHERE pe.Per_user = 'josemaria.terrazas@gmail.com'
)
UNION
(
select pe.Per_user as email, lo.Loc_Lat, lo.Loc_Lng, lo.Loc_fecha, u.Nombre, u.Apellido, u.FDP,a.estado
from Personas pe
INNER JOIN Locations lo on (pe.Loc_id = lo.Loc_id)
INNER JOIN usuario u on (u.email = lo.Loc_usuario_id)
INNER JOIN Amigos a on (a.receptor=pe.Per_usuario_visto)
WHERE pe.Per_usuario_visto = 'josemaria.terrazas@gmail.com'
)) as T
group by T.email



select * from 
((select pe.Per_usuario_visto as email, lo.Loc_Lat, lo.Loc_Lng, lo.Loc_fecha, u.Nombre, u.Apellido, u.FDP,a.estado
from Personas pe
INNER JOIN Locations lo on (pe.Loc_id = lo.Loc_id)
INNER JOIN usuario u on (u.email = lo.Loc_usuario_id)
INNER JOIN Amigos a on (a.emisor=pe.Per_user)
WHERE pe.Per_user = 'josemaria.terrazas@gmail.com'
)
UNION
(
select pe.Per_user as email, lo.Loc_Lat, lo.Loc_Lng, lo.Loc_fecha, u.Nombre, u.Apellido, u.FDP,a.estado
from Personas pe
INNER JOIN Locations lo on (pe.Loc_id = lo.Loc_id)
INNER JOIN usuario u on (u.email =pe.Per_user)
INNER JOIN Amigos a on (a.receptor=pe.Per_usuario_visto)
WHERE pe.Per_usuario_visto = 'josemaria.terrazas@gmail.com'
)) as T
WHERE T.estado = 3
Group By T.email
		*/
		public static function LlenarRVPersonas($id){
		    
			$consulta = "
SELECT * FROM 
(
(SELECT pe.Per_usuario_visto as email, lo.Loc_Lat, lo.Loc_Lng, lo.Loc_fecha, u.Nombre, u.Apellido, u.FDP,me.cont_recorrido,lo.Loc_id_recorrido ,(SELECT estado FROM Amigos WHERE (emisor = ? and receptor = pe.Per_usuario_visto) OR (receptor = ? and emisor = pe.Per_usuario_visto)) as estado
from Personas pe
INNER JOIN Locations lo on (pe.Loc_id = lo.Loc_id)
INNER JOIN usuario u on (u.email = pe.Per_usuario_visto)
INNER JOIN usuario me on(me.email = pe.Per_user)
WHERE pe.Per_user = ?)
UNION
(SELECT pe.Per_user as email, lo.Loc_Lat, lo.Loc_Lng, lo.Loc_fecha, u.Nombre, u.Apellido, u.FDP,me.cont_recorrido, lo.Loc_id_recorrido ,(SELECT estado FROM Amigos WHERE (emisor = ? and receptor = pe.Per_user) OR ( receptor = ? and emisor = pe.Per_user)) as estado
from Personas pe
INNER JOIN Locations lo on (pe.Loc_id = lo.Loc_id)
INNER JOIN usuario u on (u.email = pe.Per_user)
INNER JOIN usuario me on(me.email = pe.Per_usuario_visto)
WHERE pe.Per_usuario_visto = ?)
) AS Z WHERE Z.estado <> 4
			";
			$resultado = Database::getInstance()->getDb()->prepare($consulta);

			$resultado->execute(array($id,$id,$id,$id,$id,$id));

			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);

			return $tabla;
		}

        public static function ObtenerTotalVistos($id){
			$consulta = "SELECT COUNT(leido) as vistos FROM Mensajes WHERE leido = 0 and receptor = ? ";
			$resultado = Database::getInstance()->getDb()->prepare($consulta);
			$resultado->execute(array($id));
			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return $tabla;
		}
        public static function ObtenerVistos($id){
			$consulta = "SELECT emisor as email, COUNT(leido) as visto FROM Mensajes WHERE leido = 0 and receptor = ? GROUP BY email";
			$resultado = Database::getInstance()->getDb()->prepare($consulta);
			$resultado->execute(array($id));
			$tabla = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return $tabla;
			/*SELECT MAX(id)as id ,email, visto 
FROM (
      (SELECT MAX(id) as id, emisor as email, COUNT(leido) as visto FROM Mensajes WHERE leido = 0 and receptor = 'josemaria.terrazas@gmail.com' GROUP BY email )
	 UNION
	  (SELECT MAX(id) as id, receptor as email, COUNT(leido) as visto FROM Mensajes WHERE leido = 0 and emisor = 'josemaria.terrazas@gmail.com' GROUP BY email )
   	 ) as T
GROUP BY T.email
ORDER BY 1 DESC*/
		}
	}
	
	
?>