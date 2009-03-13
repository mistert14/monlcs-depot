<?

include "includes/secure_no_header.inc.php";

if ($_POST)
	extract($_POST);

mysql_connect($host,$user,$passDB) or die('Connexion mysql impossible!');
mysql_select_db($DB) or die('Base inconnue!');

if ($id == 'actu') {
	if ( $ML_Adm != 'Y' )
		die('Aucune action possible ...');
}

if ($id == 'scenario_choix') {
	if (is_eleve($uid))
		die('Aucune action possible ...');
	
}


$content2 = "<div class=menuitems title=$id><B> Menu</B></div>";

$content2 .="<HR />";


if ($id == 'rss') {
$content2 .="<div onclick=javascript:rssSave(); class=menuitems title=Sauver_les_flux>Enregistrer le bureau</div>";
$content2 .="<div onclick=javascript:giveRessources('$id'); class=menuitems title=Ressources>Ressources</div>";

}
if (!eregi('perso',$id) && !eregi('rss',$id) || $id == 'perso4' ) {

	$content2 .="<div onclick=javascript:desktopSave(); class=menuitems title=Sauver_le_bureau>Enregistrer le bureau</div>";
	if (( $ML_Adm == 'Y' ) && ($id != 'rss'))
		$content2 .="<div onclick=javascript:defaultSave(); class=menuitems title=Par_défaut>Proposer par défaut</div>";
	$content2 .="<div onclick=javascript:giveRessources('$id'); class=menuitems title=Ressources>Ressources</div>";

	if ($ML_Adm == 'Y')
		$content2 .="<div onclick=ajoutNote('$id'); class=menuitems title=Ajout_note>Ajouter une note</div>";
       	else {

	if (!is_eleve($uid) && ( ($id == 'bureau') || ($id == 'perso4') || ($id == 'vs' ) || ($id == 'scenario_choix') ) )
		$content2 .="<div onclick=ajoutNote('$id'); class=menuitems title=Ajout_note>Ajouter une note</div>";



//ajout note dans espaces perso
	

	$sql2 =  "SELECT * from `ml_tabs` WHERE nom='$id' and (user='$uid') LIMIT 1;";
	$curseur2=mysql_query($sql2) or die("$sql2 requete invalide");
	if ( mysql_num_rows($curseur2) != 0)
		$content2 .="<div onclick=ajoutNote('$id'); class=menuitems title=Ajout_note>Ajouter une note</div>";
	}
}
if (eregi('perso',$id)) {
if ($id == 'perso4' ) {
	$content2 .="<div onclick=javascript:scenario('$id'); class=menuitems title=Sc&eacute;nario>Publier un sc&eacute;nario</div>";
	} 
	
}

		if (($ML_Adm =='Y') && ($id != 'scenario_choix') )
			$content2 .="<div onclick=javascript:publish('$id'); class=menuitems title=\"Figer\">Figer des ressources</div>";
	




print(stringForJavascript($content2));
?>
