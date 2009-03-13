<?php
	include("includes/secure_no_header.inc.php");

	if ($_POST) {

		extract($_POST);
		$sql1 = "select * from ml_zones where id='$id' and user='$uid' ;";
		$cx1 = mysql_query($sql1) or die("ERREUR $sql1");
		if (mysql_num_rows($cx1) !=0 ) {
			$r = mysql_result($cx1,0,'rang');
			//  decrementer les tabs persos suivants avant de supprimmer
			$sql11 = "select * from ml_zones where rang > $r and user='$uid';";
			$cx11 = mysql_query($sql11) or die("ERREUR $sql11");
			for ($y=0;$y<mysql_num_rows($cx11);$y++) {
				$_id = mysql_result($cx11,$y,'id');
				$rang = mysql_result($cx11,$y,'rang')-1;
				$sql111 = "update ml_zones  set rang =  '$rang' where id='$_id' and user='$uid' ;";
				$cx111 = mysql_query($sql111) or die("ERREUR $sql111");
			}
		
			$sql2 = "delete from ml_zones where id='$id' and user='$uid' ;";
			$cx2 = mysql_query($sql2) or die("ERREUR $sql2");
		
			$sql201 = "select * from ml_tabs where id_tab='$id' and user='$uid' ;";
			$cx201 = mysql_query($sql201) or die("ERREUR $sql201");
			for ($x=0;$x<mysql_num_rows($cx201);$x++) {
				$id_tab = mysql_result($cx201,$x,'id');
				$nom = mysql_result($cx201,$x,'nom');
				//Suppression des notes perso dans onglets perso
				$sql221 = "delete from ml_notes where menu='$nom' and setter='$uid' ;";
				$cx221 = mysql_query($sql221) or die("ERREUR $sql221");
				//
				$sql211 = "delete from ml_geometry where id_menu='$id_tab' and user='$uid' ;";
				$cx211 = mysql_query($sql211) or die("ERREUR $sql211");
			}
		
			$sql21 = "delete from ml_tabs where id_tab='$id' and user='$uid' ;";
			$cx21 = mysql_query($sql21) or die("ERREUR $sql21");
	
		}
	die();
}
else die('erreur');
	

		
		
    	 
	



?>
