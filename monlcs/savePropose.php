<?
include "includes/secure_no_header.inc.php";


if($_POST) {
extract($_POST);

$sql2 =  "SELECT * from `monlcs_db`.`ml_tabs` WHERE nom='$tab';";
$curseur2=mysql_query($sql2) or die("ERR $sql2");
if (mysql_num_rows($curseur2) !=0)
	$idTab = mysql_result($curseur2,0,id);



$idR = $idUrl;

$sql1 = "SELECT * FROM `monlcs_db`.`ml_ressourcesProposees` WHERE `id_ressource` ='$idR' and  `id_menu`='$idTab' and `cible`='$cible' ;";
$curseur1=mysql_query($sql1) or die("ERR $sql1");
if (mysql_num_rows($curseur1) == 0)

{


$sql = "INSERT INTO `monlcs_db`.`ml_ressourcesProposees` ("
."`id` ,"
."`id_ressource` ,"
."`id_menu` ,"
."`cible` ,"
."`matiere` ,"
."`setter`"
.")"
." VALUES ("
."NULL , '$idR', '$idTab', '$cible','$matiere', '$uid'"
.");";

$curseur=mysql_query($sql) or die("ERR $sql");
}



}
?>


