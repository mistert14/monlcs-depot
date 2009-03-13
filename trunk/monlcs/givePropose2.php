<?
include "includes/secure_no_header.inc.php";

$content = "<table id=cmd>";
$content .="<tr>"
."<td colspan=2 class=grise>Action</td>"
."<td class=grise>Ressource</td>"
."<td class=grise>Propos&eacute; par</td>"
."<td class=grise>Cible</td>"
."</tr>";

extract($_POST);
$titres = array();
$sql = "SELECT * from `monlcs_db`.`ml_ressourcesProposees` WHERE matiere='$matiere' ";
$curseur=mysql_query($sql) or die("<ul><li>$sql requete invalide</li></ul>");

$groups = give_Groupes();


for ($x=0;$x<mysql_num_rows($curseur);$x++) {
$R=mysql_fetch_object($curseur);

		if (in_array($R->cible,$groups) || ($R->setter == $uid) || ($ML_Adm == 'Y') ) {

		$sq = "select * from ml_ressources where id='$R->id_ressource'";
		$c = mysql_query($sq) or die("ERR $sq");
		$X = mysql_fetch_object($c);
		if (eregi('lcs',$X->url))
			$X->url = $base_url.$X->url;
		echo	"<tr>";
		if ( ($R->setter == $uid)  || ($ML_Adm == 'Y') )
			$content .= "<td><div onclick=deletePropose('$R->id');>$delete_img</div></td>";
		else
			$content .="<td>-</td>";
		$content.="<td>"
		."<div onclick=view_Url('".$X->id."','".$X->url."');>$view_img</div>"
		."</td>"
		."<td class=nom>$X->titre </td>"
		."<td class=nom>$R->setter</td>";
		

		$content.="<td class=nom>".$R->cible."</td>"
		."</tr>";
		
		}
		}
$content .= "</table>";


print(stringForJavascript($content));
?>
