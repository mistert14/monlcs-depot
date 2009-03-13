<?php

	include "includes/secure_no_header.inc.php";
	if (is_eleve($uid))
		print(stringForJavascript("eleve"));
	else {
		if ($ML_Adm == 'Y') {
			print(stringForJavascript("admin"));
 		} else { 
       			print(stringForJavascript("prof"));
   		}
	}

?>
