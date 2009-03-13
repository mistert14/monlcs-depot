<?php

include "includes/secure_no_header.inc.php";

extract($_POST);
$info = explode('+',$titre);
$titre = implode(' ',$info);

$sql = "SELECT id_ressource FROM monlcs_db.ml_scenarios where titre='$titre' and  matiere='$matiere' and setter='$auteur' and type='note';";
$c2=mysql_query($sql) or die(stringForJavascript("ERR $sql"));
echo $sql;

while ($R = mysql_fetch_object($c2)) {
$idNoteEff = $R->id_ressource;
$sql2 = "DELETE FROM monlcs_db.ml_notes where id='$idNoteEff' and  menu='perso3';";
echo $sql2;
$c3=mysql_query($sql2) or die(stringForJavascript("ERR $sql2"));
}



$sql = "DELETE FROM monlcs_db.ml_scenarios where titre='$titre' and  matiere='$matiere' and setter='$auteur';";
$c2=mysql_query($sql) or die(stringForJavascript("ERR $sql"));
echo $sql;


?>
