<?
include "includes/secure_no_header.inc.php";

if (is_eleve($uid))
	die('Ceci est impossible pour un �l�ve!');

if ($_POST['id'] == 'lcs')
	die('Ceci est impossible ici!');


$upload_dir = "/var/www/monlcs/vignettes"; // Directory for file storing
                                            // filesystem path

$web_upload_dir = "/vignettes"; // Directory for file storing
                          // Web-Server dir 

$tf = $upload_dir.'/'.md5(rand()).".test";
$f = @fopen($tf, "w");
if ($f == false) 
    die("Erreur fatale ne peut �crire sur {$upload_dir} . Pensez �  'chmod 777 {$upload_dir}' ");
fclose($f);
unlink($tf);



// FILEFRAME section of the script
if (isset($_POST['fileframe'])) 
{
    $result = 'ERROR';
    $result_msg = 'No FILE field found';

    if (isset($_FILES['file']))  // file was send from browser
    {
        if ($_FILES['file']['error'] == UPLOAD_ERR_OK)  // no error
        {
            $filename = $_FILES['file']['name']; // file name 
            move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir.'/'.$filename);
            // main action -- move uploaded file to $upload_dir 
            $result = 'OK';
        }
        elseif ($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE)
            $result_msg = 'La taille de votre image est trop grande cf php.ini ';
        else 
            $result_msg = 'Erreur inconnue';

        
    }

    
    echo '<html><head><title>-</title></head><body>';
    echo '<script language="JavaScript" type="text/javascript">'."\n";
    echo 'var parDoc = window.parent.document;';
   
    if ($result == 'OK')
    {
        // Simply updating status of fields and submit button
        //echo 'parDoc.getElementById("upload_status").value = "Le fichier est correctement charg�";';
        echo 'parDoc.getElementById("filename").value = "'.$filename.'";';
        //echo 'parDoc.getElementById("filenamei").value = "'.$filename.'";';
        //echo 'parDoc.getElementById("upload_button").disabled = false;';
    }
    else
    {
        echo 'parDoc.getElementById("upload_status").value = "ERROR: '.$result_msg.'";';
    }

    echo "\n".'</script></body></html>';

    exit(); // do not go futher 
}
// FILEFRAME section END



// just userful functions
// which 'quotes' all HTML-tags and special symbols 
// from user input 
function safehtml($s)
{
    $s=str_replace("&", "&amp;", $s);
    $s=str_replace("<", "&lt;", $s);
    $s=str_replace(">", "&gt;", $s);
    $s=str_replace("'", "&apos;", $s);
    $s=str_replace("\"", "&quot;", $s);
    return $s;
}

if (isset($_POST['description']))
{
    $filename = $_POST['filename'];
    $size = filesize($upload_dir.'/'.$filename);
    $date = date('r', filemtime($upload_dir.'/'.$filename));

} 
 
?>
<!-- Beginning of main page -->
<html><head>
<title>IFRAME Async file uploader example</title>
</head>
<body>

<h3>Ressource</h3>

<?php

echo "<table><tr><td><div onclick=javascript:viewUrl2(); >$view_img</div></td><td><div onclick=javascript:addUrl(); >$add_img</div></td></tr></table>"
."<B>Url: </B><input id=urlAdd name=urlAdd size=40 value=http://www.google.fr />"
." <BR /><B>Titre: </B><input id=titreAdd name=titreAdd size=40 value=Mon_titre />"
."<BR /><input type=checkbox id=statut  /> Rendre la ressource publique"
."<BR /><input type=checkbox id=RSS  /> La ressource est un flux RSS"
."<input type=\"hidden\" name=\"filename\" id=\"filename\">";
?>


<h3>Miniature:.
<A href="#" onclick="javascript:check_vignette();">V�rifier</A></h3>
<input id="choix_mini" name="choix_mini" value="rien" checked type=radio href=# onclick="javascript:gen_clean();">Aucune (animation - widget)<BR />
<input id="choix_mini" name="choix_mini" value="thumbalizr" type=radio href=# onclick="javascript:gen_vignette();">G�n�rer la vignette sur thumbalizr.org<BR />
<input id="choix_mini" name="choix_mini" value="upload" type=radio href=# onclick="javascript:gen_upload();">Uploader la vignette<BR />

<div id="upload_panel">
<form action="<?=$PHP_SELF?>" target="upload_iframe" method="post" enctype="multipart/form-data">
<input type="hidden" name="fileframe" value="true">
<!-- Target of the form is set to hidden iframe -->
<!-- From will send its post data to fileframe section of 
     this PHP script (see above) -->

<label for="file">Miniature:</label><br>
<!-- JavaScript is called by OnChange attribute -->
<input type="file" name="file" id="file" onChange="jsUpload(this)">
</form>
</div>

<iframe name="upload_iframe" style="width: 400px; height: 100px; display: none;">
</iframe>




</body>
</html>



