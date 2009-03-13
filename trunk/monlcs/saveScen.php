<?
include "includes/secure_no_header.inc.php";

if($_POST) {
extract($_POST);
$titre = htmlspecialchars(urldecode($titre));
#die("$titre");

if (eregi('Cmd',$idR)) {
		die('Cmd pas géré ici !');
		$idR = substr($idR,4);
		}


//alimenter ml_scenarios
		$idR = substr($idR,8);


		if (eregi('Note',$idR)) {
		$type = 'note';
		$idR = substr($idR,4);
		}
		else
		{
		$type ='ressource';
		}	
	
		$sq ="select * from monlcs_db.ml_scenarios where id_ressource='$idR' and titre='$titre' and matiere='$matiere' and cible='$cible' and setter='$uid';";
		$c = mysql_query($sq) or die("ERR $sq");
		if (mysql_num_rows($c) !=0 ) {
		//update	
		} else {
		
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
			NULL , '$uid','$titre', '$idR', '$cible','$type', '$matiere','$x','$y','$w','$h'
			);";

		$cIns = mysql_query($sql) or die("ERR $sql");
		echo $sql;

		}
	}//if get


?>


