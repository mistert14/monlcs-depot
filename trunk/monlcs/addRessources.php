<?php

include "includes/secure_no_header.inc.php";

$pattern = array('&amp;');
$repl = array('&');

if ($_POST) {
extract($_POST);
//$url_vignette = choix migniature
$url = htmlspecialchars(urldecode($url));
//patch pour widgets NetVibes non W3C compliant
$url = str_replace( $pattern, $repl, $url);

$url_vignette = htmlspecialchars(urldecode($url_vignette));
$url_vignette = str_replace( $pattern, $repl, $url_vignette);

$titre = htmlspecialchars(urldecode($titre));
if ($url == "")
	die('url vide!');
if ($titre == "")
	die('le titre manque!');

}
else die('aucun post');

if ($statut == 'true')
	$stat ='public';
else
	$stat='private'; 

if ($rss == 'true')
	$gestRSS = 'RSS_no_img';
else
	$gestRSS = 'null'; 




$sql = "SELECT * FROM `monlcs_db`.`ml_ressources` WHERE url='$url' ;";
$c=mysql_query($sql) or die(stringForJavascript($sql));

$date = date('Y-m-d');

if (mysql_num_rows($c) == 0) {

if ($url_vignette == 'null')
	$url_vignette = null;

$sql ="INSERT INTO `monlcs_db`.`ml_ressources` ("
."`id` ,"
."`titre` ,"
."`url` ,"
."`RSS_template` ,"
."`owner` ,"
."`statut` ,"
."`ajoutee_le` ,"
."`url_vignette` "
.")"
." VALUES ("
."'' , '$titre', '$url', '$gestRSS', '$uid', '$stat', '$date', '$url_vignette' "
.");";

$c=mysql_query($sql) or die("ERR $sql");
die('La ressource a été insérée avec succes.');
}
else {
die(stringForJavascript('Ressource deja présente!'));
}



?>
