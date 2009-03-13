<?php

include("includes/secure_no_header.inc.php");

	

		$content ="<html><body><table>"
		."<tr><td><div class=div1><B>Nom onglet:&nbsp; </B></td><td><input id=onglet_name name=onglet_name value=mon_onglet /></div></td></tr>"
		."<tr><td><div class=div1><B>Nom des menus<BR /> s&eacute;par&eacute;s par un ;&nbsp; </B></td><td><input id=liste_menu name=liste_menu value=mon_menu /></div></td></tr>"
		."<tr><td colspan=2><center>-</center></td></tr>"
		."<tr><td colspan=2><center><input type=button value=Cr&eacute;er onclick=javascript:sauverOnglets(); /></center></td></tr>"
		."</table></body></html>";
		print(stringForJavascript($content));
		
    	 
	



?>
