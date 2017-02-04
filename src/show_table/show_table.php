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

// This is an example : this SQL will retrieve all users defined in your database and will return ID, name, pseudo, email, register date, last visit 
// date and the group of the user (registered, super-users, ...)
define('SQL', 'SELECT U.id UserID, U.name Name, U.username UserName, U.email eMail, U.registerDate RegisterDate, '.
   'U.lastvisitDate LastVisitDate, G.title GroupTitle '.
   'FROM `#__users` U '.
   'LEFT JOIN (`#__user_usergroup_map` as UG) ON UG.user_id=U.id '.
   'LEFT JOIN (`#__usergroups` as G) on UG.group_id=G.id '.
   'ORDER BY registerDate DESC, name, GroupTitle ASC');
/*
// SQL statement for retrieving informations from, f.i., the content table
define('SQL','SELECT C.id As Article_ID, C.title As Article_Title, G.title As Category_Title, '.
   'U.name As Author_Name, C.Hits As Hits, C.language As Language, C.created As Writen_Date '.
   'FROM `#__content` C LEFT JOIN `#__categories` G ON C.catid = G.id '.
   'LEFT JOIN `#__users` U on C.created_by=U.id '.
   'WHERE (state=1) '.
   'ORDER BY C.created DESC');
*/
   
define('DEBUG', false);
define('DS', DIRECTORY_SEPARATOR);
   
// Root folder of Joomla. If you've save this script in the root folder of Joomla, just leave __DIR__ otherwise you'll need
// to update this constant and specify your own root
define('ROOT', __DIR__);
//define('ROOT',dirname(__DIR__));  // Use this line instead the previous if you've put the script in a subfolder of your website root 

// Password to use.  The default one is "Joomla"
define('PASSWORD', '57ac91865e5064f231cf620988223590');   // If you want to change, use an online tool like f.i. http://www.md5.cz/

   // Check if the password is valid; if not, stop immediatly
   $password=filter_input(INPUT_GET, 'password', FILTER_SANITIZE_STRING);
if (md5($password)!==PASSWORD) {
    header('HTTP/1.0 403 Forbidden');
    echo '<form action="'.$_SERVER['PHP_SELF'].'" method="GET">Password: <input type="text" name="password" /><input class="Submit" type="submit" name="submit" /></form>';
    die();
}
   
if (!file_exists($config = rtrim(ROOT, DS).DS.'configuration.php')) {
    die('<strong>The file '.$config.' can\'t be found, please review the ROOT constant to match your website root folder</strong>');
}

if (DEBUG===true) {
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

// Ok, password valid, get the requested format : HTML or RAW.  If nothing is specified, HTML will be the default one
$format=strtoupper(filter_input(INPUT_GET, 'format', FILTER_SANITIZE_STRING));
$RAW=($format==='RAW');

// Load the Joomla framework
if (!defined('_JEXEC')) {
    define('_JEXEC', 1);
}
if (!defined('JPATH_BASE')) {
    define('JPATH_BASE', rtrim(ROOT, DS));
}
if (!defined('JPATH_PLATFORM')) {
    define('JPATH_PLATFORM', rtrim(ROOT, DS).DS.'libraries');
}

//include joomla core files (disable errors because Joomla produde WARNINGs and NOTICES)
$error=error_reporting();
error_reporting(0);

if (file_exists($fname = JPATH_BASE.'/includes/defines.php')) {
    require_once($fname);
}
if (file_exists($fname = JPATH_BASE.'/includes/framework.php')) {
    require_once($fname);
}
if (file_exists($fname = JPATH_BASE.'/includes/application.php')) {
    require_once($fname);       // No more present since J3.2
}
if (file_exists($fname = JPATH_BASE.'/libraries/joomla/factory.php')) {
    require_once($fname);
}
if (file_exists($fname = JPATH_BASE.'/libraries/joomla/log/log.php')) {
    require_once($fname);
}

error_reporting($error);
  
require_once(JPATH_BASE.'/configuration.php');

// Start the output, also for RAW output to allow the correct support of UTF8
	  
echo '<!DOCTYPE html><html lang="en">'.
  '<head>'.
  '<meta charset="utf-8"/>'.
  '<meta name="author" content="aeSecure (c) Christophe Avonture" />'.
  '<meta name="robots" content="noindex, nofollow" />'.
  '<meta name="viewport" content="width=device-width, initial-scale=1.0" />'.
  '<meta http-equiv="content-type" content="text/html; charset=UTF-8" />'.
  '<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8;" />';
     
if (!$RAW) {
   // Provide a title to the page
    echo '<title>aeSecure - Show table</title>';
      
   // Add a favicon
    echo '<link href= "data:image/x-icon;base64,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" rel="shortcut icon" type="image/vnd.microsoft.icon"/>';

    echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';
    echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.25.3/css/theme.ice.min.css" rel="stylesheet" media="screen" />';
}
   
echo '</head><body>';
   
if ($RAW!=true) {
    echo '<div style="margin:10px;">';
}

// Run the query and display the table

$db = JFactory::getDBO();
$db->setQuery(SQL);

$rows = $db->loadObjectList();

echo '<table id="tbl" class="table table-striped">';

// Output the list of fields name
$line='';

foreach ($rows[0] as $field => $value) {
    $line.='<th>'.$field.'</th>';   // Add class="filter-select filter-exact" if you wish to provide an exact match and not a free filtering
}

echo '<thead><tr>'.$line.'</tr></thead>';
 
echo '<tbody>';
   
foreach ($rows as $row) {
    $line='';
    foreach ($row as $field => $value) {
        $line.='<td>'.$value.'</td>';
    }
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
