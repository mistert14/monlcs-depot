<?php
	include("includes/secure_no_header.inc.php");

	if ($_POST) {
		extract($_POST);
                $sql = "SELECT * from monlcs_db.ml_tabs where nom='$tab' and user='$uid'; ";
		$cx = mysql_query($sql) or die("Err SQL $sql");
		if (mysql_num_rows($cx) == 0 )
			die("Ce sous-menu ne peut être renommé");
                else {
	              $id = mysql_result($cx,0,'id');
			$nom = mysql_result($cx,0,'nom');

                     $clean = strtolower(str_replace(" ","_",$nouveau_titre));

			$sql1 = "UPDATE monlcs_db.ml_tabs SET caption='$nouveau_titre', nom='$clean' where id='$id'; ";
			$cx1 = mysql_query($sql1) or die("Err SQL $sql1");

			$sql11 = "UPDATE monlcs_db.ml_notes SET menu='$clean' where menu='$nom' and setter='$uid'; ";
			$cx11 = mysql_query($sql11) or die("Err SQL $sql11");
			

			die("Sous-menu renommé avec succès.");
		     }
	}
	else
		 die("Erreur de paramètres");


?>
