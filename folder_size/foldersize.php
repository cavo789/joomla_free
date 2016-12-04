<?php

/**
 * Author : AVONTURE Christophe - https://www.aesecure.com
 * 
 * Calculate the size of a website.  This script can be stored at the root level of the website.
 *
 * Changelog :
 * 
 * 2016-12-04 
 *    + The two tables are now sortable 
 *    + The table with extensions provides now checkboxes and only therefore to select severall extensions and the script will display the total size of the selection
 *    + The table with extensions has an extra column "Type" and will mention f.i. images, archives, webfonts, ... depending on the file's extension
 * 
 */

define('DEBUG',FALSE);

if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);

define('MB',1024*1024);            // One megabyte
define('BIG_FILES',2*MB);          // A big file has a size of ... MB at least

class aeSecureFct {
	
   /**
    * Safely read posted variables
    * 
    * @param type $name          f.i. "password"
    * @param type $type          f.i. "string"
    * @param type $default       f.i. "default"
    * @return type
    */
   public static function getParam($name, $type='string', $default='', $base64=false) {
      
      $tmp='';
      $return=$default;
      
      if (isset($_POST[$name])) {
         if (in_array($type,array('int','integer'))) {
            $return=filter_input(INPUT_POST, $name, FILTER_SANITIZE_NUMBER_INT);
         } elseif ($type=='boolean') {
            // false = 5 characters
            $tmp=substr(filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING),0,5);
            $return=(in_array(strtolower($tmp), array('on','true')))?true:false;
         } elseif ($type=='string') {
            $return=filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING);    
            if($base64===true) $return=base64_decode($return);
         } elseif ($type=='unsafe') {
            $return=$_POST[$name];            
         }
		 
      } else { // if (isset($_POST[$name]))
     
         if (isset($_GET[$name])) {
            if (in_array($type,array('int','integer'))) {
               $return=filter_input(INPUT_GET, $name, FILTER_SANITIZE_NUMBER_INT);
            } elseif ($type=='boolean') {
               // false = 5 characters
               $tmp=substr(filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING),0,5);
               $return=(in_array(strtolower($tmp), array('on','true')))?true:false;
            } elseif ($type=='string') {
               $return=filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING);    
               if($base64===true) $return=base64_decode($return);                 
            } elseif ($type=='unsafe') {
               $return=$_GET[$name];            
            }
         } // if (isset($_GET[$name])) 
				
      } // if (isset($_POST[$name]))
      
      if ($type=='boolean') $return=(in_array($return, array('on','1'))?true:false);
      
      return $return;	   
	  
   } // function getParam()

   public static function get_dir_size($directory, $recursive=true, &$arrSizeByExtension=array(), &$arrMD5=array()) {
      
      $FullSize = 0;          // Total size; included f.i. the big files
      $ReportedSize = 0;    // Size of small files (i.e. excluded big files (see constant BIG_FILES))
      
      foreach (glob(rtrim($directory, DS).DS.'*', GLOB_NOSORT) as $filename) {
            
         if(is_file($filename)) {
			 
			 // It's a file
			 
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!isset($arrSizeByExtension[$ext])) $arrSizeByExtension[$ext]=0;
            $FullSize += filesize($filename);
			
			// $arrMD5 contains the list of unique file based on their content.  If the file is unique, add one entry in the file
			if(!isset($arrMD5[md5_file($filename)])) $arrMD5[md5_file($filename)]=filesize($filename);
			
			if(filesize($filename)<BIG_FILES) $ReportedSize+=filesize($filename);
			
            $arrSizeByExtension[$ext]+=filesize($filename);
			
         } else { // if(is_file($filename))
		 
		    // It's a folder
			 
            if ($recursive) {
				list($full, $reported, $arrMD5)=aeSecureFct::get_dir_size($filename, $recursive, $arrSizeByExtension, $arrMD5);
				$FullSize+=$full;
				$ReportedSize+=$reported;
			} // if ($recursive)
			
         } // if(is_file($filename))
			 
      }  // foreach
	  
      return array($FullSize, $ReportedSize, $arrMD5);
	  
   } // function get_dir_size()
   
   public static function ShowFriendlySize($fsizebyte) {
	   
      if ($fsizebyte < 1024) {
		  
         $fsize = $fsizebyte." bytes";
		 
      } elseif (($fsizebyte >= 1024) && ($fsizebyte < 1048576)) {
		  
         $fsize = round(($fsizebyte/1024), 2);
         $fsize = $fsize." KB";
		 
      } elseif (($fsizebyte >= 1048576) && ($fsizebyte < 1073741824)) {
		  
         $fsize = round(($fsizebyte/1048576), 2);
         $fsize = $fsize." MB";
		 
      } elseif ($fsizebyte >= 1073741824) {
		  
         $fsize = round(($fsizebyte/1073741824), 2);
         $fsize = $fsize." GB";
		 
      }
	  
      return $fsize;
	  
   } // function ShowFriendlySize()
   
   public static function GetType($extension) {
      
      if (in_array($extension, array('bmp','gif','ico','icon','jpg','jpeg','png','psd','svg','tiff','webp'))) {
         return 'images';
      } else if (in_array($extension, array('7z','cab','gz','gzip','jpa','lzh','rar','zip'))) {
         return 'archives';
      } else if (in_array($extension, array('bak','log','tmp'))) {
         return 'temporary files';
      } else if (in_array($extension, array('eot','oft','ttf','woff','woff2'))) {
         return 'web fonts';
      } else if (in_array($extension, array('asf','avi','flv','mov','mp3','mp4','wmv'))) {
         return 'video';
      } else {
         return '';
      }
      
   } // function GetType()

} // class aeSecureFct
	
class aeSecureFolderSize {
	  
   protected static $instance = null;
   
   function __construct() {          
      return true;      
   } // function __construct()   
   
   public static function getInstance() {
      if (self::$instance === null) self::$instance = new aeSecureFolderSize();
      return self::$instance;
   }
   
   public function DoIt($sFolder) {
	   
      ini_set('max_execution_time', '0');
      ini_set('set_time_limit', '0');

      $sReturn = '<h3>By folders</h3><table id="tblFolders" class="table tablesorter table-hover table-bordered table-striped">'.
         '<thead><tr><td>Folder name</td><td>Size (human)</td><td>Size (bytes)</td></tr></thead>'.
         '<tbody>';

      // Get the list of subfolders (only first level)
      $dirs = array_filter(glob($sFolder.'*'), 'is_dir');
      array_push($dirs, $sFolder);
      asort($dirs);
   
      $arr=array();
   
      $FullSize=0;
      $ReportedSize=0;
      $UniqueSize=0;
   
      $arrMD5=array();
   
      foreach ($dirs as $dir) {
	   
         $isRootFolder=($dir===$sFolder);
         $dir=rtrim($dir,DS).DS;
	  
         list($full, $report)=aeSecureFct::get_dir_size($dir, ($dir!==$sFolder), $arr, $arrMD5);
	  
         $FullSize+=$full;
         $ReportedSize+=$report;
	  
         $sReturn.='<tr><td data-task="folder" '.($isRootFolder?'':'class="folder"').' data-folder="'.$dir.'">'.$dir.($isRootFolder?'*.*':'').'</td><td>'.aeSecureFct::ShowFriendlySize($full).'</td><td>'.$full.'</td></tr>';
	  
      } // foreach ($dirs as $dir)
   
      $sReturn.='</tbody></table><hr/>';
 
      // $arrMD5 contains the list of unique file based on their content => get the total size
      foreach ($arrMD5 as $md5=>$size) $UniqueSize+=$size;
   
      $sReturn='<p id="totalsize">The total size of '.$sFolder.' (subfolders included) is '.aeSecureFct::ShowFriendlySize($FullSize).'<br/>'.
         '<span id="reportedsize">Files greater or equal to '.aeSecureFct::ShowFriendlySize(BIG_FILES).' excluded : '.aeSecureFct::ShowFriendlySize($ReportedSize).'</span>&nbsp;'.
	     '<span id="uniquesize">Duplicate files excluded : '.aeSecureFct::ShowFriendlySize($UniqueSize).'</span></p>'.$sReturn;   
   
      // ---------------------------------------------------------------------
      // Now get the size by extensions   
      // ---------------------------------------------------------------------
   
      $sReturn.='<h3>By extensions</h3><table id="tblExtensions" class="table tablesorter table-hover table-bordered table-striped">'.
         '<thead><tr><td>#</td><td>Files\'s Extension</td><td>Size (human)</td><td>Site (bytes)</td><td>Type</td></tr></thead>'.
         '<tbody>';
   
      $totsize=0;
      ksort($arr);
      foreach ($arr as $key=>$size) {
         $chk='<input type="checkbox" value="'.$size.'">';
         $sReturn.='<tr><td style="width:30px;">'.$chk.'</td><td>'.$key.'</td><td>'.aeSecureFct::ShowFriendlySize($size).'</td><td>'.$size.'</td><td>'.aeSecureFct::GetType(strtolower($key)).'</td></tr>';
         $totsize+=$size;
      }
   
      $sReturn.='</tbody></table>';
   
      return $sReturn;
	  
   } // function DoIt()
   
} // class aeSecureFolderSize
 
   // -------------------------------------------------
   //
   // ENTRY POINT
   //
   // -------------------------------------------------
   
   if (DEBUG===TRUE) {
      ini_set("display_errors", "1");
      ini_set("display_startup_errors", "1");
      ini_set("html_errors", "1");
      ini_set("docref_root", "http://www.php.net/");
      ini_set("error_prepend_string", "<div style='color:red; font-family:verdana; border:1px solid red; padding:5px;'>");
      ini_set("error_append_string", "</div>");
      error_reporting(E_ALL);
   } else {	   
      ini_set('error_reporting', E_ALL & ~ E_NOTICE);	  
   }
   
   // Get the folder
   $sFolder=aeSecureFct::getParam('folder','string','',false);
		 
   if ($sFolder=='') {            
      if(isset($_SERVER['SCRIPT_FILENAME'])) {
         // In case of foldersize.php isn't in the current folder but is a symbolic link.
         // The folder should be the current folder and not the folder where foldersize.php is stored
         $sFolder=str_replace('/',DS,dirname($_SERVER['SCRIPT_FILENAME'])).DS;         
      } else {
         $sFolder=__DIR__;
      }
   } else {
      $sFolder=urldecode($sFolder);
      if((substr($sFolder,-3))=='*.*') $sFolder=substr($sFolder,0,strlen($sFolder)-3);
   }   
   
   $sFolder=rtrim($sFolder, DS).DS;
   
   // Get the task
      
   $task=aeSecureFct::getParam('task','string','',false);
   
   if ($task!='') {
	   
      switch ($task) {
		  
         case 'doIt' : 
		 
            // Add a click option to each part of the full folder name so the user can go up in the directory structure
   
            $arr=explode(DIRECTORY_SEPARATOR,$sFolder);
            $sURLFolder='';
            $sSubFolder='';
            foreach ($arr as $tmp) {
               $sSubFolder.=$tmp.DS;
               $sURLFolder.='<span data-task="folder" data-folder="'.$sSubFolder.'" class="folder">'.$tmp.'</span>'.DS;
            }
   
            $sURLFolder=rtrim($sURLFolder,DS);
		 
            $sReturn='<div class="page-header"><h3>'.$sURLFolder.'</h3></div>'.
               '<div class="navig"><a href="#tblFolders">By folders</a> - <a href="#tblExtensions">By file\'s extensions</a><hr/></div>';
   		 
            $aeSecureFolderSize=aeSecureFolderSize::getInstance();
            $sReturn.=$aeSecureFolderSize->doIt($sFolder);
            unset($aeSecureFolderSize);
			
            $sReturn.='<script>initSort();</script>';
            
            echo $sReturn;
			
			break;
         
         case 'killMe' : 
		 
            
            $return.='<p class="text-success">Le script '.__FILE__.' a &eacute;t&eacute; supprim&eacute; du serveur avec succ&egrave;s</p>';
            
            // Kill this script
            unlink(__FILE__);
            
            echo $return;
            
			break;
			
      } // switch
	  
      die();
	   
   } // if ($task!='')
  
?>
<!DOCTYPE html>
<html lang="en">

   <head>
      <meta charset="utf-8"/>
      <meta name="author" content="aeSecure (c) Christophe Avonture" />
      <meta name="robots" content="noindex, nofollow" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8;" />
      <title>aeSecure - FolderSize</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.25.3/css/theme.ice.min.css" rel="stylesheet" media="screen" />
      <link href= "data:image/x-icon;base64,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" rel="shortcut icon" type="image/vnd.microsoft.icon"/>  
      <style>
         .folder{text-decoration:underline;cursor:pointer;}
         #totalsize{font-size:1.2em;}
         #reportedsize{font-weight:normal;}
      </style>
   </head>
   
   <body>
   
      <div class="container">
	  
         <div class="page-header"><h1>aeSecure - Folder size</h1></div>
         <div id="intro">
            <p>Cliquez sur le bouton 'Démarrer' pour scanner l'intégralité du site web afin de générer deux tableaux qui vont reprendre la taille du site web, dossiers par dossiers et par extensions.</p>
            <br/>
            <button type="button" id="btnDoIt" class="btn btn-primary">Détermine l'occupation disque du site</button>
   		    <button type="button" id="btnKillMe" class="btn btn-danger pull-right" style="margin-left:10px;">Supprimer ce script</button>
		    <br/>
         </div>
		 <input type="hidden" name="folder" id="folder" value="<?php echo $sFolder; ?>"/>
         <div id="Result">&nbsp;</div>
      </div>  
      <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.25.3/js/jquery.tablesorter.combined.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-noty/2.3.8/packaged/jquery.noty.packaged.min.js"></script>
	  
      <script type="text/javascript">

         function formatBytes(bytes) {
            if(bytes < 1024) return bytes + " Bytes";
            else if(bytes < 1048576) return(bytes / 1024).toFixed(2) + " KB";
            else if(bytes < 1073741824) return(bytes / 1048576).toFixed(2) + " MB";
            else return(bytes / 1073741824).toFixed(2) + " GB";
         };

         function initSort() {

            $("#tblFolders").tablesorter({
               theme: "ice",
               widthFixed: false,
               sortMultiSortKey: "shiftKey",
               sortResetKey: "ctrlKey",
               headers: {
                  0: {sorter: "text"},                 // Folder name
                  1: {sorter: "digit"},                // Folder size in Ko, MB, ...
                  2: {sorter: "digit"}                 // Folder size in integer
               },
               ignoreCase: true,
               headerTemplate: "{content} {icon}",
               widgets: ["uitheme", "filter"],
               initWidgets: true,
               widgetOptions: {
                  uitheme: "ice"
               },               
               sortList: [[2,1]]  // Sort by default on the folder size, descending
            }); // $("#tblFolders")

            $("#tblExtensions").tablesorter({
               theme: "ice",
               widthFixed: false,
               sortMultiSortKey: "shiftKey",
               sortResetKey: "ctrlKey",
               headers: {
                  0: {sorter: false, filter:false},    // checkbox
                  1: {sorter: "text"},                 // Extensions
                  2: {sorter: "digit"},                // Total size in Ko, MB, ...
                  3: {sorter: "digit"},                // Total size in integer
                  4: {sorter: "text"}                  // Type
               },
               ignoreCase: true,
               headerTemplate: "{content} {icon}",
               widgets: ["uitheme", "filter"],
               initWidgets: true,
               widgetOptions: {
                  uitheme: "ice"
               },               
               sortList: [[3,1]]  // Sort by default on the total size size, descending
            }); // $("#tblExtensions")

            $ExtensionsSize=0;
            $('#tblExtensions input[type=checkbox]').click(function () {
               if (this.checked) {
                  $ExtensionsSize += parseInt($(this).val());
               } else {
                  $ExtensionsSize -= parseInt($(this).val());
               }
               var n = noty({
                  text: 'Total size for the selection : '+formatBytes($ExtensionsSize),
                  theme: 'relax',
                  timeout: 2400,
                  layout: 'bottomRight',
                  type: 'success'
               }); // noty() 
            });


         } // function initSort()

         $('#btnDoIt').click(function(e)  { 

            e.stopImmediatePropagation(); 

            var $data = new Object;
            $data.task = "doIt"
            $data.folder = $("#folder").val();

            $.ajax({

               beforeSend: function() {
                  $('#Result').html('<div><span class="ajax_loading">&nbsp;</span><span style="font-style:italic;font-size:1.5em;">Un peu de patience svp...</span></div>');
                  $('#btnDoIt').prop("disabled", true);  
                  $('#btnKillMe').prop("disabled", true);           
               },// beforeSend()               
               async:true,
               type:"POST",
               url: "<?php echo basename(__FILE__); ?>",
               data:$data,
               datatype:"html",
               success: function (data) { 			   
                  $('#btnDoIt').prop("disabled", false);
                  $('#btnKillMe').prop("disabled", false);  
                  $('#Result').html(data);				  

                  $('[data-task="folder"]').click(function(){          
                     var $url=$(this).attr('data-folder');
                     $('#folder').val($(this).attr('data-folder'));					 
                     // And run the script again
                     $('#btnDoIt').click();
                  }); // $('[data-task="folder"]')

               }, // success
               error: function(Request, textStatus, errorThrown) {
                  $('#btnDoIt').prop("disabled", false);
                  $('#btnKillMe').prop("disabled", false);
                  // Display an error message to inform the user about the problem
                  var $msg = '<div class="bg-danger text-danger img-rounded" style="margin-top:25px;padding:10px;">';
                  $msg = $msg + '<strong>An error has occured :</strong><br/>';
                  $msg = $msg + 'Internal status: '+textStatus+'<br/>';
                  $msg = $msg + 'HTTP Status: '+Request.status+' ('+Request.statusText+')<br/>';
                  $msg = $msg + 'XHR ReadyState: ' + Request.readyState + '<br/>';
                  $msg = $msg + 'Raw server response:<br/>'+Request.responseText+'<br/>';
                  $url='<?php echo basename(__FILE__); ?>?'+$data.toString();
                  $msg = $msg + 'URL that has returned the error : <a target="_blank" href="'+$url+'">'+$url+'</a><br/><br/>';
                  $msg = $msg + '</div>';
                  $('#Result').html($msg);
               } // error                 
            }); // $.ajax()
         }); // $('#btnDoIt').click()

          // Remove this script
          $('#btnKillMe').click(function(e)  { 
            e.stopImmediatePropagation(); 

            var $data = new Object;
            $data.task = "killMe"

            $.ajax({
               beforeSend: function() {
                  $('#Result').empty();
                  $('#btnDoIt').prop("disabled", true); 
                  $('#btnKillMe').prop("disabled", true);                            
               },// beforeSend()
               async:true,
               type:"POST",
               url: "<?php echo basename(__FILE__); ?>",
               data:$data,
               datatype:"html",
               success: function (data) { 
                  $('#intro').remove();
                  $('#Result').html(data);     
               }
            }); // $.ajax()
         }); // $('#btnKillMe').click()		 

      </script>

   </body>
</html>