<?
include "includes/secure_no_header.inc.php"
?>
<BODY>
<div id = "scen-container">
<form method="post" action="processScen.php" onsubmit="multipleSelectOnSubmit()">
<? 
if ($_POST) {

extract($_POST);
$fen = $fen;
$ress = explode(',',$fen);
echo "<div id=scen-contenu>";
echo "<div id=scen-titre>Sauvegarde du scenario</div>";
echo "<div class=scen-nom>Titre:<input id=titre name=titre style=\"width: 100px;\" /></div>";
echo "<div class=scen-mat>Matiere:<select id=\"matiere\" name=\"matiere\">";
$matieres =search_groups("cn=mati*");
foreach($matieres as $mat) {
$eq = $mat['cn'];
echo "<option value='".$eq. "' class='group'>$eq</option>";
}
echo "</select></div>";
echo "</div>";
?>
<div class="scen-ldapBOX">
	<select multiple name="fromBox" id="fromBox">
<?


if (is_admin('monlcs_is_admin',$uid) == 'Y') {
	$groups =search_groups("cn=*");
	$gr = array();
			foreach($groups as $group) {
				$gr[] = $group['cn'];
			}
			////remouliner le $groups pour presenter Admins Profs Eleves
			
			$flux = implode('#', $gr);
			$pattern = array("Profs#","Eleves#","Admins#");
			$repl = array("","","");
			$flux = str_replace($pattern, $repl, $flux);
			$flux = "Admins#Eleves#Profs#".$flux;
			$gr = explode('#',$flux);
			$groups = array();
			for ($x=0;$x<count($gr);$x++) {
				$g = array();
				$g['cn'] = $gr[$x];
				$groups[] = $g;
			}
	} else {
	list($user,$groups)=people_get_variables($uid, true);
	}

foreach($groups as $group) {
$eq = $group['cn'];

if (is_admin('monlcs_is_admin',$uid) != 'Y') {
if (eregi('equipe',$eq)) {
	$info = explode('_',$eq);
	$info[0] = 'Classe';
	$eq2 = implode('_',$info); 
	echo "<option value='".$eq2. "' class='group'>$eq2</option>";
	}
	}



echo "<option value='".$eq. "' class='group'>$eq</option>";
}
?>
	
</select>

<select multiple name="toBox" id="toBox">
</select>
</form>

<script type="text/javascript">
createMovableOptions("fromBox","toBox",300,200,'Groupes disponibles','Groupes selectionn&eacute;s');
</script>

<p>Choissisez le(s) groupe(s) cible(s)</p>
</div>

		
<div id="bouton-sauve-scen">
	<a class="go" href="#" onclick="javascript:saveScenario();">Enregistrer</a>
</div>
<?
}//if get
?>
</div>
</body>
</html>