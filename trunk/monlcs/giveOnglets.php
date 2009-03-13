<?
include("includes/secure_no_header.inc.php");
$sql = "SELECT * from `ml_zones` where user='all' or user='$uid' order by `rang`;";
$curseur=mysql_query($sql) or die("<ul><li>$sql requete invalide</li></ul>");
for ($x=0;$x<mysql_num_rows($curseur);$x++) {
	$R = mysql_fetch_object($curseur);
	$id=$R->id;
	$u=$R->user;
	$content2 .="<a>".$R->nom;
	if ( $u != 'all')
		$content2 .= "<span id=rt$id class=remove_tab onclick=remove_tab($id) >x</span>";
	$content2 .="</a>";
}
print(stringForJavascript($content2));
?>
