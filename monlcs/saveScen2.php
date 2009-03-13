<?
include "includes/secure_no_header.inc.php";

if($_POST) {
extract($_POST);

$info = explode('+',$titre);
$titre = implode(' ',$info);

$idR = substr($idR,8);

if (eregi('Cmd',$idR)) {
		die('Cmd pas géré ici !');
		$idR = substr($idR,4);
		}


if (eregi('Note',$idR)) {
		$type = 'note';
		$idR = substr($idR,4);
		}
		else
		{
		$type ='ressource';
		}	


//alimenter ml_scenarios

		$cibles =array();	
   		//retrouver les cibles de l'activité
		$sq ="select * from monlcs_db.ml_scenarios where titre='$titre' and matiere='$matiere' and setter='$uid';";
		$c = mysql_query($sq) or die("ERR $sq");
		for($xx=0;$xx< mysql_num_rows($c);$xx++) {
				$R = mysql_fetch_object($c);
				if (!in_array($R->cible,$cibles))
					$cibles[] = $R->cible;		
		}
		
		if (count($cibles) == 0)
			die('Aucune cible');	
			
		$sq ="select * from monlcs_db.ml_scenarios where id_ressource='$idR' and titre='$titre' and matiere='$matiere' and setter='$uid' and type='$type';";
		$c = mysql_query($sq) or die("ERR $sq");
		
	
		if (mysql_num_rows($c) !=0 ) {
			
			
			
			for($xx=0;$xx< mysql_num_rows($c);$xx++) {
			$R = mysql_fetch_object($c);
			$sqUp = "UPDATE monlcs_db.ml_scenarios SET x='$x',y='$y',w='$w',h='$h' WHERE id='$R->id';";
			$cUp = mysql_query($sqUp) or die("ERR $sqUp");
			echo "<BR />".$sqUp;
			}
		} else {
		
		for ($yy = 0 ; $yy < count($cibles) ;$yy++) {
			
			$sql =" INSERT INTO `monlcs_db`.`ml_scenarios` (
			`id` ,
			`setter` ,
			`titre` ,
			`id_ressource` ,
			`cible` ,
			`type` ,
			`matiere` ,
			`x` ,
			`y` ,
			`w` ,
			`h`
			)
			VALUES (
			NULL , '$uid','$titre', '$idR', '$cibles[$yy]', '$type','$matiere','$x','$y','$w','$h'
			);";
			
		$cIns = mysql_query($sql) or die("ERR $sql");
		echo $sql;


		}//for
		}//else
			
		

		
	
}//if get

?>


