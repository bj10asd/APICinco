<?PHP
$hostname="localhost";
$database="id6989413_5inco";
$username="id6989413_bj10asd";
$password="adn4341979";
$json=array();
	if(isset($_GET["user"]) && isset($_GET["pwd"])){
		$email=$_GET['user'];
		$pwd=$_GET['pwd'];
		
		$conexion=mysqli_connect($hostname,$username,$password,$database);
		
		$consulta="SELECT email, pwd, Nombre, Apellido, Descrip, FDP FROM usuario WHERE email= '{$email}' AND pwd = '{$pwd}'";
		$resultado=mysqli_query($conexion,$consulta);

		if($consulta){
		
			if($reg=mysqli_fetch_array($resultado)){
				$json['datos'][]=$reg;
			}
			mysqli_close($conexion);
			echo json_encode($json);
		}



		else{
			$results["email"]='';
			$results["pwd"]='';
			$results["Nombre"]='';
			$results["Apellido"]='';
			$results["Descrip"]='';
			$results["FDP"]='';
			$json['datos'][]=$results;
			echo json_encode($json);
		}
		
	}
	else{
		   	$results["email"]='';
			$results["pwd"]='';
			$results["Nombre"]='';
			$results["Apellido"]='';
			$results["Descrip"]='';
			$results["FDP"]='';
			$json['datos'][]=$results;
			echo json_encode($json);
		}
?>