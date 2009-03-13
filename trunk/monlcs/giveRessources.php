<?
include "includes/secure_no_header.inc.php";
if ($ML_Adm == 'Y' )
	$content ='<p>Attention! Vous avez le droit monlcs_is_admin.<br /> Toute suppression sera définitive!</p>';
else
	$content ='';;

$content .= "<table id=cmd>";
$content .="<tr>"
."<td colspan=4 class=grise>Action</td>"
."<td class=grise>Titre</td>"
//."<td class=grise>Url</td>"
."</tr>";


$tab = $_POST['id'];

$sql = "select * from ml_tabs where nom='".$tab."';";
//$content .= $sql;
$curseur=mysql_query($sql) or die(stringForJavascript("ERR $sql"));
if ( mysql_num_rows($curseur) != 0 ) {
	$idTab = mysql_result($curseur,0,'id');
}

$ListeRess = array();
$ListeRss = array();

$sql = "SELECT * from `monlcs_db`.`ml_ressources` where owner = '$uid' OR statut='public' ORDER by titre;";
$curseur=mysql_query($sql) or die("<ul><li>$sql requete invalide</li></ul>");

for ($x=0; $x<mysql_num_rows($curseur);$x++) {
$Titre = mysql_result($curseur,$x,'titre');
$Ress = mysql_result($curseur,$x,'id');
$Type = mysql_result($curseur,$x,'RSS_template');

if ($Type == 'null' )
	$ListeRess[] = $Titre."#".$Ress;
else
	$ListeRss[] = $Titre."#".$Ress;
}

//ressources proposees pour scenarios

	if ( ($tab == 'scenario_choix') || ($tab == 'perso4') ) {
		
		$groupes = give_groupes();
			foreach($groupes as $gp) {
			
			$sql = "select * from ml_ressourcesProposees where cible = '$gp' ;";
			$c =mysql_query($sql) or die("Erreur $sql !");
			for ($x=0; $x<mysql_num_rows($c);$x++) {
				$r_id = mysql_result($c,$x,'id_ressource');
				$sql_rid = "select * from ml_ressources where id = '$r_id'; ";
				$curs_rid = mysql_query($sql_rid) or die(" Erreur $sql_rid ");
				if ($curs_rid) {
				$RZ = mysql_fetch_object($curs_rid);
				$elem = $RZ->titre."#".$r_id;
				if (!in_array($elem,$ListeRess))
					$ListeRess[] = $elem;
				}//if
			
			}// for x

		}//for groupes
	}//if


if ($tab == 'rss')
	$liste = $ListeRss;
else
	$liste =$ListeRess;

sort($liste);
//die(print_r($liste));




for ($y=0;$y<count($liste);$y++) {

$info = explode('#',$liste[$y]);
$idListe = $info[1];

$sq = "select * from ml_ressources where id = '$idListe' ";
$cz = mysql_query($sq) or die("Erreur $sq");
$R=mysql_fetch_object($cz);
//print_r($R);

if ( ($tab == 'rss')  && ($R->RSS_template != 'null')  || ( ($tab != 'rss')  && ($R->RSS_template == 'null') ) ) {
//print_r($R);

if  (($tab == 'rss')  && ($R->RSS_template != 'null')  ) {
$sqlDejaAff = "SELECT * FROM ml_rss WHERE url='$R->url' and user='$uid'";
$cxDejaAff = mysql_query($sqlDejaAff) or die ("ERR $sqlDejaAff");
$dejala= mysql_num_rows($cxDejaAff);
}

if ( ($tab == 'rss') && ($dejala == 0) || ($tab != 'rss') ) { 
$content.="<tr>";

if (( $R->owner == $uid )  && (Ressource_Libre($R->id) == '') || ($ML_Adm == 'Y') ) {
		$content.= "<td ><div onclick=deleteRessources(".$R->id.");>$delete_img</div></td>";
		}
			
		else
			$content.="<td>-</td>";

		if ( (  $R->owner == $uid )   || ($ML_Adm == 'Y') ) 
			$content.= "<td><div id=affStatut".$R->id." ondblclick=changeStatut(".$R->id.");>$R->statut</div></td>";
		else
			$content.="<td>-</td>";


		if ( (Ressource_Libre($R->id) != '') && (( $R->owner == $uid ) || ($ML_Adm == 'Y')) && ($tab != 'rss') )
			$content.="<td><div id=affStat".$R->id." onclick=statsFor(".$R->id.");>".$stats_img."</div></td>";
		else
			$content.="<td> - </td>";
		
		$content.="<td>";
	

	
	

if ($tab == 'rss')
	$content.="<div onclick=viewRSS('".$R->url."');>$view_img</div>";
else
	$content.="<div onclick=viewRessource(".$R->id.");>$view_img</div>";


$content .= "</td>"
."<td id=titre_ajaxWind".$R->id.">$R->titre&nbsp;";
if ($R->owner == $uid)
	$content .="<div onclick=renameRessources('ajaxWind".$R->id."');>$rename_img</div>";


$content .="</td>"
//."<td>".Tronquer_Texte($R->url,25)."</td>"
."</tr>";
}
}




} //for

	$content .= "</table>";


	print(stringForJavascript($content));
?>
