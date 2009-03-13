<?
include "includes/secure_no_header.inc.php";

$content = "<table id=cmd>";
$content .="<tr>"
."<td colspan=3 class=grise>Action</td>"
."<td class=grise>Titre</td>"
."<td class=grise>Cible</td>"
."</tr>";


$tab = $_POST['id'];

$sql = "select * from ml_tabs where nom='".$tab."';";

$curseur=mysql_query($sql) or die(stringForJavascript("ERR $sql"));
if ( mysql_num_rows($curseur) != 0 ) {
	$idTab = mysql_result($curseur,0,'id');
}



//recherche des ressources proposees
if (!eregi('perso',$id)) {
	
	$groups = give_groupes();

	$sql = "SELECT * from monlcs_db.ml_ressourcesProposees where id_menu = $idTab; ";
	$c = mysql_query($sql) or die("ERR $sql");
	for ($x=0;$x<mysql_num_rows($c);$x++) {
		$idP = mysql_result($c,$x,'id');
		$idRe = mysql_result($c,$x,'id_ressource');
		$_cible=mysql_result($c,$x,'cible');
		$_setter=mysql_result($c,$x,'setter');
		if ( in_array($_cible,$groups) || ($uid == $_setter)) {

	
			$sqlR = "SELECT * from `monlcs_db`.`ml_ressources` where id='$idRe';";
			$cR = mysql_query($sqlR) or die("ERR $sqlR");
			if ($cR) {
				$R2 = mysql_fetch_object($cR);
				$sqlDejaAff = "SELECT * FROM ml_rss WHERE url='$R2->url' and user='$uid'";
				$cxDejaAff = mysql_query($sqlDejaAff) or die ("ERR $sqlDejaAff");
				$dejala= mysql_num_rows($cxDejaAff);

	
				if ($tab == 'rss') {
					$brique="<div onclick=viewRSS('".$R2->url."');>$view_img</div>";
				}
				else					
					$brique="<div  onclick=viewRessource(".$idRe.");>$view_img</div>";

				
				
				
				if (($tab == 'rss' && $dejala == 0) || $tab != 'rss') {
					if ($R2->url) {
						$content.="<tr><td colspan=2>$brique</td>";
						if ((($_setter == $uid) || ($ML_Adm  == 'Y') ) )
							$content .= "<td><div onclick=deletePropose('$idP');>$delete_img</div></td>";
						else
							$content.="<td>-</td>";

						$content.="<td>$R2->titre</td>";
						//$content.="<td>$R2->url</td>";
						$content.="<td>$_cible</td>";
					}
				}//if tab

			}//if cR
		}// 
	
	}//foreach
}//if eregi


$content .= "</table>";

if ( $ML_Adm == 'Y' ) {
	$sqlRessImp = "SELECT * FROM ml_ressourcesAffect WHERE id_menu='$idTab'";
	$cxRessImp = mysql_query($sqlRessImp) or die ("ERR $sqlRessImp");
	if (mysql_num_rows($cxRessImp)>0) {
	$content .= "<p><b>Imposées:</b></p>";

	$content .= "<table id=cmd>";

	while($R = mysql_fetch_object($cxRessImp)) {
		if ($R->setter != 'mrT')
			$brique = "<div onclick=deleteImpose('".$R->id."')>$delete_img</div>";
		else
			$brique = 'Figée';
		
	$content.="<tr><td>$brique</td><td>".giveRessName($R->id_ressource)."</td><td>$R->setter</td><td>$R->cible</td></tr>";
	


	}//while
	$content .= "</table>";
	}//if result
}
	
	
	


print(stringForJavascript($content));
?>
