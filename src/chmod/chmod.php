<?php

 ini_set("display_errors", "1");
 ini_set("display_startup_errors", "1");
 ini_set("html_errors", "1");
 ini_set("docref_root", "http://www.php.net/");
 ini_set("error_prepend_string", "<div style='color:red; font-family:verdana; border:1px solid red; padding:5px;'>");
 ini_set("error_append_string", "</div>");
 error_reporting(E_ALL);

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
         <div class="container"><h1>aeSecure - chmod</h1></div>
      </div>

<?php

ini_set('max_execution_time', '0');
ini_set('set_time_limit', '0');

/**
 *  chmod récursif - dossiers 755 et fichiers 644
 */

function rChmod($dir = './')
{

    $sReturn='';

    $d=new RecursiveDirectoryIterator($dir);

   // By default the chmod for a file should be 644 but, on some hoster, it should be 640.
   // To determine this, just get the chmod of this file, this script and use it as default
    $chmodFile=substr(sprintf('%o', fileperms(__FILE__)), -4);

    foreach (new RecursiveIteratorIterator($d, 1) as $path) {
        // Don't process . and .. folders
        if (in_array(basename($path), array('.','..'))) {
            continue;
        }

        // Get the current chmod
        $current=substr(sprintf('%o', fileperms($path)), -4);

        // Determine the good permission, the one the folder/file should have
        $attr=($path->isDir()?'0755':$chmodFile);


        if ($path->isDir()) {
            // No need to make something if the current chmod is 755 or 750
            $continue=!in_array($current, array('0755','0750'));

            // On a Windows OS, don't bother for chmod 777
            if ($continue && ((strtoupper(substr(PHP_OS, 0, 3))==='WIN') && ($current=='0777'))) {
                $continue=false;
            }
        } else {
            // it's a file
            $continue=!in_array($current, array('0666','0644','0640'));
        }

        if ($continue===true) {
            // The permission of the folder/file isn't correct

            chmod($path, octdec($attr));
            $newchmod=substr(sprintf('%o', fileperms($path)), -4);

            if ($attr!=octdec($newchmod)) {
                $sReturn.='<li class="text-danger" style="font-size:2em;">ERROR - The current chmod for '.$path.' is '.octdec($newchmod).' and should be '.octdec($attr).' - EXITING</li>';

                break;
            } else {
                $sReturn.='<li class="text-success">'.$path.' is now '.octdec($newchmod).'</li>';
            }
        } // if ($continue===TRUE)
    } // foreach (new RecursiveIteratorIterator($d, 1) as $path)

    if ($sReturn!='') {
        $sReturn='<ul>'.$sReturn.'</ul>';
    } else {
        $sReturn='<p class="text-success">Nothing to change, chmods already correct</p>';
    }

    return $sReturn;
} // function rChmod()

echo rChmod(".");

?>
   </div>
</body>
</html>
