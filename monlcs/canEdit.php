<?
include "includes/secure_no_header.inc.php";

if ($_POST) {
	extract($_POST);
}


$sql = "select * from monlcs_db.ml_tabs where nom='$id' and user='$uid'";
$c = mysql_query($sql) or die ("ERR $sql");
if (mysql_num_rows($c) != 0) {
		$content = 'ok';
		die($content);
	}


if ($id == 'scenario_choix') {
	if ($setter != $uid)
		die ('ko');
	else die('ok');
	
	}

if ($id == 'perso4')
	die(stringForJavascript('ok'));

if ($id == 'bureau')
	die(stringForJavascript('ok'));

if ($ml_Adm =='y')
	$content = 'ok';
else
	$content = 'ko';

$sql = "select * from monlcs_db.ml_notes where id='$note' and setter='$uid'";
$c = mysql_query($sql) or die ("ERR $sql");
if (mysql_num_rows($c) != 0)
	$content = 'ok';



print(stringForJavascript($content));

?>
