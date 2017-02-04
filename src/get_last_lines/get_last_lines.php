/**
 * Code PHP pour lire un fichier texte et afficher les dernières lignes du fichier; comme un tail() sous Unix
 */


<?php

define('FILENAME', 'C:\Sites\j3\logs\very_big.log'); // METTRE ICI LE FULLNAME DU FICHIER LOG
define('MAX_NUMLINES', 100);

/**
* A few helping functions
*/
class aeSecureHTTP
{

   /**
    * Send browser HTTP header to request the browser to never cache this page
     */
    public static function HeaderNoCache()
    {
  
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
        return true;
    }
} // class aeSecureHelper

/**
* A few helping functions
*/
class aeSecureFiles
{

   /**
     * Return the number of lines of a text file
   */
    public static function getFileNumberOfLines($filename)
    {
        if (is_file($filename)) {
            $linecount = 0;
            $handle = fopen($filename, "r");
            while (!feof($handle)) {
                $line = fgets($handle);
                $linecount++;
            }
            fclose($handle);
        } else {
            $linecount=0;
        }
        return $linecount;
    } // function getFileNumberOfLines()
   
   /**
    * Get the last xxx lines of a text file
    */
    public static function getFileLastLines($filename, $lines = 10)
    {
        if (is_file($filename)) {
            return trim(implode("", array_slice(file($filename), -$lines)));
        } else {
            return null;
        }
    } // function getFileLastLines()
} // class aeSecureFiles


// This page shouldn't be cached
aeSecureHTTP::HeaderNoCache();

// Get the number of lines in the text file
$linecount=aeSecureFiles::getFileNumberOfLines(FILENAME);

$sLines='';
  
if (file_exists(FILENAME)) {
    $maxLines=isset($_GET['max'])?(int) $_GET['max']:MAX_NUMLINES;
      
   // Get the last lines of the file
    $tmp=aeSecureFiles::getFileLastLines(FILENAME, $maxLines);
     
    $i=($linecount-$maxLines);
    foreach (preg_split("/((\r?\n)|(\r\n?))/", $tmp) as $line) {
        $i+=1;
        $sLines='<tr><td>'.$i.'</td><td>'.$line.'</td></tr>'.$sLines;
    }
    $sLines='<table class="table table-striped table-bordered table-hover"><thead><tr><th>#</th><th>Line</th></tr></thead><tbody>'.$sLines.'</tbody></table>';
} else {
    $sLines='<div class="alert alert-danger" role="alert"><strong>File not found</strong>&nbsp;'.FILENAME.'</div>';
}
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>aeSecure - Last <?php echo $maxLines; ?> lines of <?php echo basename(FILENAME);?></title>     
      <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" />
   </head>
   <body>
   <div class="container-fluid" role="main" >
      <h1>aeSecure - Get last <?php echo $maxLines; ?> lines of <?php echo basename(FILENAME);?></h1><p class="lead"><?php echo FILENAME;?></p>
        <?php echo $sLines; ?>
       <p><em><u>Querystring parameter</u>: &amp;max=99 to specify the number of lines</em></p>
   </div>
   </body>
</html>