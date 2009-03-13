<?
include "includes/secure_no_header.inc.php";

$content = "<table id=cmd>";
$content .="<tr>"
."<td colspan=2 class=grise>Action</td>"
."<td class=grise>Titre</td>"
."<td class=grise>Propos&eacute; par</td>"
."<td class=grise>Cible</td>"
."</tr>";

extract($_POST);
$titres = array();
$sql = "SELECT * from `monlcs_db`.`ml_scenarios` WHERE matiere='$matiere' ;";
$curseur=mysql_query($sql) or die("<ul><li>$sql requete invalide</li></ul>");


for ($x=0;$x<mysql_num_rows($curseur);$x++) {
$R=mysql_fetch_object($curseur);
$groups = give_Groupes();


if (in_array($R->cible,$groups) || ($uid == $R->setter)  || ($ML_Adm == 'Y') ) {
if (!in_array($R->titre,$titres))
		{
		$titres[] = $R->titre;
		}
}
}//fin for



for ($x=0;$x<count($titres);$x++) {
		$cibles = array(); 
		$sq = "SELECT * from `monlcs_db`.`ml_scenarios` WHERE matiere='$matiere' and titre='$titres[$x]' ;";
		$c = mysql_query($sq) or die("ERR $sq");
		$R=mysql_fetch_object($c);
		$titre2 = (urlencode($R->titre));
		$content.="<tr>";
		if ( ($R->setter == $uid) || ($ML_Adm == 'Y') )
			$content .= "<td><div onclick=deleteScen('".$titre2."','".$matiere."','".$R->setter."');>$delete_img</div></td>";
		else
			$content .= "<td>-</td>";
		$content.="<td><div onclick=viewScen('".$titre2."','".$matiere."','".$R->setter."');>$view_img</div></td>"
		."<td>$R->titre</td>"
		."<td class=nom>$R->setter</td>";

		for ($xx=0;$xx<mysql_num_rows($c);$xx++) {
		$cible = mysql_result($c,$xx,'cible');
		if (!in_array($cible,$cibles))
			$cibles[] = $cible;
		}//for curseur
		
		$content.="<td class=nom>".implode(':',$cibles)."</td>"
		."</tr>";
		
		}//for titres
$content .= "</table>";


print(stringForJavascript($content));
?>
