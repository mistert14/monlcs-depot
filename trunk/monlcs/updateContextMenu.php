<?

include "includes/secure_no_header.inc.php";

if ($id == 'actu') {
	if ($uid != 'admin')
		die('Aucune action possible ...');
}
//dans les scenarios les eleves et les autres profs n'ont aucun droit
if ($id == 'scenario_choix') {
	if (is_eleve($uid))
		die('Aucune action possible ...');
	
	if ($uid != $setter)
		die('Aucune action possible ...');

}


$content2 = "<div class=menuitems title=$id><B> Menu</B></div>";

$content2 .="<HR />";


if (!eregi('perso',$id) ) {

$content2 .="<div onclick=javascript:desktopSave(); class=menuitems title=Sauver le bureau>Enregistrer le bureau</div>";
$content2 .="<div onclick=javascript:giveRessources('$id'); class=menuitems title=Ressources>Ressources</div>";

//notes autorisees ?
$fixNote = ($id == 'scenario_choix') && ($uid == $setter);

if (is_admin('monlcs_is_admin',$uid) == 'Y')
	$content2 .="<div onclick=ajoutNote('$id'); class=menuitems title=Ajout note>Ajouter une note</div>";
else {	
if (!is_eleve($uid) && ( ($id == 'bureau') || ($id == 'perso4') || ($id == 'vs' ) || $fixNote ) )
	$content2 .="<div onclick=ajoutNote('$id'); class=menuitems title=Ajout note>Ajouter une note</div>";
}
}


if (eregi('perso',$id)) {
if ($id == 'perso4' ) {
	$content2 .="<div onclick=javascript:scenario('$id'); class=menuitems title=Sc&eacute;nario>Publier un sc&eacute;nario</div>";
	} 
	
}

		if ((is_admin('monlcs_is_admin',$uid) == 'Y') && ($id != 'scenario_choix') )
			$content2 .="<div onclick=javascript:publish('$id'); class=menuitems title=Figer>Figer des ressources</div>";
	




print(stringForJavascript($content2));
?>
