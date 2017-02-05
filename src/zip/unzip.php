<?php

ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
ini_set("html_errors", "1");
ini_set("docref_root", "http://www.php.net/");
ini_set("error_prepend_string", "<div style='color:red; font-family:verdana; border:1px solid red; padding:5px;'>");
ini_set("error_append_string", "</div>");
error_reporting(E_ALL);

/**
  * AVONTURE Christophe - www.aesecure.com
  *
  * Décompresse chaque fichier ZIP présent dans le dossier où est placé unzip.php.
  * Si AUTO_DELETE_ONCE_UNCOMPRESSED est mis sur true, le fichier ZIP est détruit une fois décompressé
  *
  * ==> Il faut placer les fichiers ZIP à la racine du site <==
  *
  */
    
define('AUTO_DELETE_ONCE_UNCOMPRESSED', true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
   
   <div class="container">
      <div class="jumbotron">
         <div class="container"><h1>aeSecure - Unzip</h1></div>
      </div>
   
<?php

ini_set('max_execution_time', '0');
ini_set('set_time_limit', '0');


// Get the list of ZIP files in the current folder
$dir = ".";
$dh  = opendir($dir);
$files=null;

while (false !== ($filename = readdir($dh))) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if ($ext=='zip') {
        $files[] = $filename;
    }
}

if ($files!=null) {
    sort($files);
}
   
// And, if more than one, uncompress files one by one
   
if (count($files)>0) {
    echo '<p>There are '.count($files).' to decompress...</p>';
    $i=0;
     
    foreach ($files as $file) {
        $i++;

        $zip = new ZipArchive;
        $res = $zip->open($file);
         
        if ($res === true) {
            $zip->extractTo('./');
            $zip->close();
            
            echo '<h2 class="text-success">'.$i.'. '.$file.' has been extracted.</h2>';
            if (AUTO_DELETE_ONCE_UNCOMPRESSED) {
                unlink($file);
            }
        } else {
            echo '<h2 class="text-danger">'.$i.'. '.$file.' - Failure detected during the extraction.</h2>';
        } // if ($res === TRUE)
    } // foreach ($files as $file)
} else { // if (count($files)>0)
    echo '<p>No zip files found in '.__DIR__.'</p>';
} // if (count($files)>0)
   
// This file is no more needed
unlink(__FILE__);
       
?>
   </div>
</body>
</html>