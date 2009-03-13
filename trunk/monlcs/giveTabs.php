<?
include "includes/secure_no_header.inc.php";

$sqlx = "SELECT * from `ml_zones` where user='all' or user='$uid' ;";
$curseur=mysql_query($sqlx) or die("<ul><li>$sql requete invalide</li></ul>");
for ($x=0;$x<mysql_num_rows($curseur);$x++) {
$R = mysql_fetch_object($curseur);
$id = $R->rang;
$num = $id-1;
$u = $R->user;

$content2 .="<div id=submenu_".$id.">";

$sql2 =  "SELECT * from `ml_tabs` WHERE id_tab='$R->id' and (user='all' ) ORDER By id;";
$curseur2=mysql_query($sql2) or die("<ul><li>$sql2 requete invalide</li></ul>");

for ($y=0;$y<mysql_num_rows($curseur2);$y++) {
$RR=mysql_fetch_object($curseur2);

	$content2.="	<a href=# id=".$RR->nom." class=tabs >".$RR->caption."</a>";
}

$sql3 =  "SELECT * from `monlcs_db`.`ml_tabs` WHERE id_tab='$R->id' and user='$uid' ORDER by id;";
$curseur3=mysql_query($sql3) or die("<ul><li>$sql3 requete invalide</li></ul>");

for ($y=0;$y<mysql_num_rows($curseur3);$y++) {
	$RR=mysql_fetch_object($curseur3);
	$content2.="	<a href=# id=".$RR->nom." class=tabs >".$RR->caption."</a>";
}
if (!is_eleve($uid)  && ($u == 'all')) {
	$content2.="	<a href=# id=perso$num class=tabs >Proposer</a>";

}








$content2.="</div>";

}

print(stringForJavascript($content2));
?>
