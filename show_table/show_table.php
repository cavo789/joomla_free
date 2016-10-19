<?php

/**
 * Christophe Avonture - https://www.aesecure.com
 * Written date  : 2016-10-16
 * Last modified : 2016-10-19
 *
 * This small script will execute a SQL statement against the database of your Joomla website and will show the result in a nice HTML table (bootstrap).
 * When the output is HTML, the jQuery tablesorter will be used to provide extra functionnalities like sorting and filtering.
 *
 * Parameters : 
 *
 *   * password : the password define in the PASSWORD constant.
 *
 *   * format   : can be 'HTML' (default) or 'RAW' 
 *                RAW will only output a table tag without html headers or javascript.   RAW will be usefull when f.i. the table
 *                will be used in a spreadsheet application or as input for an another program.
 *                For instance : in Excel, you can create a Data Query.  Use the &format=RAW parameter to get a perfect table for Excel.
 *
 *    Add yours : Add your own parameters !  For instance a filter (period=xxxx), a selection (tablename=a_table), a limit (limit=10), ... 
 *
 * Example : https://youriste/show_table.php?password=Joomla&format=RAW
 *  
 */

// SQL statement for retrieving informations from, f.i., the content table
define('SQL','SELECT C.id As Article_ID, C.title As Article_Title, G.title As Category_Title, '.
   'U.name As Author_Name, C.Hits As Hits, C.language As Language, C.created As Writen_Date '.
   'FROM `#__content` C LEFT JOIN `#__categories` G ON C.catid = G.id '.
   'LEFT JOIN `#__users` U on C.created_by=U.id '.
   'WHERE (state=1) '.
   'ORDER BY C.created DESC');

define('DS',DIRECTORY_SEPARATOR);
   
// Root folder of Joomla. If you've save this script in the root folder of Joomla, just leave __DIR__ otherwise you'll need
// to update this constant and specify your own root
define('ROOT',__DIR__);  
//define('ROOT',dirname(__DIR__));  // Use this line instead the previous if you've put the script in a subfolder of your website root 

// Password to use.  The default one is "Joomla"
define('PASSWORD','57ac91865e5064f231cf620988223590');   // If you want to change, use an online tool like f.i. http://www.md5.cz/

   // Check if the password is valid; if not, stop immediatly
   $password=filter_input(INPUT_GET, 'password', FILTER_SANITIZE_STRING);
   if(md5($password)!==PASSWORD) { 
      header('HTTP/1.0 403 Forbidden'); 
	  echo '<form action="'.$_SERVER['PHP_SELF'].'" method="GET">Password: <input type="text" name="password" /><input class="Submit" type="submit" name="submit" /></form>';
	  die(); 
   }
   
   if (!file_exists($config=rtrim(ROOT,DS).DS.'configuration.php')) die('<strong>The file '.$config.' can\'t be found, please review the ROOT constant to match your website root folder</strong>');

   // Ok, password valid, get the requested format : HTML or RAW.  If nothing is specified, HTML will be the default one
   $format=strtoupper(filter_input(INPUT_GET, 'format', FILTER_SANITIZE_STRING));
   $RAW=($format==='RAW');

   // Load the Joomla framework
   if (!defined('_JEXEC')) define('_JEXEC',1);
   if (!defined('JPATH_BASE')) define('JPATH_BASE',rtrim(ROOT,DS));
   if (!defined('JPATH_PLATFORM')) define('JPATH_PLATFORM', rtrim(ROOT,DS).DS.'libraries');

   //include joomla core files (disable errors because Joomla produde WARNINGs and NOTICES)
   $error=error_reporting();
   error_reporting(0);
   if(file_exists($fname=JPATH_BASE.'/includes/defines.php')) require_once($fname);
   if(file_exists($fname=JPATH_BASE.'/includes/framework.php')) require_once($fname);
   if(file_exists($fname=JPATH_BASE.'/includes/application.php')) require_once($fname);       // No more present since J3.2
   if(file_exists($fname=JPATH_BASE.'/libraries/joomla/factory.php')) require_once($fname);
   if(file_exists($fname=JPATH_BASE.'/libraries/joomla/log/log.php')) require_once($fname);
   error_reporting($error);
      
   require_once(JPATH_BASE.'/configuration.php');
	
   // Start the output, also for RAW output to allow the correct support of UTF8
		  
   echo '<!DOCTYPE html><html lang="en">'.
      '<head>'.
	  '<meta charset="utf-8"/>'.
	  '<meta http-equiv="content-type" content="text/html; charset=UTF-8" />'.
      '<meta name="robots" content="noindex, nofollow" />'.
      '<meta name="viewport" content="width=device-width, initial-scale=1.0" />'.
      '<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8;" />';

   if (!$RAW) {
	  
      echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';
	  echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.25.3/css/theme.ice.min.css" rel="stylesheet" media="screen" />';
   }
   
   echo '</head><body>';
   
   if ($RAW!=TRUE) echo '<div style="margin:10px;">';

   // Run the query and display the table
   
   $db = JFactory::getDBO();
   $db->setQuery(SQL);
   
   $rows = $db->loadObjectList();

   echo '<table id="tbl" class="table table-striped">';
   
   // Output the list of fields name
   $line='';   
   foreach ($rows[0] as $field => $value) $line.='<td>'.$field.'</td>';
   echo '<thead><tr>'.$line.'</tr></thead>';
 
   echo '<tbody>';
   
   foreach($rows as $row) {
      $line='';
      foreach ($row as $field => $value) $line.='<td>'.$value.'</td>';
	  echo '<tr>'.$line.'</tr>';		
   }
   
   echo '</tbody></table>';

   if (!$RAW) {
      echo '<p><strong>Number of records &nbsp;:&nbsp;'.number_format(count($rows)).'</strong></p>';
	  echo '</div>';
      echo '<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>';
      echo '<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>';
      echo '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.27.8/js/jquery.tablesorter.combined.min.js"></script>';
      echo '<script>'.
         '$("#tbl").tablesorter({'.
         '   theme: "ice",'.
         '   widthFixed: false,'.
         '   sortMultiSortKey: "shiftKey",'.
         '   sortResetKey: "ctrlKey",'.
         '   ignoreCase: true,'.
         '   headerTemplate: "{content} {icon}",'.
         '   widgets: ["uitheme", "filter"],'.
         '   initWidgets: true,'.
         '   widgetOptions: {'.
         '      uitheme: "ice"'.
         '   },'.
         '});</script>';
   }
   
   echo '</body></html>';